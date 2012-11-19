<?php

class AlbumModel extends CActiveRecord
{
	/**
	 * The followings are the available columns in table 'album':
	 * @var integer $id
	 * @var string  $name
	 * @var string  $description
	 * @var integer $create_time
	 * @var boolean $is_public
	 * @var integer $user_id
	 */

	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'album';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('name, description, is_public', 'required'),
			array('name', 'isExisting', 'on' => 'create'),
			array('name, description, is_public', 'filter', 'filter' => array($this, 'purify')), 
			array('create_time', 'safe'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'images' => array(self::HAS_MANY, 'Image', 'album_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'name' => 'Album Name',
			'description' => 'Album Description',
			'is_public' => 'Is Public',
		);
	}

	protected function beforeSave()
	{
		if ($this->isNewRecord) {
			$this->create_time = time();
		}
		return parent::beforeSave();
	}

	private function purify($value) 
	{
		$p = new CHtmlPurifier();
		$p->options = array(
			'URI.AllowedSchemes' => array(
				'http' => true,
				'https' => true,
			),
		);
		return $p->purify($value);		
	}

	/**
	 * isExisting: check if the given album name is already used by current user. 
	 * NOTE: different users can have same album name.
	 * 
	 * @param mixed $strName 
	 * @access public
	 * @return void
	 */
	public function isExisting($strName) {
		$criteria = new CDbCriteria;  
		$criteria->addCondition(array(
			'user_id='.Yii::app()->user->id,
			"name='".$strName."'",
		));
		$objModel = AlbumModel::model()->find($criteria);
		return $objModel ? true : false;
	}

	public function getImageInAlbum($strCate = 'public') {
        $bPublic = $strCate === 'private' ? false : true;
        $aResult = array();
        if ($this->id === 0) {
            // 1) retrieve album list (including empty album)
            $aList = $this->getAlbumList($strCate);
            $aResult['list'] = $aList;
            // 2) retrieve image count for each album
            $sQuery = "SELECT album.*, user.username, COUNT(*) AS image_count FROM image".
                " JOIN image_album ON image.name = image_album.image_name AND image.user_id = image_album.user_id".
                " JOIN album ON album.id = image_album.album_id AND album.user_id = image_album.user_id".
                " JOIN user ON user.id = album.user_id";
            $sQuery .= $bPublic ? " WHERE album.is_public = 1" : " WHERE image.user_id = {$this->_nUserID}";
            $sQuery .= " GROUP BY album.id ORDER BY album.create_time";
            $temp = $this->query($sQuery);
            foreach ($temp as $value) {
                $aResult['count'][] = array(
                    'album_id' => $value['album']['id'],
                    'album_name' => $value['album']['name'],
                    'album_desc' => $value['album']['description'],
                    'is_public' => $value['album']['is_public'],
                    'author' => $value['user']['username'],
                    'image_count' => $value['']['image_count'],
                );
            }
            $temp = array();
        }
        // 3) retrieve image/album info
        $sQuery = "SELECT album.*, image.* FROM album".
            " JOIN image_album ON album.id = image_album.album_id AND album.user_id = image_album.user_id".
            " JOIN image ON image.name = image_album.image_name AND image.user_id = image_album.user_id";
        $sQuery .= $bPublic ? " WHERE album.is_public = 1" : " WHERE album.user_id = {$this->_nUserID}";
        if ($this->_nAlbumID !== 0) {
            $sQuery .= " AND album.id = {$this->_nAlbumID}";
        }
        $sQuery .= " ORDER BY album.create_time, image.create_time";
        $temp = $this->query($sQuery);
        foreach ($temp as $value) {
            $aResult['image'][] = array(
                'album_id' => $value['album']['id'],
                'album_name' => $value['album']['name'],
                'album_desc' => $value['album']['description'],
                'album_create_time' => $value['album']['create_time'],
                'image_name' => $value['image']['name'],
                'image_desc' => $value['image']['description'],
                'image_create_time' => $value['image']['create_time'],
            );
        }
        return $aResult;
    }
	
	/**
     * getAlbumList
     * 1) $strCate == 'public'  : retrieve list of all public albums (is_public = true)
     * 2) $strCate == 'private' : retrieve list of all personal albums
     *
     * @param boolean $bPublic
     * @access private
     * @return mixed
     */
	public function getAlbumList($strCate = 'public') {
		$criteria = new CDbCriteria;
		//$criteria->select = array('id');
		if ($strCate === 'private') {
			$criteria->condition = 'album.user_id=:col_val'; 
			$criteria->params = array(
				':col_val' => Yii::app()->user->id,
			);
		} else {
			$criteria->condition = 'album.is_public=:ocl_val'; 
			$criteria->params = array(
				':col_val' => 1,
			);
		}
		$criteria->order = 'album.create_time DESC';
		$arrAlbumList = AlbumModel::model()->with('user')->findAll($criteria);
		/*
		$temp = $this->query($sQuery);
		$aResult = array();
		foreach ($temp as $value) {
			$aResult[] = array(
				'album_id' => $value['album']['id'],
				'album_name' => $value['album']['name'],
				'album_desc' => $value['album']['description'],
				'is_public' => $value['album']['is_public'],
				'author' => $value['user']['username'],
			);
		}
		return $aResult;
		 */
		return $arrAlbumList;
	}
	
}
