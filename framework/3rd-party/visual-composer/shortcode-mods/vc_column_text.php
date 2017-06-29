<?php
/**
 * Visual Composer Text Block Configuration
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start Class
if ( ! class_exists( 'VCEX_VC_Column_Text_Config' ) ) {
	
	class VCEX_VC_Column_Text_Config {

		/**
		 * Main constructor
		 *
		 * @since 3.6.0
		 */
		public function __construct() {
			add_action( 'init', array( 'VCEX_VC_Column_Text_Config', 'add_params' ) );
			add_filter( 'vc_shortcode_output', array( 'VCEX_VC_Column_Text_Config', 'custom_output' ), 10, 3 );
		}

		/**
		 * Adds new params for the VC Rows
		 *
		 * @since 3.6.0
		 */
		public static function add_params() {

			// Re-usable strings
			$s_yes = __( 'Yes', 'total' );
			$s_no = __( 'No', 'total' );

			// Width
			vc_add_param( 'vc_column_text', array(
				'type' => 'textfield',
				'heading' => __( 'Width', 'total' ),
				'param_name' => 'width',
				'description' => __( 'Enter a custom width instead of using breaks to slim down your content width.', 'total' ),
			) );

			// Typography
			$typo_params = array(
				array(
					'type' => 'vcex_font_family_select',
					'heading' => __( 'Font Family', 'total' ),
					'param_name' => 'font_family',
				),
				array(
					'type' => 'colorpicker',
					'heading' => __( 'Color', 'total' ),
					'param_name' => 'color',
				),
				array(
					'type' => 'vcex_responsive_sizes',
					'heading' => __( 'Font Size', 'total' ),
					'param_name' => 'font_size',
					'target' => 'font-size',
				),
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'false',
					'heading' => __( 'Auto Responsive Font Size', 'total' ),
					'param_name' => 'responsive_text',
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Minimum Font Size', 'total' ),
					'param_name' => 'min_font_size',
					'dependency' => array( 'element' => 'responsive_text', 'value' => 'true' ),
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Line Height', 'total' ),
					'param_name' => 'line_height',
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Letter Spacing', 'total' ),
					'param_name' => 'letter_spacing',
				),
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'false',
					'heading' => __( 'Italic', 'total' ),
					'param_name' => 'italic',
				),
				array(
					'type' => 'vcex_font_weight',
					'heading' => __( 'Font Weight', 'total' ),
					'param_name' => 'font_weight',
				),
				array(
					'type' => 'dropdown',
					'heading' => __( 'Text Align', 'total' ),
					'param_name' => 'text_align',
					'value' => array_flip( wpex_alignments() ),
					'std' => '',
				),
			);

			foreach ( $typo_params as $param ) {
				$param['group'] = __( 'Typography', 'total' );
				vc_add_param( 'vc_column_text', $param );
			}

		}

		/**
		 * Add custom HTML to ouput
		 *
		 * @since 4.0
		 */
		public static function custom_output( $output, $obj, $atts ) {

			// Check needed settings
			if ( ! $obj->settings( 'base' ) ) {
				return $output;
			}

			// Only tweaks neeed for single image
			if ( 'vc_column_text' != $obj->settings( 'base' ) ) {
				return $output;
			}

			$add_attrs   = '';
			$add_classes = '';

			// Inline style
			$inline_style = vcex_inline_style( array(
				'color'          => isset( $atts['color'] ) ? $atts['color'] : '',
				'font_family'    => isset( $atts['font_family'] ) ? $atts['font_family'] : '',
				'font_size'      => isset( $atts['font_size'] ) ? $atts['font_size'] : '',
				'letter_spacing' => isset( $atts['letter_spacing'] ) ? $atts['letter_spacing'] : '',
				'font_weight'    => isset( $atts['font_weight'] ) ? $atts['font_weight'] : '',
				'text_align'     => isset( $atts['text_align'] ) ? $atts['text_align'] : '',
				'line_height'    => isset( $atts['line_height'] ) ? $atts['line_height'] : '',
				'width'          => isset( $atts['width'] ) ? $atts['width'] : '',
				'font_style'     => ( isset( $atts['italic'] ) && 'true' == $atts['italic'] ) ? 'italic' : '',
			), false );

			// Add inline style to add_attrs var
			if ( $inline_style ) {
				$add_attrs .= ' style="' . $inline_style . '"';
			}

			// load custom fonts
			if ( ! empty( $atts['font_family'] ) ) {
				wpex_enqueue_google_font( $atts['font_family'] );
			}

			// Auto Responsive text
			if ( ! empty( $atts['responsive_text'] )
				&& 'true' == $atts['responsive_text']
				&& ! empty( $atts['font_size'] )
				&& ! empty( $atts['min_font_size'] )
			) {

				$font_size     = $atts['font_size'];
				$min_font_size = $atts['min_font_size'];

				// Convert em font size to pixels
				if ( strpos( $atts['font_size'], 'em' ) !== false ) {
					$font_size = str_replace( 'em', '', $atts['font_size'] );
					$font_size = $font_size * wpex_get_body_font_size();
				}

				// Convert em min-font size to pixels
				if ( strpos( $atts['min_font_size'], 'em' ) !== false ) {
					$min_font_size = str_replace( 'em', '', $atts['min_font_size'] );
					$min_font_size = $min_font_size * wpex_get_body_font_size();
				}

				// Add inline Jsv
				vcex_inline_js( 'responsive_text' );

				// Add wrap classes and data
				$add_attrs .= ' data-max-font-size="'. absint( $font_size ) .'"';
				$add_attrs .= ' data-min-font-size="'. absint( $min_font_size ) .'"';
				$add_classes .= ' wpex-responsive-txt';

			}

			// Responsive data
			if ( $responsive_data = vcex_get_module_responsive_data( $atts ) ) {
				$add_attrs   .= ' '. $responsive_data['data'];
				$add_classes .= ' wpex-rcss '. $responsive_data['uniqid'];
			}

			// Add classes
			if ( $add_classes ) {
				$output = str_replace( 'wpb_text_column', 'wpb_text_column'. $add_classes, $output );
			}

			// Add custom attributes in first div
			if ( $add_attrs ) {
				$pos = strpos( $output, '<div' );
				if ( $pos !== false ) {
					$output = substr_replace( $output, '<div '. trim( $add_attrs ), $pos, strlen( '<div' ) );
				}
			}

			// Return output
			return $output;

		}

	}

}
new VCEX_VC_Column_Text_Config();