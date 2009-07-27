<?php 
/* SVN FILE: $Id$ */
/* User Fixture generated on: 2009-07-18 21:07:02 : 1247920802*/

class UserFixture extends CakeTestFixture {
	public $name = 'User';
	public $fields = array(
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

	public $records = array(
		array(
			'id' => '4a6c5d0c-44d0-4fd5-b328-140c83b789e4',
			'username'  => 'Lorem ipsum dolor sit amet',
			'email'  => 'mail@localhost.loc',
			'hash_password'  => 'Lorem ipsum dolor sit amet',
			'expires'  => '2009-07-18 21:40:02',
			'email_checkcode'  => 'Lorem ipsum dolor sit amet',
			'password_checkcode'  => 'Lorem ipsum dolor sit amet',
			'disabled'  => 1,
			'email_tmp'  => 'Lorem ipsum dolor sit amet',
			'modified'  => '2009-07-18 21:40:02',
			'created'  => '2009-07-18 21:40:02',
		),
		array(
			'id' => '4a6c5d0c-44d0-4fd5-b123-140c83b789e4',
			'username'  => 'Lorem ipsum dolor sit amet',
			'email'  => 'mail2@localhost.loc',
			'hash_password'  => 'Lorem ipsum dolor sit amet',
			'expires'  => '2009-07-18 21:40:02',
			'email_checkcode'  => 'Lorem ipsum dolor sit amet',
			'password_checkcode'  => 'Lorem ipsum dolor sit amet',
			'disabled'  => 1,
			'email_tmp'  => 'Lorem ipsum dolor sit amet',
			'modified'  => '2009-07-18 21:40:02',
			'created'  => '2009-07-18 21:40:02',
		),
	);
}
?>