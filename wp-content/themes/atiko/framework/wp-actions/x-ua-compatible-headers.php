<?php
/**
 * Run IE 11 as IE edge
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
 * Adds x-ua compatible meta tag
 *
 * @since 4.0
 */
function wpex_x_ua_compatible_meta_tag() {
	echo '<meta http-equiv="X-UA-Compatible" content="IE=edge" />';
	echo "\r\n";
}
add_action( 'wp_head', 'wpex_x_ua_compatible_meta_tag' );

/**
 * Filters the HTTP headers before they’re sent to the browser.
 *
 * @since 4.0
 */
function wpex_x_ua_compatible_headers( $headers ) {
	$headers['X-UA-Compatible'] = 'IE=edge';
	return $headers;
}
add_filter( 'wp_headers', 'wpex_x_ua_compatible_headers' );