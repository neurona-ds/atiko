<?php
/**
 * Icon Font Family VC param
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function vcex_icon_font_family_shortcode_param( $settings, $value ) {

	// Begin output
	$output = '<select name="'
			. $settings['param_name']
			. '" class="wpb_vc_param_value wpb-input wpb-select '
			. $settings['param_name']
			. ' ' . $settings['type'] .'">';
	
	// Define options
	$options = apply_filters( 'vcex_icon_font_family_options', array(
		''            => __( 'None', 'total' ),
		'fontawesome' => __( 'Font Awesome', 'total' ),
		'openiconic'  => __( 'Open Iconic', 'total' ),
		'typicons'    => __( 'Typicons', 'total' ),
		'entypo'      => __( 'Entypo', 'total' ),
		'linecons'    =>__( 'Linecons', 'total' ),
	) );

	// Loop through options
	foreach ( $options as $key => $name ) {

		$output .= '<option value="'. esc_attr( $key )  .'" '. selected( $value, $key, false ) .'>'. esc_attr( $name ) .'</option>';

	}

	$output .= '</select>';

	// Return output
	return $output;

}
vc_add_shortcode_param( 'vcex_icon_font_family', 'vcex_icon_font_family_shortcode_param' );