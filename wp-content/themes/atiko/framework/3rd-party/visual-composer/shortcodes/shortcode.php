<?php
/**
 * Visual Composer Shortcode for Shortcodes
 *
 * Provides a better way for adding shortcodes in the VC
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.1
 */

if ( ! class_exists( 'VCEX_Shortcode' ) ) {

	class VCEX_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.6.0
		 */
		public function __construct() {
			
			// Add shortcode
			add_shortcode( 'vcex_shortcode', array( 'VCEX_Shortcode', 'output' ) );

			// Map to VC
			if ( function_exists( 'vc_lean_map' ) ) {
				vc_lean_map( 'vcex_shortcode', array( 'VCEX_Shortcode', 'map' ) );
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.6.0
		 */
		public static function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_shortcode.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.6.0
		 */
		public static function map() {
			return array(
				'name' => __( 'Shortcode', 'total' ),
				'description' => __( 'Insert custom shortcodes', 'total' ),
				'base' => 'vcex_shortcode',
				'icon' => 'vcex-shortcode vcex-icon fa fa-cog',
				'category' => wpex_get_theme_branding(),
				'params' => array(
					array(
						'type' => 'textfield',
						'heading' => __( 'Shortcode', 'total' ),
						'param_name' => 'content',
						'admin_label' => true,
					),
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
				),
			);
		}

	}
}
new VCEX_Shortcode;