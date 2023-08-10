<?php
/**
 * Various security-minded changes inspired by or lifted from
 * github.com/CityOfNewYork/nyco-wp-boilerplate.
 */

namespace CumulusTheme\Setup\Security;

\defined( 'ABSPATH' ) || exit( 'No direct access allowed.' );

require __DIR__ . '/disable_defaults.php';
require __DIR__ . '/disable_comments.php';
require __DIR__ . '/headers.php';
require __DIR__ . '/robots.php';
