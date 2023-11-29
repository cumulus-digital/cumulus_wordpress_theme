<?php
/**
 * Hacks for All In One SEO.
 */

namespace CumulusTheme\Setup\PluginHacks;

use CumulusTheme\CMLS_Cache;

\defined( 'ABSPATH' ) || exit( 'No direct access allowed.' );

function handleAioSeo() {
	$cache_group       = 'CumulusTheme::aioseoPostsNoindex';
	$cache_key_noindex = 'aioseo_posts_noindex';

	// Collapse the AIOSEO editor metabox by default
	\add_action( 'current_screen', function () {
		$screen = \get_current_screen();

		if ( \is_admin() && \property_exists( $screen, 'post_type' ) ) {
			\add_filter( "get_user_option_closedpostboxes_{$screen->post_type}", function ( $closed ) {
				$closed[] = 'aioseo-settings';

				return $closed;
			}, 10, 1 );
		}
	} );

	// Remove AIOSEO bug from admin bar
	\add_action( 'admin_bar_menu', function ( $wp_admin_bar ) {
		$wp_admin_bar->remove_node( 'aioseo-main' );
	}, \PHP_INT_MAX );

	// Remove AIOSEO crap from admin menu
	\add_action( 'admin_menu', function () {
		// Redirects under Tools
		\remove_submenu_page( 'tools.php', \admin_url( '/admin.php?page=aioseo-redirects' ) );
		\remove_submenu_page( 'aioseo', 'aioseo-link-assistant' );
		\remove_submenu_page( 'aioseo', 'aioseo-redirects' );
		\remove_submenu_page( 'aioseo', 'aioseo-local-seo' );
		\remove_submenu_page( 'aioseo', 'aioseo-seo-analysis' );
		\remove_submenu_page( 'aioseo', 'aioseo-search-statistics' );
	}, \PHP_INT_MAX );

	// Exclude specifically noindexed content from site search results
	\add_filter( 'pre_get_posts', function ( $query ) use ( $cache_group, $cache_key_noindex ) {
		if ( \is_admin() ) {
			return $query;
		}

		if ( \function_exists( 'aioseo' ) && $query->is_main_query() && $query->is_search ) {
			$cached = CMLS_Cache::get( $cache_key_noindex, $cache_group );

			if ( $cached !== false ) {
				$noIndex = $cached;
			} else {
				global $wpdb;
				$noIndex = $wpdb->get_col( "
					SELECT post_id FROM {$wpdb->prefix}aioseo_posts WHERE robots_noindex = 1
				" );
				CMLS_Cache::set( $cache_key_noindex, $noIndex, $cache_group );
			}

			if ( $noIndex && \count( $noIndex ) ) {
				$post_not_in = $query->get( 'post__not_in' );
				$post_not_in = \array_merge( $post_not_in, $noIndex );
				$query->set( 'post__not_in', $post_not_in );
			}
		}

		return $query;
	} );
	// Clear the noindex cache on post save
	\add_filter( 'post_save', function () use ( $cache_group, $cache_key_noindex ) {
		CMLS_Cache::delete( $cache_key_noindex, $cache_group );
	} );
}

if ( \function_exists( 'aioseo' ) ) {
	handleAioSeo();
}
