<?php
/**
 * Post Type Functions
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.0
 */

/*-------------------------------------------------------------------------------*/
/* [ Portfolio ]
/*-------------------------------------------------------------------------------*/

/**
 * Returns portfolio name
 *
 * @since 3.3.0
 */
function wpex_get_portfolio_name() {
	$name = wpex_get_translated_theme_mod( 'portfolio_labels' );
	$name = $name ? $name : __( 'Portfolio', 'total' );
	return $name;
}

/**
 * Returns portfolio singular name
 *
 * @since 3.3.0
 */
function wpex_get_portfolio_singular_name() {
	$name = wpex_get_translated_theme_mod( 'portfolio_singular_name' );
	$name = $name ? $name : __( 'Portfolio Item', 'total' );
	return $name;
}

/**
 * Returns portfolio menu icon
 *
 * @since 3.3.0
 */
function wpex_get_portfolio_menu_icon() {
	$icon = wpex_get_mod( 'portfolio_admin_icon' );
	$icon = $icon ? $icon : 'portfolio';
	return $icon;
}

/*-------------------------------------------------------------------------------*/
/* [ Staff ]
/*-------------------------------------------------------------------------------*/

/**
 * Returns staff name
 *
 * @since 3.3.0
 */
function wpex_get_staff_name() {
	$name = wpex_get_translated_theme_mod( 'staff_labels' );
	$name = $name ? $name : __( 'Staff', 'total' );
	return $name;
}

/**
 * Returns staff singular name
 *
 * @since 3.3.0
 */
function wpex_get_staff_singular_name() {
	$name = wpex_get_translated_theme_mod( 'staff_singular_name' );
	$name = $name ? $name : __( 'Staff Member', 'total' );
	return $name;
}

/**
 * Returns staff menu icon
 *
 * @since 3.3.0
 */
function wpex_get_staff_menu_icon() {
	$icon = wpex_get_mod( 'staff_admin_icon' );
	$icon = $icon ? $icon : 'groups';
	return $icon;
}

/*-------------------------------------------------------------------------------*/
/* [ Testimonials ]
/*-------------------------------------------------------------------------------*/

/**
 * Returns testimonials name
 *
 * @since 3.3.0
 */
function wpex_get_testimonials_name() {
	$name = wpex_get_translated_theme_mod( 'testimonials_labels' );
	$name = $name ? $name : __( 'Testimonials', 'total' );
	return $name;
}

/**
 * Returns testimonials singular name
 *
 * @since 3.3.0
 */
function wpex_get_testimonials_singular_name() {
	$name = wpex_get_translated_theme_mod( 'testimonials_singular_name' );
	$name = $name ? $name : __( 'Testimonial', 'total' );
	return $name;
}

/**
 * Returns testimonials menu icon
 *
 * @since 3.3.0
 */
function wpex_get_testimonials_menu_icon() {
	$icon = wpex_get_mod( 'testimonials_admin_icon' );
	$icon = $icon ? $icon : 'format-status';
	return $icon;
}