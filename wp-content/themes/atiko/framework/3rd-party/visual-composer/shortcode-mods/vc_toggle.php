<?php
/**
 * Visual Composer Toggle Configuration
 *
 * @package Total WordPress Theme
 * @subpackage Visual Composer
 * @version 4.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start Class
if ( ! class_exists( 'VCEX_VC_Toggle_Config' ) ) {
	
	class VCEX_VC_Toggle_Config {

		/**
		 * Main constructor
		 *
		 * @since 4.1
		 */
		public function __construct() {
			add_action( 'init', array( 'VCEX_VC_Toggle_Config', 'map_update' ), 10 );
		}

		/**
		 * Update main settings
		 *
		 * @since 4.1
		 */
		public static function map_update() {
			vc_map_update( 'vc_toggle', array(
				'name' => __( 'FAQ/Toggle', 'total' ),
			) );
		}

	}

}
new VCEX_VC_Toggle_Config();