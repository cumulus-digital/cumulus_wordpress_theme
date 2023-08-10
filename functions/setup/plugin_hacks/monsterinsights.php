<?php
/**
 * Hacks for Monster Insights.
 */

namespace CumulusTheme\Setup\PluginHacks;

\defined( 'ABSPATH' ) || exit( 'No direct access allowed.' );

// Remove MonsterInsights comments and version leak
if ( \defined( 'MONSTERINSIGHTS_VERSION' ) || \defined( 'MONSTERINSIGHTS_PRO_VERSION' ) ) {
	\add_action( 'get_header', function () {
		\ob_start( function ( $o ) {
			$ret = \preg_replace( '/\n?<\!\-\-.*?monsterinsights.*?>/mi', '', $o );

			return \preg_replace( '/\n?\s*var mi_version = .*;/mi', '', $ret );
		} );
	} );
	\add_action( 'wp_head', function () {
		\ob_end_flush();
	}, 999 );
}
