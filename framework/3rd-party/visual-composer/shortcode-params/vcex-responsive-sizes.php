<?php
/**
 * Responsive Sizes VC param
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function vcex_responsive_sizes_shortcode_param( $settings, $value ) {

	if ( $value && strpos( $value, ':' ) === false ) {
		$ogvalue = $value;
		$value = 'd:'. $value;
	}

	$medias = array(
		'd'  => array(
			'label' => __( 'Desktop', 'total' ),
			'icon'  => 'dashicons dashicons-desktop',
		),
		'tl' => array(
			'label' => __( 'Tablet Landscape', 'total' ),
			'icon'  => 'dashicons dashicons-tablet',
		),
		'tp' => array(
			'label' => __( 'Tablet Portrait', 'total' ),
			'icon'  => 'dashicons dashicons-tablet',
		),
		'pl' => array(
			'label' => __( 'Phone Landscape', 'total' ),
			'icon'  => 'dashicons dashicons-smartphone',
		),
		'pp' => array(
			'label' => __( 'Phone Portrait', 'total' ),
			'icon'  => 'dashicons dashicons-smartphone',
		),
	);

	$defaults = array();

	foreach ( $medias as $key => $val ) {
		$defaults[$key] = '';
	}

	$field_values = vcex_parse_multi_attribute( $value, $defaults );

	// Begin output
	$output = '<div class="vcex-rs-param vc_clearfix">';
	
	// Add options
	if ( function_exists( 'wpex_grid_columns' ) ) {

		$options = wpex_grid_columns();

		$count = 0;

		foreach ( $medias as $key => $val ) {

			$count++;

			$classes = 'vcex-item vcex-item-' . $count;

			if ( $count > 1 && ! $field_values['d'] ) {
				$classes .= ' vcex-hidden';
			}

			$output .= '<div class="' . $classes . '">';

				$icon_classes = 'vcex-icon';

				if ( 'pl' == $key || 'tl' == $key ) {
					$icon_classes .= ' vcex-flip';
				}

				$output .= '<span class="'. $icon_classes .'"><span class="'. $val['icon'] .'"></span></span>';

				$output .= '<input class="vcex-input" name="' . esc_attr( $key ) . '" value="'. $field_values[$key] .'" type="text" placeholder="-">';

			$output .= '</div>';

		}

	}

	if ( ! empty( $ogvalue ) ) {
		$value = $ogvalue;
	}

	// Add hidden field
	$output .= '<input name="' . $settings['param_name'] . '" class="wpb_vc_param_value  ' . $settings['param_name'] . ' ' . $settings['type'] . '_field" type="hidden" value="' . esc_attr( $value ) . '">';

    // Close wrapper
	$output .= '</div>';

	// Return output
	return $output;

}
vc_add_shortcode_param(
	'vcex_responsive_sizes',
	'vcex_responsive_sizes_shortcode_param',
	wpex_asset_url( 'js/dynamic/vcex-params.js' )
);