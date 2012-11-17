<?php
class AlbumController extends Controller 
{
	const NAME_EMPTY_ERROR = 1;
	const DESC_EMPTY_ERROR = 2;
	const NAME_EXIST_ERROR = 4;

	public function index() {
		exit;
	}

	/**
	 * showGallery 
	 * 1) /album/showgallery/private: display all public/private albums belongs to current user
	 * 2) /album/showgallery/[anything_else]: display all public albums
	 * 
	 * @param string $sCate 
	 * @access public
	 * @return void
	 */
	public function showGallery($sCate = '') {
		$oView = $this->getRenderEngine();
		$oView->addCSSInclude('bootstrap.icon-large.css');
		$oView->set('cate', $sCate);
		switch ($sCate) {
			case 'private': 
				$oView->PageTitle('My Gallery');
				break;
			default:
				$oView->PageTitle('Public Gallery');
		}
		$oView->render('showgallery');
	}

	/**
	 * showAlbum: display request album with edit option if it belongs to current user or just the photos
	 * 
	 * @param number $nAlbumID 
	 * @access public
	 * @return void
	 */
	public function showAlbum($nAlbumID = '') {
        $nAlbumID = intval($nAlbumID);
		$sCate = isset($_GET['cate']) ? $_GET['cate'] : 'public';
		$temp = $this->checkAccessibility($nAlbumID, 'show');
		$oModel = new AlbumModel();	
		$oModel->_nAlbumID = $nAlbumID;
		$aInfo = $oModel->getAlbumInfo($sCate);
		$sAlbumName = urldecode($aInfo['name']);
		$oView = $this->getRenderEngine();
		/*
		@session_start();
		if ($temp['is_public'] and $temp['user_id'] != $_SESSION['userid']) {
			$oView->set('cate', 'public');
			// disable access to empty public album
			$temp = $oModel->getImageInAlbum('public');
			if (empty($temp)) {
				throw new ControllerException("Access Deny - Not your album and empty", ControllerException::ACCESS_DENIED);
			}
		} else {
			$oView->set('cate', 'private');
		}
		*/
		$oView->set('cate', $sCate);
		$oView->PageTitle($sAlbumName);
		// galleria
		$oView->addCSSInclude('bootstrap.icon-large.css');
		$oView->addJSInclude('album/galleria-1.2.8.min.js');
		$oView->addJSInclude('album/galleria.classic.js');
		$oView->set('album_name', $sAlbumName);
		$oView->set('album_id', $nAlbumID);
		$oView->render('showalbum');
	}

	/**
	 * editGallery: edit all public/private albums belong to current user 
	 * NOTE: DEPRICIATED 
	 * 
	 * @access public
	 * @return void
	 */
	private function editGallery() {
		$this->showGallery();exit;

		$oView = $this->getRenderEngine();
		$oView->PageTitle('Edit My Gallery');
		$oView->render('editgallery');
	}

	/**
	 * editAlbum: edit requested album belong to current user 
	 * 
	 * @param number $nAlbumID 
	 * @access public
	 * @return void
	 */
	public function editAlbum($nAlbumID = '') {
        $nAlbumID = intval($nAlbumID);
		$sCate = isset($_GET['cate']) ? $_GET['cate'] : 'private';
		$this->checkAccessibility($nAlbumID);
		$oModel = new AlbumModel();	
		$oModel->_nAlbumID = $nAlbumID;
		$aInfo = $oModel->getAlbumInfo($sCate);
		$sAlbumName = urldecode($aInfo['name']);
		$oView = $this->getRenderEngine();
		$oView->PageTitle('Edit&nbsp;Album');
		$oView->addCSSInclude('bootstrap.icon-large.css');
		$oView->addCSSInclude('formly.css');
		$oView->addJSInclude('formly.js');
		$oView->set('album_info', $aInfo);
		$oView->render('editalbum');
	}

