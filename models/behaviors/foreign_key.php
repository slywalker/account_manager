<?php
/**
 * foreign_key.php
 * 
 * How to use.
 * Ex.
 *	class AppModel extends Model {
 *		var $actsAs = array(
 *			'ForeignKey' => array(
 *				'User' => array(
 *					'foreignKey' => 'user_id',
 *					'callback' => 'callbackForeignKey',
 *				),
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

/**
 * ForeignKeyBehavior
 **/
class ForeignKeyBehavior extends ModelBehavior {

	/**
	 * $settings
	 *
	 * @var array
	 */
	public $settings = array();

	/**
	 * setup
	 *
	 * @param object $model 
	 * @param array $config 
	 */
	public function setup(&$model, $config=array()) {
		$defalut = array(
			'User' => array(
				'foreignKey' => 'user_id',
				'callback' => 'callbackForeignKey',
			),
		);
		$settings = array();
		foreach ($config as $modelName => $con) {
			if (!is_array($con)) {
				$modelName = $con;
				$con = array();
			}
			if (!isset($con['foreignKey'])) {
				$con['foreignKey'] = Inflector::underscore($modelName).'_id';
			}
			if (!isset($con['callback'])) {
				$con['callback'] = 'callbackForeignKey'.$modelName;
			}
			$settings[$modelName] = $con;
		}
		$this->settings[$model->alias] = Set::merge($defalut, $settings);
	}

	/**
	 * foreignKey
	 *
	 * @param object $model 
	 */
	public function foreignKey(&$model) {
		$args = func_get_args();
		$args = call_user_func_array('am', array_slice($args, 1));
		if (count($args) > 1) {
			$this->settings[$model->alias][$args[0]]['foreignKey'] = $args[1];
		} elseif (count($args) == 1) {
			$this->settings[$model->alias]['User']['foreignKey'] = $args[0];
		} else {
			$this->settings[$model->alias]['User']['foreignKey'] = false;
		}
	}

	/**
	 * beforeFind
	 *
	 * @param object $model 
	 * @param array $query 
	 * @return array
	 */
	public function beforeFind(&$model, $query) {
		foreach ($this->settings[$model->alias] as $modelName => $settings) {
			$fk = $settings['foreignKey'];
			if (isset($query['foreignKey'])) {
				if (isset($query['foreignKey'][$modelName])) {
					$fk = $query['foreignKey'][$modelName];
				} elseif ($query['foreignKey'] === false) {
					$fk = false;
				}
			}
			if ($fk && $model->alias === $modelName) {
				$id = $model->{$settings['callback']}();
				if ($id) {
					$conditions = array($model->alias.'.id' => $id);
					$query['conditions'] = Set::merge($query['conditions'], $conditions);
				}
			}
			elseif ($fk && $model->hasField($fk)) {
				$fkValue = $model->{$settings['callback']}();
				if ($fkValue) {
					$conditions = array($model->alias.'.'.$fk => $fkValue);
					$query['conditions'] = Set::merge($query['conditions'], $conditions);
				}
			}
		
			if ($fk) {
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
							$fk = $this->settings[$_model][$modelName]['foreignKey'];
							if (isset($query['contain'][$_model]['foreignKey'])) {
								if (isset($query['contain'][$_model]['foreignKey'][$modelName])) {
									$fk = $query['contain'][$_model]['foreignKey'][$modelName];
								} elseif ($query['contain'][$_model]['foreignKey'] === false) {
									$fk = false;
								}
							}
							if($fk && $model->{$_model}->hasField($fk)) {
								$fkValue = $model->{$_model}->{$this->settings[$_model][$modelName]['callback']}();
								if ($fkValue) {
									$conditions = array($_model.'.'.$fk => $fkValue);
									$assocParams = Set::merge($assocParams, compact('conditions'));
									$model->bindModel(array($type => array($_model => $assocParams)));
								}
							}
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
	 */
	public function beforeValidate(&$model) {
		foreach ($this->settings[$model->alias] as $modelName => $settings) {
			$fk = $settings['foreignKey'];
			if ($fk && !$model->id && $model->hasField($fk)) {
				$value = $model->{$settings['callback']}();
				if ($value && !isset($model->data[$model->alias][$fk])) {
					$model->data[$model->alias][$fk] = $value;
				}
			}
		}
		return true;
	}

	/**
	 * beforeDelete
	 *
	 * @param object $model 
	 * @return void
	 */
	public function beforeDelete(&$model) {
		$foreignKeyConditions = array();
		foreach ($this->settings[$model->alias] as $modelName => $settings) {
			$fk = $settings['foreignKey'];
			if ($fk && $model->hasField($fk)) {
				$value = $model->{$settings['callback']}();
				if (!$value) {
					return false;
				}
				$foreignKeyConditions[$fk] = $value;
			}
		}
		$conditions = array_merge($foreignKeyConditions, array('id' => $model->id));
		$recursive = -1;
		if (!$model->find('first', compact('conditions', 'recursive'))) {
			return false;
		}
		return true;
	}

	/**
	 * callbackForeignKey
	 *
	 * @param object $model 
	 * @return integer or string, user_id
	 */
	public function callbackForeignKey(&$model) {
		return Configure::read('AccountManager.User.id');
	}
}
?>