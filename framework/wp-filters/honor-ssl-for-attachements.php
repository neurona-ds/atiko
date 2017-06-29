<?php
/**
 * The wp_get_attachment_url() function doesn't distinguish whether a page request arrives via HTTP or HTTPS.
 * Using wp_get_attachment_url filter, we can fix this to avoid the dreaded mixed content browser warning
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wpex_honor_ssl_for_attachments( $url ) {
	if ( is_ssl() ) {
		$upload_info = wp_upload_dir();
		if ( isset( $upload_info[ 'baseurl' ] ) && strpos( $upload_info[ 'baseurl' ], 'https' ) ) {
			$http  = site_url( FALSE, 'http' );
			$https = site_url( FALSE, 'https' );
			return str_replace( $http, $https, $url );
		}
	}
	return $url;
}
add_filter( 'wp_get_attachment_url', 'wpex_honor_ssl_for_attachments' );