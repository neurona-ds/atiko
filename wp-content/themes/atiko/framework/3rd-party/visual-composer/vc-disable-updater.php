<?php
/**
 * Visual Composer disable updater
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Admin only functions
if ( ! is_admin() ) {
	return;
}

// Set in theme mode & disable updater
if ( function_exists( 'vc_set_as_theme' ) ) {
	$disable_updater = true;
	vc_set_as_theme( $disable_updater );
}

// Add admin notice on product license tab
function wpex_vc_license_tab_notice() {
	$screen = get_current_screen();
	if ( 'visual-composer_page_vc-updater' == $screen->id ) {
		echo '<div class="error"><p><strong>'. esc_html__( 'Activating the Visual Composer plugin is 100% optional and NOT required to function correctly with the theme.', 'total' ) .'</strong></p></div>';
	}
}
add_action( 'admin_notices', 'wpex_vc_license_tab_notice' );

// Remove plugin license admin tab
function wpex_vc_remove_plugin_license_submenu_page(){
	remove_submenu_page( VC_PAGE_MAIN_SLUG, 'vc-updater' );
}
add_action( 'admin_menu', 'wpex_vc_remove_plugin_license_submenu_page', 999 );

// Remove plugin license tab
function wpex_vc_remove_plugin_license_tab() { ?>
	<script>
		( function( $ ) {

			"use strict";

			$( document ).on( 'ready', function() {

				var $vctabs = $( '.vc_settings .nav-tab' );

				$vctabs.each( function() {

					var href = $( this ).attr( 'href' );

					if ( href.indexOf( 'updater' ) > -1 ) {
						$( this ).hide();
					}

				} );

			} );

		} ) ( jQuery );
	</script>
<?php }
add_action( 'admin_footer-toplevel_page_vc-general', 'wpex_vc_remove_plugin_license_tab' );
add_action( 'admin_footer-visual-composer_page_vc-roles', 'wpex_vc_remove_plugin_license_tab' );
add_action( 'admin_footer-visual-composer_page_vc-automapper', 'wpex_vc_remove_plugin_license_tab' );
add_action( 'admin_footer-visual-composer_page_templatera', 'wpex_vc_remove_plugin_license_tab' );
add_action( 'admin_footer-visual-composer_page_vc-templatera', 'wpex_vc_remove_plugin_license_tab' );

// Disable VC updater
function wpex_disable_vc_updater() {
	wpex_remove_class_filter( 'upgrader_pre_download', 'Vc_Updater', 'preUpgradeFilter', 10, 4 );
	wpex_remove_class_filter( 'plugins_api', 'Vc_Updating_Manager', 'check_info', 10, 3 );
	wpex_remove_class_filter( 'pre_set_site_transient_update_plugins', 'Vc_Updating_Manager', 'check_update', 10 );
	if ( function_exists( 'vc_plugin_name' ) ) {
		wpex_remove_class_filter( 'in_plugin_update_message-' . vc_plugin_name(), 'Vc_Updating_Manager', 'addUpgradeMessageLink', 10 );
	}
}
add_action( 'admin_init', 'wpex_disable_vc_updater', 99 );