<?php

namespace CumulusTheme\Setup;

\defined( 'ABSPATH' ) || exit( 'No direct access allowed.' );

require __DIR__ . '/cache.php';
require __DIR__ . '/security/index.php';
require __DIR__ . '/global.php';
require __DIR__ . '/theme_support.php';
require __DIR__ . '/required.php';
require __DIR__ . '/menus.php';
require __DIR__ . '/media.php';
require __DIR__ . '/filters/index.php';
require __DIR__ . '/shortcodes/index.php';
require __DIR__ . '/backend.php';
require __DIR__ . '/frontend.php';
require __DIR__ . '/cleanup.php';

require __DIR__ . '/acf/index.php';
require __DIR__ . '/plugin_hacks/index.php';
