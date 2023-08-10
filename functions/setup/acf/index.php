<?php
/**
 * ACF setup continued.
 */

namespace CumulusTheme;

\defined( 'ABSPATH' ) || exit( 'No direct access allowed.' );

// Disable ACF menu if we're not in a local dev environment
function acfDisableAdminInProd() {
	if (
		\mb_strstr( \get_site_url(), '.local' )       === false
		&& \mb_strstr( \get_site_url(), '.dev' )      === false
		&& \mb_strstr( \get_site_url(), 'localhost' ) === false
	) {
		\acf_update_setting( 'show_admin', false );
	}
}
\add_action( 'acf/init', ns( 'acfDisableAdminInProd' ) );

// Load and save only our acf-json
$MY_ACF_GROUPS = array(
	'group_5cddb72336fb5', // Header options
	'group_5cddbd6323e9b', // Card options
	'group_5cddc31c09f70', // Footer Options
	'group_5cddcf8c63698', // Special footer options
	'group_5cdf099992b63', // Front page video
);
$ACF_JSON = new Vendors\vena\AcfJson\Loader( $MY_ACF_GROUPS, theme_path() );

include __DIR__ . '/permanent_metabox_order.php';
include __DIR__ . '/location-hierarchical_post.php';
include __DIR__ . '/location-hierarchical_taxonomy.php';
include __DIR__ . '/post_title.php';
include __DIR__ . '/post_author.php';
include __DIR__ . '/validation.php';

foreach ( $MY_ACF_GROUPS as $acf_group ) {
	acfResetMetaboxesForCPT( 'post', $acf_group );
	acfResetMetaboxesForCPT( 'page', $acf_group );
}
