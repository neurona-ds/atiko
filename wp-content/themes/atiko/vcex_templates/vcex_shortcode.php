<?php
/**
 * Visual Composer Bullets
 *
 * @package Total WordPress Theme
 * @subpackage VC Templates
 * @version 4.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Not needed in admin ever
if ( is_admin() ) {
    return;
}

// Required VC functions
if ( ! function_exists( 'vc_map_get_attributes' ) ) {
	vcex_function_needed_notice();
	return;
}

// Content required
if ( empty( $content ) ) {
	return;
}

// Get shortcode attributes
$atts = vc_map_get_attributes( 'vcex_shortcode', $atts );

// Define classes
$classes = 'vcex-sshortcode clr';
if ( $atts['visibility'] ) {
	$classes .= ' '. $atts['visibility'];
}
if ( $css_animation = vcex_get_css_animation( $atts['css_animation'] ) ) {
	$classes .= ' '. $css_animation;
}
if ( $el_class = vcex_get_extra_class( $atts['el_class'] ) ) {
	$classes .= ' '. $el_class;
}
$classes = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $classes, 'vcex_shortcode', $atts );

// Echo shortcode
echo '<div class="'. esc_attr( $classes ) .'">'. do_shortcode( $content ) .'</div>';
	