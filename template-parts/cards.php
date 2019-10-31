<?php
namespace CumulusTheme;
?>

<?php
	$card_options = \get_query_var('card_options');
	$has_children = false;
	if (get_arr_val($card_options, 'show_subpages')) {
		$children = new \WP_Query(array(
			'post_parent'    => \get_the_ID(),
			'post_type'      => 'page', 
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'ignore_sticky_posts' => true
		));
		if ($children->have_posts()) {
			$has_children = true;
		}
	}
?>
<?php if ($has_children || ! empty(get_arr_val($card_options, 'manual_page_ids'))): ?>

	<section
		class="cards square <?php
			echo $card_options['columns'] . '-columns ';
			echo $card_options['show_link_label'] === false ? 'no-link-label ' : '';
			echo $card_options['uppercase_title'] === true ? 'uppercase-titles' : '';
		?>"
		style="<?php
			if ($card_options['background_color']) {
				echo 'background-color:' . $card_options['background_color'] .';';
			}
			if ($card_options['background_image']) {
				$bg_image = \get_post($card_options['background_image']);
				echo 'background-image:url(\'' . \wp_make_link_relative($bg_image->guid) . '\');';
				echo 'background-position:' . $card_options['background_position'] . ';';
			}
		?>"
	>

		<div class="cards-container <?php
			if ( ! $card_options['equal_height']) {
				echo 'cards-height-flow ';
				echo 'cards-vertical_align-' . $card_options['vertical_alignment'];
			}
		?>">
	
			<?php if ($has_children): ?>
				<?php while($children->have_posts()): $children->the_post() ?>

					<?php \get_template_part('template-parts/card') ?>

				<?php endwhile ?>
			<?php endif ?>

			<?php if ( ! empty(get_arr_val($card_options, 'manual_page_ids'))): ?>
				<?php 
					$manual_cards = new \WP_Query([
						'post_type' => 'page',
						'post__in' => (array) get_arr_val($card_options, 'manual_page_ids'),
						'orderby' => 'post__in',
						//'order' => 'ASC',
						'ignore_sticky_posts' => true
					]);
				?>
				<?php while($manual_cards->have_posts()): $manual_cards->the_post() ?>

					<?php \get_template_part('template-parts/card') ?>

				<?php endwhile ?>
			<?php endif ?>

		</div>

	</section>

<?php endif ?>