	/**
	 * add: create a new album for the current user 
	 * 
	 * @access public
	 * @return json
	 */
	public function actionAdd() {
		if (empty($_POST)) {
            throw new ControllerException('Access denied!', ControllerException::ACCESS_DENIED);
        } 
        $oModel = new AlbumModel();
        $oFDS = new XSSHandler();
        $oModel->_sAlbumName = $oFDS->FilterXSS_SQL(trim($_POST['album_name']));
        $oModel->_sAlbumDesc = $oFDS->FilterXSS_SQL(trim($_POST['album_desc']));
		$oModel->_nIsPublic = isset($_POST['make-pub']) && $_POST['make-pub'] === 'on' ? 1 : 0;
        $oModel->_nCreateTime = time();
        
        $numErr = 0;
        if ($oModel->_sAlbumName === '') {
            $numErr |= self::NAME_EMPTY_ERROR;
        }
        if ($oModel->_sAlbumDesc === '') {
            $numErr |= self::DESC_EMPTY_ERROR;
        }
		if ($oModel->isExisting()) {
            $numErr |= self::NAME_EXIST_ERROR;
		}
		if ($numErr == 0) {
			$temp = $oModel->add();
			if ($temp === false) {
				$data['success'] = 0;
			} else {
				$data['success'] = 1;
				$data['name'] = $oModel->_sAlbumName;
				$data['is_public'] = $oModel->_nIsPublic;
				// add info table
				$nInsertID = $oModel->getAlbumID();
				$oInfoModel = new InfoModel();
				$oInfoModel->_sCategory = 'album';
				$oInfoModel->_sContent = $data['name'];
				$oInfoModel->_nAlbumID = $nInsertID;
				$oInfoModel->_nIsPublic = $oModel->_nIsPublic;
				$oInfoModel->add();
				$data['id'] = $nInsertID;
			}
		} else {
			$aErrors = $this->getErrorsArray($numErr);
			$data['success'] = 0;
			$data['error'] = $aErrors;
		}
		echo json_encode($data);
	}

	/**
	 * getImage 
	 * 1) /album/getimage/private: album/image infos for public/private albums belong to current user 
	 * 2) /album/getimage/private/[album_id]: album/image infos for requested album belongs to current user
	 * 3) /album/getimage/[anything_else]: album/image infos for public albums  
	 * 4) /album/getimage/[anything_else]/[album_id]: album/image infos for requested album  
	 * 
	 * @param string $sCate 
	 * @param number $nAlbumID 
	 * @access public
	 * @return json
	 */
	public function getImage($sCate = '', $nAlbumID = 0) {
        $oModel = new AlbumModel();
		$oModel->_nAlbumID = $nAlbumID;
		$infos = $oModel->getImageInAlbum($sCate);
		if (empty($infos)) {
			$data['count'] = array();
			$data['image'] = array();
			$data['list']  = array();
		} else {
			$data['count'] = $nAlbumID == 0 ? 
				isset($infos['count']) ? $infos['count'] : array() : array();
			$data['image'] = isset($infos['image']) ? $infos['image'] : array();
			$data['list']  = $nAlbumID == 0 ? 
				isset($infos['list']) ? $infos['list'] : array() : array();
		}
		echo json_encode($data);
	}

