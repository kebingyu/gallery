<?php
class WebUser extends CWebUser 
{
	// Store model to not repeat query.
	private $_model;

	protected function afterLogin($fromCookie)
	{
		$oModel = UserModel::model()->find('id=?', array(
			$this->getId(),
		));
		$oModel->last_login_time = time();
		$oModel->last_login_ip = $_SERVER['REMOTE_ADDR'];
		$oModel->save();
		// display user info 
		Yii::app()->user->setFlash('display_login', true);
		parent::afterLogin($fromCookie);
	}

	protected function beforeLogout()
	{
		$oModel = UserModel::model()->find('id=?', array(
			$this->getId(),
		));
		$oModel->last_logout_time = time();
		$oModel->save();
		return parent::beforeLogout();
	}
}
