<?php

namespace CumulusTheme;

\get_template_part('template-parts/html-head');

?>
<body <?php \body_class(BodyClasses::get()); ?>>
<?php
    if (\function_exists('wp_body_open')) {
        \wp_body_open();
    } else {
        \do_action('wp_body_open');
    }
?>

<?php
    if (\function_exists('gtm4wp_the_gtm_tag')) {
        \gtm4wp_the_gtm_tag();
    }
?>

	<?php \get_template_part('template-parts/masthead') ?>
