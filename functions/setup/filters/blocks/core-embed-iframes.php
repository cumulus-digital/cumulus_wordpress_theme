<?php
/**
 * Add loading=lazy and title attributes to iframes.
 */

namespace CumulusTheme\BlockFilters\CoreEmbedIframes;

\defined( 'ABSPATH' ) || exit( 'No direct access allowed.' );

\add_filter( 'render_block', function ( $content, $block ) {
	if (
		! \in_array( $block['blockName'], array( 'core/embed', 'core/html' ) )
	) {
		return $content;
	}

	try {
		if ( \mb_strpos( $content, '<iframe' ) ) {
			$use_errors = \libxml_use_internal_errors( true );
			$dom        = new \DOMDocument( '1.0', 'UTF-8' );

			$raw_content = $content;

			if ( false === \mb_stripos( $content, '<?xml' ) ) {
				$raw_content = '<?xml encoding="utf-8" ?>' . $content;
			}

			$dom->loadHTML(
				$raw_content,
				\LIBXML_SCHEMA_CREATE | \LIBXML_HTML_NOIMPLIED | \LIBXML_HTML_NODEFDTD
			);

			foreach ( $dom->childNodes as $item ) {
				if ( $item instanceof \DOMProcessingInstruction ) {
					$dom->removeChild( $item );

					break;
				}
			}
			$dom->encoding = 'UTF-8';

			$xpath = new \DOMXPath( $dom );

			$iframes = $xpath->query( '//iframe' );

			foreach ( $iframes as $iframe ) {
				if ( $iframe instanceof \DOMElement ) {
					// Set loading to lazy if not already set
					$loading = $iframe->getAttribute( 'loading' );
					if ( ! $loading ) {
						$iframe->setAttribute( 'loading', 'lazy' );
					}

					// Add title attribute if missing
					$title = $iframe->getAttribute( 'title' );
					if ( ! $title ) {
						$new_title = 'Iframe embed';
						$src       = $iframe->getAttribute( 'src' );
						if ( \mb_stripos( $src, 'megaphone.fm' ) ) {
							$new_title = 'Podcast embed';
						}
						$iframe->setAttribute( 'title', $new_title );
					}
				}
			}
			$content = $dom->saveHTML();
			\libxml_use_internal_errors( $use_errors );
		}
	} catch ( \Exception $e ) {
	}

	return $content;
}, 10, 2 );
