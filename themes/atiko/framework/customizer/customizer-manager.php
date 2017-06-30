<?php
/**
 * Customizer Manager
 *
 * @package Total WordPress Theme
 * @subpackage Customizer
 * @version 3.6.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPEX_Customizer_Manager' ) ) {
	
	class WPEX_Customizer_Manager {

		/**
		 * Start things up
		 *
		 * @since 3.0.0
		 */
		public function __construct() {
			if ( is_admin() ) {
				add_action( 'admin_menu', array( 'WPEX_Customizer_Manager', 'add_admin_page' ), 40 );
				add_action( 'admin_init', array( 'WPEX_Customizer_Manager','admin_options' ) );
				add_action( 'admin_print_styles-'. WPEX_ADMIN_PANEL_HOOK_PREFIX . '-customizer', array( 'WPEX_Customizer_Manager','admin_styles' ), 40 );
			}
		}

		/**
		 * Add sub menu page for the custom CSS input
		 *
		 * @since 3.0.0
		 */
		public static function add_admin_page() {
			add_submenu_page(
				WPEX_THEME_PANEL_SLUG,
				esc_html__( 'Customizer Manager', 'total' ),
				esc_html__( 'Customizer Manager', 'total' ),
				'administrator',
				WPEX_THEME_PANEL_SLUG .'-customizer',
				array( 'WPEX_Customizer_Manager', 'create_admin_page' )
			);
		}

		/**
		 * Prints styles for the admin page
		 *
		 * @since 3.0.0
		 */
		public static function admin_styles() { ?>

			<style type="text/css">
				.wpex-customizer-editor-table th,
				.wpex-customizer-editor-table td { padding: 7px 0 !important; }
			</style>

		<?php }

		/**
		 * Function that will register admin page options.
		 *
		 * @since 3.0.0
		 */
		public static function admin_options() {
			register_setting( 'wpex_customizer_editor', 'wpex_customizer_panels' );
		}

		/**
		 * Settings page output
		 *
		 * @since 3.0.0
		 *
		 */
		public static function create_admin_page() { ?>

			<div class="wrap">

				<h2><?php esc_html_e( 'Customizer Manager', 'total' ); ?></h2>
				<p style="max-width:70%;"><?php esc_html_e( 'It\'s best to disable the Customizer panels you\'re not currently changing or won\'t need to change anymore to speed things up. Your settings will still be set, so don\'t worry about them being reverted to their defaults.', 'total' ); ?></p>

				<?php
				// Customizer url
				$customize_url = add_query_arg( array(
					'return'                => urlencode( wp_unslash( $_SERVER['REQUEST_URI'] ) ),
					'wpex_theme_customizer' => 'true',
				), 'customize.php' ); ?>

				<h2 class="nav-tab-wrapper">
					<a href="#" class="nav-tab nav-tab-active"><?php esc_html_e( 'Enable Panels', 'total' ); ?></a>
					<a href="<?php echo esc_url( $customize_url ); ?>"  class="nav-tab"><?php esc_html_e( 'Customizer', 'total' ); ?><span class="dashicons dashicons-external" style="padding-left:7px;"></span></a>
				</h2>

				<div style="margin-top:20px;">
					<a href="#" class="wpex-customizer-check-all"><?php esc_html_e( 'Check all', 'total' ); ?></a> | <a href="#" class="wpex-customizer-uncheck-all"><?php esc_html_e( 'Uncheck all', 'total' ); ?></a>
				</div>

				<form method="post" action="options.php">

					<?php settings_fields( 'wpex_customizer_editor' ); ?>

					<table class="form-table wpex-customizer-editor-table">
						<?php
						// Get panels
						$panels = WPEX_Customizer::panels();

						// Check if post types are enabled
						$post_types = wpex_theme_post_types();

						// Get options and set defaults
						$options = get_option( 'wpex_customizer_panels', $panels );

						// Loop through panels and add checkbox
						foreach ( $panels as $id => $val ) {

							// Parse panel data
							$title     = isset( $val['title'] ) ? $val['title'] : $val;
							$condition = isset( $val['condition'] ) ? $val['condition'] : true;

							// Get option
							$option = isset( $options[$id] ) ? 'on' : false;

							// Display option if condition is met
							if ( $condition ) { ?>

								<tr valign="top">
									<th scope="row"><?php echo $title; ?></th>
									<td>
										<fieldset>
											<input class="wpex-customizer-editor-checkbox" type="checkbox" name="wpex_customizer_panels[<?php echo $id; ?>]"<?php checked( $option, 'on' ); ?>>
										</fieldset>
									</td>
								</tr>

							<?php }

							// Condition isn't met so add it as a hidden item
							else { ?>

								<input type="hidden" name="wpex_customizer_panels[<?php echo $id; ?>]"<?php checked( $option, 'on' ); ?>>	

							<?php } ?>

						<?php } ?>

					</table>

					<?php submit_button(); ?>

				</form>

			</div><!-- .wrap -->

			<script>
				(function($) {
					"use strict";
						$( '.wpex-customizer-check-all' ).click( function() {
							$('.wpex-customizer-editor-checkbox').each( function() {
								this.checked = true;
							} );
							return false;
						} );
						$( '.wpex-customizer-uncheck-all' ).click( function() {
							$('.wpex-customizer-editor-checkbox').each( function() {
								this.checked = false;
							} );
							return false;
						} );
				} ) ( jQuery );
			</script>

		<?php }

	}

}
new WPEX_Customizer_Manager;