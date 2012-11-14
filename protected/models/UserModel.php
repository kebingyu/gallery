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
	public $new_password;
	public $conf_email;
	public $new_email;
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
			array('username, password, new_password, email', 'length', 'max' => 128),
			array('email, new_email, conf_email', 'email'),
			array('create_time, last_login_time, last_logout_time, last_login_ip', 'safe'),
			// purify inputs
			array('username, password, new_password', 'filter', 'filter' => array($this, 'purify')), 
			// register scenario
			array('username, password, conf_password, pcode', 'required', 'on' => 'register'),
			array('password', 'compare', 'compareAttribute' => 'conf_password', 'on' => 'register'),
			array('pcode', 'match', 'pattern' => '/^kyu$/', 'on' => 'register'),
			array('username', 'unique', 'on' => 'register'),
			array('username', 'match', 'pattern' => '/^[A-Za-z0-9]+$/', 'on' => 'register', 
				'message' => 'Username can only contains numbers and letters'),
			// login scenario
			array('username, password', 'required', 'on' => 'login'),
			array('username', 'exist', 'on' => 'login'),
			// reset scenario (reset password and email)
			array('password, new_password, conf_password, new_email, conf_email', 'required', 'on' => 'reset'),
			array('new_email', 'compare', 'compareAttribute' => 'conf_email', 'on' => 'reset'),
			array('new_password', 'compare', 'compareAttribute' => 'conf_password', 'on' => 'reset'),
			array('new_email', 'unique', 'on' => 'reset'),
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
			'new_password'  => 'New Password',
			'conf_email'    => 'Confirm Email',
			'new_email'     => 'New Email',
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
