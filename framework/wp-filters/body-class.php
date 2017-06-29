<?php
/**
 * Adds custom classes to the body tag
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Add custom classes to body tag
function wpex_body_class( $classes ) {

	// Save some vars
	$post_id     = wpex_get_current_post_id();
	$main_layout = wpex_site_layout( $post_id );
	$post_layout = wpex_content_area_layout( $post_id );

	// RTL
	if ( is_RTL() ) {
		$classes[] = 'rtl';
	}

	// Customizer
	if ( is_customize_preview() ) {
		$classes[] = 'is_customize_preview';
	}
	
	// Main class
	$classes[] = 'wpex-theme';

	// Responsive
	if ( wpex_is_layout_responsive() ) {
		$classes[] = 'wpex-responsive';
	}

	// Skin => deprecated
	if ( function_exists( 'wpex_active_skin' ) ) {
		if ( 'base' != wpex_active_skin() ) {
			$classes[] = 'skin-' . wpex_active_skin();
		}
	}

	// Layout Style
	$classes[] = $main_layout .'-main-layout';

	// Check if the Visual Composer is being used on this page
	if ( wpex_post_has_vc_content( $post_id ) ) {
		$classes[] = 'has-composer';
	} else {
		$classes[] = 'no-composer';
	}

	// Live site class
	if ( ! wpex_vc_is_inline() ) {
		$classes[] = 'wpex-live-site';
	}

	// Boxed Layout dropshadow
	if ( 'boxed' == $main_layout && wpex_get_mod( 'boxed_dropdshadow' ) ) {
		$classes[] = 'wrap-boxshadow';
	}

	// Sidebar enabled
	if ( 'left-sidebar' == $post_layout || 'right-sidebar' == $post_layout ) {
		$classes[] = 'has-sidebar';
	}

	// Content layout
	if ( $post_layout ) {
		$classes[] = 'content-'. $post_layout;
	}

	// Site Header classes
	if ( ! wpex_has_header() ) {
		$classes[] = 'wpex-site-header-disabled';
	} else {
		$header_style = wpex_header_style();
		if ( 'six' == $header_style ) {
			$classes[] = 'wpex-has-vertical-header';
			if ( 'fixed' == wpex_get_mod( 'vertical_header_style' ) ) {
				$classes[] = 'wpex-fixed-vertical-header';
			}
		}
	}

	// Topbar
	if ( wpex_has_topbar() ) {
		$classes[] = 'has-topbar';
	}

	// Single Post cagegories
	if ( is_singular( 'post' ) ) {
		$cats = get_the_category( $post_id );
		foreach ( $cats as $cat ) {
			$classes[] = 'post-in-category-'. $cat->category_nicename;
		}
	}

	// Breadcrumbs
	if ( wpex_has_breadcrumbs( $post_id ) ) {
		$classes[] = 'has-breadcrumbs';
	}

	// Widget Icons
	if ( wpex_get_mod( 'has_widget_icons', true ) ) {
		$classes[] = 'sidebar-widget-icons';
	}

	// Overlay header style
	if ( wpex_has_overlay_header() ) {
		$classes[] = 'has-overlay-header';
	} else {
		$classes[] = 'hasnt-overlay-header';
	}

	// Footer reveal
	if ( wpex_footer_has_reveal() ) {
		$classes[] = 'footer-has-reveal';
	}

	// Fixed Footer - adds min-height to main wraper
	if ( wpex_get_mod( 'fixed_footer', false ) ) {
		$classes[] = 'wpex-has-fixed-footer';
	}

	// Disabled header
	if ( wpex_has_page_header() ) {
		if ( 'background-image' == wpex_page_header_style() ) {
			$classes[] = 'page-with-background-title';
		}
		if ( 'on' == get_post_meta( $post_id, 'wpex_disable_header_margin', true ) ) {
			$classes[] = 'no-header-margin';
		}
	} else {
		$classes[] = 'page-header-disabled';
	}

	// Page slider
	if ( wpex_post_has_slider( $post_id ) && $slider_position = wpex_post_slider_position( $post_id ) ) {
		$classes[]       = 'page-with-slider'; // Deprecated @todo remove this class
		$classes[]       = 'has-post-slider';
		$slider_position = str_replace( '_', '-', $slider_position );
		$classes[]       = 'post-slider-'. $slider_position;
	}

	// Font smoothing
	if ( wpex_get_mod( 'enable_font_smoothing' ) ) {
		$classes[] = 'smooth-fonts';
	}

	// Mobile menu toggle style
	if ( wpex_header_has_mobile_menu() ) {
		
		// Mobile menu toggle style
		$classes[] = 'wpex-mobile-toggle-menu-'. wpex_header_menu_mobile_toggle_style();

		// Mobile menu style
		if ( 'disabled' == wpex_header_menu_mobile_style() ) {
			$classes[] = 'mobile-menu-disabled';
		} else {
			$classes[] = 'has-mobile-menu';
		}

	}

	// Navbar inner span bg
	if ( wpex_get_mod( 'menu_link_span_background' ) ) {
		$classes[] = 'navbar-has-inner-span-bg';
	}

	// Check if avatars are enabled
	if ( is_singular() && ! get_option( 'show_avatars' ) ) {
		$classes[] = 'comment-avatars-disabled';
	}

	// Togglebar
	if ( 'inline' == wpex_togglebar_style() ) {
		$classes[] = 'togglebar-is-inline';
	}
	
	// Return classes
	return $classes;

}
add_filter( 'body_class', 'wpex_body_class' );