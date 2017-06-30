<?php
/**
 * Remove Core VC elements
 *
 * @package Total WordPress Theme
 * @subpackage Visual Composer
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wpex_vc_remove_elements() {

	// Array of elements to remove
	$elements = apply_filters( 'wpex_vc_remove_elements', array(
		'vc_teaser_grid',
		'vc_posts_grid',
		'vc_posts_slider',
		'vc_gallery',
		'vc_wp_text',
		'vc_wp_pages',
		'vc_wp_links',
		'vc_wp_categories',
		'vc_wp_meta',
		'vc_carousel',
		'vc_images_carousel',
	) );

	// Return if elements is not an array
	if ( ! is_array( $elements ) ) {
		return;
	}

	// Loop through elements to remove and remove them
	foreach ( $elements as $element ) {
		vc_remove_element( $element );
	}

}
add_action( 'vc_after_init', 'wpex_vc_remove_elements' );