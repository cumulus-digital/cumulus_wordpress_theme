<?php
namespace CumulusTheme;
$card_options = \get_query_var('card_options');
$custom_fields = \get_fields();
$card_image_id = $custom_fields['card_image'];
?>

<?php if ($card_options['links']): ?>

	<a href="<?php \the_permalink() ?>" class="card <?php
		echo 'card-image_' . $card_options['image_display'] . ' ';
		echo $card_options['show_title'] ? 'card-show_title ' : 'card-no_title ';
		echo $card_options['show_excerpt'] ? 'card-show_excerpt ' : 'card-no_excerpt ';
		echo $card_image_id ? 'card-has_image ' : 'card-no_image ';
	?>">

<?php else: ?>

	<div class="card <?php
		echo 'card-image_' . $card_options['image_display'] . ' ';
		echo $card_options['show_title'] ? 'card-show_title ' : 'card-no_title ';
		echo $card_options['show_excerpt'] ? 'card-show_excerpt ' : 'card-no_excerpt ';
		echo $card_image_id ? 'card-has_image ' : 'card-no_image ';
	?>">

<?php endif ?>

		<?php
			if ($card_image_id):
		?>
			<?php $card_image = \get_post($card_image_id) ?>
			<img 
				src="<?php echo $card_image->guid ?>"
				class="card-image card_image-<?php echo $card_options['image_display'] ?>"
				alt="<?php
					if (empty(get_arr_val($custom_fields, 'alt_title'))) {
						\the_title();
					} else {
						echo \esc_html(get_arr_val($custom_fields, 'alt_title'));
					}
				?>"
			>
		<?php endif ?>
		
		<?php 
		if (
			! $card_image_id ||
			(
				$card_options['show_title'] || 
				(
					\has_excerpt() && $card_options['show_excerpt']
				)
			)
		):
		?>
			<div class="card-content">
				<?php if ($card_options['show_title'] || ! $card_image_id): ?>
					<h3>
						<?php if (empty(get_arr_val($custom_fields, 'alt_title'))): ?>
							<?php \the_title() ?>
						<?php else: ?>
							<?php echo \esc_html(get_arr_val($custom_fields, 'alt_title')) ?>
						<?php endif ?>
					</h3>
				<?php endif ?>

				<?php if (\has_excerpt() && $card_options['show_excerpt']): ?>
					<div class="body">
						<?php \the_excerpt() ?>
					</div>
				<?php endif ?>
			</div>
		<?php endif ?>

		<?php if ($card_options['links'] && $card_options['show_link_label'] !== false): ?>

			<div class="card-permalink">
				<span>
					<?php if (get_arr_val($custom_fields, 'override_card_link_label')): ?>
						<?php echo \esc_html(get_arr_val($custom_fields, 'override_card_link_label')) ?>
					<?php else: ?>
						<?php echo $card_options['link_label'] ? \esc_html($card_options['link_label']) : 'Learn More' ?>
					<?php endif ?>
				</span>
				<img src="<?php echo \get_template_directory_uri() ?>/assets/prod/images/arrow-blue-right.svg" class="arrow">
			</div>

		<?php endif ?>

<?php if ($card_options['links']): ?>
	
	</a>

<?php else: ?>

	</div>

<?php endif ?>
