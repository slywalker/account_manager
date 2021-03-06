<?php
App::import('Core', array('AppModel', 'Model'));

if (!defined('CAKEPHP_UNIT_TEST_EXECUTION')) {
	define('CAKEPHP_UNIT_TEST_EXECUTION', 1);
}

class Article extends CakeTestModel {
	var $name = 'Article';
	var $actsAs = array('AccountManager.ForeignKey');
	var $belongsTo = array('User', 'Category');

	function callbackForeignKey() {
		return '4a6c5d0c-44d0-4fd5-b099-140c83b789e4';
	}
}

class Category extends CakeTestModel {
	var $name = 'Category';
	var $actsAs = array('AccountManager.ForeignKey');
	var $hasMany = array('Article');
}

App::import('Model', 'AccountManager.User');

class ForeignKeyBehaviorTest extends CakeTestCase {
	var $fixtures = array(
		'plugin.account_manager.article',
		'plugin.account_manager.user',
		'plugin.account_manager.category',
	);

	function startTest() {
		$this->User =& ClassRegistry::init('User');
		$this->Article =& ClassRegistry::init('Article');
		$this->Category =& ClassRegistry::init('Category');
	}

	function testBforeFindAndBforeValidateAndBeforeDelete() {
		// beforeFind
		$results = $this->Article->find('all');
		$this->assertIdentical(count($results), 0);

		// beforeValidate
		$data = array('Article' => array(
			'category_id' => 1,
			'title' => 'First Article',
			'body' => 'First Article Body',
			'published' => 'Y',
			'created' => '2007-03-18 10:39:23',
			'updated' => '2007-03-18 10:41:31',
		));
		$result = $this->Article->save($data);
		$this->assertIdentical($result['Article']['user_id'], '4a6c5d0c-44d0-4fd5-b099-140c83b789e4');
		
		$id = $this->Article->getInsertID();
		$data['Article']['id'] = $id;
		$result = $this->Article->save($data);
		$this->assertTrue(!isset($result['Article']['user_id']));

		// beforeFind
		$results = $this->Article->find('all', array('foreignKey' => false));
		$this->assertIdentical(count($results), 4);

		$this->Article->foreignKey();
		$results = $this->Article->find('all');
		$this->assertIdentical(count($results), 4);
		$this->Article->foreignKey('user_id');

		$results = $this->Article->find('all');
		$this->assertIdentical(count($results), 1);
		$id = $results[0]['Article']['id'];

		// assoc
		$results = $this->Category->find('all');
		$this->assertIdentical(count($results[0]['Article']), 1);
		$this->assertIdentical(count($results[1]['Article']), 0);

		$this->Category->Article->foreignKey();
		$results = $this->Category->find('all');
		$this->assertIdentical(count($results[0]['Article']), 3);
		$this->assertIdentical(count($results[1]['Article']), 1);
		$this->Category->Article->foreignKey('user_id');

		// beforeDelete
		$result = $this->Article->delete(1);
		$this->assertFalse($result);
		$result = $this->Article->delete($id);
		$this->assertTrue($result);
	}

	function endTest() {
		unset($this->Article);
		unset($this->User);

		ClassRegistry::flush();
	}
}
?>