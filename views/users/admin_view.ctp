<div id="main">
	<div class="users view">
		<h2><?php  __('User');?></h2>
		<dl>
			<?php
			$lists = array();
			$lists[] = array('dt' => __('Id', true), 'dd' => h($user['User']['id']));
			$lists[] = array('dt' => __('Name', true), 'dd' => h($user['User']['username']));
			$lists[] = array('dt' => __('Email', true), 'dd' => h($user['User']['email']));
			$lists[] = array('dt' => __('Expires', true), 'dd' => h($user['User']['expires']));
			$lists[] = array('dt' => __('Email Checkcode', true), 'dd' => h($user['User']['email_checkcode']));
			$lists[] = array('dt' => __('Password Checkcode', true), 'dd' => h($user['User']['password_checkcode']));
			$lists[] = array('dt' => __('Disabled', true), 'dd' => h($user['User']['disabled']));
			$lists[] = array('dt' => __('Email Tmp', true), 'dd' => h($user['User']['email_tmp']));
			$lists[] = array('dt' => __('Modified', true), 'dd' => h($user['User']['modified']));
			$lists[] = array('dt' => __('Created', true), 'dd' => h($user['User']['created']));
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
		<h3><?php __('Actions');?></h3>
		<?php
		$li = array();
		$li[] = $html->link(__('Edit User', true), array('action' => 'edit', $user['User']['id']));
		$li[] = $html->link(__('Delete User', true), array('action' => 'delete', $user['User']['id']), null, __('Are you sure you want to delete?', true));
		$li[] = $html->link(__('List Users', true), array('action' => 'index'));
		$li[] = $html->link(__('New User', true), array('action' => 'add'));
		echo $html->nestedList($li, array('class'=>'navigation'));
		?>
	</div>
	<div class="block notice">
		<h4>Notice Title</h4>
		<p>Morbi posuere urna vitae nunc. Curabitur ultrices, lorem ac aliquam blandit, lectus eros hendrerit eros, at eleifend libero ipsum hendrerit urna. Suspendisse viverra. Morbi ut magna. Praesent id ipsum. Sed feugiat ipsum ut felis. Fusce vitae nibh sed risus commodo pulvinar. Duis ut dolor. Cras ac erat pulvinar tortor porta sodales. Aenean tempor venenatis dolor.</p>
	</div>
</div>
