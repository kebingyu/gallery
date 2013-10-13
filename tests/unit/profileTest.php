<?php
use Codeception\Util\Stub;

class profileTest extends \Codeception\TestCase\Test
{
	/**
	 * @var \CodeGuy
	 */
	protected $codeGuy;

	protected function _before()
	{
		include '/var/www/sites/yii/gallery/protected/models/UserModel.php';
	}

	protected function _after()
	{
	}

	// tests
	/*
	public function testMe()
	{
		$I->seeInDatabase('users', array(
			'username' => 'kyu',
		));
	}
	 */
	function testSavingUser()
	{
		$user = new UserModel();
		$user->username = 'Miles';
		$user->password = 'test';
		$user->save();
		$this->assertEquals('Miles', $user->username);
		$this->codeGuy->seeInDatabase('user', array(
			'username' => 'Miles', 
			//'password' => 'test',
		));
	}

}
