<?php
/**
 * Force enqueued styles to preload.
 */

namespace CumulusTheme;

\defined( 'ABSPATH' ) || exit( 'No direct access allowed.' );

if ( \get_option( 'cmls-async_fonts', '1' ) === '1' ) {
	\add_filter( 'style_loader_tag', function ( $tag, $handle, $href, $media ) {
		// Don't alter real preload tags
		if (
			\mb_stristr( $tag, 'rel="preload"' )
			|| \mb_stristr( $tag, "rel='preload'" )
		) {
			return $tag;
		}

		if (
			$media     === 'preload'
			|| $handle === 'wp-block-library'
			|| \mb_stristr( $href, '&cmpreload' )
			|| \mb_stristr( $href, '?cmpreload' )
			|| \mb_stristr( $href, ';cmpreload' )
		) {
			$replace_media = array(
				'media="all"',
				"media='all'",
				'media="screen"',
				"media='screen'",
				'media="preload"',
				"media='preload'",
			);
			$replace_rel = array(
				'rel="stylesheet"',
				"rel='stylesheet'",
			);

			// $noscript = \str_ireplace( $replace_media, 'media="all"', $tag );
			$norel   = \str_ireplace( $replace_rel, '', $tag );
			$preload = \str_ireplace( $replace_media, 'rel="preload" as="style" fetchpriority="low"', $norel );

			return "{$preload}\n<noscript id={$handle}-noscript>{$tag}</noscript>";
			/*
			$replace_media = array(
				'media="all"',
				"media='all'",
				'media="screen"',
				"media='screen'",
				'media="preload"',
				"media='preload'",
			);

			$noscript = \str_ireplace( $replace_media, 'media="all"', $tag );
			$onload   = \str_ireplace( $replace_media, 'media="print" data-cmpreloading', $tag );

			return "{$onload}\n<noscript>{$noscript}</noscript>";
			 */
		}

		return $tag;
	}, \PHP_INT_MAX, 4 );

	\add_action( 'wp_enqueue_scripts', function () {
		\wp_register_script(
			PREFIX . '_script-swap_preloading_styles',
			theme_url() . '/assets/prod/swap-preloading-styles.js',
			null,
			null,
			false
		);
		\wp_enqueue_script( PREFIX . '_script-swap_preloading_styles' );
	}, \PHP_INT_MIN );
}
