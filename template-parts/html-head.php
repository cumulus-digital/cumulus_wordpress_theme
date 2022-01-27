<!DOCTYPE html>
<html <?php \language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php \bloginfo('charset'); ?>">

	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	<?php if (\get_bloginfo('description')): ?>
		<meta name="description" content="<?php bloginfo('description'); ?>">
	<?php endif; ?>

	<?php if (\has_site_icon()): ?>
		<?php \wp_site_icon() ?>
	<?php else: ?>
		<link rel="apple-touch-icon" sizes="180x180" href="<?=THEME_PATH?>/assets/static/favicon/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="<?=THEME_PATH?>/assets/static/favicon/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="<?=THEME_PATH?>/assets/static/favicon/favicon-16x16.png">
		<link rel="manifest" href="<?=THEME_PATH?>/assets/static/favicon/site.webmanifest">
		<link rel="mask-icon" href="<?=THEME_PATH?>/assets/static/favicon/safari-pinned-tab.svg" color="#00598e">
		<link rel="shortcut icon" href="<?=THEME_PATH?>/assets/static/favicon/favicon.ico">
		<meta name="msapplication-TileColor" content="#00598e">
		<meta name="msapplication-config" content="<?=THEME_PATH?>/assets/static/favicon/browserconfig.xml">
		<meta name="theme-color" content="#ffffff">
	<?php endif ?>

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

	<?php \wp_head(); ?>
</head>