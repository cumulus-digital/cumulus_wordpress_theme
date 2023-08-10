<?php
/**
 * Disable defaults.
 */

namespace CumulusTheme\Setup\Security;

\defined( 'ABSPATH' ) || exit( 'No direct access allowed.' );

// Disable pingbacks, pings, comments, and registration
\add_action( 'init', function () {
	$disable_options = array(
		'default_pingback_flag'        => 0,
		'default_ping_status'          => 'closed',
		'default_comment_status'       => 'closed',
		'close_comments_for_old_posts' => 1,
		'comments_notify'              => 1,
		'comment_moderation'           => 1,
		'comment_registration'         => 1,
	);

	foreach ( $disable_options as $key => $value ) {
		$opt = \get_option( $key );

		if ( (string) $opt !== (string) $value ) {
			\update_option( $key, $value );
		}
	}
} );

// Disable comments on attachments
\add_filter( 'wp_insert_post_data', function ( $data ) {
	if ( $data['post_type'] === 'attachment' ) {
		$data['comment_status'] = 'closed';
		$data['ping_status']    = 'closed';
	}

	return $data;
} );

// Comments are never open.
\add_filter( 'comments_open', '__return_false', 999, 2 );
\add_filter( 'pings_open', '__return_false', 999, 2 );

// Disable comment support for all post types
\add_action( 'init', function () {
	$post_types = \get_post_types();

	foreach ( $post_types as $post_type ) {
		if ( \post_type_supports( $post_type, 'comments' ) ) {
			\remove_post_type_support( $post_type, 'comments' );
			\remove_post_type_support( $post_type, 'trackbacks' );
		}
	}
} );

// Remove comment references in admin
\add_action( 'admin_init', function () {
	// Remove Comments from dashboard at a glance
	\remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );

	// Remove admin nav items
	// Core WP doesn't check if this is an array first!
	global $menu;

	if ( \is_array( $menu ) ) {
		\remove_menu_page( 'edit-comments.php' );
	}

	// Disable comment support for all post types
	$post_types = \get_post_types();

	foreach ( $post_types as $post_type ) {
		// Remove metaboxes
		\remove_meta_box( 'commentsdiv', $post_type, 'normal' );
		\remove_meta_box( 'commentstatusdiv', $post_type, 'normal' );
		\remove_meta_box( 'trackbacksdiv', $post_type, 'normal' );
	}
} );
\add_action( 'admin_bar_menu', function ( $menu ) {
	$menu->remove_node( 'comments' );
} );
\add_action( 'wp_before_admin_bar_render', function () {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu( 'comments' );
} );

// Remove comments links from admin bar
\add_action( 'init', function () {
	if ( \is_admin_bar_showing() ) {
		\remove_action( 'admin_bar_menu', 'wp_admin_bar_comments_menu', 60 );
	}
} );
