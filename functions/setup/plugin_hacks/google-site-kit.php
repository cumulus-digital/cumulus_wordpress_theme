<?php

/**
 * Hacks for Google Site Kit.
 */

namespace CumulusTheme\Setup\PluginHacks;

\defined( 'ABSPATH' ) || exit( 'No direct access allowed.' );

// Remove Google Site Kit "generator" meta tag
\add_filter( 'googlesitekit_generator', '__return_empty_string' );
