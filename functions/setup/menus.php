<?php
/**
 * Menu initialization.
 */

namespace CumulusTheme;

\defined( 'ABSPATH' ) || exit( 'No direct access allowed.' );

function init_nav_support() {
	\register_nav_menus( array(
		'header-menu' => \__( 'Header Menu' ),
		// 'extra-header-menu' => \__( 'Before-Hamburger Menu (Desktop only!)' ),
		'footer-menu' => \__( 'Footer Menu' ),
		'social-menu' => \__( 'Footer Social' ),
	) );
}
\add_action( 'after_setup_theme', ns( 'init_nav_support' ) );

function makeMenu( $location, $options = array() ) {
	$defaults = array(
		'theme_location'  => $location,
		'menu'            => '',
		'container'       => '',
		'container_class' => 'menu-{menu slug}-container',
		'container_id'    => '',
		'menu_class'      => 'menu',
		'menu_id'         => $location,
		'echo'            => true,
		'fallback_cb'     => 'wp_page_menu',
		'before'          => '',
		'after'           => '',
		'link_before'     => '',
		'link_after'      => '',
		'items_wrap'      => '<ul itemscope itemtype="http://www.schema.org/SiteNavigationElement" class="%2$s" role="menu">%3$s</ul>',
		'depth'           => 0,
		'walker'          => new CleanMenuWalker(),
	);
	$args        = \array_merge( $defaults, $options );
	$cache_key   = \serialize( array( $location, $args ) );
	$cache_group = 'CumulusTheme::makeMenu';

	$should_echo = $args['echo'];

	$menu = CMLS_Cache::get( $cache_key, $cache_group );

	if ( $menu === false ) {
		$args['echo'] = false;
		$menu         = \wp_nav_menu( $args );
		CMLS_Cache::set( $cache_key, $menu, $cache_group, 30 * 60 );
	}

	if ( $should_echo ) {
		echo $menu;

		return;
	}

	return $menu;
}

function header_menu() {
	makeMenu( 'header-menu', array(
		'container'       => 'nav',
		'container_class' => 'menu menu-header-container',
		'container_id'    => 'header-menu-container',
	) );
}
function has_header_menu() {
	return \has_nav_menu( 'header-menu' );
}

function extra_header_menu() {
	makeMenu( 'extra-header-menu' );
}

function has_extra_header_menu() {
	return \has_nav_menu( 'extra-header-menu' );
}

function footer_menu() {
	makeMenu( 'footer-menu', array(
		'container'       => '',
		'container_class' => 'menu-footer-menu-container',
		'container_id'    => 'footer-menu-container',
	) );
}
function has_footer_menu() {
	return \has_nav_menu( 'footer-menu', array( 'show_description' => false ) );
}

function social_menu( $options = array() ) {
	makeMenu(
		'social-menu',
		\array_merge(
			array(
				'menu_class'       => 'social social-link-icons',
				'link_before'      => '<i>',
				'link_after'       => '</i>',
				'show_description' => false,
			),
			$options
		)
	);
}
function has_social_menu() {
	return \has_nav_menu( 'social-menu' );
}

// Flush menu cache after menu updated
\add_action( 'wp_update_nav_menu', function () {
	CMLS_Cache::flushGroup( 'CumulusTheme::makeMenu' );
} );
