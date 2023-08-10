<?php

namespace CumulusTheme;

// Pre-compile body classes
if ( \is_singular() ) {
	generateBodyClasses();
}

\get_template_part( 'template-parts/html-head' );

// Add a slug class to body classes based on post_name
$slug = null;
$qo   = \get_queried_object();

if ( \is_object( $qo ) && \property_exists( $qo, 'post_name' ) ) {
	$slug = 'slug-' . \esc_attr( $qo->post_name );
}
?>
<body <?php \body_class( $slug ); ?>>
<?php
	if ( \function_exists( 'wp_body_open' ) ) {
		\wp_body_open();
	} else {
		\do_action( 'wp_body_open' );
	}
?>

<?php
	if ( \function_exists( 'gtm4wp_the_gtm_tag' ) ) {
		\gtm4wp_the_gtm_tag();
	}
?>

	<?php \get_template_part( 'template-parts/masthead' ); ?>
