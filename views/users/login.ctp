<div id="main">
	<div class="users form">
		<?php
		$session->flash('auth');
		echo $form->create('User', array('action' => 'login'));
		echo $form->inputs(array(
			'legend' => __('Login', true),
			'email',
			'password',
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
		$li[] = $html->link(__('Register User', true), array('action' => 'register'));
		$li[] = $html->link(__('Forgot Password', true), array('action' => 'forgot_password'));
		echo $html->nestedList($li, array('class'=>'navigation'));
		?>
	</div>
	<div class="block notice">
		<h4>Notice Title</h4>
		<p>Morbi posuere urna vitae nunc. Curabitur ultrices, lorem ac aliquam blandit, lectus eros hendrerit eros, at eleifend libero ipsum hendrerit urna. Suspendisse viverra. Morbi ut magna. Praesent id ipsum. Sed feugiat ipsum ut felis. Fusce vitae nibh sed risus commodo pulvinar. Duis ut dolor. Cras ac erat pulvinar tortor porta sodales. Aenean tempor venenatis dolor.</p>
	</div>
</div>
