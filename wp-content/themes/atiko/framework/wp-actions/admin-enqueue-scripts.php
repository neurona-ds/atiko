<?php
/**
 * Enqueue admin scripts
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue Font-Awesome on WP admin side
 *
 * @since 4.0
 */
function wpex_enqueue_font_awesome_in_admin( $hook ) {

		// Array of places to load font awesome
		$hooks = array(
			'edit.php',
			'post.php',
			'post-new.php',
			'widgets.php',
		);

		// Only needed on these admin screens
		if ( ! in_array( $hook, $hooks ) ) {
			return;
		}

		// Load font awesome script for VC icons and other
		wp_enqueue_style(
			'font-awesome',
			wpex_asset_url( 'lib/font-awesome/css/font-awesome.min.css' ),
			array(),
			'4.6.3'
		);

	}
add_action( 'admin_enqueue_scripts', 'wpex_enqueue_font_awesome_in_admin' );