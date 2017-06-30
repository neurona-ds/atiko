<?php
/**
 * LayerSlider Config
 *
 * @package Total WordPress Theme
 * @subpackage 3rd Party
 * @version 3.6.0
 */

// Start Class
if ( ! class_exists( 'WPEX_Layerslider_Config' ) ) {

	class WPEX_Layerslider_Config {

		/**
		 * Start things up
		 *
		 * @since 3.6.0
		 */
		public function __construct() {

			// Enqueue CSS
			add_action( 'wp_enqueue_scripts', array( 'WPEX_Layerslider_Config', 'css' ) );

			// Remove purchase notice on plugins page
			if ( defined( 'LS_PLUGIN_BASE' ) && ! get_option( 'layerslider-authorized-site', null ) ) {
				remove_action( 'after_plugin_row_'. LS_PLUGIN_BASE, 'layerslider_plugins_purchase_notice', 10, 3 );
			}

		}

		/**
		 * Enqueue layerslider theme CSS
		 *
		 * @since 3.6.0
		 */
		public static function css() {
			wp_enqueue_style( 'wpex-layerslider', wpex_asset_url( 'css/wpex-layerslider.css' ), array(), WPEX_THEME_VERSION );
		}

	}

}
new WPEX_Layerslider_Config;