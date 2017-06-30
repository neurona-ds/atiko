<?php
/**
 * Site Header Helper Functions
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.2.1
 */

/*-------------------------------------------------------------------------------*/
/* [ Table of contents ]
/*-------------------------------------------------------------------------------*

	# General
	# Logo
	# Overlay
	# Sticky
	# Header Aside
	# Header Builder

/*-------------------------------------------------------------------------------*/
/* [ General ]
/*-------------------------------------------------------------------------------*/

/**
 * Check if site header is enabled
 *
 * @since 4.0
 */
function wpex_has_header( $post_id = '' ) {

	// Return true by default
	$return = wpex_get_mod( 'enable_header', true );

	// Get current post ID
	$post_id = $post_id ? $post_id : wpex_get_current_post_id();

	// Check meta
	if ( $post_id && $meta = get_post_meta( $post_id, 'wpex_disable_header', true ) ) {
		if ( 'on' == $meta ) {
			$return = false;
		} elseif ( 'enable' == $meta ) {
			$return = true;
		}
	}

	// Apply filters and return
	return apply_filters( 'wpex_display_header', $return );

}

/**
 * Get header style
 *
 * @since 4.0
 */
function wpex_header_style( $post_id = '' ) {

	// Check URL
	if ( ! empty( $_GET['header_style'] ) ) {
		return esc_html( $_GET['header_style'] );
	}

	// Return if header is disabled
	if ( ! wpex_has_header() ) {
		return 'disabled';
	}

	// Check if builder is enabled
	if ( wpex_header_builder_id() ) {
		return 'builder';
	}

	// Get header style from customizer setting
	$style = wpex_get_mod( 'header_style', 'one' );

	// Overlay header only supports header styles 1 and five
	if ( $style !== 'five' && wpex_has_overlay_header() ) {
		$style = 'one';
	}

	// Get current post ID
	$post_id = $post_id ? $post_id : wpex_get_current_post_id();

	// Check for custom header style defined in meta options => Overrides all
	if ( $post_id && $meta = get_post_meta( $post_id, 'wpex_header_style', true ) ) {
		$style = $meta;
	}

	// Sanitize style to make sure it isn't empty
	$style = $style ? $style : 'one';

	// Apply filters and return
	return apply_filters( 'wpex_header_style', $style );

}

/**
 * Add classes to the header wrap
 *
 * @since 1.5.3
 */
function wpex_header_classes() {

	// Vars
	$post_id      = wpex_get_current_post_id();
	$header_style = wpex_header_style( $post_id );

	// Setup classes array
	$classes = array();

	// Main header style
	$classes['header_style'] = 'header-'. $header_style;

	// Full width header
	if ( 'full-width' == wpex_site_layout() && wpex_get_mod( 'full_width_header' ) ) {
		$classes[] = 'wpex-full-width';
	}

	// Sticky Header
	if ( wpex_has_sticky_header() ) {

		// Fixed header style
		$fixed_header_style = wpex_sticky_header_style();

		// Main fixed class
		$classes['fixed_scroll'] = 'fixed-scroll'; // @todo rename this at some point?
		if ( wpex_has_shrink_sticky_header() ) {
			$classes['shrink-sticky-header'] = 'shrink-sticky-header';
			if ( 'shrink_animated' == $fixed_header_style ) {
				$classes['anim-shrink-header'] = 'anim-shrink-header';
			}
		}

	}

	// Reposition cart and search dropdowns
	if ( 'three' == $header_style
		|| 'four' == $header_style
		|| 'five' == $header_style
		|| ( 'two' == $header_style && wpex_get_mod( 'header_menu_center', false ) )
	) {
		$classes[] = 'wpex-reposition-cart-search-drops';
	}

	// Dropdown style (must be added here so we can target shop/search dropdowns)
	$dropdown_style = wpex_get_mod( 'menu_dropdown_style' );
	if ( $dropdown_style && 'default' != $dropdown_style ) {
		$classes['wpex-dropdown-style-'. $dropdown_style] = 'wpex-dropdown-style-'. $dropdown_style;
	}

	// Dropdown shadows
	if ( $shadow = wpex_get_mod( 'menu_dropdown_dropshadow' ) ) {
		$classes[] = 'wpex-dropdowns-shadow-'. $shadow;
	}

	// Header Overlay Style
	if ( wpex_has_overlay_header() ) {

		// Get header style
		$overlay_style = wpex_overlay_header_style();
		$overlay_style = $overlay_style ? $overlay_style : 'light';

		// Dark dropdowns for overlay header
		if ( 'core' != $overlay_style ) {
			if ( $post_id && $dropdown_style_meta = get_post_meta( $post_id, 'wpex_overlay_header_dropdown_style', true ) ) {
				if ( 'default' != $dropdown_style_meta ) {
					$classes[] = 'wpex-dropdown-style-'. $dropdown_style_meta;
				}
			} else {
				unset( $classes['wpex-dropdown-style-'. $dropdown_style] );
				$classes[] = 'wpex-dropdown-style-black';
			}
		}

		// Add overlay header class
		$classes[] = 'overlay-header';

		// Add overlay header style class
		$classes[] = $overlay_style .'-style';

	}

	// Dynamic style class
	$classes[] = 'dyn-styles';

	// Clearfix class
	$classes[] = 'clr';

	// Set keys equal to vals
	$classes = array_combine( $classes, $classes );

	// Apply filters for child theming
	$classes = apply_filters( 'wpex_header_classes', $classes );

	// Turn classes into space seperated string
	$classes = implode( ' ', $classes );

	// return classes
	return $classes;

}

