<?php
/**
 * Core theme functions - VERY IMPORTANT!!
 *
 * These functions are used throughout the theme and must be loaded
 * early on.
 *
 * Do not ever edit this file, if you need to make
 * adjustments, please use a child theme. If you aren't sure how, please ask!
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.2
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*-------------------------------------------------------------------------------*/
/* [ Table of contents ]
/*-------------------------------------------------------------------------------*

	# General
	# Sanitize Data
	# Parse HTML
	# Grids / Entries
	# Content Blocks ( Entrys & Posts )
	# Taxonomy & Terms
	# Sliders
	# Images
	# Buttons
	# Search Functions
	# Other

/*-------------------------------------------------------------------------------*/
/* [ General ]
/*-------------------------------------------------------------------------------*/

/**
 * Get Theme Branding
 *
 * @since 3.3.0
 */
function wpex_get_theme_branding() {
	$branding = WPEX_THEME_BRANDING;
	if ( $branding && 'disabled' != $branding ) {
		return $branding;
	}
}

/**
 * Return correct assets url for loading scripts
 *
 * @since 3.6.0
 */
if ( ! function_exists( 'wpex_asset_url' ) ) {
	function wpex_asset_url( $part = '' ) {
		return WPEX_THEME_URI . '/assets/' . $part;
	}
}

/**
 * Returns array of recommended plugins
 *
 * @since 3.3.3
 */
function wpex_recommended_plugins() {
	return apply_filters( 'wpex_recommended_plugins', array(
		'js_composer'          => array(
			'name'             => 'WPBakery Visual Composer',
			'slug'             => 'js_composer',
			'version'          => WPEX_VC_SUPPORTED_VERSION,
			'source'           => WPEX_FRAMEWORK_DIR_URI .'plugins/js_composer.zip',
			'required'         => false,
			'force_activation' => false,
		),
		'templatera'           => array(
			'name'             => 'Templatera',
			'slug'             => 'templatera',
			'source'           => WPEX_FRAMEWORK_DIR_URI .'plugins/templatera.zip',
			'version'          => '1.1.12',
			'required'         => false,
			'force_activation' => false,
		),
		'revslider'            => array(
			'name'             => 'Slider Revolution',
			'slug'             => 'revslider',
			'version'          => '5.4.3.1',
			'source'           => WPEX_FRAMEWORK_DIR_URI .'plugins/revslider.zip',
			'required'         => false,
			'force_activation' => false,
		),
		'contact-form-7'       => array(
			'name'             => 'Contact Form 7',
			'slug'             => 'contact-form-7',
			'required'         => false,
			'force_activation' => false,
		),
	) );
}

/**
 * Returns current URL
 *
 * @since 4.0
 */
function wpex_get_current_url() {
	global $wp;
	if ( $wp ) {
		return home_url( add_query_arg( array(), $wp->request ) );
	}
}

/**
 * Returns correct ID
 *
 * Fixes some issues with posts page and 3rd party plugins that use custom pages for archives
 * such as WooCommerce. So we can correctly get post_meta values
 *
 * @since 4.0
 */
function wpex_get_current_post_id() {

	// Default value is empty
	$id = '';

	// If singular get_the_ID
	if ( is_singular() ) {
		$id = get_queried_object_id();
		$id = $id ? $id : get_the_ID(); // backup
	}

	// Posts page
	elseif ( is_home() && $page_for_posts = get_option( 'page_for_posts' ) ) {
		$id = $page_for_posts;
	}

	// Apply filters and return
	return apply_filters( 'wpex_post_id', $id );

}

/**
 * Returns theme custom post types
 *
 * @since 1.3.3
 */
function wpex_theme_post_types() {
	$post_types = array( 'portfolio', 'staff', 'testimonials' );
	$post_types = array_combine( $post_types, $post_types );
	return apply_filters( 'wpex_theme_post_types', $post_types );
}

/**
 * Returns body font size
 * Used to convert EM values to PX values such as for responsive headings.
 *
 * @since 3.3.0
 */
function wpex_get_body_font_size() {
	$body_typo = wpex_get_mod( 'body_typography' );
	$font_size = ! empty( $body_typo['font-size'] ) ? $body_typo['font-size'] : 13;
	return apply_filters( 'wpex_get_body_font_size', $font_size );
}

/**
 * Echo the post URL
 *
 * @since 1.5.4
 */
function wpex_permalink( $post_id = '' ) {
	echo wpex_get_permalink( $post_id );
}

/**
 * Return the post URL
 *
 * @since 2.0.0
 */
function wpex_get_permalink( $post_id = '' ) {

	// If post ID isn't defined lets get it
	$post_id = $post_id ? $post_id : get_the_ID();

	// Check wpex_post_link custom field for custom link
	$redirect = wpex_get_post_redirect_link( $post_id );

	// If wpex_post_link custom field is defined return that otherwise return the permalink
	$permalink = $redirect ? esc_url( $redirect ) : get_permalink( $post_id );

	// Apply filters and return
	return apply_filters( 'wpex_permalink', $permalink );

}

/**
 * Get custom post link
 *
 * @since 4.1.2
 */
function wpex_get_post_redirect_link( $post_id = '' ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	return get_post_meta( $post_id, 'wpex_post_link', true );
}


/**
 * Return custom permalink
 *
 * @since 2.0.0
 */
function wpex_get_custom_permalink() {
	if ( $custom_link = get_post_meta( get_the_ID(), 'wpex_post_link', true ) ) {
		$custom_link = ( 'home_url' == $custom_link ) ? home_url( '/' ) : $custom_link;
		return esc_url( $custom_link );
	}
}

/**
 * Returns correct site layout
 *
 * @since 4.0
 */
