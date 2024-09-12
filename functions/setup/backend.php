<?php
/**
 * Admin area scripts and styles.
 */

namespace CumulusTheme;

\defined( 'ABSPATH' ) || exit( 'No direct access allowed.' );

// Disable theme and plugin editor
if ( ! \defined( 'DISALLOW_FILE_EDIT' ) ) {
	\define( 'DISALLOW_FILE_EDIT', true );
}

// Disable the block directory
\add_action(
	'admin_init',
	function () {
		\remove_action( 'enqueue_block_editor_assets', 'wp_enqueue_editor_block_directory_assets' );
		\remove_action( 'enqueue_block_editor_assets', 'gutenberg_enqueue_block_editor_assets_block_directory' );
	}
);

// Add Reusable Blocks to the admin
\add_action( 'admin_menu', function () {
	\add_menu_page( 'Reusable Blocks', 'Reusable Blocks', 'edit_pages', 'edit.php?post_type=wp_block', '', 'dashicons-block-default', 22 );
} );

// Block editor styles
/*
function editorSetupStyles() {
	\add_editor_style( 'build/default_variables.css' );
	\add_editor_style( 'build/backend.css' );
}
\add_action( 'after_setup_theme', ns( 'editorSetupStyles' ), 11 );
 */

function backendSetupScripts() {
	global $pagenow;

	\wp_register_style(
		'google_montserrat_font',
		'https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,500;0,600;0,700;0,800;0,900;1,100;1,300;1,500;1,600;1,700;1,800;1,900&display=swap',
		false,
		null,
		'screen'
	);
	\wp_enqueue_style( 'google_montserrat_font' );

	\wp_register_style(
		'guttenberg_styles',
		theme_url() . '/assets/prod/editor.css',
		false,
		null,
		'screen'
	);
	\wp_enqueue_style( 'guttenberg_styles' );

	if ( ! \is_admin() || 'widgets.php' === $pagenow ) {
		return;
	}

	$assets = include theme_path() . '/assets/prod/editor.asset.php';
	\wp_enqueue_script(
		PREFIX . '-editor-script',
		theme_url() . '/assets/prod/editor.js',
		$assets['dependencies'],
		$assets['version'],
		true
	);

	// Custom blocks
	\wp_enqueue_script(
		'cumulus-gutenberg/anchor', // Handle.
		theme_url() . '/assets/prod/blocks-anchor.js',
		array( 'wp-blocks', 'wp-element' )
	);
}
\add_action( 'enqueue_block_editor_assets', ns( 'backendSetupScripts' ) );

// Brand the admin bar
\add_action( 'init', function () {
	if ( ! \is_user_logged_in() ) {
		return;
	}

	$logo = \preg_replace(
		'#https?://#i',
		'',
		\get_site_icon_url()
	);
	if ( ! $logo ) {
		return;
	}

	$color_brand     = '#00598e';
	$color_highlight = '#3399cc';

	\wp_register_style( PREFIX . '-branded_logo', '' );
	\wp_enqueue_style( PREFIX . '-branded_logo' );

	\wp_add_inline_style(
		PREFIX . '-branded_logo',
		"
		#wpadminbar #wp-admin-bar-wp-logo a {
			background: transparent !important;
		}
		#wpadminbar #wp-admin-bar-wp-logo,
		#editor .edit-post-header .edit-post-fullscreen-mode-close.has-icon {
			background-color: {$color_brand};
			background-image: url({$logo}) !important;
			background-position: center center !important;
			background-repeat: no-repeat !important;
			background-size: 70% !important;
		}
		#wpadminbar #wp-admin-bar-wp-logo:hover {
			background-color: {$color_highlight};
			background-image: url({$logo}) !important;
		}
		#wpadminbar #wp-admin-bar-wp-logo > .ab-item .ab-icon:before {
			content: '' !important;
		}
		.edit-post-fullscreen-mode-close.has-icon::before {
			box-shadow: none;
		}
		#editor .edit-post-header .edit-post-fullscreen-mode-close.has-icon svg {
			display: none;
		}
		"
	);
} );

// Brand the login page
\add_action( 'login_enqueue_scripts', function () {
	$logo = \preg_replace(
		'#https?://#i',
		'',
		\get_site_icon_url()
	);

	if ( ! $logo ) {
		return;
	}
	$color_masthead_bg = '#fff';
	$color_masthead_fg = '#111';
	$color_brand       = '#00598e';
	$color_highlight   = '#3399cc';

	\wp_register_style( PREFIX . '-branded_logo', '' );
	\wp_enqueue_style( PREFIX . '-branded_logo' );

	\wp_add_inline_style(
		PREFIX . '-branded_logo',
		"
		body {
			background-color: {$color_masthead_bg} !important;
		}
		#login h1 a, .login h1 a {
			background-image: url({$logo});
			background-size: contain;
			background-position: center center;
			background-repeat: no-repeat;
			width: 150px;
			height: 150px;
		}
		#loginform {
			border-radius: .5em;
		}
		#wp-submit {
			background-color: {$color_brand} !important;
		}
		.login #backtoblog a, .login #nav a {
			color: {$color_masthead_fg} !important;
		}
		.login #backtoblog a:hover, .login #nav a:hover {
			color: {$color_highlight} !important;
		}
		"
	);
} );
\add_action( 'login_headerurl', function () {
	return \home_url();
} );

// Add custom copyright field to Settings / General
function register_copyright_field() {
	\register_setting(
		'general',
		'copyright_info',
		array(
			'description'       => 'Copyright text displayed on site.',
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => null,
		)
	);
	\add_settings_field(
		'copyright_info',
		'<label for="copyright_info">' . \__( 'Copyright Info', 'copyright_info' ) . '</label>',
		function () {
			$value = \get_option( 'copyright_info', '' );
			echo '<input type="text" id="copyright_info" name="copyright_info" value="' . $value . '" class="regular-text" />';
		},
		'general'
	);
}
\add_filter( 'admin_init', ns( 'register_copyright_field' ) );

// Show custom copyright line in admin footer
function custom_admin_footer() {
	return \esc_html( \str_ireplace( '%YEAR%', \date( 'Y' ), \get_option( 'copyright_info' ) ) );
}
\add_filter( 'admin_footer_text', ns( 'custom_admin_footer' ) );

// Rename "Default Template"
\add_filter( 'default_page_template_title', function () {
	return \__( 'Basic', PREFIX );
} );

// Allow menu order for posts
\add_action( 'admin_init', function () {
	\add_post_type_support( 'post', 'page-attributes' );
} );
