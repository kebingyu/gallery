<?php
/*
 * jQuery File Upload Plugin PHP Class 5.11.2
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */
class UploadHandler
{
    protected $options;

    function __construct($options = null) {
		// retrieve site settings
		define('DELETE_URL', Yii::app()->params['BaseUrl'] . '/upload/delete/');
		define('UPLOAD_DIR', Yii::app()->params['RootDir'] . '/privacy/photo');
		define('UPLOAD_URL', Yii::app()->params['BaseUrl'] . '/image');

        $this->options = array(
            'delete_url' => DELETE_URL,
            'upload_dir' => UPLOAD_DIR . '/files/',
            'upload_url' => UPLOAD_URL . '/files/',
            'param_name' => 'files',
            // Set the following option to 'POST', if your server does not support
            // DELETE requests. This is a parameter sent to the client:
            //'delete_type' => 'DELETE',
            'delete_type' => 'POST',
            // The php.ini settings upload_max_filesize and post_max_size
            // take precedence over the following max_file_size setting:
            'max_file_size' => null,
            'min_file_size' => 1,
            'accept_file_types' => '/.+$/i',
            // The maximum number of files for the upload directory:
            'max_number_of_files' => null,
            // Image resolution restrictions:
            'max_width' => 1920,
            'max_height' => 1200,
            'min_width' => 1,
            'min_height' => 1,
            // Set the following option to false to enable resumable uploads:
            'discard_aborted_uploads' => true,
            // Set to true to rotate images based on EXIF meta data, if available:
            'orient_image' => false,
            'image_versions' => array(
                // Uncomment the following version to restrict the size of
                // uploaded images. You can also add additional versions with
                // their own upload directories:
                'medium' => array(
                    'upload_dir' => UPLOAD_DIR . '/mediums/',
                    'upload_url' => UPLOAD_URL . '/mediums/',
                    'max_width' => 160,
                    'max_height' => 100,
                    'jpeg_quality' => 95,
                ),
                'thumbnail' => array(
                    'upload_dir' => UPLOAD_DIR . '/thumbnails/',
                    'upload_url' => UPLOAD_URL . '/thumbnails/',
                    'max_width' => 80,
                    'max_height' => 50,
                )
            ),
        );
        if ($options) {
            $this->options = array_replace_recursive($this->options, $options);
        }
    }

    protected function getFullUrl() {
        $https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
      	return
    		($https ? 'https://' : 'http://').
    		(!empty($_SERVER['REMOTE_USER']) ? $_SERVER['REMOTE_USER'].'@' : '').
    		(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : ($_SERVER['SERVER_NAME'].
    		($https && $_SERVER['SERVER_PORT'] === 443 ||
    		$_SERVER['SERVER_PORT'] === 80 ? '' : ':'.$_SERVER['SERVER_PORT']))).
    		substr($_SERVER['SCRIPT_NAME'],0, strrpos($_SERVER['SCRIPT_NAME'], '/'));
    }

    protected function set_file_delete_url($file) {
        //$file->delete_url = $this->options['delete_url'] . '?file=' . rawurlencode($file->name);
        $file->delete_url = $this->options['delete_url'] . rawurlencode($file->name);
		/*
        $file->delete_type = $this->options['delete_type'];
        if ($file->delete_type !== 'DELETE') {
            $file->delete_url .= '&_method=DELETE';
        }
		*/
    }

