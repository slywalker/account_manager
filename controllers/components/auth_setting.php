<?php
/*
*  このコンポーネントの呼び出しは、AuthComponent, SecurityComponentの前!!
*/
class AuthSettingComponent extends Object {

	public function startup(&$controller) {
		if (!empty($controller->params[Configure::read('Routing.admin')])) {
			$this->__adminSettings($controller);
		} else {
			$this->__authSettings($controller);
		}
	}
	
	private function __authSettings(&$controller) {
		if (isset($controller->Auth)) {
			$controller->Auth->userModel = 'User';
			$controller->Auth->fields = array('username' => 'email', 'password' => 'hash_password');
			$controller->Auth->userScope = array('User.disabled' => 0);
			$controller->Auth->loginAction = array(Configure::read('Routing.admin') => false, 'plugin' => 'account_manager', 'controller' => 'users', 'action' => 'login');
			$controller->Auth->allow('*');
			$controller->Auth->deny('add', 'edit', 'delete');
			$user = $controller->Auth->user();
			Configure::write('Auth', $user['User']);
		}
	}

	private function __adminSettings(&$controller) {
		if (config('basic')) {
			$users = BASIC_CONFIG::$default;
			if (isset($controller->Security)) {
				$controller->Security->loginOptions = array('type'=>'basic');
				$controller->Security->loginUsers = $users;
				$controller->Security->requireLogin('*');
			}
		}
		if (isset($controller->Auth)) {
			$controller->Auth->allow('*');
		}
	}

}
?>
