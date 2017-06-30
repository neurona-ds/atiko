<?php
/**
 * Customizer Pages Select Control
 *
 * @package Total WordPress Theme
 * @subpackage Customizer
 * @version 4.2
 */

if ( ! class_exists( 'WPEX_Customizer_Dropdown_Pages' ) ) {

	class WPEX_Customizer_Dropdown_Pages extends WP_Customize_Control {

		/**
		 * The control type.
		 *
		 * @access public
		 * @var string
		 */
		public $type = 'wpex-dropdown-pages';

		/**
		 * Render the content
		 *
		 * @access public
		 */
		public function render_content() { ?>

			<label class="customize-control-select">

			<?php if ( ! empty( $this->label ) ) : ?>

				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>

			<?php endif;

			// Description
			if ( ! empty( $this->description ) ) { ?>
				<span class="description customize-control-description"><?php echo $this->description; ?></span>
			<?php } ?>

			<div class="wpex-customizer-chosen-select">

				<?php
				// The dropdown
				$dropdown = wp_dropdown_pages( array(
						'name'              => '_customize-dropdown-pages-' . $this->id,
						'echo'              => 0,
						'show_option_none'  => '&mdash; '. esc_html__( 'Select', 'total' ) .' &mdash;',
						'option_none_value' => '0',
						'selected'          => $this->value(),
				) );

				// Hackily add in the data link parameter.
				echo str_replace( '<select', '<select ' . $this->get_link(), $dropdown ); ?>

			</div>

		<?php }
	}

}