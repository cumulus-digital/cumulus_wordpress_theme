<?php
// Add option to disable sticky posts
\add_filter( 'admin_init', function () {
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
		'Disable sticky posts',
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
} );
