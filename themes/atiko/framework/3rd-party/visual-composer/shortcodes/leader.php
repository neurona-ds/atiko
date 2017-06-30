<?php
/**
 * Visual Composer Leader
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 3.5.3
 */

if ( ! class_exists( 'VCEX_Leader_Shortcode' ) ) {

	class VCEX_Leader_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.5.0
		 */
		public function __construct() {
			
			// Add shortcode
			add_shortcode( 'vcex_leader', array( 'VCEX_Leader_Shortcode', 'output' ) );

			// Map to VC
			if ( function_exists( 'vc_lean_map' ) ) {
				vc_lean_map( 'vcex_leader', array( 'VCEX_Leader_Shortcode', 'map' ) );
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.0
		 */
		public static function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_leader.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.0
		 */
		public static function map() {

			$s_design = __( 'Design', 'total' );

			return array(
				'name' => __( 'Leader (Menu Items)', 'total' ),
				'description' => __( 'CSS dot or line leader (menu item)', 'total' ),
				'base' => 'vcex_leader',
				'icon' => 'vcex-leader vcex-icon fa fa-long-arrow-right',
				'category' => wpex_get_theme_branding(),
				'params' => array(
					array(
						'type' => 'textfield',
						'admin_label' => true,
						'heading' => __( 'Extra class name', 'total' ),
						'param_name' => 'el_class',
						'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total' ),
					),
					vcex_vc_map_add_css_animation(),
					array(
						'type' => 'param_group',
						'param_name' => 'leaders',
						'value' => urlencode( json_encode( array(
							array(
								'label' => __( 'One', 'total' ),
								'value' => '$10',
							),
							array(
								'label' => __( 'Two', 'total' ),
								'value' => '$20',
							),
						) ) ),
						'params' => array(
							array(
								'type' => 'textfield',
								'heading' => __( 'Label', 'total' ),
								'param_name' => 'label',
								'admin_label' => true,
							),
							array(
								'type' => 'textfield',
								'heading' => __( 'Value', 'total' ),
								'param_name' => 'value',
								'admin_label' => true,
							),
						),
					),

					// General
					array(
						'type' => 'dropdown',
						'heading' => __( 'Style', 'total' ),
						'param_name' => 'style',
						'value' => array(
							__( 'Dots', 'total' ) => 'dots',
							__( 'Dashes', 'total' ) => 'dashes',
						),
						'group' => $s_design,
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Responsive', 'total' ),
						'param_name' => 'responsive',
						'group' => $s_design,
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'color',
						'group' => $s_design,
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background', 'total' ),
						'param_name' => 'background',
						'group' => $s_design,
					),
					array(
						'type' => 'vcex_responsive_sizes',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'font_size',
						'group' => $s_design,
						'target' => 'font-size',
					),
					// Label
					array(
						'type' => 'colorpicker',
						'param_name' => 'label_color',
						'heading' => __( 'Color', 'total' ),
						'group' => __( 'Label', 'total' ),
					),
					array(
						'type' => 'vcex_font_weight',
						'param_name' => 'label_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'group' => __( 'Label', 'total' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Font Style', 'total' ),
						'param_name' => 'label_font_style',
						'value' => array(
							__( 'Normal', 'total' ) => '',
							__( 'Italic', 'total' ) => 'italic',
						),
						'group' => __( 'Label', 'total' ),
					),
					array(
						'type'  => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'label_font_family',
						'group' => __( 'Label', 'total' ),
					),
					// Color
					array(
						'type' => 'colorpicker',
						'param_name' => 'value_color',
						'heading' => __( 'Color', 'total' ),
						'group' => __( 'Value', 'total' ),
					),
					array(
						'type' => 'vcex_font_weight',
						'param_name' => 'value_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'group' => __( 'Value', 'total' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Font Style', 'total' ),
						'param_name' => 'value_font_style',
						'value' => array(
							__( 'Normal', 'total' ) => '',
							__( 'Italic', 'total' ) => 'italic',
						),
						'group' => __( 'Value', 'total' ),
					),
					array(
						'type'  => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'value_font_family',
						'group' => __( 'Value', 'total' ),
					),
				)
			);
		}

	}

}
new VCEX_Leader_Shortcode;