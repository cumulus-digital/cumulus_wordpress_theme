<div class="wrap">
	<h2>Cumulus Theme Settings</h2>
	<form method="post" action="options.php">
		<?php
		\settings_fields( 'cmls-theme_settings' );
		\do_settings_sections( 'cmls-theme_settings' );
		\submit_button();
		?>
	</form>
</div>