	/**
	 * updateDatabase: add/delete operation in image/image_album table
	 * NOTE: update image info is done in services/image controller
	 * 
	 * @param string $sMethod 
	 * @param mixed $file 
	 * @access protected
	 * @return mixed
	 */
	protected function updateDatabase($sMethod, $file) {
		if ($sMethod === 'add') { // add image record to the database
			$oImageModel = new ImageModel();
			$oImageModel->_sImageName = $file->name;
			$oImageModel->_sImageDesc = $file->desc;
			$oImageModel->_nCreateTime = time();
			$oImageModel->_nIsPublic = $file->album['is_public'];
			$oImageModel->add();
			unset($oImageModel);
			// add relation info 
			$oRelationModel = new ImageAlbumModel();
			$oRelationModel->_sImageName = $file->name;
			$oRelationModel->_nAlbumID = $file->album['id'];
			$oRelationModel->add();
			unset($oRelationModel);
			// add info table
			$oInfoModel = new InfoModel();
			$oInfoModel->_sCategory = 'image';
			$oInfoModel->_sContent = $file->desc;
			$oInfoModel->_nAlbumID = $file->album['id'];
			$oInfoModel->_sImageName = $file->name;
			$oInfoModel->_nIsPublic = $file->album['is_public'];
			$oInfoModel->add();
			unset($oInfoModel);
		} else if ($sMethod === 'delete'){ // delete image record from database
			$oModel = new ImageModel();
            $oModel->_sImageName = $file;
            $result = $oModel->delete();
            if ($result === false) {
                $data['stat'] = 'error';
            } else {
                $data['stat'] = $result === 0 ? 'fail' : 'success';
            }
			return $data;
		}
	}

    protected function get_file_object($file_name) {
        $file_path = $this->options['upload_dir'] . $file_name;
        if (is_file($file_path) && $file_name[0] !== '.') {
            $file = new stdClass();
            $file->name = $file_name;
            $file->size = filesize($file_path);
            $file->url = $this->options['upload_url'] . rawurlencode($file->name);
            foreach($this->options['image_versions'] as $version => $options) {
                if (is_file($options['upload_dir'] . $file_name)) {
                    $file->{$version . '_url'} = $options['upload_url'] . rawurlencode($file->name);
                }
            }
            $this->set_file_delete_url($file);
            return $file;
        }
        return null;
    }

    protected function get_file_objects() { // get all file objs in the upload_dir??
        return array_values(array_filter(array_map(
            array($this, 'get_file_object'),
            scandir($this->options['upload_dir'])
        )));
    }

	// create a new scaled image if original one is too big
    protected function create_scaled_image($file_name, $options) {
        $file_path = $this->options['upload_dir'] . $file_name;
        $new_file_path = $options['upload_dir'] . $file_name;
        list($img_width, $img_height) = @getimagesize($file_path);
        if (!$img_width || !$img_height) {
            return false;
        }
        $scale = min(
            $options['max_width'] / $img_width,
            $options['max_height'] / $img_height
        );
        if ($scale >= 1) {
            if ($file_path !== $new_file_path) {
                return copy($file_path, $new_file_path); // copy file to new file
            }
            return true;
        }
        $new_width = $img_width * $scale;
        $new_height = $img_height * $scale;
        $new_img = @imagecreatetruecolor($new_width, $new_height);
        switch (strtolower(substr(strrchr($file_name, '.'), 1))) {
            case 'jpg':
            case 'jpeg':
                $src_img = @imagecreatefromjpeg($file_path);
                $write_image = 'imagejpeg';
                $image_quality = isset($options['jpeg_quality']) ?
                    $options['jpeg_quality'] : 75;
                break;
            case 'gif':
                @imagecolortransparent($new_img, @imagecolorallocate($new_img, 0, 0, 0));
                $src_img = @imagecreatefromgif($file_path);
                $write_image = 'imagegif';
                $image_quality = null;
                break;
            case 'png':
                @imagecolortransparent($new_img, @imagecolorallocate($new_img, 0, 0, 0));
                @imagealphablending($new_img, false);
                @imagesavealpha($new_img, true);
                $src_img = @imagecreatefrompng($file_path);
                $write_image = 'imagepng';
                $image_quality = isset($options['png_quality']) ?
                    $options['png_quality'] : 9;
                break;
            default:
                $src_img = null;
        }
        $success = $src_img && @imagecopyresampled(
            $new_img,
            $src_img,
            0, 0, 0, 0,
            $new_width,
            $new_height,
            $img_width,
            $img_height
        ) && $write_image($new_img, $new_file_path, $image_quality);
        // Free up memory (imagedestroy does not delete files):
        @imagedestroy($src_img);
        @imagedestroy($new_img);
        return $success;
    }

