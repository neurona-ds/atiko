<?php
/**
 * Edits the default font size options in the WP editor
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Add custom font sizes
function wpex_tiny_mce_fontsize_formats( $initArray ){
	$initArray['fontsize_formats'] = '9px 10px 12px 13px 14px 16px 18px 21px 24px 28px 32px 36px';
	return $initArray;
}
add_filter( 'tiny_mce_before_init', 'wpex_tiny_mce_fontsize_formats' );