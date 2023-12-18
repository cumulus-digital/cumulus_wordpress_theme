<?php

namespace CumulusTheme;

?>

	<footer class="row" role="contentinfo">

		<div class="row-container">

			<div class="logo">
				<a href="<?php echo \home_url(); ?>">
					<?php if ( \function_exists( 'custom_logo' ) ): ?>
						<?php \custom_logo(); ?>
					<?php else: ?>
						<img src="<?php echo theme_url(); ?>/assets/prod/images/cumulus-logo-white-full.svg" alt="<?php \bloginfo( 'name' ); ?>">
					<?php endif; ?>
				</a>
			</div>

			<nav class="menu">
				<?php footer_menu(); ?>
			</nav>

			<nav class="social">
				<?php social_menu(); ?>
			</nav>

			<div class="copyright">
				&copy; <?php echo \date( 'Y' ); ?> <?php echo \esc_html( \get_option( 'copyright_info' ) ); ?>
			</div>

		</div>

	</footer>

	<?php if ( \get_option( 'quantcast_choice_id', false ) ): ?>
	<div class="choice-footer-msg-wrapper">
		<div id="choice-footer-msg"></div>
	</div>
	<?php endif; ?>

	<?php \wp_footer(); ?>

	<!-- theme by github.com/vena -->

</body>
</html>