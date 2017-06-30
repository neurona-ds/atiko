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

function vcex_ofswitch_shortcode_param( $settings, $value ) {

	$on  = 'true';
	$off = 'false';

	if ( isset( $settings['vcex'] ) ) {
		$on = $settings['vcex']['on'];
		$off = $settings['vcex']['off'];
	}

	$output = '<div class="vcex-ofswitch">';

		$active = $value == $on ? ' vcex-active' : '';

		$output .= '<div class="vcex-btn vcex-on' . $active . '" data-value="' . $on . '">on</div>';

		$active = $value == $off ? ' vcex-active' : '';

		$output .= '<div class="vcex-btn vcex-off' . $active . '" data-value="' . $off . '">off</div>';

		$output .= '<input name="' . $settings['param_name'] . '" class="vcex-hidden-input wpb-input wpb_vc_param_value  ' . $settings['param_name'] . ' ' . $settings['type'] . '_field" type="hidden" value="' . esc_attr( $value ) . '" />';

	$output .= '</div>';

	// Return output
	return $output;

}
vc_add_shortcode_param(
	'vcex_ofswitch',
	'vcex_ofswitch_shortcode_param',
	wpex_asset_url( 'js/dynamic/vcex-params.js' )
);