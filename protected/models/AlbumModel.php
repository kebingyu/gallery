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
			array('name, description, is_public, user_id', 'required'),
			array('name', 'isExisting', 'on' => 'create'),
			array('name, description, is_public, user_id', 'filter', 'filter' => array($this, 'purify')), 
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

	public function isExisting($strName) {
		$criteria = new CDbCriteria;  
		$criteria->addCondition(array(
			'user_id='.Yii::app()->user->id,
			"name='".$strName."'",
		));
		$objModel = AlbumModel::model()->find($criteria);
		return $objModel ? true : false;
	}
}
