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

	public $components = array('Auth', 'Security');
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
		$this->Auth->userModel = 'User';
		$this->Auth->fields = array('username' => 'email', 'password' => 'hash_password');
		$this->Auth->userScope = array('User.disabled' => 0);
		$this->Auth->loginAction = array(Configure::read('Routing.admin') => false, 'plugin' => 'account_manager', 'controller' => 'users', 'action' => 'login');
		$this->Auth->loginError = __d('account_manager', 'Login failed. Invalid email or username, password.', true);
		$this->Auth->authError = __d('account_manager', 'You are not authorized to access that location.', true);
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
			$this->Security->loginOptions = array('type' => 'basic');
			$this->Security->loginUsers = $users;
			$this->Security->requireLogin('*');
		}
		$this->Auth->allow('*');
	}

	/**
	 * startup
	 *
	 * @param object $controller 
	 * @return void
	 * @author Yasuo Harada
	 */
	public function startup(&$controller) {
		Configure::write('AccountManager', $this->Auth->user());
	}
}
?>
