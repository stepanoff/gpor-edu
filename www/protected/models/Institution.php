<?php
class Institution extends CActiveRecord
{
    const STATUS_NEW = 10;
    const STATUS_HIDDEN = 40;
    const STATUS_VISIBLE = 50;

    public $_addresses = null;
    public $_emails = null;
    public $_phones = null;
    public $_customText = null;

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'institutions';
    }

    public static function statusTypes ($source = false)
    {
        return array (
            self::STATUS_NEW => 'Новое',
            self::STATUS_HIDDEN => 'Скрыто',
            self::STATUS_VISIBLE => 'На сайте',
        );
    }

    public function attributeLabels()
    {
        return array(
            'title' => 'Название',
            'fullTitle' => 'Полное название',
            'type' => 'Тип',
            'logo' => 'Логотип',
            'status' => 'Статус',
            'priority' => 'Приоритет',
            'emails' => 'Почтовые адреса',
            'phones' => 'Телефоны',
            'addresses' => 'Адреса',
            'announce' => 'Краткий текст',
            'text' => 'Подробное описание',
            'customText' => 'Прочее описание',
        );
    }

    public function rules()
    {
        return array(
            array('title', 'required'),
            array('title, fullTitle, type, logo, status, clientSource, priority, emails, phones, addresses, announce, text, customText', 'safe')
        );
    }

    public function relations()
    {
        return array(
        );
    }

    public function getAddressesArray()
    {
        if ($this->_addresses === null) {
            $this->_addresses = array();
            if ($this->addresses) {
                $this->_addresses = CJSON::decode($this->addresses);
            }
        }
        return $this->_addresses;
    }

    public function getPhonesArray()
    {
        if ($this->_phones === null) {
            $this->_phones = array();
            if ($this->phones) {
                $this->_phones = CJSON::decode($this->phones);
            }
        }
        return $this->_phones;
    }

    public function getEmailsArray()
    {
        if ($this->_emails === null) {
            $this->_emails = array();
            if ($this->emails) {
                $this->_emails = CJSON::decode($this->emails);
            }
        }
        return $this->_emails;
    }

    public function getCustomTextArray()
    {
        if ($this->_customText === null) {
            $this->_customText = array();
            if ($this->customText) {
                $this->_customText = CJSON::decode($this->customText);
            }
        }
        return $this->_customText;
    }

    public function scopes()
    {
        $alias = $this->getTableAlias();
        return array(
            'onSite' => array(
                'condition' => $alias.'.status IN ('. self::STATUS_VISIBLE .')',
            ),
            'orderDefault' => array(
                'order' => $alias.'.title ASC',
            ),
        );
    }

    public function orderPriority()
    {
        $alias = $this->getTableAlias();
        $this->getDbCriteria()->mergeWith(array(
            'order' => $alias.'.priority DESC',
        ));
        return $this;
    }

    /*
    public function byClientSource($source)
    {
        $alias = $this->getTableAlias();
        $this->getDbCriteria()->mergeWith(array(
            'condition' => $alias.'.clientSource = "'.$source.'"',
        ));
        return $this;
    }
    */


    protected function beforeSave()
    {
        if (!$this->status)
            $this->status = self::STATUS_NEW;

        $this->addresses = CJSON::encode($this->getAddressesArray());
        $this->emails = CJSON::encode($this->getEmailsArray());
        $this->phones = CJSON::encode($this->getPhonesArray());
        $this->customText = CJSON::encode($this->getCustomTextArray());

        return parent::beforeSave();
    }

    protected function afterDelete()
    {
        return parent::afterDelete();
    }

    protected function afterSave()
    {
        return parent::afterSave();
    }

}