<?php
/**
 * CMLS Base Theme
 * Bypass WP's use of SQL_CALC_FOUND_ROWS.
 *
 * Adapted from:
 * https://web.archive.org/web/20210515005657/https://wpartisan.me/tutorials/wordpress-database-queries-speed-sql_calc_found_rows
 */

namespace CumulusTheme;

\defined( 'ABSPATH' ) || exit( 'No direct access allowed.' );

/**
 * Stop WP from using the SQL_CALC_FOUND_ROWS function by
 * telling it not to try.
 *
 * @param \WP_Query $query
 */
function dbSetNoFoundRowsTrue( $query ) {
	$query->set( 'no_found_rows', true );
}
\add_filter( 'pre_get_posts', ns( 'dbSetNoFoundRowsTrue' ), 10, 1 );

/**
 * Replace SQL where clause to re-implement WP's 2nd query for
 * total rows using count().
 *
 * @param array $clauses
 */
function dbReplaceFoundRows( $clauses, \WP_Query $query ) {
	// Do not operate on single post queries
	if ( $query->is_singular()  ) {
		return $clauses;
	}

	global $wpdb;
	$cache_group = 'CumulusTheme::dbReplaceFoundRows';

	// Check for clauses
	$where    = isset( $clauses[ 'where' ] ) ? $clauses[ 'where' ] : '';
	$join     = isset( $clauses[ 'join' ] ) ? $clauses[ 'join' ] : '';
	$distinct = isset( $clauses[ 'distinct' ] ) ? $clauses[ 'distinct' ] : '';
	$groupby  = isset( $clauses[ 'groupby' ] ) && \mb_strlen( $clauses['groupby'] ) ? 'GROUP BY ' . $clauses[ 'groupby' ] : '';
	// $fields   = isset( $clauses['fields'] ) ? $clauses['fields'] : "`{$wpdb->posts}`.`ID`";
	$fields = "`{$wpdb->posts}`.`ID`";

	$max_num_pages = 0;

	// Posts per page may be query-specific or global
	if ( ! empty( $query->query_vars['posts_per_page'] ) ) {
		$posts_per_page = \absint( $query->query_vars['posts_per_page'] );
	} else {
		$posts_per_page = \absint( \get_option( 'posts_per_page' ) );
	}

	// Construct our total pages query using COUNT(ID)
	$count_query = "SELECT {$distinct} COUNT(*) FROM `{$wpdb->posts}` {$join} WHERE 1=1 {$where}";

	if ( $groupby ) {
		$count_query = "SELECT COUNT(*) FROM (SELECT {$distinct} {$fields} FROM `{$wpdb->posts}` {$join} WHERE 1=1 {$where} {$groupby}) AS OQ";
	}

	$found_posts = CMLS_Cache::get( $count_query, $cache_group );

	if ( $found_posts !== false ) {
		$query->found_posts = $found_posts;
	} else {
		// Fetch and calculate max_num_pages
		$query->found_posts = $wpdb->get_var( $count_query );
	}

	$max_num_pages = \ceil( $query->found_posts / $posts_per_page );

	CMLS_Cache::set( $count_query, $query->found_posts, $cache_group, 30 * 60 );
	$query->max_num_pages = $max_num_pages;

	// Return the $clauses so the main query can run.
	return $clauses;
}
\add_filter( 'posts_clauses', ns( 'dbReplaceFoundRows' ), 10, 2 );

// Clear the found rows cache on post save
function dbClearFoundRowsCache() {
	CMLS_Cache::flushGroup( 'CumulusTheme::dbReplaceFoundRows' );
}
\add_filter( 'post_save', ns( 'dbClearFoundRowsCache' ) );
