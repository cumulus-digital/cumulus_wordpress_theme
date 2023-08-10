<?php
/**
 * Disable defaults.
 */

namespace CumulusTheme\Setup\Security;

\defined( 'ABSPATH' ) || exit( 'No direct access allowed.' );

\add_action( 'init', function () {
	// Disable user registration
	if ( \get_option( 'users_can_register' ) ) {
		\update_option( 'users_can_register', false );
	}

	// Remove sticky posts
	if ( \get_option( 'sticky_posts' ) ) {
		\update_option( 'sticky_posts', array() );
	}
} );

// Remove users from core sitemap
\add_filter( 'wp_sitemaps_add_provider', function ( $provider, $name ) {
	if ( $name === 'users' || \is_a( $provider, 'WP_Sitemaps_Users' ) ) {
		return false;
	}

	return $provider;
}, 10, 2 );

// Disable potentially dangerous REST endpoints for non-logged-in users.
\add_filter( 'rest_endpoints', function ( $endpoints ) {
	if ( \is_user_logged_in() || \is_admin() ) {
		return $endpoints;
	}

	$disable = array(
		'/wp/v2/users',
		'/wp/v2/users/me',
		'/wp/v2/users/(?P<id>[\d]+)',
		'/wp/v2/users/(?P<user_id>(?:[\d]+|me))/application-passwords',
		'/wp/v2/users/(?P<user_id>(?:[\d]+|me))/application-passwords/introspect',
		'/wp/v2/users/(?P<user_id>(?:[\d]+|me))/application-passwords/(?P<uuid>[\w\-]+)',
		'/wp/v2/plugins',
		'/wp/v2/plugins/(?P<plugin>[^.\/]+(?:\/[^.\/]+)?)',
		'/acf/v3/users',
		'/acf/v3/users/(?P<id>[\\d]+)/?(?P<field>[\\w\\-\\_]+)?',
		'/acf/v3/users/(?P<id>[\d]+)/?(?P<field>[\w\-\_]+)?',
	);

	foreach ( $disable as $key => $endpoint ) {
		if ( isset( $endpoints[$endpoint] ) ) {
			unset( $endpoints[$endpoint] );
		}
	}

	return $endpoints;
} );

// Disable XML-RPC
\add_filter( 'xmlrpc_enabled', '__return_false' );
