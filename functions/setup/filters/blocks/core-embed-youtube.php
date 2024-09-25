<?php
/**
 * Automatically replace youtube embeds with youtube-nocookie.
 */

namespace CumulusTheme\BlockFilters\CoreEmbedYoutube;

\defined( 'ABSPATH' ) || exit( 'No direct access allowed.' );

if ( \get_option( 'cmls-youtube_nocookie', '1' ) === '1' ) {
	\add_filter( 'oembed_result', function ( $data ) {
		return \str_ireplace( 'youtube.com/embed', 'youtube-nocookie.com/embed', $data );
	}, 10, 1 );

	\add_filter( 'embed_oembed_html', function ( $html ) {
		if ( \mb_stripos( 'youtube.com/embed', $html ) === false ) {
			return $html;
		}

		return \str_ireplace( 'youtube.com/embed', 'youtube-nocookie.com/embed', $html );
	}, 10, 1 );

	\add_filter( 'render_block', function ( $content, $block ) {
		if (
			! \in_array( $block['blockName'], array( 'core/embed', 'core/html' ) )
		) {
			return $content;
		}

		if ( \mb_stripos( $content, '<iframe' ) && \mb_stripos( $content, 'youtube.com/embed' ) ) {
			$content = \str_ireplace( 'youtube.com/embed', 'youtube-nocookie.com/embed', $content );
		}

		return $content;
	}, 10, 2 );
}
