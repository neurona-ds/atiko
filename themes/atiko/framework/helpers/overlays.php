<?php
/**
 * Create awesome overlays for image hovers
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.0
 */

/**
 * Displays the Overlay HTML
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'wpex_overlay' ) ) {

	function wpex_overlay( $position = 'inside_link', $style = '', $args = array() ) {

		// If style is set to none lets bail
		if ( 'none' == $style ) {
			return;
		}

		// If style not defined get correct style based on theme_mods
		elseif ( ! $style ) {
			$style = wpex_overlay_style();
		}

		// If style is defined lets locate and include the overlay template
		if ( $style ) {

			// Add position to args
			$args['overlay_position'] = $position;

			// Add new action for loading custom templates
			do_action( 'wpex_pre_include_overlay_template', $style, $args );

			// Load the overlay template
			$overlays_dir = 'partials/overlays/';
			$template = $overlays_dir . $style . '.php';
			$template = locate_template( $template, false );

			// Only load template if it exists
			if ( $template ) {
				include( $template );
			}

		}

	}

}

/**
 * Create an array of overlay styles so they can be altered via child themes
 *
 * @since 1.0.0
 */
function wpex_overlay_styles_array() {
	return apply_filters( 'wpex_overlay_styles_array', array(
		''                                => __( 'None', 'total' ),
		'hover-button'                    => __( 'Hover Button', 'total' ),
		'magnifying-hover'                => __( 'Magnifying Glass Hover', 'total' ),
		'plus-hover'                      => __( 'Plus Icon Hover', 'total' ),
		'plus-two-hover'                  => __( 'Plus Icon #2 Hover', 'total' ),
		'plus-three-hover'                => __( 'Plus Icon #3 Hover', 'total' ),
		'view-lightbox-buttons-buttons'   => __( 'View/Lightbox Icons Hover', 'total' ),
		'view-lightbox-buttons-text'      => __( 'View/Lightbox Text Hover', 'total' ),
		'title-center'                    => __( 'Title Centered', 'total' ),
		'title-bottom'                    => __( 'Title Bottom', 'total' ),
		'title-bottom-see-through'        => __( 'Title Bottom See Through', 'total' ),
		'title-push-up'                   => __( 'Title Push Up', 'total' ),
		'title-excerpt-hover'             => __( 'Title + Excerpt Hover', 'total' ),
		'title-category-hover'            => __( 'Title + Category Hover', 'total' ),
		'title-category-visible'          => __( 'Title + Category Visible', 'total' ),
		'title-date-hover'                => __( 'Title + Date Hover', 'total' ),
		'title-date-visible'              => __( 'Title + Date Visible', 'total' ),
		'categories-title-bottom-visible' => __( 'Categories + Title Bottom Visible', 'total' ),
		'slideup-title-white'             => __( 'Slide-Up Title White', 'total' ),
		'slideup-title-black'             => __( 'Slide-Up Title Black', 'total' ),
		'category-tag'                    => __( 'Category Tag', 'total' ),
		'category-tag-two'                => __( 'Category Tag', 'total' ) .' 2',
		'thumb-swap'                      => __( 'Secondary Image Swap', 'total' ),
	) );
}

/**
 * Returns the overlay type depending on your theme options & post type
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'wpex_overlay_style' ) ) {

	function wpex_overlay_style( $style = '' ) {

		$style = $style ? $style : get_post_type();
		
		if ( 'portfolio' == $style ) {
			$style = wpex_get_mod( 'portfolio_entry_overlay_style' );
		} elseif ( 'staff' == $style ) {
			$style = wpex_get_mod( 'staff_entry_overlay_style' );
		}

		return apply_filters( 'wpex_overlay_style', $style );

	}

}

/**
 * Returns the correct overlay Classname
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'wpex_overlay_classes' ) ) {

	function wpex_overlay_classes( $style = '' ) {

		// Return if style is none
		if ( 'none' == $style ) {
			return;
		}

		// Sanitize style
		$style = $style ? $style : wpex_overlay_style();

		// Return classes
		if ( $style ) {
			return 'overlay-parent overlay-parent-' . $style;
		}
		
	}
	
}