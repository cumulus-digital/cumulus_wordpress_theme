<?php
/**
 * Media inits and support.
 */

namespace CumulusTheme;

\defined( 'ABSPATH' ) || exit( 'No direct access allowed.' );

// Allow media to have categories
function registerMediaTaxonomy() {
	\register_taxonomy_for_object_type( 'category', 'attachment' );
}
\add_action( 'init', ns( 'registerMediaTaxonomy' ) );

// Custom uncropped thumbnail size
\add_image_size( 'thumbnail-uncropped', 400, 400, false );

// Only allow srcset to get up to 2.5x requested size
function setMaxSrcsetSize( $max_size, $sizes ) {
	if ( \is_array( $sizes ) ) {
		$size = $sizes[0];

		return $size * 2.5;
	}

	return $max_size;
}
\add_filter( 'max_srcset_image_width', ns( 'setMaxSrcsetSize' ), 10, 2 );

// Automatically add attachment media to category if possible
function addAttachmentToPostCategory( $attachment_id ) {
	$attachment = \get_post( $attachment_id );

	if ( $attachment->post_parent ) {
		$cats = \wp_get_post_categories( $attachment->post_parent, array( 'fields' => 'names' ) );

		if ( $cats && \is_array( $cats ) ) {
			\wp_set_object_terms(
				$attachment_id,
				$cats,
				'category',
				true
			);
		}
	}
}
\add_action( 'add_attachment', ns( 'addAttachmentToPostCategory' ), 10, 1 );

// Sorting by category
\add_filter( 'manage_upload_sortable_columns', function ( $columns ) {
	$columns['categories'] = 'category';

	return $columns;
} );

// Filtering by category
\add_action( 'restrict_manage_posts', function () {
	$screen = \get_current_screen();

	if ( ! \is_admin() || $screen->base !== 'upload' ) {
		return;
	}

	$tax = \filter_input( \INPUT_GET, 'taxonomy', \FILTER_UNSAFE_RAW );
	$cat = \filter_input( \INPUT_GET, 'cat', \FILTER_UNSAFE_RAW );

	$tax = $tax ? $tax : 'category';

	$args = array(
		'show_option_none'  => 'All Post Categories',
		'option_none_value' => null,
		'name'              => 'cat',
		'selected'          => $cat,
		'value_field'       => 'term_id',
		'taxonomy'          => $tax,
		'hierarchical'      => true,
	);
	\wp_dropdown_categories( $args );
} );

// Filter by author
\add_action( 'restrict_manage_posts', function () {
	$screen = \get_current_screen();

	if ( ! \is_admin() || $screen->base !== 'upload' ) {
		return;
	}

	$author = \filter_input( \INPUT_GET, 'author', \FILTER_UNSAFE_RAW );

	$args = array(
		'show_option_none'  => 'All Authors',
		'option_none_value' => null,
		'name'              => 'author',
		'selected'          => $author,
		'value_field'       => 'id',
	);
	\wp_dropdown_users( $args );
} );

// Allow WP AJAX calls to get images by category
function getImagesByCategory() {
	$category = \json_decode( $_POST['category'], true );

	if ( ! \filter_var( $category, \FILTER_VALIDATE_INT ) ) {
		\header( 'HTTP/1.0 400 Bad error' );
		echo '{ error: "Bad category." }';
	} else {
		$args = array(
			'category'  => $category,
			'post_type' => 'attachment',
		);
		$media = \get_posts( $args );

		if ( ! empty( $_GET['callback'] ) ) {
			echo $_GET['callback'] . '(' . \json_encode( $media ) . ');';
		} else {
			echo \json_encode( $media );
		}
	}
}
\add_action( 'wp_ajax_get_images_by_category', ns( 'getImagesByCategory' ) );
\add_action( 'wp_ajax_nopriv_get_images_by_category', ns( 'getImagesByCategory' ) );

// Recognize SVG files
function addSvgToMimes( $mimes ) {
	$mimes['svg'] = 'image/svg+xml';

	return $mimes;
}
\add_filter( 'upload_mimes', ns( 'addSvgToMimes' ) );

// Exclude SVGs from Jetpack Photon, it cannot handle them
function photonExcludeSVG( $val, $src, $tag ) {
	if ( \mb_strpos( \mb_strtolower( $src ), '.svg' ) >= -1 ) {
		return true;
	}

	return $val;
}
\add_filter( 'jetpack_photon_skip_image', ns( 'photonExcludeSVG' ), 10, 3 );
