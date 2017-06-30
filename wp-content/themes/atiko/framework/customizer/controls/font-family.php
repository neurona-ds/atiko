<?php
/**
 * Customizer Font Family Control
 *
 * @package Total WordPress Theme
 * @subpackage Customizer
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPEX_Fonts_Dropdown_Custom_Control' ) ) {

	class WPEX_Fonts_Dropdown_Custom_Control extends WP_Customize_Control {

		/**
		 * The control type.
		 *
		 * @access public
		 * @var string
		 */
		public $type = 'wpex-font-family';

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

					<?php
					// Add custom fonts from child themes
					$fonts = wpex_add_custom_fonts();
					if ( $fonts && is_array( $fonts ) ) { ?>
						<optgroup label="<?php esc_html_e( 'Custom Fonts', 'total' ); ?>">
							<?php foreach ( $fonts as $font ) {
								$font = esc_attr( $font ); ?>
								<option value="<?php echo $font; ?>" <?php selected( $font, $this_val ); ?>><?php echo $font; ?></option>
							<?php } ?>
						</optgroup>
					<?php }

					// Get Standard font options
					if ( $std_fonts = wpex_standard_fonts() ) { ?>

						<optgroup label="<?php esc_html_e( 'Standard Fonts', 'total' ); ?>">
							<?php
							// Loop through font options and add to select
							foreach ( $std_fonts as $font ) {
								$font = esc_attr( $font ); ?>
								<option value="<?php echo $font; ?>" <?php selected( $font, $this_val ); ?>><?php echo $font; ?></option>
							<?php } ?>
						</optgroup>

					<?php }

					// Google font options
					if ( $google_fonts = wpex_google_fonts_array() ) { ?>

						<optgroup label="<?php esc_html_e( 'Google Fonts', 'total' ); ?>">
							<?php
							// Loop through font options and add to select
							foreach ( $google_fonts as $font ) {
								$font = esc_attr( $font ); ?>
								<option value="<?php echo $font; ?>" <?php selected( $font, $this_val ); ?>><?php echo $font; ?></option>
							<?php } ?>
						</optgroup>

					<?php } ?>

				</select>

				<?php if ( ! empty( $this->description ) ) : ?>

					<span class="description customize-control-description">
						<?php echo wp_strip_all_tags( $this->description ); ?>
					</span>

				<?php endif; ?>

			</div>

		<?php }
	}

}