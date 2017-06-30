<?php
/**
 * Font Weight VC param
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function vcex_button_set_shortcode_param( $settings, $value ) {

	$type    = isset( $settings['vcex']['type'] ) ? $settings['vcex']['type'] : '';
	$buttons = '';

	if ( ! $type ) {
		$buttons = $settings['value'];
	} elseif ( 'text_align' == $type ) {
		$options = array(
			'' => array(
				'title' => __( 'Default', 'total' ),
			),
			'left' => array(
				'icon'  => 'dashicons dashicons-align-left',
				'title' => __( 'Left', 'total' ),
			),
			'center' => array(
				'icon'  => 'dashicons dashicons-align-center',
				'title' => __( 'Middle', 'total' ),
			),
			'right' => array(
				'icon'  => 'dashicons dashicons-align-right',
				'title' => __( 'Right', 'total' ),
			),
		);
	}

	$output = '<div class="vcex-button-set">';

		foreach ( $options as $k => $v ) {

			$output .= '<div class="vcex-btn' . $active . '" data-value="' . $k . '">';

				if ( isset( $v['icon'] ) ) {

					$output .= '<span class="' . $v['icon'] . '" data-title="' . $v['title'] . '"></span>';

				} else {

					$output .= '<span>' . $v['title'] . '</span>';

				}

			$output .= '</div>';

		}

		$output .= '<input name="' . $settings['param_name'] . '" class="vcex-hidden-input wpb-input wpb_vc_param_value  ' . $settings['param_name'] . ' ' . $settings['type'] . '_field" type="hidden" value="' . esc_attr( $value ) . '" />';

	$output .= '</div>';

	// Return output
	return $output;

}
vc_add_shortcode_param(
	'vcex_button_set',
	'vcex_button_set_shortcode_param',
	wpex_asset_url( 'js/dynamic/vcex-params.js' )
);