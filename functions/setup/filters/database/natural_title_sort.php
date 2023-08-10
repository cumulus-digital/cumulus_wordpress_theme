<?php
/**
 * CMLS Base Theme
 * Adds a natural_title orderby field.
 */

namespace CumulusTheme;

\defined( 'ABSPATH' ) || exit( 'No direct access allowed.' );

\add_filter( 'posts_orderby', function ( $orderby, $query ) {
	global $wpdb;

	if ( \mb_strpos( $orderby, "{$wpdb->posts}.post_title" ) !== false ) {
		$orderby = \preg_replace(
			"|{$wpdb->posts}.post_title|",
			"
			CASE
				WHEN SUBSTRING_INDEX( {$wpdb->posts}.post_title, ' ', 1 ) IN ( 'a', 'an', 'the' )
				THEN SUBSTRING( {$wpdb->posts}.post_title, INSTR( {$wpdb->posts}.post_title, ' ' ) + 1 )
				ELSE {$wpdb->posts}.post_title
			END
			",
			$orderby
		);
		/*
		$matches = 'A|An|The';

		$orderby = \str_replace(
			"{$wpdb->posts}.post_title",
			"REGEXP_REPLACE( {$wpdb->posts}.post_title, '^({$matches})[[:space:]]+', '' )",
			$orderby
		);
		 */
	}

	return $orderby;
}, 10, 2 );
