<?php
/**
 * Contat Form 7 Configuration Class
 *
 * @package Total WordPress Theme
 * @subpackage 3rd Party
 * @version 4.0
 */

if ( ! class_exists( 'WPEX_Contact_Form_7' ) ) {

	class WPEX_Contact_Form_7 {

		/**
		 * Start things up
		 *
		 * @version 3.6.0
		 */
		public function __construct() {

			// Remove CSS Completely - theme adds styles
			add_filter( 'wpcf7_load_css', '__return_false' );

			// Remove JS
			add_filter( 'wpcf7_load_js', '__return_false' );

			// Conditionally load JS
			add_action( 'wpcf7_contact_form', array( 'WPEX_Contact_Form_7', 'enqueue_js' ), 1 );

		}

		/**
		 * Load JS conditionally
		 *
		 * @version 3.6.0
		 */
		public static function enqueue_js() {
			if ( function_exists( 'wpcf7_enqueue_scripts' ) ) {
				wpcf7_enqueue_scripts();
			}
		}

	}
	
}
new WPEX_Contact_Form_7();