/**
 * Returns header logo image
 *
 * @since 4.0
 */
function wpex_header_logo_img() {

	// Get logo from theme mod
	$logo = wpex_get_translated_theme_mod( 'custom_logo' );

	// Apply filters for child theme mods
	$logo = apply_filters( 'wpex_header_logo_img_url', $logo );

	// Sanitize return
	$logo = $logo ? set_url_scheme( $logo ) : false;

	// Return logo
	return $logo;

}

/**
 * Returns header logo icon
 *
 * @since 2.0.0
 */
function wpex_header_logo_icon() {

	// Get logo img from admin panel
	$icon = esc_html( wpex_get_mod( 'logo_icon' ) );

	// Apply filter for child theming
	$icon = apply_filters( 'wpex_header_logo_icon', $icon );

	// Apply an empty/hidden icon in the customizer for postMessage support
	if ( 'none' == $icon && is_customize_preview() ) {
		$icon = 'wpex-hidden';
	}

	// Return icon
	if ( $icon && 'none' != $icon ) {
		return '<span id="site-logo-fa-icon" class="fa fa-'. $icon .'" aria-hidden="true"></span>';
	}

}

/**
 * Returns header logo title
 *
 * @since 2.0.0
 */
function wpex_header_logo_title() {
	return apply_filters( 'wpex_logo_title', get_bloginfo( 'name' ) );
}

/**
 * Returns header logo URL
 *
 * @since 2.0.0
 */
function wpex_header_logo_url() {
	return apply_filters( 'wpex_logo_url', home_url( '/' ) );
}

/**
 * Header logo classes
 *
 * @since 2.0.0
 */
function wpex_header_logo_classes() {

	// Define classes array
	$classes = array( 'site-branding', 'clr' );

	// Default class
	$classes[] = 'header-' . wpex_header_style() . '-logo';

	// Get custom overlay logo
	if ( wpex_has_overlay_header() && wpex_overlay_header_logo_img() ) {
		$classes[] = 'has-overlay-logo';
	}

	// Apply filters for child theming
	$classes = apply_filters( 'wpex_header_logo_classes', $classes );

	// Turn classes into space seperated string
	$classes = implode( ' ', $classes );

	// Return classes
	return $classes;

}

/*-------------------------------------------------------------------------------*/
/* [ Logo ]
/*-------------------------------------------------------------------------------*/

/**
 * Returns correct header logo height
 *
 * @since 4.0
 */
