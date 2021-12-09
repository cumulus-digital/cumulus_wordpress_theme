<?php

namespace CumulusTheme;

?>
<div class="row-container">
	<h1 class="logo">
		<a aria-label="Home" href="<?= \home_url() ?>" title="<?= \get_bloginfo('name') ?>"></a>
	</h1>

	<div class="hamburger-container">
		<button class="hamburger hamburger--spin" type="button"
		        aria-label="Menu" aria-controls="header-menu-container">
		  <span class="hamburger-box">
		    <span class="hamburger-inner"></span>
		  </span>
		</button>
	</div>

</div>

<?php header_menu() ?>
