<?php
/**
 * Disable the VC template library
 *
 * @package Total WordPress Theme
 * @subpackage Visual Composer
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Admin only functions
if ( ! is_admin() ) {
	return;
}

// Disable (hide) Template Library
function wpex_disable_vc_template_library( $data ) {
	foreach( $data as $key => $val ) {
		if ( isset( $val['category'] ) && 'shared_templates' == $val['category'] ) {
			unset( $data[$key] );
		}
	}
	return $data;
}
add_filter( 'vc_get_all_templates', 'wpex_disable_vc_template_library', 99 );