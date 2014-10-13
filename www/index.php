<?php
date_default_timezone_set('Asia/Yekaterinburg');
setlocale(LC_ALL, 'ru_RU.UTF-8');

// to_do: vendor path надо выносить в настройки
require_once '../vendor/autoload.php';

// change the following paths if necessary
$yii=dirname(__FILE__).'/../vendor/yiisoft/yii/framework/yii.php';

$localConfig = @include(dirname(__FILE__) . '/localConfig/params.php');
$yiiDebug = (!empty($localConfig) && isset($localConfig['yiiDebug'])) ? $localConfig['yiiDebug'] : false;

$config=dirname(__FILE__).'/protected/config/main.php';

define('ROOT_PATH', dirname(__FILE__));
define('BASE_PATH', dirname(__FILE__). DS . '..');
define('FILES_PATH', dirname(__FILE__). DS . 'files');
define('LIB_PATH', dirname(__FILE__). DS . '..' . DS . 'lib');
define('VENDOR_PATH', dirname(__FILE__). DS . '..' . DS . 'vendor');

defined('YII_DEBUG') or define('YII_DEBUG', $yiiDebug);
defined('YII_DEBUG_LOG') or define('YII_DEBUG_LOG', $yiiDebug);


require_once($yii);
require(dirname(__FILE__) . '/protected/components/ExtendedWebApplication.php');
$app = Yii::createApplication('ExtendedWebApplication', $config);
Yii::app()->VExtension;
Yii::app()->user;
$app->run();
