<?php
namespace CumulusTheme;
?>
<header class="masthead <?php echo in_array('post_header_image', BodyClasses::get()) || \is_front_page() ? 'trans' : 'solid' ?>">
	
	<?php \get_template_part('template-parts/masthead', 'inner') ?>

</header>