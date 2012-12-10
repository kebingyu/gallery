<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	public function actionIndex() 
	{
		$this->pageTitle = 'Home';
		$this->render('index');
	}

	public function actionRegister() 
	{
		$this->pageTitle = 'Register';
		$this->layout = 'system';
		$this->render('register', array(
			'model_reg' => new UserModel('register'),
		));
	}

}
