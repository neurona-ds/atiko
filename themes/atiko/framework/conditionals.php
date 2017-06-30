<?php
/**
 * Conditonal functions.
 * These functions load before anything else in the main theme class so they can be used
 * early on in pretty much any hook.
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*-------------------------------------------------------------------------------*/
/* [ Table of contents ]
/*-------------------------------------------------------------------------------*

	# Core
	# Blog
	# Social Sharing
	# Post Series
	# Taxonomies
	# Terms
	# WooCommerce
	# Authors
	# Visual Composer

/*-------------------------------------------------------------------------------*/
/* [ Core ]
/*-------------------------------------------------------------------------------*/

/**
 * Check if the theme should load deprecated fallbacks
 *
 * @since 4.0
 */
function wpex_load_deprecated_functions() {
	return apply_filters( 'wpex_load_deprecated_functions', wpex_get_mod( 'deprecated_functions', true ) );
}

/**
 * Check if responsiveness is enabled
 *
 * @since 4.0
 */
function wpex_is_layout_responsive() {
	return apply_filters( 'wpex_is_layout_responsive', wpex_get_mod( 'responsive', true ) );
}

/**
 * Check if the post edit links should display on the page
 *
 * @since 2.0.0
 */
function wpex_is_retina_enabled() {
	if ( wpex_get_mod( 'image_resizing', true ) && wpex_get_mod( 'retina', false ) ) {
		return true;
	}
}

/**
 * Check if a post has media
 *
 * @since 3.6.0
 */
function wpex_post_has_media( $post_id = '', $check_gallery = false ) {

	// Get post ID
	$post_id = $post_id ? $post_id : get_the_ID();

	// Check thumbnail and videos
	if ( has_post_thumbnail( $post_id )
		|| get_post_meta( $post_id, 'wpex_post_oembed', true )
		|| get_post_meta( $post_id, 'wpex_post_self_hosted_media', true )
		|| get_post_meta( $post_id, 'wpex_post_video_embed', true )
	) {
		return true;
	}

	// Check gallery
	if ( $check_gallery && wpex_post_has_gallery( $post_id ) ) {
		return true;
	}

}

/**
 * Check if the post edit links should display on the page
 *
 * @since 2.0.0
 */
function wpex_has_post_edit() {

	// Return if disabled in Customizer
	if ( ! wpex_get_mod( 'edit_post_link_enable', true ) ) {
		return false;
	}

	// Display by default
	$return = true;

	// If not singular or in front-end editor we can bail completely
	if ( ! is_singular() || wpex_vc_is_inline() ) {
		return false;
	}

	// Not needed for these woo commerce pages
	// @todo move to WooCommerce config?
	if ( WPEX_WOOCOMMERCE_ACTIVE && ( is_cart() || is_checkout() ) ) {
		return false;
	}

	// Apply filters and return
	return apply_filters( 'wpex_has_post_edit', $return );

}

/**
 * Check if the next/previous links should display
 *
 * @since 2.0.0
 */
function wpex_has_next_prev() {

	// Display by default
	$return = true;

	// Not needed here
	if ( ! is_singular() || is_page() || is_singular( 'attachment' ) ) {
		return false;
	}

	// Check if it should be enabled on standard posts
	if ( is_singular( 'post' ) && ! wpex_get_mod( 'blog_next_prev', true ) ) {
		$return = false;
	}

	// Apply filters
	$return = apply_filters( 'wpex_has_next_prev', $return );

	// Return bool
	return $return;

}

/**
 * Check if the readmore button should display
 *
 * @since 2.1.2
 */
function wpex_has_readmore() {

	// Display by default
	$bool = true;

	// Disable if posts are set to display full content
	if ( 'post' == get_post_type()
		&& ! strpos( get_the_content(), 'more-link' )
		&& ! wpex_get_mod( 'blog_exceprt', true ) ) {
		$bool = false;
	}

	// Don't show for password protected posts
	if ( post_password_required() ) {
		$bool = false;
	}

	// Apply filters
	$bool = apply_filters( 'wpex_has_readmore', $bool );

	// Return bool
	return $bool;

}

