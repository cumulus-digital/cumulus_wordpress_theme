<?php

namespace CumulusTheme;

$header_image_id = false;
?>
<?php if ( \have_posts() ): ?>

	<?php
		$custom_fields  = \get_fields();
	$header_image_id = gav( $custom_fields, 'header_image_id' );
	if ( $header_image_id ) {
		BodyClasses::add( 'post_header_image' );
	}
	$card_options = array(
		'show_subpages'       => gav( $custom_fields, 'show_subpages_as_cards' ),
		'manual_page_ids'     => gav( $custom_fields, 'manual_card_page_ids' ),
		'columns'             => gav( $custom_fields, 'cards_columns' ),
		'background_color'    => gav( $custom_fields, 'card_section_background_color' ),
		'background_image'    => gav( $custom_fields, 'card_section_background_image' ),
		'background_position' => gav( $custom_fields, 'card_section_background_image_position' ),
		'display_type'        => gav( $custom_fields, 'cards_display_type' ),
		'equal_height'        => gav( $custom_fields, 'cards_equal_height' ),
		'vertical_alignment'  => gav( $custom_fields, 'cards_vertical_alignment' ),
		'show_image'          => gav( $custom_fields, 'cards_display_image' ),
		'links'               => gav( $custom_fields, 'cards_links' ),
		'show_link_label'     => gav( $custom_fields, 'cards_display_link_label' ),
		'link_label'          => gav( $custom_fields, 'cards_link_label' ),
		'show_title'          => gav( $custom_fields, 'cards_display_title' ),
		'uppercase_title'     => gav( $custom_fields, 'cards_uppercase_title' ),
		'show_excerpt'        => gav( $custom_fields, 'cards_display_excerpt' ),
	);
	\set_query_var( 'card_options', $card_options );
	?>

	<?php \get_header(); ?>

	<main role="main" class="page <?php echo ! gav( $custom_fields, 'disable_body_margin' ) ?: 'no-bottom-margin'; ?>">

		<?php while ( \have_posts() ) : \the_post();
			$top_id = \get_the_ID(); ?>

			<article id="post-<?php \the_ID(); ?>" <?php \post_class( array( 'row' ) ); ?>>
				<?php if ( gav( $custom_fields, 'display_header', true ) ): ?>
					<?php if ( $header_image_id ): ?>
						<?php $header_image        = \get_post( $header_image_id ); ?>
						<?php $background_position = gav( $custom_fields, 'header_background_position' ); ?>
						<?php
								$header_classes = array();
						if ( \is_array( $background_position ) ) {
							$background_position = $background_position[0];
						}
						switch ( \mb_strtolower( $background_position ) ) {
							case 'top':
								$header_classes[] = 'background_top';

								break;
							case 'bottom':
								$header_classees[] = 'background_bottom';

								break;
						}
						?>
						<header
							class="has_image <?php echo \implode( ' ', $header_classes ); ?>"
							style="background-image:url('<?php echo \wp_get_attachment_image_url( $header_image_id, 'full' ); ?>')"
							<?php if ( ! empty( $header_image->post_excerpt ) ): ?>
								data-credit="<?php echo \esc_html( $header_image->post_excerpt ); ?>"
							<?php endif; ?>
						>
					<?php else: ?>
						<header>
					<?php endif; ?>

					<span>
						<h1>
							<?php if ( empty( gav( $custom_fields, 'alt_title' ) ) ): ?>
								<?php \the_title(); ?>
							<?php else: ?>
								<?php echo \esc_html( gav( $custom_fields, 'alt_title' ) ); ?>
							<?php endif; ?>
						</h1>
						<?php if ( gav( $custom_fields, 'subtitle' ) ): ?>
							<h2>
								<?php echo \esc_html( gav( $custom_fields, 'subtitle' ) ); ?>
							</h2>
						<?php endif; ?>
					</span>

				</header>
				<?php endif; ?>
				<div class="row-container">

					<div class="body">
						<?php \the_content(); ?>
					</div>

				</div>

				<?php if ( $card_options['show_subpages'] || $card_options['manual_page_ids'] ): ?>
					<?php \get_template_part( 'template-parts/cards' ); ?>
				<?php endif; ?>

				<?php if ( gav( $custom_fields, 'show_listen_now_footer' ) ): ?>
					<?php echo \get_template_part( 'template-parts/page_footer', 'listen' ); ?>
				<?php endif; ?>

				<?php if ( gav( $custom_fields, 'footer_page' ) ): ?>
					<?php foreach ( gav( $custom_fields, 'footer_page' ) as $footer_page ): ?>
						<?php \setup_postdata( $footer_page ); ?>
						<div class="body">
							<?php \the_content(); ?>
						</div>
					<?php endforeach; ?>
					<?php \wp_reset_postdata(); ?>
				<?php endif; ?>

			</article>
			<?php \edit_post_link( 'Edit this page' ); ?>

		<?php endwhile; ?>

	</main>

<?php else: ?>

	<main role="main" class="page">
		<article class="row">
			<div class="row-container">

				<div class="body">
					<p>Sorry, nothing to display.</p>
				</div>

			</div>
		</article>
	</main>

<?php endif; ?>

<?php \get_footer(); ?>