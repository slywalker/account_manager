<?php
/*
*  このコンポーネントの呼び出しは、AuthComponentの前!!
*/
class AccountKitAuthComponent extends Object {
	var $components = array('Session');

	function startup(&$controller)
	{
		if (isset($controller->Auth)) {
			if (isset($controller->data['AccountUser']['raw_password'])) {
				$controller->data['AccountUser']['password']
					= $controller->data['AccountUser']['raw_password'];
			}
			$controller->Auth->userModel = 'AccountUser';
			$controller->Auth->fields = array(
				'username' => 'email',
				'password' => 'password',
			);
			$controller->Auth->userScope = array('AccountUser.disable' => 0);
			$controller->Auth->loginAction  = '/login';
			$controller->Auth->loginRedirect = '/admin/statuses';
			$controller->Auth->autoRedirect = false;
			$controller->Auth->loginError = 'ログインに失敗しました';
			$controller->Auth->authError = 'ログインしてください';

			if (!isset($controller->params['admin'])) {
				$controller->Auth->allow('*');
			}
			$controller->params['Auth']['fields'] = $this->Auth->fields;
		}
	}
}
?>