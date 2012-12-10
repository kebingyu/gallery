<?php
class ProfileController extends Controller 
{
	public $section = 'profile';

	public function filters() {
		return array(
			'accessControl',
		);
	}

	public function accessRules() {
		return array(
			array('deny',
				'actions' => array('index'),
				'users'   => array('?'),
			),
		);
	}

	public function actionIndex() {
		$this->pageTitle = 'User Profile';
		$oModel = UserModel::model()->find('id=?', array(
			Yii::app()->user->id,
		));
		$this->render('index', array(
			'user' => $oModel,
		));
	}
	
	/**
	 * actionReset: reset password/email. The request could come from a logged in user or a registered user who forgot password 
	 * 
	 * @access public
	 * @return json
	 */
	public function actionReset() {
		if (isset($_POST['UserModel']))
		{
			if (Yii::app()->user->isGuest) {
				$oModel = UserModel::model()->findByAttributes(array(
					'username' => $_POST['UserModel']['username'],
					'email' => $_POST['UserModel']['email'],
				));
				if ($oModel) {
					$oModel->setScenario('forget-password');
					if ($oModel->validate()) {
						// reset password
					} else {
						$data['stat'] = 'fail';
						$data['error'] = $oModel->getErrors();
					}
				} else {
					$data['stat'] = 'fail';
					$data['error'] = $oModel->getErrors();
				}
			} else {
				$oModel = UserModel::model()->find('id=?', array(
					Yii::app()->user->id,
				));
				$oModel->setScenario('reset-'.Yii::app()->request->getQuery('cate'));
				$oModel->attributes = $_POST['UserModel'];
				if ($oModel->validate())
				{
					$oIdentity = new UserIdentity($oModel->username, $oModel->password);
					if ($oIdentity->authenticate()) {
						if (Yii::app()->request->getQuery('cate') == 'password') {
							$oModel->password = $oModel->encrypt($oModel->new_password);
						} else if (Yii::app()->request->getQuery('cate') == 'email') {
							$oModel->password = $oModel->encrypt($oModel->password);
						}
						if ($oModel->save()) {
							$data['stat'] = 'success';
							$data['email'] = $oModel->email;
						} else {
							$data['stat'] = 'error';
							$data['error'] = $oModel->getErrors();
						}
					} else {
						$data['stat'] = 'fail';
						$data['error'] = array($oIdentity->errorMessage);
					}
				} else {
					$data['stat'] = 'fail';
					$data['error'] = $oModel->getErrors();
				}
			}
			echo json_encode($data);
		}
	}
}
