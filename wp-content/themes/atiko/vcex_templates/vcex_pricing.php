<?php
/**
 * Visual Composer Pricing
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

// Get and extract shortcode attributes
$atts = vc_map_get_attributes( 'vcex_pricing', $atts );
extract( $atts );

// Define output var
$output = '';

// Wrapper attributes
$wrap_attrs = array(
	'class' => 'vcex-module vcex-pricing',
	'id'    => vcex_get_unique_id( $unique_id ),
);

// Wrapper Classes
if ( 'yes' == $featured ) {
	$wrap_attrs['class'] .= ' featured';
}
if ( $css_animation && 'none' != $css_animation ) {
	$wrap_attrs['class'] .= ' '. vcex_get_css_animation( $css_animation );
}
if ( $el_class ) {
	$wrap_attrs['class'] .= ' '. vcex_get_extra_class( $el_class );
}
if ( $visibility ) {
	$wrap_attrs['class'] .= ' '. $visibility;
}
if ( $hover_animation ) {
	$wrap_attrs['class'] .= ' '. wpex_hover_animation_class( $hover_animation );
	vcex_enque_style( 'hover-animations' );
}
if ( $css ) {
	$wrap_attrs['class'] .= ' '. vc_shortcode_custom_css_class( $css );
}

// Apply filters to wrap class
$wrap_attrs['class'] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $wrap_attrs['class'], 'vcex_pricing', $atts );

// Start output
$output .='<div '. wpex_parse_attrs( $wrap_attrs ) . '>';

	// Display plan
	if ( $plan ) {

		$plan_attrs = array(
			'class' => 'vcex-pricing-header clr'
		);

		$plan_attrs['style'] = vcex_inline_style( array(
			'margin'         => $plan_margin,
			'padding'        => $plan_padding,
			'background'     => $plan_background,
			'color'          => $plan_color,
			'font_size'      => $plan_size,
			'font_weight'    => $plan_weight,
			'letter_spacing' => $plan_letter_spacing,
			'border'         => $plan_border,
			'text_transform' => $plan_text_transform,
			'font_family'    => $plan_font_family,
		), false );

		wpex_enqueue_google_font( $plan_font_family );

		if ( $responsive_data = vcex_get_module_responsive_data( $plan_size, 'font_size' ) ) {
			$plan_attrs['data'] = $responsive_data['data'];
			$plan_attrs['class'] .= ' wpex-rcss '. $responsive_data['uniqid'];
		}

		$output .= wpex_parse_html( 'div', $plan_attrs, wp_kses_post( do_shortcode( $plan ) ) );

	}

	// Display cost
	if ( $cost ) {

		$cost_attrs = array(
			'class' => 'vcex-pricing-ammount'
		);

		$cost_attrs['style'] = vcex_inline_style( array(
			'color'       => $cost_color,
			'font_size'   => $cost_size,
			'font_weight' => $cost_weight,
		), false );

		if ( $responsive_data = vcex_get_module_responsive_data( $cost_size, 'font_size' ) ) {
			$cost_attrs['data'] = $responsive_data['data'];
			$cost_attrs['class'] .= ' wpex-rcss '. $responsive_data['uniqid'];
		}

		$cost_wrap_style = vcex_inline_style( array(
			'background'  => $cost_background,
			'padding'     => $cost_padding,
			'border'      => $cost_border,
			'font_family' => $cost_font_family,
		) );

		wpex_enqueue_google_font( $cost_font_family );

		$output .= '<div class="vcex-pricing-cost clr"' . $cost_wrap_style . '>';

			$output .= '<div '. wpex_parse_attrs( $cost_attrs ) . '>';

				$output .= do_shortcode( wp_kses_post( $cost ) );

			$output .= '</div>';

			// Per section
			if ( $per ) {

				$per_attrs = array(
					'class' => 'vcex-pricing-per'
				);

				$per_attrs['style'] = vcex_inline_style( array(
					'display'        => $per_display,
					'font_size'      => $per_size,
					'color'          => $per_color,
					'font_weight'    => $per_weight,
					'text_transform' => $per_transform,
					'font_family'    => $per_font_family
				), false );

				wpex_enqueue_google_font( $per_font_family );

				if ( $responsive_data = vcex_get_module_responsive_data( $per_size, 'font_size' ) ) {
					$per_attrs['data'] = $responsive_data['data'];
					$per_attrs['class'] .= ' wpex-rcss '. $responsive_data['uniqid'];
				}

				$output .= '<div ' . wpex_parse_attrs( $per_attrs ) . '>';

					$output .= do_shortcode( wp_kses_post( $per ) );

				$output .= '</div>';
			}

		$output .= '</div>';

	}

	// Display content
	if ( $content ) {

		$content_attrs = array(
			'class' => 'vcex-pricing-content clr',
		);

		$content_attrs['style'] = vcex_inline_style( array(
			'padding'     => $features_padding,
			'background'  => $features_bg,
			'border'      => $features_border,
			'color'       => $font_color,
			'font_size'   => $font_size,
			'font_family' => $font_family
		), false );

		wpex_enqueue_google_font( $font_family );

		if ( $responsive_data = vcex_get_module_responsive_data( $font_size, 'font_size' ) ) {
			$content_attrs['data'] = $responsive_data['data'];
			$content_attrs['class'] .= ' wpex-rcss '. $responsive_data['uniqid'];
		}

		$output .= '<div ' . wpex_parse_attrs( $content_attrs ) . '>';

			$output .= do_shortcode( wp_kses_post( $content ) );
			
		$output .= '</div>';

	}
	
	// Display button
	if ( $button_url || $custom_button ) {

		// Set button url to false if custom_button isn't empty
		$button_url = $custom_button ? false : $button_url;

		// Define button attributes
		$button_attrs = array();

		// Button Wrap Style
		$button_wrap_style = vcex_inline_style( array(
			'padding'     => $button_wrap_padding,
			'border'      => $button_wrap_border,
			'background'  => $button_wrap_bg,
			'font_family' => $button_font_family,
		) );

		wpex_enqueue_google_font( $button_font_family );

		// VCEX button styles
		if ( $button_url ) {

			$button_title = $button_target = $button_rel = '';

			$button_url_temp = $button_url;
			$button_url      = vcex_get_link_data( 'url', $button_url_temp );

			if ( $button_url ) {

				$button_title  = vcex_get_link_data( 'title', $button_url_temp );
				$button_target = vcex_get_link_data( 'target', $button_url_temp );
				$button_rel    = vcex_get_link_data( 'rel', $button_url_temp );

			}

			// Get correct icon classes
			$button_icon_left  = vcex_get_icon_class( $atts, 'button_icon_left' );
			$button_icon_right = vcex_get_icon_class( $atts, 'button_icon_right' );

			if ( $button_icon_left || $button_icon_right ) {
				vcex_enqueue_icon_font( $icon_type );
			}

			// Button Classes
			$button_classes = wpex_get_button_classes( $button_style, $button_style_color );
			if ( 'true' == $button_local_scroll ) {
				$button_classes .= ' local-scroll-link'; 
			}
			if ( $button_transform ) {
				$button_classes .= ' text-transform-'. $button_transform;
			}
			if ( $button_hover_bg_color || $button_hover_color ) {
				$button_classes .= ' wpex-data-hover';
			}

			// Button Data attributes
			$button_data = array();
			if ( $button_hover_bg_color ) {
				$button_data[] = ' data-hover-background="'. $button_hover_bg_color .'"';
			}
			if ( $button_hover_color ) {
				$button_data[] = ' data-hover-color="'. $button_hover_color .'"';
			}
			if ( $button_size && $responsive_data = vcex_get_module_responsive_data( $button_size, 'font_size' ) ) {
				$button_data[] = $responsive_data['data'];
				$button_classes .= ' wpex-rcss '. $responsive_data['uniqid'];
			}

			// Button Style
			$border_color = ( 'outline' == $button_style ) ? $button_color : '';
			$button_style = vcex_inline_style( array(
				'background'     => $button_bg_color,
				'color'          => $button_color,
				'letter_spacing' => $button_letter_spacing,
				'font_size'      => $button_size,
				'padding'        => $button_padding,
				'border_radius'  => $button_border_radius,
				'font_weight'    => $button_weight,
				'border_color'   => $border_color,
				'text_transform' => $button_transform,
			), false );

			// Add parsed button attributes to array
			$button_attrs['class']  = $button_classes;
			$button_attrs['href']   = esc_url( $button_url );
			$button_attrs['title']  = esc_attr( $button_title );
			$button_attrs['data']   = $button_data;
			$button_attrs['target'] = $button_target;
			$button_attrs['style']  = $button_style;
			$button_attrs['rel']    = $button_rel;

		}

		// Extra checks needed due to button_url sanitization
		if ( $button_url || $custom_button ) {

			$output .= '<div class="vcex-pricing-button"'. $button_wrap_style .'>';

				if ( $custom_button = vcex_parse_textarea_html( $custom_button ) ) {

					$output .= do_shortcode( $custom_button );

				} elseif ( $button_url ) {

					$output .= '<a ' . wpex_parse_attrs( $button_attrs ) . '>';
						
						if ( $button_icon_left ) {

							$output .= '<span class="vcex-icon-wrap left"><span class="'. $button_icon_left .'"></span></span>';
							
						}

						$output .= $button_text;

						if ( $button_icon_right ) {

							$output .= '<span class="vcex-icon-wrap right"><span class="'. $button_icon_right .'"></span></span>';
						}

					$output .= '</a>';
					
				}

			$output .= '</div>';

		}

	} // End button checks

$output .= '</div>';

echo $output;