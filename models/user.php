<?php
/**
 * user.php
 *
 * @package AccountManager
 * @author Yasuo Harada
 * @copyright 2009 SLYWALKER Co,.Ltd.
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @date $LastChangedDate$
 * @version $Rev$
 **/

/**
 * User
 **/
class User extends AccountManagerAppModel {

	/**
	 * $name
	 *
	 * @var string
	 */
	public $name = 'User';

	/**
	 * $validate
	 *
	 * @var array
	 */
	public $validate = array(
		'username' => array(
			array(
				'rule' => array('isUnique'),
				'message' => 'This field must be unique',
			),
			array(
				'rule' => array('notEmpty'),
				'message' => 'This field is required',
			),
		),
		'email' => array(
			array(
				'rule' => array('checkCompare', '_confirm'),
				'message' => 'This field is differ with confirm',
			),
			array(
				'rule' => array('isUnique'),
				'message' => 'This field must be unique',
			),
			array(
				'rule' => array('email'),
				'message' => 'This field needs email format',
			),
		),
		'password' => array(
			array(
				'rule' => array('alphaNumeric'),  
				'message' => 'This field must be number of characters in English',
			),
			array(
				'rule' => array('minLength', '4'),
				'message' => 'This field needs 4 characters or more',
			),
			array(
				'rule' => array('checkCompare', '_confirm'),
				'message' => 'This field is differ with confirm',
			),
			array(
				'rule' => array('notEmpty'),
				'message' => 'This field is required',
			),
		),
		'hash_password' => array(
			'rule' => array('notEmpty'),
			'message' => 'This field is required',
		),
		'disabled' => array('numeric'),
	);

	/**
	 * $__expires
	 *
	 * @var string
	 */
	private $__expires = '+1 hour';

	/**
	 * register
	 *
	 * @param array $data 
	 * @return mixed On success Model::$data if its not empty or true, false on failure
	 * @author Yasuo Harada
	 */
	public function register($data) {
		// 期限切れregister削除
		$conditions = array(
			'NOT' => array($this->alias.'.email_checkcode' => null),
			$this->alias.'.disabled' => 1,
			$this->alias.'.expires <' => date('Y-m-d H:i:s'),
		);
		$this->deleteAll($conditions);
		// データ追加
		$data[$this->alias]['email_checkcode'] = String::uuid();
		$data[$this->alias]['disabled'] = 1;
		$data[$this->alias]['expires'] = date('Y-m-d H:i:s', strtotime('now '.$this->__expires));
		$this->create();
		return $this->save($data);
	}

	/**
	 * changeEmail
	 *
	 * @param array $data 
	 * @return mixed On success Model::$data if its not empty or true, false on failure
	 * @author Yasuo Harada
	 */
	public function changeEmail($data) {
		$this->set($data);
		if (!$this->validates()) {
			return false;
		}
		// uuid発行
		$_data = array(
			$this->alias => array(
				'id' => $data[$this->alias]['id'],
				'email_tmp' => $data[$this->alias]['email'],
				'email_checkcode' => String::uuid(),
				'expires' => date('Y-m-d H:i:s', strtotime('now '.$this->__expires)),
			),
		);
		return $this->save($_data, false, array('id', 'email_tmp', 'email_checkcode', 'expires'));
	}

	/**
	 * changePassword
	 *
	 * @param array $data 
	 * @return mixed On success Model::$data if its not empty or true, false on failure
	 * @author Yasuo Harada
	 */
	public function changePassword($data) {
		$this->set($data);
		if (!$this->validates()) {
			return false;
		}
		// uuid発行
		$_data = array(
			$this->alias => array(
				'id' => $data[$this->alias]['id'],
				'hash_password' => $data[$this->alias]['hash_password'],
				'password_checkcode' => '',
				'expires' => null,
			),
		);
		return $this->save($_data, false, array('id', 'hash_password', 'password_checkcode', 'expires'));
	}

