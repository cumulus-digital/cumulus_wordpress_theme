<?php

namespace CumulusTheme;

\defined( 'ABSPATH' ) || exit( 'No direct access allowed.' );

\add_action( 'admin_init', function () {
	\add_settings_section(
		'cmls-theme_settings-header_scripts',
		'Header Scripts',
		function () {
			?>
			<p>
				Enter HTML which will be injected immediately following the opening &lt;head&gt; tag.
				If the Cumulus Wordpress Security Headers plugin is installed, and auto-nonce is enabled,
				the output will be filtered.
			</p>
			<?php
		},
		'cmls-theme_settings'
	);

	\register_setting(
		'cmls-theme_settings',
		'cmls-header_scripts',
		array(
			'description'       => 'Header Scripts',
			'type'              => 'string',
			'sanitize_callback' => null,
			'default'           => null,
		)
	);
	\add_settings_field(
		'cmls-header_scripts',
		'<label for="cmls-header_scripts">Header Scripts</label>',
		function () {
			$value = \get_option( 'cmls-header_scripts', '' );
			echo '<textarea rows="10" id="cmls-header_scripts" name="cmls-header_scripts" class="regular-text" style="width: 600px; max-width: 100%">' . $value . '</textarea>';
		},
		'cmls-theme_settings',
		'cmls-theme_settings-header_scripts'
	);
}, 2 );

if ( ! empty( \get_option( 'cmls-header_scripts', false ) ) ) {
	\add_action( 'cmls_template-head-begin', function () {
		$value = \get_option( 'cmls-header_scripts', null );
		if ( ! empty( $value ) ) {
			$scripts = \apply_filters( 'cmls_wpsh_filter_scripts', \do_shortcode( $value, false ) );
			if ( ! empty( $scripts ) ) {
				echo $scripts;
			}
		}
	}, 10, 0 );
}
