<?php 
/* SVN FILE: $Id$ */
/* User Test cases generated on: 2009-07-18 21:07:02 : 1247920802*/
App::import('Model', 'AccountManager.User');

class UserTestCase extends CakeTestCase {
	public $User = null;
	public $fixtures = array('plugin.account_manager.user');

	function startTest() {
		$this->User =& ClassRegistry::init('User');
	}

	function testUserInstance() {
		$this->assertTrue(is_a($this->User, 'User'));
	}

	function testUserFind() {
		$this->User->recursive = -1;
		$results = $this->User->find('first');
		$this->assertTrue(!empty($results));

		$expected = array('User' => array(
			'id'  => 1,
			'name'  => 'Lorem ipsum dolor sit amet',
			'email'  => 'foo@hoge.hage',
			'hash_password'  => 'Lorem ipsum dolor sit amet',
			'expires'  => '2009-07-18 21:40:02',
			'email_checkcode'  => 'Lorem ipsum dolor sit amet',
			'password_checkcode'  => 'Lorem ipsum dolor sit amet',
			'disabled'  => 1,
			'email_tmp'  => 'Lorem ipsum dolor sit amet'
		));
		$this->assertEqual($results, $expected);
	}

	function testUserChangeEmailAndConfirmEmail() {
		// メールアドレスバリデーションで失敗
		$data = array('User' => array(
			'id' => 1,
			'email' => 'foo',
		));
		$results = $this->User->changeEmail($data);
		$this->assertIdentical($results, false);
		// 同一メールアドレスで失敗
		$this->User->create();
		$this->User->saveField('email', 'bar@hoge.hage');
		$data = array('User' => array(
			'id' => 1,
			'email' => 'bar@hoge.hage',
		));
		$results = $this->User->changeEmail($data);
		$this->assertIdentical($results, false);
		// 成功
		$data = array('User' => array(
			'id' => 1,
			'email' => 'slywalker.net@gmail.com',
		));
		$results = $this->User->changeEmail($data);
		$this->assertTrue(!empty($results));
		$this->assertIdentical($results['User']['email_tmp'], $data['User']['email']);
		$emailCheckcode = $results['User']['email_checkcode'];
		// データが置き換わっているか確認
		$this->User->recursive = -1;
		$results = $this->User->find('first', array('User.id' => 1));
		$this->assertTrue(!empty($results));
		$this->assertIdentical($results['User']['email'], 'foo@hoge.hage');
		$this->assertIdentical($results['User']['email_tmp'], $data['User']['email']);
		// 確認テスト
		$results = $this->User->confirmEmail('Lorem ipsum dolor sit amet');
		$this->assertIdentical($results, false);
		$results = $this->User->confirmEmail($emailCheckcode);
		$this->assertTrue(!empty($results));
		$this->assertIdentical($results['User']['email'], $data['User']['email']);
		$this->assertIdentical($results['User']['email_tmp'], '');
		$this->assertIdentical($results['User']['email_checkcode'], '');
		$this->assertIdentical($results['User']['expires'], null);
	}

	function testUserForgotPassword() {
		$results = $this->User->forgotPassword('not_exist_email');
		$this->assertIdentical($results, false);

		$results = $this->User->forgotPassword('foo@hoge.hage');
		$this->assertTrue(!empty($results));
	}

	function testUserRegisterAndConfirmRegister() {
		// メールアドレスバリデーションで失敗
		$data = array('User' => array(
			'name' => 'Lorem ipsum dolor sit amet',
			'email' => 'foo',
			'password' => 'Lorem ipsum dolor sit amet',
		));
		$results = $this->User->register($data);
		$this->assertIdentical($results, false);
		// 成功
		$data = array('User' => array(
			'name' => 'test',
			'email'  => 'slywalker.net@gmail.com',
			'password' => 'pass',
		));
		$results = $this->User->register($data);
		$this->assertTrue(!empty($results));
		$this->assertIdentical($results['User']['disabled'], 1);
		$emailCheckcode = $results['User']['email_checkcode'];
		// 確認テスト
		$results = $this->User->confirmRegister('Lorem ipsum dolor sit amet');
		$this->assertIdentical($results, false);
		$results = $this->User->confirmRegister($emailCheckcode);
		$this->assertTrue(!empty($results));
		$this->assertIdentical($results['User']['disabled'], 0);
		$this->assertIdentical($results['User']['email_checkcode'], '');
		$this->assertIdentical($results['User']['expires'], null);		
	}

	function testUserChangepassword() {
		$data = array('User' => array(
			'id' => 1,
			'password' => 'password',
			'password_confirm' => 'not_password',
			'hash_password' => 'hash_password',
		));
		$results = $this->User->changePassword($data);
		$this->assertIdentical($results, false);
		$data = array('User' => array(
			'id' => 1,
			'password' => 'password',
			'password_confirm' => 'password',
			'hash_password' => 'hash_password',
		));
		$results = $this->User->changePassword($data);
		$this->assertTrue(!empty($results));
		$this->assertIdentical($results['User']['hash_password'], $data['User']['hash_password']);
		$this->assertIdentical($results['User']['password_checkcode'], '');
		$this->assertIdentical($results['User']['expires'], null);
		// データが置き換わっているか確認
		$this->User->recursive = -1;
		$results = $this->User->find('first', array('User.id' => 1));
		$this->assertTrue(!empty($results));
		$this->assertIdentical($results['User']['hash_password'], $data['User']['hash_password']);
		$this->assertIdentical($results['User']['password_checkcode'], '');
		$this->assertIdentical($results['User']['expires'], null);
	}

}
?>