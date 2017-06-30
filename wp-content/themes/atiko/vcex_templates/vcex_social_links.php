<?php
/**
 * Visual Composer Social Links
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

// Get and extract shortcode attributes
extract( vc_map_get_attributes( 'vcex_social_links', $atts ) );

// Get social profiles array | Used for fallback method and to grab icon styles
$social_profiles = (array) vcex_social_links_profiles();

// Social profile array can't be empty
if ( ! $social_profiles ) {
	return;
}

// Define output var
$output = '';

// New method since 3.5.0 | must check $atts value due to fallback and default var
if ( ! empty( $atts['social_links'] ) ) {
	$social_links = (array) vc_param_group_parse_atts( $social_links );
	$loop = array();
	foreach ( $social_links as $key => $val ) {
		$loop[$val['site']] = isset( $val['link'] ) ? $val['link'] : '';
	}
} else {
	$loop = $social_profiles;
}

// Wrap attributes
$wrap_attrs = array(
	'id' => $unique_id,
	'data' => '',
);

// Wrap classes
$wrap_classes = array( 'vcex-module' );
if ( $style ) {
	$wrap_classes[] = 'wpex-social-btns vcex-social-btns';
} else {
	$wrap_classes[] = 'vcex-social-links';
}
if ( $align ) {
	$wrap_classes[] = 'text'. $align;
}
if ( $visibility ) {
	$wrap_classes[] = $visibility;
}
if ( $css_animation && 'none' != $css_animation ) {
	$wrap_classes[] = vcex_get_css_animation( $css_animation );
}
if ( $classes ) {
	$wrap_classes[] = vcex_get_extra_class( $classes );
}

// Wrap style
$wrap_style = vcex_inline_style( array(
	'color'         => $color,
	'font_size'     => $size,
	'border_radius' => $border_radius,
), false );

// Link style
$link_style = vcex_inline_style( array(
	'width'       => $width,
	'height'      => $height,
	'line_height' => $height ? intval( $height ) .'px' : '',
) );

// Link Attributes
$attributes = '';
if ( $link_style ) {
	$attributes .= $link_style;
}
if ( 'blank' == $link_target || '_blank' == $link_target ) {
	$attributes .= ' target="_blank"';
}
if ( $hover_bg ) {
	$attributes .= ' data-hover-background="'. $hover_bg .'"';
}
if ( $hover_color ) {
	$attributes .= ' data-hover-color="'. $hover_color .'"';
}

// Link Classes
$a_classes = array();
if ( $style ) {
	$a_classes[] = wpex_get_social_button_class( $style );
} else {
	$a_classes[] = 'vcex-social-link';
}
if ( $width || $height ) {
	$a_classes[] = 'no-padding';
}
if ( $hover_bg || $hover_color ) {
   $a_classes[] = ' wpex-data-hover';
}
if ( $hover_animation ) {
	$a_classes[] = wpex_hover_animation_class( $hover_animation );
	vcex_enque_style( 'hover-animations' );
}
if ( $css ) {
	$a_classes[] = vc_shortcode_custom_css_class( $css );
}

// Responsive settings
if ( $responsive_data = vcex_get_module_responsive_data( $size, 'font_size' ) ) {
	$wrap_attrs['data'] .= ' '. $responsive_data['data'];
	$wrap_classes[] = 'wpex-rcss '. $responsive_data['uniqid'];
}

// Add attributes to array
$wrap_attrs['class'] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', $wrap_classes ), 'vcex_social_links', $atts );
$wrap_attrs['style'] = $wrap_style;

// Begin output
$output .= '<div ' . wpex_parse_attrs(  $wrap_attrs ) . '>';

	// Loop through social profiles
	if ( $loop ) {

		foreach ( $loop as $key => $val ) {

			// Sanitize classname
			$profile_class = $key;
			$profile_class = 'googleplus' == $key ? 'google-plus' : $key;

			// Get URL
			if ( empty( $atts['social_links'] ) ) {
				$url = isset( $atts[$key] ) ? $atts[$key] : '';
			} else {
				$url = $val;
			}

			// Link output
			if ( $url ) {
				$output .= '<a href="'. esc_url( $url ) .'" class="'. implode( ' ', $a_classes ) .' wpex-'. $profile_class .'"'. $attributes .'><span class="'. $social_profiles[$key]['icon_class'] .'"></span></a>';
			}

		}
	}

$output .= '</div>';

echo $output;