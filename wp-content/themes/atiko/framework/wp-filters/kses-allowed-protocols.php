<?php
/**
 * Filters the kses_allowed_protocols for sanitization like esc_url to allow
 * specific protocols such as skype calls
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wpex_kses_allowed_protocols( $protocols ) {
	$protocols[] = 'skype';
	return $protocols;
}
add_filter( 'kses_allowed_protocols' , 'wpex_kses_allowed_protocols' );