	// error message stored in /js/upload/locale.js
    protected function validate($uploaded_file, $file, $error, $index) {
        if ($error) {
            $file->error = $error;
            return false;
        }
        if (!$file->name) {
            $file->error = 'missingFileName';
            return false;
        }
        if (!preg_match($this->options['accept_file_types'], $file->name)) {
            $file->error = 'acceptFileTypes';
            return false;
        }
        if ($uploaded_file && is_uploaded_file($uploaded_file)) {
            $file_size = filesize($uploaded_file);
        } else {
            $file_size = $_SERVER['CONTENT_LENGTH'];
        }
        if ($this->options['max_file_size'] && (
                $file_size > $this->options['max_file_size'] ||
                $file->size > $this->options['max_file_size'])
            ) {
            $file->error = 'maxFileSize';
            return false;
        }
        if ($this->options['min_file_size'] &&
            $file_size < $this->options['min_file_size']) {
            $file->error = 'minFileSize';
            return false;
        }
        if (is_int($this->options['max_number_of_files']) && (
                count($this->get_file_objects()) >= $this->options['max_number_of_files'])
            ) {
            $file->error = 'maxNumberOfFiles';
            return false;
        }
        list($img_width, $img_height) = @getimagesize($uploaded_file);
        if (is_int($img_width)) {
            if ($this->options['max_width'] && $img_width > $this->options['max_width'] ||
                    $this->options['max_height'] && $img_height > $this->options['max_height']) {
                $file->error = 'maxResolution';
                return false;
            }
            if ($this->options['min_width'] && $img_width < $this->options['min_width'] ||
                    $this->options['min_height'] && $img_height < $this->options['min_height']) {
                $file->error = 'minResolution';
                return false;
            }
        }
		if (!isset($_POST['saveto_album_name'])) {
        	$file->error = 'emptyAlbum';
            return false;
		}
        return true;
    }

	// if duplicate file name found, create a new file name like 'Desert_1.jpg'
    protected function upcount_name_callback($matches) {
        $index = isset($matches[1]) ? intval($matches[1]) + 1 : 1;
        $ext = isset($matches[2]) ? $matches[2] : '';
        return '_' . $index . $ext;
    }

    protected function upcount_name($name) {
        return preg_replace_callback(
            '/(?:(?:_([\d]+))?(\.[^.]+))?$/',
            array($this, 'upcount_name_callback'),
            $name,
            1
        );
    }

    protected function trim_file_name($name, $type, $index) {
        // Remove path information and dots around the filename, to prevent uploading
        // into different directories or replacing hidden system files.
        // Also remove control characters and spaces (\x00..\x20) around the filename:
        $file_name = trim(basename(stripslashes($name)), ".\x00..\x20");
        if ($this->options['discard_aborted_uploads']) {
            while(is_file($this->options['upload_dir'] . $file_name)) {
                $file_name = $this->upcount_name($file_name);
            }
        }
        return $file_name;
    }

	protected function md5_file_name($name, $type) {
		// generate a unique id for uploaded image
		$tmp = $name;
		//$tmp = uniqid($tmp, true);
		// use md5 hash
		$tmp = md5($tmp . time() . rand(1, 99999999) . $type);
		preg_match('/^image\/(gif|jpe?g|png)/', $type, $matches);
		return $tmp . '.' . $matches[1];
	}

    protected function handle_form_data($file, $index) {
        // get album info
		$temp = array();
		$oFDS = new XSSHandler();
		$temp['name'] = $oFDS->FilterXSS_SQL(trim($_POST['saveto_album_name']));
		$temp['id'] = $_POST['saveto_album_id'];
		$temp['is_public'] = $_POST['saveto_album_is_public'];
		return $temp;
    }

