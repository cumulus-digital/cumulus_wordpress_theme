<?php
namespace CumulusTheme;

\get_header();
?>
<main role="main" class="page">
	<section class="row">
		<div class="row-container">

			<?php if (\have_posts()): ?>
				<?php while (\have_posts()) : \the_post(); ?>

					<article id="post-<?php the_ID(); ?>" <?php \post_class(); ?>>

						<header>
							<h1>
								<?php \the_title(); ?>
							</h1>
						</header>

						<div class="body">
							<?php \the_content(); ?>
						</div>

					<?php \edit_post_link(); ?>
					</article>

				<?php endwhile; ?>
			<?php else: ?>

				<article>

					<p>
						Sorry, nothing to display.
					</p>

				</article>

			<?php endif; ?>

		</div>
	</section>
</main>

<?php \get_footer(); ?>