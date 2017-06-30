<?php
/**
 * Visual Composer Icon Box
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
if ( ! function_exists( 'vc_map_get_attributes' ) || ! function_exists( 'vc_shortcode_custom_css_class' ) ) {
	vcex_function_needed_notice();
	return;
}

// FALLBACK VARS => NEVER REMOVE!!
$padding          = isset( $atts['padding'] ) ? $atts['padding'] : '';
$background       = isset( $atts['background'] ) ? $atts['background'] : '';
$background_image = isset( $atts['background_image'] ) ? $atts['background_image'] : '';
$margin_bottom    = isset( $atts['margin_bottom'] ) ? $atts['margin_bottom'] : '';
$border_color     = isset( $atts['border_color'] ) ? $atts['border_color'] : '';

// Get and extract shortcode attributes
$atts = vc_map_get_attributes( 'vcex_icon_box', $atts );
extract( $atts );

// Sanitize data & declare main vars
$output           = '';
$url              = esc_url( $url );
$outer_wrap_class = array( 'vcex-icon-box-css-wrap' );
$clickable_boxes  = array( 'four', 'five', 'six' );
$url_wrap         = in_array( $style, $clickable_boxes ) ? 'true' : $url_wrap;
$icon             = $image ? '' : vcex_get_icon_class( $atts, 'icon' );
$heading_type     = $heading_type ? $heading_type : 'h2';

// Check if we should have an outer wrap
$has_outer_wrap = ( $width || ( $css && in_array( $style, array( 'one', 'seven' ) ) ) ) ? true : false;

// Icon functions
if ( $icon ) {

	// Load icon family CSS
	vcex_enqueue_icon_font( $icon_type );

	// Icon Style
	$icon_style = array();
	$icon_style['color']         = $icon_color;
	$icon_style['width']         = $icon_width;
	$icon_style['font_size']     = $icon_size;
	$icon_style['border_radius'] = $icon_border_radius;
	$icon_style['background']    = $icon_background;
	$icon_style['height']        = $icon_height;
	if ( $icon_height ) {
		$icon_style['line_height']   = intval( $icon_height ) .'px';
	}

	if ( $icon_bottom_margin && in_array( $style, array( 'two', 'three', 'four', 'five', 'six' ) ) ) {
		$icon_style['margin_bottom'] = $icon_bottom_margin;
	}

	// Convert icon style array to inline style
	$icon_style = vcex_inline_style( $icon_style );

	// Icon Classes
	$icon_classes = array( 'vcex-icon-box-icon' );
	if ( $icon_background ) {
		$icon_classes['vcex-icon-box-w-bg'] = 'vcex-icon-box-w-bg';
	}
	if ( $icon_width || $icon_height ) {
		if ( $icon_height ) {
			unset( $icon_classes['vcex-icon-box-w-bg'] );
		}
		$icon_classes[] = 'no-padding';
	}
	$icon_classes = implode( ' ', $icon_classes );

}

// Main Classes
$wrap_classes = array( 'vcex-module', 'vcex-icon-box', 'clr' );
if ( $style ) {
	$wrap_classes[] = 'vcex-icon-box-'. $style;
}
if ( empty( $icon ) && empty( $image ) ) {
	$wrap_classes[] = 'vcex-icon-box-wo-icon';
}
if ( $url && 'true' == $url_wrap ) {
	$wrap_classes[] = 'vcex-icon-box-link-wrap';
}
if ( $alignment ) {
	$wrap_classes[] = 'align'. $alignment;
}
if ( $icon_background ) {
	$wrap_classes[] = 'vcex-icon-box-w-bg';
}
if ( 'true' == $hover_white_text ) {
	$wrap_classes['wpex-hover-white-text'] = 'wpex-hover-white-text';
	$outer_wrap_class[] = 'wpex-hover-white-text';
}
if ( $hover_animation ) {
	if ( $css && in_array( $style, array( 'one', 'seven' ) ) ) {
		$outer_wrap_class[] = wpex_hover_animation_class( $hover_animation );
	} else {
		$wrap_classes[] = wpex_hover_animation_class( $hover_animation );
	}
	vcex_enque_style( 'hover-animations' );
}
if ( ! $hover_animation && $hover_background ) {
	$wrap_classes[] = 'animate-all-hover';
	$outer_wrap_class[] = 'animate-bg-hover';
}
if ( $css_animation ) {
	if ( $css && in_array( $style, array( 'one', 'seven' ) ) ) {
		$outer_wrap_class[] = vcex_get_css_animation( $css_animation );
	} else {
		$wrap_classes[] = vcex_get_css_animation( $css_animation );
	}
}
if ( $classes ) {
	$wrap_classes[] = vcex_get_extra_class( $classes );
}
if ( $visibility ) {
	$wrap_classes[] = $visibility;
}
if ( $css ) {
	$css_class = vc_shortcode_custom_css_class( $css );
	if ( in_array( $style, array( 'one', 'seven' ) ) ) {
		$outer_wrap_class[] = $css_class;
	} else {
		$wrap_classes[] = $css_class;
	}
}

// Container Style
$wrapper_style = array();
if ( $border_radius && ! $has_outer_wrap ) {
	$wrapper_style['border_radius'] = $border_radius;
}
if ( 'six' == $style && $icon_color ) {
	$wrapper_style['color'] = $icon_color;
}
if ( 'one' == $style && $container_left_padding ) {
	$wrapper_style['padding_left'] = $container_left_padding;
}
if ( 'seven' == $style && $container_right_padding ) {
	$wrapper_style['padding_right'] = $container_right_padding;
}

// Fallback styles if $css is empty
if ( ! $css ) {
	if ( $padding ) {
		$wrapper_style['padding'] = $padding;
	}
	if ( 'four' == $style && $border_color ) {
		$wrapper_style['border_color'] = $border_color;
	}
	if ( 'six' == $style && $icon_background && '' === $background ) {
		$wrapper_style['background_color'] = $icon_background;
	}
	if ( $background && in_array( $style, $clickable_boxes ) ) {
		$wrapper_style['background_color'] = $background;
	}
	if ( $background_image && in_array( $style, $clickable_boxes ) ) {
		$background_image = wp_get_attachment_url( $background_image );
		$wrapper_style['background_image'] = $background_image;
		$wrap_classes[] = 'vcex-background-'. $background_image_style;
	}
	if ( $margin_bottom ) {
		$wrapper_style['margin_bottom'] = $margin_bottom;
	}
}

// Wrapper data
$wrapper_data = array();
if ( $hover_background ) {
	$wrapper_data[] = 'data-hover-background="'. $hover_background .'"';
}
if ( $hover_background ) {
	$wrap_classes['wpex-data-hover'] = 'wpex-data-hover';
	$outer_wrap_class[] = 'wpex-data-hover';
}

// Content classes
$content_classes = 'vcex-icon-box-content clr';

// Content style
$content_style = vcex_inline_style( array(
	'color'     => $font_color,
	'font_size' => $font_size,
) );

// Get responsive data
if ( $content_responsive_font_size = vcex_get_module_responsive_data( $font_size, 'font_size' ) ) {
	$content_data = $content_responsive_font_size['data'];
	$content_classes .= ' wpex-rcss '. $content_responsive_font_size['uniqid'];
} else {
	$content_data = '';
}

// Link data
if ( $url ) {

	$url_classes = '';
	if ( 'true' != $url_wrap ) {
		$url_classes = 'vcex-icon-box-link';
	}
	if ( 'local' == $url_target ) {
		$url_classes .= ' local-scroll-link';
	} elseif ( '_blank' == $url_target ) {
		$url_target = ' target="_blank"';
	} else {
		$url_target = '';
	}
	if ( $url_rel ) {
		$url_rel = ' rel="'. $url_rel .'"';
	}

}

// Heading style
if ( $heading ) {

	$heading_classes = 'vcex-icon-box-heading';
	$heading_data    = '';

	if ( $heading_font_family ) {
		wpex_enqueue_google_font( $heading_font_family );
	}

	$heading_style = vcex_inline_style( array(
		'font_family'    => $heading_font_family,
		'font_weight'    => $heading_weight,
		'color'          => $heading_color,
		'font_size'      => $heading_size,
		'letter_spacing' => $heading_letter_spacing,
		'margin_bottom'  => $heading_bottom_margin,
		'text_transform' => $heading_transform,
	) );

	if ( $heading_responsive_font_size = vcex_get_module_responsive_data( $heading_size, 'font_size' ) ) {
		$heading_data = $heading_responsive_font_size['data'];
		$heading_classes .= ' wpex-rcss '. $heading_responsive_font_size['uniqid'];
	}

}

// Open new wrapper for icon style 1
if ( $has_outer_wrap ) {

	// Outer wrap Data
	$outer_wrap_data = '';

	// Outer wrap Style
	$outer_wrap_style = vcex_inline_style( array(
		'width'         => $width,
		'border_radius' => $border_radius,
	) );

	// Outer wrap CSS
	if ( in_array( $style, array( 'one', 'seven' ) ) ) {

		// Remove wrapper hover
		if ( isset( $wrap_classes['wpex-data-hover'] ) ) {
			unset( $wrap_classes['wpex-data-hover'] );
		}
		if ( isset( $wrap_classes['wpex-hover-white-text'] ) ) {
			unset( $wrap_classes['wpex-hover-white-text'] );
		}

		// Add hover animations to css div
		if ( $hover_background ) {
			$outer_wrap_data = ' data-hover-background="'. $hover_background .'"';
		}

	}

	// Convert wrapper classes to string
	$outer_wrap_class = implode( ' ', $outer_wrap_class );

	// Outer wrap open
	$output .= '<div class="'. $outer_wrap_class .'"'. $outer_wrap_data . $outer_wrap_style .'>';

}

// Convert arrays to strings
$wrap_classes  = implode( ' ', $wrap_classes );
$wrapper_data  = $wrapper_data ? ' '. implode( ' ', $wrapper_data ) : '';
$wrapper_style = vcex_inline_style( $wrapper_style );

// Apply filters
$wrap_classes = trim( apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $wrap_classes, 'vcex_icon_box', $atts ) );

// Open link tag if url and url_wrap are defined
if ( $url && 'true' == $url_wrap ) {

	if ( 'local' == $url_target ) {
		$wrap_classes .= ' local-scroll-link';
	}

	$output .= '<a href="'. esc_url( $url ) .'" title="'. esc_attr( $heading ) .'" class="'. $wrap_classes .'"'
		. vcex_get_unique_id( $unique_id )
		. $wrapper_style
		. $url_target
		. $url_rel
		. $wrapper_data;
	$output .= '>';

// Open icon box with standard div
} else {

	$output .= '<div class="'. $wrap_classes .'"'
		. vcex_get_unique_id( $unique_id )
		. $wrapper_style
		. $wrapper_data;
	$output .= '>';

}

	// Open link if url is defined and the entire wrapper isn't a link
	if ( $url && 'true' != $url_wrap ) {

		$output .= '<a href="'. esc_url( $url ) .'" title="'. esc_attr( $heading ) .'" class="'. $url_classes .'"'
			. $url_target
			. $url_rel;
		$output .= '>';

	}
	
	// Display Image Icon Alternative
	if ( $image ) {

		// Image style
		$image_style = vcex_inline_style( array(
			'width'         => $image_width,
			'margin_bottom' => $image_bottom_margin,
		), false );

		$output .= wpex_get_post_thumbnail( array(
			'size'       => 'wpex-custom',
			'attachment' => $image,
			'alt'        => $heading,
			'width'      => $image_width,
			'height'     => $image_height,
			'crop'       => 'center-center',
			'style'      => $image_style,
			'class'      => 'vcex-icon-box-image',
		) );

	// Display Icon
	} elseif ( $icon ) {

		$output .= '<div class="'. $icon_classes .'"'. $icon_style .'>';

			// Display alternative icon
			if ( $icon_alternative_classes ) {

				$output .= '<span class="'. esc_attr( $icon_alternative_classes ) .'"></span>';

			// Display theme supported icon
			} else {

				$output .= '<span class="'. esc_attr( $icon ) .'"></span>';

			}

		$output .= '</div>';

	}
	
	// Display heading if defined
	if ( $heading ) {

		$output .= '<' . $heading_type . ' class="' . $heading_classes . '"' . $heading_style . $heading_data . '>';

			$output .= $heading;

		$output .= '</' . $heading_type . '>';

	} // End heading

	// Close link around heading and icon
	if ( $url && 'true' != $url_wrap ) {
		$output .= '</a>';
	}

	// Display content if defined
	if ( $content ) {

		$output .= '<div class="' .  $content_classes . '"' . $content_style . $content_data . '>';

			$output .= wpex_the_content( $content );

		$output .= '</div>';

	} // End content

// Close outer link wrap
if ( $url && 'true' == $url_wrap ) :

	$output .= '</a>';

// Close outer div wrap
else :

	$output .= '</div>';

endif;

// Close css wrapper for icon style one
if ( $has_outer_wrap ) {
	$output .= '</div>';
}

echo $output;