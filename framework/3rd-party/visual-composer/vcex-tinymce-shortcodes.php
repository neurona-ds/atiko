<?php
/**
 * Adds custom templates to VC for use
 *
 * @package Total WordPress Theme
 * @subpackage Visual Composer
 * @version 4.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Add shortcodes to the theme's shortcode editor inserter button
function vcex_wpex_shortcodes_tinymce_json( $data ) {

	$data['shortcodes']['vcex_button'] = array(
		'text' => esc_html__( 'Button', 'total' ),
		'insert' => '[vcex_button url="http://www.google.com/" title="Visit Site" style="graphical" align="left" color="black" size="small" target="self" rel="none"]Button Text[/vcex_button]',
	);

	$data['shortcodes']['vcex_divider'] = array(
		'text' => esc_html__( 'Divider', 'total' ),
		'insert' => '[vcex_divider color="#dddddd" width="100%" height="1px" margin_top="20" margin_bottom="20"]',
	);

	$data['shortcodes']['vcex_divider_dots'] = array(
		'text' => esc_html__( 'Divider Dots', 'total' ),
		'insert' => '[vcex_divider_dots color="#dd3333" margin_top="10" margin_bottom="10"]',
	);

	$data['shortcodes']['vcex_spacing'] = array(
		'text' => esc_html__( 'Spacing', 'total' ),
		'insert' => '[vcex_spacing size="20px"]',
	);

	$data['shortcodes']['vcex_spacing'] = array(
		'text' => esc_html__( 'Spacing', 'total' ),
		'insert' => '[vcex_spacing size="30px"]',
	);
	
	return $data;
}
add_filter( 'wpex_shortcodes_tinymce_json', 'vcex_wpex_shortcodes_tinymce_json' );