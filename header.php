<?php
namespace CumulusTheme;

\get_template_part('template-parts/html-head');

?>
<body <?php \body_class(BodyClasses::get()); ?>>
	<?php if ( function_exists( 'gtm4wp_the_gtm_tag' ) ) { \gtm4wp_the_gtm_tag(); } ?>

	<?php \get_template_part('template-parts/masthead') ?>


