<?php 
/* SVN FILE: $Id$ */
/* User Test cases generated on: 2009-07-18 21:07:02 : 1247920802*/
App::import('Core', array('AppModel', 'Model'));

if (!defined('CAKEPHP_UNIT_TEST_EXECUTION')) {
	define('CAKEPHP_UNIT_TEST_EXECUTION', 1);
}

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
		$results = $this->User->find('all');
		$this->assertTrue(!empty($results));
	}

	function testUserChangeEmailAndConfirmEmail() {
		// メールアドレスバリデーションで失敗
		$data = array('User' => array(
			'id' => '4a6c5d0c-44d0-4fd5-b328-140c83b789e4',
			'email' => 'mail',
		));
		$results = $this->User->changeEmail($data);
		$this->assertIdentical($results, false);
		// 同一メールアドレスで失敗
		$this->User->create();
		$this->User->saveField('email', 'bar@hoge.hage');
		$data = array('User' => array(
			'id' => '4a6c5d0c-44d0-4fd5-b328-140c83b789e4',
			'email' => 'mail2@localhost.local',
		));
		$results = $this->User->changeEmail($data);
		$this->assertIdentical($results, false);
		// 成功
		$data = array('User' => array(
			'id' => '4a6c5d0c-44d0-4fd5-b328-140c83b789e4',
			'email' => 'mail3@localhost.loc',
		));
		$results = $this->User->changeEmail($data);
		$this->assertTrue(!empty($results));
		$this->assertIdentical($results['User']['email_tmp'], $data['User']['email']);
		$emailCheckcode = $results['User']['email_checkcode'];
		// データが置き換わっているか確認
		$this->User->recursive = -1;
		$results = $this->User->find('first', array('User.id' => '4a6c5d0c-44d0-4fd5-b328-140c83b789e4'));
		$this->assertTrue(!empty($results));
		$this->assertIdentical($results['User']['email'], 'mail@localhost.loc');
		$this->assertIdentical($results['User']['email_tmp'], $data['User']['email']);
		// 確認テスト
		$results = $this->User->confirmEmail('some-uuid');
		$this->assertIdentical($results, false);
		$results = $this->User->confirmEmail($emailCheckcode);
		$this->assertTrue(!empty($results));
		$this->assertIdentical($results['User']['email'], $data['User']['email']);
		$this->assertIdentical($results['User']['email_tmp'], '');
		$this->assertIdentical($results['User']['email_checkcode'], '');
		$this->assertIdentical($results['User']['expires'], null);
	}

	function testUserForgotPassword() {
		$results = $this->User->forgotPassword('mail99@localhost.loc');
		$this->assertIdentical($results, false);

		$results = $this->User->forgotPassword('mail2@localhost.loc');
		$this->assertTrue(!empty($results));
	}

	function testUserRegisterAndConfirmRegister() {
		// メールアドレスバリデーションで失敗
		$data = array('User' => array(
			'username' => 'test',
			'email' => 'mail',
			'password' => 'pass',
		));
		$results = $this->User->register($data);
		$this->assertIdentical($results, false);
		// 成功
		$data = array('User' => array(
			'username' => 'test',
			'email'  => 'mail100@localhost.loc',
			'password' => 'pass',
		));
		$results = $this->User->register($data);
		$this->assertTrue(!empty($results));
		$this->assertIdentical($results['User']['disabled'], 1);
		$emailCheckcode = $results['User']['email_checkcode'];
		// 確認テスト
		$results = $this->User->confirmRegister('some-uuid');
		$this->assertIdentical($results, false);
		$results = $this->User->confirmRegister($emailCheckcode);
		$this->assertTrue(!empty($results));
		$this->assertIdentical($results['User']['disabled'], 0);
		$this->assertIdentical($results['User']['email_checkcode'], '');
		$this->assertIdentical($results['User']['expires'], null);		
	}

	function testUserChangepassword() {
		$data = array('User' => array(
			'id' => '4a6c5d0c-44d0-4fd5-b328-140c83b789e4',
			'password' => 'password',
			'password_confirm' => 'not_password',
			'hash_password' => 'hash_password',
		));
		$results = $this->User->changePassword($data);
		$this->assertIdentical($results, false);
		$data = array('User' => array(
			'id' => '4a6c5d0c-44d0-4fd5-b328-140c83b789e4',
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