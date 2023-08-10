<?php
/**
 * CMLS Base Theme
 * Default filters for archive diplay
 */

namespace CumulusTheme;

\defined( 'ABSPATH' ) || exit( 'No direct access allowed.' );

\add_filter( 'display-archive-display_format', function ( $display_format ) {
	if (
		\is_author()
		|| \is_search()
	) {
		return 'list';
	}

	return 'cards';
}, 1, 1 );

\add_filter( 'display-archive-show_image', '__return_true', 1, 1 );
\add_filter( 'display-archive-thumbnail_size', function ( $thumbnail_size ) {
	return 'thumbnail-uncropped';
}, 1, 1 );

\add_filter( 'display-archive-show_title', '__return_true', 1, 1 );
\add_filter( 'display-archive-show_source', '__return_true', 1, 1 );
\add_filter( 'display-archive-show_excerpt', '__return_true', 1, 1 );

\add_filter( 'display-archive-show_date', function ( $show_date ) {
	return ! check_query_post_type_hierarchical();
}, 1, 1 );

\add_filter( 'display-archive-show_author', function ( $show_author ) {
	return \has_category( 'blog' );
}, 1, 1 );

\add_filter( 'display-archive-show_category', function ( $show_category ) {
	$term = \get_queried_object();

	return \property_exists( $term, 'taxonomy' ) && \is_taxonomy_hierarchical( $term->taxonomy );
}, 1, 1 );
