<div id="main">
	<div class="users view">
		<h2><?php  __('User');?></h2>
		<dl>
			<?php
			$lists = array();
			$lists[] = array('dt' => __('Icon', true), 'dd' => $html->image($gravatar->url($user['User']['email'], 50), array('url' => 'http://www.gravatar.com/')));
			$lists[] = array('dt' => __('Name', true), 'dd' => h($user['User']['username']));
			if (Configure::read('Auth.id') === $user['User']['id']) {
				$lists[] = array('dt' => __('Email', true), 'dd' => h($user['User']['email']));
			}
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
	<?php if (Configure::read('Auth.id') === $user['User']['id']) :?>
	<div class="block">
		<h3><?php __('Actions');?></h3>
		<?php
		$li = array();
		$li[] =$html->link(__('Change Email', true), array('action' => 'change_email'));
		$li[] =$html->link(__('Change Password', true), array('action' => 'change_password'));
		$li[] = $html->link(__('Delete User', true), array('action' => 'delete'), null, __('Are you sure you want to delete?', true));
		echo $html->nestedList($li, array('class'=>'navigation'));
		?>
	</div>
	<?php endif;?>
	<div class="block notice">
		<h4>Notice Title</h4>
		<p>Morbi posuere urna vitae nunc. Curabitur ultrices, lorem ac aliquam blandit, lectus eros hendrerit eros, at eleifend libero ipsum hendrerit urna. Suspendisse viverra. Morbi ut magna. Praesent id ipsum. Sed feugiat ipsum ut felis. Fusce vitae nibh sed risus commodo pulvinar. Duis ut dolor. Cras ac erat pulvinar tortor porta sodales. Aenean tempor venenatis dolor.</p>
	</div>
</div>
