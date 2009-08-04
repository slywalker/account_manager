<?php
class UsersController extends AccountManagerAppController {
	public $name = 'Users';
	public $uses = array('AccountManager.User');
	public $components = array('AccountManager.Qdmail', 'AccountManager.Qdsmtp');
	public $helpers = array('Gravatar');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('*');
		$this->Auth->deny('delete');
		
		if (isset($this->Security)) {
			$this->Security->disabledFields = array('hash_password', 'username');
		}
		if (!empty($this->data['User']['password'])) {
			$this->data['User']['hash_password'] = $this->data['User']['password'];
			if (!strpos($this->data['User']['email'], '@')) {
				$this->Auth->fields['username'] = 'username';
				$this->data['User']['username'] = $this->data['User']['email'];
			}
		}
	}

	public function admin_index() {
		$this->User->recursive = 0;
		$this->set('users', $this->paginate());
	}

	public function view($id = null) {
		if (is_null($id)) {
			$id = $this->Auth->user('id');
		}
		$conditions = array('User.id' => $id);
		$foreignKey = false;
		$user = $this->User->find('first', compact('conditions', 'foreignKey'));
		if (!$user) {
			$this->Session->setFlash(__d('account_manager', 'Invalid User', true));
			$this->redirect(array('action'=>'logout'));
		}
		$this->set(compact('user'));
	}

	public function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__d('account_manager', 'Invalid User', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->set('user', $this->User->read(null, $id));
	}

	public function admin_add() {
		if ($this->data) {
			$this->User->create();
			if ($this->User->save($this->data)) {
				$this->Session->setFlash(__d('account_manager', 'The User has been saved', true), 'default', array('class' => 'message success'));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__d('account_manager', 'The User could not be saved. Please, try again.', true));
			}
		}
	}

	public function admin_edit($id = null) {
		if (!$id && !$this->data) {
			$this->Session->setFlash(__d('account_manager', 'Invalid User', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->data) {
			if ($this->User->save($this->data)) {
				$this->Session->setFlash(__d('account_manager', 'The User has been saved', true), 'default', array('class' => 'message success'));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__d('account_manager', 'The User could not be saved. Please, try again.', true));
			}
		} else {
			$this->data = $this->User->read(null, $id);
		}
	}

	public function delete() {
		if ($this->User->delete($this->Auth->user('id'))) {
			$this->Session->setFlash(__d('account_manager', 'User deleted', true), 'default', array('class' => 'message success'));
			$this->redirect(array('action'=>'logout'));
		}
		$this->redirect(array('action'=>'view'));
	}

	public function admin_delete($id = null) {
		if (!$id) {
			if (isset($this->data['delete'])) {
				if ($this->User->deleteAll(array('User.id' => $this->data['delete']))) {
					$this->Session->setFlash(__d('account_manager', 'User deleted', true), 'default', array('class' => 'message success'));
				}
			}
		} else {
			if ($this->User->delete($id)) {
				$this->Session->setFlash(__d('account_manager', 'User deleted', true), 'default', array('class' => 'message success'));
			}
		}
		$this->redirect(array('action'=>'index'));
	}

	public function login() {
		$this->Session->write('Auth.redirect', '/');
	}

	public function logout() {
		$this->redirect($this->Auth->logout());
	}

	public function register() {
		if ($this->data) {
			$this->User->begin();
			if ($user = $this->User->register($this->data)) {
				// sendmail
				$this->set(compact('user'));
				if ($this->_send($user['User']['email'], 'Confirm Register', 'confirm_register')) {
					$this->User->commit();
					$this->Session->setFlash(__d('account_manager', 'A confirm mail has been sent', true), 'default', array('class' => 'message success'));
					$this->redirect(array('action'=>'login'));
				}
			}
			$this->User->rollback();
			$this->Session->setFlash(__d('account_manager', 'The User could not be saved. Please, try again.', true));
		}
	}

	public function forgot_password() {
		if (isset($this->data['User']['email'])) {
			$this->User->begin();
			if ($user = $this->User->forgotPassword($this->data['User']['email'])) {
				// sendmail
				$this->set(compact('user'));
				if ($this->_send($user['User']['email'], 'Change Password', 'forgot_password')) {
					$this->User->commit();
					$this->Session->setFlash(__d('account_manager', 'A confirm mail has been sent', true), 'default', array('class' => 'message success'));
					$this->redirect(array('action'=>'logout'));
				}
			}
			$this->User->rollback();
			$this->Session->setFlash(__d('account_manager', 'A confirm mail has been sent', true), 'default', array('class' => 'message success'));
			$this->redirect(array('action'=>'logout'));
		}
	}

	public function confirm_register($emailCheckcode = null) {
		if ($this->User->confirmRegister($emailCheckcode)) {
			$this->Session->setFlash(__d('account_manager', 'Confirm has been success', true), 'default', array('class' => 'message success'));
		} else {
			$this->Session->setFlash(__d('account_manager', 'Invalid URL', true));
		}
		$this->redirect(array('action'=>'logout'));
	}

	public function confirm_email($emailCheckcode = null) {
		if ($this->User->confirmEmail($emailCheckcode)) {
			$this->Session->setFlash(__d('account_manager', 'Confirm has been success', true), 'default', array('class' => 'message success'));
		} else {
			$this->Session->setFlash(__d('account_manager', 'Invalid URL', true));
		}
		$this->redirect(array('action'=>'logout'));
	}


	public function change_email() {
		$user = $this->User->find('first');
		if (!$user && !$this->data) {
			$this->Session->setFlash(__d('account_manager', 'Invalid User', true));
			$this->redirect(array('action'=>'logout'));
		}
		if ($this->data) {
			$this->User->begin();
			if ($user = $this->User->changeEmail($this->data)) {
				// sendmail
				$this->set(compact('user'));
				if ($this->_send($user['User']['email'], 'Confirm Email', 'confirm_email')) {
					$this->User->commit();
					$this->Session->setFlash(__d('account_manager', 'A confirm mail has been sent', true), 'default', array('class' => 'message success'));
					$this->redirect(array('action'=>'logout'));
				}
			}
			$this->User->rollback();
			$this->Session->setFlash(__d('account_manager', 'The Email could not be changed. Please, try again.', true));
		} else {
			$this->data = $user;
		}
	}

	public function change_password($id = null) {
		if (is_null($id)) {
			$id = $this->Auth->user('id');
		} else {
			$id = $this->User->field('id', array('password_checkcode' => $id));
		}
		if (!$id && !$this->data) {
			$this->Session->setFlash(__d('account_manager', 'Invalid URL', true));
			$this->redirect(array('action'=>'logout'));
		}
		if ($this->data) {
			$this->data['User']['hash_password'] = $this->Auth->password($this->data['User']['password']);
			$this->User->begin();
			if ($user = $this->User->changePassword($this->data)) {
				$this->User->commit();
				$this->Session->setFlash(__d('account_manager', 'The Password has been changed', true), 'default', array('class' => 'message success'));
				$this->redirect(array('action'=>'logout'));
			}
			$this->User->rollback();
			$this->Session->setFlash(__d('account_manager', 'The Password could not be changed. Please, try again.', true));
		} else {
			$this->data = $this->User->read(null, $id);
		}
	}

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