<?php
/**
 * Registers the form shortcode and adds it to the Visual Composer
 *
 * @package Total WordPress Theme
 * @subpackage VC Templates
 * @version 4.0
 */

if ( ! class_exists( 'VCEX_Form_Shortcode' ) ) {
	class VCEX_Form_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.6.0
		 */
		public function __construct() {
			
			// Add shortcode
			add_shortcode( 'vcex_form_shortcode', array( 'VCEX_Form_Shortcode', 'output' ) );

			// Map to VC
			if ( function_exists( 'vc_lean_map' ) ) {
				vc_lean_map( 'vcex_form_shortcode', array( 'VCEX_Form_Shortcode', 'map' ) );
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.6.0
		 */
		public static function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_form_shortcode.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.6.0
		 */
		public static function map() {
			return array(
				'name' => __( 'Form Shortcode', 'total' ),
				'description' => __( 'Form shortcode with style', 'total' ),
				'base' => 'vcex_form_shortcode',
				'category' => wpex_get_theme_branding(),
				'icon' => 'vcex-form-shortcode vcex-icon fa fa-wpforms',
				'params' => array(
					array(
						'type' => 'textfield',
						'admin_label' => true,
						'heading' => __( 'Form Shortcode', 'total' ),
						'param_name' => 'content',
					),
					vcex_vc_map_add_css_animation(),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Style', 'total' ),
						'param_name' => 'style',
						'std' => '',
						'value' => array_flip( wpex_get_form_styles() ),
						'description' => __( 'The theme will try and apply the necessary styles to your form (works best with Contact Form 7) but remember every contact form plugin has their own styles so additional tweaks may be required.', 'total' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Full-Width Inputs', 'total' ),
						'param_name' => 'full_width',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Width', 'total' ),
						'param_name' => 'width',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'font_size',
					),
					// Design
					array(
						'type' => 'css_editor',
						'heading' => __( 'Design', 'total' ),
						'param_name' => 'css',
						'group' => __( 'Design options', 'total' ),
					),
				),
			);
		}

	}
}
new VCEX_Form_Shortcode;