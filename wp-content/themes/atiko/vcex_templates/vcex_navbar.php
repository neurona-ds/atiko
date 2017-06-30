<?php
/**
 * Visual Composer Navbar
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

// Define output var
$output = '';

// Deprecated params
$atts['border_radius'] = ! empty( $atts['border_radius'] ) ? $atts['border_radius'] : '';

// Get shortcode attributes
$atts = vc_map_get_attributes( 'vcex_navbar', $atts );

// Return if no menu defined
if ( ! $atts['menu'] ) {
	return;
}

// Old style param fallback
if ( isset( $atts['style'] ) && 'simple' == $atts['style'] ) {
	$button_style = 'plain-text';
}

// Sanitize vars
$preset_design = $atts['preset_design'] ? $atts['preset_design'] : 'none';

// Hover animation
if ( $atts['hover_animation'] ) {
	$hover_animation = wpex_hover_animation_class( $atts['hover_animation'] );
	vcex_enque_style( 'hover-animations' );
}

// CSS class
if ( $atts['css'] ) {
	$css_class = vc_shortcode_custom_css_class( $atts['css'] );
} else {
	$css_class = '';
}

// Border radius
if ( $atts['border_radius'] ) {
	$atts['border_radius'] = vcex_get_border_radius_class( $atts['border_radius'] );
}

// Define wrap attributes
$wrap_attrs = array(
	'id' => $atts['unique_id'],
);

// Wrap style
$wrap_style = vcex_inline_style( array(
	'font_size'      => $atts['font_size'],
	'letter_spacing' => $atts['letter_spacing'],
	'font_family'    => $atts['font_family'],
), false );

// Load custom fonts
if ( $atts['font_family'] ) {
	wpex_enqueue_google_font( $atts['font_family'] );
}

// Classes
$wrap_classes = array( 'vcex-module', 'vcex-navbar', 'clr' );
$wrap_data    = array();
if ( $atts['filter_menu'] ) {
	$wrap_classes[] = 'vcex-filter-nav';
	$wrap_data[]    = 'data-filter-grid="'. esc_attr( $atts['filter_menu'] ) .'"';
	if ( 'fitRows' == $atts['filter_layout_mode'] ) {
		$wrap_data[] = 'data-layout-mode="fitRows"';
	}
	if ( $atts['filter_transition_duration'] ) {
		$wrap_data[] = 'data-transition-duration="'. esc_attr( $atts['filter_transition_duration'] ) .'"';
	}
}
if ( 'none' != $atts['preset_design'] ) {
	$wrap_classes[] = 'vcex-navbar-'. $atts['preset_design'];
}
if ( 'true' == $atts['sticky'] ) {
	$wrap_classes[] = 'vcex-navbar-sticky';
}
if ( $atts['classes'] ) {
	$wrap_classes[] = vcex_get_extra_class( $atts['classes'] );
}
if ( $atts['visibility'] ) {
	$wrap_classes[] = $atts['visibility'];
}
if ( $atts['align'] ) {
	$wrap_classes[] = 'align-'. $atts['align'];
}
if ( $atts['css_animation'] && 'none' != $atts['css_animation'] ) {
	$wrap_classes[] = vcex_get_css_animation( $atts['css_animation'] );
}
if ( $atts['wrap_css'] ) {
	$wrap_classes[] = vc_shortcode_custom_css_class( $atts['wrap_css'] );
}

// Responsive styles
if ( $responsive_data = vcex_get_module_responsive_data( $atts ) ) {
	$wrap_data[]    = $responsive_data['data'];
	$wrap_classes[] = 'wpex-rcss '. $responsive_data['uniqid'];
}

// Parse wrap attributes
$wrap_attrs['class'] = esc_attr( apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', $wrap_classes ), 'vcex_navbar', $atts ) );
$wrap_attrs['style'] = $wrap_style;
$wrap_attrs['data']  = $wrap_data;

// Begin output
$output .= '<nav ' . wpex_parse_attrs( $wrap_attrs ) . '>';

	// Inner classes
	$inner_classes = 'vcex-navbar-inner clr';

	if ( 'true' == $atts['full_screen_center'] ) {
		$inner_classes .= ' container';
	}

	$output .= '<div class="'. esc_attr( $inner_classes ) .'">';

		// Get menu object
		$menu = wp_get_nav_menu_object( $atts['menu'] );

		// If menu isn't empty display items
		if ( ! empty( $menu ) ) :

			// Get menu items
			$menu_items = wp_get_nav_menu_items( $menu->term_id );

			// Link style
			$link_style = vcex_inline_style( array(
				'background' => $atts['background'],
				'color'      => $atts['color'],
			), false );

			// Make sure we have menu items
			if ( $menu_items && is_array( $menu_items ) ) :

				// Define counter var
				$counter = 0;

				// Loop through menu items
				foreach ( $menu_items as $menu_item ) :

					// Define link style and reset for each item to prevent issues with active item
					$item_link_style = $link_style;

					// Define active class
					$is_active = false;

					// Add to counter
					$counter++;

					// Link Classes
					$link_classes = array( 'vcex-navbar-link' );
					if ( 'none' == $atts['preset_design'] ) {
						$link_classes[] = wpex_get_button_classes( $atts['button_style'], $atts['button_color'] );
						if ( $atts['button_layout'] ) {
							$link_classes[] = $atts['button_layout'];
						}
					}
					if ( $atts['hover_bg'] || $atts['hover_color'] ) {
						$link_classes[] = 'wpex-data-hover';
					}
					if ( $atts['font_weight'] ) {
						$link_classes[] = 'wpex-fw-'. $atts['font_weight'];
					}
					if ( 'true' == $atts['local_scroll'] && ! in_array( 'local-scroll', $menu_item->classes ) ) {
						$link_classes[] = 'local-scroll';
					}
					if ( $css_class ) {
						$link_classes[] = $css_class;
					}
					if ( $atts['hover_animation'] ) {
						$link_classes[] = $atts['hover_animation'];
					}
					if ( $atts['hover_bg'] ) {
						$link_classes[] = 'has-bg-hover';
					}
					if ( $atts['border_radius'] ) {
						$link_classes[] = $atts['border_radius'];
					}
					if ( $menu_item->classes ) {
						$link_classes = array_merge( $link_classes, $menu_item->classes );
					}

					// Add active item item for singular pages
					if ( is_singular()
						&& 'taxonomy' != $menu_item->type
						&& $menu_item->object_id == get_the_ID()
					) {
						$link_classes[] = 'active';
						$is_active = true;
					}

					// Add special classes for filtering by terms
					$data_filter = ''; // reset filter
					if ( $atts['filter_menu'] ) {

						// All link
						if ( '1' == $counter && '#' == $menu_item->url ) {
							$data_filter = '*';
							$link_classes[] = 'active';
						}

						// Taxonomy links
						if ( 'taxonomy' == $menu_item->type ) {
							$obj = $menu_item->object;
							if ( $obj ) {
								$prefix = $menu_item->object;
								if ( 'category' == $obj ) {
									$prefix = 'cat';
								} else {
									$parse_types = wpex_theme_post_types();
									foreach ( $parse_types as $type ) {
										if ( strpos( $prefix, $type ) !== false ) {
											$search  = array( $type .'_category', $type .'_tag' );
											$replace = array( 'cat', 'tag' );
											$prefix  = str_replace( $search, $replace, $prefix );
										}
									}
								}
								$data_filter = '.'. $prefix .'-'. $menu_item->object_id;
							}
						}

					}

					// Add active styles
					if ( $is_active ) {

						$item_link_style .= vcex_inline_style( array(
							'background' => $atts['hover_bg'],
							'color'      => $atts['hover_color'],
						), false );

					}

					// Link attributes
					$link_attrs = array(
						'href'                  => esc_url( $menu_item->url ),
						'title'                 => esc_attr( $menu_item->attr_title ? $menu_item->attr_title : $menu_item->title ),
						'class'                 => implode( ' ', array_filter( $link_classes, 'trim' ) ),
						'target'                => $menu_item->target,
						'style'                 => $item_link_style,
						'data-filter'           => $data_filter ? $data_filter : '',
						'data-hover-background' => $atts['hover_bg'] ? $atts['hover_bg'] : '',
						'data-hover-color'      => $atts['hover_color'] ? $atts['hover_color'] : '',
					);

					// Link item output
					$output .= wpex_parse_html( 'a', $link_attrs, '<span>' . $menu_item->title . '</span>' );

				endforeach; // End menu item loop

			endif; // End menu_items check

		endif; // End menu check

	$output .= '</div>';

$output .= '</nav>';

// Echo navbar
echo $output;