<?php
/**
 * Visual Composer Bullets
 *
 * @package Total WordPress Theme
 * @subpackage VC Templates
 * @version 4.0
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

// Return if no content
if ( empty( $content ) ) {
	return;
}

// Define output
$output = '';

// Get shortcode attributes
$atts = vc_map_get_attributes( 'vcex_bullets', $atts );

// Define wrap attributes
$wrap_attrs = array(
	'id'   => vcex_get_unique_id( $atts['unique_id'] ),
	'data' => '',
);

// Wrap classes
$wrap_classes = 'vcex-module vcex-bullets';	
if ( $atts['css_animation'] && 'none' != $atts['css_animation'] ) {
	$wrap_classes .= ' '. vcex_get_css_animation( $atts['css_animation'] );
}
if ( $atts['style'] && ! $atts['icon_type'] ) {
	$wrap_classes .= ' vcex-bullets-'. $atts['style'];
} else {
	$icon       = vcex_get_icon_class( $atts, 'icon' );
	$icon_style = vcex_inline_style( array(
		'color' => $atts['icon_color']
	) );
	$content = str_replace( '<li>', '<li><span class="vcex-icon '. $icon .'" '. $icon_style .'></span>', $content );
	$wrap_classes .= ' custom-icon';
}

// Wrap Style
$wrap_attrs['style'] = vcex_inline_style( array(
	'color'          => $atts['color'],
	'font_family'    => $atts['font_family'],
	'font_size'      => $atts['font_size'],
	'letter_spacing' => $atts['letter_spacing'],
	'font_weight'    => $atts['font_weight'],
	'line_height'    => $atts['line_height'],
) );

// Load custom font
if ( $atts['font_family'] ) {
	wpex_enqueue_google_font( $atts['font_family'] );
}

// Enqueue needed icon font
if ( $atts['icon'] && 'fontawesome' != $atts['icon_type'] ) {
	vcex_enqueue_icon_font( $atts['icon_type'] );
}

// Responsive settings
if ( $responsive_data = vcex_get_module_responsive_data( $atts['font_size'], 'font_size' ) ) {
	$wrap_attrs['data'] .= ' '. $responsive_data['data'];
	$wrap_classes .= ' wpex-rcss '. $responsive_data['uniqid'];
}

// Add filters to wrap classes and add to attributes
$wrap_attrs['class'] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $wrap_classes, 'vcex_bullets', $atts );

// Output
$output .= '<div ' . wpex_parse_attrs( $wrap_attrs ) . '>';

	$output .= do_shortcode( wp_kses_post( $content ) );

$output .= '</div>';

// Echo output
echo $output;