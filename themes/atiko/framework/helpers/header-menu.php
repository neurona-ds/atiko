<?php
/**
 * Site Header Menu Functions
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.0
 */

/*-------------------------------------------------------------------------------*/
/* [ Table of contents ]
/*-------------------------------------------------------------------------------*

	# General
	# Search
	# Mobile

/*-------------------------------------------------------------------------------*/
/* [ General ]
/*-------------------------------------------------------------------------------*/

/**
 * Check if header has a menu
 *
 * @since 4.0
 */
function wpex_header_has_menu() {

	// Return false if header is disabled or set to custom header
	if ( ! wpex_has_header() || wpex_has_custom_header() ) {
		return;
	}

	// Menu Location
	$menu_location = wpex_header_menu_location();

	// Custom menu
	$custom_menu = wpex_custom_menu();

	// Multisite global menu
	$ms_global_menu = apply_filters( 'wpex_ms_global_menu', false );

	// Display menu if defined
	if ( has_nav_menu( $menu_location ) || $custom_menu || $ms_global_menu ) {
		return true;
	}

}

/**
 * Returns correct header menu location
 *
 * @since 4.0
 */
function wpex_header_menu_location() {
	return apply_filters( 'wpex_main_menu_location', 'main_menu' );
}

/**
 * Returns correct menu classes
 *
 * @since 2.0.0
 */
function wpex_header_menu_classes( $return ) {

	// Define classes array
	$classes = array();

	// Get data
	$header_style = wpex_header_style();
	$has_overlay  = wpex_has_overlay_header();

	// Return wrapper classes
	if ( 'wrapper' == $return ) {

		// Add Header Style to wrapper
		$classes[] = 'navbar-style-'. $header_style;

		// Add the fixed-nav class if the fixed header option is enabled
		if ( wpex_get_mod( 'fixed_header', true )
			&& ( 'two' == $header_style || 'three' == $header_style || 'four' == $header_style )
		) {
			$classes[] = 'fixed-nav';
		}

		// Dropdown dropshadow
		if ( 'one' == $header_style || 'five' == $header_style || $has_overlay ) {
			$classes[] = 'wpex-dropdowns-caret';
		}

		// Flush Dropdowns
		if ( wpex_get_mod( 'menu_flush_dropdowns' )
			&& 'one' == $header_style
			&& ! $has_overlay
		) {
			$classes[] = 'wpex-flush-dropdowns';
		}

		// Add special class if the dropdown top border option in the admin is enabled
		if ( wpex_get_mod( 'menu_dropdown_top_border' ) ) {
			$classes[] = 'wpex-dropdown-top-border';
		}

		// Disable header two borders
		if ( 'two' == $header_style && wpex_get_mod( 'header_menu_disable_borders', false ) ) {
			$classes[] = 'no-borders';
		}

		// Center items
		if ( 'two' == $header_style && wpex_get_mod( 'header_menu_center', false ) ) {
			$classes[] = 'center-items';
		}

		// Add clearfix
		$classes[] = 'clr';

		// Set keys equal to vals
		$classes = array_combine( $classes, $classes );

		// Apply filters
		$classes = apply_filters( 'wpex_header_menu_wrap_classes', $classes );

	}

	// Inner Classes
	elseif ( 'inner' == $return ) {

		// Core
		$classes[] = 'navigation';
		$classes[] = 'main-navigation';
		$classes[] = 'clr';

		// Add the container div for specific header styles
		if ( in_array( $header_style, array( 'two', 'three', 'four' ) ) ) {
			$classes[] = 'container';
		}

		// Set keys equal to vals
		$classes = array_combine( $classes, $classes );

		// Apply filters
		$classes = apply_filters( 'wpex_header_menu_classes', $classes );

	}

	// Return
	if ( is_array( $classes ) ) {
		return implode( ' ', $classes );
	} else {
		return $return;
	}

}

/**
 * Custom menu walker
 *
 * @since 1.3.0
 */
