<?php
/**
 * Outputs the CSS saved in the VC for a spefic post ID
 *
 * @package Total WordPress Theme
 * @subpackage Visual Composer
 * @since 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Loops through settings to add line CSS for VC content added outside it's standard scope
 *
 * @since 4.0
 */
function wpex_output_post_vc_css( $css ) {

	// Get current post ID
	$post_id = wpex_get_current_post_id();

	// Define ID's as array
	$ids = array();

	// Toggle Bar
	if ( wpex_has_togglebar() && $togglebar_id = wpex_togglebar_content_id() ) {
		$ids[] = $togglebar_id;
	}

	// Top Bar Content
	if ( wpex_has_topbar() && $topbar_content = wpex_topbar_content( $post_id ) ) {
		if ( $topbar_content && strpos( $topbar_content, 'vc_row' ) ) {
			$ids[] = wpex_get_translated_theme_mod( 'top_bar_content' );
		}
	}

	// Top Bar Social Alt
	if ( wpex_has_topbar() && $topbar_social_alt = wpex_topbar_social_alt_content( $post_id ) ) {
		if ( $topbar_social_alt && strpos( $topbar_social_alt, 'vc_row' ) ) {
			$ids[] = wpex_get_translated_theme_mod( 'top_bar_social_alt' );
		}
	}

	// Header Aside
	if ( wpex_header_supports_aside() ) {
		$header_aside_content = wpex_header_aside_content( $post_id );
		if ( $header_aside_content && strpos( $header_aside_content, 'vc_row' ) ) {
			$ids[] = wpex_get_translated_theme_mod( 'header_aside' );
		}
	}

	// Callout
	if ( wpex_has_callout() && $callout_content = wpex_callout_content( $post_id ) ) {
		if ( $callout_content && strpos( $callout_content, 'vc_row' ) ) {
			$ids[] = wpex_get_translated_theme_mod( 'callout_text' );
		}
	}

	// Apply filters to ID's
	$ids = apply_filters( 'wpex_vc_css_ids', $ids );

	// Generate CSS
	if ( $ids ) {

		// Remove dups
		$ids = array_unique( $ids );

		// Loop through id's
		foreach ( $ids as $id ) {

			// Sanitize
			$id = intval( $id );

			// If not valid ID continue
			if ( ! $id ) {
				continue;
			}

			// Conditional checks, some CSS isn't necessarily needed globally
			if ( function_exists( 'is_shop' ) && is_shop() ) {
				$condition = true; // Always return true for the shop
			} elseif ( is_404() && $id == wpex_get_current_post_id() ) {
				$condition = true;
			} else {
				$condition = ( $id == wpex_get_current_post_id() ) ? false : true;
			}

			// Add CSS
			if ( $condition && $vc_css = get_post_meta( $id, '_wpb_shortcodes_custom_css', true ) ) {
				$css .= '/*VC META CSS*/' . $vc_css;
			}

		}

	}


	// Return $css
	return $css;

}
add_action( 'wpex_head_css', 'wpex_output_post_vc_css' );

/**
 * Output inline style element for the CSS saved in the VC meta for any post ID
 *
 * @since 4.0
 */
function wpex_get_vc_meta_inline_style( $id = '' ) {
	if ( $id && $css = get_post_meta( $id, '_wpb_shortcodes_custom_css', true ) ) {
		return '<style>' . $css . '</style>';
	}
}