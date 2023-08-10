<?php
/**
 * Init theme support.
 */

namespace CumulusTheme;

\defined( 'ABSPATH' ) || exit( 'No direct access allowed.' );

function init_theme_support() {
	\add_theme_support( 'custom-logo', array(
		'width'      => 250,
		'height'     => 250,
		'flex-width' => true,
	) );
	\add_theme_support( 'menus' );
	\add_theme_support( 'editor-styles' );
	// \add_editor_style('build/backend.css');
	\add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption', 'style', 'script' ) );

	\add_theme_support( 'align-wide' );
	\add_theme_support( 'custom-spacing' );
	\add_theme_support( 'custom-units' );

	\add_theme_support( 'title-tag' );
	\add_theme_support( 'wp-block-styles' );
	\add_theme_support( 'responsive-embeds' );
	\add_theme_support( 'post-thumbnails', array( 'page', 'post' ) );
	\remove_theme_support( 'post-formats' );

	// /\remove_theme_support( 'core-block-patterns' );
	\remove_theme_support( 'block-templates' );
	// \remove_theme_support( 'widgets-block-editor' );

	\add_post_type_support( 'page', 'excerpt' );

	// Enable color palette
	\add_theme_support( 'editor-color-palette', array(
		array(
			'name'  => \__( 'White', 'cumuluswp' ),
			'slug'  => 'white',
			'color' => '#fff',
		),
		array(
			'name'  => \__( 'Black', 'cumuluswp' ),
			'slug'  => 'black',
			'color' => '#000',
		),
		array(
			'name'  => \__( 'Grey', 'cumuluswp' ),
			'slug'  => 'grey',
			'color' => '#888',
		),
		array(
			'name'  => \__( 'Light Grey', 'cumuluswp' ),
			'slug'  => 'light-grey',
			'color' => '#d6d6d6',
		),
		array(
			'name'  => \__( 'Dark Grey', 'cumuluswp' ),
			'slug'  => 'cumulus-dark-grey',
			'color' => '#333',
		),
		array(
			'name'  => \__( 'Cumulus Blue', 'cumuluswp' ),
			'slug'  => 'cumulus-blue',
			'color' => '#00598e',
		),
		array(
			'name'  => \__( 'Cumulus Light Blue', 'cumuluswp' ),
			'slug'  => 'cumulus-light-blue',
			'color' => '#3399cc',
		),
		array(
			'name'  => \__( 'WWOPN Purple', 'cumuluswp' ),
			'slug'  => 'wwopn-purple',
			'color' => '#6a2774',
		),
		array(
			'name'  => \__( 'WWOPN Light Purple', 'cumuluswp' ),
			'slug'  => 'wwopn-light-purple',
			'color' => '#a33cb3',
		),
	) );

	// Additional editor font styles
	\add_theme_support( 'editor-font-sizes', array(
		array(
			'name'      => \__( 'Default', 'cumuluswp' ),
			'shortName' => \__( 'default', 'cumuluswp' ),
			'size'      => 18,
			'slug'      => 'default',
		),
		array(
			'name'      => \__( 'Small', 'cumuluswp' ),
			'shortName' => \__( 'S', 'cumuluswp' ),
			'size'      => 12,
			'slug'      => 'small',
		),
		array(
			'name'      => \__( 'Medium', 'cumuluswp' ),
			'shortName' => \__( 'M', 'cumuluswp' ),
			'size'      => 16,
			'slug'      => 'medium',
		),
		array(
			'name'      => \__( 'Large', 'cumuluswp' ),
			'shortName' => \__( 'L', 'cumuluswp' ),
			'size'      => 20,
			'slug'      => 'large',
		),
		array(
			'name'      => \__( 'Larger', 'cumuluswp' ),
			'shortName' => \__( 'XL', 'cumuluswp' ),
			'size'      => 24,
			'slug'      => 'larger',
		),
	) );

	// Disable custom font sizes for all but admin
	if ( ! \current_user_can( 'administrator' ) ) {
		\add_theme_support( 'disable-custom-font-sizes' );
	}
}
\add_action( 'after_setup_theme', ns( 'init_theme_support' ), 10 );

/*
global $content_width;
$content_width = 1200;
 */

// Separate stylesheets for blocks
\add_filter( 'should_load_separate_core_block_assets', '__return_false' );
