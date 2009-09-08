<?php
/**
 * account_manager_app_model.php
 *
 * @package AccountManager
 * @author Yasuo Harada
 * @copyright 2009 SLYWALKER Co,.Ltd.
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @date $LastChangedDate$
 * @version $Rev$
 **/

/**
 * AccountManagerAppModel
 **/
class AccountManagerAppModel extends AppModel {

	/**
	 * checkCompare
	 * 
	 * Custom Validation Rule
	 *
	 * @param array $data 
	 * @param string $suffix 
	 * @return boolean
	 * @author Yasuo Harada
	 */
	public function checkCompare($data, $suffix) {
		$field = key($data);
		$value = current($data);
		if (isset($this->data[$this->alias][$field.$suffix])) {
			return $value === $this->data[$this->alias][$field.$suffix];
		}
		return true;
	}

	/**
	 * invalidate
	 * 
	 * Validation message i18n
	 *
	 * @param string $field 
	 * @param boolean $value 
	 * @return void
	 * @author Yasuo Harada
	 */
	public function invalidate($field, $value = true){
		parent::invalidate($field, $value);
		$this->validationErrors[$field] = __d('account_manager', $value, true);
	}

}
?>