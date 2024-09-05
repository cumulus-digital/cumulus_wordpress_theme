<?php

namespace CumulusTheme;

\get_header();

$description = \get_the_archive_description();
?>
<main role="main" class="page">
	<section class="row">
		<div class="row-container">
			<header>
				<?php \the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
				<?php if ( $description ) : ?>
					<div class="archive-description"><?php echo \wp_kses_post( \wpautop( $description ) ); ?></div>
				<?php endif; ?>
			</header>
		</div>
	</section>
	<section class="row">
		<div class="row-container">

			<?php if ( \have_posts() ): ?>
				<?php while ( \have_posts() ) : \the_post(); ?>

					<article id="post-<?php \the_ID(); ?>" <?php \post_class(); ?>>
						<?php \edit_post_link(); ?>

						<header>
							<h2>
								<a href="<?php echo \get_permalink(); ?>">
									<?php \the_title(); ?>
								</a>
							</h2>
							<time datetime="<?php echo \get_the_time( 'Y-m-d', \get_the_ID() ); ?>">
								<?php
									echo \wp_kses( \get_the_date(), array() );
					?>
							</time>
						</header>

						<div class="body">
							<?php \the_excerpt(); ?>
						</div>
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
	<section class="row">
		<div class="row-container pagination">
			<?php
			\the_posts_pagination( array(
				'prev_text'          => 'Previous page',
				'next_text'          => 'Next page',
				'before_page_number' => '<span class="meta-nav screen-reader-text">Page </span>',
			) );
?>
		</div>
	</section>
</main>

<?php \get_footer(); ?>