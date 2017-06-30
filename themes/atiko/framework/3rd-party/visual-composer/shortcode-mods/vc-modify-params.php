<?php
/**
 * Updates Visual Composer map to modify params all in one single function to speed things up
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

function wpex_vc_modify_params() {

	$params = apply_filters( 'wpex_vc_modify_params', array() );

	if ( ! $params ) {
		return;
	}

	foreach ( $params as $module => $params ) {

		foreach ( $params as $param_id => $settings ) {

			if ( is_array( $settings ) ) {

				foreach ( $settings as $key => $value ) {

					$get_param = WPBMap::getParam( $module, $param_id );

					if ( $get_param ) {

						if ( is_array( $value ) ) {
							if ( 'dependency' == $key ) {
								$get_param[$key] = $value;
							} else {
								foreach ( $value as $sub_k => $sub_v ) {
									$get_param[$key][$sub_k] = $sub_v;
								}
							}
						} else {
							$get_param[$key] = $value;
						}

						vc_update_shortcode_param( $module, $get_param );

					}

				}

			} else {

				$get_param = WPBMap::getParam( $module, $param_id );

				if ( $get_param ) {

					if ( is_array( $value ) ) {
						if ( 'dependency' == $key ) {
							$get_param[$key] = $value;
						} else {
							foreach ( $value as $sub_k => $sub_v ) {
								$get_param[$key][$sub_k] = $sub_v;
							}
						}
					} else {
						$get_param[$key] = $value;
					}

					vc_update_shortcode_param( $module, $get_param );

				}

			}

		}

	}

}

// Doesn't need init hook because we are modifying VC modules only.
// MUCH slower hooking into INIT
add_action( 'vc_after_init', 'wpex_vc_modify_params', 40 );