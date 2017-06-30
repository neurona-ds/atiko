<?php
/**
 * Grid Columns Resonsive VC param
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function vcex_grid_columns_responsive_shortcode_param( $settings, $value ) {

	$medias = array(
		'tl' => __( 'Tablet Landscape', 'total' ),
		'tp' => __( 'Tablet Portrait', 'total' ),
		'pl' => __( 'Phone Landscape', 'total' ),
		'pp' => __( 'Phone Portrait', 'total' ),
	);

	$defaults = array();

	foreach ( $medias as $key => $val ) {
		$defaults[$key] = '';
	}

	$field_values = vcex_parse_multi_attribute( $value, $defaults );

	// Begin output
	$output = '<div class="vcex-responsive-columns-param"><div class="wpex-row wpex-clr">';
	
	// Add options
	if ( function_exists( 'wpex_grid_columns' ) ) {

		$options = wpex_grid_columns();

		foreach ( $medias as $id => $name ) {

			$field_value = $field_values[$id];

			$output .= '<div class="vc_col-sm-6">';

				$output .= '<div class="wpb_element_label">' . esc_attr( $name ) . '</div>';

				$output .= '<select name="' . esc_attr( $id ) . '" class="vcex-responsive-column-select">';

					$output .= '<option value="" '. selected( '', $key, false ) .'>'. esc_attr__( 'Default', 'total' ) .'</option>';

					foreach ( $options as $key => $name ) {

						$output .= '<option value="'. esc_attr( $key )  .'" '. selected( $field_value, $key, false ) .'>'. esc_attr( $name ) .'</option>';

					}

				$output .= '</select>';

			$output .= '</div>';

		}

	}

	// Add hidden field
	$output .= '<input name="' . $settings['param_name'] . '" class="wpb_vc_param_value  ' . $settings['param_name'] . ' ' . $settings['type'] . '_field" type="hidden" value="' . esc_attr( $value ) . '" />';

    // Close wrapper
	$output .= '</div></div>';

	// Return output
	return $output;

}
vc_add_shortcode_param(
	'vcex_grid_columns_responsive',
	'vcex_grid_columns_responsive_shortcode_param',
	wpex_asset_url( 'js/dynamic/vcex-params.js' )
);