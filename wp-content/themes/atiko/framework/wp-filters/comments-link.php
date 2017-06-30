<?php
/**
 * Filters the comments_link for smoother local scrolling and to
 * fix issues with fixed/sticky elements
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wpex_get_comments_link( $comments_link, $post_id ) {
	$hash          = get_comments_number( $post_id ) ? '#view_comments' : '#comments_reply';
	$comments_link = get_permalink( $post_id ) . $hash;
	return $comments_link;
}
add_filter( 'get_comments_link', 'wpex_get_comments_link', 10, 2 );
add_filter( 'respond_link', 'wpex_get_comments_link', 10, 2 );