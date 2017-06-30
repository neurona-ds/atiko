<?php
/**
 * Customizer Patterns Control
 *
 * @package Total WordPress Theme
 * @subpackage Customizer
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPEX_Customizer_BG_Patterns_Control' ) ) {

	class WPEX_Customizer_BG_Patterns_Control extends WP_Customize_Control {

		/**
		 * The control type
		 *
		 * @access public
		 * @var string
		 */
		public $type = 'wpex-bg-patterns';

		/**
		 * The control template
		 *
		 * @since 4.0
		 */
		public function render_content() {

			$this_val = $this->value(); ?>

			<label><span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span></label>
				
			<select <?php $this->link(); ?>>
				<option value="" <?php selected( $this_val, '' ); ?>><?php esc_html_e( 'None', 'total' ); ?></option>
				<?php
				if ( $patterns = wpex_get_background_patterns() ) {
					foreach ( $patterns as $key => $val ) { ?>
						<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $this_val, $key ); ?>><?php echo $val['label']; ?></option>
					<?php }
				} ?>
			</select>

		<?php }

	}

}