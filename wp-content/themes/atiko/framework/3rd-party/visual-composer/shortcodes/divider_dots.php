<?php
/**
 * Visual Composer Divider: Dots
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.0
 */

if ( ! class_exists( 'VCEX_Divider_Dots_Shortcode' ) ) {

	class VCEX_Divider_Dots_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.5.0
		 */
		public function __construct() {
			
			// Add shortcode
			add_shortcode( 'vcex_divider_dots', array( 'VCEX_Divider_Dots_Shortcode', 'output' ) );

			// Map to VC
			if ( function_exists( 'vc_lean_map' ) ) {
				vc_lean_map( 'vcex_divider_dots', array( 'VCEX_Divider_Dots_Shortcode', 'map' ) );
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.0
		 */
		public static function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_divider_dots.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.0
		 */
		public static function map() {
			return array(
				'name' => __( 'Divider Dots', 'total' ),
				'description' => __( 'Dot Separator', 'total' ),
				'base' => 'vcex_divider_dots',
				'icon' => 'vcex-dots vcex-icon fa fa-ellipsis-h',
				'category' => wpex_get_theme_branding(),
				'params' => array(
					// General
					array(
						'type' => 'vcex_visibility',
						'heading' => __( 'Visibility', 'total' ),
						'param_name' => 'visibility',
					),
					vcex_vc_map_add_css_animation(),
					array(
						'type' => 'textfield',
						'heading' => __( 'Extra class name', 'total' ),
						'param_name' => 'el_class',
						'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total' ),
					),
					array(
						'type' => 'vcex_text_alignments',
						'heading' => __( 'Align', 'total' ),
						'param_name' => 'align',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Count', 'total' ),
						'param_name' => 'count',
						'value' => '3',
						'admin_label' => true,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Size', 'total' ),
						'param_name' => 'size',
						'description' => __( 'Default', 'total' ) . ': 5px',
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'color',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Margin Top', 'total' ),
						'param_name' => 'margin_top',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Margin Bottom', 'total' ),
						'param_name' => 'margin_bottom',
					),
				),
			);
		}

	}
}
new VCEX_Divider_Dots_Shortcode;