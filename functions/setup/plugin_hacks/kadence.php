<?php
/**
 * Hacks for Kadence Blocks.
 */

namespace CumulusTheme\Setup\PluginHacks;

\defined( 'ABSPATH' ) || exit( 'No direct access allowed.' );

if ( \defined( 'KADENCE_BLOCKS_VERSION' ) ) {
	// Force Kadence to use our theme's editor width
	\add_filter( 'kadence_blocks_editor_width', '__return_false', \PHP_INT_MAX );

	// Disable Kadence Design Library
	\add_filter( 'kadence_blocks_design_library_enabled', '__return_false', \PHP_INT_MAX );
}