/**
 * Check if the breadcrumbs is enabled
 *
 * @since 3.6.0
 */
function wpex_has_breadcrumbs( $post_id = '' ) {

	// Check default value in Customizer
	$bool = wpex_get_mod( 'breadcrumbs', true );

	// Get current post ID
	$post_id = $post_id ? $post_id : wpex_get_current_post_id();

	// Check page settings
	if ( $post_id && $meta = get_post_meta( $post_id, 'wpex_disable_breadcrumbs', true ) ) {
		if ( 'on' == $meta ) {
			$bool = false;
		} elseif ( 'enable' == $meta ) {
			$bool = true;
		}
	}

	// Apply filters and return
	return apply_filters( 'wpex_has_breadcrumbs', $bool );

}

/*-------------------------------------------------------------------------------*/
/* [ Blog ]
/*-------------------------------------------------------------------------------*/

/**
 * Returns true if the current Query is a query related to standard blog posts.
 *
 * @since 1.6.0
 */
function wpex_is_blog_query() {

	// False by default
	$bool = false;

	// Return true for blog archives
	if ( is_search() ) {
		$bool = false; // Fixes wp bug
	} elseif (
		is_home()
		|| is_category()
		|| is_tag()
		|| is_date()
		|| is_author()
		|| is_page_template( 'templates/blog.php' )
		|| ( is_tax( 'post_format' ) && 'post' == get_post_type() )
	) {
		$bool = true;
	}

	// Apply filters and return
	return apply_filters( 'wpex_is_blog_query', $bool );

}

/*-------------------------------------------------------------------------------*/
/* [ Social Sharing ]
/*-------------------------------------------------------------------------------*/

/**
 * Checks if social share is enabled
 *
 * @since 4.0
 */
function wpex_has_social_share() {

	// Disable if password protected
	if ( post_password_required() ) {
		return;
	}

	// Disabled by default
	$bool = false;

	// Get current post ID
	$post_id = wpex_get_current_post_id();

	// Check page settings to overrides theme mods and filters
	if ( $post_id && $meta = get_post_meta( $post_id, 'wpex_disable_social', true ) ) {

		// Check if disabled by meta options
		if ( 'on' == $meta ) {
			return false;
		}

		// Return true if enabled via meta option
		if ( 'enable' == $meta ) {
			return true;
		}
		
	}

	// Page/Post check ~ Uses the Customizer composer module so we should return true
	if ( is_singular() ) {
		$bool = true;
	}

	// Check post entries
	elseif ( 'post' == get_post_type() ) {
		$bool = true; // if disabled by the entry won't matter, but needed to prevent issues
	}

	// Apply filters and return
	return apply_filters( 'wpex_has_social_share', $bool );

}

/**
 * Checks if there are any social sharing sites enabled
 *
 * @since 1.0.0
 */
function wpex_has_social_share_sites() {
	if ( wpex_social_share_sites() ) {
		return true;
	}
}

/**
 * Checks if the social sharing style supports a custom heading
 *
 * @since 1.0.0
 */
function wpex_social_sharing_supports_heading() {
	$bool = false;
	if ( wpex_social_share_sites() && 'horizontal' == wpex_social_share_position() ) {
		$bool = true;
	}
	$bool = apply_filters( 'wpex_social_sharing_supports_heading', $bool );
	return $bool;
}

/*-------------------------------------------------------------------------------*/
/* [ Post Series ]
/*-------------------------------------------------------------------------------*/

/**
 * Checks if the current post is part of a post series.
 *
 * @since 2.0.0
 */
function wpex_is_post_in_series() {
	if ( ! taxonomy_exists( 'post_series' ) ) {
		return false;
	}
	$terms = get_the_terms( get_the_id(), 'post_series' );
	if ( $terms ) {
		return true;
	} else {
		return false;
	}

}

