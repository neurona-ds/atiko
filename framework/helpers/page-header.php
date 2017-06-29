<?php
/**
 * All page header functions
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.0
 *
 */

/*-------------------------------------------------------------------------------*/
/* [ Table of contents ]
/*-------------------------------------------------------------------------------*

	# General
	# Background
	# Subheading
	# Inline CSS

/*-------------------------------------------------------------------------------*/
/* [ General ]
/*-------------------------------------------------------------------------------*/

/**
 * Check if page header is enabled
 *
 * @since 4.0
 */
function wpex_has_page_header() {

	// Display by default
	$bool = true;

	// Get page header style
	$style = wpex_page_header_style();

	// Hide by default if style is set to hidden
	if ( 'hidden' == $style ) {
		$bool = false;
	}

	// Check meta options
	if ( $post_id = wpex_get_current_post_id() ) {

		// Get page meta setting
		$meta = get_post_meta( $post_id, 'wpex_disable_title', true );

		// Return true if enabled via page settings
		if ( 'enable' == $meta ) {
			$bool = true;
		}

		// Return false if page header is disabled and there isn't a page header background defined
		elseif ( 'on' == $meta ) {
			$bool = false;
		}

	}

	// Re enable for background image style
	// This must run last of course
	if ( 'background-image' == $style ) {
		$bool = true;
	}

	// Apply filters and return
	return apply_filters( 'wpex_display_page_header', $bool );

}

/**
 * Check if page header title is enabled
 *
 * @since 4.0
 */
function wpex_has_page_header_title() {

	// Get current post ID
	$post_id = wpex_get_current_post_id();

	// Disable title if the page header is disabled via meta (ignore filter)
	if ( $post_id && 'on' == get_post_meta( $post_id, 'wpex_disable_title', true ) ) {
		return false;
	}

	// Apply filters and return
	return apply_filters( 'wpex_has_page_header_title', true );

}

/**
 * Returns correct page header style
 *
 * @since 4.0
 */
function wpex_page_header_style() {

	// Get default page header style defined in Customizer
	$style = wpex_get_mod( 'page_header_style' );

	// Get current post id
	$post_id = wpex_get_current_post_id();

	// Get for header style defined in page settings
	if ( $post_id && $meta = get_post_meta( $post_id, 'wpex_post_title_style', true ) ) {
		$style = $meta;
	}

	// Sanitize data
	$style = ( 'default' == $style ) ? '' : $style;

	// Apply filters and return
	return apply_filters( 'wpex_page_header_style', $style );

}

/**
 * Adds correct classes to the page header
 *
 * @since 2.0.0
 */
function wpex_page_header_classes() {

	// Define main class
	$classes = array( 'page-header' );

	// Get header style
	$style = wpex_page_header_style();

	// Add classes for title style
	if ( $style ) {
		$classes[$style .'-page-header'] = $style .'-page-header';
	}

	// Check if current page title supports mods
	if ( ! in_array( $style, array( 'background-image', 'solid-color' ) ) ) {
		$classes['wpex-supports-mods'] = 'wpex-supports-mods';
	}

	// Customizer background setting
	// Do not confuse with the page settings background-image style
	if ( 'background-image' != $style
		&& wpex_page_header_background_image()
		&& 'background-image' != wpex_get_mod( 'page_header_style' )
	) {
		$classes['has-bg-image'] = 'has-bg-image';
		$bg_style = get_theme_mod( 'page_header_background_img_style' );
		$bg_style = $bg_style ? $bg_style : 'fixed';
		$bg_style = apply_filters( 'wpex_page_header_background_img_style', $bg_style );
		$classes['bg-'. $bg_style] = 'bg-'. $bg_style;
	}

	// Apply filters
	$classes = apply_filters( 'wpex_page_header_classes', $classes );

	// Turn into comma seperated list
	$classes = implode( ' ', $classes );

	// Return classes
	return $classes;

}

