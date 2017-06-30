<?php
/**
 * Visual Composer Leader
 *
 * @package Total WordPress Theme
 * @subpackage VC Templates
 * @version 4.2.1
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
extract( vc_map_get_attributes( 'vcex_leader', $atts ) );

$leaders = (array) vc_param_group_parse_atts( $leaders );

if ( $leaders ) {

	$responsive = ( wpex_is_layout_responsive() && 'true' == $responsive ) ? true : false;

	$inline_style = vcex_inline_style( array(
		'color'      => $color,
		'font_size'  => $font_size,
	) );

	$classes = 'vcex-module vcex-leader vcex-leader-'. $style .' clr';
	if ( $responsive ) {
		$classes .= ' vcex-responsive';
	}
	if ( $el_class ) {
		$classes .= ' '. vcex_get_extra_class( $el_class );
	}

	if ( $responsive_data = vcex_get_module_responsive_data( $atts ) ) {
		$data_attr = ' '. $responsive_data['data'];
		$classes  .= ' wpex-rcss '. $responsive_data['uniqid'];
	} else {
		$data_attr = '';
	}

	// Add filters to the module classes
	$classes = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $classes, 'vcex_leader', $atts );

	// Begin output
	$output = '<ul class="'. $classes .'"'. $inline_style . $data_attr .'>';

	// Label typography
	$label_typo = vcex_inline_style( array(
		'color'       => $label_color,
		'font_weight' => $label_font_weight,
		'font_style'  => $label_font_style,
		'font_family' => $label_font_family,
		'background'  => $background,
	) );

	if ( $label_font_family ) {
		wpex_enqueue_google_font( $label_font_family );
	}

	// value typography
	$value_typo = vcex_inline_style( array(
		'color'       => $value_color,
		'font_weight' => $value_font_weight,
		'font_style'  => $value_font_style,
		'font_family' => $value_font_family,
		'background'  => $background,
	) );

	if ( $value_font_family ) {
		wpex_enqueue_google_font( $value_font_family );
	}

	// Individual item classes
	$leader_classes = 'clr';
	if ( $css_animation && 'none' != $css_animation ) {
		$leader_classes .= ' '. vcex_get_css_animation( $css_animation );
	}

	// Loop through leaders and output it's content
	foreach ( $leaders as $leader ) {

		$label = isset( $leader['label'] ) ? $leader['label'] : esc_html__( 'Label', 'total' );
		$value = isset( $leader['value'] ) ? $leader['value'] : esc_html__( 'Value', 'total' );

		$output .= '<li class="'. $leader_classes .'">';
			
			$output .= '<span class="vcex-first"'. $label_typo .'>'. esc_html( $label ) .'</span>';
			
			if ( $responsive ) {

				$output .= '<span class="vcex-inner">...</span>';

			}

			if ( 'Value' != $value ) {
			
				$output .= '<span class="vcex-last"'. $value_typo .'>'. esc_html( $value ) .'</span>';

			}
		
		$output .= '</li>';

	}

	$output .= '</ul>';

	echo $output;

}