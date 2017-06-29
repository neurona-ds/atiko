<?php
/**
 * Visual Composer Divider MultiColor
 *
 * @package Total WordPress Theme
 * @subpackage VC Templates
 * @version 4.2
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
if ( ! function_exists( 'vc_map_get_attributes' ) || ! function_exists( 'vc_param_group_parse_atts' ) ) {
	vcex_function_needed_notice();
	return;
}

// Get and extract shortcode attributes
$atts = vc_map_get_attributes( 'vcex_divider_multicolor', $atts );

$colors = (array) vc_param_group_parse_atts( $atts['colors'] );

if ( ! $colors ) {
	return;
}

$count = count( $colors );

// Define default wrap attributes
$wrap_attrs = array(
	'class' => 'vcex-module vcex-divider-multicolor clr',
);

if ( $atts['el_class'] ) {
	$wrap_attrs['class'] .= ' ' . vcex_get_extra_class( $el_class );
}

if ( $atts['visibility'] ) {
	$wrap_attrs['class'] .= ' ' . $atts['visibility'];
}

if ( $atts['align'] && 'center' != $atts['align'] ) {
	$wrap_attrs[ 'class' ] .= ' float-' . $atts['align'];
}

if ( $atts['width'] && '100%' != $atts['width'] ) {
	$wrap_attrs['style'] = vcex_inline_style( array(
		'width'         => $atts['width'],
		'margin_bottom' => $atts['margin_bottom'],
	), false );
}

// Begin output
$output = '<div ' . wpex_parse_attrs( $wrap_attrs ) . '>';

	// Loop through colors
	foreach ( $colors as $color ) {

		$inline_style = vcex_inline_style( array(
			'background' => isset( $color['value'] ) ? $color['value'] : '',
			'width'      => ( 100 / $count ) . '%',
			'height'     => ( $atts['height'] && '8px' !== $atts['height'] ) ? intval( $atts['height'] ) : '',
		), false );

		$output .= wpex_parse_html( 'span', array(
			'style' => $inline_style
		) );
	 
	}

// End output
$output .= '</div>';

// Clear floats
if ( $atts['align'] && 'center' != $atts['align'] ) {
	$output .= '<div style="clear:both;"></div>';
}

// Echo output
echo $output;