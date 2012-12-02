<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name' => 'Gallery',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.components.widgets.*'
	),

	//'defaultController' => 'post',

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
			/*
			'class' => 'ext.minScript.components.ExtMinScript',
			'minScriptControllerId' => 'min',
			'minScriptCacheId' => 'cache',
			'minScriptDebug' => false,
			//'minScriptLmCache' => 700,
			'minScriptLmCache' => false,
			'minScriptUrlMap' => array(),
			 */
			'packages' => array(
				'main' => array(
					'baseUrl' => '/',
					'css' => array(
						'css/zebra_dialog.css', 
						'css/all.css',
					),
					'js' => array(
						'js/jquery-1.8.1.min.js',
						'js/jquery-ui-1.8.23.custom.min.js',
						'js/vendors/jquery.yiiactiveform.js',
						'js/zebra_dialog.js',
						'js/all.js',
					),
				),
				'formly'=>array(
					'baseUrl'=> '/',   
					'css'    => array( 'css/formly.css' ),
					'js'     => array( 'js/formly.js' ),
				),
				'chosen'=>array(
					'basePath'=> '/', 
					'css'    => array( 'css/chosen.css' ),
					'js'     => array( 'js/chosen.jquery.min.js' ),
				),
				'upload'=>array(
					'basePath'=> '/', 
					'css'     => array(
						'css/upload/jquery.fileupload-ui.css',
						'css/upload/upload_layout.css'
					),
				),
				'tabify'=>array(
					'basePath'=> '/', 
					'js'     => array( 'js/jquery.tabify.js' ),
				),
			),
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
			'connectionString' => 'mysql:host=localhost;dbname=gallery',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => 'zhandxu5',
			'charset' => 'utf8',
			// turn on schema caching to improve performance
			// 'schemaCachingDuration' => 3600,
		),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction' => 'site/error',
		),
		'urlManager'=>array(
			'urlFormat' => 'path',
			'rules'=>array(
				'<controller:\w+>/<action:\w+>' => '<controller>/<action>',
				'<controller:\w+>' => '<controller>/index',
				'' => 'site/index',
			),
			'useStrictParsing' => true,
			'showScriptName' => false,
		),
		'log'=>array(
			'class' => 'CLogRouter',
			'routes'=>array(
				array(
					'class' => 'CFileLogRoute',
					'levels' => 'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class' => 'CWebLogRoute',
				),
				 */
			),
		),
	),
	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params' => array(
		'environment' => 'dev',
		'adminEmail' => '',
		'reCaptchaPub' => '',
		'reCaptchaPriv' => '',
		'BaseUrl' => 'http://yii.kyutest.com',
		'RootDir' => '/var/www/sites/yii/gallery',
	),
	'controllerMap' => array(
		'min' => array(
			'class' => 'ext.minScript.controllers.ExtMinScriptController',
		),
	),
);
