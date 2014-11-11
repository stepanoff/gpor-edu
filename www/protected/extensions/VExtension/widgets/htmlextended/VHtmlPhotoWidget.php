<?php
/**
 * PhotoWidget class file.
 *
 * @copyright Copyright &copy; 2008-2010 stepanoff
 */

class VHtmlPhotoWidget extends CWidget
{
	public $model;
	public $attribute;
	public $htmlOptions = array();
	
	/**
	 * 
	 */
	public function run()
	{
		$attr = $this->attribute;
		$attr1 = '_'.$this->attribute;
		$attr2 = '_'.$this->attribute.'_delete';
		if (!empty($this->model->$attr))
		{
			$file = Yii::app()->fileManager->getFile($this->model->$attr);
			if ($file) {
				echo '<div class="form-photo"><img src="'. $file->getThumbUrl(100, 100).'"/></div>';
			}
		}
		echo '<div>';
		echo CHtml::activeFileField($this->model, $attr1);
		echo '</div>';
		echo '<div class="checkbox" style="padding-left: 20px;"><label>';
		echo CHtml::activeCheckbox($this->model, $attr2);
		echo ' удалить';
		echo '</label></div>';
		
	}
	
}