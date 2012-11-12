<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	private $_id;

	const ERROR_USER_INACTIVE = 3;

	/**
	 * Authenticates a user.
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		$oUser = UserModel::model()->find('LOWER(username)=?', array(
			strtolower($this->username),
		));
		if($oUser === null) {
			$this->errorCode = self::ERROR_USERNAME_INVALID;
			$this->errorMessage = 'No match record found.';
		} else if(!$oUser->is_active) {
			$this->errorCode = self::ERROR_USER_INACTIVE;
			$this->errorMessage = 'This user is NOT active. Please contact the webmaster.';
		} else if(!$oUser->validatePassword($this->password, $oUser->password)) {
			$this->errorCode = self::ERROR_PASSWORD_INVALID;
			$this->errorMessage = 'No match record found.';
		} else {
			$this->_id = $oUser->id;
			$this->username = $oUser->username;
			$this->errorCode = self::ERROR_NONE;
		}
		return $this->errorCode == self::ERROR_NONE;
	}

	/**
	 * @return integer the ID of the user record
	 */
	public function getId()
	{
		return $this->_id;
	}

	public function setId($id)
	{
		$this->_id = is_numeric($id) ? $id : (int) $id; 
	}
}
