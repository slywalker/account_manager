<?php
/**
 * auth_setting.php
 *
 * このコンポーネントの呼び出しは、AuthComponent, SecurityComponentの後ろ!!
 * This Component must be called after AuthComponent, SecurityComponent !!
 *
 * @package AccountManager
 * @author Yasuo Harada
 * @copyright 2009 SLYWALKER Co,.Ltd.
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @date $LastChangedDate$
 * @version $Rev$
 **/

/**
 * AuthSettingComponent
 **/
class AuthSettingComponent extends Object {

	/**
	 * initialize
	 *
	 * @param object $controller 
	 * @return void
	 * @author Yasuo Harada
	 */
	public function initialize(&$controller) {
		if (Configure::read('Routing.admin') && !empty($controller->params[Configure::read('Routing.admin')])) {
			$this->__adminSettings($controller);
		} else {
			$this->__authSettings($controller);
		}
	}
	
	/**
	 * __authSettings
	 *
	 * @param object $controller 
	 * @return void
	 * @author Yasuo Harada
	 */
	private function __authSettings(&$controller) {
		if (isset($controller->Auth)) {
			$controller->Auth->userModel = 'User';
			$controller->Auth->fields = array('username' => 'email', 'password' => 'hash_password');
			$controller->Auth->userScope = array('User.disabled' => 0);
			$controller->Auth->loginAction = array(Configure::read('Routing.admin') => false, 'plugin' => 'account_manager', 'controller' => 'users', 'action' => 'login');
			//$controller->Auth->allow('*');
			//$controller->Auth->deny('add', 'edit', 'delete');
		}
	}

	/**
	 * __adminSettings
	 *
	 * @param object $controller 
	 * @return void
	 * @author Yasuo Harada
	 */
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

	/**
	 * startup
	 *
	 * @param object $controller 
	 * @return void
	 * @author Yasuo Harada
	 */
	public function startup(&$controller) {
		$user = array();
		if (isset($controller->Auth)) {
			$user = $controller->Auth->user();
		}
		App::import('Model', 'User');
		User::store($user);
	}
}
?>
