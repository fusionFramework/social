
<form id="form-<?=$resource;?>">
			<?php
			$form = $model->get_form(['title', 'content', 'status']);
			$form->content->add_class('wysiwyg');
			$form->status->set('opts', ['active' => 'Published', 'archived' => 'Draft']);
			echo $form->render();
			?>
</form>