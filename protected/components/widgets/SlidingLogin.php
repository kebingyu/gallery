<?php
class SlidingLogin extends CWidget {
	public function run() {
		$model_login = new UserModel('login');
		$this->render('slidingLogin', array(
			'model_login' => $model_login,
		));
	}
}
