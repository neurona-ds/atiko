<?php
/**
 * Visual Composer Icon
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.0
 */

if ( ! class_exists( 'VCEX_Icon_Shortcode' ) ) {

	class VCEX_Icon_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.5.0
		 */
		public function __construct() {
			
			// Add shortcode
			add_shortcode( 'vcex_icon', array( 'VCEX_Icon_Shortcode', 'output' ) );

			// Map to VC
			if ( function_exists( 'vc_lean_map' ) ) {
				vc_lean_map( 'vcex_icon', array( 'VCEX_Icon_Shortcode', 'map' ) );
			}

			// Edif form fields
			if ( is_admin() ) {
				add_filter( 'vc_edit_form_fields_attributes_vcex_icon', array( 'VCEX_Icon_Shortcode', 'edit_form_fields' ) );
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.0
		 */
		public static function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_icon.php' ) );
			return ob_get_clean();
		}

		/**
		 * Parse shortcode attributes and set correct values
		 *
		 * @since 3.5.0
		 */
		public static function edit_form_fields( $atts ) {

			// Convert textfield link to vc_link
			if ( ! empty( $atts['link_url'] ) && false === strpos( $atts['link_url'], 'url:' ) ) {
				$url = 'url:'. $atts['link_url'] .'|';
				$link_title = isset( $atts['link_title'] ) ? 'title:' . $atts['link_title'] .'|' : '|';
				$link_target = ( isset( $atts['link_target'] ) && 'blank' == $atts['link_target'] ) ? 'target:_blank' : '';
				$atts['link_url'] = $url . $link_title . $link_target;
			}

			// Update link target
			if ( isset( $atts['link_target'] ) && 'local' == $atts['link_target'] ) {
				$atts['link_local_scroll'] = 'true';
			}

			// Return $atts
			return $atts;
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.0
		 */
		public static function map() {

			// Reusable strings
			$s_icon = __( 'Icon', 'total' );

			// Return array
			return array(
				'name' => __( 'Font Icon', 'total' ),
				'description' => __( 'Font Icon from various libraries', 'total' ),
				'base' => 'vcex_icon',
				'icon' => 'vcex-font-icon vcex-icon fa fa-bolt',
				'category' => wpex_get_theme_branding(),
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
						'param_name' => 'el_class',
						'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total' ),
					),
					vcex_vc_map_add_css_animation(),
					array(
						'type' => 'vcex_hover_animations',
						'heading' => __( 'Hover Animation', 'total'),
						'param_name' => 'hover_animation',
					),
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
							__( 'Mono Social', 'total' ) => 'monosocial',
						),
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
						'type' => 'iconpicker',
						'heading' => $s_icon,
						'param_name' => 'icon_pixelicons',
						'std' => '',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'pixelicons',
							'source' => vcex_pixel_icons(),
							'iconsPerPage' => 4000,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'pixelicons' ),
						'group' => $s_icon,
					),
					array(
						'type' => 'iconpicker',
						'heading' => $s_icon,
						'param_name' => 'icon_monosocial',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'monosocial',
							'iconsPerPage' => 4000,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'monosocial' ),
						'group' => $s_icon,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Icon Font Alternative Classes', 'total' ),
						'param_name' => 'icon_alternative_classes',
						'group' => $s_icon,
					),

					// Design
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'color',
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color: Hover', 'total' ),
						'param_name' => 'color_hover',
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background', 'total' ),
						'param_name' => 'background',
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background: Hover', 'total' ),
						'param_name' => 'background_hover',
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Size', 'total' ),
						'param_name' => 'size',
						'std' => 'normal',
						'value' => array(
							__( 'Inherit', 'total' ) => 'inherit',
							__( 'Extra Large', 'total' ) => 'xlarge',
							__( 'Large', 'total' ) => 'large',
							__( 'Normal', 'total' ) => 'normal',
							__( 'Small', 'total') => 'small',
							__( 'Tiny', 'total' ) => 'tiny',
						),
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'vcex_text_alignments',
						'heading' => __( 'Align', 'total' ),
						'param_name' => 'float',
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Custom Size', 'total' ),
						'param_name' => 'custom_size',
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Border Radius', 'total' ),
						'param_name' => 'border_radius',
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Border', 'total' ),
						'param_name' => 'border',
						'description' => __( 'Please use the shorthand format: width style color. Enter 0px or "none" to disable border.', 'total' ),
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Width', 'total' ),
						'param_name' => 'width',
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Height', 'total' ),
						'param_name' => 'height',
						'group' => __( 'Design', 'total' ),
					),
					// Link
					array(
						'type' => 'vc_link',
						'heading' => __( 'Link', 'total' ),
						'param_name' => 'link_url',
						'group' => __( 'Link', 'total' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Link: Local Scroll', 'total' ),
						'param_name' => 'link_local_scroll',
						'value' => array(
							__( 'False', 'total' ) => 'false',
							__( 'True', 'total' ) => 'true',
						),
						'group' => __( 'Link', 'total' ),
					),
				)
			);
		}

	}
}
new VCEX_Icon_Shortcode;