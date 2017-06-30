<?php
/**
 * bbPress Configuration Class
 *
 * @package Total WordPress Theme
 * @subpackage 3rd Party
 * @version 4.0
 */

if ( ! class_exists( 'WPEX_Buddypress_Config' ) ) {

	class WPEX_Buddypress_Config {

		/**
		 * Start things up
		 *
		 * @access public
		 * @since  4.0
		 */
		public function __construct() {

			// Define bbPress directory
			define( 'WPEX_BUDDYPRESS_DIR', WPEX_FRAMEWORK_DIR . '3rd-party/buddypress/' );

			// Load custom CSS
			add_action( 'wp_enqueue_scripts', array( 'WPEX_Buddypress_Config', 'scripts' ), 20 );

		}

		/**
		 * Load custom CSS
		 *
		 * @since  4.0
		 */
		public static function scripts() {
			wp_enqueue_style(
				'wpex-buddypress',
				wpex_asset_url( 'css/wpex-buddypress.css' ),
				array(),
				WPEX_THEME_VERSION
			);
		}

	}

}

new WPEX_Buddypress_Config();