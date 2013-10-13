<?php
$I = new WebGuy($scenario);
$I->wantTo('check frontpage');
$I->amOnPage('/');
$I->see('zhou family gallery');
