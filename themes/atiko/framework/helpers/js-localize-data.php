<?php
/**
 * Returns array of data for the global js wpexLocalize object
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.0
 *
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wpex_js_localize_data() {

	// Get Header Style and Mobile meny style
	$post_id         = wpex_get_current_post_id();
	$header_style    = wpex_header_style( $post_id );
	$mm_style        = wpex_header_menu_mobile_style();
	$mm_toggle_style = wpex_header_menu_mobile_toggle_style();
	$mm_breakpoint   = wpex_header_menu_mobile_breakpoint();

	// Create array
	$array = array(
		'isRTL'                     => is_rtl(),
		'mainLayout'                => wpex_site_layout(),
		'menuSearchStyle'           => wpex_header_menu_search_style(),
		'siteHeaderStyle'           => esc_attr( $header_style ),
		'megaMenuJS'                => true,
		'superfishDelay'            => 600,
		'superfishSpeed'            => 'fast',
		'superfishSpeedOut'         => 'fast',
		'hasMobileMenu'             => wpex_header_has_mobile_menu(),
		'mobileMenuBreakpoint'      => $mm_breakpoint ? $mm_breakpoint : '960',
		'mobileMenuStyle'           => esc_attr( $mm_style ),
		'mobileMenuToggleStyle'     => $mm_toggle_style,
		'localScrollUpdateHash'     => false,
		'localScrollHighlight'      => true,
		'localScrollSpeed'          => 1000,
		'localScrollEasing'         => 'easeInOutExpo',
		'scrollTopSpeed'            => 1000,
		'scrollTopOffset'           => 100,
		'carouselSpeed'		        => 150,
		'lightboxType'              => '',
		'customSelects'             => '.woocommerce-ordering .orderby, #dropdown_product_cat, .widget_categories select, .widget_archive select, #bbp_stick_topic_select, #bbp_topic_status_select, #bbp_destination_topic, .single-product .variations_form .variations select, .widget .dropdown_product_cat',
		'overlaysMobileSupport'     => true,
		'responsiveDataBreakpoints' => array(
			'tl' => '1024px',
			'tp' => '959px',
			'pl' => '767px',
			'pp' => '479px',
		),
	);

	/**** Header params ****/
	if ( 'disabled' != $header_style ) {

		// Sticky Header
		if ( wpex_has_sticky_header() ) {

			$array['hasStickyHeader'] = true;
			if ( $logo = wpex_sticky_header_logo_img() ) {
				$array['stickyheaderCustomLogo'] = esc_url( $logo );
				if ( $logo = wpex_sticky_header_logo_img_retina() ) {
					$array['stickyheaderCustomLogoRetina'] = esc_url( $logo );
				}
			}

			$array['stickyHeaderStyle']      = wpex_sticky_header_style();
			$array['hasStickyMobileHeader']  = wpex_get_mod( 'fixed_header_mobile' );
			$array['overlayHeaderStickyTop'] = 0;
			$array['stickyHeaderBreakPoint'] = 960;

			// Make sure sticky is always enabled if responsive is disabled
			if ( ! wpex_is_layout_responsive() ) {
				$array['hasStickyMobileHeader'] = true;
			}

			// Shrink sticky header > used for local-scroll offset
			if ( wpex_has_shrink_sticky_header() ) {
				$height = intval( wpex_get_mod( 'fixed_header_shrink_end_height' ) );
				$height = $height ? $height + 20 : 70;
				$array['shrinkHeaderHeight'] = $height;
			}

		}

		// Sticky Navbar
		if ( 'two' == $header_style || 'three' == $header_style || 'four' == $header_style ) {
			$enabled = wpex_get_mod( 'fixed_header_menu', true );
			$array['hasStickyNavbar'] = $enabled;
			if ( $enabled ) {
				$array['hasStickyNavbarMobile']  = wpex_get_mod( 'fixed_header_menu_mobile' );
				$array['stickyNavbarBreakPoint'] = 960;
			}
		}

		// Header five
		if ( 'five' == $header_style ) {
			$array['headerFiveSplitOffset'] = 1;
		}

		// WooCart
		if ( function_exists( 'wpex_header_menu_cart_style' ) ) {
			$array['wooCartStyle'] = wpex_header_menu_cart_style( 'menu_cart_style' );
		}

	} // End header params

	// Toggle mobile menu position
	if ( 'toggle' == $mm_style ) {
		$array['animateMobileToggle'] = true;
		if ( wpex_get_mod( 'fixed_header_mobile' ) ) {
			$mobileToggleMenuPosition = 'absolute'; // Must be absolute for sticky header
		} elseif ( 'fixedTopNav' != $mm_toggle_style && wpex_has_overlay_header() ) {
			if ( 'navbar' == $mm_toggle_style ) {
				$mobileToggleMenuPosition = 'afterself';
			} else {
				$mobileToggleMenuPosition = 'absolute';
			}
		} elseif ( 'outer_wrap_before' == wpex_get_mod( 'mobile_menu_navbar_position' ) && 'navbar' == $mm_toggle_style ) {
			$mobileToggleMenuPosition = 'afterself';
		} else {
			$mobileToggleMenuPosition = 'afterheader';
		}
		$array['mobileToggleMenuPosition'] = $mobileToggleMenuPosition;
	}

	// Sidr settings
	if ( 'sidr' == $mm_style ) {
		$sidr_side = wpex_get_mod( 'mobile_menu_sidr_direction' );
		$sidr_side = $sidr_side ? $sidr_side : 'left'; // Fallback is crucial
		$array['sidrSource']         = wpex_sidr_menu_source( $post_id );
		$array['sidrDisplace']       = wpex_get_mod( 'mobile_menu_sidr_displace', true ) ?  true : false;
		$array['sidrSide']           = $sidr_side;
		$array['sidrBodyNoScroll']   = false;
		$array['sidrSpeed']          = 300;
		$array['sidrDropdownTarget'] = 'arrow';
	}

	// Sticky topBar
	if ( ! wpex_vc_is_inline() && apply_filters( 'wpex_has_sticky_topbar', wpex_get_mod( 'top_bar_sticky' ) ) ) {
		$array['stickyTopBarBreakPoint'] = 960;
		$array['hasStickyTopBarMobile']  = wpex_get_mod( 'top_bar_sticky_mobile', true );
	}

	// Full screen mobile menu style
	if ( 'full_screen' == $mm_style ) {
		$array['fullScreenMobileMenuStyle'] = wpex_get_mod( 'full_screen_mobile_menu_style', 'white' );
	}

	// Contact form 7 preloader
	if ( defined( 'WPCF7_VERSION' ) ) {
		$array['altercf7Prealoader'] = true;
	}

	// @todo deprecate this filter and a new one that makes more sense?
	$array = apply_filters( 'wpex_localize_array', $array );

	// Return array
	return $array;

}