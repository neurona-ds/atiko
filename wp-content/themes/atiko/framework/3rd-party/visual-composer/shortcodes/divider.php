<?php
/**
 * Visual Composer Divider
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.0
 */

if ( ! class_exists( 'VCEX_Divider_Shortcode' ) ) {

	class VCEX_Divider_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.5.0
		 */
		public function __construct() {
			
			// Add shortcode
			add_shortcode( 'vcex_divider', array( 'VCEX_Divider_Shortcode', 'output' ) );

			// Map to VC
			if ( function_exists( 'vc_lean_map' ) ) {
				vc_lean_map( 'vcex_divider', array( 'VCEX_Divider_Shortcode', 'map' ) );
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.0
		 */
		public static function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_divider.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.0
		 */
		public static function map() {

			// Define re-usable strings
			$s_icon   = __( 'Icon', 'total' );
			$s_design = __( 'Design', 'total' );

			// Return settings array
			return array(
				'name' => __( 'Divider', 'total' ),
				'description' => __( 'Line Separator', 'total' ),
				'base' => 'vcex_divider',
				'icon' => 'vcex-divider vcex-icon fa fa-minus',
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
					// Design
					array(
						'type' => 'dropdown',
						'admin_label' => true,
						'heading' => __( 'Style', 'total' ),
						'param_name' => 'style',
						'value' => array(
							__( 'Solid', 'total' ) => 'solid',
							__( 'Dashed', 'total' ) => 'dashed',
							__( 'Double', 'total' ) => 'double',
							__( 'Dotted Line', 'total' ) => 'dotted-line',
							__( 'Dotted', 'total' ) => 'dotted',
						),
						'group' => $s_design,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Width', 'total' ),
						'param_name' => 'width',
						'description' => __( 'Enter a pixel or percentage value.', 'total' ),
						'group' => $s_design,
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Align', 'total' ),
						'param_name' => 'align',
						'group' => $s_design,
						'value' => array(
							__( 'Default', 'total' ) => '',
							__( 'Center', 'total' ) => 'center',
							__( 'Right', 'total' ) => 'right',
							__( 'Left', 'total' ) => 'left',
						),
						'dependency' => array( 'element' => 'width', 'not_empty' => true ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Height', 'total' ),
						'param_name' => 'height',
						'dependency' => array(
							'element' => 'style',
							'value' => array( 'solid', 'dashed', 'double', 'dotted-line' ),
						),
						'group' => $s_design,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Height', 'total' ),
						'param_name' => 'dotted_height',
						'dependency' => array(
							'element' => 'style',
							'value' => 'dotted',
						),
						'group' => $s_design,
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'color',
						'value' => '',
						'dependency' => array(
							'element' => 'style',
							'value' => array( 'solid', 'dashed', 'double', 'dotted-line' ),
						),
						'group' => $s_design,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Margin Top', 'total' ),
						'param_name' => 'margin_top',
						'description' => __( 'Please enter a px value.', 'total' ),
						'group' => $s_design,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Margin Bottom', 'total' ),
						'description' => __( 'Please enter a px value.', 'total' ),
						'param_name' => 'margin_bottom',
						'group' => $s_design,
					),
					// Icon
					array(
						'type' => 'dropdown',
						'heading' => __( 'Icon library', 'total' ),
						'param_name' => 'icon_type',
						'description' => __( 'Select icon library.', 'total' ),
						'value' => array(
							__( 'Font Awesome', 'total' ) => 'fontawesome',
							__( 'Open Iconic', 'total' ) => 'openiconic',
							__( 'Typicons', 'total' ) => 'typicons',
							__( 'Entypo', 'total' ) => 'entypo',
							__( 'Linecons', 'total' ) => 'linecons',
							__( 'Pixel', 'total' ) => 'pixelicons',
						),
						'group' => $s_icon,
					),
					array(
						'type' => 'iconpicker',
						'heading' => $s_icon,
						'param_name' => 'icon',
						'std' => '',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'fontawesome',
							'iconsPerPage' => 200,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'fontawesome' ),
						'group' => $s_icon,
					),
					array(
						'type' => 'iconpicker',
						'heading' => $s_icon,
						'param_name' => 'icon_openiconic',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'openiconic',
							'iconsPerPage' => 200,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'openiconic' ),
						'group' => $s_icon,
					),
					array(
						'type' => 'iconpicker',
						'heading' => $s_icon,
						'param_name' => 'icon_typicons',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'typicons',
							'iconsPerPage' => 200,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'typicons' ),
						'group' => $s_icon,
					),
					array(
						'type' => 'iconpicker',
						'heading' => $s_icon,
						'param_name' => 'icon_entypo',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'entypo',
							'iconsPerPage' => 300,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'entypo' ),
						'group' => $s_icon,
					),
					array(
						'type' => 'iconpicker',
						'heading' => $s_icon,
						'param_name' => 'icon_linecons',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'linecons',
							'iconsPerPage' => 200,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'linecons' ),
						'group' => $s_icon,
					),
					array(
						'type' => 'iconpicker',
						'heading' => $s_icon,
						'param_name' => 'icon_pixelicons',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'pixelicons',
							'source' => vcex_pixel_icons(),
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'pixelicons' ),
						'group' => $s_icon,
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Icon Color', 'total' ),
						'param_name' => 'icon_color',
						'group' => $s_icon,
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Icon Background', 'total' ),
						'param_name' => 'icon_bg',
						'group' => $s_icon,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Icon Size', 'total' ),
						'param_name' => 'icon_size',
						'description' => __( 'You can use em or px values, but you must define them.', 'total' ),
						'group' => $s_icon,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Icon Height', 'total' ),
						'param_name' => 'icon_height',
						'group' => $s_icon,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Icon Width', 'total' ),
						'param_name' => 'icon_width',
						'group' => $s_icon,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Icon Border Radius', 'total' ),
						'param_name' => 'icon_border_radius',
						'group' => $s_icon,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Icon Padding', 'total' ),
						'param_name' => 'icon_padding',
						'description' => __( 'Please use the following format: top right bottom left.', 'total' ),
						'group' => $s_icon,
					),
				)
			);
		}

	}
}
new VCEX_Divider_Shortcode;