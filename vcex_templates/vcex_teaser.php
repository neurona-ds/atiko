<?php
/**
 * Visual Composer Teaser
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
if ( ! function_exists( 'vc_map_get_attributes' )
	|| ! function_exists( 'vc_shortcode_custom_css_class' )
	|| ! function_exists( 'vc_build_link' )
) {
	vcex_function_needed_notice();
	return;
}

// Define output var
$output = '';

// Get and extract shortcode attributes
extract( vc_map_get_attributes( 'vcex_teaser', $atts ) );

// Add main Classes
$wrap_classes = array( 'vcex-module', 'vcex-teaser' );
if ( $style ) {
	$wrap_classes[] = 'vcex-teaser-'. $style;
}
if ( $classes ) {
	$wrap_classes[] = vcex_get_extra_class( $classes );
}
if ( $visibility ) {
	$wrap_classes[] = $visibility;
}
if ( $css_animation && 'none' != $css_animation ) {
	$wrap_classes[] = vcex_get_css_animation( $css_animation );
}
if ( $hover_animation ) {
	$wrap_classes[] = wpex_hover_animation_class( $hover_animation );
	vcex_enque_style( 'hover-animations' );
}
if ( 'two' == $style ) {
	$wrap_classes[] = 'wpex-bg-gray';
	$wrap_classes[] = 'wpex-padding-20';
	$wrap_classes[] = 'wpex-bordered';
	$wrap_classes[] = 'wpex-rounded';
} elseif ( 'three' == $style ) {
	$wrap_classes[] = 'wpex-bg-gray';
	$wrap_classes[] = 'wpex-bordered';
} elseif ( 'four' == $style ) {
	$wrap_classes[] = 'wpex-bordered';
}
if ( $css ) {
	$wrap_classes[] = vc_shortcode_custom_css_class( $css );
}

// Add inline style for main div
$wrap_style = '';
if ( $text_align ) {
	$wrap_style .= 'text-align:'. $text_align .';';
}
if ( $padding && 'two' == $style ) {
	$wrap_style .= 'padding:'. $padding .';';
}
if ( $background && 'two' == $style ) {
	$wrap_style .= 'background:'. $background .';';
}
if ( $background && 'three' == $style && '' == $content_background ) {
	$wrap_style .= 'background:'. $background .';';
}
if ( $border_color ) {
	$wrap_style .= 'border-color:'. $border_color .';';
}
if ( $border_radius ) {
	$wrap_style .= 'border-radius:'. $border_radius .';';
}
if ( $wrap_style ) {
	$wrap_style = ' style="'. $wrap_style .'"';
}

// Media classes
$media_classes = 'vcex-teaser-media';
if ( 'three' == $style || 'four' == $style ) {
	$media_classes .= ' no-margin';
}

// Content classes
$content_classes  = 'vcex-teaser-content clr';
if ( 'three' == $style || 'four' == $style ) {
	$content_classes .= ' wpex-padding-20';
}

// Implode and apply filter to wrap classes
$wrap_classes = implode( ' ', $wrap_classes );
$wrap_classes = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $wrap_classes, 'vcex_teaser', $atts );

// Output shortcode
$output .= '<div class="'. esc_attr( $wrap_classes ) .'"'. vcex_get_unique_id( $unique_id ) . $wrap_style .'>';

	// Display video
	if ( $video ) {

		$output .= '<div class="'. $media_classes .' responsive-video-wrap">';

			$output .= wp_oembed_get( $video );
			
		$output .= '</div>';

	}

	// Generate url
	$url_output = '';
	if ( $url && '||' != $url ) {

		// Deprecated attributes
		$url_title = isset( $url_title ) ? $url_title : '';
		$url_target = isset( $url_target ) ? $url_target : '';

		// Get link field attributes
		$url_atts = vc_build_link( $url );
		if ( ! empty( $url_atts['url'] ) ) {
			$url        = isset( $url_atts['url'] ) ? $url_atts['url'] : $url;
			$url_title  = isset( $url_atts['title'] ) ? $url_atts['title'] : $url_title;
			$url_target = isset( $url_atts['target'] ) ? $url_atts['target'] : $url_target;
		}

		// Title fallback (shouldn't be an empty title)
		$url_title = $url_title ? $url_title : $heading;

		// Link classes
		$url_classes = 'wpex-td-none'; // Remove text decoration on link

		// Sanitize target
		if ( 'true' == $url_local_scroll ) {
			$url_classes .= ' local-scroll-link';
			$url_target = '';
		} elseif ( strpos( $url_target, 'blank' ) !== false ) {
			$url_target = ' target="_blank"';
		}

		$url_attrs = array(
			'href'   => esc_url( $url ),
			'title'  => esc_attr( $url_title ),
			'class'  => esc_attr( $url_classes ),
			'target' => $url_target,
			'rel'    => isset( $url_atts['rel'] ) ? $url_atts['rel'] : '',
		);

		$url_output = '<a '. wpex_parse_attrs( $url_attrs ) .'>';

	} // End url sanitization

	// Image
	if ( $image ) {

		// Image classes
		$image_classes = $media_classes;
		if ( $img_filter ) {
			$image_classes .= ' '. wpex_image_filter_class( $img_filter );
		}
		if ( $img_hover_style ) {
			$image_classes .= ' '. wpex_image_hover_classes( $img_hover_style );
		}
		if ( $img_align ) {
			$image_classes .= ' text'. $img_align;
		}
		if ( 'stretch' == $img_style ) {
			$image_classes .= ' stretch-image';
		}

		$output .= '<figure class="'. $image_classes .'">';

			// Open URl
			if ( $url_output ) {

				$output .= $url_output;

			}

				// Display image
				$output .= wpex_get_post_thumbnail( array(
					'attachment' => $image,
					'crop'       => $img_crop,
					'size'       => $img_size,
					'width'      => $img_width,
					'height'     => $img_height,
				) );

			if ( $url ) {
				$output .= '</a>';
			}

		$output .= '</figure>';

	} // End image output

	// Content
	if ( $content || $heading ) {

		// Content area
		$content_style = array(
			'margin'     => $content_margin,
			'padding'    => $content_padding,
			'background' => $content_background,
		);
		if ( $border_radius && ( 'three' == $style || 'four' == $style ) ) {
			$content_style['border_radius'] = $border_radius;
		}
		$content_style = vcex_inline_style( $content_style );

		$output .= '<div class="'. $content_classes .'"'. $content_style .'>';

			/// Heading
			if ( $heading ) {

				// Load custom font
				if ( $heading_font_family ) {
					wpex_enqueue_google_font( $heading_font_family );
				}

				// Classes
				$heading_attrs = array(
					'class' => 'vcex-teaser-heading',
				);

				// Heading style
				$heading_attrs['style'] = vcex_inline_style( array(
					'font_family'    => $heading_font_family,
					'color'          => $heading_color,
					'font_size'      => $heading_size,
					'margin'         => $heading_margin,
					'font_weight'    => $heading_weight,
					'letter_spacing' => $heading_letter_spacing,
					'text_transform' => $heading_transform,
				), false );

				// Get responsive data
				if ( $responsive_data = vcex_get_module_responsive_data( $heading_size, 'font_size' ) ) {
					$heading_attrs['data']   = $responsive_data['data'];
					$heading_attrs['class'] .= ' wpex-rcss '. $responsive_data['uniqid'];
				}

				// heading output
				$output .= '<' . $heading_type .' '. wpex_parse_attrs( $heading_attrs ) . '>';

					// Open URL
					if ( $url_output ) {

						$output .= $url_output;

					}

						$output .= wp_kses_post( $heading );

					// Close URL
					if ( $url ) {
						$output .= '</a>';
					}

				$output .= '</'. $heading_type .'>';

			} // End heading

			// Content
			if ( $content ) {

				$content_attrs = array(
					'class' => 'vcex-teaser-text clr'
				);
				
				$content_attrs['style'] = vcex_inline_style( array(
					'font_size'   => $content_font_size,
					'color'       => $content_color,
					'font_weight' => $content_font_weight,
				), false );

				// Get responsive data
				if ( $responsive_data = vcex_get_module_responsive_data( $content_font_size, 'font_size' ) ) {
					$content_attrs['data']   = $responsive_data['data'];
					$content_attrs['class'] .= ' wpex-rcss '. $responsive_data['uniqid'];
				}

				// Output content
				$output .= '<div ' . wpex_parse_attrs( $content_attrs ) . '>';

					$output .= wpex_the_content( $content );

				$output .= '</div>';

			} // End content output

		$output .= '</div>';

	} // End heading & content display

$output .= '</div>';

echo $output;