function wpex_header_logo_img_height() {
	$height = apply_filters( 'logo_height', wpex_get_mod( 'logo_height' ) );
	return $height ? intval( $height ) : ''; // can't be 0
}

/**
 * Returns correct header logo width
 *
 * @since 4.0
 */
function wpex_header_logo_img_width() {
	$width = intval( apply_filters( 'logo_width', wpex_get_mod( 'logo_width' ) ) );
	return $width ? intval( $width ) : ''; // can't be 0
}

/**
 * Returns correct heeader logo retina img
 *
 * @since 4.0
 */
function wpex_header_logo_img_retina() {

	// Overlay header custom logo retina version
	if ( wpex_has_overlay_header() && wpex_overlay_header_logo_img() ) {
		$logo = wpex_overlay_header_logo_img_retina();
	}

	// Default retina logo
	else {
		$logo = wpex_get_translated_theme_mod( 'retina_logo' );
	}

	// Apply filters
	$logo = apply_filters( 'wpex_retina_logo_url', $logo );

	// Sanitize output
	$logo = $logo ? set_url_scheme( $logo ) : false;

	// Return retina logo url
	return $logo;

}

/**
 * Returns correct heeader logo retina img height
 *
 * @since 4.0
 */
function wpex_header_logo_img_retina_height() {

	// Get default height from customizer setting
	$height = wpex_get_translated_theme_mod( 'logo_height' );

	// Get post id
	$post_id = wpex_get_current_post_id();

	// Check overlay header
	if ( wpex_has_overlay_header() && $overlay_logo_height = wpex_overlay_header_logo_img_retina_height() ) {
		$height = $overlay_logo_height;
	}

	// Apply filters
	$height = apply_filters( 'wpex_retina_logo_height', $height );

	// Sanitize
	$height = $height ? $height : false;

	// Return logo height
	return $height;

}

/**
 * Adds js for the retina logo
 *
 * @since 1.1.0
 */
function wpex_header_logo_img_retina_js() {

	// Not needed in admin or if there is a custom header
	if ( is_admin() || wpex_has_custom_header() ) {
		return;
	}

	// Get retina logo url and height
	$logo_url    = wpex_header_logo_img_retina();
	$logo_height = $logo_url ? wpex_header_logo_img_retina_height() : '';

	// Output JS for retina logo
	if ( $logo_url && $logo_height ) {

		$output = '<!-- Retina Logo --><script type="text/javascript">';

			$output .= 'jQuery(function($){';

				$output .= 'if ( window.devicePixelRatio >= 2 ) {';

					$output .= '$("#site-logo img.logo-img").attr("src","'. $logo_url .'" ).css("max-height","'. intval( $logo_height ) .'px");';

				$output .= '}';

			$output .= '});';

		$output .= '</script>';

		echo $output;
	}
}
add_action( 'wp_head', 'wpex_header_logo_img_retina_js' );

/*-------------------------------------------------------------------------------*/
/* [ Header Overlay Style ]
/*-------------------------------------------------------------------------------*/

/**
 * Checks if the overlay header is enabled
 *
 * @since 4.0
 */
function wpex_has_overlay_header() {

	// Return false if header is disabled
	// @todo is this check really needed?
	if ( ! wpex_has_header() ) {
		return false;
	}

	// False by default
	$return = false;

	// Get current post id
	$post_id = wpex_get_current_post_id();

	// Return true if enabled via the post meta
	if ( $post_id && 'on' == get_post_meta( $post_id, 'wpex_overlay_header', true ) ) {
		$return = true;
	}

	// Apply filters and return
	return apply_filters( 'wpex_has_overlay_header', $return );

}

/**
 * Returns overlay header style
 *
 * @since 4.0
 */
function wpex_overlay_header_style() {

	// Default style is empty
	$style = '';

	// Get post id
	$post_id = wpex_get_current_post_id();

	// If overlay header is enabled
	if ( $post_id ) {
		$style = get_post_meta( $post_id, 'wpex_overlay_header_style', true );
		$style = $style ? $style : 'light'; // Fallback for when light setting used to be empty, must keep
	}

	// Apply filters and return
	return apply_filters( 'wpex_header_overlay_style', $style );
}

