<!doctype html>
<html <?php \language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php \bloginfo('charset'); ?>">

	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	<meta name="description" content="<?php bloginfo('description'); ?>">

	<?php if (\has_site_icon()): ?>
		<?php \wp_site_icon() ?>
	<?php else: ?>
		<link rel="apple-touch-icon" sizes="180x180" href="<?=\get_template_directory_uri()?>/assets/static/favicon/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="<?=\get_template_directory_uri()?>/assets/static/favicon/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="<?=\get_template_directory_uri()?>/assets/static/favicon/favicon-16x16.png">
		<link rel="manifest" href="<?=\get_template_directory_uri()?>/assets/static/favicon/site.webmanifest">
		<link rel="mask-icon" href="<?=\get_template_directory_uri()?>/assets/static/favicon/safari-pinned-tab.svg" color="#00598e">
		<link rel="shortcut icon" href="<?=\get_template_directory_uri()?>/assets/static/favicon/favicon.ico">
		<meta name="msapplication-TileColor" content="#00598e">
		<meta name="msapplication-config" content="<?=\get_template_directory_uri()?>/assets/static/favicon/browserconfig.xml">
		<meta name="theme-color" content="#ffffff">
	<?php endif ?>

	<?php \wp_head(); ?>
</head>