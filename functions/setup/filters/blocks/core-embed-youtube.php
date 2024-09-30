<?php
/**
 * Automatically replace youtube embeds with youtube-nocookie.
 */

namespace CumulusTheme\BlockFilters\CoreEmbedYoutube;

\defined( 'ABSPATH' ) || exit( 'No direct access allowed.' );

function replaceYouttube( $html ) {
	if ( \mb_stripos( $html, 'youtube.com/embed' ) === false ) {
		return $html;
	}

	return \str_ireplace( 'youtube.com/embed', 'youtube-nocookie.com/embed', $html );
}

if ( \get_option( 'cmls-youtube_nocookie', '1' ) === '1' ) {
	foreach ( array( 'oembed_result', 'embed_oembed_html', 'embed_handler_html' ) as $hook ) {
		\add_filter( $hook, function ( $html ) {
			return replaceYouttube( $html );
		}, \PHP_INT_MAX, 1 );
	}

	\add_filter( 'render_block', function ( $html, $block ) {
		if (
			! \in_array( $block['blockName'], array( 'core/embed', 'core/html' ) )
		) {
			return $html;
		}

		return replaceYouttube( $html );
	}, 10, 2 );
}
