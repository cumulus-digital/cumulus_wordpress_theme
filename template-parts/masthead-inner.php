<?php

namespace CumulusTheme;

?>
<div class="row-container">
	<div class="logo">
		<a aria-label="Home" href="<?= \home_url() ?>"></a>
	</div>

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
