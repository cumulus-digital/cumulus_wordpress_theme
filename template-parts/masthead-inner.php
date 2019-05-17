<?php
namespace CumulusTheme;
?>
<div class="row-container">
	<?php if (function_exists('custom_logo')): ?>
		<div class="logo custom">
			<a href="<?= \home_url() ?>">
				<?php \custom_logo(); ?>
			</a>
		</div>
	<?php else: ?>
		<div class="logo">
			<a href="<?= \home_url() ?>"></a>
		</div>
	<?php endif ?>

	<div class="hamburger-container">
		<button class="hamburger hamburger--spin" type="button"
		        aria-label="Menu" aria-controls="navigation">
		  <span class="hamburger-box">
		    <span class="hamburger-inner"></span>
		  </span>
		</button>
	</div>

</div>

<?php header_menu() ?>
