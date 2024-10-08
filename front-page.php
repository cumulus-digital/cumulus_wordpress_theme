<?php

namespace CumulusTheme;

// Alter the title for front page to only display the site title
function cmFrontPageTitle( $title ) {
	if ( \is_front_page() ) {
		return array( \get_bloginfo( 'name' ) );
	}

	return $title;
}
\add_filter( 'document_title_parts', ns( 'cmFrontPageTitle' ), 99999, 1 );
\add_filter( 'wpseo_title', '__return_empty_string' ); // Yoast interception

$custom_fields    = \get_fields();
$header_video_ids = array();
if ( $custom_fields ) {
	foreach( $custom_fields as $key => $field ) {
		if (
			( \mb_strstr( $key, 'header_video_id' ) || \mb_strstr( $key, 'header_video_alt' ) )
			&& ! empty( $field )
		) {
			$header_video_ids[$key] = $field;
		}
	}
}
if ( \count( $header_video_ids ) ) {
	BodyClasses::add( 'post_header_image' );
	$header_videos = \get_posts( array(
		'include'     => \array_values( $header_video_ids ),
		'post_type'   => 'attachment',
		'post_status' => 'all',
	) );
}

\get_header();
?>
<section class="row hero">

	<?php if ( \count( $header_videos ) ): ?>
		<div class="video-container">
			<video <?php echo $custom_fields['header_video_autoplay'] ? 'autoplay' : ''; ?> loop muted playsinline id="header_video" class="landing" data-object-fit="cover" poster="<?php echo theme_url(); ?>/assets/prod/images/bg-video_hero-small.png">
				<?php foreach( $header_videos as $video ): ?>
					<source src="<?php echo \wp_get_attachment_url( $video->ID ); ?>#t=0" type="<?php echo $video->post_mime_type; ?>">
				<?php endforeach; ?>
				<img src="<?php echo theme_url(); ?>/assets/prod/images/bg-video_hero-small.png" alt loading="lazy">
			</video>
		</div>
	<?php endif; ?>

</section>

<span class="scroll-down-arrow"></span>

<main role="main" class="row <?php echo ! gav( $custom_fields, array( 'disable_body_margin' ) ) ?: 'no-bottom-margin'; ?>">

	<div class="row-container">

		<?php if ( \have_posts() ): ?>
			<?php while ( \have_posts() ) : \the_post(); ?>

				<article id="post-<?php \the_ID(); ?>" <?php \post_class(); ?>>

					<div class="body">
						<?php \the_content(); ?>
					</div>

				</article>

			<?php endwhile; ?>
		<?php endif; ?>

	</div>

</main>

<?php \get_footer(); ?>