<?php
/**
 * Visual Composer Callout
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 3.5.0
 */

if ( ! class_exists( 'VCEX_Callout_Shortcode' ) ) {

	class VCEX_Callout_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.5.0
		 */
		public function __construct() {
			
			// Add shortcode
			add_shortcode( 'vcex_callout', array( 'VCEX_Callout_Shortcode', 'output' ) );

			// Map to VC
			if ( function_exists( 'vc_lean_map' ) ) {
				vc_lean_map( 'vcex_callout', array( 'VCEX_Callout_Shortcode', 'map' ) );
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.0
		 */
		public static function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_callout.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.0
		 */
		public static function map() {
			return array(
				'name' => __( 'Callout', 'total' ),
				'description' => __( 'Call to action section with or without button', 'total' ),
				'base' => 'vcex_callout',
				'icon' => 'vcex-callout vcex-icon fa fa-bullhorn',
				'deprecated' => '3.0.0',
				'category' => wpex_get_theme_branding(),
				'params' => array(
					array(
						'type' => 'textfield',
						'admin_label' => true,
						'heading' => __( 'Unique Id', 'total' ),
						'description' => __( 'Give your main element a unique ID.', 'total' ),
						'param_name' => 'unique_id',
					),
					array(
						'type' => 'textfield',
						'admin_label' => true,
						'heading' => __( 'Classes', 'total' ),
						'description' => __( 'Add additonal classes to the main element.', 'total' ),
						'param_name' => 'classes',
					),
					array(
						'type' => 'vcex_visibility',
						'heading' => __( 'Visibility', 'total' ),
						'param_name' => 'visibility',
					),
					vcex_vc_map_add_css_animation(),
					// Content
					array(
						'type' => 'textarea_html',
						'holder' => 'div',
						'class' => 'vcex-callout',
						'heading' => __( 'Callout Content', 'total' ),
						'param_name' => 'content',
						'value' => __( 'Enter your content here.', 'total' ),
						'group' => __( 'Content', 'total' ),
					),
					// Button
					array(
						'type' => 'textfield',
						'heading' => __( 'URL', 'total' ),
						'param_name' => 'button_url',
						'group' => __( 'Button', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Text', 'total' ),
						'param_name' => 'button_text',
						'group' => __( 'Button', 'total' ),
					),
					array(
						'type' => 'vcex_button_styles',
						'heading' => __( 'Button Style', 'total' ),
						'param_name' => 'button_style',
						'group' => __( 'Button', 'total' ),
					),
					array(
						'type' => 'vcex_button_colors',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'button_color',
						'group' => __( 'Button', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Border Radius', 'total' ),
						'param_name' => 'button_border_radius',
						'description' => __( 'Please enter a px value.', 'total' ),
						'group' => __( 'Button', 'total' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Link Target', 'total' ),
						'param_name' => 'button_target',
						'value' => array(
							__( 'Self', 'total' ) => '',
							__( 'Blank', 'total' ) => 'blank',
						),
						'group' => __( 'Button', 'total' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Rel', 'total' ),
						'param_name' => 'button_rel',
						'value' => array(
							__( 'None', 'total' ) => 'none',
							__( 'Nofollow', 'total' ) => 'nofollow',
						),
						'group' => __( 'Button', 'total' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Icon Left', 'total' ),
						'param_name' => 'button_icon_left',
						'value' => wpex_get_awesome_icons(),
						'group' => __( 'Button', 'total' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Icon Right', 'total' ),
						'param_name' => 'button_icon_right',
						'value' => wpex_get_awesome_icons(),
						'group' => __( 'Button', 'total' ),
					),
					array(
						'type' => 'css_editor',
						'heading' => __( 'CSS', 'total' ),
						'param_name' => 'css',
						'group' => __( 'Design options', 'total' ),
					),
				)
			);
		}

	}
}
new VCEX_Callout_Shortcode;