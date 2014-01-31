<ul class="nav nav-pills" style="margin-bottom: 10px">
	<li class="active"><a href="#tab-info" data-toggle="tab">Info</a></li>
	<li><a href="#tab-options" data-toggle="tab">Options</a></li>
</ul>
<form id="form-<?=$resource;?>">
	<div class="tab-content">
		<div class="tab-pane fade in active" id="tab-info">
			<?php
			echo $model->get_form(['*'])
				->render('bootstrap/form_fields', array('name', 'description'));
			?>
		</div>
		<div class="tab-pane fade" id="tab-options">
			<?php
			echo $model->get_form(['*'])
				->render('bootstrap/form_fields', array('count_topics', 'record_last_topic', 'stickies',
				'count_replies', 'count_views', 'record_last_post'));
			?>
		</div>
	</div>
</form>