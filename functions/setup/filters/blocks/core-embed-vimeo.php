<?php
/**
 * Automatically add dnt=1 to vimeo embeds.
 */

namespace CumulusTheme\BlockFilters\CoreEmbedVimeo;

\defined( 'ABSPATH' ) || exit( 'No direct access allowed.' );

function addDntToVimeo( $html ) {
	if ( \mb_stripos( $html, 'player.vimeo.com' ) === false ) {
		return $html;
	}

	$pattern = '/((?:href|src)\s*=\s*[\'"](https?:\/\/(?:player\.)?vimeo\.com\/[^\s\'"]+))[\'"]/i';
	\preg_match_all( $pattern, $html, $matches );
	if ( \count( $matches[1] ) > 0 ) {
		foreach ( $matches[1] as $url ) {
			if ( \mb_stripos( $url, 'dnt=1' ) !== false ) {
				continue;
			}
			$html = \str_ireplace( $url, $url . ( \mb_strstr( $url, '?' ) ? '&' : '?' ) . 'dnt=1', $html );
		}
	}

	return $html;
}

if ( \get_option( 'cmls-youtube_nocookie', '1' ) === '1' ) {
	foreach ( array( 'oembed_result', 'embed_oembed_html', 'embed_handler_html' ) as $hook ) {
		\add_filter( $hook, function ( $html ) {
			return addDntToVimeo( $html );
		}, \PHP_INT_MAX, 1 );
	}

	\add_filter( 'render_block', function ( $html, $block ) {
		if (
			! \in_array( $block['blockName'], array( 'core/embed', 'core/html' ) )
		) {
			return $html;
		}

		return addDntToVimeo( $html );
	}, 10, 2 );
}
