<?php
class Institution extends CActiveRecord
{
    const STATUS_NEW = 10;
    const STATUS_HIDDEN = 40;
    const STATUS_VISIBLE = 50;

    protected $__addresses = null;
    protected $__emails = null;
    protected $__phones = null;
    protected $__customText = null;

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
            '_emails' => 'Почтовые адреса',
            'phones' => 'Телефоны',
            '_phones' => 'Телефоны',
            'addresses' => 'Адреса',
            '_addresses' => 'Адреса',
            'announce' => 'Краткий текст',
            'text' => 'Подробное описание',
            'customText' => 'Прочее описание',
            '_customText' => 'Прочее описание',
        );
    }

    public function rules()
    {
        return array(
            array('title', 'required'),
            array('title, fullTitle, type, logo, status, clientSource, priority, emails, phones, addresses, announce, text, customText, _addresses, _emails, _phones, _customText', 'safe')
        );
    }

    public function relations()
    {
        return array(
        );
    }

    public function get_addresses()
    {
        if ($this->__addresses === null) {
            $this->__addresses = array();
            if ($this->addresses) {
                $tmp = CJSON::decode($this->addresses);
                if ($tmp) {
                    $_tmp = array();
                    foreach ($tmp as $key => $value) {
                        if (empty($value['address'])) {
                            continue;
                        }
                        $_tmp[] = $value;
                    }
                    $this->__addresses = $_tmp;
                }
            }
        }
        return $this->__addresses;
    }

    public function set_addresses($val)
    {
        $this->__addresses = $val;
    }

    public function get_phones()
    {
        if ($this->__phones === null) {
            $this->__phones = array();
            if ($this->phones) {
                $tmp = CJSON::decode($this->phones);
                if ($tmp) {
                    $_tmp = array();
                    foreach ($tmp as $key => $value) {
                        if (empty($value['phone'])) {
                            continue;
                        }
                        $_tmp[] = $value;
                    }
                    $this->__phones = $_tmp;
                }
            }
        }
        return $this->__phones;
    }

    public function set_phones($val)
    {
        $this->__phones = $val;
    }

    public function get_emails()
    {
        if ($this->__emails === null) {
            $this->__emails = array();
            if ($this->emails) {
                $tmp = CJSON::decode($this->emails);
                if ($tmp) {
                    $_tmp = array();
                    foreach ($tmp as $key => $value) {
                        if (empty($value['email'])) {
                            continue;
                        }
                        $_tmp[] = $value;
                    }
                    $this->__emails = $_tmp;
                }
            }
        }
        return $this->__emails;
    }

    public function set_emails($val)
    {
        $this->__emails = $val;
    }

    public function get_customText()
    {
        if ($this->__customText === null) {
            $this->__customText = array();
            if ($this->customText) {
                $tmp = CJSON::decode($this->customText);
                if ($tmp) {
                    $_tmp = array();
                    foreach ($tmp as $key => $value) {
                        if (empty($value['text'])) {
                            continue;
                        }
                        $_tmp[] = $value;
                    }
                    $this->__customText = $_tmp;
                }
            }
        }
        return $this->__customText;
    }

    public function set_customText($val)
    {
        $this->__customText = $val;
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

        $this->addresses = CJSON::encode($this->get_addresses());
        $this->emails = CJSON::encode($this->get_emails());
        $this->phones = CJSON::encode($this->get_phones());
        $this->customText = CJSON::encode($this->get_customText());

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