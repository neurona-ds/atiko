<?php
/**
 * Overlay VC param
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function vcex_overlay_shortcode_param( $settings, $value ) {

	// Begin output
	$output = '<select name="'
			. $settings['param_name']
			. '" class="wpb_vc_param_value wpb-input wpb-select '
			. $settings['param_name']
			. ' ' . $settings['type'] .'">';
	
	if ( function_exists( 'wpex_overlay_styles_array' ) ) {

		$options = wpex_overlay_styles_array();

		foreach ( $options as $key => $name ) {

			$output .= '<option value="'. esc_attr( $key )  .'" '. selected( $value, $key, false ) .'>'. esc_attr( $name ) .'</option>';

		}

	}

	$output .= '</select>';

	// Return output
	return $output;

}
vc_add_shortcode_param( 'vcex_overlay', 'vcex_overlay_shortcode_param' );