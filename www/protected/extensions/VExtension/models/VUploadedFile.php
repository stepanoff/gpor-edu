<?php
/**
 */
class VUploadedFile extends CActiveRecord
{
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'vuploadedfiles';
	}

    public function rules()
    {
        return array(
        	array('uid', 'required'),
			array('uid', 'safe'),
		);
    }
	
    public function attributeLabels()
    {
        return array(
        	'uid' => 'UID файла',
        	'name' => 'Название файла',
		);
    }	
    
	protected function beforeSave()
	{
		return parent::beforeSave();
    }

    protected function afterSave()
    {
		return parent::afterSave();
    }    
    
    protected function afterDelete()
    {
		$fileManager = Yii::app()->getComponent('fileManager');
		$fileManager->deleteFileByUid($this->uid);
		$this->uid = '';
    	parent::afterDelete();
    }    
    
}