<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	public $metaKeyword = 'Zhou, family, photo, gallery';
	public $metaDescription = 'Zhou family photp gallery';

	/**
	 * @var string the default layout for the controller view. Defaults to 'column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout = 'main';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu = array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs = array();

	// default section for the top navigation bar
	public $section = 'home';

	public function init()
	{
		Yii::app()->clientScript->registerCssFile('/css/zebra_dialog.css');
		Yii::app()->clientScript->registerCssFile('/css/all.css');

		Yii::app()->clientScript->registerScriptFile('/js/jquery-1.8.1.min.js');
		Yii::app()->clientScript->registerScriptFile('/js/jquery-ui-1.8.23.custom.min.js');
		Yii::app()->clientScript->registerScriptFile('/js/vendors/jquery.yiiactiveform.js');
		Yii::app()->clientScript->registerScriptFile('/js/zebra_dialog.js');
		Yii::app()->clientScript->registerScriptFile('/js/all.js');
	}
}
