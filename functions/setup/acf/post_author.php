<?php

// Filters for acting on ACF post author fields

namespace CumulusTheme;

\defined( 'ABSPATH' ) || exit( 'No direct access allowed.' );

function filterPostAuthorForAltAuthor( $display_name, $user_id = null, $original_user_id = null ) {
	global $pagenow;
	$post_id = \get_the_ID();

	if ( ! $post_id || ( \is_admin() && $pagenow === 'edit.php' ) ) {
		return $display_name;
	}
	$alt_display_name = \get_field( 'field_613a67efc94aa', $post_id );

	if ( ! $alt_display_name || \mb_strlen( $alt_display_name ) < 1 ) {
		return $display_name;
	}

	return $alt_display_name;
}
\add_filter( 'the_author', ns( 'filterPostAuthorForAltAuthor' ), 10, 1 );
\add_filter( 'get_the_author_display_name', ns( 'filterPostAuthorForAltAuthor' ), 10, 3 );

function filterPostAuthorLinkForAltAuthor( $link ) {
	$post_id = \get_the_ID();

	if ( ! $post_id ) {
		return $link;
	}
	$alt_display_name = \get_field( 'field_613a67efc94aa', $post_id );

	if ( ! $alt_display_name || \mb_strlen( $alt_display_name ) < 1 ) {
		return $link;
	}

	return $alt_display_name;
}
\add_filter( 'the_author_posts_link', ns( 'filterPostAuthorLinkForAltAuthor' ), 10, 1 );

function filterPostAuthorAvatarforAltAuthor( $avatar = null, $id = null, $args = array() ) {
	// Only function when displaying posts
	if ( ! \in_the_loop() ) {
		return $avatar;
	}

	$post_id = \get_the_ID();

	if ( ! $post_id ) {
		return $avatar;
	}

	$alt_display_name = \get_field( 'field_613a67efc94aa', $post_id );

	if ( ! $alt_display_name || \mb_strlen( $alt_display_name ) < 1 ) {
		return $avatar;
	}
}
\add_filter( 'pre_get_avatar', ns( 'filterPostAuthorAvatarforAltAuthor' ), 10, 3 );
