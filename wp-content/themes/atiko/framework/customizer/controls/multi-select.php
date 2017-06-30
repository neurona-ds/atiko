<?php
/**
 * Customizer Multi-Select Control
 *
 * @package Total WordPress Theme
 * @subpackage Customizer
 * @version 3.6.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPEX_Customize_Control_Multiple_Select' ) ) {

	class WPEX_Customize_Control_Multiple_Select extends WP_Customize_Control {
		/**
		 * The type of customize control being rendered.
		 */
		public $type = 'multiple-select';

		/**
		 * Displays the multiple select on the customize screen.
		 */
		public function render_content() {
		if ( empty( $this->choices ) ) {
			return;
		}
		$this_val = $this->value(); ?>
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php if ( '' != $this->description ) { ?>
					<span class="description customize-control-description"><?php echo $this->description; ?></span>
				<?php } ?>
				<select <?php $this->link(); ?> multiple="multiple" style="height:120px;">
					<?php foreach ( $this->choices as $value => $label ) {
						$selected = ( in_array( $value, $this_val ) ) ? selected( 1, 1, false ) : '';
						echo '<option value="' . esc_attr( $value ) . '"' . $selected . '>' . $label . '</option>';
					} ?>
				</select>
			</label>
		<?php }
	}

}