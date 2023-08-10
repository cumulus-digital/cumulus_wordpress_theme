<?php
/**
 * CMLS Base Theme
 * Filter.
 */

namespace CumulusTheme\Filters\Display;

\defined( 'ABSPATH' ) || exit( 'No direct access allowed.' );

function superscriptRegistrationMarks( $str ) {
	$got_marks = \preg_match( '/(®|&reg;)/', $str );

	if ( $got_marks ) {
		$str = \preg_replace( '/(®|&reg;)/i', '<sup class="r">$1</sup>', $str );
	}

	return $str;
}

\add_filter( 'the_title', function ( $title ) {
	if ( \in_the_loop() ) {
		return superscriptRegistrationMarks( $title );
	}

	return $title;
}, 10, 1 );

\add_filter( 'single_term_title', function ( $title ) {
	return superscriptRegistrationMarks( $title );
}, 9999999, 1 );

\add_filter( 'get_the_archive_description', function ( $description ) {
	return superscriptRegistrationMarks( $description );
}, 9999999, 1 );

\add_filter( 'wp_nav_menu_items', function ( $items ) {
	return superscriptRegistrationMarks( $items );
}, 9999999, 1 );

\add_filter( 'get_term', function ( $term ) {
	if ( \is_admin() ) {
		return $term;
	}

	$term->name = superscriptRegistrationMarks( $term->name );

	return $term;
} );
