<?php 
date_default_timezone_set('Asia/Yekaterinburg');
setlocale(LC_ALL, 'ru_RU.UTF-8');
defined('STDIN') or define('STDIN', fopen('php://stdin', 'r'));
defined('YII_DEBUG') or define('YII_DEBUG',true);
error_reporting(E_ALL | E_STRICT);

// to_do: vendor path надо выносить в настройки
require_once '../../vendor/autoload.php';

mb_internal_encoding("UTF-8");

// change the following paths if necessary
$yii=dirname(__FILE__).'/../../vendor/yiisoft/yii/framework/yii.php';
$localConfig = @include(dirname(__FILE__) . '/../localConfig/params.php');
$yiiDebug = (!empty($localConfig) && isset($localConfig['yiiDebug'])) ? $localConfig['yiiDebug'] : false;
$config=dirname(__FILE__).'/../protected/config/console.php';

define('ROOT_PATH', dirname(__FILE__));
define('BASE_PATH', dirname(__FILE__). DS . '..' . DS . '..');
define('FILES_PATH', dirname(__FILE__). DS . '..' . DS . 'files');
define('LIB_PATH', dirname(__FILE__). DS . '..' . DS . '..' . DS . 'lib');
define('VENDOR_PATH', dirname(__FILE__). DS . '..' . DS . '..' . DS . 'vendor');
defined('YII_DEBUG_LOG') or define('YII_DEBUG_LOG', $yiiDebug);

// подключаем файл инициализации Yii
require_once($yii);

require(dirname(__FILE__) . '/components/ExtendedConsoleApplication.php');
$yiiConsoleApp = Yii::createApplication('ExtendedConsoleApplication', $config);
Yii::app()->VExtension;
$yiiConsoleApp->run();

?>