/**
 * Returns correct logo image for the overlay header image
 *
 * @since 4.0
 */
function wpex_overlay_header_logo_img() {

	// No custom overlay logo by default
	$logo = false;

	// Get logo via custom field
	$logo = get_post_meta( wpex_get_current_post_id(), 'wpex_overlay_header_logo', true );

	// Check old method
	if ( is_array( $logo ) ) {
		if ( ! empty( $logo['url'] ) ) {
			$logo = $logo['url'];
		} else {
			$logo = false;
		}
	}

	// Apply filters for child theming
	$logo = apply_filters( 'wpex_header_overlay_logo', $logo );

	// If numeric logo is an attachment ID so lets get the URL
	if ( is_numeric( $logo ) ) {
		$logo = wp_get_attachment_image_src( $logo, 'full' );
		$logo = $logo[0];
	}

	// Sanitize
	$logo = $logo ? set_url_scheme( $logo ) : false;

	// Return logo
	return $logo;
}

/**
 * Returns correct retina logo image for the overlay header image
 *
 * @since 4.0
 */
function wpex_overlay_header_logo_img_retina() {

	// Empty by default
	$logo = '';

	// Get post ID
	$post_id = wpex_get_current_post_id();

	// Get meta value
	$logo = get_post_meta( $post_id, 'wpex_overlay_header_logo_retina', true );

	// Sanitize meta
	if ( $logo && is_numeric( $logo ) ) {
		$logo = wp_get_attachment_image_src( $logo, 'full' );
		$logo = isset( $logo[0] ) ? $logo[0] : '';
	}

	// Return value
	return $logo;

}

/**
 * Returns correct retina logo image height for the overlay header image
 *
 * @since 4.0
 */
function wpex_overlay_header_logo_img_retina_height() {
	return get_post_meta( wpex_get_current_post_id(), 'wpex_overlay_header_logo_retina_height', true );
}

/*-------------------------------------------------------------------------------*/
/* [ Sticky Header ]
/*-------------------------------------------------------------------------------*/

/**
 * Check if sticky header is enabled
 *
 * @since 4.0
 */
function wpex_has_sticky_header() {

	// Disable for custom header and in VC Live editor
	if ( wpex_has_custom_header() || wpex_vc_is_inline() ) {
		return;
	}

	// Disabled by default
	$return = false;

	// Get current post id
	$post_id = wpex_get_current_post_id();

	// Check meta first it should override any filter!
	if ( $post_id && 'disable' == get_post_meta( $post_id, 'wpex_sticky_header', true ) ) {
		return false;
	}

	// Get header style
	$header_style = wpex_header_style( $post_id );

	// Return true if header is not disabled and header style is either 1 or 2
	if ( 'disabled' != wpex_sticky_header_style() && ( 'one' == $header_style || 'five' == $header_style ) ) {
		$return = true;
	}

	// No sticky header for the vertical header
	if ( 'six' == $header_style ) {
		$return = false;
	}

	// Apply filters and return
	return apply_filters( 'wpex_has_fixed_header', $return );

}

/**
 * Get sticky header style
 *
 * @since 4.0
 */
function wpex_sticky_header_style() {

	// Get default style from customizer
	$style = wpex_get_mod( 'fixed_header_style', 'standard' );

	// If disabled in Customizer but enabled in meta set to "standard" style
	if ( 'disabled' == $style && 'enable' == get_post_meta( wpex_get_current_post_id(), 'wpex_sticky_header', true ) ) {
		$style = 'standard';
	}

	// Sanitize
	$style = $style ? $style : 'standard';

	// Return style
	return $style;

}

/**
 * Returns correct sticky header logo img
 *
 * @since 4.0
 */
