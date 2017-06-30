<?php
/**
 * Visual Composer Heading
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
if ( ! function_exists( 'vc_map_get_attributes' )
	|| ! function_exists( 'vc_shortcode_custom_css_class' )
	|| ! function_exists( 'vc_value_from_safe' )
) {
	vcex_function_needed_notice();
	return;
}

// Get and extract shortcode attributes
$atts = vc_map_get_attributes( 'vcex_heading', $atts );
extract( $atts );

// Get text
if ( 'post_title' == $source ) {
	$text = wpex_title( wpex_get_current_post_id() ); // Supports archives as well
} elseif( 'custom_field' == $source ) {
	$text = $custom_field ? get_post_meta( get_the_ID(), $custom_field, true ) : '';
} else {
	$text = trim( vc_value_from_safe( $text ) );
	$text = do_shortcode( $text );
}

// Apply filters
$text = apply_filters( 'vcex_heading_text', $text );

// Return if no heading
if ( empty( $text ) ) {
	return;
}

// Define& sanitize vars
$output           = $icon_left = $icon_right = $link_wrap_tag = '';
$heading_attrs    = array( 'class' => '' );
$wrap_classes     = array( 'vcex-module', 'vcex-heading' );
$tag              = $tag ? $tag : 'div';
$add_css_to_inner = ( 'plain' == $style ) ? $add_css_to_inner : false;

// Add classes to wrapper
if ( $style ) {
	$wrap_classes[] = 'vcex-heading-'. $style;
}
if ( $css_animation && 'none' != $css_animation ) {
	$wrap_classes[] = vcex_get_css_animation( $css_animation );
}
if ( $visibility ) {
	$wrap_classes[] = $visibility;
}
if ( $css && 'true' != $add_css_to_inner ) {
	$wrap_classes[] = vc_shortcode_custom_css_class( $css );
}
if ( $el_class ) {
	$wrap_classes[] = vcex_get_extra_class( $el_class );
}
if ( 'true' == $italic ) {
	$wrap_classes[] = 'wpex-italic';
}

// Load custom font
wpex_enqueue_google_font( $font_family );

// Get link data
$link = vcex_build_link( $link );
if ( $link && isset( $link['url'] ) ) {
	$heading_attrs['href']   = $link['url'];
	$heading_attrs['title']  = isset( $link['title'] ) ? $link['title'] : '';
	$heading_attrs['target'] = isset( $link['target'] ) ? $link['target'] : '';
	$heading_attrs['rel']    = isset( $link['rel'] ) ? $link['rel'] : '';
	$link_wrap_tag = $tag; // Add wrapper around link to keep tag (h2,h3...etc)
	$tag = 'a'; // Set tag to link
	if ( 'true' == $link_local_scroll ) {
		$wrap_classes[] = 'local-scroll-link';
	}
}

// Auto responsive Text
if ( 'true' == $responsive_text && $font_size ) {

	// Convert em font size to pixels
	if ( strpos( $font_size, 'em' ) !== false ) {
		$font_size = str_replace( 'em', '', $font_size );
		$font_size = $font_size * wpex_get_body_font_size();
	}

	// Convert em min-font size to pixels
	if ( strpos( $min_font_size, 'em' ) !== false ) {
		$min_font_size = str_replace( 'em', '', $min_font_size );
		$min_font_size = $min_font_size * wpex_get_body_font_size();
	}

	// Add wrap classes and data
	if ( $font_size && $min_font_size ) {
		$wrap_classes[] = 'wpex-responsive-txt';
		$heading_attrs['data-max-font-size'] = absint( $font_size );
		$min_font_size  = $min_font_size ? $min_font_size : '21px'; // 21px = default heading font size
		$min_font_size  = apply_filters( 'wpex_vcex_heading_min_font_size', $min_font_size );
		$heading_attrs['data-min-font-size'] = absint( $min_font_size );
	}

}

// Get responsive data
if ( $responsive_data = vcex_get_module_responsive_data( $atts ) ) {
	$heading_attrs['data'] = $responsive_data['data'];
	$wrap_classes[] = 'wpex-rcss '. $responsive_data['uniqid'];
}

// Color hover
if ( $color_hover ) {
	$wrap_classes[] = 'wpex-data-hover';
	$heading_attrs['data-hover-color'] = esc_attr( $color_hover );
}

if ( $background_hover ) {
	if ( ! isset( $wrap_classes['wpex-data-hover'] ) ) {
		$wrap_classes['wpex-data-hover'] = 'wpex-data-hover';
	}
	$wrap_classes[] = 'transition-all';
	$heading_attrs['data-hover-background'] = esc_attr( $background_hover );
}

if ( 'true' == $hover_white_text ) {
	$wrap_classes[] = 'wpex-hover-white-text';
}

// Inner attributes
$inner_attrs = array(
	'class' => 'vcex-heading-inner clr',
);

// Inner style
$inner_attrs['style'] = vcex_inline_style( array(
	'border_color' => $inner_bottom_border_color,
) );

// Inner CSS
if ( 'true' == $add_css_to_inner ) {
	$inner_attrs['class'] .= ' ' . vc_shortcode_custom_css_class( $css );
}

// Icon output
if ( $icon = vcex_get_icon_class( $atts, 'icon' ) ) {

	// Load font CSS file if needed
	vcex_enqueue_icon_font( $icon_type );

	$icon_output = wpex_parse_html( 'span', array(
		'class' => 'vcex-icon-wrap vcex-icon-position-'. $icon_position,
		'style' => vcex_inline_style( array(
			'color' => $icon_color,
		) )
	), '<span class="'. $icon .'"></span>' );

	// Add icon to heading
	if ( 'left' == $icon_position ) {
		$icon_left = $icon_output;
	} else {
		$icon_right = $icon_output;
	}

}

// Apply filters to classes
$wrap_classes = implode( ' ', $wrap_classes );
$wrap_classes = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $wrap_classes, 'vcex_heading', $atts );

// Add classes to attributes array
$heading_attrs['class'] = $wrap_classes;

// Add inline style
$heading_attrs['style'] = vcex_inline_style( array(
	'color'               => $color,
	'font_family'         => $font_family,
	'font_size'           => $font_size,
	'letter_spacing'      => $letter_spacing,
	'font_weight'         => $font_weight,
	'text_align'          => $text_align,
	'line_height'         => $line_height,
	'border_bottom_color' => $inner_bottom_border_color_main,
	'width'               => $width,
), false );

// Heading output
if ( $link_wrap_tag ) {

	$output .= '<'. $link_wrap_tag .' class="vcex-heading-link-wrap clr">';

}

$output .= '<'. $tag .' '. wpex_parse_attrs( $heading_attrs ) .'>';

	$output .= '<span '. wpex_parse_attrs( $inner_attrs ) . '>';

		$output .= $icon_left;

			$output .= $text;

		$output .= $icon_right;

	$output .= '</span>';

$output .= '</'. $tag .'>';

if ( $link_wrap_tag ) {

	$output .= '</'. $link_wrap_tag .'>';
	
}

// Echo heading
echo $output;