<?php

namespace CumulusTheme\Setup\PluginHacks;

\defined( 'ABSPATH' ) || exit( 'No direct access allowed.' );

// Hacks to make Git Updater work better
\add_filter( 'admin_init', function () {
	\register_setting(
		'general',
		'cmls-github_key',
		array(
			'description'       => 'Github Key for Git Updater',
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => null,
		)
	);
	\add_settings_field(
		'cmls-github_key',
		'<label for="cmls-github_key">Github Key</label>',
		function () {
			$value = \get_option( 'cmls-github_key' );
			?>
				<input type="text" id="cmls-github_key" name="cmls-github_key" value="<?php echo \esc_attr( $value ); ?>" class="regular-text" />
				<p><small>Automatically re-injects Github key into Git Updater settings when Git Updater wipes it out.</small></p>
			<?php
		},
		'general'
	);
} );
\add_action( 'init', function () {
	$key = \get_option( 'cmls-github_key' );

	if ( ! $key ) {
		return;
	}
	$gu_config = \get_option( 'git_updater' );

	if (
		$gu_config
		&& \is_array( $gu_config )
		&& (
			! \array_key_exists( 'github_access_token', $gu_config )
			|| \mb_strlen( $gu_config['github_access_token'] ) < 1
		)
	) {
		$gu_config['github_access_token'] = $key;
		\update_site_option( 'git_updater', $gu_config );
	}
}, \PHP_INT_MAX );

// Remove nag
\add_action( 'admin_init', function () {
	$hooks = _wp_array_get( $GLOBALS, array( 'wp_filter', 'admin_notices' ) );
	if ( \is_object( $hooks ) && \property_exists( $hooks, 'callbacks' ) ) {
		$callbacks = $hooks->callbacks;
		if ( $callbacks && \is_array( $callbacks ) ) {
			foreach ( $callbacks as $priority => $callback ) {
				foreach ( $callback as $ref => $action ) {
					if (
						\array_key_exists( 'function', $action )
						&& \is_array( $action['function'] )
						&& \count( $action['function'] ) > 1
						&& $action['function'][1] === 'get_license'
						&& \is_a( $action['function'][0], 'Fragen\Git_Updater\Messages' )
					) {
						\remove_action( 'admin_notices', $ref );

						break 2;
					}
				}
			}
		}
	}
} );
