<?php
/**
 * Visual Composer Row Configuration
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start Class
if ( ! class_exists( 'VCEX_VC_Column_Config' ) ) {
	
	class VCEX_VC_Column_Config {

		/**
		 * Main constructor
		 *
		 * @since 2.0.0
		 */
		public function __construct() {

			// Add new parameters
			add_action( 'wpex_vc_add_params', array( 'VCEX_VC_Column_Config', 'add_params' ) );

			// Tweak fields on edit
			add_filter( 'vc_edit_form_fields_attributes_vc_column', array( 'VCEX_VC_Column_Config', 'edit_form_fields') );
			add_filter( 'vc_edit_form_fields_attributes_vc_column_inner', array( 'VCEX_VC_Column_Config', 'edit_form_fields') );

			// Alter shortcode classes
			add_filter( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, array( 'VCEX_VC_Column_Config', 'shortcode_classes' ), 9999, 3 );

			// Alter shortcode output
			add_filter( 'vc_shortcode_output', array( 'VCEX_VC_Column_Config', 'custom_output' ), 10, 3 );
		}

		/**
		 * Adds new params for the VC Rows
		 *
		 * @since 2.0.0
		 */
		public static function add_params( $params ) {

			/*-----------------------------------------------------------------------------------*/
			/*  - Columns
			/*-----------------------------------------------------------------------------------*/

			// Array of params to add
			$column_params = array();

			$column_params[] = array(
				'type'       => 'vcex_visibility',
				'heading'    => __( 'Visibility', 'total' ),
				'param_name' => 'visibility',
				'std'        => '',
				'weight'     => 99,
			);

			$column_params[] = array(
				'type'       => 'vcex_visibility',
				'heading'    => __( 'Visibility', 'total' ),
				'param_name' => 'visibility',
				'std'        => '',
			);

			$column_params[] = array(
				'type'       => 'dropdown',
				'heading'    => __( 'Typography Style', 'total' ),
				'param_name' => 'typography_style',
				'value'      => array_flip( wpex_typography_styles() ),
			);

			$column_params[] = array(
				'type'        => 'textfield',
				'heading'     => __( 'Minimum Height', 'total' ),
				'param_name'  => 'min_height',
				'description' => __( 'You can enter a minimum height for this row.', 'total' ),
			);

			// Hidden fields = Deprecated params, these should be removed on save
			$deprecated_column_params = array(
				'id',
				'style',
				'typo_style',
				'bg_color',
				'bg_image',
				'bg_style',
				'border_style',
				'border_color',
				'border_width',
				'margin_top',
				'margin_bottom',
				'margin_left',
				'padding_top',
				'padding_bottom',
				'padding_left',
				'padding_right',
				'drop_shadow',
			);

			foreach ( $deprecated_column_params as $param ) {

				$column_params[] = array(
					'type'       => 'hidden',
					'param_name' => $param,
				);

			}

			$params['vc_column'] = $column_params;

			/*-----------------------------------------------------------------------------------*/
			/*  - Inner Columns
			/*-----------------------------------------------------------------------------------*/
			$inner_column_params = array();

			// Hidden fields = Deprecated params, these should be removed on save
			$deprecated_params = array(
				'id',
				'style',
				'typo_style',
				'bg_color',
				'bg_image',
				'bg_style',
				'border_style',
				'border_color',
				'border_width',
				'margin_top',
				'margin_bottom',
				'margin_left',
				'padding_top',
				'padding_bottom',
				'padding_left',
				'padding_right',
			);
			
			foreach ( $deprecated_params as $param ) {

				$inner_column_params[] = array(
					'type'       => 'hidden',
					'param_name' => $param,
				);

			}

			$params['vc_column_inner'] = $inner_column_params;

			return $params;

		}

		/**
		 * Tweaks row attributes on edit
		 *
		 * @since 3.0.0
		 */
		public static function edit_form_fields( $atts ) {

			// Parse ID
			if ( empty( $atts['el_id'] ) && ! empty( $atts['id'] ) ) {
				$atts['el_id'] = $atts['id'];
				unset( $atts['id'] );
			}

			// Parse $atts['typo_style'] into $atts['typography_style']
			if ( empty( $atts['typography_style'] ) && ! empty( $atts['typo_style'] ) ) {
				if ( in_array( $atts['typo_style'], array_flip( wpex_typography_styles() ) ) ) {
					$atts['typography_style'] = $atts['typo_style'];
					unset( $atts['typo_style'] );
				}
			}

			// Remove old style param and add it to the classes field
			$style = isset( $atts['style'] ) ? $atts['style'] : '';
			if ( $style && ( 'bordered' == $style || 'boxed' == $style ) ) {
				if ( ! empty( $atts['el_class'] ) ) {
					$atts['el_class'] .= ' '. $style .'-column';
				} else {
					$atts['el_class'] = $style .'-column';
				}
				unset( $atts['style'] );
			}

			// Parse css
			if ( empty( $atts['css'] ) ) {

				// Convert deprecated fields to css field
				$atts['css'] = vcex_parse_deprecated_row_css( $atts );

				// Unset deprecated vars
				unset( $atts['bg_image'] );
				unset( $atts['bg_color'] );

				unset( $atts['margin_top'] );
				unset( $atts['margin_bottom'] );
				unset( $atts['margin_right'] );
				unset( $atts['margin_left'] );

				unset( $atts['padding_top'] );
				unset( $atts['padding_bottom'] );
				unset( $atts['padding_right'] );
				unset( $atts['padding_left'] );

				unset( $atts['border_width'] );
				unset( $atts['border_style'] );
				unset( $atts['border_color'] );

			}

			// Return $atts
			return $atts;

		}

		/**
		 * Tweak shortcode classes
		 *
		 * @since 4.0
		 */
		public static function shortcode_classes( $class_string, $tag, $atts ) {

			// Edits only for columns
			if ( 'vc_column' != $tag && 'vc_column_inner' != $tag ) {
				return $class_string;
			}
			// Remove colorfill class which VC adds extra margins to
			$class_string = str_replace( 'vc_col-has-fill', 'wpex-vc_col-has-fill', $class_string );

			// Visibility
			if ( ! empty( $atts['visibility'] ) ) {
				$class_string .= ' '. $atts['visibility'];
			}

			// Style => deprecated fallback
			if ( ! empty( $atts['style'] ) && 'default' != $atts['style'] ) {
				$class_string .= ' '. $atts['style'] .'-column';
			}

			// Typography Style => deprecated fallback
			if ( ! empty( $atts['typo_style'] ) && empty( $atts['typography_style'] ) ) {
				$class_string .= ' '. wpex_typography_style_class( $atts['typo_style'] );
			} elseif ( empty( $atts['typo_style'] ) && ! empty( $atts['typography_style'] ) ) {
				$class_string .= ' '. wpex_typography_style_class( $atts['typography_style'] );
			}

			// Return class string
			return $class_string;

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

			// Only tweaks neeed for columns
			if ( 'vc_column' != $obj->settings( 'base' ) ) {
				return $output;
			}

			// Generate inline CSS
			$inline_style = '';

			// Min Height
			if ( ! empty( $atts['min_height'] ) ) {
				$inline_style .= 'min-height:' . esc_attr( $atts['min_height'] ) . ';';
			}

			// Inline css styles => Fallback For OLD Total Params
			if ( empty( $atts['css'] ) && function_exists( 'vcex_parse_deprecated_row_css' ) ) {
				$inline_style .= vcex_parse_deprecated_row_css( $atts, 'inline_css' );
			}

			// Add inline style to wrapper attributes
			if ( $inline_style ) {
				$inline_style = 'style="'. $inline_style .'"';
				$output = str_replace( 'class="vc_column-inner', $inline_style .' class="vc_column-inner', $output );
			}

			// Add output
			return $output;

		}

	}

}
new VCEX_VC_Column_Config();