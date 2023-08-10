<?php
/*
 * CMLS Base Theme
 * Shortcodes
 */

namespace CumulusTheme\Shortcodes;

use WP_Query;

\defined( 'ABSPATH' ) || exit( 'No direct access allowed.' );

function shortcode_post_cards( $attr ) {
	if ( ! \array_key_exists( 'post-type', $attr ) ) {
		$attr['post-type'] = 'post';
	}

	// make sure the requested post type exists
	if ( $attr['post-type'] !== 'post' ) {
		$post_types = \get_post_types( array( 'public' => true ) );

		if ( ! \array_key_exists( $attr['post-type'], $post_types ) ) {
			return 'Post type does not exist.';
		}
	}

	// Get any taxonomies we could query
	$taxes     = array();
	$not_taxes = array();

	if ( \count( $attr ) > 1 ) {
		$taxes     = \get_object_taxonomies( $attr['post-type'] );
		$not_taxes = array();

		if ( \count( $taxes ) ) {
			foreach ( $taxes as $tax ) {
				$not_taxes["not-{$tax}"] = null;
			}
			$taxes = \array_fill_keys( $taxes, null );
		}
	}

	$attr = \shortcode_atts( \array_merge( array(
		'post-type'         => 'post',
		'card-width'        => '100px',
		'card-width-mobile' => '25%',
		'card-gap'          => '.2em',
		'justify'           => 'center',
	), $taxes, $not_taxes ), $attr, 'post-cards' );

	$q = array(
		'post_type'      => $attr['post-type'],
		'post_status'    => 'publish',
		'orderby'        => 'post_title',
		'order'          => 'asc',
		'posts_per_page' => -1,
		'tax_query'      => array(),
	);

	foreach ( \array_keys( $taxes ) as $tax ) {
		if ( $attr[$tax] ) {
			$q['tax_query']['relation'] = 'AND';
			$terms                      = \preg_split( '/[\|\&,\s]/', $attr[$tax] );
			$q['tax_query'][]           = array(
				'taxonomy'         => $tax,
				'field'            => 'slug',
				'terms'            => $terms,
				'include_children' => true,
				'operator'         => 'IN',
			);
		}
	}

	foreach ( \array_keys( $not_taxes ) as $tax ) {
		if ( $attr[$tax] ) {
			$terms            = \preg_split( '/[\|\&,\s]/', $attr[$tax] );
			$q['tax_query'][] = array(
				'taxonomy'         => $tax,
				'field'            => 'slug',
				'terms'            => $terms,
				'include_children' => false,
				'operator'         => 'NOT IN',
			);
		}
	}

	$posts = (new WP_Query( $q ))->get_posts();

	if ( \count( $posts ) ) {
		\ob_start();
		$q_id = \wp_unique_id( 'post-cards-' );

		global $post;
		$originalPost = $post; ?>

		<div id="<?php echo $q_id; ?>" class="inline-archive cards">
			<style>
				#<?php echo $q_id; ?> {
					--card-width: <?php echo \esc_attr( $attr['card-width'] ); ?>;
					--card-gap: <?php echo \esc_attr( $attr['card-gap'] ); ?>;
					justify-content: <?php echo \esc_attr( $attr['justify'] ); ?>;
				}
				@media (max-width: 640px) {
					#<?php echo $q_id; ?> {
						--card-width: <?php echo \esc_attr( $attr['card-width-mobile'] ); ?>;
					}
				}
			</style>
			<?php foreach ( $posts as $cardPost ): ?>
				<?php
					global $post;
				\setup_postdata( $cardPost );
				$post = $cardPost; ?>
				<?php
						\CumulusTheme\cmls_get_template_part(
							'templates/pages/excerpt',
							\CumulusTheme\make_post_class(),
							array(
								'display_format'       => 'cards',
								'show_image'           => true,
								'force_featured_image' => true,
								'thumbnail_size'       => 'thumbnail-uncropped',
								'show_title'           => false,
								'show_date'            => false,
								'show_author'          => false,
								'show_excerpt'         => false,
							)
						); ?>
			<?php endforeach; ?>
		</div>
		<?php

								\wp_reset_postdata();
		$post = $originalPost;

		return \ob_get_clean();
	}

}
\add_shortcode( 'post-cards', __NAMESPACE__ . '\\shortcode_post_cards' );
