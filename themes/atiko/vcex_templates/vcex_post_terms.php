<?php
/**
 * Visual Composer Terms Grid
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
if ( ! function_exists( 'vc_map_get_attributes' ) || ! function_exists( 'vc_shortcode_custom_css_class' ) ) {
	vcex_function_needed_notice();
	return;
}

// Get shortcode attributes
$atts = vc_map_get_attributes( 'vcex_post_terms', $atts );

// Taxonomy is required
if ( ! $atts['taxonomy'] ) {
	return;
}

// Load Google Fonts if needed
if ( $atts['button_font_family'] ) {
	wpex_enqueue_google_font( $atts['button_font_family'] );
}

// Query arguments
$query_args = array(
	'order'   => $atts['order'],
	'orderby' => $atts['orderby'],
	'fields'  => 'all',
);

// Apply filters to query args
$query_args = apply_filters( 'vcex_post_terms_query_args', $query_args );

// Get terms
$terms = wp_get_post_terms( get_the_ID(), $atts['taxonomy'], $query_args );

// Terms needed
if ( ! $terms || is_wp_error( $terms ) ) {
	return;
}

// Wrap classes
$wrap_classes = 'vcex-post-terms clr';
if ( $atts['visibility'] ) {
	$wrap_classes .= ' '. $atts['visibility'];
}
if ( $atts['classes'] ) {
	$wrap_classes .= ' '. vcex_get_extra_class( $atts['classes'] );
}
if ( 'center' == $atts['button_align'] ) {
	$wrap_classes .= ' textcenter';
}

// Get button classes
$button_classes = wpex_get_button_classes( $atts['button_style'], $atts['button_color_style'], $atts['button_size'], $atts['button_align'] );
if ( $atts['css_animation'] && 'none' != $css_animation ) {
	$button_classes .= ' '. vcex_get_css_animation( $atts['css_animation'] );
}

// Button Style
$button_style = vcex_inline_style( array(
	'margin'         => $atts['button_margin'],
	'color'          => $atts['button_color'],
	'background'     => $atts['button_background'],
	'padding'        => $atts['button_padding'],
	'font_size'      => $atts['button_font_size'],
	'font_weight'    => $atts['button_font_weight'],
	'border_radius'  => $atts['button_border_radius'],
	'text_transform' => $atts['button_text_transform'],
) );

// Button data
$button_data = '';
if ( $atts['button_hover_background'] || $atts['button_hover_color'] ) {
	if ( $atts['button_hover_background'] ) {
		$button_data .= ' data-hover-background="'. $atts['button_hover_background'] .'"';
	}
	if ( $atts['button_hover_color'] ) {
		$button_data .= ' data-hover-color="'. $atts['button_hover_color'] .'"';
	}
	if ( $button_data ) {
		$button_classes .= ' wpex-data-hover';
	}
}

// Get excluded terms
if ( $atts['exclude_terms'] ) {
	$exclude_terms = preg_split( '/\,[\s]*/', $atts['exclude_terms'] );
} else {
	$exclude_terms = array();
}

// Define output var
$output = '';

// Get total count
$tcount = count( $terms );

// VC filter
$wrap_classes = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $wrap_classes, 'vcex_post_terms', $atts );

// Begin output
$output .= '<div class="'. esc_attr( $wrap_classes ) .'"'. vcex_get_unique_id( $atts['unique_id'] ) .'>';

	// Loop through terms
	foreach ( $terms as $term ) :

		// Skip excluded terms
		if ( in_array( $term->slug, $exclude_terms ) ) {
			continue;
		}

		// Open link if enabled
		if ( 'true' == $atts['archive_link'] ) {

			$output .= '<a href="'. get_term_link( $term, $atts['taxonomy'] ) .'" title="'. $term->name .'" class="'. esc_attr( $button_classes ) .'"'. $button_style . $button_data .'>';

		}

		// Open Span
		else {

			$output .= '<span class="'. esc_attr( $button_classes ) .'"'. $button_style . $button_data .'>';

		}

		// Display title
		$output .= $term->name;

		// Close link if enabled
		if ( 'true' == $atts['archive_link'] ) {

			$output .= '</a>';
			
		}

		// Close span
		else {
			$output .= '</span>';
		}

	endforeach;

// Close main wrapper
$output .= '</div>';

// Echo output
echo $output;