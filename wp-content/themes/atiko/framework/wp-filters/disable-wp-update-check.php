<?php
/**
 * Prevent WP update checks
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Disable auto updates check function
function wpex_disable_wporg_request( $r, $url ) {

	// If it's not a theme update request, bail.
	if ( 0 !== strpos( $url, 'https://api.wordpress.org/themes/update-check/1.1/' ) ) {
		return $r;
	}

	// Decode the JSON response
	$themes = json_decode( $r['body']['themes'] );

	// Remove the active parent and child themes from the check
	if ( $parent = get_option( 'template' ) ) {
		unset( $themes->themes->$parent );
	}
	if ( $child  = get_option( 'stylesheet' ) ) {
		unset( $themes->themes->$child );
	}

	// Encode the updated JSON response
	$r['body']['themes'] = json_encode( $themes );

	return $r;

}
add_filter( 'http_request_args', 'wpex_disable_wporg_request', 5, 2 );