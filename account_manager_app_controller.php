<?php
class AccountManagerAppController extends AppController {
	public $components = array('AccountManager.Qdmail', 'AccountManager.Qdsmtp');
	
	protected function _send($to, $subject, $template = 'default') {
		if (config('smtp')) {
			$params = SMTP_CONFIG::$default;
			$this->Qdmail->smtp(true);
			$this->Qdmail->smtpServer($params);
		}
		//$this->Qdmail->debug(2);
		$this->Qdmail->to($to);
		$this->Qdmail->from('noreplay@'.env('HTTP_HOST'));
		$this->Qdmail->subject($subject);
		
		$view = $this->view;
		$this->view = 'View';
		$this->Qdmail->cakeText(null, $template, null, null, 'iso-2022-jp');
		$this->view = $view;
		
		return $this->Qdmail->send();
	}

}
?>
