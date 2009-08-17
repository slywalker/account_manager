<?php
/**
 * user.php
 * 
 * Schema users
 *
 * @package AccountManager
 * @author Yasuo Harada
 * @copyright 2009 Slywalker Co,.Ltd.
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @date $LastChangedDate$
 * @version $Rev$
 **/

/**
 * UsersSchema
 **/
class UsersSchema extends CakeSchema {
	var $name = 'Users';

	function before($event = array()) {
		return true;
	}

	function after($event = array()) {
	}

	var $users = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary'),
		'username' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'email' => array('type' => 'string', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'hash_password' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'expires' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'email_checkcode' => array('type' => 'string', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'password_checkcode' => array('type' => 'string', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'disabled' => array('type' => 'boolean', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'email_tmp' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'email' => array('column' => 'email', 'unique' => 0), 'expires' => array('column' => 'expires', 'unique' => 0), 'email_checkcode' => array('column' => 'email_checkcode', 'unique' => 0), 'password_checkcode' => array('column' => 'password_checkcode', 'unique' => 0), 'disabled' => array('column' => 'disabled', 'unique' => 0))
	);
}
?>