<?php
/**
 * Adds filters to allow shortcodes in various WP locations
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Allow for the use of shortcodes in the WordPress excerpt
add_filter( 'the_excerpt', 'shortcode_unautop' );
add_filter( 'the_excerpt', 'do_shortcode' );

// Allow shortcodes in menus
add_filter( 'wp_nav_menu_items', 'do_shortcode' );