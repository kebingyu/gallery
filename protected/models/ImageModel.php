<?php

class ImageModel extends CActiveRecord
{
	/**
	 * The followings are the available columns in table 'image':
	 * @var integer $id
	 * @var string  $name
	 * @var string  $description
	 * @var integer $create_time
	 * @var boolean $is_public
	 * @var integer $user_id
	 * @var integer $album_id
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
		return 'image';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('name, description, is_public, user_id, album_id', 'required'),
			array('name, description, is_public, user_id, album_id', 'filter', 'filter' => array($this, 'purify')), 
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
			'album' => array(self::BELONGS_TO, 'Album', 'album_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
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

	public function purify($value) 
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
}
