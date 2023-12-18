<?php

namespace CumulusTheme;

$description = '';
if ( \is_singular() && \has_excerpt() ) {
	$description = \get_the_excerpt();
} elseif ( \get_bloginfo( 'description' ) ) {
	$description = \get_bloginfo( 'description' );
} else {
	$description = \get_bloginfo( 'name' );
}

?><!DOCTYPE html>
<html <?php \language_attributes(); ?> class="no-js">
<head>
	<?php if ( \has_action( 'cmls_template-head-begin' ) ): ?>
		<!-- action:cmls_template-head-begin -->
		<?php \do_action( 'cmls_template-head-begin' ); ?>
		<!-- /action:cmls_template-head-begin -->
	<?php endif; ?>

	<meta charset="<?php \bloginfo( 'charset' ); ?>">

	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

	<meta name="description" content="<?php echo \wp_kses( $description, array() ); ?>">

	<?php if ( \has_site_icon() ): ?>
		<?php \wp_site_icon(); ?>
	<?php else: ?>
		<link rel="apple-touch-icon" sizes="180x180" href="<?php echo theme_url(); ?>/assets/static/favicon/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="<?php echo theme_url(); ?>/assets/static/favicon/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="<?php echo theme_url(); ?>/assets/static/favicon/favicon-16x16.png">
		<link rel="manifest" href="<?php echo theme_url(); ?>/assets/static/favicon/site.webmanifest">
		<link rel="mask-icon" href="<?php echo theme_url(); ?>/assets/static/favicon/safari-pinned-tab.svg" color="#00598e">
		<link rel="shortcut icon" href="<?php echo theme_url(); ?>/assets/static/favicon/favicon.ico">
		<meta name="msapplication-TileColor" content="#00598e">
		<meta name="msapplication-config" content="<?php echo theme_url(); ?>/assets/static/favicon/browserconfig.xml">
		<meta name="theme-color" content="#ffffff">
	<?php endif; ?>

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link rel="preconnect" href="https://www.googletagmanager.com">
	<link rel="preconnect" href="https://www.googletagmanager.com" crossorigin>

	<?php \wp_head(); ?>

	<?php if ( \has_action( 'cmls_template-head-end' ) ): ?>
		<!-- action:cmls_template-head-end -->
		<?php \do_action( 'cmls_template-head-end' ); ?>
		<!-- /action:cmls_template-head-end -->
	<?php endif; ?>
</head>