/*-------------------------------------------------------------------------------*/
/* [ Background ]
/*-------------------------------------------------------------------------------*/

/**
 * Get page header background image URL
 *
 * @since 1.5.4
 */
function wpex_page_header_background_image() {

	// Get default Customizer value
	$image = wpex_get_mod( 'page_header_background_img', null );

	// Get current post ID
	$post_id = wpex_get_current_post_id();

	// Apply filters before meta checks => meta should always override
	$image = apply_filters( 'wpex_page_header_background_img', $image ); // @todo remove this deprecated filter
	$image = apply_filters( 'wpex_page_header_background_image', $image, $post_id );

	// Check meta for bg image
	if ( $post_id ) {

		// Get page header background from meta
		if ( $post_id && 'background-image' == get_post_meta( $post_id, 'wpex_post_title_style', true ) ) {

			if ( $new_meta = get_post_meta( $post_id, 'wpex_post_title_background_redux', true ) ) {
				if ( is_array( $new_meta ) && ! empty( $new_meta['url'] ) ) {
					$image = $new_meta['url'];
				} else {
					$image = $new_meta;
				}
			} else {
				$image = get_post_meta( $post_id, 'wpex_post_title_background', true ); // Fallback
			}

		}

	}

	// Generate image URL if using ID
	if ( is_numeric( $image ) ) {
		$image = wp_get_attachment_image_src( $image, 'full' );
		$image = $image[0];
	}

	// Return image
	return $image;
}

/**
 * Get correct page header overlay style
 *
 * @since 3.6.0
 */
function wpex_get_page_header_overlay_style() {
	$post_id = wpex_get_current_post_id();
	if ( $post_id && 'background-image' == get_post_meta( $post_id, 'wpex_post_title_style', true ) ) {
		$style = get_post_meta( $post_id, 'wpex_post_title_background_overlay', true );
	} else {
		$style = 'dark'; // Default style for categories
	}
	$style = $style == 'none' ? '' : $style; // Backwards compatibility
	return apply_filters( 'wpex_page_header_overlay_style', $style );
}

/**
 * Get correct page header overlay opacity
 *
 * @since 3.6.0
 */
function wpex_get_page_header_overlay_opacity() {
	$post_id = wpex_get_current_post_id();
	$opacity = '';
	if ( $post_id
		&& 'background-image' == get_post_meta( $post_id, 'wpex_post_title_style', true )
		&& $meta = get_post_meta( $post_id, 'wpex_post_title_background_overlay_opacity', true )
	) {
		$opacity = $meta;
	}
	return apply_filters( 'wpex_page_header_overlay_opacity', $opacity );
}

/**
 * Outputs html for the page header overlay
 *
 * @since 1.5.3
 */
function wpex_page_header_overlay( ) {

	// Only needed for the background-image style so return otherwise
	if ( 'background-image' != wpex_page_header_style() ) {
		return;
	}

	// Define vars
	$return  = '';

	// Get settings
	$overlay_style = wpex_get_page_header_overlay_style();

	// Check that overlay style isn't set to none
	if ( $overlay_style ) {

		// Return overlay element
		$return = '<span class="background-image-page-header-overlay style-'. $overlay_style .'"></span>';

	}

	// Apply filters and echo
	echo apply_filters( 'wpex_page_header_overlay', $return );
}

/*-------------------------------------------------------------------------------*/
/* [ Subheading ]
/*-------------------------------------------------------------------------------*/

/**
 * Check if page has header subheading
 *
 * @since 4.0
 */
function wpex_page_header_has_subheading() {
	$bool = wpex_page_header_subheading_content() ? true : false;
	return apply_filters( 'wpex_page_header_has_subheading', $bool );
}

/**
 * Returns page header subheading content
 *
 * @since 4.0
 */