function wpex_site_layout( $post_id = '' ) {

	// Check URL
	if ( ! empty( $_GET['site_layout'] ) ) {
		return esc_html( $_GET['site_layout'] );
	}

	// Get layout from theme mod
	$layout = wpex_get_mod( 'main_layout_style', 'full-width' );

	// Get post ID
	$post_id = $post_id ? $post_id : wpex_get_current_post_id();

	// Check meta
	if ( $post_id && $meta = get_post_meta( $post_id, 'wpex_main_layout', true ) ) {
		$layout = $meta;
	}

	// Apply filters
	$layout = apply_filters( 'wpex_main_layout', $layout );

	// Sanitize layout => Can't be empty!!
	$layout = $layout ? $layout : 'full-width';

	// Return layout
	return $layout;

}

/**
 * Returns correct content area layout
 *
 * @since 4.0
 */
function wpex_content_area_layout( $post_id = '' ) {

	// Check URL
	if ( ! empty( $_GET['post_layout'] ) ) {
		return esc_html( $_GET['post_layout'] );
	}

	// Define variables
	$class = 'right-sidebar';

	// Get post ID
	$post_id = $post_id ? $post_id : wpex_get_current_post_id();

	// Check meta first to override and return (prevents filters from overriding meta)
	if ( $post_id && $meta = get_post_meta( $post_id, 'wpex_post_layout', true ) ) {
		return $meta;
	}

	// Singular Page
	if ( is_page() ) {

		// Blog template
		if ( is_page_template( 'templates/blog.php' ) ) {
			$class = wpex_get_mod( 'blog_archives_layout', 'right-sidebar' );
		}

		// Landing Page
		if ( is_page_template( 'templates/landing-page.php' ) ) {
			$class = 'full-width';
		}

		// Attachment
		elseif ( is_attachment() ) {
			$class = 'full-width';
		}

		// All other pages
		else {
			$class = wpex_get_mod( 'page_single_layout', 'right-sidebar' );
		}

	} // End is_page

	// Singular Post
	elseif ( is_singular( 'post' ) ) {
		$class = wpex_get_mod( 'blog_single_layout', 'right-sidebar' );
	}

	// Attachment
	elseif ( is_singular( 'attachment' ) ) {
		$class = 'full-width';
	}

	// 404 page => must check before archives due to WP bug with pagination
	elseif ( is_404() ) {
		if ( ! wpex_get_mod( 'error_page_content_id' ) ) {
			$class = 'full-width';
		}
	}

	// Home
	elseif ( is_home() ) {
		$class = wpex_get_mod( 'blog_archives_layout', 'right-sidebar' );
	}

	// Search
	elseif ( is_search() ) {
		$class = get_theme_mod( 'search_layout', 'right-sidebar' );
	}

	// Standard Categories
	elseif ( is_category() ) {
		$class = wpex_get_mod( 'blog_archives_layout', 'right-sidebar' );
		$term  = get_query_var( 'cat' );
		if ( $term_data = get_option( "category_$term" ) ) {
			if ( ! empty( $term_data['wpex_term_layout'] ) ) {
				$class = $term_data['wpex_term_layout'];
			}
		}
	}

	// Archives
	elseif ( wpex_is_blog_query() ) {
		$class = wpex_get_mod( 'blog_archives_layout', 'right-sidebar' );
	}

	// All else
	else {
		$class = 'right-sidebar';
	}

	// Class should never be empty
	if ( empty( $class ) ) {
		$class = 'right-sidebar';
	}

	// Apply filters and return
	return apply_filters( 'wpex_post_layout_class', $class );

}

/**
 * Returns the correct sidebar ID
 *
 * @since  1.0.0
 */
function wpex_get_sidebar( $sidebar = 'sidebar', $post_id = '' ) {

	// Page Sidebar
	if ( is_page() ) {
		if ( wpex_get_mod( 'pages_custom_sidebar', true ) && ! is_page_template( 'templates/blog.php' ) ) {
			$sidebar = 'pages_sidebar';
		}
	}

	// Search Sidebar
	elseif ( is_search() ) {
		if ( wpex_get_mod( 'search_custom_sidebar', true ) ) {
			$sidebar = 'search_sidebar';
		}
	}

	// 404
	elseif ( is_404() ) {
		if ( wpex_get_mod( 'pages_custom_sidebar', true ) ) {
			$sidebar = 'pages_sidebar';
		}
	}

	/***
	 * FILTER    => Add filter for tweaking the sidebar display via child theme's
	 * IMPORTANT => Must be added before meta options so that it doesn't take priority
	 ***/
	$sidebar = apply_filters( 'wpex_get_sidebar', $sidebar );

	// Get current post id
	$post_id = $post_id ? $post_id : wpex_get_current_post_id();

	// Check meta option after filter so it always overrides
	if ( $meta = get_post_meta( $post_id, 'sidebar', true ) ) {
		$sidebar = $meta;
	}

	// Check term meta after filter so it always overrides
	// get_term_meta introduced in WP 4.4.0
	if ( function_exists( 'get_term_meta' ) ) {

		if ( is_singular() && ! is_page() ) {
			$meta = '';
			$post_type  = get_post_type();
			$taxonomies = get_object_taxonomies( $post_type );
			foreach( $taxonomies as $taxonomy ) {
				if ( $meta ) break; // stop loop we found a custom sidebar
				$terms = get_the_terms( get_the_ID(), $taxonomy );
				if ( $terms ) {
					foreach ( $terms as $term ) {
						if ( $meta ) break; // stop loop we found a custom sidebar
						$meta = get_term_meta( $term->term_id, 'wpex_sidebar', true );
					}
				}
			}
			$sidebar = $meta ? $meta : $sidebar;
		}

		// Taxonomies
		elseif ( is_tax() || is_tag() || is_category() ) {
			$term_id = get_queried_object()->term_id;
			$meta    = get_term_meta( $term_id, 'wpex_sidebar', true );
			$sidebar = ! empty( $meta ) ? $meta : $sidebar;
		}

	}

	// Never show empty sidebar
	if ( ! is_active_sidebar( $sidebar ) ) {
		$sidebar = 'sidebar';
	}

	// Return the correct sidebar
	return $sidebar;

}

/**
 * Returns the correct classname for any specific column grid
 *
 * @since 1.0.0
 */