/*-------------------------------------------------------------------------------*/
/* [ Taxonomies ]
/*-------------------------------------------------------------------------------*/

/**
 * Checks if on a theme portfolio taxonomy archive
 *
 * @since 1.6.0
 */
function wpex_is_portfolio_tax() {
	$bool = false;
	if ( ! is_search() && ( is_tax( 'portfolio_category' ) || is_tax( 'portfolio_tag' ) ) ) {
		$bool = true;
	}
	return apply_filters( 'wpex_is_portfolio_tax', $bool );
}

/**
 * Checks if on a theme staff taxonomy archive
 *
 * @since 1.6.0
 */
function wpex_is_staff_tax() {
	$bool = false;
	if ( ! is_search() && ( is_tax( 'staff_category' ) || is_tax( 'staff_tag' ) ) ) {
		$bool = true;
	}
	return apply_filters( 'wpex_is_staff_tax', $bool );
}

/**
 * Checks if on a theme testimonials taxonomy archive
 *
 * @since 1.6.0
 */
function wpex_is_testimonials_tax() {
	$bool = false;
	if ( ! is_search() && ( is_tax( 'testimonials_category' ) || is_tax( 'testimonials_tag' ) ) ) {
		$bool = true;
	}
	return apply_filters( 'wpex_is_testimonials_tax', $bool );
}

/*-------------------------------------------------------------------------------*/
/* [ Terms ]
/*-------------------------------------------------------------------------------*/

/**
 * Check if a post has terms/categories
 *
 * This function is used for the next and previous posts so if a post is in a category it
 * will display next and previous posts from the same category.
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'wpex_post_has_terms' ) ) {
	function wpex_post_has_terms( $post_id = '', $post_type = '' ) {

		// Default false
		$bool = false;

		// Post data
		$post_id    = $post_id ? $post_id : get_the_ID();
		$post_type  = $post_type ? $post_type : get_post_type( $post_id );

		// Standard Posts
		if ( $post_type == 'post' ) {
			$terms = get_the_terms( $post_id, 'category' );
			if ( $terms ) {
				$bool =  true;
			}
		}

		// Portfolio
		elseif ( 'portfolio' == $post_type ) {
			$terms = get_the_terms( $post_id, 'portfolio_category' );
			if ( $terms ) {
				$bool =  true;
			}
		}

		// Staff
		elseif ( 'staff' == $post_type ) {
			$terms = get_the_terms( $post_id, 'staff_category' );
			if ( $terms ) {
				$bool =  true;
			}
		}

		// Testimonials
		elseif ( 'testimonials' == $post_type ) {
			$terms = get_the_terms( $post_id, 'testimonials_category' );
			if ( $terms ) {
				$bool =  true;
			}
		}

		// Product
		elseif ( WPEX_WOOCOMMERCE_ACTIVE && 'product' == $post_type ) {
			$terms = get_the_terms( $post_id, 'product_category' );
			if ( $terms ) {
				$bool = true;
			}
		}

		return apply_filters( 'wpex_post_has_terms', $bool );

	}
}

/**
 * Check if term description should display above the loop.
 *
 * By default the term description displays in the subheading in the page header,
 * however, there are some built-in settings to enable the term description above the loop.
 * This function returns true if the term description should display above the loop and not in the header.
 *
 * @since 2.0.0
 */
function wpex_has_term_description_above_loop( $return = false ) {

	// Return true for tags and categories only
	if (  'above_loop' == wpex_get_mod( 'category_description_position' )
		&& ( is_category() || is_tag() )
	) {
		$return = true;
	}

	// Apply filters
	$return = apply_filters( 'wpex_has_term_description_above_loop', $return );

	// Return
	return $return;

}

/*-------------------------------------------------------------------------------*/
/* [ WooCommerce ]
/*-------------------------------------------------------------------------------*/

/**
 * Check if the current WooCommerce version is supported
 *
 * @since 4.1
 */
function wpex_woo_version_supported() {
	$bool = false;
	if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '3.0.0', '>=' ) ) {
		$bool = true;
	}
	return apply_filters( 'wpex_woo_version_supported', $bool );
}

