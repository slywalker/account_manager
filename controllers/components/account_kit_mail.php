<?php
class AccountKitMailComponent extends Object {
	var $components = array('App.AppQdmail');
	var $Controller = null;
	var $_to = null;
	var $_from = null;
	var $_subject = null;
	
	function startup(&$controller) {
		$this->Controller = $controller;
		$this->from('noreplay@'.env('HTTP_HOST'));
	}

	function to($to) {
		$this->_to = $to;
		return $this;
	}

	function from($from) {
		$this->_from = $from;
		return $this;
	}

	function subject($subject) {
		$this->_subject = $subject;
		return $this;
	}
	
	function set($name, $data = null) {
		return $this->Controller->set($name, $data);
	}
	
	function send($template) {
		//$this->AppQdmail->debug(2);
		$this->AppQdmail->to($this->_to);
		$this->AppQdmail->from($this->_from);
		$this->AppQdmail->subject($this->_subject);
		$this->AppQdmail->template($template);
		return $this->AppQdmail->send();
	}
}
?>