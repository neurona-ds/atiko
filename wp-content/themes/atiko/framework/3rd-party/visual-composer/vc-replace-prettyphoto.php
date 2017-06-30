<?php
/**
 * Replace the Visual Composer lightbox with theme lightbox
 *
 * @todo Finish this...wait till next big VC update since things may change
 *
 * @package Total WordPress Theme
 * @subpackage Visual Composer
 * @version 3.6.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Remove scripts
function wpex_vc_remove_prettyphoto_scripts() {
	global $wp_scripts;
	if ( is_object( $wp_scripts ) && $wp_scripts->queue ) {
		if ( in_array( 'prettyphoto', $wp_scripts->queue ) ) {
			wp_deregister_style( 'prettyphoto' );
			wp_deregister_script( 'prettyphoto' );
			wpex_enqueue_ilightbox_skin( 'default' );
		}
	}	
}
add_action( 'wp_footer', 'wpex_vc_remove_prettyphoto_scripts' );

// Add param to localize array
function wpex_vc_lightbox_localize_array( $array ) {
	$array['replaceVcPrettyPhoto'] = true;
	return $array;
}
add_action( 'wpex_localize_array', 'wpex_vc_lightbox_localize_array' );