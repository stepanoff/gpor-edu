<?php
/**
 * визивиг
 */
class VHtmlCkEditorWidget extends CWidget
{
    public $id = null;
    public $model;
    public $attribute;
    public $description;
    public $htmlOptions = array();
    public $disabled = null;
    public $mode = 'default';
    public $options = array();
    public $css = null;

    private $_defaultOptions = array(
        'width'                    => 790,
        'height'                   => 300,
        'skin'                     => 'bootstrapck',
        'toolbar'                  => array(
            array(
                'Bold', 'Italic', 'Strike', 'Format', '-',
                'Undo', 'Redo', 'Image', '-',
                'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'BulletedList', 'NumberedList', '-',
                'Blockquote', 'Link', 'Unlink', 'Templates', 'Source',
//                'Blockquote', 'Link', 'Unlink', 'Linkpopup', 'Templates', 'Maximize', 'Typograf', 'Source',
            )
        ),
        'allowedContent' => true,
        /*
        'allowedContent'=> array (
            'b strong em i ul ol big small span del ins' => true,
            'h1 h2 h3 p blockquote li' => array (
                'styles' => 'text-align'
            ),
            'table tr th td caption' => true,
            'a' => array ('attributes' => '!href,target' ),
            'img' => array (
                'attributes' => '!src,alt',
                'styles' => 'width,height',
                'classes' => 'left,right'
            )
        ),
        */
        'extraPlugins'             => 'templates',
//        'extraPlugins'             => 'format,fakeckobject,webkitfix,typograf,linkpopup',
        'templates_replaceContent' => false,
        'forcePasteAsPlainText'    => true,
        'lang'                     => 'ru',
        'format_tags'              => 'h2;h3;h4;h5;h6',
        'filebrowserBrowseUrl' => "",
        'filebrowserUploadUrl' => "/VAdmin/VAdminFileUpload/uploadImage/",
    );

    public function init () {
        $this->id = $this->id !== null ? $this->id : (isset($this->htmlOptions['id']) ? $this->htmlOptions['id'] : get_class($this->model) . '_' . $this->attribute);
    }

    public function run()
    {
        $this->registerAssets();

        if ($this->disabled !== null)
            $this->htmlOptions['disabled'] = $this->disabled;
        $this->htmlOptions['id'] = $this->id;

        echo CHtml::activeTextArea($this->model, $this->attribute, $this->htmlOptions);
    }

    public function registerAssets () {
        $cs = Yii::app()->clientScript;
        $url = Yii::app()->VExtension->getAssetsUrl();

        $cs->registerScriptFile($url . '/js/ckeditor/ckeditor.js', CClientScript::POS_HEAD);

        $config = CJSON::encode(array_merge($this->_defaultOptions, $this->options));
        $script = '';
        if (!empty($this->css)) {
            $script .= '
                CKEDITOR.config.filebrowserBrowseUrl = "/site/browser/";
                CKEDITOR.config.filebrowserUploadUrl = "/site/upload/";
                CKEDITOR.config.contentsCss =
                    [
                        "' . $url . '/js/ckeditor/' . $this->css . '"
                    ];
                    ';
        }
        $script .= '
            var editor' . $this->id . ' = CKEDITOR.replace("' . $this->id . '",' . $config . ');
        ';
        $cs->registerScript('ckeditor_' . $this->id, $script, CClientScript::POS_END);
    }

}