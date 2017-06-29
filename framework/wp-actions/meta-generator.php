<?php
/**
 * Add theme meta generator tag
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wpex_theme_meta_generator() {
	echo '<meta name="generator" content="Total WordPress Theme ' . WPEX_THEME_VERSION . '" />';
	echo "\r\n";
}
add_action( 'wp_head', 'wpex_theme_meta_generator', 1 );