<?php 
/* SVN FILE: $Id$ */
/* AccountUser Test cases generated on: 2009-06-13 11:06:49 : 1244860069*/
App::import('Model', 'Account.AccountUser');

class AccountUserTestCase extends CakeTestCase {
	var $AccountUser = null;
	var $fixtures = array('plugin.account.account_user');

	function startTest() {
		$this->AccountUser =& ClassRegistry::init('AccountUser');
	}

	function testAccountUserInstance() {
		$this->assertTrue(is_a($this->AccountUser, 'AccountUser'));
	}
}
?>