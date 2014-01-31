
<form id="form-<?=$resource;?>">
			<?php
			echo $model->get_form(['title', 'description', 'status'])
				->render();
			?>

</form>