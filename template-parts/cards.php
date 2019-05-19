<?php
namespace CumulusTheme;
?>

<?php
	$card_options = \get_query_var('card_options');
	$children = new \WP_Query(array(
		'post_parent'    => \get_the_ID(),
		'post_type'      => 'page', 
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'ignore_sticky_posts' => true
	));
?>
<?php if ($children->have_posts()): ?>

	<section
		class="cards square <?php echo $card_options['columns'] . '-columns' ?> <?php echo $card_options['show_link_label'] === false ? 'no-link-label' : '' ?>"
		style="<?php
			if ($card_options['background_color']) {
				echo 'background-color:' . $card_options['background_color'] .';';
			}
			if ($card_options['background_image']) {
				$bg_image = \get_post($card_options['background_image']);
				echo 'background-image:url(\'' . $bg_image->guid . '\');';
				echo 'background-position:' . $card_options['background_position'] . ';';
			}
		?>"
	>

		<?php while($children->have_posts()): $children->the_post() ?>

			<?php \get_template_part('template-parts/card') ?>

		<?php endwhile ?>

		<?php if ( ! empty(get_arr_val($card_options, 'manual_page_ids'))): ?>
			<?php 
				$manual_cards = new \WP_Query([
					'post_type' => 'page',
					'post__in' => (array) get_arr_val($card_options, 'manual_page_ids'),
					'orderby' => 'menu_order title',
					'order' => 'ASC',
					'ignore_sticky_posts' => true
				]);
			?>
			<?php while($manual_cards->have_posts()): $manual_cards->the_post() ?>

				<?php \get_template_part('template-parts/card') ?>

			<?php endwhile ?>
		<?php endif ?>

	</section>

<?php endif ?>