function wpex_grid_class( $col = '4' ) {
	return apply_filters( 'wpex_grid_class', 'span_1_of_'. $col );
}

/**
 * Returns the correct gap class
 *
 * @since 1.0.0
 */
function wpex_gap_class( $gap = '' ) {
	return apply_filters( 'wpex_gap_class', 'gap-'. $gap );
}

/**
 * Outputs a theme heading
 *
 * @since 1.3.3
 */
function wpex_heading( $args = array() ) {

	// Define output
	$output = '';

	// Default tag
	$tag = esc_html( wpex_get_mod( 'theme_heading_tag' ) );
	$tag = $tag ? $tag : 'div';

	// Defaults
	$defaults = array(
		'echo'          => true,
		'apply_filters' => '',
		'content'       => '',
		'tag'           => $tag,
		'classes'       => array(),
	);

	// Add filters if defined
	if ( ! empty( $args['apply_filters'] ) ) {
		$args = apply_filters( 'wpex_heading_'. $args['apply_filters'], $args );
	}

	// Parse args
	$args = wp_parse_args( $args, $defaults );

	// Extract args
	extract( $args );

	// Return if text is empty
	if ( ! $content ) {
		return;
	}

	// Add custom classes
	$add_classes = $classes;
	$classes     = array(
		'theme-heading',
		wpex_get_mod( 'theme_heading_style' )
	);
	if ( $add_classes && is_array( $add_classes ) ) {
		$classes = array_merge( $classes, $add_classes );
	}

	// Turn classes into space seperated string
	$classes = implode( ' ', $classes );

	$output .= '<'. esc_attr( $tag ) .' class="'. esc_attr( $classes ) .'">';
		$output .= '<span class="text">'. $content .'</span>';
	$output .= '</'. esc_attr( $tag ) .'>';

	if ( $echo ) {
		echo $output;
	} else {
		return $output;
	}
}

/**
 * Provides translation support for plugins such as WPML
 *
 * @since 1.3.3
 * @todo Remove this function? I don't think it's needed...or make more use of it.
 */
if ( ! function_exists( 'wpex_element' ) ) {
	function wpex_element( $element ) {

		// Rarr
		if ( 'rarr' == $element ) {
			if ( is_rtl() ) {
				return '&larr;';
			} else {
				return '&rarr;';
			}
		}

		// Angle Right
		elseif ( 'angle_right' == $element ) {

			if ( is_rtl() ) {
				return '<span class="fa fa-angle-left"></span>';
			} else {
				return '<span class="fa fa-angle-right"></span>';
			}

		}

	}
}

/**
 * Returns correct hover animation class
 *
 * @since 2.0.0
 */
function wpex_hover_animation_class( $animation ) {
	return 'hvr hvr-'. $animation;
}

/**
 * Returns correct typography style class
 *
 * @since  2.0.2
 * @return string
 */
function wpex_typography_style_class( $style ) {
	$class = '';
	if ( $style
		&& 'none' != $style
		&& array_key_exists( $style, wpex_typography_styles() ) ) {
		$class = 'typography-'. $style;
	}
	return $class;
}

/**
 * Convert to array
 *
 * @since 2.0.0
 */
function wpex_string_to_array( $value = array() ) {

	// Return empty array if value is empty
	if ( empty( $value ) ) {
		return array();
	}

	// Check if array and not empty
	elseif ( ! empty( $value ) && is_array( $value ) ) {
		return $array;
	}

	// Create our own return
	else {

		// Define array
		$array = array();

		// Clean up value
		$items  = preg_split( '/\,[\s]*/', $value );

		// Create array
		foreach ( $items as $item ) {
			if ( strlen( $item ) > 0 ) {
				$array[] = $item;
			}
		}

		// Return array
		return $array;

	}

}

/**
 * Converts a dashicon into it's CSS
 *
 * @since 1.0.0
 */
function wpex_dashicon_css_content( $dashicon = '' ) {
	$css_content = 'f111';
	if ( $dashicon ) {
		$dashicons = wpex_get_dashicons_array();
		if ( isset( $dashicons[$dashicon] ) ) {
			$css_content = $dashicons[$dashicon];
		}
	}
	return $css_content;
}

/**
 * Returns correct Google Fonts URL if you want to change it to another CDN
 * such as the one in for China
 *
 * https://chineseseoshifu.com/blog/google-fonts-instable-in-china.html
 *
 * @since 3.3.2
 */
function wpex_get_google_fonts_url() {
	return esc_url( apply_filters( 'wpex_get_google_fonts_url', '//fonts.googleapis.com' ) );
}

/**
 * Returns array of widget areays
 *
 * @since 3.3.3
 */
function wpex_get_widget_areas() {
	global $wp_registered_sidebars;
	$widgets_areas = array();
	if ( ! empty( $wp_registered_sidebars ) ) {
		foreach ( $wp_registered_sidebars as $widget_area ) {
			$name = isset ( $widget_area['name'] ) ? $widget_area['name'] : '';
			$id = isset ( $widget_area['id'] ) ? $widget_area['id'] : '';
			if ( $name && $id ) {
				$widgets_areas[$id] = $name;
			}
		}
	}
	return $widgets_areas;
}

/*-------------------------------------------------------------------------------*/
/* [ Sanitize Data ]
/*-------------------------------------------------------------------------------*/

/**
 * Applies correct functions to the content to render p tags and shortcodes,
 * much like the_content filter but without causing conflicts with 3rd party plugins.
 *
 * @since 4.1
 */
function wpex_the_content( $content = '', $context = '' ) {
	if ( ! $content ) {
		return;
	}
	$content = do_shortcode( shortcode_unautop( wpautop( wp_kses_post( $content ) ) ) );
	return apply_filters( 'wpex_the_content', $content, $context );
}

/**
 * Echo escaped post title
 *
 * @since 2.0.0
 */
function wpex_esc_title( $post = '' ) {
	echo wpex_get_esc_title( $post );
}

