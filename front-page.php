<?php
namespace CumulusTheme;

\get_header();
$custom_fields = \get_fields();
$header_videos = array();
foreach($custom_fields as $key => $field) {
	if (
		(strstr($key, 'header_video_id') || strstr($key, 'header_video_alt')) &&
		! empty($field)
	) {
		$header_videos[$key] = $field;
	}
}
?>
<section class="row hero">

	<?php if (count($header_videos)): ?>
		<div class="video-container">
			<video autoplay="<?php $custom_fields['header_video_autoplay'] ?>" loop="" muted="" id="header_video" class="landing" data-keepplaying="" data-autoplay="">
				<?php foreach($header_videos as $video_id): ?>
					<?php $video = \get_post($video_id) ?>
					<source src="<?php echo $video->guid ?>" type="<?php echo $video->post_mime_type ?>">
				<?php endforeach ?>
				<img src="<?php echo \get_template_directory_uri() ?>/assets/prod/images/bg-video_hero.png">
			</video>
		</div>
	<?php endif ?>

</section>

<main role="main" class="row">

	<div class="row-container">

		<?php if (\have_posts()): ?>
			<?php while (\have_posts()) : \the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php \post_class(); ?>>

					<div class="body">
						<?php \the_content(); ?>
					</div>

				</article>

			<?php endwhile; ?>
		<?php endif ?>

	<?php \edit_post_link('Edit this page'); ?>
	</div>

</main>

<?php \get_footer(); ?>