/**
 * Checks if on the WooCommerce shop page.
 *
 * @since 1.6.0
 */
function wpex_is_woo_shop() {
	if ( ! WPEX_WOOCOMMERCE_ACTIVE ) {
		return false;
	} elseif ( function_exists( 'is_shop' ) && is_shop() ) {
		return true;
	}
}

/**
 * Checks if on a WooCommerce tax.
 *
 * @since 1.6.0
 */
if ( ! function_exists( 'wpex_is_woo_tax' ) ) {
	function wpex_is_woo_tax() {
		if ( ! WPEX_WOOCOMMERCE_ACTIVE ) {
			return false;
		} elseif ( ! is_tax() ) {
			return false;
		} elseif ( function_exists( 'is_product_category' ) && function_exists( 'is_product_tag' ) ) {
			if ( is_product_category() || is_product_tag() ) {
				return true;
			}
		}
	}
}

/**
 * Checks if on singular WooCommerce product post.
 *
 * @since 1.6.0
 */
function wpex_is_woo_single() {
	if ( ! WPEX_WOOCOMMERCE_ACTIVE ) {
		return false;
	} elseif ( is_woocommerce() && is_singular( 'product' ) ) {
		return true;
	}
}

/*-------------------------------------------------------------------------------*/
/* [ Authors ]
/*-------------------------------------------------------------------------------*/

/**
 * Check if current user has social profiles defined.
 *
 * @since 1.0.0
 */
function wpex_author_has_social( $user = '' ) {

	if ( ! $user ) {
		global $post;
		$user = ! empty( $post->post_author ) ? $post->post_author : '';
	}

	if ( ! $user ) {
		return;
	}

	if ( get_the_author_meta( 'wpex_twitter', $user ) ) {
		return true;
	} elseif ( get_the_author_meta( 'wpex_facebook', $user ) ) {
		return true;
	} elseif ( get_the_author_meta( 'wpex_googleplus', $user ) ) {
		return true;
	} elseif ( get_the_author_meta( 'wpex_linkedin', $user ) ) {
		return true;
	} elseif ( get_the_author_meta( 'wpex_pinterest', $user ) ) {
		return true;
	} elseif ( get_the_author_meta( 'wpex_instagram', $user ) ) {
		return true;
	}

}

/*-------------------------------------------------------------------------------*/
/* [ Visual Composer ]
/*-------------------------------------------------------------------------------*/

/**
 * Check if the theme has custom VC mods + extensions.
 *
 * @since 4.1
 */
function wpex_has_vc_mods() {
	return apply_filters( 'wpex_has_vc_mods', true );
}

/**
 * Check if the current visual composer plugin is active and supported
 *
 * @since 3.3.4
 */
function wpex_vc_is_supported() {
	if ( defined( 'WPB_VC_VERSION' )
		&& version_compare( WPB_VC_VERSION, WPEX_VC_SUPPORTED_VERSION, '>=' )
	) {
		return true;
	}
}

/**
 * Check if a specific post is using the Visual Composer
 *
 * @since 1.0.0
 */
function wpex_post_has_vc_content( $post_id = '' ) {

	// Return false if VC is disabled
	if ( ! WPEX_VC_ACTIVE ) {
		return;
	}

	// Get post ID
	$post_id = $post_id ? $post_id : wpex_get_current_post_id();

	// Return false if not on a post
	if ( ! $post_id ) {
		return;
	}

	// Get post content
	$post_content = get_post_field( 'post_content', $post_id );

	// Check for vc_row shortcode and if found then post is using VC
	if ( $post_content && strpos( $post_content, 'vc_row' ) ) {
		return true;
	}

}

/**
 * Check if user is currently editing in front-end editor mode
 *
 * @since 1.0.0
 */
function wpex_vc_is_inline() {
	if ( function_exists( 'vc_is_inline' ) ) {
		return vc_is_inline();
	}
}