	/**
	 * update: update album infos which belongs to current user
	 * 
	 * @access public
	 * @return json
	 */
	public function update() {
		if (empty($_POST)) {
            throw new ControllerException('Access denied!', ControllerException::ACCESS_DENIED);
        } 
        $nAlbumID = isset($_POST['album_id']) ? intval($_POST['album_id']) : 0;
		$this->checkAccessibility($nAlbumID);

        $oModel = new AlbumModel();
        $oFDS = new XSSHandler();
		$oModel->_nAlbumID = $nAlbumID;
        $oModel->_sAlbumName = $oFDS->FilterXSS_SQL(trim(isset($_POST['album_name']) ? $_POST['album_name'] : ''));
        $oModel->_sAlbumDesc = $oFDS->FilterXSS_SQL(trim(isset($_POST['album_desc']) ? $_POST['album_desc'] : ''));
		$oModel->_nIsPublic = isset($_POST['is_public']) && $_POST['is_public'] === 'on' ? 1 : 0;
		$temp = array(
			'id' => $oModel->_nAlbumID,
			'name' => $oModel->_sAlbumName,
			'description' => $oModel->_sAlbumDesc,
			'is_public' => $oModel->_nIsPublic,
		);

        $numErr = 0;
        if ($oModel->_sAlbumName === '') {
            $numErr |= self::NAME_EMPTY_ERROR;
        }
        if ($oModel->_sAlbumDesc === '') {
            $numErr |= self::DESC_EMPTY_ERROR;
        }
		if ($numErr == 0) {
			$result = $oModel->update($temp);
			if ($result === false) {
				$data['stat'] = 'error';
			} else {
				$data['stat'] = $result === 0 ? 'fail' : 'success';
			}
		} else {
			$aErrors = $this->getErrorsArray($numErr);
			$data['stat'] = 'error';
			$data['error'] = $aErrors;
		}
		echo json_encode($data);
	}

	/**
	 * delete 
	 * only delete album record from album table
	 * image file/record removal is done by '/services/upload/delete'
	 * 
	 * @access public
	 * @return json
	 */
	public function delete() {
		if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        	throw new ControllerException('Method not allowed', ControllerException::ACCESS_DENIED);
        }
        $nAlbumID = isset($_POST['albumID']) ? intval($_POST['albumID']) : 0;
		//$bEmpty = isset($_POST['albumEmpty']) ? $_POST['albumEmpty'] : false;
		$this->checkAccessibility($nAlbumID);
        $oModel = new AlbumModel();
		$oModel->_nAlbumID = $nAlbumID;
		$result = $oModel->delete();
		if ($result === false) {
			$data['stat'] = 'error';
        } else {
			$data['stat'] = $result === 0 ? 'fail' : 'success';
			/*
			if ($data['stat'] == 'success') {
				// delete info table
				$oInfoModel = new InfoModel();
				$oInfoModel->_sCategory = 'album';
				$oInfoModel->_nAlbumID = $oModel->_nAlbumID;
				$oInfoModel->delete();
			}
			 */
        }
		echo json_encode($data);
	}

	private function getErrorsArray($numError) {
        $aError = array();
        if ($numError & self::NAME_EMPTY_ERROR) {
            $aError[] = '* Album name is required.';
        }
        if ($numError & self::NAME_EXIST_ERROR) {
            $aError[] = '* This album name is already used. Please choose another one.';
        }
		if ($numError & self::DESC_EMPTY_ERROR) {
            $aError[] = '* Album description is required.';
        }
		return $aError;
	}

	/**
	 * checkAccessibility: check if it is a valid action for current user
	 * 
	 * @param number $nAlbumID 
	 * @param string $sAction 
	 * @access private
	 * @return array
	 */
	private function checkAccessibility($nAlbumID, $sAction = '') {
        $nAlbumID = intval($nAlbumID);
		$oModel = new AlbumModel();	
		$oModel->_nAlbumID = $nAlbumID;
		$temp = $oModel->checkAccessibility();
		if (empty($temp)) {
			throw new ControllerException("Page Not Found - Invalid album id", ControllerException::NOT_FOUND_ERROR);
		}
		@session_start();
		if ($sAction == 'show') {
			if (!$temp['is_public'] and $temp['user_id'] != $_SESSION['userid']) {
				throw new ControllerException("Access Deny - Not your album", ControllerException::ACCESS_DENIED);
			}
		} else {
			if ($temp['user_id'] != $_SESSION['userid']) {
				throw new ControllerException("Access Deny - Not your album", ControllerException::ACCESS_DENIED);
			}
		}
		return $temp;
	}

}
