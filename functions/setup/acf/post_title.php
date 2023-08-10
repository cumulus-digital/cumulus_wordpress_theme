<?php
/**
 * Filters for acting on ACF post title fields.
 */

namespace CumulusTheme;

\defined( 'ABSPATH' ) || exit( 'No direct access allowed.' );

function filterPostTitlesForAltTitle( $title, $id = null ) {
	if ( $id === null || \is_admin() ) {
		return $title;
	}

	$alt_title = \get_field( 'cmls-alt_title', $id, null );

	if ( $alt_title ) {
		return $alt_title;
	}

	return $title;
}
\add_filter( 'the_title', ns( 'filterPostTitlesForAltTitle' ), 10, 2 );

function filterHeadTitleForAltTitle( $title ) {
	if ( \is_array( $title ) && \array_key_exists( 'title', $title ) && \is_singular() ) {
		$type = \get_post_type();

		if (
			$type === 'post' || $type === 'page'
		) {
			$alt_title = \get_field( 'cmls-alt_title', \get_the_ID(), null );

			if ( $alt_title ) {
				$title['title'] = $alt_title;
			}
		}
	}

	return $title;
}
\add_filter( 'document_title_parts', ns( 'filterHeadTitleForAltTitle' ), 10 );
