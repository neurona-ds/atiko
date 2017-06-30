<?php
/**
 * Updates VC map to add new params all in one single function to speed things up
 * rather then using vc_update_shortcode_param which can be slow.
 *
 * @package Total WordPress Theme
 * @subpackage Visual Composer
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// @todo update to use vc_map_update
// vc_map_update($shortcode, $newData); make sure to unset($newData['base']) to avoid warning.
// $getAllShortCodes = WPBMap::getAllShortCodes();

function wpex_vc_add_params() {

	if ( ! function_exists( 'vc_add_param' ) ) {
		return;
	}

	$params = apply_filters( 'wpex_vc_add_params', array() );

	if ( ! $params ) {
		return;
	}

	foreach ( $params as $module => $params ) {

		if ( is_array( $params ) ) {

			foreach ( $params as $param ) {

				vc_add_param( $module, $param );

			}

		}

	}

}

// Doesn't need init hook because we are modifying VC modules only.
// MUCH slower hooking into INIT
add_action( 'vc_after_init', 'wpex_vc_add_params', 40 );