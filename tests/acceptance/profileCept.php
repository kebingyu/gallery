<?php
$I = new WebGuy($scenario);
$I->wantTo('check profile');
$I->amOnPage('/');
$I->sendAjaxPostRequest('/services/login', array(
	'UserModel[username]' => 'kyu',
	'UserModel[password]' => '123456',
));
$I->amOnPage('/profile/index');
$I->see('kyu\'s profile');
