<?php
class VExtensionComponent extends CComponent {

    public $extensionAlias = 'application.extensions.VExtension';
    public $modules = array ();
    public $components;
    public $staticUrl = '/';
    public $useBootstrap = true;

    protected $assetsPath = '';
    protected $assetsUrl = '';

    protected $mapsDefaultLatitude = '56.837982';
    protected $mapsDefaultLongitude = '60.59734';
    protected $mapsDefaultZoom = '12';
    protected $mapsDefaultCity = 'Екатеринбург';

    public function init () {
        Yii::import($this->extensionAlias.'.*');
        Yii::import($this->extensionAlias.'.models.*');
        Yii::import($this->extensionAlias.'.widgets.*');
        Yii::import($this->extensionAlias.'.validators.*');
        Yii::import($this->extensionAlias.'.controllers.*');
        Yii::import($this->extensionAlias.'.widgets.htmlextended.*');
        Yii::import($this->extensionAlias.'.helpers.*');

        if ($this->components) {
            foreach ($this->components as $componentName => $comp) {
                Yii::app()->setComponents(array(
                    $comp['name']=>$comp['options']
                ));
            }
        }

        $this->assetsPath = dirname(__FILE__).DIRECTORY_SEPARATOR.'assets';
        $this->assetsUrl = $this->staticUrl.Yii::app()->assetManager->publish($this->assetsPath, false, -1, YII_DEBUG);
    }

    public function getAssetsPath () {
        return $this->assetsPath;
    }

    public function getAssetsUrl () {
        return $this->assetsUrl;
    }

    public function getViewsAlias () {
        return $this->extensionAlias . '.views';
    }

    public function registerBootstrap () {
        if ($this->useBootstrap) {
            $assetsPath = VENDOR_PATH . DIRECTORY_SEPARATOR . 'twitter' . DIRECTORY_SEPARATOR . 'bootstrap' . DIRECTORY_SEPARATOR . 'dist';
            $url = Yii::app()->assetManager->publish($assetsPath, false, -1, YII_DEBUG);
            $cs = Yii::app()->clientScript;

            $cs->registerCssFile($url.'/css/bootstrap.css');
            $cs->registerCssFile($url.'/css/bootstrap-responsive.css');
            //$cs->registerCssFile($url.'/css/docs.css');
            $cs->registerScriptFile($url.'/js/bootstrap.min.js', CClientScript::POS_HEAD);
        }
    }

    public function registerGlyphicons () {
        $cs = Yii::app()->clientScript;
        $url = $this->getAssetsUrl();
        $cs->registerCssFile($url.'/css/glyphicons.css');
    }

    // todo: выложить в отдельный компонент
    public function getMapsConfig () {
        return array(
            'defaultLatitude' => $this->mapsDefaultLatitude,
            'defaultLongitude' => $this->mapsDefaultLongitude,
            'defaultZoom' => $this->mapsDefaultZoom,
            'defaultCity' => $this->mapsDefaultCity,
        );
    }

    public function registerMaps () {
        $cs = Yii::app()->clientScript;

        $maps = array (
            'defaultLatitude' => '',
            'defaultLongitude' => '',
            'defaultZoom' => '12',
            'city' => 'Екатеринбург',
        );
        $url = 'http://api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU&ns=YMaps&mode=release';

        $script = "
            $.data(document, 'yandexDefaultLatitude', '". $this->mapsDefaultLatitude . "');
            $.data(document, 'yandexDefaultLongitude', '". $this->mapsDefaultLongitude . "');
            $.data(document, 'yandexDefaultZoom', '". $this->mapsDefaultZoom . "');
            $.data(document, 'portal.city', '". $this->mapsDefaultCity . "');
        ";
        $cs->registerScriptFile($url, CClientScript::POS_HEAD);
        $cs->registerScript('mapsData', $script, CClientScript::POS_END);
    }
}
?>
