<div id="main">
	<div class="users form">
		<?php
		echo $form->create('User', array('action' => 'forgot_password'));
		echo $form->inputs(array(
			'legend' => __('Forgot Password', true),
			'email',
		));
		echo $form->end(__('Submit', true));
		?>
	</div>
</div>
<div id="sidebar">
	<div class="block notice">
		<h4>Notice Title</h4>
		<p>Morbi posuere urna vitae nunc. Curabitur ultrices, lorem ac aliquam blandit, lectus eros hendrerit eros, at eleifend libero ipsum hendrerit urna. Suspendisse viverra. Morbi ut magna. Praesent id ipsum. Sed feugiat ipsum ut felis. Fusce vitae nibh sed risus commodo pulvinar. Duis ut dolor. Cras ac erat pulvinar tortor porta sodales. Aenean tempor venenatis dolor.</p>
	</div>
</div>
