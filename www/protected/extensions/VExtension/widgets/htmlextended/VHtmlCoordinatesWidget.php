<?php
class VHtmlCoordinatesWidget extends CWidget
{
    public $model;
    public $attribute;
    public $defaultZoom = null;
    public $textField = null;

    public function run()
    {
        if (empty($this->model) || !is_object($this->model)) {
            echo 'Error: model incorrect';
            return;
        }

        $defaultZoom = $this->defaultZoom !== null ? $this->defaultZoom : 12;
        $this->render('coordinates', array(
            'uniqId'            => rand(0, getrandmax()),
            'model'             => $this->model,
            'modelName'         => get_class($this->model),
            'defaultLatitude'   => Yii::app()->params['defaultLatitude'],
            'defaultLongitude'  => Yii::app()->params['defaultLongitude'],
            'defaultZoom'       => $defaultZoom,
            'textField'         => $this->attribute,
            'inputId'       => CHtml::activeId($this->model, $this->attribute),
        ));
    }

}