function wpex_page_header_subheading_content() {

	// Subheading is empty by default
	$subheading = '';

	// Get post ID
	$post_id = wpex_get_current_post_id();

	// Posts & Pages
	if ( $post_id && $meta = get_post_meta( $post_id, 'wpex_post_subheading', true ) ) {
		$subheading = $meta;
	}

	// Categories
	elseif ( is_category() ) {
		$position = wpex_get_mod( 'category_description_position' );
		$position = $position ? $position : 'under_title';
		if ( 'under_title' == $position ) {
			$subheading = term_description();
		}
	}

	// Author
	elseif ( is_author() ) {
		$subheading = __( 'This author has written', 'total' ) . ' ' . get_the_author_posts() . ' ' . __( 'articles', 'total' );
	}

	// All other Taxonomies
	elseif ( is_tax() && ! wpex_has_term_description_above_loop() ) {
		$subheading = get_the_archive_description();
	}

	// Apply filters and return
	return apply_filters( 'wpex_post_subheading', $subheading );

}


/*-------------------------------------------------------------------------------*/
/* [ Inline CSS ]
/*-------------------------------------------------------------------------------*/

/**
 * Outputs Custom CSS for the page title
 *
 * @since 1.5.3
 */
function wpex_page_header_css( $output ) {

	// If page header is disabled we don't have to add any inline CSS to the site
	if ( ! wpex_has_page_header() ) {
		return $output;
	}

	// Get post ID
	$post_id = wpex_get_current_post_id();

	// Get header style
	$page_header_style = wpex_page_header_style();

	// Define var
	$css = $bg_img = $bg_color = $page_header_css = '';

	// Check if a header style is defined and make header style dependent tweaks
	if ( $page_header_style ) {

		// Customize background color
		if ( 'solid-color' == $page_header_style || 'background-image' == $page_header_style ) {
			$bg_color = get_post_meta( $post_id, 'wpex_post_title_background_color', true );
			if ( $bg_color ) {
				$page_header_css .='background-color: '. $bg_color .' !important;';
			}
		}

		// Background image Style
		if ( 'background-image' == $page_header_style ) {

			// Get background image
			$bg_img = wpex_page_header_background_image();

			// Add CSS for background image
			if ( $bg_img ) {

				// Add css for background image
				$page_header_css .= 'background-image: url('. $bg_img .' ) !important;background-position: 50% 0;-webkit-background-size: cover;-moz-background-size: cover;-o-background-size: cover;background-size: cover;';

			}

			// Custom height => Added to inner table NOT page header
			$title_height = wpex_get_mod( 'page_header_table_height' );
			if ( 'background-image' == get_post_meta( $post_id, 'wpex_post_title_style', true )
				&& $meta = get_post_meta( $post_id, 'wpex_post_title_height', true ) ) {
				$title_height = $meta;
			}
			$title_height = apply_filters( 'wpex_post_title_height', $title_height );
			if ( $title_height ) {
				$css .= '.page-header-table { height:'. wpex_sanitize_data( $title_height, 'px' ) .'; }';
			}

		}

		// Apply all css to the page-header class
		if ( ! empty( $page_header_css ) ) {
			$css .= '.page-header { '. $page_header_css .' }';
		}

		// Overlay Styles
		if ( $bg_img && 'background-image' == $page_header_style ) {

			$overlay_css = '';

			// Use bg_color for overlay background
			if ( $bg_color && 'bg_color' == wpex_get_page_header_overlay_style() ) {
				$overlay_css .= 'background-color: '. $bg_color .' !important;';
			}

			// Overlay opacity
			if ( $opacity = wpex_get_page_header_overlay_opacity() ) {
				$overlay_css .= 'opacity:'. $opacity .';-moz-opacity:'. $opacity .';-webkit-opacity:'. $opacity .';';

			}

			// Add overlay CSS
			if ( $overlay_css ) {
				$css .= '.background-image-page-header-overlay{'. $overlay_css .'}';
			}

		}


		// If css var isn't empty add to custom css output
		if ( ! empty( $css ) ) {
			$output .= $css;
		}

	}

	// Return output
	return $output;

}
add_filter( 'wpex_head_css', 'wpex_page_header_css' );