	/**
	 * forgotPassword
	 *
	 * @param string $email 
	 * @return mixed On success Model::$data if its not empty or true, false on failure
	 * @author Yasuo Harada
	 */
	public function forgotPassword($email) {
		// email存在確認
		$conditions = array($this->alias.'.email' => $email);
		$data = $this->find('first', compact('conditions'));
		if (!$data) {
			return false;
		}
		// uuid発行
		$_data = array(
			$this->alias => array(
				'id' => $data[$this->alias]['id'],
				'email' => $data[$this->alias]['email'],
				'password_checkcode' => String::uuid(),
				'expires' => date('Y-m-d H:i:s', strtotime('now '.$this->__expires)),
			),
		);
		return $this->save($_data, false, array('id', 'email', 'password_checkcode', 'expires'));
	}

	/**
	 * __findByEmailCheckcode
	 *
	 * @param string $emailCheckcode 
	 * @return array Array of records
	 * @author Yasuo Harada
	 */
	private function __findByEmailCheckcode($emailCheckcode) {
		// checkcode存在確認
		$conditions = array(
			$this->alias.'.email_checkcode' => $emailCheckcode,
			$this->alias.'.expires >' => date('Y-m-d H:i:s'),
		);
		return $this->find('first', compact('conditions'));
	}

	/**
	 * confirmRegister
	 *
	 * @param string $emailCheckcode 
	 * @return mixed On success Model::$data if its not empty or true, false on failure
	 * @author Yasuo Harada
	 */
	public function confirmRegister($emailCheckcode) {
		$data = $this->__findByEmailCheckcode($emailCheckcode);
		if (!$data) {
			return false;
		}
		// データ更新
		$_data = array(
			$this->alias => array(
				'id' => $data[$this->alias]['id'],
				'email_checkcode' => '',
				'disabled' => 0,
				'expires' => null,
			)
		);
		return $this->save($_data, false, array('id', 'email_checkcode', 'disabled', 'expires'));
	}

	/**
	 * confirmEmail
	 *
	 * @param string $emailCheckcode 
	 * @return mixed On success Model::$data if its not empty or true, false on failure
	 * @author Yasuo Harada
	 */
	public function confirmEmail($emailCheckcode) {
		$data = $this->__findByEmailCheckcode($emailCheckcode);
		if (!$data) {
			return false;
		}
		// データ更新
		$_data = array(
			$this->alias => array(
				'id' => $data[$this->alias]['id'],
				'email_checkcode' => '',
				'email' => $data[$this->alias]['email_tmp'],
				'email_tmp' => '',
				'expires' => null,
			),
		);
		return $this->save($_data, false, array('id', 'email_checkcode', 'email', 'email_tmp', 'expires'));
	}

	/*
	 * Static methods that can be used to retrieve the logged in user
	 * from anywhere
	 *
	 * Copyright (c) 2008 Matt Curry
	 * www.PseudoCoder.com
	 * http://github.com/mcurry/cakephp/tree/master/snippets/static_user
	 * http://www.pseudocoder.com/archives/2008/10/06/accessing-user-sessions-from-models-or-anywhere-in-cakephp-revealed/
	 *
	 * @author      Matt Curry <matt@pseudocoder.com>
	 * @license     MIT
	 *
	 */

	//in AppController::beforeFilter:
	//App::import('Model', 'User');
	//User::store($this->Auth->user());

	private static function &getInstance($user=null) {
		static $instance = array();

		if ($user) {
			$instance[0] =& $user;
		}

		if (!$instance) {
			trigger_error(__("User not set.", true), E_USER_WARNING);
			return false;
		}

		return $instance[0];
	}

	public static function store($user) {
		if (empty($user)) {
			return false;
		}

		User::getInstance($user);
	}

	public static function get($path) {
		$_user =& User::getInstance();

		$path = str_replace('.', '/', $path);
		if (strpos($path, 'User') !== 0) {
			$path = sprintf('User/%s', $path);
		}

		if (strpos($path, '/') !== 0) {
			$path = sprintf('/%s', $path);
		}

		$value = Set::extract($path, $_user);

		if (!$value) {
			return false;
		}

		return $value[0];
	}
}
?>