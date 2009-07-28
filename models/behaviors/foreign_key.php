<?php
/**
 * ForeignKeyBehavior
 * 
 * How to use.
 * Ex.
 * 	class AppModel extends Model {
 * 		var $actsAs = array('ForeignKey' => array('modelName' => 'User', 'foreignKey' => 'user_id', 'callback' => 'callbackForeignKey'));
 * 
 * 		function callbackForeignKey()
 * 		{
 * 			return Configure::read('User.id');
 * 		}
 * 	}
 *
 * @package default
 * @author Yasuo Harada
 */
App::import('Component', 'Session');

class ForeignKeyBehavior extends ModelBehavior {
	public $settings = array();

	public function setup(&$model, $config=array()) {
		$defalut = array(
			'modelName' => 'User',
			'foreignKey' => 'user_id',
			'callback' => 'callbackForeignKey',
		);
		$config = array_merge($defalut, $config);
		$this->settings[$model->name] = $config;
		
		$model->Session = new SessionComponent;
	}

	public function beforeFind(&$model, $query) {
		$fk = $this->settings[$model->name]['foreignKey'];
		if ($model->name === $this->settings[$model->name]['modelName']) {
			$id = $model->{$this->settings[$model->name]['callback']}();
			if ($id) {
				$conditions = array($model->name.'.id' => $id);
				$query['conditions'] = Set::merge($query['conditions'], $conditions);
			}
		}
		elseif ($model->hasField($fk)) {
			if (!isset($query['foreignKey']) || $query['foreignKey'] !== false) {
				$value = $model->{$this->settings[$model->name]['callback']}();
				if ($value) {
					$conditions = array($model->alias.'.'.$fk => $value);
					$query['conditions'] = Set::merge($query['conditions'], $conditions);
				} else {
					//trigger_error(__("ForeignKeyBehavior: Can't set at find foreign key [{$fk}] in {$model->alias}.", true), E_USER_ERROR);
				}
			}
		}
		return $query;
	}

	public function beforeValidate(&$model) {
		$fk = $this->settings[$model->name]['foreignKey'];
		if ($model->hasField($fk)) {
			$value = $model->{$this->settings[$model->name]['callback']}();
			if ($value && !isset($model->data[$model->alias][$fk])) {
				$model->data[$model->alias][$fk] = $value;
				return true;
			} else {
				//trigger_error(__("ForeignKeyBehavior: Can't set at save foreign key [{$fk}] in {$model->alias}.", true), E_USER_ERROR);
			}
			return false;
		}
		return true;
	}

	public function beforeDelete(&$model) {
		$fk = $this->settings[$model->name]['foreignKey'];
		if ($model->hasField($fk)) {
			$value = $model->{$this->settings[$model->name]['callback']}();
			if ($value && !isset($model->data[$model->alias][$fk])) {
				$conditions = array('id' => $model->id, $fk => $value);
				$recursive = -1;
				if ($model->find('first', compact('conditions', 'recursive'))) {
					return true;
				}
			} else {
				//trigger_error(__("ForeignKeyBehavior: Can't set at save foreign key [{$fk}] in {$model->alias}.", true), E_USER_ERROR);
			}
			return false;
		}
		return true;
	}

	public function callbackForeignKey(&$model) {
		return $model->Session->read('Auth.'.$this->settings[$model->name]['modelName'].'.id');
	}
}
?>