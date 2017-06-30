<?php
/**
 * Run functions after theme switch
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wpex_after_switch_theme() {

	// Flush rewrite rules
	flush_rewrite_rules();

	// Delete tgma plugin activation script user meta data to make sure notices display correctly
	delete_metadata( 'user', null, 'tgmpa_dismissed_notice_wpex_theme', null, true );

}
add_action( 'after_switch_theme', 'wpex_after_switch_theme' );