<?php
/**
 * Customizer Heading Control
 *
 * @package Total WordPress Theme
 * @subpackage Customizer
 * @version 3.6.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPEX_Customizer_Heading_Control' ) ) {

	class WPEX_Customizer_Heading_Control extends WP_Customize_Control {

		/**
		 * The control type
		 *
		 * @access public
		 * @var string
		 */
		public $type = 'wpex-heading';
		
		/**
		 * Don't render the control content from PHP, as it's rendered via JS on load.
		 *
		 * @since 3.6.0
		 */
		public function render_content() {}

		/**
		 * The control template
		 *
		 * @since 3.6.0
		 */
		public function content_template() { ?>

			<# if ( data.label ) { #>
				<span class="wpex-customizer-heading">{{ data.label }}</span>
			<# } #>

		<?php }

	}

}