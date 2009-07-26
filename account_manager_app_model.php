<?php
class AccountManagerAppModel extends AppModel {

	// Custom Validation Rule
	protected function checkCompare(&$model, $data, $suffix) {
		$field = key($data);
		$value = current($data);
		if (isset($model->data[$model->alias][$field.$suffix])) {
			return $value === $model->data[$model->alias][$field.$suffix];
		}
		return true;
	}

	// Validation message i18n
	protected function invalidate($field, $value = true){
		parent::invalidate($field, $value);
		$this->validationErrors[$field] = __($value, true);
	}

}
?>