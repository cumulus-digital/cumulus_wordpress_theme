<?php
/**
 * Required plugins
 * Uses TGM Plugin Activation library
 * http://tgmpluginactivation.com.
 */

namespace CumulusTheme;

\defined( 'ABSPATH' ) || exit( 'No direct access allowed.' );

require_once theme_path() . '/assets/prod/composer/vendor/tgmpa/tgm-plugin-activation/class-tgm-plugin-activation.php';

function registerRequiredPlugins() {
	$plugins = array(
		array(
			'name'             => 'Advanced Custom Fields',
			'slug'             => 'advanced-custom-fields',
			'required'         => true,
			'force_activation' => true,
			'is_callable'      => 'acf_register_block_type',
		),
		array(
			'name'             => 'Safe SVG',
			'slug'             => 'safe-svg',
			'required'         => true,
			'force_activation' => true,
		),
		/*
		array(
			'name'     => 'Jetpack by WordPress.com',
			'slug'     => 'jetpack',
			'required' => false,
		),
		 */
		array(
			'name'     => 'Kadence Blocks',
			'slug'     => 'kadence-blocks',
			'required' => false,
		),
	);

	$config = array(
		// Unique ID for hashing notices for multiple instances of TGMPA.
		'id' => PREFIX,
		// Default absolute path to bundled plugins.
		'default_path' => '',
		// Menu slug.
		'menu' => PREFIX . '-install-plugins',
		// Parent menu slug.
		'parent_slug' => 'themes.php',
		// Capability needed to view plugin install page,
		// should be a capability associated with the parent menu used.
		'capability' => 'edit_theme_options',
		// Show admin notices or not.
		'has_notices' => true,
		// If false, a user cannot dismiss the nag message.
		'dismissable' => false,
		// If 'dismissable' is false, this message will be output at top of nag.
		'dismiss_msg' => '',
		// Automatically activate plugins after installation or not.
		'is_automatic' => true,
		// Message to output right before the plugins table.
		'message' => '',
		'strings' => array(),
	);

	tgmpa( $plugins, $config );
}
\add_action( 'tgmpa_register', ns( 'registerRequiredPlugins' ) );
