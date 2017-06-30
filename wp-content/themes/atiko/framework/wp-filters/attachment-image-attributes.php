<?php
/**
 * Filters the wp_get_attachment_image() attributes output
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filters the wp_get_attachment_image() function to make sure all images have an alt value
 *
 * @since 4.0
 */
function wpex_parse_attachment_image_alt_attribute( $attr, $attachment ) {
	if ( empty( $attr['alt'] ) ) {
		if ( $img_description = get_post_field( 'post_excerpt', $attachment ) ) {
			$attr['alt'] = trim( strip_tags( $img_description ) );
		}
		// Otherwide get alt from the title
		else if ( $img_title = get_the_title( $attachment ) ) {
			$attr['alt'] = trim( strip_tags( $img_title ) );
		}
	}
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'wpex_parse_attachment_image_alt_attribute', 10, 2 );