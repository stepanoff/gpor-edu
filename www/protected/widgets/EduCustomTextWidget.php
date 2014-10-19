<?php
/**
 * 
 */
class EduCustomTextWidget extends CWidget
{
    public $model;
    public $attribute;

    protected $_template = 'customText';

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
    }

}