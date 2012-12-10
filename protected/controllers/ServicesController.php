<?php

class ServicesController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
		);
	}

	public function actionIndex() 
	{
		$this->redirect(Yii::app()->homeUrl);
	}

	/**
	 * actionRegister: register a new user 
	 * 
	 * @access public
	 * @return void
	 */
	public function actionRegister()
	{
		$oModel = new UserModel('register');

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax'] === 'reg_form')
		{
			echo CActiveForm::validate($oModel);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['UserModel']))
		{
			$oModel->attributes = $_POST['UserModel'];
			if($oModel->validate())
			{
				if ($oModel->save()) {
					$oIdentity = new UserIdentity($oModel->username, $oModel->password);
					$oIdentity->setId($oModel->id);
					Yii::app()->user->login($oIdentity);
					$user['register'] = 1;
				} else {
					$user['register'] = 0;
				}
			} else {
				$user['error'] = $oModel->getErrors();
				$user['register'] = 0;
			}
			echo json_encode($user);
		} else {
			$this->redirect(Yii::app()->homeUrl);
		}
	}

	/**
	 * actionLogin: user log in 
	 * 
	 * @access public
	 * @return void
	 */
	public function actionLogin()
	{
		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax'] === 'login_form')
		{
			$oModel = new UserModel('login');
			echo CActiveForm::validate($oModel);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['UserModel']))
		{
			$username = isset($_POST['UserModel']['username']) ? $_POST['UserModel']['username'] : '';
			$password = isset($_POST['UserModel']['password']) ? $_POST['UserModel']['password'] : '';
			$oIdentity = new UserIdentity($username, $password);
			if ($oIdentity->authenticate()) {
				Yii::app()->user->login($oIdentity);
				$user['login'] = 1;
			} else {
				$user['error'] = array($oIdentity->errorMessage);
				$user['login'] = 0;
			}
			echo json_encode($user);
		} else {
			$this->redirect(Yii::app()->homeUrl);
		}
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}