/**
 * Return escaped post title
 *
 * @since 1.5.4
 */
function wpex_get_esc_title( $post = '' ) {
	return the_title_attribute( array(
		'echo' => false,
		'post' => $post,
	) );
}

/**
 * Escape attribute with fallback
 *
 * @since 3.3.5
 */
function wpex_esc_attr( $val = null, $fallback = null ) {
	if ( $val = esc_attr( $val ) ) {
		return $val;
	} else {
		return $fallback;
	}
}

/**
 * Escape html with fallback
 *
 * @since 3.3.5
 */
function wpex_esc_html( $val = null, $fallback = null ) {
	if ( $val = esc_html( $val ) ) {
		return $val;
	} else {
		return $fallback;
	}
}

/**
 * Sanitize numbers with fallback
 *
 * @since 3.3.5
 */
function wpex_intval( $val = null, $fallback = null ) {
	if ( 0 == $val ) {
		return 0; // Some settings may need this
	} elseif ( $val = intval( $val ) ) {
		return $val;
	} else {
		return $fallback;
	}
}

/*-------------------------------------------------------------------------------*/
/* [ Parse HTML ]
/*-------------------------------------------------------------------------------*/

/**
 * Takes an array of attributes and outputs them for HTML
 *
 * @since 3.4.0
 */
function wpex_parse_html( $tag = '', $attrs = array(), $content = '' ) {
	$attrs = wpex_parse_attrs( $attrs );
	$output = '<'. $tag .' '. $attrs .'>';
	if ( $content ) {
		$output .= $content;
	}
	$output .= '</' . $tag . '>';
	return $output;
}

/**
 * Parses an html data attribute
 *
 * @since 3.4.0
 */
function wpex_parse_attrs( $attrs = null ) {

	if ( ! $attrs || ! is_array( $attrs ) ) {
		return $attrs;
	}

	// Define output
	$output = '';

	// Loop through attributes
	foreach ( $attrs as $key => $val ) {

		// If the attribute is an array convert to string
		if ( is_array( $val ) ) {
			$val = array_filter( $val, 'trim' ); // Remove extra space
			$val = implode( ' ', $val );
		}

		// Val required => no need for empty attributes unless it's a data attribute
		if ( ! $val && strpos( $key, 'data' ) === false ) {
			continue;
		}

		// Sanitize rel attribute
		if ( 'rel' == $key && 'nofollow' != $val ) {
			continue;
		}

		// Sanitize ID
		if ( 'id' == $key ) {
			$val = trim ( str_replace( '#', '', $val ) );
			$val = str_replace( ' ', '', $val );
		}

		// Sanitize targets
		if ( 'target' == $key ) {
			$val = ( strpos( $val, 'blank' ) !== false ) ? '_blank' : '';
		}

		// Add attribute to output
		if ( $val ) {
			if ( in_array( $key, array( 'download' ) ) ) {
				$output .= ' '. trim( $val ); // Used for example on total button download attribute
			} else {
				$needle = ( 'data' == $key ) ? 'data-' : $key .'="';
				if ( strpos( $val, $needle ) !== false ) {
					$output .= ' '. trim( $val ); // Already has tag added
				} else {
					$output .= ' '. $key .'="'. $val .'"';
				}
			}
		}

	}

	// Return output
	return trim( $output );

}

/*-------------------------------------------------------------------------------*/
/* [ Grids/Entries ]
/*-------------------------------------------------------------------------------*/

/**
 * Returns correct classes for archive grid
 *
 * @since 3.6.0
 */
function wpex_get_archive_grid_class() {

	// Define class array
	$class = array( 'archive-grid', 'entries', 'clr' );

	// Add row class for multi column grid
	$class[] = 'wpex-row';

	// Apply filters
	$class = apply_filters( 'wpex_get_archive_grid_class', $class );

	// Return classes as a string
	return implode( ' ', $class );

}

/**
 * Returns correct grid columns for custom types
 *
 * @since 3.6.0
 */
function wpex_get_grid_entry_columns() {
	return apply_filters( 'wpex_get_grid_entry_columns', 1 );
}

/**
 * Returns correct classes for archive grid entries
 *
 * @since 3.6.0
 */
function wpex_get_archive_grid_entry_class() {

	// Define class array
	$class = array( 'cpt-entry', 'col', 'clr' );

	// Add columns class
	$columns = wpex_get_grid_entry_columns();
	if ( '1' != $columns ) {
		$class[] = wpex_grid_class( $columns );
		global $wpex_count;
		if ( $wpex_count ) {
			$class[] = 'col-'. $wpex_count;
		}
	} else {
		$class[] = 'span_1_of_1';
	}

	// Apply filters and return
	return apply_filters( 'wpex_get_archive_grid_class', $class );

}

/*-------------------------------------------------------------------------------*/
/* [ Content Blocks ( Entrys & Posts ) ]
/*-------------------------------------------------------------------------------*/

/**
 * Returns array of blocks for the entry post type layout
 *
 * @since 3.2.0
 */
function wpex_entry_blocks() {
	$type = get_post_type();
	return apply_filters( 'wpex_'. $type .'_entry_blocks', array(
		'media'    => 'media',
		'title'    => 'title',
		'meta'     => 'meta',
		'content'  => 'content',
		'readmore' => 'readmore',
	), $type );
}

/**
 * Returns array of blocks for the single post type layout
 *
 * @since 3.2.0
 */
function wpex_single_blocks( $post_type = '' ) {

	// Define empty blocks array
	$blocks = array();

	// Get type
	$type = $post_type ? $post_type : get_post_type();

	// Pages
	if ( 'page' == $type ) {
		$blocks = wpex_get_mod( 'page_composer', array( 'content' ) );
	}

	// Custom Types
	else {
		$blocks = array( 'media', 'title', 'meta', 'post-series', 'content', 'page-links', 'share', 'comments' );
	}

	// Convert to array if not already (for customizer settings)
	if ( ! is_array( $blocks ) ) {
		$blocks = explode( ',', $blocks );
	}

	// Set keys equal to values for easier filter removal
	$blocks = $blocks ? array_combine( $blocks, $blocks ) : array();

	// Type specific filter //@todo remove extra filter and update snippets/docs
	// This one is deprecated used filter defined below
	$blocks = apply_filters( 'wpex_'. $type .'_single_blocks', $blocks, $type );

	// Needed because of plugins using archives such as bbPress - @todo deprecate previouos filter?
	$blocks = apply_filters( 'wpex_single_blocks', $blocks, $type );

	// Sanitize & return blocks
	return $blocks;

}

