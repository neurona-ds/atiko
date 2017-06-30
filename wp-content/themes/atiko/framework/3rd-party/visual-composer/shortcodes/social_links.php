<?php
/**
 * Visual Composer Social Links
 *
 * @package Total WordPress Theme
 * @subpackage VC Templates
 * @version 4.0
 */

if ( ! class_exists( 'VCEX_Social_Links_Shortcode' ) ) {

	class VCEX_Social_Links_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.5.0
		 */
		public function __construct() {
			
			// Add shortcode
			add_shortcode( 'vcex_social_links', array( 'VCEX_Social_Links_Shortcode', 'output' ) );

			// Map to VC
			if ( function_exists( 'vc_lean_map' ) ) {
				vc_lean_map( 'vcex_social_links', array( 'VCEX_Social_Links_Shortcode', 'map' ) );
			}

			// Admin filters
			if ( is_admin() ) {
				add_filter( 'vc_edit_form_fields_attributes_vcex_social_links', array( 'VCEX_Social_Links_Shortcode', 'edit_form_fields' ) );
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.0
		 */
		public static function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_social_links.php' ) );
			return ob_get_clean();
		}

		/**
		 * Parse attributes on edit
		 *
		 * @since 3.5.0
		 */
		public static function edit_form_fields( $atts ) {

			// Get array of social links to loop through
			$social_profiles = vcex_social_links_profiles();

			// Social links list required
			if ( empty( $social_profiles ) )  {
				return $atts;
			}

			// Loop through old options and move to new ones + delete old settings?
			if ( empty( $atts['social_links'] ) ) {
				$social_links = array();
				foreach ( $social_profiles  as $key => $val ) {
					if ( ! empty( $atts[$key] ) ) {
						$social_links[] = array(
							'site' => $key,
							'link' => $atts[$key],
						);
					}
					unset( $atts[$key] );
				}
				if ( $social_links ) {
					$atts['social_links'] = urlencode( json_encode( $social_links ) );
				}
			}

			// Return attributes
			return $atts;
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.0
		 */
		public static function map() {
			// Get array of social links to loop through
			$social_links = vcex_social_links_profiles();
			// Social links list required
			if ( empty( $social_links ) )  {
				return;
			}
			// Create dropdown of social sites
			$social_link_select = array();
			foreach ( $social_links as $key => $val ) {
				$social_link_select[$val['label']] = $key;
			}
			// Return array
			return array(
				'name' => __( 'Social Links', 'total' ),
				'description' => __( 'Display social links using icon fonts', 'total' ),
				'base' => 'vcex_social_links',
				'category' => wpex_get_theme_branding(),
				'icon' => 'vcex-social-links vcex-icon fa fa-user-plus',
				'params' => array(
					array(
						'type' => 'textfield',
						'heading' => __( 'Unique Id', 'total' ),
						'param_name' => 'unique_id',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Extra class name', 'total' ),
						'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total' ),
						'param_name' => 'classes',
					),
					array(
						'type' => 'vcex_visibility',
						'heading' => __( 'Visibility', 'total' ),
						'param_name' => 'visibility',
					),
					vcex_vc_map_add_css_animation(),
					array(
						'type' => 'vcex_hover_animations',
						'heading' => __( 'Hover Animation', 'total'),
						'param_name' => 'hover_animation',
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Link Target', 'total'),
						'param_name' => 'link_target',
						'value' => array(
							__( 'Self', 'total' ) => '',
							__( 'Blank', 'total' ) => 'blank',
						),
					),
					// Social Links
					array(
						'type' => 'param_group',
						'param_name' => 'social_links',
						'group' => __( 'Profiles', 'total' ),
						'value' => urlencode( json_encode( array( ) ) ),
						'params' => array(
							array(
								'type' => 'dropdown',
								'heading' => __( 'Site', 'total' ),
								'param_name' => 'site',
								'admin_label' => true,
								'value' => $social_link_select,
							),
							array(
								'type' => 'textfield',
								'heading' => __( 'Link', 'total' ),
								'param_name' => 'link',
							),
						),
					),
					// Style
					array(
						'type' => 'vcex_social_button_styles',
						'heading' => __( 'Style', 'total'),
						'param_name' => 'style',
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'vcex_text_alignments',
						'heading' => __( 'Align', 'total' ),
						'param_name' => 'align',
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'vcex_responsive_sizes',
						'target' => 'font-size',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'size',
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
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'color',
						'group' => __( 'Design', 'total' ),
						'dependency' => array( 'element' => 'style', 'value' => array( 'none', '' ) ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Hover Background', 'total' ),
						'param_name' => 'hover_bg',
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Hover Color', 'total' ),
						'param_name' => 'hover_color',
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'css_editor',
						'heading' => __( 'CSS', 'total' ),
						'param_name' => 'css',
						'group' => __( 'CSS', 'total' ),
					),
					array(
						'type' => 'hidden',
						'param_name' => 'border_radius',
					),
				),
			);
		}

	}
}
new VCEX_Social_Links_Shortcode;