<?php
/**
 * Global inits.
 */

namespace CumulusTheme;

\defined( 'ABSPATH' ) || exit( 'No direct access allowed.' );

// Attempt to force auto-updates
\add_filter( 'auto_update_plugin', '__return_true' );
\add_filter( 'auto_update_theme', '__return_true' );
\add_filter( 'auto_update_translation', '__return_true' );

// Set up global script included in both front and backend.
function registerGlobalScript() {
	$assets = include theme_path() . '/assets/prod/index.asset.php';
	\wp_register_script(
		PREFIX . '_script',
		theme_url() . '/assets/prod/index.js',
		$assets['dependencies'],
		$assets['version'],
		true
	);
	\wp_register_style(
		PREFIX . '_style',
		theme_url() . '/assets/prod/index.css',
		array(),
		null,
		'screen'
	);
	\wp_register_style(
		'google_montserrat_font',
		'https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,500;0,600;0,700;0,800;0,900;1,100;1,300;1,500;1,600;1,700;1,800;1,900&display=swap&preload',
		false,
		null,
		'all'
	);
}
function enqueueGlobalScript() {
	\wp_enqueue_script( PREFIX . '_script' );
	\wp_enqueue_style( PREFIX . '_style' );
	\wp_enqueue_style( 'google_montserrat_font' );
}

\add_action( 'wp_loaded', ns( 'registerGlobalScript' ) );
\add_action( 'wp_enqueue_scripts', ns( 'enqueueGlobalScript' ) );
// \add_action( 'admin_enqueue_scripts', ns( 'enqueueGlobalScript' ) );
