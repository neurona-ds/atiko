<?php
/**
 * Visual Composer Bullets
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.0
 */

if ( ! class_exists( 'VCEX_Bullets_Shortcode' ) ) {

	class VCEX_Bullets_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.5.0
		 */
		public function __construct() {
			
			// Add shortcode
			add_shortcode( 'vcex_bullets', array( 'VCEX_Bullets_Shortcode', 'output' ) );

			// Map to VC
			if ( function_exists( 'vc_lean_map' ) ) {
				vc_lean_map( 'vcex_bullets', array( 'VCEX_Bullets_Shortcode', 'map' ) );
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.0
		 */
		public static function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_bullets.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.0
		 */
		public static function map() {

			// Reusable strings
			$s_icon       = __( 'Icon', 'total' );
			$s_typography = __( 'Typography', 'total' );

			// Return array
			return array(
				'name' => __( 'Bullets', 'total' ),
				'description' => __( 'Styled bulleted lists', 'total' ),
				'base' => 'vcex_bullets',
				'category' => wpex_get_theme_branding(),
				'icon' => 'vcex-bullets vcex-icon fa fa-dot-circle-o',
				'params' => array(
					array(
						'type' => 'textfield',
						'heading' => __( 'Unique Id', 'total' ),
						'param_name' => 'unique_id',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Extra class name', 'total' ),
						'param_name' => 'classes',
						'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total' ),
					),
					array(
						'type' => 'vcex_visibility',
						'heading' => __( 'Visibility', 'total' ),
						'param_name' => 'visibility',
					),
					vcex_vc_map_add_css_animation(),
					array(
						'type' => 'textarea_html',
						'heading' => __( 'Insert Unordered List', 'total' ),
						'param_name' => 'content',
						'value' => '<ul><li>List 1</li><li>List 2</li><li>List 3</li><li>List 4</li></ul>',
					),

					// Icon
					array(
						'type' => 'dropdown',
						'heading' => __( 'Style', 'total' ),
						'param_name' => 'style',
						'admin_label' => true,
						'value' => array(
							__( 'Check', 'total') => 'check',
							__( 'Blue', 'total' ) => 'blue',
							__( 'Gray', 'total' ) => 'gray',
							__( 'Purple', 'total' ) => 'purple',
							__( 'Red', 'total' ) => 'red',
						),
						'group' => $s_icon,
					),
					array(
						'type' => 'vcex_icon_font_family',
						'heading' => __( 'Custom Icon', 'total' ),
						'param_name' => 'icon_type',
						'description' => __( 'Select icon library.', 'total' ),
						'group' => $s_icon,
					),
					array(
						'type' => 'iconpicker',
						'heading' => $s_icon,
						'param_name' => 'icon',
						'admin_label' => true,
						'value' => 'fa fa-info-circle',
						'settings' => array(
							'emptyIcon' => true,
							'iconsPerPage' => 4000,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'fontawesome' ),
						'group' => $s_icon,
					),
					array(
						'type' => 'iconpicker',
						'heading' => $s_icon,
						'param_name' => 'icon_openiconic',
						'std' => '',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'openiconic',
							'iconsPerPage' => 4000,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'openiconic' ),
						'group' => $s_icon,
					),
					array(
						'type' => 'iconpicker',
						'heading' => $s_icon,
						'param_name' => 'icon_typicons',
						'std' => '',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'typicons',
							'iconsPerPage' => 4000,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'typicons' ),
						'group' => $s_icon,
					),
					array(
						'type' => 'iconpicker',
						'heading' => $s_icon,
						'param_name' => 'icon_entypo',
						'std' => '',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'entypo',
							'iconsPerPage' => 4000,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'entypo' ),
						'group' => $s_icon,
					),
					array(
						'type' => 'iconpicker',
						'heading' => $s_icon,
						'param_name' => 'icon_linecons',
						'std' => '',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'linecons',
							'iconsPerPage' => 4000,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'linecons' ),
						'group' => $s_icon,
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Icon Color', 'total' ),
						'param_name' => 'icon_color',
						'dependency' => array( 'element' => 'icon_type', 'not_empty' => true ),
						'group' => $s_icon,
					),

					// Typography
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'color',
						'group' => $s_typography,
					),
					array(
						'type' => 'vcex_responsive_sizes',
						'target' => 'font-size',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'font_size',
						'group' => $s_typography,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Line Height', 'total' ),
						'param_name' => 'line_height',
						'group' => $s_typography,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Letter Spacing', 'total' ),
						'param_name' => 'letter_spacing',
						'group' => $s_typography,
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'font_weight',
						'std' => '',
						'group' => $s_typography,
					),
					array(
						'type' => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'font_family',
						'group' => $s_typography,
					),
				)
			);
		}

	}
}
new VCEX_Bullets_Shortcode;