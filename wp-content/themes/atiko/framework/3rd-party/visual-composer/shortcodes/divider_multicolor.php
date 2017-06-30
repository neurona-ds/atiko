<?php
/**
 * Visual Composer Multi-Color divider
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.0
 */

if ( ! class_exists( 'VCEX_Multi_Color_Divider_Shortcode' ) ) {

	class VCEX_Multi_Color_Divider_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 4.0
		 */
		public function __construct() {
			
			// Add main shortcode
			add_shortcode( 'vcex_divider_multicolor', array( 'VCEX_Multi_Color_Divider_Shortcode', 'output' ) );

			// Map to VC
			if ( function_exists( 'vc_lean_map' ) ) {
				vc_lean_map( 'vcex_divider_multicolor', array( 'VCEX_Multi_Color_Divider_Shortcode', 'map' ) );
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 4.0
		 */
		public static function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_divider_multicolor.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 4.0
		 */
		public static function map() {
			return array(
				'name' => __( 'Divider - Multicolor', 'total' ),
				'description' => __( 'A multicolor divider.', 'total' ),
				'base' => 'vcex_divider_multicolor',
				'category' => wpex_get_theme_branding(),
				'icon' => 'vcex-divider-multicolor vcex-icon fa fa-ellipsis-h',
				'params' => array(
					array(
						'type' => 'textfield',
						'heading' => __( 'Extra class name', 'total' ),
						'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total' ),
						'param_name' => 'el_class',
					),
					array(
						'type' => 'vcex_visibility',
						'heading' => __( 'Visibility', 'total' ),
						'param_name' => 'visibility',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Width', 'total' ),
						'param_name' => 'width',
						'value' => '100%',
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Align', 'total' ),
						'param_name' => 'align',
						'value' => array(
							__( 'Center', 'total' ) => 'center',
							__( 'Right', 'total' ) => 'right',
							__( 'Left', 'total' ) => 'left',
						),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Margin Bottom', 'total' ),
						'param_name' => 'margin_bottom',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Height', 'total' ),
						'param_name' => 'height',
						'value' => '8px',
					),
					array(
						'type' => 'param_group',
						'param_name' => 'colors',
						'value' => urlencode( json_encode( array(
							array(
								'value' => '#301961',
							),
							array(
								'value' => '#452586',
							),
							array(
								'value' => '#301961',
							),
							array(
								'value' => '#5f3aae',
							),
							array(
								'value' => '#01c1a8',
							),
							array(
								'value' => '#11e2c5',
							),
							array(
								'value' => '#6ffceb',
							),
							array(
								'value' => '#b0fbff',
							),
						) ) ),
						'params' => array(
							array(
								'type' => 'colorpicker',
								'heading' => __( 'Color', 'total' ),
								'param_name' => 'value',
								'admin_label' => true,
							),
						),
					),
				)
			);
		}

	}
}
new VCEX_Multi_Color_Divider_Shortcode;