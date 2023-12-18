<?php

namespace CumulusTheme;

\defined( 'ABSPATH' ) || exit( 'No direct access allowed.' );

\add_action( 'admin_menu', function () {
	\add_options_page(
		'Cumulus Theme Settings',
		'CMLS Settings',
		'switch_themes',
		'cmls-theme_settings',
		function () {
			require theme_path() . '/settings.php';
		},
		\PHP_INT_MAX
	);
} );
\add_filter( 'admin_init', function () {
	\add_settings_section(
		'cmls-theme_settings-general',
		'General Settings',
		null,
		'cmls-theme_settings'
	);

	// Add option in writing settings to disable sticky posts
	\register_setting(
		'cmls-theme_settings',
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
		'cmls-theme_settings',
		'cmls-theme_settings-general'
	);

	// Add option in general settings to async load our fonts and block library
	\register_setting(
		'cmls-theme_settings',
		'cmls-async_fonts',
		array(
			'description'       => 'Defer loading custom web fonts',
			'type'              => 'boolean',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => '1',
		)
	);
	\add_settings_field(
		'cmls-async_fonts',
		'<label for="cmls-async_fonts">Defer Web Font</label>',
		function () {
			$value = \get_option( 'cmls-async_fonts' );
			?>
				<label for="cmls-async_fonts">
					<input type="hidden" name="cmls-async_fonts" value="0" />
					<input type="checkbox" id="cmls-async_fonts" name="cmls-async_fonts" <?php echo $value === '1' ? 'checked' : ''; ?> value="1" />
					Defer loading custom web fonts and base WordPress block styles.<br>
					<small style="max-width: 500px">Note that other plugins may interfere with or not be affected by this setting.</small>
				</label>
			<?php
		},
		'cmls-theme_settings',
		'cmls-theme_settings-general'
	);
}, 1 );

require __DIR__ . '/settings/quantcast-choice.php';
require __DIR__ . '/settings/header-scripts.php';