    protected function orient_image($file_path) {
      	$exif = @exif_read_data($file_path); // Reads the EXIF headers from JPEG or TIFF
        if ($exif === false) {
            return false;
        }
      	$orientation = intval(@$exif['Orientation']);
      	if (!in_array($orientation, array(3, 6, 8))) {
      	    return false;
      	}
      	$image = @imagecreatefromjpeg($file_path);
      	switch ($orientation) {
        	  case 3:
          	    $image = @imagerotate($image, 180, 0);
          	    break;
        	  case 6:
          	    $image = @imagerotate($image, 270, 0);
          	    break;
        	  case 8:
          	    $image = @imagerotate($image, 90, 0);
          	    break;
          	default:
          	    return false;
      	}
      	$success = imagejpeg($image, $file_path);
      	// Free up memory (imagedestroy does not delete files):
      	@imagedestroy($image);
      	return $success;
    }

    protected function handle_file_upload($uploaded_file, $name, $size, $type, $error, $index = null) {
        $file = new stdClass();
		// desc is the original file name; name is the md5 hashed name
        $file->desc = $this->trim_file_name($name, $type, $index);
        $file->name = $this->md5_file_name($file->desc, $type);
        $file->size = intval($size);
        $file->type = $type;
        if ($this->validate($uploaded_file, $file, $error, $index)) {
            $file->album = $this->handle_form_data($file, $index);
            $file_path = $this->options['upload_dir'] . $file->name;
            $append_file = !$this->options['discard_aborted_uploads'] &&
                is_file($file_path) && $file->size > filesize($file_path);
            clearstatcache(); // Clears file status cache
            if ($uploaded_file && is_uploaded_file($uploaded_file)) {
                // multipart/formdata uploads (POST method uploads)
                if ($append_file) {
                    file_put_contents(
                        $file_path,
                        fopen($uploaded_file, 'r'),
                        FILE_APPEND
                    );
                } else {
                    move_uploaded_file($uploaded_file, $file_path);
                }
            } else {
                // Non-multipart uploads (PUT method support)
                file_put_contents(
                    $file_path,
                    fopen('php://input', 'r'),
                    $append_file ? FILE_APPEND : 0
                );
            }
            $file_size = filesize($file_path);
            if ($file_size === $file->size) {
            	if ($this->options['orient_image']) {
            		$this->orient_image($file_path);
            	}
                $file->url = $this->options['upload_url'] . rawurlencode($file->name);
                foreach($this->options['image_versions'] as $version => $options) {
                    if ($this->create_scaled_image($file->name, $options)) {
                        if ($this->options['upload_dir'] !== $options['upload_dir']) {
                            $file->{$version.'_url'} = $options['upload_url'] . rawurlencode($file->name);
                        } else {
                            clearstatcache();
                            $file_size = filesize($file_path);
                        }
                    }
                }
            } else if ($this->options['discard_aborted_uploads']) {
                unlink($file_path);
                $file->error = 'abort';
            }
            $file->size = $file_size;
            $this->set_file_delete_url($file);
			$this->updateDatabase('add', $file);
        }
        return $file;
    }

	// return file info: name, size, url, delete_url
    public function get() {
        $file_name = isset($_REQUEST['file']) ?
            basename(stripslashes($_REQUEST['file'])) : null;
        if ($file_name) {
            $info = $this->get_file_object($file_name);
        } else {
            $info = $this->get_file_objects();
        }
        header('Content-type: application/json');
        echo json_encode($info);
    }

