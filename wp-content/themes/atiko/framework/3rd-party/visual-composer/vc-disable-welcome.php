<?php
/**
 * Visual Composer disable updater
 *
 * @package Total WordPress Theme
 * @subpackage Visual Composer
 * @version 3.6.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Admin only functions
if ( ! is_admin() ) {
	return;
}

// Remove actions
remove_action( 'vc_activation_hook', 'vc_page_welcome_set_redirect' );
remove_action( 'init', 'vc_page_welcome_redirect' );
remove_action( 'admin_init', 'vc_page_welcome_redirect' );

// Remove menu item
function wpex_vc_remove_welcome_page() {
	remove_submenu_page( 'vc-general', 'vc-welcome' );
}
add_action( 'admin_menu', 'wpex_vc_remove_welcome_page', 999 );