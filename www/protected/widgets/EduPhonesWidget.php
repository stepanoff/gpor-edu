<?php
/**
 * ввод телефона по маске
 */
class EduPhonesWidget extends CWidget
{
    public $model;
    public $attribute;

    protected $_template = 'phones';

    public function run()
    {
        $this->registerAssets();
        $this->render($this->_template, array(
            'inputId' => CHtml::activeId($this->model, $this->attribute),
            'inputName' => CHtml::activeName($this->model, $this->attribute),
            'model' => $this->model,
            'attribute' => $this->attribute,
        ));
    }

    public function registerAssets () {
        $cs = Yii::app()->clientScript;
        $url = Yii::app()->VExtension->getAssetsUrl();
        $cs->registerScriptFile($url.'/js/jquery.maskedinput-1.3.js', CClientScript::POS_HEAD);
    }

}