    public function post() {
        $upload = isset($_FILES[$this->options['param_name']]) ?
            $_FILES[$this->options['param_name']] : null;
        $info = array();
        if ($upload && is_array($upload['tmp_name'])) {
            // param_name is an array identifier like "files[]",
            // $_FILES is a multi-dimensional array:
			$oFDS = new XSSHandler();
			$aUploadName = array_map(array($oFDS, 'FilterXSS_SQL'), $_POST['upload_name']);
            foreach ($upload['tmp_name'] as $index => $value) {
                $info[] = $this->handle_file_upload(
                    $upload['tmp_name'][$index],
                    isset($_SERVER['HTTP_X_FILE_NAME']) ?
                        //$_SERVER['HTTP_X_FILE_NAME'] : $upload['name'][$index],
                        $_SERVER['HTTP_X_FILE_NAME'] : $aUploadName[$index],
                    isset($_SERVER['HTTP_X_FILE_SIZE']) ?
                        $_SERVER['HTTP_X_FILE_SIZE'] : $upload['size'][$index],
                    isset($_SERVER['HTTP_X_FILE_TYPE']) ?
                        $_SERVER['HTTP_X_FILE_TYPE'] : $upload['type'][$index],
                    $upload['error'][$index],
                    $index
                );
            }
        } elseif ($upload || isset($_SERVER['HTTP_X_FILE_NAME'])) {
            // param_name is a single object identifier like "file",
            // $_FILES is a one-dimensional array:
            $info[] = $this->handle_file_upload(
                isset($upload['tmp_name']) ? $upload['tmp_name'] : null,
                isset($_SERVER['HTTP_X_FILE_NAME']) ?
                    $_SERVER['HTTP_X_FILE_NAME'] : (isset($upload['name']) ?
                        $upload['name'] : null),
                isset($_SERVER['HTTP_X_FILE_SIZE']) ?
                    $_SERVER['HTTP_X_FILE_SIZE'] : (isset($upload['size']) ?
                        $upload['size'] : null),
                isset($_SERVER['HTTP_X_FILE_TYPE']) ?
                    $_SERVER['HTTP_X_FILE_TYPE'] : (isset($upload['type']) ?
                        $upload['type'] : null),
                isset($upload['error']) ? $upload['error'] : null
            );
        }
        header('Vary: Accept');
        $json = json_encode($info);
        $redirect = isset($_REQUEST['redirect']) ?
            stripslashes($_REQUEST['redirect']) : null;
        if ($redirect) {
            header('Location: ' . sprintf($redirect, rawurlencode($json)));
            return;
        }
        if (isset($_SERVER['HTTP_ACCEPT']) &&
            (strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)) {
            header('Content-type: application/json');
        } else {
            header('Content-type: text/plain');
        }
        echo $json;
    }

    /**
     * delete: delete image file from disk 
     * 
     * @access public
     * @return json
     */
    public function delete($sName = '') {
		require_once('FormDataScrubbing.class.inc');
		$oFDS = new XSSHandler();	
        //$file_name = isset($_REQUEST['imageName']) ? $oFDS->FilterXSS_SQL(trim($_REQUEST['imageName'])) : '';
		$file_name = isset($_REQUEST['file']) ? 
			$_REQUEST['file'] : isset($_REQUEST['imageName']) ? 
			$_REQUEST['imageName'] : $sName;
		$file_name = $oFDS->FilterXSS_SQL(trim($file_name));
		$temp = $this->updateDatabase('delete', $file_name);
		if ($temp['stat'] !== 'success') {
			$data['stat'] = $temp['stat'];
        	header('Content-type: application/json');
        	echo json_encode($data);
			return;
		}
        $file_path = $this->options['upload_dir'] . $file_name;
        $success = is_file($file_path) && $file_name !== '.' && unlink($file_path);
        if ($success) {
            foreach($this->options['image_versions'] as $version => $options) {
                $file = $options['upload_dir'] . $file_name;
                if (is_file($file)) {
                    unlink($file);
                } else {
					$data['stat'] = 'error';
        			header('Content-type: application/json');
        			echo json_encode($data);
					return;
				}
            }
			$data['stat'] = $temp['stat'];
        } else {
			$data['stat'] = 'error';
		}
        header('Content-type: application/json');
        echo json_encode($data);
    }

}