/**
 * Returns array of blocks for the entry meta
 *
 * @since 3.6.0
 */
function wpex_meta_blocks() {
	return apply_filters( 'wpex_meta_blocks', array( 'date', 'author', 'categories', 'comments' ), get_post_type() );
}

/*-------------------------------------------------------------------------------*/
/* [ Taxonomy & Terms ]
/*-------------------------------------------------------------------------------*/

/**
 * Returns 1st term name
 *
 * @since 3.2.0
 */
function wpex_get_first_term_name( $post_id = '', $taxonomy = 'category' ) {
	if ( ! taxonomy_exists( $taxonomy ) ) {
		return;
	}
	$post_id = $post_id ? $post_id : get_the_ID();
	$terms   = wp_get_post_terms( $post_id, $taxonomy );
	if ( ! is_wp_error( $terms ) && ! empty( $terms[0] ) ) {
		return $terms[0]->name;
	}
}

/**
 * Returns 1st taxonomy of any taxonomy with a link
 *
 * @since 3.2.0
 */
function wpex_get_first_term_link( $post_id = '', $taxonomy = 'category' ) {
	if ( ! taxonomy_exists( $taxonomy ) ) {
		return;
	}
	$post_id = $post_id ? $post_id : get_the_ID();
	$terms   = wp_get_post_terms( $post_id, $taxonomy );
	if ( ! is_wp_error( $terms ) && ! empty( $terms[0] ) ) {
		$attrs = array(
			'href'  => esc_url( get_term_link( $terms[0], $taxonomy ) ),
			'class' => 'term-' . $terms[0]->term_id,
			'title' => esc_attr( $terms[0]->name ),
		);
		return wpex_parse_html( 'a', $attrs, esc_html( $terms[0]->name ) );
	}
}

/**
 * Echos 1st taxonomy of any taxonomy with a link
 *
 * @since 2.0.0
 */
function wpex_first_term_link( $post_id = '', $taxonomy = 'category' ) {
	echo wpex_get_first_term_link( $post_id, $taxonomy );
}

/**
 * Returns a list of terms for specific taxonomy
 *
 * @since 2.1.3
 */
function wpex_get_list_post_terms( $taxonomy = 'category', $show_links = true ) {
	return wpex_list_post_terms( $taxonomy, $show_links, false );
}

/**
 * List terms for specific taxonomy
 *
 * @since 1.6.3
 */
function wpex_list_post_terms( $taxonomy = 'category', $show_links = true, $echo = true ) {

	// Make sure taxonomy exists
	if ( ! taxonomy_exists( $taxonomy ) ) {
		return;
	}

	// Get terms
	$list_terms = array();
	$terms      = wp_get_post_terms( get_the_ID(), $taxonomy );

	// Return if no terms are found
	if ( ! $terms ) {
		return;
	}

	// Loop through terms
	foreach ( $terms as $term ) {

		if ( $show_links ) {

			$attrs = array(
				'href'  => esc_url( get_term_link( $term->term_id, $taxonomy ) ),
				'title' => esc_attr( $term->name ),
				'class' => 'term-' . $term->term_id,
			);

			$list_terms[] = wpex_parse_html( 'a', $attrs, esc_html( $term->name ) );

		} else {

			$attrs = array(
				'class' => 'term-' . $term->term_id,
			);

			$list_terms[] = wpex_parse_html( 'span', $attrs, esc_html( $term->name ) );

		}
	}

	// Turn into comma seperated string
	if ( $list_terms && is_array( $list_terms ) ) {
		$list_terms = implode( ', ', $list_terms );
	} else {
		return;
	}

	// Apply filters (can be used to change the comas to something else)
	$list_terms = apply_filters( 'wpex_list_post_terms', $list_terms, $taxonomy );

	// Echo terms
	if ( $echo ) {
		echo $list_terms;
	} else {
		return $list_terms;
	}

}

/**
 * Returns the "category" taxonomy for a given post type
 *
 * @since 2.0.0
 */
function wpex_get_post_type_cat_tax( $post_type = '' ) {

	// Get the post type
	$post_type = $post_type ? $post_type : get_post_type();

	// Return taxonomy
	if ( 'post' == $post_type ) {
		$tax = 'category';
	} elseif ( 'portfolio' == $post_type ) {
		$tax = 'portfolio_category';
	} elseif ( 'staff' == $post_type ) {
		$tax = 'staff_category';
	} elseif ( 'testimonials' == $post_type ) {
		$tax = 'testimonials_category';
	} elseif ( 'product' == $post_type ) {
		$tax = 'product_cat';
	} elseif ( 'tribe_events' == $post_type ) {
		$tax = 'tribe_events_cat';
	} elseif ( 'download' == $post_type ) {
		$tax = 'download_category';
	} else {
		$tax = false;
	}

	// Apply filters & return
	return apply_filters( 'wpex_get_post_type_cat_tax', $tax );

}

/**
 * Retrieve all term data
 *
 * @since 2.1.0
 */
function wpex_get_term_data() {
	return get_option( 'wpex_term_data' );
}

/*-------------------------------------------------------------------------------*/
/* [ Sliders ]
/*-------------------------------------------------------------------------------*/

/**
 * Returns data attributes for post sliders
 *
 * @since 2.0.0
 */
