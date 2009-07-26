<?php
$li = array();
if ($session->check('Auth.User')) {
	$li[] = $html->link(__('User', true), array(Configure::read('Routing.admin') => false, 'plugin' => 'account_manager', 'controller' => 'users', 'action' => 'view'));
	$li[] = $html->link(__('Sign Out', true), array(Configure::read('Routing.admin') => false, 'plugin' => 'account_manager', 'controller' => 'users', 'action' => 'logout'));
} else {
	$li[] = $html->link(__('Sign In', true), array(Configure::read('Routing.admin') => false, 'plugin' => 'account_manager', 'controller' => 'users', 'action' => 'login'));
}
echo $html->nestedList($li);
?>