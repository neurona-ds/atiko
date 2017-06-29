<?php
/**
 * Redirect single posts if redirect custom field is being used.
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wpex_post_redirect() {
	if ( ! wpex_vc_is_inline()
		&& is_singular()
		&& $custom_link = wpex_get_custom_permalink()
	) {
		wp_redirect( $custom_link, 301 );
	}
}
add_action( 'template_redirect', 'wpex_post_redirect' );