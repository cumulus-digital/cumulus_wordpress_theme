<?php
/**
 * Force enqueued styles to preload.
 */

namespace CumulusTheme;

\defined( 'ABSPATH' ) || exit( 'No direct access allowed.' );

if ( \get_option( 'cmls-async_fonts', '1' ) === '1' ) {
	\add_filter( 'style_loader_tag', function ( $tag, $handle, $href, $media ) {
		// Don't alter real preload tags or ones with existing onload attributes
		if (
			\mb_stristr( $tag, 'rel="preload"' )
			|| \mb_stristr( $tag, "rel='preload'" )
		) {
			return $tag;
		}

		if (
			$media     === 'preload'
			|| $handle === 'wp-block-library'
			|| \mb_stristr( $href, '&preload' )
			|| \mb_stristr( $href, '?preload' )
			|| \mb_stristr( $href, ';preload' )
		) {
			$replace_media = array(
				'media="all"',
				"media='all'",
				'media="screen"',
				"media='screen'",
				'media="preload"',
				"media='preload'",
			);

			$noscript = \str_ireplace( $replace_media, 'media="all"', $tag );
			$onload   = \str_ireplace( $replace_media, 'media="print" data-preloading', $tag );

			return "{$onload}\n<noscript>{$noscript}</noscript>";
		}

		return $tag;
	}, \PHP_INT_MAX, 4 );

	\add_action( 'wp_enqueue_scripts', function () {
		\wp_register_script( PREFIX . '-preload-style', '', array( ), false, false );
		\wp_enqueue_script( PREFIX . '-preload-style', '', array( ), false, false );

		\wp_add_inline_script(
			PREFIX . '-preload-style',
			"
	document.addEventListener('DOMContentLoaded', function () {
		var styles = document.querySelectorAll('link[data-preloading]');
		[].forEach.call(styles, function(s) {
			if (s.sheet) {
				s.media='all';
			} else {
				s.addEventListener('load', function() { this.media = 'all'; });
			}
		});
	});
		"
		);
	}, 1 );
}
