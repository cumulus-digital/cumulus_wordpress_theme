<?php
/**
 * Wrapper for WP object cache.
 */

namespace CumulusTheme;

\defined( 'ABSPATH' ) || exit( 'No direct access allowed.' );

class CMLS_Cache {
	private static $instance;

	// Default cache object expiratioon
	private $expires = 1800;

	public function __construct() {
	}

	public static function getInstance() {
		if ( self::$instance == null ) {
			self::$instance = new CMLS_Cache();
		}

		return self::$instance;
	}

	public static function get( $key, $group ) {
		return \wp_cache_get( $key, $group );
	}

	/**
	 * @param string $key
	 * @param mixed  $value
	 * @param string $group
	 * @param mixed  $expires False or a time in seconds
	 */
	public static function set( $key, $value, $group, $expires = false ) {
		$cache = self::getInstance();

		if ( $expires === false ) {
			$expires = $cache->expires;
		}

		return \wp_cache_set( $key, $value, $group, $expires );
	}

	public static function delete( $key, $group ) {
		$cache = self::getInstance();

		return \wp_cache_delete( $key, $group );
	}

	public static function flushGroup( $group ) {
		$cache = self::getInstance();
		global $wp_object_cache;

		if ( \array_key_exists( $group, $wp_object_cache->__get( 'cache' ) ) ) {
			foreach ( \array_keys( $wp_object_cache->__get( 'cache' )[$group] ) as $key ) {
				$cache->delete( $key, $group );
			}
		}
	}
}
