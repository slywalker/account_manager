<?php
/**
 * user.php
 *
 * @package AccountManager
 * @author Yasuo Harada
 * @copyright 2009 Slywalker Co,.Ltd.
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @date $LastChangedDate$
 * @version $Rev$
 **/

/**
 * User
 **/
class User extends AccountManagerAppModel {
	public $name = 'User';
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
	private $__expires = '+1 hour';

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

	private function _findByEmailCheckcode($emailCheckcode) {
		// checkcode存在確認
		$conditions = array(
			$this->alias.'.email_checkcode' => $emailCheckcode,
			$this->alias.'.expires >' => date('Y-m-d H:i:s'),
		);
		return $this->find('first', compact('conditions'));
	}

	public function confirmRegister($emailCheckcode) {
		$data = $this->_findByEmailCheckcode($emailCheckcode);
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

	public function confirmEmail($emailCheckcode) {
		$data = $this->_findByEmailCheckcode($emailCheckcode);
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

}
?>