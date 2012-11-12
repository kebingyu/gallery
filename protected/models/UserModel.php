<?php

class UserModel extends CActiveRecord
{
	/**
	 * The followings are the available columns in table 'user':
	 * @var integer $id
	 * @var string  $username
	 * @var string  $password
	 * @var string  $email
	 * @var integer $create_time
	 * @var integer $last_login_time
	 * @var integer $last_logout_time
	 * @var string  $last_login_ip
	 * @var boolean $is_admin
	 * @var boolean $is_active
	 */

	public $conf_password;
	public $pcode;

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
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that will receive user inputs.
		return array(
			array('username, password, email', 'length', 'max' => 128),
			array('username, password, conf_password, pcode', 'required', 'on' => 'register'),
			array('password', 'compare', 'compareAttribute' => 'conf_password', 'on' => 'register'),
			array('pcode', 'match', 'pattern' => '/^kyu$/', 'on' => 'register'),
			array('username', 'unique', 'on' => 'register'),
			array('username', 'match', 'pattern' => '/^[A-Za-z0-9]+$/', 'on' => 'register'),
			array('username, password', 'required', 'on' => 'login'),
			array('username', 'exist', 'on' => 'login'),
			//array('email', 'email'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'albums' => array(self::HAS_MANY, 'Album', 'user_id'),
			'images' => array(self::HAS_MANY, 'Image', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'conf_password' => 'Confirm Password',
			'pcode'         => 'Promo Code',
		);
	}

	public function validatePassword($sPassword, $sHash)
	{
		$oBcrypt = new Bcrypt();
		return $oBcrypt->verify($sPassword, $sHash) ? true : false;	
	}

	protected function beforeSave()
	{
		if ($this->isNewRecord) {
			$this->password = $this->encrypt($this->password);
			$this->create_time = time();
		}
		return parent::beforeSave();
	}

	/**
	 * encrypt password 
	 * 
	 * @param string $value 
	 * @access protected
	 * @return string
	 */
	private function encrypt($value)
	{
		$oBcrypt = new Bcrypt();
		return $oBcrypt->hash($value);
	}
}
