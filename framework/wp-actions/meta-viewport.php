<?php
/**
 * Add meta viewport tag to header
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wpex_meta_viewport() {
	
	// Responsive viewport viewport
	if ( wpex_is_layout_responsive() ) {
		$viewport = '<meta name="viewport" content="width=device-width, initial-scale=1">';
	}

	// Non responsive meta viewport
	else {
		$width    = intval( wpex_get_mod( 'main_container_width', '980' ) );
		$width    = $width ? $width: '980';
		$viewport = '<meta name="viewport" content="width=' . $width . '" />';
	}

	// Apply filters to the meta viewport for child theme tweaking
	echo apply_filters( 'wpex_meta_viewport', $viewport );
	echo "\r\n";
	
}
add_action( 'wp_head', 'wpex_meta_viewport', 1 );