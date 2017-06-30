<?php
/**
 * Customizer HR Control
 *
 * @package Total WordPress Theme
 * @subpackage Customizer
 * @version 3.6.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPEX_Customizer_Hr_Control' ) ) {

	class WPEX_Customizer_Hr_Control extends WP_Customize_Control {

		/**
		 * The control type
		 *
		 * @access public
		 * @var string
		 */
		public $type = 'hr';

		/**
		 * The control template
		 *
		 * @since 3.6.0
		 */
		public function content_template() { ?>

			<hr />

		<?php }

	}

}