function wpex_sticky_header_logo_img() {

	// Get fixed header logo from the Customizer
	$logo = wpex_get_mod( 'fixed_header_logo' );

	// Set sticky logo to header logo for overlay header when custom overlay logo is set
	// This way you can have a white logo on overlay but the default on sticky.
	if ( ! $logo
		&& wpex_has_overlay_header()
		&& 'light' != wpex_overlay_header_style()
		&& wpex_overlay_header_logo_img()
	) {
		$header_logo = wpex_header_logo_img();
		$logo        = $header_logo ? $header_logo : $logo;
	}

	// Apply filters
	$logo = apply_filters( 'wpex_fixed_header_logo', $logo );

	// Sanitize logo
	$logo = $logo ? esc_url( set_url_scheme( $logo ) ) : '';

	// Return logo
	return $logo;

}

/**
 * Returns correct sticky header logo img retina version
 *
 * @since 4.0
 */
function wpex_sticky_header_logo_img_retina() {

	// Check customizer setting
	$logo = wpex_get_mod( 'fixed_header_logo_retina' );

	// SSL Fix
	$logo = set_url_scheme( $logo );

	// Apply filters and return
	return apply_filters( 'wpex_fixed_header_logo_retina', $logo );

}

/**
 * Returns correct sticky header logo img retina version
 *
 * @since 4.0
 * @todo  Check if this function is used still...
 */
function wpex_sticky_header_logo_img_retina_height() {

	// Get height and apply filters
	$height = apply_filters( 'wpex_fixed_header_logo_retina_height', wpex_get_mod( 'fixed_header_logo_retina_height' ) );

	// Sanitize
	$height = $height ? intval( $height ) : null;

	// Return height
	return $height;

}

/**
 * Check if shrink sticky header is enabled
 *
 * @since 4.0
 */
function wpex_has_shrink_sticky_header() {

	// Disabled by default
	$bool = false;

	// Sticky header must be enabled
	if ( wpex_has_sticky_header() ) {

		// Get sticky header style
		$sticky_style = wpex_sticky_header_style();

		// Check if enabled via sticky style
		if ( 'shrink' == $sticky_style || 'shrink_animated' == $sticky_style ) {

			// Get header style
			$header_style = wpex_header_style();

			// Only enabled for header styles 1 and 5
			if ( 'one' == $header_style || 'five' == $header_style ) {
				$bool = true;
			}

		}

	}

	// Apply filters and return
	return apply_filters( 'wpex_has_shrink_sticky_header', $bool );

}

/*-------------------------------------------------------------------------------*/
/* [ Header Aside ]
/*-------------------------------------------------------------------------------*/

/**
 * Check if the current header supports aside content
 *
 * @since 3.0.0
 */
function wpex_header_supports_aside( $header_style = '' ) {

	// False by default
	$bool = false;

	// Get header style
	$header_style = $header_style ? $header_style : wpex_header_style();

	// Validate
	if ( 'two' == $header_style || 'three' == $header_style || 'four' == $header_style ) {
		$bool = true;
	}

	// Apply filters and return
	return apply_filters( 'wpex_header_supports_aside', $bool );

}

/**
 * Get Header Aside content
 *
 * @since 4.0
 */
function wpex_header_aside_content() {

	// Get header aside content
	$content = wpex_get_translated_theme_mod( 'header_aside' );

	// Check if content is a page ID and get page content
	if ( is_numeric( $content ) ) {
		$post_id = $content;
		$post = get_post( $post_id );
		if ( $post && ! is_wp_error( $post ) ) {
			$content = $post->post_content;
		}
	}

	// Apply filters and return content
	return apply_filters( 'wpex_header_aside_content', $content );

}

/*-------------------------------------------------------------------------------*/
/* [ Header Builder ]
/*-------------------------------------------------------------------------------*/

/**
 * Check if header is custom
 *
 * @since 4.0
 */
function wpex_has_custom_header() {
	return apply_filters( 'wpex_has_custom_header', wpex_header_builder_id() );
}

/**
 * Get header builder ID
 *
 * @since 4.0
 */
function wpex_header_builder_id() {
	if ( class_exists( 'WPEX_Header_Builder' ) && $id = WPEX_Header_Builder::header_builder_id() ) {
		$translated_id = wpex_parse_obj_id( $id, 'page' ); // translate
		return $translated_id ? $translated_id : $id; // if not translated return original ID
	}
}