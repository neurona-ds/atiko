<?php
/**
 * Gravity Forms Configuration Class
 *
 * @package Total WordPress Theme
 * @subpackage 3rd Party
 * @version 3.6.0
 */

if ( ! class_exists( 'WPEX_Gravity_Forms_Config' ) ) {

	class WPEX_Gravity_Forms_Config {

		/**
		 * Main constructor
		 *
		 * @version 3.6.0
		 */
		public function __construct() {
			if ( ! is_admin() ) {
				add_action( 'wp_enqueue_scripts', array( 'WPEX_Gravity_Forms_Config', 'gravity_forms_css' ), 40 );
			}
		}

		/**
		 * Loads Gravity Forms stylesheet
		 *
		 * @since 1.6.0
		 */
		public static function gravity_forms_css() {
			global $post;
			if ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'gravityform' ) ) {
				wp_enqueue_style( 'wpex-gravity-forms', wpex_asset_url( 'css/wpex-gravity-forms.css' ), array(), WPEX_THEME_VERSION );
			}
		}

	}
	
}
new WPEX_Gravity_Forms_Config();