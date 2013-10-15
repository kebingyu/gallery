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
	}

	protected function _after()
	{
	}

	// tests
	function testSavingUser()
	{
		$user = new UserModel();
		$user->username = 'Miles';
		$user->password = 'test';
		$user->save();
		$this->assertEquals('Miles', $user->username);
		$this->codeGuy->seeInDatabase('user', array(
			'username' => 'Miles', 
		));
	}

}
