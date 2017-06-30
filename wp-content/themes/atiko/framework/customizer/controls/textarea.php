<?php
/**
 * Customizer Textarea Control
 *
 * @package Total WordPress Theme
 * @subpackage Customizer
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPEX_Customizer_Textarea_Control' ) ) {

	class WPEX_Customizer_Textarea_Control extends WP_Customize_Control {

		/**
		 * The control type
		 *
		 * @access public
		 * @var string
		 */
		public $type = 'wpex-textarea';

		/**
		 * How many rows for the textarea
		 *
		 * @access public
		 * @var string
		 */
		public $rows = '10';

		/**
		 * Refresh the parameters passed to the JavaScript via JSON.
		 *
		 * @access public
		 */
		public function to_json() {
			parent::to_json();
			$this->json['rows'] = $this->rows;
		}

		/**
		 * Don't render the control content from PHP, as it's rendered via JS on load.
		 *
		 * @since 3.6.0
		 */
		public function render_content() { ?>

			<?php
			// Label
			if ( ! empty( $this->label ) ) : ?>

				<label><span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span></label>

			<?php endif; ?>

			<?php
			// Description
			if ( ! empty( $this->description ) ) : ?>

				<span class="description customize-control-description">
					<?php echo wp_strip_all_tags( $this->description ); ?>
				</span>

			<?php endif; ?>

			<textarea rows="<?php echo intval( $this->rows ); ?>" <?php $this->link(); ?> style="width:100%;"><?php echo esc_textarea( $this->value() ); ?></textarea>

		<?php }

	}

}