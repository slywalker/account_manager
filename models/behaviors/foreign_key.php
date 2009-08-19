<?php
/**
 * foreign_key.php
 * 
 * How to use.
 * Ex.
 *	class AppModel extends Model {
 *		var $actsAs = array(
 *			'ForeignKey' => array(
 *				'modelName' => 'User',
 *				'foreignKey' => 'user_id',
 *				'callback' => 'callbackForeignKey',
 *			),
 *		);
 * 
 *		function callbackForeignKey() {
 *			return Configure::read('User.id');
 *		}
 *	}
 *
 * @package AccountManager
 * @author Yasuo Harada
 * @copyright 2009 SLYWALKER Co,.Ltd.
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @date $LastChangedDate$
 * @version $Rev$
 */
App::import('Component', 'Session');

/**
 * ForeignKeyBehavior
 **/
class ForeignKeyBehavior extends ModelBehavior {

	/**
	 * settings
	 *
	 * @var array
	 */
	public $settings = array();

	/**
	 * setup
	 *
	 * @param object $model 
	 * @param array $config 
	 * @author Yasuo Harada
	 */
	public function setup(&$model, $config=array()) {
		$defalut = array(
			'modelName' => 'User',
			'foreignKey' => 'user_id',
			'callback' => 'callbackForeignKey',
		);
		$config = array_merge($defalut, $config);
		$this->settings[$model->alias] = $config;
		
		$model->Session = new SessionComponent;
	}

	/**
	 * foreignKey
	 *
	 * @param object $model 
	 * @author Yasuo Harada
	 */
	public function foreignKey(&$model) {
		$args = func_get_args();
		$foreignKey = call_user_func_array('am', array_slice($args, 1));
		if (isset($foreignKey[0])) {
			$this->settings[$model->alias]['foreignKey'] = $foreignKey[0];
		} else {
			$this->settings[$model->alias]['foreignKey'] = false;
		}
	}

	/**
	 * beforeFind
	 *
	 * @param object $model 
	 * @param array $query 
	 * @return array
	 * @author Yasuo Harada
	 */
	public function beforeFind(&$model, $query) {
		$fk = $this->settings[$model->alias]['foreignKey'];
		if (isset($query['foreignKey'])) {
			$fk = $query['foreignKey'];
		}
		if ($fk && $model->name === $this->settings[$model->alias]['modelName']) {
			$id = $model->{$this->settings[$model->alias]['callback']}();
			if ($id) {
				$conditions = array($model->name.'.id' => $id);
				$query['conditions'] = Set::merge($query['conditions'], $conditions);
			}
		}
		elseif ($fk && $model->hasField($fk)) {
			$fkValue = $model->{$this->settings[$model->alias]['callback']}();
			if ($fkValue) {
				$conditions = array($model->alias.'.'.$fk => $fkValue);
				$query['conditions'] = Set::merge($query['conditions'], $conditions);
			}
		}
		
		$assocs['hasMany'] = $model->hasMany;
		$assocs['hasOne'] = $model->hasOne;
		$assocs['hasAndBelongsToMany'] = $model->hasAndBelongsToMany;
		foreach ($assocs as $type=>$assoc) {
			if (!empty($assoc)) {
				foreach ($assoc as $key=>$alias) {
					$_model = $alias;
					$assocParams = array();
					if (is_array($alias)) {
						$_model = $key;
						$assocParams = $alias;
					}
					$fk = $this->settings[$_model]['foreignKey'];
					if (isset($query['contain'][$_model]['foreignKey'])) {
						$fk = $query['contain'][$_model]['foreignKey'];
					}
					if($fk && $model->{$_model}->hasField($fk)) {
						$fkValue = $model->{$_model}->{$this->settings[$_model]['callback']}();
						if ($fkValue) {
							$conditions = array($_model.'.'.$fk => $fkValue);
							$assocParams = Set::merge($assocParams, compact('conditions'));
							$model->bindModel(array($type => array($_model => $assocParams)));
						}
					}
				}
			}
		}
		return $query;
	}

	/**
	 * beforeValidate
	 *
	 * @param object $model 
	 * @return void
	 * @author Yasuo Harada
	 */
	public function beforeValidate(&$model) {
		$fk = $this->settings[$model->alias]['foreignKey'];
		if ($fk && !$model->id && $model->hasField($fk)) {
			$value = $model->{$this->settings[$model->alias]['callback']}();
			if ($value && !isset($model->data[$model->alias][$fk])) {
				$model->data[$model->alias][$fk] = $value;
				return true;
			} else {
				//trigger_error(__d('account_manager', "ForeignKeyBehavior: Can't set at save foreign key [{$fk}] in {$model->alias}.", true), E_USER_ERROR);
			}
			return false;
		}
		return true;
	}

	/**
	 * beforeDelete
	 *
	 * @param object $model 
	 * @return void
	 * @author Yasuo Harada
	 */
	public function beforeDelete(&$model) {
		$fk = $this->settings[$model->alias]['foreignKey'];
		if ($fk && $model->hasField($fk)) {
			$value = $model->{$this->settings[$model->alias]['callback']}();
			if ($value && !isset($model->data[$model->alias][$fk])) {
				$conditions = array('id' => $model->id, $fk => $value);
				$recursive = -1;
				if ($model->find('first', compact('conditions', 'recursive'))) {
					return true;
				}
			} else {
				//trigger_error(__d('account_manager', "ForeignKeyBehavior: Can't set at save foreign key [{$fk}] in {$model->alias}.", true), E_USER_ERROR);
			}
			return false;
		}
		return true;
	}

	/**
	 * callbackForeignKey
	 *
	 * @param object $model 
	 * @return integer or string, user_id
	 * @author Yasuo Harada
	 */
	public function callbackForeignKey(&$model) {
		return $model->Session->read('Auth.'.$this->settings[$model->alias]['modelName'].'.id');
	}
}
?>