function wpex_slider_data( $args = '' ) {

	// Define main vars
	$defaults = array(
		'filter_tag'        => 'wpex_slider_data',
		'auto-play'         => 'false',
		'buttons'           => 'false',
		'fade'              => 'true',
		'loop'              => 'true',
		'thumbnails-height' => '60',
		'thumbnails-width'  => '60',
	);

	// Parse arguments
	$args = wp_parse_args( $args, $defaults );

	// Extract args
	extract( $args );

	// Apply filters for child theming
	$args = apply_filters( $filter_tag, $args );

	// Turn array into HTML
	$return = '';
	foreach ( $args as $key => $val ) {
		$return .= ' data-'. $key .'="'. $val .'"';
	}

	// Return
	echo $return;

}

/*-------------------------------------------------------------------------------*/
/* [ Images ]
/*-------------------------------------------------------------------------------*/

/**
 * Echo animation classes for entries
 *
 * @since 1.1.6
 */
function wpex_entry_image_animation_classes() {
	echo wpex_get_entry_image_animation_classes();
}

/**
 * Returns animation classes for entries
 *
 * @since 1.1.6
 */
function wpex_get_entry_image_animation_classes() {

	// Empty by default
	$classes = '';

	// Only used for standard posts now
	if ( 'post' != get_post_type( get_the_ID() ) ) {
		return;
	}

	// Get blog classes
	if ( wpex_get_mod( 'blog_entry_image_hover_animation' ) ) {
		$classes = ' wpex-image-hover '. wpex_get_mod( 'blog_entry_image_hover_animation' );
	}

	// Apply filters
	return apply_filters( 'wpex_entry_image_animation_classes', $classes );

}

/**
 * Returns attachment data
 *
 * @since 2.0.0
 */
function wpex_get_attachment_data( $attachment = '', $return = 'array' ) {

	// Initial checks
	if ( ! $attachment || 'none' == $return ) {
		return;
	}

	// Sanitize return value
	$return = $return ? $return : 'array';

	// Return data
	if ( 'array' == $return ) {
		return array(
			'url'         => get_post_meta( $attachment, '_wp_attachment_url', true ),
			'src'         => wp_get_attachment_url( $attachment ),
			'alt'         => get_post_meta( $attachment, '_wp_attachment_image_alt', true ),
			'title'       => get_the_title( $attachment ),
			'caption'     => get_post_field( 'post_excerpt', $attachment ),
			'description' => get_post_field( 'post_content', $attachment ),
			'video'       => esc_url( get_post_meta( $attachment, '_video_url', true ) ),
		);
	} elseif ( 'url' == $return ) {
		return get_post_meta( $attachment, '_wp_attachment_url', true );
	} elseif ( 'src' == $return ) {
		return get_post_meta( $attachment, '_wp_attachment_url', true );
	} elseif ( 'alt' == $return ) {
		return get_post_meta( $attachment, '_wp_attachment_image_alt', true );
	} elseif ( 'title' == $return ) {
		return get_the_title( $attachment );
	} elseif ( 'caption' == $return ) {
		return get_post_field( 'post_excerpt', $attachment );
	} elseif ( 'description' == $return ) {
		return get_post_field( 'post_content', $attachment );
	} elseif ( 'video' == $return ) {
		return esc_url( get_post_meta( $attachment, '_video_url', true ) );
	}

	// Set alt to title if alt not defined => Removed in v4.0
	//$array['alt'] = $array['alt'] ? $array['alt'] : $array['title'];

}

/**
 * Checks if a featured image has a caption
 *
 * @since 2.0.0
 */
function wpex_featured_image_caption( $post_id = '' ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	return get_post_field( 'post_excerpt', get_post_thumbnail_id( $post_id ) );
}

/**
 * Echo lightbox image URL
 *
 * @since 2.0.0
 */
function wpex_lightbox_image( $attachment = '' ) {
	echo wpex_get_lightbox_image( $attachment );
}

/**
 * Returns lightbox image URL.
 *
 *  @since 2.0.0
 */
function wpex_get_lightbox_image( $attachment = '' ) {

	// Get attachment if empty (in loop)
	if ( ! $attachment ) {
		if ( 'attachment' == get_post_type() ) {
			$attachment = get_the_ID();
		} else {
			$attachment = get_post_thumbnail_id();
		}
	}

	// If the attachment is an ID lets get the URL
	if ( is_numeric( $attachment ) ) {
		$image = '';
	} elseif ( is_array( $attachment ) ) {
		return $attachment[0];
	} else {
		return $attachment;
	}

	// Sanitize data
	$image = wpex_get_post_thumbnail_url( array(
		'attachment' => $attachment,
		'image'      => $image,
		'size'       => apply_filters( 'wpex_get_lightbox_image_size', 'lightbox' ),
		'retina'     => false, // no need to create retina image for this
	) );

	// Return escaped image
	return esc_url( $image );
}

/**
 * Placeholder Image
 *
 * @since 2.1.0
 */
function wpex_placeholder_img_src() {
	return apply_filters( 'wpex_placeholder_img_src', wpex_asset_url( '/images/placeholder.png' ) );
}

/**
 * Blank Image
 *
 * @since 2.1.0
 */
function wpex_blank_img_src() {
	return esc_url( WPEX_THEME_URI .'/images/slider-pro/blank.png' );
}

/**
 * Returns correct image hover classnames
 *
 * @since 2.0.0
 */
function wpex_image_hover_classes( $style = '' ) {
	if ( ! $style ) {
		return;
	}
	$classes   = array( 'wpex-image-hover' );
	$classes[] = $style;
	return implode( ' ', $classes );
}

/**
 * Returns correct image rendering class
 *
 * @since 2.0.0
 */
function wpex_image_rendering_class( $rendering ) {
	return 'image-rendering-'. $rendering;
}

/**
 * Returns correct image filter class
 *
 * @since 2.0.0
 */
function wpex_image_filter_class( $filter ) {
	if ( ! $filter || 'none' == $filter ) {
		return;
	}
	return 'image-filter-'. $filter;
}

/*-------------------------------------------------------------------------------*/
/* [ Buttons ]
/*-------------------------------------------------------------------------------*/

