<?php
namespace CumulusTheme;

// Alter the title for front page to only display the site title
function cmFrontPageTitle( $title ) {
	if (\is_front_page()) {
		return array(\get_bloginfo('name'));
	}
	return $title;
}
\add_filter( 'document_title_parts', ns('cmFrontPageTitle'), 99999, 1 );
\add_filter('wpseo_title', '__return_empty_string'); // Yoast interception

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
			<video <?php echo $custom_fields['header_video_autoplay'] ? 'autoplay' : '' ?> loop muted playsinline id="header_video" class="landing" data-object-fit="cover">
				<?php if (array_key_exists('header_video_id', $header_videos)): ?>
					<?php $video_full = \get_post($header_videos['header_video_id']); ?>
					<source src="<?php echo $video_full->guid ?>#t=0.1" type="<?php echo $video_full->post_mime_type ?>" media="all and (min-width: 800px)">
				<?php endif ?>
				<?php if (array_key_exists('header_video_alt_id', $header_videos)): ?>
					<?php $video_tablet = \get_post($header_videos['header_video_alt_id']); ?>
					<source src="<?php echo $video_tablet->guid ?>#t=0.1" type="<?php echo $video_tablet->post_mime_type ?>" media="all and (min-width: 500px)">
				<?php endif ?>
				<?php if (array_key_exists('header_video_alt2_id', $header_videos)): ?>
					<?php $video_mobile = \get_post($header_videos['header_video_alt2_id']); ?>
					<source src="<?php echo $video_mobile->guid ?>#t=0.1" type="<?php echo $video_mobile->post_mime_type ?>" media="all and (min-width: 1px)">
				<?php endif ?>
				<img src="<?php echo \get_template_directory_uri() ?>/assets/prod/images/bg-video_hero.png">
			</video>
		</div>
		<script src="https://cdn.jsdelivr.net/npm/objectFitPolyfill@2.3.0/dist/objectFitPolyfill.basic.min.js" integrity="sha256-Kms38lBvuW1aCtPabohpwj3Xx1VCuIjGgDS6X6ay3Hc=" crossorigin="anonymous"></script>
	<?php endif ?>

</section>

<span class="scroll-down-arrow"></span>

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