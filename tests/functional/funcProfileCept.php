<?php
$I = new TestGuy($scenario);
$I->wantTo('check frontpage');
$I->amOnPage('/');
$I->see('zhou family gallery');
