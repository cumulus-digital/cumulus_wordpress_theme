<?php
namespace CumulusTheme;
$header_image_id = false;
?>
<?php if (\have_posts()): ?>

	<?php
		$custom_fields = \get_fields();
		$header_image_id = get_arr_val($custom_fields, 'header_image_id');
		if ($header_image_id) {
			BodyClasses::add('post_header_image');
		}
		$card_options = array(
			'show' => get_arr_val($custom_fields, 'show_subpages_as_cards'),
			'manual_page_ids' => get_arr_val($custom_fields, 'manual_card_page_ids'),
			'columns' => get_arr_val($custom_fields, 'cards_columns'),
			'background_color' => get_arr_val($custom_fields, 'card_section_background_color'),
			'background_image' => get_arr_val($custom_fields, 'card_section_background_image'),
			'background_position' => get_arr_val($custom_fields, 'card_section_background_image_position'),
			'display_type' => get_arr_val($custom_fields, 'cards_display_type'),
			'equal_height' => get_arr_val($custom_fields, 'cards_equal_height'),
			'vertical_alignment' => get_arr_val($custom_fields, 'cards_vertical_alignment'),
			'show_image' => get_arr_val($custom_fields, 'cards_display_image'),
			'links' => get_arr_val($custom_fields, 'cards_links'),
			'show_link_label' => get_arr_val($custom_fields, 'cards_display_link_label'),
			'link_label' => get_arr_val($custom_fields, 'cards_link_label'),
			'show_title' => get_arr_val($custom_fields, 'cards_display_title'),
			'uppercase_title' => get_arr_val($custom_fields, 'cards_uppercase_title'),
			'show_excerpt' => get_arr_val($custom_fields, 'cards_display_excerpt'),
		);
		\set_query_var('card_options', $card_options);
	?>

	<?php \get_header() ?>

	<main role="main" class="page <?php echo ! get_arr_val($custom_fields, 'disable_body_margin') ?: 'no-bottom-margin' ?>">

		<?php while (\have_posts()) : \the_post(); $top_id = \get_the_ID() ?>

			<article id="post-<?php the_ID() ?>" <?php \post_class(['row']) ?>>
				<?php if (get_arr_val($custom_fields, 'display_header', true)): ?>
					<?php if ($header_image_id): ?>
						<?php $header_image = \get_post($header_image_id) ?>
						<?php $background_position = get_arr_val($custom_fields, 'header_background_position') ?>
						<?php
							$header_classes = array();
							if (is_array($background_position)) {
								$background_position = $background_position[0];
							}
							switch (strtolower($background_position)) {
								case 'top':
									$header_classes[] = 'background_top';
									break;
								case 'bottom':
									$header_classees[] = 'background_bottom';
									break;
							}
						?>
						<header
							class="has_image <?php echo implode(' ', $header_classes) ?>"
							style="background-image:url('<?php echo $header_image->guid ?>')"
							<?php if ( ! empty($header_image->post_excerpt)): ?>
								data-credit="<?php echo \esc_html($header_image->post_excerpt) ?>"
							<?php endif ?>
						>
					<?php else: ?>
						<header>
					<?php endif ?>

					<span>
						<!--
						<?php $parent = $post->post_parent ? \get_post($post->post_parent) : false ?>
						<?php if ($parent): ?>
							<?php $parent_alt_title = \get_field('alt_title', $parent->ID) ?>
							<h3>
								<a href="<?php echo \get_permalink($parent) ?>">
									<img src="<?php echo \get_template_directory_uri() ?>/assets/prod/images/arrow-circle-lightblue-left.svg" class="arrow">
									<?php echo empty($parent_alt_title) ? \esc_html($parent->post_title) : \esc_html($parent_alt_title) ?>
								</a>
							</h3>
						<?php endif ?>
						-->
						<h1>
							<?php if (empty(get_arr_val($custom_fields, 'alt_title'))): ?>
								<?php \the_title() ?>
							<?php else: ?>
								<?php echo \esc_html(get_arr_val($custom_fields, 'alt_title')) ?>
							<?php endif ?>
						</h1>
						<?php if (get_arr_val($custom_fields, 'subtitle')): ?>
							<h2>
								<?php echo \esc_html(get_arr_val($custom_fields, 'subtitle')) ?>
							</h2>
						<?php endif ?>
					</span>

				</header>
				<?php endif ?>
				<div class="row-container">

					<div class="body">
						<?php \the_content() ?>
					</div>

				</div>

				<?php if ($card_options['show'] || $card_options['manual_page_ids']): ?>
					<?php \get_template_part('template-parts/cards') ?>
				<?php endif ?>

				<?php if (get_arr_val($custom_fields, 'show_listen_now_footer')): ?>
					<?php echo \get_template_part('template-parts/page_footer', 'listen') ?>
				<?php endif ?>

				<?php if (get_arr_val($custom_fields, 'footer_page')): ?>
					<?php foreach(get_arr_val($custom_fields, 'footer_page') as $footer_page): ?>
						<?php \setup_postdata($footer_page) ?>
						<div class="body">
							<?php \the_content() ?>
						</div>
					<?php endforeach ?>
					<?php \wp_reset_postdata() ?>
				<?php endif ?>

			</article>
			<?php \edit_post_link('Edit this page') ?>

		<?php endwhile ?>

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

<?php endif ?>

<?php \get_footer() ?>