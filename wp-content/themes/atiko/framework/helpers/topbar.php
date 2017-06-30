<?php
/**
 * Site topbar functions
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.0
 */

/*-------------------------------------------------------------------------------*/
/* [ Table of contents ]
/*-------------------------------------------------------------------------------*

	# General
	# Social

/*-------------------------------------------------------------------------------*/
/* [ General ]
/*-------------------------------------------------------------------------------*/

/**
 * Topbar style
 *
 * @since 2.0.0
 */
function wpex_topbar_style() {
	$style = wpex_get_mod( 'top_bar_style' );
	$style = $style ? $style : 'one';
	return apply_filters( 'wpex_top_bar_style', $style );
}

/**
 * Check if topbar is enabled
 *
 * @since 4.0
 */
function wpex_has_topbar( $post_id = '' ) {

	// Get theme mod value (enabled by default)
	$return = wpex_get_mod( 'top_bar', true );

	// Get current post ID
	$post_id = $post_id ? $post_id : wpex_get_current_post_id();

	// Check meta
	if ( $post_id ) {

		// Return false if disabled via post meta
		if ( 'on' == get_post_meta( $post_id, 'wpex_disable_top_bar', true ) ) {
			$return = false;
		}

		// Return false if disabled via post meta
		if ( 'enable' == get_post_meta( $post_id, 'wpex_disable_top_bar', true ) ) {
			$return = true;
		}

	}

	// Apply filers and return
	return apply_filters( 'wpex_is_top_bar_enabled', $return );

}

/**
 * Get topbar aside content
 *
 * @since 4.0
 */
function wpex_topbar_content( $post_id = '' ) {

	// Get topbar content from Customizer
	$content = wpex_get_translated_theme_mod( 'top_bar_content', '[font_awesome icon="phone" margin_right="5px" color="#000"] 1-800-987-654 [font_awesome icon="envelope" margin_right="5px" margin_left="20px" color="#000"] admin@totalwptheme.com [font_awesome icon="user" margin_right="5px" margin_left="20px" color="#000"] [wp_login_url text="User Login" logout_text="Logout"]' );

	// Check if content is a page ID and get page content
	if ( is_numeric( $content ) ) {
		$post_id = wpex_parse_obj_id( $content, 'page' );
		$post    = get_post( $post_id );
		if ( $post && ! is_wp_error( $post ) ) {
			$content = $post->post_content;
		}
	}

	// Apply filters and return content
	return apply_filters( 'wpex_top_bar_content', $content );

}

/**
 * Topbar content classes
 *
 * @since 2.0.0
 */
function wpex_topbar_content_classes() {

	// Define classes
	$classes = array( 'wpex-clr' );

	// Check for content
	if ( wpex_topbar_content() ) {
		$classes[] = 'has-content';
	}

	// Get topbar style
	$style = wpex_topbar_style();

	// Add classes based on top bar style only if social is enabled
	if ( 'one' == $style ) {
		$classes[] = 'top-bar-left';
	} elseif ( 'two' == $style ) {
		$classes[] = 'top-bar-right';
	} elseif ( 'three' == $style ) {
		$classes[] = 'top-bar-centered';
	}

	// Apply filters for child theming
	$classes = apply_filters( 'wpex_top_bar_classes', $classes );

	// Turn classes array into space seperated string
	$classes = implode( ' ', $classes );

	// Return classes
	return $classes;

}

/*-------------------------------------------------------------------------------*/
/* [ Social ]
/*-------------------------------------------------------------------------------*/

/**
 * Get topbar aside content
 *
 * @since 4.0
 */
function wpex_topbar_social_alt_content( $post_id = '' ) {

	// Check customizer setting
	$content = wpex_get_translated_theme_mod( 'top_bar_social_alt' );

	// Check if social_alt is a page ID and get page content
	if ( is_numeric( $content ) ) {
		$post_id = wpex_parse_obj_id( $content, 'page' );
		$post    = get_post( $post_id );
		if ( $post && ! is_wp_error( $post ) ) {
			$content = $post->post_content;
		}
	}

	// Return content
	return $content;

}