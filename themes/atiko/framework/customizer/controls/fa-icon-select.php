<?php
/**
 * Customizer Font Family Control
 *
 * @package Total WordPress Theme
 * @subpackage Customizer
 * @version 3.6.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPEX_Font_Awesome_Icon_Select' ) ) {

	class WPEX_Font_Awesome_Icon_Select extends WP_Customize_Control {

		/**
		 * The control type.
		 *
		 * @access public
		 * @var string
		 */
		public $type = 'wpex-fa-icon-select';

		/**
		 * Render the content
		 *
		 * @access public
		 */
		public function render_content() {

			$this_val = $this->value(); ?>

			<label><span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span></label>

			<div class="wpex-customizer-chosen-select">
				
				<select <?php $this->link(); ?>>
					<option value="" <?php if ( ! $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Default', 'total' ); ?></option>
					<?php if ( $icons = wpex_get_awesome_icons() ) {
						foreach ( $icons as $icon ) {
							$icon = esc_attr( $icon ); ?>
							<option value="<?php echo $icon; ?>" <?php selected( $icon, $this_val ); ?>><?php echo $icon; ?></option>
						<?php }
					} ?>
				</select>

			</div>

		<?php }

	}

}