/**
 * Returns correct social button class
 *
 * @since 3.0.0
 */
function wpex_get_social_button_class( $style = 'default' ) {

	// Default style is empty
	if ( 'default' == $style || ! $style ) {
		$style = apply_filters( 'wpex_default_social_button_style', 'flat-rounded' );
	}

	// None
	if ( 'none' == $style ) {
		$style = 'wpex-social-btn-no-style';
	}

	// Minimal
	elseif ( 'minimal' == $style ) {
		$style = 'wpex-social-btn-minimal wpex-social-color-hover';
	} elseif ( 'minimal-rounded' == $style ) {
		$style = 'wpex-social-btn-minimal wpex-social-color-hover wpex-semi-rounded';
	} elseif ( 'minimal-round' == $style ) {
		$style = 'wpex-social-btn-minimal wpex-social-color-hover wpex-round';
	}

	// Flat
	elseif ( 'flat' == $style ) {
		$style = 'wpex-social-btn-flat wpex-social-color-hover wpex-bg-gray';
	} elseif ( 'flat-rounded' == $style ) {
		$style = 'wpex-social-btn-flat wpex-social-color-hover wpex-semi-rounded';
	} elseif ( 'flat-round' == $style ) {
		$style = 'wpex-social-btn-flat wpex-social-color-hover wpex-round';
	}

	// Flat Color
	elseif ( 'flat-color' == $style ) {
		$style = 'wpex-social-btn-flat wpex-social-bg';
	} elseif ( 'flat-color-rounded' == $style ) {
		$style = 'wpex-social-btn-flat wpex-social-bg wpex-semi-rounded';
	} elseif ( 'flat-color-round' == $style ) {
		$style = 'wpex-social-btn-flat wpex-social-bg wpex-round';
	}

	// 3D
	elseif ( '3d' == $style ) {
		$style = 'wpex-social-btn-3d';
	} elseif ( '3d-color' == $style ) {
		$style = 'wpex-social-btn-3d wpex-social-bg';
	}

	// Black
	elseif ( 'black' == $style ) {
		$style = 'wpex-social-btn-black';
	} elseif ( 'black-rounded' == $style ) {
		$style = 'wpex-social-btn-black wpex-semi-rounded';
	} elseif ( 'black-round' == $style ) {
		$style = 'wpex-social-btn-black wpex-round';
	}

	// Black + Color Hover
	elseif ( 'black-ch' == $style ) {
		$style = 'wpex-social-btn-black-ch wpex-social-bg-hover';
	} elseif ( 'black-ch-rounded' == $style ) {
		$style = 'wpex-social-btn-black-ch wpex-social-bg-hover wpex-semi-rounded';
	} elseif ( 'black-ch-round' == $style ) {
		$style = 'wpex-social-btn-black-ch wpex-social-bg-hover wpex-round';
	}

	// Graphical
	elseif ( 'graphical' == $style ) {
		$style = 'wpex-social-bg wpex-social-btn-graphical';
	} elseif ( 'graphical-rounded' == $style ) {
		$style = 'wpex-social-bg wpex-social-btn-graphical wpex-semi-rounded';
	} elseif ( 'graphical-round' == $style ) {
		$style = 'wpex-social-bg wpex-social-btn-graphical wpex-round';
	}

	// Rounded
	elseif ( 'bordered' == $style ) {
		$style = 'wpex-social-btn-bordered wpex-social-border wpex-social-color';
	} elseif ( 'bordered-rounded' == $style ) {
		$style = 'wpex-social-btn-bordered wpex-social-border wpex-semi-rounded wpex-social-color';
	} elseif ( 'bordered-round' == $style ) {
		$style = 'wpex-social-btn-bordered wpex-social-border wpex-round wpex-social-color';
	}

	// Apply filters & return style
	return apply_filters( 'wpex_get_social_button_class', 'wpex-social-btn '. $style );
}

/**
 * Returns correct theme button classes based on args
 *
 * @since 3.2.0
 */
function wpex_get_button_classes( $style = '', $color = '', $size = '', $align = '' ) {

	// Extract if style is an array of arguments
	if ( is_array( $style ) ) {
		extract( $style );
	}

	// Main classes
	if ( 'plain-text' == $style ) {
		$classes = 'theme-txt-link';
	} elseif ( $style ) {
		$classes = 'theme-button '. $style;
	} else {
		$classes = 'theme-button';
	}

	// Color
	if ( $color ) {
		$classes .= ' '. $color;
	}

	// Size
	if ( $size ) {
		$classes .= ' '. $size;
	}

	// Align
	if ( $align ) {
		$classes .= ' align-'. $align;
	}

	// Apply filters and return classes
	return apply_filters( 'wpex_get_theme_button_classes', $classes, $style, $color, $size, $align );
}

/*-------------------------------------------------------------------------------*/
/* [ Search Functions ]
/*-------------------------------------------------------------------------------*/

/**
 * Defines your default search results page style
 *
 * @since 1.5.4
 */
function wpex_search_results_style() {
	return apply_filters( 'wpex_search_results_style', wpex_get_mod( 'search_style', 'default' ) );
}

/*-------------------------------------------------------------------------------*/
/* [ Other ]
/*-------------------------------------------------------------------------------*/

/**
 * Check user access
 *
 * @since 4.0
 */
function wpex_user_can_access( $check, $custom_callback = '' ) {

	// Logged in acccess
	if ( 'logged_in' == $check ) {
		
		return is_user_logged_in();

	}

	// Logged out access
	elseif ( 'logged_out' == $check ) {
		
		return is_user_logged_in() ? false : true;

	}

	// Custom Access
	elseif ( 'custom' == $check ) {

		if ( ! is_callable( $custom_callback ) ) {
			return true;
		}
		
		return call_user_func( $custom_callback );

	}

	// Return true if all else fails
	return true;

}


/**
 * Display user social links
 *
 * @since 4.0
 */
