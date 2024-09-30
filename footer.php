<?php

namespace CumulusTheme;

?>

	<footer class="row" role="contentinfo">

		<div class="row-container">

			<div class="logo">
				<a href="<?php echo \home_url(); ?>">
					<img src="<?php echo theme_url(); ?>/assets/prod/images/cumulus-logo-white-full.svg" alt="<?php \bloginfo( 'name' ); ?>" loading="lazy">
				</a>
				<?php if ( \mb_strlen( \get_option( 'copyright_info' ) ) ): ?>
				<div class="copyright">
					<?php echo \esc_html( \str_ireplace( '%YEAR%', \date( 'Y' ), \get_option( 'copyright_info' ) ) ); ?>
				</div>
				<?php endif; ?>
			</div>

			<?php if ( has_footer_menu() ): ?>
			<nav class="menu">
				<?php footer_menu(); ?>
			</nav>
			<?php endif; ?>

			<?php if ( has_social_menu() ): ?>
			<nav class="social">
				<?php social_menu(); ?>
			</nav>
			<?php endif; ?>

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