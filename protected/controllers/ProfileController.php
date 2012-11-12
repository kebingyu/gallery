<?php
class ProfileController extends Controller 
{
	const PASSWORD_ERROR       = 1;
	const PASSWORD_MATCH_ERROR = 2;
    const PASSWORD_SHORT_ERROR = 4;
    const EMAIL_ERROR          = 8;
    const EMAIL_MATCH_ERROR    = 16;

	public $section = 'profile';

	public function filters() {
		return array(
			'accessControl',
		);
	}

	public function accessRules() {
		return array(
			array('deny',
				'actions' => array('index', 'update'),
				'users'   => array('?'),
			),
		);
	}

	public function actionIndex() {
		$this->pageTitle = 'User Profile';
		Yii::app()->clientScript->registerCssFile('/css/formly.css');
		Yii::app()->clientScript->registerScriptFile('/js/formly.js');
		$oModel = UserModel::model()->find('id=?', array(
			Yii::app()->user->id,
		));
		$this->render('index', array(
			'user' => $oModel,
		));
	}
	
	public function update() {
		if (empty($_POST) || !isset($_GET['cate'])) {
			throw new ControllerException('Access denied!', ControllerException::ACCESS_DENIED);
		}
		$oModel = new UserModel();
		@session_start();
		$oModel->_sUsername = $_SESSION["username"];
		$oModel->_sPassword = $_POST['password_current'];
		$aUserInfo = $oModel->isValidLogin($oModel->_sPassword);

		$numErr = 0;
		if ($aUserInfo === false) {
			$numErr |= self::PASSWORD_ERROR;
		}
		if ($_GET['cate'] == 'email') {
			if ($_POST['email_new'] != $_POST['email_confirm']) {
				$numErr |= self::EMAIL_MATCH_ERROR;
			} elseif (!preg_match("/^[_A-Za-z0-9-]+(\.[_A-Za-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $_POST['email_new'])) {
				$numErr |= self::EMAIL_ERROR;
			}
		} else if ($_GET['cate'] == 'password') {
			if ($_POST['password_new'] != $_POST['password_confirm']) {
				$numErr |= self::PASSWORD_MATCH_ERROR;
			} else {
				$oBcrypt = new Bcrypt();
				$sPasswd = $oBcrypt->hash($_POST['password_new']);
				if ($sPasswd === false) {
					$numErr |= self::PASSWORD_SHORT_ERROR;
				}
			}
		}
		if ($numErr == 0) {
			if ($_GET['cate'] == 'email') {
				$temp = array(
					'email' => $_POST['email_new'],
				);
			} else if ($_GET['cate'] == 'password') {
				$temp = array(
					'password' => $sPasswd,
				);
			}
			$result = $oModel->update($temp);
			if ($result === false) {
				$data['stat'] = 'error';
			} else {
				$data['stat'] = $result === 0 ? 'fail' : 'success';
				if ($_GET['cate'] == 'email') {
					$data['email'] = $result === 0 ? '' : $_POST['email_new'];
				}
			}
		} else {
			$aErrors = $this->getErrorsArray($numErr);
			$data['error'] = $aErrors;
		}
		echo json_encode($data);
	}

	private function getErrorsArray($numError) {
		$aError = array();
		if ($numError & self::PASSWORD_ERROR) {
			$aError[] = '* You need to enter the correct password.';
		} else if ($numError & self::PASSWORD_MATCH_ERROR) {
			$aError[] = '* Passwords entered do not match.';
		} else if ($numError & self::PASSWORD_SHORT_ERROR) {
			$aError[] = '* The new password is too short.';
		}
		if ($numError & self::EMAIL_ERROR) {
			$aError[] = '* Please enter a valid email address.';
		} else if ($numError & self::EMAIL_MATCH_ERROR) {
			$aError[] = '* Email addresses do not match.';
		}
		return $aError;
	}
}
