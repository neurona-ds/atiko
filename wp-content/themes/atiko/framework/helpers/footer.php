<?php
/**
 * Site Footer Helper Functions
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.2.1
 */

/**
 * Check if footer is enabled
 *
 * @since 4.0
 */
function wpex_has_footer() {

	// Return true by default
	$bool = true;

	// Disabled on landing page
	if ( is_page_template( 'templates/landing-page.php' ) ) {
		$bool = false;
	}

	// Get current post id
	$post_id = wpex_get_current_post_id();

	// Check page settings
	if ( $post_id && $meta = get_post_meta( $post_id, 'wpex_disable_footer', true ) ) {
		if ( 'on' == $meta ) {
			$bool = false;
		} elseif ( 'enable' == $meta ) {
			$bool = true;
		}
	}

	// Apply filters and return bool
	return apply_filters( 'wpex_display_footer', $bool );

}

/**
 * Check if footer has widgets
 *
 * @since 4.0
 */
function wpex_footer_has_widgets() {

	// Get current post ID
	$post_id = wpex_get_current_post_id();

	// Check if enabled via the customizer
	$return = wpex_get_mod( 'footer_widgets', true );

	// Check post settings
	if ( $post_id && $meta = get_post_meta( $post_id, 'wpex_disable_footer_widgets', true ) ) {
		if ( 'on' == $meta ) {
			$return = false;
		} elseif ( 'enable' == $meta ) {
			$return = true;
		}
	}

	// Apply filters and return
	return apply_filters( 'wpex_display_footer_widgets', $return );

}

/**
 * Get footer builder ID
 *
 * @since 4.0
 */
function wpex_footer_builder_id() {
	if ( class_exists( 'WPEX_Footer_Builder' ) && $id = WPEX_Footer_Builder::footer_builder_id() ) {
		$translated_id = wpex_parse_obj_id( $id, 'page' ); // translate
		return $translated_id ? $translated_id : $id; // if not translated return original ID
	}
}

/**
 * Check if footer builder is enabled
 *
 * @since 4.1
 */
function wpex_has_footer_builder() {
	return wpex_footer_builder_id();
}

/**
 * Check if footer reveal is enabled
 *
 * @since 4.0
 */
function wpex_footer_has_reveal( $post_id = '' ) {

	// Disable here always
	if ( ! wpex_has_footer()
		|| 'boxed' == wpex_site_layout()
		|| 'six' == wpex_header_style()
		|| wpex_vc_is_inline()
	) {
		return false;
	}

	// Check customizer setting
	$bool = wpex_get_mod( 'footer_reveal', false );

	// Get current post id if not set
	$post_id = $post_id ? $post_id : wpex_get_current_post_id();

	// Check page settings
	if ( $post_id && $meta = get_post_meta( $post_id, 'wpex_footer_reveal', true ) ) {
		if ( 'on' == $meta ) {
			$bool = true;
		} elseif ( 'off' == $meta ) {
			$bool = false;
		}
	}

	// Apply filters and return
	return apply_filters( 'wpex_has_footer_reveal', $bool );
}

/**
 * Check if footer bottom is enabled
 *
 * @since 4.1
 */
function wpex_has_footer_bottom() {
	if ( wpex_has_footer_builder() ) {
		$bool = wpex_get_mod( 'footer_builder_footer_bottom', false );
	} else {
		$bool = wpex_get_mod( 'footer_bottom', true );
	}
	return apply_filters( 'wpex_has_footer_bottom', $bool );
}