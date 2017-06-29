<?php
/**
 * Enables custom buttons in the WP tiny mce editor
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
 * Add Formats dropdown
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'wpex_style_select' ) ) {
	function wpex_style_select( $buttons ) {
		array_push( $buttons, 'styleselect' );
		return $buttons;
	}
}
add_filter( 'mce_buttons', 'wpex_style_select' );

/**
 * Enable font size buttons in the editor
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'wpex_mce_buttons' ) ) {
	function wpex_mce_buttons( $buttons ) {
		array_unshift( $buttons, 'fontselect' );
		array_unshift( $buttons, 'fontsizeselect' );
		return $buttons;
	}
}
add_filter( 'mce_buttons_2', 'wpex_mce_buttons' );