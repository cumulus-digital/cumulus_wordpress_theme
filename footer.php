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
						<img src="<?=\get_template_directory_uri()?>/assets/prod/images/cumulus-logo-white-full.svg" alt="<?php \bloginfo('name') ?>">
					<?php endif ?>
				</a>
			</div>

			<nav class="menu">
				<?php footer_menu() ?>
			</nav>

			<nav class="social">

			</nav>

			<div class="copyright">
				&copy; <?=date('Y')?> <?=\esc_html(\get_option('copyright_info'))?>
			</div>

		</div>

	</footer>

	<?php wp_footer(); ?>

	<!-- Built by @danielvena -->

	<script>window.jQuery || document.write('<script src="<?=\get_template_directory_uri()?>/assets/static/jquery-3.3.1.min.js>\x3C/script>')</script>
</body>
</html>