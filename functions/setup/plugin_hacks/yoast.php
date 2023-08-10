<?php
/**
 * Hacks for Yoast.
 */

namespace CumulusTheme\Setup\PluginHacks;

\defined( 'ABSPATH' ) || exit( 'No direct access allowed.' );

\add_action( 'init', function () {
	if ( \defined( 'WPSEO_VERSION' ) ) {
		// Remove Yoast ad
		// https://buddydev.com/remove-this-site-is-optimized-with-the-yoast-seo-plugin-vx-y-z/
		\add_action( 'template_redirect', function () {
			if ( ! \class_exists( 'WPSEO_Frontend' ) ) {
				return;
			}

			$instance = \WPSEO_Frontend::get_instance();

			// make sure future version of the plugin does not break our site.
			if ( ! \method_exists( $instance, 'debug_mark' ) ) {
				return;
			}

			// ok, let us remove the love letter.
			\remove_action( 'wpseo_head', array( $instance, 'debug_mark' ), 2 );
		}, \PHP_INT_MAX );

		// Move Yoast post box to bottom
		\add_filter( 'wpseo_metabox_prio', function () {
			return 'low';
		} );

		// Remove Yoast comments
		\add_filter( 'wpseo_debug_markers', '__return_false' );

		// Remove Yoast admin notifications
		\add_action( 'admin_init', function () {
			if ( \class_exists( 'Yoast_Notification_Center' ) ) {
				$yoast_nc = \Yoast_Notification_Center::get();
				\remove_action( 'admin_notices', array( $yoast_nc, 'display_notifications' ) );
				\remove_action( 'all_admin_notices', array( $yoast_nc, 'display_notifications' ) );
			}
		} );
	}
} );

// Collapse Yoast metabox by default
\add_action( 'current_screen', function () {
	$screen = \get_current_screen();

	if ( \is_admin() && \property_exists( $screen, 'post_type' ) ) {
		\add_filter( "get_user_option_closedpostboxes_{$screen->post_type}", function ( $closed ) {
			$closed[] = 'wpseo_meta';

			return $closed;
		}, 10, 1 );
	}
} );
