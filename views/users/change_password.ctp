<div id="main">
	<div class="users form">
		<?php
		echo $form->create('User', array('action' => 'change_password'));
		echo $form->inputs(array(
			'legend' => __('Change Password', true),
			'id',
			'password',
			'password_confirm' => array('type' => 'password'),
		));
		echo $form->end(__('Submit', true));
		?>
	</div>
</div>
<div id="sidebar">
	<div class="block">
		<h3><?php __('Actions');?></h3>
		<?php
		$li = array();
		$li[] =$html->link(__('View User', true), array('action' => 'view', $form->value('User.id')));
		echo $html->nestedList($li, array('class'=>'navigation'));
		?>
	</div>
	<div class="block notice">
		<h4>Notice Title</h4>
		<p>Morbi posuere urna vitae nunc. Curabitur ultrices, lorem ac aliquam blandit, lectus eros hendrerit eros, at eleifend libero ipsum hendrerit urna. Suspendisse viverra. Morbi ut magna. Praesent id ipsum. Sed feugiat ipsum ut felis. Fusce vitae nibh sed risus commodo pulvinar. Duis ut dolor. Cras ac erat pulvinar tortor porta sodales. Aenean tempor venenatis dolor.</p>
	</div>
</div>
