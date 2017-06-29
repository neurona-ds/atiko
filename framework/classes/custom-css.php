<?php
/**
 * Adds custom CSS to the site from Customizer settings
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.2.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start Class
if ( ! class_exists( 'WPEX_Custom_CSS' ) ) {

	class WPEX_Custom_CSS {

		/**
		 * Main constructor
		 *
		 * @since 4.0
		 */
		public function __construct() {

			// Add custom CSS to head tag
			add_action( 'wp_head', array( 'WPEX_Custom_CSS', 'total_head_css' ), 9999 );

			// Minify custom CSS on front-end only
			// Can't minify on backend or messes up the Custom CSS panel
			if ( ! is_admin() && ! is_customize_preview() ) {
				add_filter( 'wp_get_custom_css', array( 'WPEX_Custom_CSS', 'minify_custom_css' ) );
			}

		}

		/**
		 * Add all custom CSS into the WP Header
		 *
		 * @since 4.0
		 * @deprecated Used as a fallback but deprecated in 4.0 to use function above instead
		 */
		public static function total_head_css( $output = NULL ) {

			// Add filter for adding custom css via other functions
			$output = apply_filters( 'wpex_head_css', $output );

			// Custom CSS panel => Add last after all filters to make sure it always overrides
			// Deprecated in 4.0
			if ( $css = wpex_get_mod( 'custom_css', false ) ) {
				$output .= '/*CUSTOM CSS*/'. $css;
			}

			// Minify and output CSS in the wp_head
			if ( ! empty( $output ) ) {

				// Sanitize output
				$output = wp_strip_all_tags( wpex_minify_css( $output ) );

				// Echo output
				echo '<style type="text/css" data-type="wpex-css" id="wpex-css">' . $output . '</style>';

			}

		}

		/**
		 * Filter the WP custom CSS to minify the output since WP doesn't do it by default
		 *
		 * @since 4.0
		 */
		public static function minify_custom_css( $css ) {
			return wpex_minify_css( $css );
		}

	}

}
new WPEX_Custom_CSS();