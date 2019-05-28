<?php
namespace CumulusTheme;

$custom_fields = \get_fields();
$header_videos = array();
if ($custom_fields) {
	foreach($custom_fields as $key => $field) {
		if (
			(strstr($key, 'header_video_id') || strstr($key, 'header_video_alt')) &&
			! empty($field)
		) {
			$header_videos[$key] = $field;
		}
	}
}
if (count($header_videos)) BodyClasses::add('post_header_image');
\get_header();
?>
<section class="row hero">

	<?php if (count($header_videos)): ?>
		<div class="video-container">
			<video <?php echo $custom_fields['header_video_autoplay'] ? 'autoplay' : '' ?> loop="" muted="" playsinline="" id="header_video" class="landing">
				<?php foreach($header_videos as $video_id): ?>
					<?php $video = \get_post($video_id) ?>
					<source src="<?php echo $video->guid ?>#t=0.1" type="<?php echo $video->post_mime_type ?>">
				<?php endforeach ?>
				<img src="<?php echo \get_template_directory_uri() ?>/assets/prod/images/bg-video_hero.png">
			</video>
		</div>
	<?php endif ?>

</section>

<main role="main" class="row <?php echo ! get_arr_val($custom_fields, 'disable_body_margin') ?: 'no-bottom-margin' ?>">

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

	</div>

</main>

<?php \get_footer(); ?>