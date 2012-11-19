<?php
class UploadController extends Controller 
{
	public function actionIndex() {
		$this->pageTitle  = 'Upload Photo';
		Yii::app()->clientScript->registerMetaTag('viewport', 'width=device-width', null, array('lang' => 'en'));
		Yii::app()->clientScript->registerPackage('upload');
		Yii::app()->clientScript->registerPackage('chosen');
		Yii::app()->clientScript->registerPackage('formly');
		Yii::app()->clientScript->registerPackage('tabify');
		$this->render('index', array(
			'arrAlbumList' => AlbumModel::model()->getAlbumList('private'),
		));
	}

	public function actionUpload() {
		error_reporting(E_ALL | E_STRICT);
		$upload_handler = new UploadHandler();
		header('Pragma: no-cache');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Content-Disposition: inline; filename="files.json"');
		header('X-Content-Type-Options: nosniff');
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: OPTIONS, HEAD, GET, POST, PUT, DELETE');
		header('Access-Control-Allow-Headers: X-File-Name, X-File-Type, X-File-Size');

		switch ($_SERVER['REQUEST_METHOD']) {
			case 'OPTIONS':
				break;
			case 'HEAD':
			/*
			case 'GET':
				$upload_handler->get();
				break;
			*/
			case 'POST':
				$upload_handler->post();
				break;
			/*
			case 'DELETE':
				$upload_handler->delete($sFileName);
				break;
			*/
			default:
				throw new CHttpException(400, 'Method not allowed');
		}
	}

	public function actionDelete($sName = '') {
		error_reporting(E_ALL | E_STRICT);
		$upload_handler = new UploadHandler();
		header('Pragma: no-cache');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Content-Disposition: inline; filename="files.json"');
		header('X-Content-Type-Options: nosniff');
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: OPTIONS, HEAD, GET, POST, PUT, DELETE');
		header('Access-Control-Allow-Headers: X-File-Name, X-File-Type, X-File-Size');
				
		switch ($_SERVER['REQUEST_METHOD']) {
			case 'POST':
				$upload_handler->delete($sName);
				break;
			case 'DELETE':
				$upload_handler->delete($sName);
				break;
			default:
				throw new CHttpException(400, 'Method not allowed');
		}
	}
}
