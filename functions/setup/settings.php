<?php
\add_filter( 'admin_init', function () {
	// Add option in writing settings to disable sticky posts
	\register_setting(
		'writing',
		'cmls-disable_sticky',
		array(
			'description'       => 'Disable sticky posts',
			'type'              => 'boolean',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => '1',
		)
	);
	\add_settings_field(
		'cmls-disable_sticky',
		'<label for="cmls-disable_sticky">Disable sticky posts</label>',
		function () {
			$value = \get_option( 'cmls-disable_sticky' );
			?>
				<label for="cmls-disable_sticky">
					<input type="hidden" name="cmls-disable_sticky" value="0" />
					<input type="checkbox" id="cmls-disable_sticky" name="cmls-disable_sticky" <?php echo $value === '1' ? 'checked' : ''; ?> value="1" />
					Disable sticky posts
				</label>
			<?php
		},
		'writing'
	);

	// Add option in general settings to async load our fonts and block library
	\register_setting(
		'general',
		'cmls-async_fonts',
		array(
			'description'       => 'Use async method for theme customizer web font',
			'type'              => 'boolean',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => '1',
		)
	);
	\add_settings_field(
		'cmls-async_fonts',
		'<label for="cmls-async_fonts">Async Web Font</label>',
		function () {
			$value = \get_option( 'cmls-async_fonts' );
			?>
				<label for="cmls-async_fonts">
					<input type="hidden" name="cmls-async_fonts" value="0" />
					<input type="checkbox" id="cmls-async_fonts" name="cmls-async_fonts" <?php echo $value === '1' ? 'checked' : ''; ?> value="1" />
					Load theme's web font asynchronously.
				</label>
			<?php
		},
		'general'
	);
} );
