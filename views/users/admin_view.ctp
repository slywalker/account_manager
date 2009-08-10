<div id="main">
	<div class="users view">
		<h2><?php  __d('account_manager', 'User');?></h2>
		<dl>
			<?php
			$lists = array();
			$lists[] = array('dt' => __d('account_manager', 'Id', true), 'dd' => h($user['User']['id']));
			$lists[] = array('dt' => __d('account_manager', 'Name', true), 'dd' => h($user['User']['username']));
			$lists[] = array('dt' => __d('account_manager', 'Email', true), 'dd' => h($user['User']['email']));
			$lists[] = array('dt' => __d('account_manager', 'Expires', true), 'dd' => h($user['User']['expires']));
			$lists[] = array('dt' => __d('account_manager', 'Email Checkcode', true), 'dd' => h($user['User']['email_checkcode']));
			$lists[] = array('dt' => __d('account_manager', 'Password Checkcode', true), 'dd' => h($user['User']['password_checkcode']));
			$lists[] = array('dt' => __d('account_manager', 'Disabled', true), 'dd' => h($user['User']['disabled']));
			$lists[] = array('dt' => __d('account_manager', 'Email Tmp', true), 'dd' => h($user['User']['email_tmp']));
			$lists[] = array('dt' => __d('account_manager', 'Modified', true), 'dd' => h($user['User']['modified']));
			$lists[] = array('dt' => __d('account_manager', 'Created', true), 'dd' => h($user['User']['created']));
			foreach ($lists as $key => $list) {
				$class = array();
				if ($key % 2 == 0) {
					$class = array('class' => 'altrow');
				}
				echo $html->tag('dt', $list['dt'], $class);
				echo $html->tag('dd', $list['dd'].'&nbsp;', $class);
			}
			?>
		</dl>
	</div>
</div>
<div id="sidebar">
	<div class="block">
		<h3><?php __d('account_manager', 'Actions');?></h3>
		<?php
		$li = array();
		$li[] = $html->link(__d('account_manager', 'Edit User', true), array('action' => 'edit', $user['User']['id']));
		$li[] = $html->link(__d('account_manager', 'Delete User', true), array('action' => 'delete', $user['User']['id']), null, __d('account_manager', 'Are you sure you want to delete?', true));
		$li[] = $html->link(__d('account_manager', 'List Users', true), array('action' => 'index'));
		$li[] = $html->link(__d('account_manager', 'New User', true), array('action' => 'add'));
		echo $html->nestedList($li, array('class'=>'navigation'));
		?>
	</div>
	<!--
	<div class="block notice">
		<h4>Notice Title</h4>
		<p>Morbi posuere urna vitae nunc. Curabitur ultrices, lorem ac aliquam blandit, lectus eros hendrerit eros, at eleifend libero ipsum hendrerit urna. Suspendisse viverra. Morbi ut magna. Praesent id ipsum. Sed feugiat ipsum ut felis. Fusce vitae nibh sed risus commodo pulvinar. Duis ut dolor. Cras ac erat pulvinar tortor porta sodales. Aenean tempor venenatis dolor.</p>
	</div>
	-->
</div>
