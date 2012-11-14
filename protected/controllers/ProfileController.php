<?php
class ProfileController extends Controller 
{
	public $section = 'profile';

	public function init() {
		parent::init();
		Yii::app()->clientScript->registerPackage('formly');
	}

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
		$oModel = UserModel::model()->find('id=?', array(
			Yii::app()->user->id,
		));
		$oModel->setScenario('reset');
		$this->render('index', array(
			'user' => $oModel,
		));
	}
	
	public function actionUpdate() {
		// collect user input data
		if(isset($_POST['UserModel']))
		{
			$oModel = UserModel::model()->find('id=?', array(
				Yii::app()->user->id,
			));
			$oModel->attributes = $_POST['UserModel'];
			if ($oModel->validate())
			{
				$oIdentity = new UserIdentity($oModel->username, $oModel->password);
				if ($oIdentity->authenticate()) {
					if (Yii::app()->request->getQuery('cate') == 'password') {
						$oModel->password = $oModel->encrypt($oModel->new_password);
					} else if (Yii::app()->request->getQuery('cate') == 'email') {
						$oModel->email = $oModel->new_email;
					}
					if ($oModel->save()) {
						$data['stat'] = 'success';
						$data['email'] = $oModel->email;
					} else {
						$data['stat'] = 'error';
						$data['error'] = array($oModel->getErrors());
					}
				} else {
					$data['stat'] = 'fail';
					$data['error'] = array($oIdentity->errorMessage);
				}
			} else {
				$data['stat'] = 'fail';
				$data['error'] = array($oModel->getErrors());
			}
			echo json_encode($data);
		}
	}
}