function wpex_get_user_social_profile_settings_array() {
	return apply_filters( 'wpex_get_user_social_profile_settings_array', array(
		'twitter'    => 'Twitter',
		'facebook'   => 'Facebook',
		'googleplus' => 'Google +',
		'linkedin'   => 'LinkedIn',
		'pinterest'  => 'Pinterest',
		'instagram'  => 'Instagram',
	) );
}

/**
 * Display user social links
 *
 * @since 4.0
 */
function wpex_get_user_social_links( $user_id = '', $display = 'icons', $attr = '' ) {

	if ( ! $user_id ) {
		return;
	}

	$output = '';

	$settings = wpex_get_user_social_profile_settings_array();

	foreach ( $settings as $id => $label ) {

		if ( $url = get_the_author_meta( 'wpex_'. $id, $user_id ) ) {

			$default_attr = array(
				'href'  => esc_url( $url ),
				'class' => '',
				'title' => trim( strip_tags( $label ) ),
			);

			$item_attr = wp_parse_args( $attr, $default_attr );

			if ( 'icons' == $display ) {

				$icon = ( 'googleplus' == $id ) ? 'google-plus' : $id;

				$label = '<span class="fa fa-'. $icon .'"></span>';

				$item_attr['class'] .= ' wpex-'. $icon;

			} else {

				$label = strip_tags( $label );

			}

			$html = '<a';

			foreach ( $item_attr as $name => $value ) {

				$html .= ' '. $name .'="'. $value .'"';

        	}

			$html .= '>'. $label .'</a>';

			$output .= $html;

		}

	}

	return $output;

}

/**
 * Get star rating
 *
 * @since 4.0
 */
function wpex_get_star_rating( $rating = '', $post_id = '' ) {

	// Post id
	$post_id = $post_id ? $post_id : get_the_ID();

	// Define rating
	$rating = $rating ? $rating : get_post_meta( $post_id, 'wpex_post_rating', true );

	// Return if no rating
	if ( ! $rating ) {
		return false;
	}

	// Sanitize
	else {
		$rating = abs( $rating );
	}

	$output = '';

	// Star fonts
	$full_star  = '<span class="fa fa-star"></span>';
	$half_star  = '<span class="fa fa-star-half-full"></span>';
	$empty_star = '<span class="fa fa-star-o"></span>';

	// Integers
	if ( ( is_numeric( $rating ) && ( intval( $rating ) == floatval( $rating ) ) ) ) {
		$output = str_repeat( $full_star, $rating );
		if ( $rating < 5 ) {
			$output .= str_repeat( $empty_star, 5 - $rating );
		}

	// Fractions
	} else {
		$rating = intval( $rating );
		$output = str_repeat( $full_star, $rating );
		$output .= $half_star;
		if ( $rating < 5 ) {
			$output .= str_repeat( $empty_star, 4 - $rating );
		}
	}

	// Return output
	return $output;

}

/**
 * Returns string version of WP core get_post_class
 *
 * @since 3.5.0
 */
function wpex_get_post_class( $class = '', $post_id = null ) {
	return 'class="' . implode( ' ', get_post_class( $class, $post_id ) ) . '"';
}

/**
 * Check if the header supports aside content
 *
 * @since 3.2.0
 */
function wpex_disable_google_services() {
	return apply_filters( 'wpex_disable_google_services', wpex_get_mod( 'disable_gs', false ) );
}

/**
 * Minify CSS
 *
 * @since 1.6.3
 */
function wpex_minify_css( $css = '' ) {

	// Return if no CSS
	if ( ! $css ) return;

	// Normalize whitespace
	$css = preg_replace( '/\s+/', ' ', $css );

	// Remove ; before }
	$css = preg_replace( '/;(?=\s*})/', '', $css );

	// Remove space after , : ; { } */ >
	$css = preg_replace( '/(,|:|;|\{|}|\*\/|>) /', '$1', $css );

	// Remove space before , ; { }
	$css = preg_replace( '/ (,|;|\{|})/', '$1', $css );

	// Strips leading 0 on decimal values (converts 0.5px into .5px)
	$css = preg_replace( '/(:| )0\.([0-9]+)(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}.${2}${3}', $css );

	// Strips units if value is 0 (converts 0px to 0)
	$css = preg_replace( '/(:| )(\.?)0(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}0', $css );

	// Trim
	$css = trim( $css );

	// Return minified CSS
	return $css;

}

/**
 * Allow to remove method for an hook when, it's a class method used and class doesn't have global for instanciation
 *
 * @since 3.4.0
 */
function wpex_remove_class_filter( $hook_name = '', $class_name ='', $method_name = '', $priority = 0 ) {
	global $wp_filter;

	// Make sure class exists
	if ( ! class_exists( $class_name ) ) {
		return false;
	}

	// Take only filters on right hook name and priority
	if ( ! isset($wp_filter[$hook_name][$priority] ) || ! is_array( $wp_filter[$hook_name][$priority] ) ) {
		return false;
	}

	// Loop on filters registered
	foreach( (array) $wp_filter[$hook_name][$priority] as $unique_id => $filter_array ) {

		// Test if filter is an array ! (always for class/method)
		// @todo consider using has_action instead
		// @link https://make.wordpress.org/core/2016/09/08/wp_hook-next-generation-actions-and-filters/
		if ( isset( $filter_array['function'] ) && is_array( $filter_array['function'] ) ) {

			// Test if object is a class, class and method is equal to param !
			if ( is_object( $filter_array['function'][0] )
				&& get_class( $filter_array['function'][0] )
				&& get_class( $filter_array['function'][0] ) == $class_name
				&& $filter_array['function'][1] == $method_name
			) {
				if ( isset( $wp_filter[$hook_name] ) ) {
					// WP 4.7
					if ( is_object( $wp_filter[$hook_name] ) ) {
						unset( $wp_filter[$hook_name]->callbacks[$priority][$unique_id] );
					}
					// WP 4.6
					else {
						unset( $wp_filter[$hook_name][$priority][$unique_id] );
					}
				}
			}

		}

	}
	return false;
}