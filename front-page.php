<?php
namespace CumulusTheme;

\get_header();
?>
<section class="row hero">

	<div class="video-container">
		<video autoplay="" loop="" muted="" id="myVideo" class="landing" data-keepplaying="" data-autoplay="">
		  <source src="http://mrdrnewyork.com/sandbox/cumulus/wp-content/uploads/2019/04/Comp-1_2.mp4" type="video/mp4">
		  <img src="<?php echo \get_template_directory_uri() ?>/assets/prod/images/videohero.jpg">
		</video>
	</div>

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