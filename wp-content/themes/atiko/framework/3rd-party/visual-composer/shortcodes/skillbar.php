<?php
/**
 * Visual Composer Skillbar
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.0
 */

if ( ! class_exists( 'VCEX_Skillbar_Shortcode' ) ) {

	class VCEX_Skillbar_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.5.0
		 */
		public function __construct() {
			
			// Add shortcode
			add_shortcode( 'vcex_skillbar', array( 'VCEX_Skillbar_Shortcode', 'output' ) );

			// Map to VC
			if ( function_exists( 'vc_lean_map' ) ) {
				vc_lean_map( 'vcex_skillbar', array( 'VCEX_Skillbar_Shortcode', 'map' ) );
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.0
		 */
		public static function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_skillbar.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.0
		 */
		public static function map() {

			// Save re-usable strings
			$s_icon   = __( 'Icon', 'total' );
			$s_design = __( 'Design', 'total' );

			// Return settings array
			return array(
				'name' => __( 'Skill Bar', 'total' ),
				'description' => __( 'Animated skill bar', 'total' ),
				'base' => 'vcex_skillbar',
				'category' => wpex_get_theme_branding(),
				'icon' => 'vcex-skill-bar vcex-icon fa fa-server',
				'params' => array(
					// General
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
					vcex_vc_map_add_css_animation(),
					array(
						'type' => 'vcex_visibility',
						'heading' => __( 'Visibility', 'total' ),
						'param_name' => 'visibility',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Title', 'total' ),
						'param_name' => 'title',
						'admin_label' => true,
						'value' => 'Web Design',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Percentage', 'total' ),
						'param_name' => 'percentage',
						'value' => 70,
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Display % Number', 'total' ),
						'param_name' => 'show_percent',
					),
					// Icon
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Display Icon', 'total' ),
						'param_name' => 'show_icon',
						'group' => $s_icon,
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Icon library', 'total' ),
						'param_name' => 'icon_type',
						'value' => array(
							__( 'Font Awesome', 'total' ) => 'fontawesome',
							__( 'Open Iconic', 'total' ) => 'openiconic',
							__( 'Typicons', 'total' ) => 'typicons',
							__( 'Entypo', 'total' ) => 'entypo',
							__( 'Linecons', 'total' ) => 'linecons',
							__( 'Pixel', 'total' ) => 'pixelicons',
						),
						'group' => $s_icon,
						'dependency' => array( 'element' => 'show_icon', 'value' => 'true' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => $s_icon,
						'param_name' => 'icon',
						'settings' => array(
							'emptyIcon' => true,
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
							'iconsPerPage' => 200,
							'type' => 'openiconic',
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
							'iconsPerPage' => 200,
							'type' => 'typicons',
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'typicons' ),
						'group' => $s_icon,
					),
					array(
						'type' => 'iconpicker',
						'heading' => $s_icon,
						'param_name' => 'icon_entypo',
						'settings' => array(
							'emptyIcon' => false,
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
							'iconsPerPage' => 200,
							'type' => 'linecons',
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
							'iconsPerPage' => 200,
							'type' => 'pixelicons',
							'source' => vcex_pixel_icons(),
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'pixelicons' ),
						'group' => $s_icon,
					),
					// Design
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Container Background', 'total' ),
						'param_name' => 'background',
						'group' => $s_design,
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Container Inset Shadow', 'total' ),
						'param_name' => 'box_shadow',
						'group' => $s_design,
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Skill Bar Color', 'total' ),
						'param_name' => 'color',
						'group' => $s_design,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Container Height', 'total' ),
						'param_name' => 'container_height',
						'group' => $s_design,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Container Left Padding', 'total' ),
						'param_name' => 'container_padding_left',
						'group' => $s_design,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'font_size',
						'group' => $s_design,
					),
				)
			);
		}

	}
}
new VCEX_Skillbar_Shortcode;