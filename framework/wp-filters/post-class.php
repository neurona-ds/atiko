<?php
/**
 * Adds custom classes to the posts class
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.1.2
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wpex_post_class( $classes ) {

	// Get post type
	$type = get_post_type();

	// Not needed here
	if ( 'forum' == $type || 'topic' == $type ) {
		return $classes;
	}

	// Add entry class
	$classes[] = 'entry';

	// Conditional to check for gallery output
	$check_gallery = ( 'post' == $type && wpex_get_mod( 'blog_entry_gallery_output', true ) ) ? true : false;

	// Add media class
	if ( wpex_post_has_media( get_the_ID(), $check_gallery ) ) {
		$classes[] = 'has-media';
	} else {
		$classes[] = 'no-media';
	}

	// Custom link class
	if ( wpex_get_post_redirect_link() ) {
		$classes[] = 'has-redirect';
	}

	// Return classes
	return $classes;
}
add_filter( 'post_class', 'wpex_post_class' );