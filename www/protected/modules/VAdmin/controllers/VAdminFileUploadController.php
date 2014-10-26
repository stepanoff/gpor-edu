<?php
class VAdminFileUploadController extends VAdminController
{
	public $model = 'VUploadedFile';
	
	public function actionUploadImage ()
	{
		$funcNum = isset($_GET['CKEditorFuncNum']) ? $_GET['CKEditorFuncNum'] : '';
		$CKEditor = isset($_GET['CKEditor']) ?  $_GET['CKEditor'] : '';
		$langCode = isset($_GET['langCode']) ? $_GET['langCode'] : '';

		$error = 'File not found';
		$url = '';
		if ($instance = CUploadedFile::getInstanceByName('upload')) {
			$fileManager = Yii::app()->getComponent('fileManager');
			$file = $fileManager->publishFile($instance->getTempName(), $instance->getExtensionName());
			if (is_object($file) && get_class($file) == 'VImageFile') {
				$modelName = $this->model;
				$model = new $modelName;
				$model->uid = $file->getUid();
				$model->name = $file->getUid();
				$url = $file->getUrl();
				$error = false;
			}
			elseif (is_object($file) && get_class($file) != 'VImageFile') {
				$error = 'Файл должен быть изображением';
				$fileManager = Yii::app()->getComponent('fileManager');
				$fileManager->deleteFileByUid($file->getUid());
			}
			else {
				$error = 'Ошбка загрузки файла';
				//TODO: send notification
			}
        }
		else {
			$error = 'Файл не найден';
		}
		echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '$error');</script>";
		exit;
	}	
	
	protected function editFormElements ($model)
	{
		return array();
	}
}