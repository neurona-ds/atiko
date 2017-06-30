<?php
/**
 * Carousel Arrow Positions VC param
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function vcex_carousel_arrow_positions_shortcode_param( $settings, $value ) {

	// Begin output
	$output = '<select name="'
			. $settings['param_name']
			. '" class="wpb_vc_param_value wpb-input wpb-select '
			. $settings['param_name']
			. ' ' . $settings['type'] .'">';
	
	if ( function_exists( 'wpex_carousel_arrow_positions' ) ) {

		$options = wpex_carousel_arrow_positions();

		foreach ( $options as $key => $name ) {

			$output .= '<option value="'. esc_attr( $key )  .'" '. selected( $value, $key, false ) .'>'. esc_attr( $name ) .'</option>';

		}

	}

	$output .= '</select>';

	// Return output
	return $output;

}
vc_add_shortcode_param( 'vcex_carousel_arrow_positions', 'vcex_carousel_arrow_positions_shortcode_param' );