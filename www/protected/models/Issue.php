<?php
class Issue extends CActiveRecord
{
    const STATUS_NEW = 10;
    const STATUS_HOLD = 15;
    const STATUS_PROCESS = 20;
    const STATUS_REVIEW = 30;
    const STATUS_SOLVED = 40;
    const STATUS_PRODUCTION = 50;

    const ACTION_NEW = 10;
    const ACTION_PROCESS = 20;
    const ACTION_REVIEW = 30;
    const ACTION_SOLVED = 40;
    const ACTION_CLOSED = 50;
    const ACTION_REOPEN = 60;

    public $labels = null;
    public $_collaborators = null;

    private $_devIssue = null;
    private $_clientIssue = null;

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'issues';
    }

    public static function statusTypes ($source = false)
    {
        return array (
            self::STATUS_NEW => 'New',
            self::STATUS_HOLD => 'Hold',
            self::STATUS_PROCESS => 'Process',
            self::STATUS_REVIEW => 'Review',
            self::STATUS_SOLVED => 'Solved',
            self::STATUS_PRODUCTION => 'Production',
        );
    }

    public function attributeLabels()
    {
        return array(
            'title' => 'Заголовок',
            'body' => 'Описание',
            'status' => 'Статус',
            'clientSource' => 'Источник постановщика задач',
            'clientSourceId' => 'id в источнике постановщика задач',
            'devSource' => 'Источник в системе разработке',
            'devSourceId' => 'id в в системе разработки',
        );
    }

    public function rules()
    {
        return array(
            array('title', 'required'),
            array('title, body, status, clientSource, clientSourceId, devSource, devSourceId, requesterId, assigneeId, deadlineDate, typeId, priority, priorityId, createDate, closedDate, milestoneId', 'safe')
        );
    }

    public function relations()
    {
        return array(
            'type' => array(self::BELONGS_TO, 'IssueType', 'typeId'),
            'priorityObj' => array(self::BELONGS_TO, 'Priority', 'priorityId'),
            'org' => array(self::BELONGS_TO, 'RequesterOrg', 'orgId'),
            'developer' => array(self::BELONGS_TO, 'Developer', 'assigneeId'),
            'collaborators' => array(self::MANY_MANY, 'Developer', 'issuecollaborators(issueId, developerId)'),
            'collaborations' => array(self::HAS_MANY, 'IssueCollaborator', 'issueId'),
        );
    }

    public function getDevIssue()
    {
        if ($this->_devIssue === null) {
            $this->_devIssue = false;
            if ($this->devSourceId) {
                // todo: при нескольких драйверов поменять
                $driver = Yii::app()->senter->getDevDriver();
                $this->_devIssue = $driver->getIssueById($this->devSourceId);
            }
        }
        return $this->_devIssue;
    }

    public function getClientIssue()
    {
        if ($this->_clientIssue === null) {
            $this->_clientIssue = false;
            if ($this->clientSourceId) {
                $driver = Yii::app()->senter->getClientDriver($this->clientSource);
                $this->_clientIssue = $driver->getIssueById($this->clientSourceId);
            }
        }
        return $this->_clientIssue;
    }

    public function scopes()
    {
        $alias = $this->getTableAlias();
        return array(
            'inProcess' => array(
                'condition' => $alias.'.status IN ('. self::STATUS_PROCESS .', '. self::STATUS_NEW .')',
            ),
            'notClosed' => array(
                'condition' => $alias.'.status IN ('. self::STATUS_PROCESS .', '. self::STATUS_NEW .', '. self::STATUS_REVIEW .')',
            ),
            'solved' => array(
                'condition' => $alias.'.status IN ('. self::STATUS_SOLVED .')',
            ),
            'closed' => array(
                'condition' => $alias.'.status IN ('. self::STATUS_PRODUCTION .')',
            ),
            'inDevelopment' => array(
                'condition' => $alias.'.devSourceId > 0',
            ),
            'inSupport' => array(
                'condition' => $alias.'.milestoneId = 0',
            ),
            'orderSolvedDate' => array(
                'order' => $alias.'.solvedDate DESC',
            ),
        );
    }

    public function isNew ()
    {
        return $this->status == self::STATUS_NEW;
    }

    public function isInProcess ()
    {
        return $this->status == self::STATUS_PROCESS;
    }

    public function isOnReview ()
    {
        return $this->status == self::STATUS_REVIEW;
    }

    public function isSolved ()
    {
        return $this->status == self::STATUS_SOLVED;
    }

    public function isOnProduction ()
    {
        return $this->staus == self::STATUS_PRODUCTION;
    }

    public function hasDeadline ()
    {
        return $this->deadlineDate != '0000-00-00 00:00:00';
    }

    public function isReacted ()
    {
        if ($this->deadlineDate != '0000-00-00 00:00:00' && strtotime($this->processDate) <= strtotime($this->deadlineDate))
            return true;
        return false;
    }

    public function isSuccessful ()
    {
        if ($this->deadlineDate != '0000-00-00 00:00:00' && strtotime($this->solvedDate) <= strtotime($this->deadlineDate))
            return true;
        return false;
    }

    public function orderPriority()
    {
        $alias = $this->getTableAlias();
        $this->getDbCriteria()->mergeWith(array(
            'select' => $alias.'.*, if ('.$alias.'.deadlineDate = "0000-00-00 00:00:00", 0, 1) as `hasPriority` ',
            'order' => 'hasPriority DESC, '.$alias.'.deadlineDate, '.$alias.'.priority DESC',
        ));
        return $this;
    }

    public function byClientSource($source)
    {
        $alias = $this->getTableAlias();
        $this->getDbCriteria()->mergeWith(array(
            'condition' => $alias.'.clientSource = "'.$source.'"',
        ));
        return $this;
    }

    public function byStatus($status)
    {
        $alias = $this->getTableAlias();
        $this->getDbCriteria()->mergeWith(array(
            'condition' => $alias.'.status = "'.$status.'"',
        ));
        return $this;
    }

    public function byDevSource($source)
    {
        $alias = $this->getTableAlias();
        $this->getDbCriteria()->mergeWith(array(
            'condition' => $alias.'.devSource = "'.$source.'"',
        ));
        return $this;
    }

    public function byDevId($id)
    {
        $alias = $this->getTableAlias();
        $this->getDbCriteria()->mergeWith(array(
            'condition' => $alias.'.devSourceId = "'.$id.'"',
        ));
        return $this;
    }

    public function byMilestoneId($id)
    {
        $alias = $this->getTableAlias();
        $this->getDbCriteria()->mergeWith(array(
            'condition' => $alias.'.milestoneId = "'.$id.'"',
        ));
        return $this;
    }

    public function byClientId($id)
    {
        $alias = $this->getTableAlias();
        $this->getDbCriteria()->mergeWith(array(
            'condition' => $alias.'.clientSourceId = "'.$id.'"',
        ));
        return $this;
    }

    public function bySolvedMonth($month, $year)
    {
        $toDate = ($month == 12 ? ($year+1).'-01-01' : $year.'-'.($month+1).'-01').' 00:00:00';
        $fromDate = $year.'-'.($month).'-01'.' 00:00:00';
        $alias = $this->getTableAlias();
        $this->getDbCriteria()->mergeWith(array(
            'condition' => $alias.'.solvedDate >= "'.$fromDate.'" AND '.$alias.'.solvedDate <= "'.$toDate.'"',
        ));
        return $this;
    }

    public function byMonth($month, $year)
    {
        $toDate = ($month == 12 ? ($year+1).'-01-01' : $year.'-'.($month+1).'-01').' 00:00:00';
        $fromDate = $year.'-'.($month).'-01'.' 00:00:00';
        $alias = $this->getTableAlias();
        $this->getDbCriteria()->mergeWith(array(
            'condition' => $alias.'.createDate >= "'.$fromDate.'" AND '.$alias.'.createDate <= "'.$toDate.'"',
        ));
        return $this;
    }

    public function byCollaborators($collaboratorsIds)
    {
        $this->with(array(
            'collaborators' => array(
                'select'	=> 'collaborators.*',
                'together'	=> true,
                'alias'		=> 'collaborators',
                'group'=>'t.id'
            )))
            ->getDbCriteria()
            ->addInCondition('collaborators.id', $collaboratorsIds);

        return $this;
    }


    protected function beforeSave()
    {
        if (!$this->status)
            $this->status = self::STATUS_NEW;
        if (!$this->createDate || $this->createDate == '0000-00-00 00:00:00')
            $this->createDate = date('Y-m-d G:i:s', time());

        if ($this->priorityId) {
            $priority = Priority::model()->findByPk($this->priorityId);
            if ($priority) {
                $this->priority = $priority->number;
            }
            else {
                $this->priority = 0;
            }
        }

        return parent::beforeSave();
    }

    protected function afterDelete()
    {
        $senter = Yii::app()->getComponent('senter');
        if ($this->devSourceId && $senter) {
            $senter->removeDevIssueByIssue($this);
        }
        if ($this->clientSourceId && $senter) {
            $senter->removeClientIssueByIssue($this);
        }

        return parent::afterDelete();
    }

    protected function afterSave()
    {
        if ($this->_collaborators !== null) {
            $this->_collaborators = is_array($this->_collaborators) ? $this->_collaborators : array($this->_collaborators);
            IssueCollaborator::model()->byIssueId($this->id)->delete();
            foreach ($this->_collaborators as $collaborator) {
                $c = new IssueCollaborator();
                $c->setAttributes($collaborator);
                $c->issueId = $this->id;
                $c->save();
            }
        }

        return parent::afterSave();
    }

}