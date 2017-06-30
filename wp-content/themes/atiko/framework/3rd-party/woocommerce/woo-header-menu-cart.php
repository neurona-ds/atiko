<?php
/**
 * WooCommerce menu cart functions.
 *
 * @package Total WordPress Theme
 * @subpackage WooCommerce
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get correct style for WooCommerce menu cart style
 *
 * @since 4.0
 */
function wpex_header_menu_cart_style() {

	// Return if disabled completely in Customizer
	if ( 'disabled' == wpex_get_mod( 'woo_menu_icon_display', 'icon_count' ) ) {
		return false;
	}

	// If not disabled get style from settings
	else {

		// Get Menu Icon Style
		$style = wpex_get_mod( 'woo_menu_icon_style', 'drop_down' );

		// Overlay header and header style six should use popup
		// @todo - double check for overlay header since some customers have complained is it needed?
		if ( wpex_has_overlay_header() || 'six' == wpex_header_style() ) {
			$style = 'overlay';
		}

		// Return click style for these pages
		if ( is_cart() || is_checkout() ) {
			$style = 'custom-link';
		}

	}

	// Apply filters for child theme mods
	$style = apply_filters( 'wpex_menu_cart_style', $style );

	// Sanitize output so it's not empty and check for deprecated 'drop-down' style
	if ( 'drop-down' == $style || ! $style ) {
		$style = 'drop_down';
	}

	// Return style
	return $style;

}

/**
 * Add menu cart icon fragments
 *
 * @since 4.0
 */
function wpex_add_header_menu_cart_item( $items, $args ) {

	// Only used for the main menu
	if ( 'main_menu' != $args->theme_location ) {
		return $items;
	}

	// Get style
	$style = wpex_header_menu_cart_style();

	// Return items if no style
	if ( ! $style ) {
		return $items;
	}

	// Define classes to add to li element
	$classes = 'woo-menu-icon wpex-menu-extra';

	// Add style class
	$classes .= ' wcmenucart-toggle-' . $style;

	// Prevent clicking on cart and checkout
	if ( 'custom-link' != $style && ( is_cart() || is_checkout() ) ) {
		$classes .= ' nav-no-click';
	}

	// Add toggle class
	else {
		$classes .= ' toggle-cart-widget';
	}

	// Add ubermenu classes
	if ( class_exists( 'UberMenu' ) && apply_filters( 'wpex_add_search_toggle_ubermenu_classes', true ) ) {
		$classes .= ' ubermenu-item-level-0 ubermenu-item';
	}

	// Add cart link to menu items
	$items .= '<li class="' . $classes . '">' . wpex_wcmenucart_menu_item() . '</li>';

	// Return menu items
	return $items;
}
add_filter( 'wp_nav_menu_items', 'wpex_add_header_menu_cart_item', 10, 2 );

/**
 * Add menu cart icon fragments
 *
 * @since 4.0
 */
function wpex_menu_cart_icon_fragments( $fragments ) {
	$fragments['.wcmenucart'] = wpex_wcmenucart_menu_item();
	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'wpex_menu_cart_icon_fragments' );

/**
 * Creates the WooCommerce link for the navbar
 * Must check if function exists for easier child theme edits.
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'wpex_wcmenucart_menu_item' ) ) {
	function wpex_wcmenucart_menu_item() {
		
		// Vars
		global $woocommerce;
		$icon_style   = wpex_get_mod( 'woo_menu_icon_style', 'drop-down' );
		$custom_link  = wpex_get_mod( 'woo_menu_icon_custom_link' );
		$count        = WC()->cart->cart_contents_count;

		// Link classes
		$a_classes = 'wcmenucart';
		$count     = $count ? $count : '0';
		$a_classes .= ' wcmenucart-items-' . intval( $count );
		if ( class_exists( 'UberMenu' ) && apply_filters( 'wpex_add_search_toggle_ubermenu_classes', true ) ) {
			$a_classes .= ' ubermenu-target ubermenu-item-layout-default ubermenu-item-layout-text_only';
		}

		// Define cart icon link URL
		if ( 'custom-link' == $icon_style && $custom_link ) {
			$url = esc_url( $custom_link );
		} elseif ( $cart_id = wpex_parse_obj_id( wc_get_page_id( 'cart' ), 'page' ) ) {
			$url = get_permalink( $cart_id );
		}
		
		// Cart total
		$display = wpex_get_mod( 'woo_menu_icon_display', 'icon_count' );
		if ( 'icon_total' == $display ) {
			$cart_extra = WC()->cart->get_cart_total();
			$cart_extra = str_replace( 'amount', 'wcmenucart-details', $cart_extra );
		} elseif ( 'icon_count' == $display ) {
			$cart_extra = '<span class="wcmenucart-details count">'. esc_html( $count ) .'</span>';
		} else {
			$cart_extra = '';
		}

		// Cart Icon
		$icon_class = wpex_get_mod( 'woo_menu_icon_class' );
		$icon_class = $icon_class ? esc_attr( $icon_class ) : 'shopping-cart';
		$cart_icon = '<span class="wcmenucart-icon fa fa-' . $icon_class . '"></span><span class="wcmenucart-text">' . esc_html__( 'Shop', 'total' ) . '</span>';
		$cart_icon = apply_filters( 'wpex_menu_cart_icon_html', $cart_icon, $icon_class );

		ob_start(); ?>

			<a href="<?php echo esc_url( $url ); ?>" class="<?php echo $a_classes; ?>" title="<?php esc_html_e( 'Your Cart', 'total' ); ?>">
				<span class="link-inner">
					<span class="wcmenucart-count"><?php echo $cart_icon; ?><?php echo $cart_extra; ?></span>
				</span>
			</a>
			
		<?php
		return ob_get_clean();
		
	}
}

/**
 * Add cart overlay html to site
 *
 * @since 4.0
 */
function wpex_cart_overlay_html() {
	if ( 'overlay' == wpex_header_menu_cart_style() ) {
		get_template_part( 'partials/cart/cart-overlay' );
	}
}
add_action( 'wp_footer', 'wpex_cart_overlay_html' );

/**
 * Add cart dropdown html
 *
 * @since 4.0
 */
function wpex_add_cart_dropdown_html() {

	// Return if style not set to dropdown
	if ( 'drop_down' != wpex_header_menu_cart_style() ) {
		return;
	}

	// Should we get the template part?
	$get = false;

	// Get current header style
	$header_style = wpex_header_style();

	// Header Inner Hook
	if ( 'wpex_hook_header_inner' == current_filter() ) {
		if ( 'one' == $header_style ) {
			$get = true;
		}
	}

	// Menu bottom hook
	elseif ( 'wpex_hook_main_menu_bottom' == current_filter() ) {
		if ( 'two' == $header_style
			|| 'three' == $header_style
			|| 'four' == $header_style
			|| 'five' == $header_style ) {
			$get = true;
		}
	}

	// Get template file
	if ( $get ) {
		get_template_part( 'partials/cart/cart-dropdown' );
	}

}
add_action( 'wpex_hook_header_inner', 'wpex_add_cart_dropdown_html', 40 );
add_action( 'wpex_hook_main_menu_bottom', 'wpex_add_cart_dropdown_html' );