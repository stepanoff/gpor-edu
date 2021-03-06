<?php
$params = array();
$localConfigFile = dirname(__FILE__).DS.'../../localConfig/params.php';
$localDistConfigFile = dirname(__FILE__).DS.'../../localConfig/params-dist.php';
if (file_exists($localDistConfigFile))
	$localDistConfig = require($localDistConfigFile);
else
	die('local config-dist doesn`t exists at '.$localDistConfigFile."\n");
if (file_exists($localConfigFile))
	$localConfig = require($localConfigFile);
else
	die('local config doesn`t exists at '.$localConfigFile."\n");
$params = array_merge ($localDistConfig, $localConfig);
$emptyKeys = array();
foreach ($params as $k=>$v)
{
	if (is_string($v) && empty($v))
		$emptyKeys[] = $k;
}

/*
if (sizeof($emptyKeys))
{
	echo "Error: params\n".implode("\n", $emptyKeys)."\nrequired";
	die();
}
*/

return array(
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>$params['appName'],
    'runtimePath' => dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'data',
    'language' => 'ru',
    'commandMap' => array(
//        'mailsend'                  => $extDir . DS . 'mailer' . DS . 'MailSendCommand.php',
    ),

	'preload'=>array('log'),

	// autoloading model, component and helper classes
	'import'=>array(
        'application.models.*',
        'application.models.forms.*',
        'application.components.*',
        'application.extensions.*',
        'ext.eauth.*',
        'ext.eauth.services.*',
        'ext.eoauth.*',
        'ext.lightopenid.*',
        'application.helpers.*',
        'application.widgets.*',
    ),

	'params'=>$params,

	'components'=>array(
        'cron' => array(
			'class' => 'CronComponent',
			'logPath' => $params['cronLogsPath'],
		),

        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                array(
                    'class'=>'CFileLogRoute',
                    'levels'=>'error, warning, info',
                ),
                array(
                    'class'=>'CWebLogRoute',
                    'enabled' => YII_DEBUG_LOG,
                    'levels'=>'info, error, warning, trace, profile',
                    'showInFireBug' => false,
                ),
                array(
                    'class'=>'CProfileLogRoute',
                    'enabled' => YII_DEBUG_LOG,
                    'showInFireBug' => false,
                    'report' => 'summary',
                ),
            ),
        ),
        'authManager' => array(
            'class'=>'CDbAuthManager'
        ),
        'fileManager' => array(
            'class' => 'VFileManager',
            'filesPath' => $params['filesPath'],
            'filesUrl' => $params['filesUrl'],
        ),
        'request' => array(
            'class' => 'ExtendedRequestComponent',
            'staticUrl' => $params['staticUrl'],
        ),
        'assetManager' => array(
            'class' => 'CAssetManager',
            'basePath' => dirname(__FILE__).DS.'..'.DS.'..'.DS.'assets',
        ),
        'loid' => array(
            'class' => 'ext.lightopenid.loid',
        ),
        'VExtension' => array (
            'class' => 'ext.VExtension.VExtensionComponent',
            'staticUrl' => $params['baseUrl'],
            'components' => array (
                'auth' => array (
                    'name' => 'vauth',
                    'options' => require(dirname(__FILE__).'/vauth.php'),
                ),
                'user' => array (
                    'name' => 'user',
                    'options' => array(
                        'class'=>'application.extensions.VExtension.VUserComponent',
                        'allowAutoLogin'=>true,
                        'dbDriver'=> 'VMysqlAuthDbDriver',
                        'identityCookie'=>array('domain'=>'.'.$params['domain']),
                        'defaultAvatars' => array (
                            'M' => array (
                                'small' => $params['staticUrl'].'/images/blank.gif',
                                'medium' => $params['staticUrl'].'/images/blank.gif',
                            ),
                            'F' => array (
                                'small' => $params['staticUrl'].'/images/blank.gif',
                                'medium' => $params['staticUrl'].'/images/blank.gif',
                            ),
                            'default' => array (
                                'small' => $params['staticUrl'].'/images/blank.gif',
                                'medium' => $params['staticUrl'].'/images/blank.gif',
                            )
                        ),

                    )
                ),
            ),
            'modules' => array (
            ),
        ),
        'urlManager'=>require(dirname(__FILE__).'/urlManager.php'),

        'cache' => array(
            'class' => 'CFileCache'
        ),
        'db'=>array(
            'connectionString'=>'mysql:'.$params['db_host'].'=localhost;dbname='.$params['db_name'],
            'username'=>$params['db_user'],
            'password'=>$params['db_password'],
            'charset' => 'utf8',
            'autoConnect'=>true,
        ),

        'errorHandler' => array(
            'class' => 'application.components.ExtendedErrorHandler'
        ),
        'localConfig' => array(
            'class' => 'application.components.LocalConfigComponent'
        ),

    ),

    'modules'=>require(dirname(__FILE__).'/modules.php'),

);