if ( ! class_exists( 'WPEX_Dropdown_Walker_Nav_Menu' ) ) {
	class WPEX_Dropdown_Walker_Nav_Menu extends Walker_Nav_Menu {
		function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {

			// Define vars
			$id_field     = $this->db_fields['id'];
			$header_style = wpex_header_style();

			// Down Arrows
			if ( ! empty( $children_elements[$element->$id_field] ) && ( $depth == 0 ) ) {
				$element->classes[] = 'dropdown';
				if ( wpex_get_mod( 'menu_arrow_down' ) ) {
					$arrow_class = 'six' == $header_style ? 'fa-chevron-right' : 'fa-angle-down';
					$element->title .= ' <span class="nav-arrow top-level fa '. $arrow_class .'"></span>';
				}
			}

			// Right/Left Arrows
			if ( ! empty( $children_elements[$element->$id_field] ) && ( $depth > 0 ) ) {
				$element->classes[] = 'dropdown';
				if ( wpex_get_mod( 'menu_arrow_side', true ) ) {
					if ( is_rtl() ) {
						$element->title .= '<span class="nav-arrow second-level fa fa-angle-left"></span>';
					} else {
						$element->title .= '<span class="nav-arrow second-level fa fa-angle-right"></span>';
					}
				}
			}

			// Remove current menu item when using local-scroll class
			if ( in_array( 'local-scroll', $element->classes ) && in_array( 'current-menu-item', $element->classes ) ) {
				$key = array_search( 'current-menu-item', $element->classes );
				unset( $element->classes[$key] );
			}

			// Define walker
			Walker_Nav_Menu::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );

		}
	}
}

/**
 * Checks for custom menus.
 *
 * @since 1.3.0
 */
function wpex_custom_menu() {
	$menu = get_post_meta( wpex_get_current_post_id(), 'wpex_custom_menu', true );
	$menu = 'default' != $menu ? $menu : '';
	return apply_filters( 'wpex_custom_menu', $menu );
}

/**
 * Adds the search icon to the menu items
 *
 * @since 1.0.0
 */
function wpex_add_search_to_menu( $items, $args ) {

	// Only used on main menu
	if ( 'main_menu' != $args->theme_location ) {
		return $items;
	}

	// Get search style
	$search_style = wpex_header_menu_search_style();

	// Return if disabled
	if ( ! $search_style || 'disabled' == $search_style ) {
		return $items;
	}

	// Define classes
	$li_classes = 'search-toggle-li wpex-menu-extra';
	$a_classes  = 'site-search-toggle';

	// Get header style
	$header_style = wpex_header_style();

	// Get correct search icon class
	if ( 'overlay' == $search_style) {
		$a_classes .= ' search-overlay-toggle';
	} elseif ( 'drop_down' == $search_style ) {
		$a_classes .= ' search-dropdown-toggle';
	} elseif ( 'header_replace' == $search_style ) {
		$a_classes .= ' search-header-replace-toggle';
	}

	// Add search item to menu
	if ( class_exists( 'UberMenu' ) && apply_filters( 'wpex_add_search_toggle_ubermenu_classes', true ) ) {
		$li_classes .= ' ubermenu-item-level-0 ubermenu-item';
		$a_classes  .= ' ubermenu-target ubermenu-item-layout-default ubermenu-item-layout-text_only';
	}
	$items .= '<li class="'. $li_classes .'">';
		$items .= '<a href="#" class="'. $a_classes .'">';
			$items .= '<span class="link-inner">';
				$text = esc_html__( 'Search', 'total' );
				$text = apply_filters( 'wpex_header_search_text', $text );
				if ( 'six' == $header_style ) {
					$items .= '<span class="fa fa-search"></span>';
					$items .= '<span class="wpex-menu-search-text">'. $text .'</span>';
				} else {
					$items .= '<span class="wpex-menu-search-text">'. $text .'</span>';
					$items .= '<span class="fa fa-search" aria-hidden="true"></span>';
				}
			$items .= '</span>';
		$items .= '</a>';
	$items .= '</li>';

	// Return nav $items
	return $items;

}
add_filter( 'wp_nav_menu_items', 'wpex_add_search_to_menu', 11, 2 );

/*-------------------------------------------------------------------------------*/
/* [ Search ]
/*-------------------------------------------------------------------------------*/

/**
 * Check if current header menu style supports search icon
 *
 * @since 4.0
 */
