<?php
/**
 * RevSlider Config
 *
 * @package Total WordPress Theme
 * @subpackage 3rd Party
 * @version 3.6.0
 */

// Start Class
if ( ! class_exists( 'WPEX_RevSlider_Config' ) ) {

	class WPEX_RevSlider_Config {

		/**
		 * Start things up
		 *
		 * @since 3.4.0
		 */
		public function __construct() {

			// Admin functions
			if ( is_admin() ) {

				// Remove notices
				global $pagenow;
				if ( $pagenow == 'plugins.php' ) {
					add_action( 'admin_notices', array( $this, 'remove_plugins_page_notices' ), 9999 );
				}

				// Remove activation notice
				wpex_remove_class_filter( 'admin_notices', 'RevSliderAdmin', 'addActivateNotification', 10 );

				// Remove metabox from VC grid builder
				add_action( 'do_meta_boxes', array( 'WPEX_RevSlider_Config', 'remove_metabox' ) );

			}

			// Front end functions
			else {

				// Remove front-end meta generator
				add_filter( 'revslider_meta_generator', '__return_false' );

			}

		}

		/**
		 * Remove Revolution Slider plugin notices
		 *
		 * @since 3.4.0
		 */
		public function remove_plugins_page_notices() {
			$plugin_id = 'revslider/revslider.php';

			// Remove plugin page purchase notice
			remove_action( 'after_plugin_row_'. $plugin_id, array( 'RevSliderAdmin', 'show_purchase_notice' ), 10, 3 );

			// Hide update notice if not valid
			if ( 'false' == get_option( 'revslider-valid', 'false' ) ) {

				remove_action( 'after_plugin_row_' . $plugin_id, array( 'RevSliderAdmin', 'show_update_notice' ), 10, 3 );

			}

		}

		/**
		 * Remove metabox from VC grid builder
		 *
		 * @since 3.6.0
		 */
		public static function remove_metabox() {
			remove_meta_box( 'mymetabox_revslider_0', array( 'vc_grid_item', 'post', 'page', 'portfolio', 'staff', 'testimonials', 'products', 'tribe_events' ), 'normal' );
		}


	}

}
new WPEX_RevSlider_Config();