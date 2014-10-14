<?php
class VAdminFilterBuilderWidget extends CWidget
{
	public $form;

    public $defaultOptions = array(
		'action'=>'',
		'method'=>'get',
        'htmlOptions' => array('class' => ''),
	);
	public $renderSafeAttributes = false;

	public function run()
	{
		$form = $this->form;
        if (!$form)
            return;

        if (!count($form->model->getFormElements()))
            return;

        $this->registerAssets();

        echo CHtml::openTag('div', array('class' => 'navbar'));
        echo CHtml::openTag('div', array('class' => 'navbar-inner', 'style' => 'padding-top: 60px;'));

        $form->activeForm = array_merge($this->defaultOptions, $form->activeForm );
        $form->formInputLayout = '<div class="col-xs-6 col-md-3"><div class="form-group">{label}{input}{hint}<div class="form-row__error">{error}</div></div></div>';
        $form->buttonsLayout = '<div class="col-xs-3 col-md-3"><div class="row">{buttons}</div></div></div>';
        $form->buttonLayout = '<div class="col-xs-12 col-md-6"><div class="form-group"><label for="">&nbsp;</label>{button}</div></div>';
        $form->formInputsLayout = '<div class="row"><div class="col-xs-9 col-md-9"><div class="row">{elements}</div></div>';
        $form->formErrorLayout = '{error}';

        echo $form->render();

        echo CHtml::closeTag('div');
        echo CHtml::closeTag('div');
	}

    public function registerAssets () {

        /*
        $cs = Yii::app()->clientScript;
        $url = Yii::app()->VExtension->getAssetsUrl();
        $cs->registerCssFile($url.'/css/vform.css');
        $cs->registerScriptFile($url.'/js/jquery.form.js', CClientScript::POS_HEAD);
        */
    }
}
?>