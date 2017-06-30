<?php
/**
 * Exclude items from the next and previous posts functions
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wpex_prev_next_join_posts_exclude( $join ) {
	global $wpdb;
	$join .= " LEFT JOIN $wpdb->postmeta AS m ON ( p.ID = m.post_id AND m.meta_key = 'wpex_post_link' )";
	return $join;
}
add_filter( 'get_previous_post_join', 'wpex_prev_next_join_posts_exclude' );
add_filter( 'get_next_post_join', 'wpex_prev_next_join_posts_exclude' );

function wpex_prev_next_where_posts_exclude( $where ) {
	$where .= " AND ( (m.meta_key = 'wpex_post_link' AND CAST(m.meta_value AS CHAR) = '' ) OR m.meta_id IS NULL ) ";
	return $where;
}
add_filter( 'get_previous_post_where', 'wpex_prev_next_where_posts_exclude' );
add_filter( 'get_next_post_where', 'wpex_prev_next_where_posts_exclude' );