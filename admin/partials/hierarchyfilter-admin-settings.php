<div class="wrap">
	<?php settings_errors(); ?>
	<form action="options.php" method="post">
	<?php
	settings_fields('hierarchyfilter_settings'); // a settings group name - should match the group name used in register_setting()
	do_settings_sections('hierarchyfilter_settings'); // the slug name of the page whose settings sections you want to output
	submit_button();
	?>
	</form>
</div>


