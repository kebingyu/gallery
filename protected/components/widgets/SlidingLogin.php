<?php
class SlidingLogin extends CWidget {
	public function run() {
		$model_reg = new UserModel('register');
		$model_login = new UserModel('login');
		$this->render('slidingLogin', array(
			'model_reg' => $model_reg,
			'model_login' => $model_login,
		));
	}
}
