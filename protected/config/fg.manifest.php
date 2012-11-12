<?php

$sRoot = '/var/www/sites/mymvctest';
$sBaseUrl = 'http://www.kyutest.com';

$aManifest =  array(
	'Site' => array(
		'Name' 				=>	'My mvctest',
		'Domain'			=> 	'kyutest.com',
		'RootDir' 			=> 	$sRoot,
		'BaseUrl' 			=>  $sBaseUrl,
		'ErrorLogPath'		=> 	$sRoot . '/logs/execption_logs/error',
		'AssetPath' 		=>  $sRoot . '/privacy',
		'AssetPathSecure' 	=>  $sRoot . '/privacy',
		'ImagePath' 		=>  $sRoot . '/privacy/photo/',
		'ImagePathSecure' 	=>  $sRoot . '/privacy/photo/',
	),
	'Applications'		=>	array(
		array(
			'Name' 			=> 	'Homepage',
			'Namespace'		=> 	'home',
			'AppDir'		=>	'home',
			'RoutingFile'	=>	$sRoot . '/applications/home/conf/routing.xml',
			'IniFile'		=>	$sRoot . '/conf/fg_site.ini',
		),
		array(
			'Name' 			=> 	'Album Display and Edit',
			'Namespace'		=> 	'album',
			'AppDir'		=>	'album',
			'RoutingFile'	=>	$sRoot . '/applications/album/conf/routing.xml',
			'IniFile'		=>	$sRoot . '/conf/fg_site.ini',
		),
		array(
			'Name' 			=> 	'Misc Services',
			'Namespace'		=> 	'services',
			'AppDir'		=>	'services',
			'RoutingFile'	=>	$sRoot . '/applications/services/conf/routing.xml',
			'IniFile'		=>	$sRoot . '/conf/fg_site.ini',
		),
		array(
			'Name' 			=> 	'User Profile',
			'Namespace'		=> 	'profile',
			'AppDir'		=>	'profile',
			'RoutingFile'	=>	$sRoot . '/applications/profile/conf/routing.xml',
			'IniFile'		=>	$sRoot . '/conf/fg_site.ini',
		),
	),
);
