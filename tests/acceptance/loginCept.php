<?php
$I = new WebGuy($scenario);
$I->wantTo('check login');
$I->amOnPage('/');
$I->sendAjaxPostRequest('/services/login', array(
	'UserModel[username]' => 'kyu',
	'UserModel[password]' => '123456',
));
$I->see('1');