function wpex_header_menu_supports_search() {
	if ( wpex_has_header() ) {
		return apply_filters( 'wpex_has_menu_search', true ); // Return true always now since 3.0.0
	}
}

/**
 * Returns correct header menu search style
 *
 * @since 4.0
 */
function wpex_header_menu_search_style() {

	// Return if not allowed
	if ( ! wpex_header_menu_supports_search() ) {
		return false;
	}

	// Get search style from Customizer
	$style = wpex_get_mod( 'menu_search_style', 'drop_down' );

	// Overlay header should use pop-up
	// @todo double check thi for the header overlay because some customers complain
	if ( 'disabled' != $style && ( wpex_has_overlay_header() || 'six' == wpex_header_style() ) ) {
		$style = 'overlay';
	}

	// Apply filters for advanced edits
	$style = apply_filters( 'wpex_menu_search_style', $style );

	// Sanitize output so it's not empty and return
	$style = $style ? $style : 'drop_down';

	// Return style
	return $style;

}

/*-------------------------------------------------------------------------------*/
/* [ Mobile ]
/*-------------------------------------------------------------------------------*/

/**
 * Return correct header menu mobile style
 *
 * @since 4.0
 */
function wpex_header_menu_mobile_style() {

	// Get and sanitize style
	$style = wpex_get_mod( 'mobile_menu_style' );

	/* Toggle style not supported when overlay header is enabled
	// Deprecated in 3.5.0
	if ( 'toggle' == $style && wpex_has_overlay_header() ) {
		//$style = 'sidr';
	}*/

	// Disable if responsive is disabled
	$style = wpex_is_layout_responsive() ? $style : 'disabled';

	// Sanitize => can't be empty
	$style = $style ? $style : 'sidr';

	// Apply filters and return
	return apply_filters( 'wpex_mobile_menu_style', $style );

}

/**
 * Check if mobile menu is enabled
 *
 * @since 4.0
 */
function wpex_header_has_mobile_menu() {

	// Always return false for custom header
	if ( wpex_has_custom_header() ) {
		return false;
	}

	// Check if enabled
	if ( ! wpex_is_layout_responsive() ) {
		$bool = false;
	} elseif ( 'disabled' == wpex_header_menu_mobile_style() ) {
		$bool = false;
	} elseif ( has_nav_menu( 'mobile_menu_alt' ) || wpex_header_has_menu() ) {
		$bool = true;
	} else {
		$bool = false;
	}

	// Apply filters and return
	return apply_filters( 'wpex_has_mobile_menu', $bool );

}

/**
 * Return correct header menu mobile toggle style
 *
 * @since 4.0
 */
function wpex_header_menu_mobile_toggle_style() {

	// Set to false if header builder is enabled or mobile menu is disabled
	if ( 'disabled' == wpex_header_menu_mobile_style() ) {
		return false;
	}

	// Get style
	$style = wpex_get_mod( 'mobile_menu_toggle_style' );

	// Sanitize => can't be empty
	$style = $style ? $style : 'icon_buttons';

	// Apply filters and return style
	return apply_filters( 'wpex_mobile_menu_toggle_style', $style );

}

/**
 * Return correct header menu mobile toggle style
 *
 * @since 4.0
 */
function wpex_header_menu_mobile_breakpoint() {
	return intval( apply_filters( 'wpex_header_menu_mobile_toggle_style', wpex_get_mod( 'mobile_menu_breakpoint' ) ) );
}

/**
 * Return sidr menu source
 *
 * @since 4.0
 */
function wpex_sidr_menu_source( $id = '' ) {

	// Define array of items
	$items = array();

	// Add close button
	$items['sidrclose'] = '#sidr-close';

	// Add mobile menu alternative if defined
	if ( has_nav_menu( 'mobile_menu_alt' ) ) {
		$items['nav'] = '#mobile-menu-alternative';
	}

	// If mobile menu alternative is not defined add main navigation
	else {
		$items['nav'] = '#site-navigation';
	}

	// Add search form
	if ( wpex_get_mod( 'mobile_menu_search', true ) ) {
		$items['search'] = '#mobile-menu-search';
	}

	// Apply filters for child theming
	$items = apply_filters( 'wpex_mobile_menu_source', $items );

	// Turn items into comma seperated list and return
	return implode( ', ', $items );

}