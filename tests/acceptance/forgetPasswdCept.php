<?php
$I = new WebGuy($scenario);
$I->wantTo('check password reset page');
$I->amOnPage('/');
$I->seeLink('Forgot your password?');
$I->click('.lost_pwd a');
$I->expect('to be on the password reset page');
$I->seeInCurrentUrl('/site/forget');
$I->see('Your username');
