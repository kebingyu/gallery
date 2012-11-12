<?php

class UserModel_fg extends BaseModel 
{
	protected $_nUserID;
	protected $_sUsername;
	protected $_sPassword;
	protected $_sEmail;
	protected $_nCreateTime;
	protected $_sLastLoginTime;
	protected $_sLastLogOutTime;
	protected $_sLastLoginIP;
	protected $_bIsAdmin;
	protected $_bIsActive;

	public function __construct() {
        parent::__construct();
        $this->_table = 'user';
    }

	public function add() {
		$sQuery = "INSERT INTO {$this->_table} (username, password, create_time, last_login_time, last_login_ip)".
			" VALUES ('{$this->_sUsername}', '{$this->_sPassword}', '{$this->_nCreateTime}', '{$this->_sLastLoginTime}', '{$this->_sLastLoginIP}')";
		return $this->query($sQuery);
	}

	/**
	 * isValidLogin: password/username match check
	 * 
	 * @access public
	 * @return mix
	 */
	public function isValidLogin() {
		$sQuery = "SELECT * FROM {$this->_table} WHERE username = '{$this->_sUsername}'";
		$aResult = $this->query($sQuery);
		$hash = $aResult[0]['user']['password'];
		$oBcrypt = new Bcrypt();
		if ($oBcrypt->verify($this->_sPassword, $hash)) {
			return $aResult[0]['user'];
		}
		return false;
	}

	public function getInfo() {
		$sQuery = "SELECT * FROM {$this->_table} WHERE username = '{$this->_sUsername}'";
		$aResult = $this->query($sQuery);
		return empty($aResult) ? false : $aResult[0]['user'];
	}

	/**
	 * resetCheck: check input when user wants to do an email reset 
	 * 
	 * @access public
	 * @return mixed
	 */
	public function resetCheck() {
		$sQuery = "SELECT * FROM {$this->_table} WHERE username = '{$this->_sUsername}' AND email = '{$this->_sEmail}'";
		$aResult = $this->query($sQuery);
		return empty($aResult) ? false : $aResult[0]['user'];
	}

	public function update($aKeyValuePair) {
		@session_start();
		$this->_nUserID = isset($this->_nUserID) ? $this->_nUserID : intval($_SESSION['userid']);
		$sSet = '';
		foreach ($aKeyValuePair as $key => $value) {
			$sSet .= "{$key} = '{$value}', ";
		}
		$sSet = rtrim($sSet, ', ');
		$sQuery = "UPDATE {$this->_table} SET {$sSet} WHERE id = {$this->_nUserID}";
		return $this->query($sQuery);
	}
}
