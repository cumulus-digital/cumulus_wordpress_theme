<?php

namespace CumulusTheme;

?>

	<footer class="row" role="contentinfo">

		<div class="row-container">

			<div class="logo">
				<a href="<?= \home_url() ?>">
					<?php if (function_exists('custom_logo')): ?>
						<?php \custom_logo(); ?>
					<?php else: ?>
						<img src="<?=THEME_PATH?>/assets/prod/images/cumulus-logo-white-full.svg" alt="<?php \bloginfo('name') ?>">
					<?php endif ?>
				</a>
			</div>

			<nav class="menu">
				<?php footer_menu() ?>
			</nav>

			<nav class="social">
				<?php social_menu() ?>
			</nav>

			<div class="copyright">
				&copy; <?=date('Y')?> <?=\esc_html(\get_option('copyright_info'))?>
			</div>

		</div>

	</footer>
	<div class="choice-footer-msg-wrapper">
		<div id="choice-footer-msg"></div>
	</div>

	<?php wp_footer(); ?>

	<!-- theme by github.com/vena -->

</body>
</html>