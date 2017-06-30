<?php
/**
 * Adds a new custom font family select parameter to the VC
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.2
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'vcex_font_family_select_shortcode_param' ) ) {

	function vcex_font_family_select_shortcode_param( $settings, $value ) {

		// Begin output
		$output = '<select name="'
				. $settings['param_name']
				. '" class="wpb_vc_param_value wpb-input wpb-select vcex-chosen '
				. $settings['param_name']
				. ' ' . $settings['type'] .'">'
				. '<option value="" '. selected( $value, '', false ) .'>'. esc_html__( 'Default', 'total' ) .'</option>';
		
		// Add Custom fonts
		$fonts = wpex_add_custom_fonts();
		if ( $fonts && is_array( $fonts ) ) {
			$output .= '<optgroup label="'. esc_html__( 'Custom Fonts', 'total' ) .'">';
			foreach ( $fonts as $font ) {
				$output .= '<option value="'. esc_html( $font ) .'" '. selected( $value, $font, false ) .'>'. esc_html( $font ) .'</option>';
			}
			$output .= '</optgroup>';
		}
		
		// Get Standard font options
		if ( $std_fonts = wpex_standard_fonts() ) {
			$output .= '<optgroup label="'. esc_html__( 'Standard Fonts', 'total' ) .'">';
				foreach ( $std_fonts as $font ) {
					$output .= '<option value="'. esc_html( $font ) .'" '. selected( $font, $value, false ) .'>'. esc_html( $font ) .'</option>';
				}
			$output .= '</optgroup>';
		}
		
		// Google font options
		if ( $google_fonts = wpex_google_fonts_array() ) {
			$output .= '<optgroup label="'. esc_html__( 'Google Fonts', 'total' ) .'">';
				foreach ( $google_fonts as $font ) {
					$output .= '<option value="'. esc_html( $font ) .'" '. selected( $font, $value ) .'>'. esc_html( $font ) .'</option>';
				}
			$output .= '</optgroup>';
		}

		// Close select
		$output .= '</select>';

		$output .= '<div class="vc_description vc_clearfix">' . sprintf( wp_kses( __( 'Choose between standard and Google font options. If you are unfamiliar with Google fonts you can visit <a href="%s" target="_blank">the Google Fonts website</a> and locate the font you like then type the name into the field above.', 'total' ), array(  'a' => array( 'href' => array(), 'target' => array() ) ) ), 'https://fonts.google.com/' ) . '</div>';

		// Return output
		return $output;

	}
	
}
vc_add_shortcode_param(
	'vcex_font_family_select',
	'vcex_font_family_select_shortcode_param',
	wpex_asset_url( 'js/dynamic/vcex-params.js' )
);