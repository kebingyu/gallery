<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Gallery',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.components.widgets.*'
	),

	//'defaultController'=>'post',

	// application components
	'components'=>array(
			/*
		'memcached' => array(
			'class' => 'CMemCache',
			'useMemcached' => true,
			'servers' => array(
				array(
					'host' => '127.0.0.1',
					'port' => 0,
				),
			),
		),
		'cache' => array(
			'class' => 'CApcCache',
		),
			 */
		'clientScript' => array(
			'class' => 'ext.minScript.components.ExtMinScript',
			'minScriptControllerId' => 'min',
			'minScriptCacheId' => 'cache',
			'minScriptDebug' => false,
			//'minScriptLmCache' => 700,
			'minScriptLmCache' => false,
			'minScriptUrlMap' => array(),
		),
		'user'=>array(
			'class' => 'WebUser',
			// enable cookie-based authentication
			'allowAutoLogin' => true,
		),
		'session' => array (
			'sessionName' => 'Zhou_Gallery',
			'savePath' => '/var/www/sites/yii/gallery/protected/runtime/sessions',
		),
		'db'=>array(
			'class' => 'CDbConnection',
			'connectionString' => 'mysql:host=localhost;dbname=test',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => 'zhandxu5',
			'charset' => 'utf8',
			// turn on schema caching to improve performance
			// 'schemaCachingDuration' => 3600,
		),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
				'<action:\w*>' => 'site/<action>',
			),
			'useStrictParsing' => true,
			'showScriptName' => false,
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				 */
			),
		),
	),
	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params' => array(
		'environment' => 'dev',
		// this is used in contact page
		'adminEmail' => 'kyu@internetbrands.com',
		'emails' => array('kyu@internetbrands.com'),
		'reCaptchaPub' => '6LdtdtYSAAAAADK0a0yTIckKUBaz0TtFG2INoBzq',
		'reCaptchaPriv' => '6LdtdtYSAAAAAMj42UA0piZpICMh7C8LGCTucznO',
	),
	'controllerMap' => array(
		'min' => array(
			'class' => 'ext.minScript.controllers.ExtMinScriptController',
		),
	),
);
