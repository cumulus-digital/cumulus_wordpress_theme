<?php

declare(strict_types = 1);

use Isolated\Symfony\Component\Finder\Finder;

$composer = \json_decode( \file_get_contents( 'composer.json' ), true );

$wp_functions = \str_getcsv( \file_get_contents( __DIR__ . '/.php-cs-fixer/wp-functions.csv' ), ',', '"', '\\' );
$wp_classes   = \str_getcsv( \file_get_contents( __DIR__ . '/.php-cs-fixer/wp-classes.csv' ), ',', '"', '\\' );
$wp_consts    = \str_getcsv( \file_get_contents( __DIR__ . '/.php-cs-fixer/wp-consts.csv' ), ',', '"', '\\' );

return array(
	// The prefix configuration. If a non null value will be used, a random prefix will be generated.
	'prefix' => 'CumulusTheme\Vendors',

	// By default when running php-scoper add-prefix, it will prefix all relevant code found in the current working
	// directory. You can however define which files should be scoped by defining a collection of Finders in the
	// following configuration key.
	//
	// For more see: https://github.com/humbug/php-scoper#finders-and-paths
	'finders' => array(
		// Finder::create()->files()->in( 'vendor' ),
		Finder::create()
			->files()
			->ignoreVCS( true )
			->notName( '/LICENSE|.*\\.md|.*\\.dist|Makefile|composer\\.json|composer\\.lock/' )
			->exclude( array(
				'doc',
				'test',
				'test_old',
				'tests',
				'Tests',
				'vendor-bin',
				'php-cs-fixer',
				'friendsofphp',
			) )
			->notPath( \array_keys( $composer['require-dev'] ) )
			->notPath( 'friendsofphp' )
			->notPath( 'bin' )
			->in( 'vendor' ),
		Finder::create()->append( array(
			'composer.json',
			'composer.lock',
		) ),
	),

	// Whitelists a list of files. Unlike the other whitelist related features, this one is about completely leaving
	// a file untouched.
	// Paths are relative to the configuration file unless if they are already absolute
	/*
	'files-whitelist' => [
		//'src/a-whitelisted-file.php',
	],
	 */
	'exclude-files' => array(
		'vendor/tgmpa/tgm-plugin-activation/class-tgm-plugin-activation.php',
	),

	// When scoping PHP files, there will be scenarios where some of the code being scoped indirectly references the
	// original namespace. These will include, for example, strings or string manipulations. PHP-Scoper has limited
	// support for prefixing such strings. To circumvent that, you can define patchers to manipulate the file to your
	// heart contents.
	//
	// For more see: https://github.com/humbug/php-scoper#patchers
	'patchers' => array(
		function ( string $filePath, string $prefix, string $contents ): string {
			// Change the contents here.

			// Fix TGMPA calls
			if ( $filePath === __DIR__ . '/vendor/tgmpa/tgm-plugin-activation/class-tgm-plugin-activation.php' ) {
				/*
				$contents = \str_replace(
					"array('TGMPA_Utils',",
					"array('\\{$prefix}\\TGMPA_Utils',",
					$contents
				);
				$contents = \preg_replace(
					'/(\s*)\\\load_tgm_plugin_activation\(\);/',
					"$1\\{$prefix}\\load_tgm_plugin_activation();",
					$contents
				);
				$contents = \preg_replace(
					"/add_action\(\s*'([^']+)',\s*'/",
					"add_action( '$1', '\\{$prefix}\\",
					$contents
				);
				//*/
			}

			return $contents;
		},
	),

	// If `true` then the user defined constants belonging to the global namespace will not be prefixed.
	//
	// For more see https://github.com/humbug/php-scoper#constants--constants--functions-from-the-global-namespace
	'expose-global-constants' => true,

	// If `true` then the user defined classes belonging to the global namespace will not be prefixed.
	//
	// For more see https://github.com/humbug/php-scoper#constants--constants--functions-from-the-global-namespace
	'expose-global-classes' => true,

	// If `true` then the user defined functions belonging to the global namespace will not be prefixed.
	//
	// For more see https://github.com/humbug/php-scoper#constants--constants--functions-from-the-global-namespace
	'expose-global-functions' => true,

	'exclude-classes'   => $wp_classes,
	'exclude-functions' => \array_merge(
		array(
			'wp_count_terms',
		),
		\array_map( 'strtolower', $wp_functions )
	),
	'exclude-constants' => $wp_consts,
);
