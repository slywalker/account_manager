<?php
class AccountManagerAppModel extends AppModel {

	// Custom Validation Rule
	public function checkCompare($data, $suffix) {
		$field = key($data);
		$value = current($data);
		if (isset($this->data[$this->alias][$field.$suffix])) {
			return $value === $this->data[$this->alias][$field.$suffix];
		}
		return true;
	}

	// Validation message i18n
	public function invalidate($field, $value = true){
		parent::invalidate($field, $value);
		$this->validationErrors[$field] = __($value, true);
	}

}
?>