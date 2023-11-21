<?php
/**
 * Add quantcast choice ID field and inject script if provided.
 */

namespace CumulusTheme;

\defined( 'ABSPATH' ) || exit( 'No direct access allowed.' );

function quantcast_choice_field() {
	\register_setting(
		'general',
		'quantcast_choice_id',
		array(
			'description'       => 'Quantcast Choice ID',
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => null,
		)
	);
	\add_settings_field(
		'quantcast_choice_id',
		'<label for="quantcast_choice_id">' . \__( 'Quantcast Choice ID', 'quantcast_choice_id' ) . '</label>',
		function () {
			$value = \get_option( 'quantcast_choice_id', '' );
			echo '<input type="text" id="quantcast_choice_id" name="quantcast_choice_id" value="' . $value . '" class="regular-text" />';
		},
		'general'
	);

	\register_setting(
		'general',
		'quantcast_choice_push_datalayer',
		array(
			'description'       => 'QC: Push IAB & Non-IAB consent data to the Data Layer',
			'type'              => 'boolean',
			'sanitize_callback' => function ( $val ) {
				return (bool) $val;
			},
			'default' => true,
		)
	);
	\add_settings_field(
		'quantcast_choice_push_datalayer',
		'<label for="quantcast_choice_push_datalayer">' . \__( 'QC: Push IAB & Non-IAB consent data to the Data Layer', 'quantcast_choice_push_datalayer' ) . '</label>',
		function () {
			$value = \get_option( 'quantcast_choice_push_datalayer', false );
			\do_action( 'qm/debug', $value );
			echo '<input type="hidden" name="quantcast_choice_push_datalayer" value="0">';
			echo '<input type="checkbox" id="quantcast_choice_push_datalayer" name="quantcast_choice_push_datalayer" value="1"' . ( $value ? ' checked' : '' ) . '>';
		},
		'general'
	);
}
\add_filter( 'admin_init', ns( 'quantcast_choice_field' ) );

\add_filter( 'wp_enqueue_scripts', function () {
	if ( \is_admin() ) {
		return;
	}

	$ID = \get_option( 'quantcast_choice_id', '' );
	if ( $ID ) {
		$ID      = \esc_js( $ID );
		$PUSH_DL = (bool) \get_option( 'quantcast_choice_push_datalayer', false ) ? 'true' : 'false';
		\wp_register_script( PREFIX . '-quantcast-choice-config', null, array(), false, false );
		\wp_add_inline_script(
			PREFIX . '-quantcast-choice-config',
			<<<"END"
window.cmls_qc_config = {
	// QC Choice ID
	UTID: '{$ID}',

	// CCPA support
	ccpa: true,

	// CCPA message container ID
	ccpa_msg_id: 'choice-footer-msg',

	// CCPA message
	ccpa_msg: 'We use cookies' +
		' and other data collection technologies' +
		' to provide the best experience for our customers. You may request' +
		' that your data not be shared with third parties here: ' +
		'<a href="#" class="cmls-uspapi-displayuspui"' +
		'>Do Not Sell or Share My Personal Information</a>',

	// Push consent events to datalayer(s)
	datalayer_push: {$PUSH_DL},

	// DataLayer(s) to push events to
	datalayers: ['dataLayer', 'sharedContainerDataLayer', 'corpDataLayer'],

	// Override detected hostname
	domain: false,

	// When hostname is not overridden, drop subdomains
	// from detected domain
	drop_domain_prefix: true,
};
END
		);
		\wp_enqueue_script(
			PREFIX . '-quantcast-choice-tag',
			theme_url() . '/assets/static/script/inmobi-choice.js',
			array( PREFIX . '-quantcast-choice-config' ),
			false,
			false
		);
		\array_unshift( \wp_scripts()->queue, PREFIX . '-quantcast-choice-tag' );
		\array_unshift( \wp_scripts()->queue, PREFIX . '-quantcast-choice-config' );
	}
}, 0 );
