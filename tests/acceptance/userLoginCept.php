<?php
// for selenium
$I = new WebGuy($scenario);
$I->wantTo('check login with selenium');
$I->amOnPage('/');
$I->fillField('UserModel[username]', 'kyu');
$I->fillField('UserModel[password]', '123456');
$I->click('#btn_login');
$I->see('kyu');
//$I->see('kyu\'s profile');
