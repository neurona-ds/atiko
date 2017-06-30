<?php
/**
 * Visual Composer Helper Functions
 *
 * @package Total WordPress Theme
 * @subpackage Visual Composer
 * @version 4.2.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check if VC theme mode is enabled
 *
 * @since 3.5.0
 */
function vcex_theme_mode_check() {
	$theme_mode = wpex_get_mod( 'visual_composer_theme_mode', true );
	if ( vc_license()->isActivated() ) {
		$theme_mode = false; // disable if VC is active
	}
	return $theme_mode;
}

/**
 * Displays notice when functions aren't found
 *
 * @since 3.5.0
 */
function vcex_function_needed_notice() {
	echo '<div class="vcex-function-needed">This module can not work without the required functions. Please make sure all your plugins and WordPress is up to date. If you still have issues contact the developer for assistance.</div>';
}

/**
 * Returns animation class and loads animation js
 *
 * @since 3.5.0
 */
function vcex_grid_get_post_class( $classes = array(), $post_id ) {

	// Get post
	$post_id = $post_id ? $post_id : get_the_ID();

	// Get post type
	$type = get_post_type( $post_id );

	// Add post ID class
	$classes[] = 'post-'. $post_id;

	// Add entry class
	$classes[] = 'entry';

	// Add type class
	$classes[] = 'type-'. $type;

	// Add has media class
	if ( wpex_post_has_media( $post_id, true ) ) {
		$classes[] = 'has-media';
	} else {
		$classes[] = 'no-media';
	}

	// Add terms
	if ( $terms = vcex_get_post_term_classes( $post_id, $type ) ) {
		$classes[] = $terms;
	}

	// Custom link class
	if ( wpex_get_post_redirect_link() ) {
		$classes[] = 'has-redirect';
	}

	// Apply filters
	$classes = apply_filters( 'vcex_grid_get_post_class', $classes );

	// Turn into string
	$classes = implode( ' ', $classes );

	// Sanitize and return
	return 'class="'. esc_attr( $classes ) .'"';

}

/**
 * Returns entry classes for vcex module entries
 *
 * @since 3.5.3
 */
function vcex_get_post_term_classes( $post_id, $post_type ) {

	// Define vars
	$classes = array();

	// Loop through tax objects and save in taxonomies var
	$taxonomies = get_object_taxonomies( $post_type, 'names' );

	// Return of there is an error
	if ( is_wp_error( $taxonomies ) || ! $taxonomies ) {
		return;
	}

	// Loop through taxomies
	foreach ( $taxonomies as $tax ) {

		// Get terms
		$terms = get_the_terms( $post_id, $tax );

		// Make sure terms aren't empty before loop
		if ( ! is_wp_error( $terms ) && $terms ) {

			// Loop through terms
			foreach ( $terms as $term ) {

				// Set prefix as taxonomy name
				$prefix = esc_html( $term->taxonomy );

				// Add class if we have a prefix
				if ( $prefix ) {

					// Get total post types to parse
					$parse_types = wpex_theme_post_types();
					if ( in_array( $post_type, $parse_types ) ) {
						$search  = array( $post_type .'_category', $post_type .'_tag' );
						$replace = array( 'cat', 'tag' );
						$prefix  = str_replace( $search, $replace, $prefix );
					}

					// Category prefix
					if ( 'category' == $prefix ) {
						$prefix = 'cat';
					}

					// Add term
					$classes[] = $prefix .'-'. $term->term_id;

					// Add term parent
					if ( $term->parent ) {
						$classes[] = $prefix .'-'. $term->parent;
					}

				}

			}
		}
	}

	// Return classes
	return $classes ? implode( ' ', $classes ) : '';

}

/**
 * Returns correct class for columns
 *
 * @since 4.0
 */
function vcex_get_grid_column_class( $atts ) {
	$return_class = '';
	if ( isset( $atts['columns'] ) ) {
		$return_class .= 'span_1_of_' . $atts['columns'];
	}
	if ( isset( $atts['single_column_style'] ) && 'left_thumbs' == $atts['single_column_style'] ) {
		return $return_class;
	}
	if ( ! empty( $atts['columns_responsive_settings'] ) ) {
		$rs = vcex_parse_multi_attribute( $atts['columns_responsive_settings'], array() );
		foreach ( $rs as $key => $val ) {
			if ( $val ) {
				$return_class .= ' span_1_of_' . $val . '_' . $key;
			}
		}
	}
	return $return_class;
}

/**
 * Returns correct class for columns
 *
 * @since 4.0
 */
function vcex_parse_multi_attribute( $value = '', $default = array() ) {
	if ( function_exists( 'vc_parse_multi_attribute' ) ) {
		return vc_parse_multi_attribute( $value, $default );
	} else {
		$result = $default;
		$params_pairs = explode( '|', $value );
		if ( ! empty( $params_pairs ) ) {
			foreach ( $params_pairs as $pair ) {
				$param = preg_split( '/\:/', $pair );
				if ( ! empty( $param[0] ) && isset( $param[1] ) ) {
					$result[ $param[0] ] = rawurldecode( $param[1] );
				}
			}
		}
		return $result;
	}
}

/**
 * Helper function enqueues icon fonts from Visual Composer
 *
 * @since 2.0.0
 */
function vcex_enqueue_icon_font( $family = '' ) {

	// Return if there isn't an icon or it's set to fontawesome
	if ( ! $family || 'fontawesome' == $family ) {
		return;
	}

	// Return if VC function doesn't exist
	if ( ! function_exists( 'vc_icon_element_fonts_enqueue' ) ) {
		return;
	}

	// Check for custom enqueue
	$fonts = vcex_get_icon_font_families();

	// Custom stylesheet check
	if ( ! empty( $fonts[$family]['style'] ) ) {
		wp_enqueue_style( $fonts[$family]['style'] );
	}

	// Default core styles
	else {
		vc_icon_element_fonts_enqueue( $family );
	}

}

/**
 * Returns font icon options
 *
 * @since 4.0
 */
function vcex_get_icon_font_families( $module = '' ) {
	return apply_filters( 'vcex_vc_map_icon_font_families', array(
		'fontawesome' => array(
			'label' => __( 'Font Awesome', 'total' ),
			'source' => '',
			'default' => 'fa fa-info-circle',
		),
		'openiconic' => array(
			'label' => __( 'Open Iconic', 'total' ),
		),
		'typicons' => array(
			'label' =>__( 'Typicons', 'total' ),
		),
		'entypo' => array(
			'label' =>__( 'Entypo', 'total' ),
		),
		'linecons' => array(
			'label' =>__( 'Linecons', 'total' ),
		),
		'pixelicons' => array(
			'label' =>__( 'Pixel', 'total' ),
			'source' => vcex_pixel_icons()
		),
		'monosocial' => array(
			'label' =>__( 'Mono Social', 'total' ),
		),
	), $module );
}

/**
 * Returns font icon options
 *
 * @since 4.0
 */
function vcex_vc_map_add_icon_font( $args = '', $module ) {
	$settings = array();

	$defaults = array(
		'type_param_name' => 'icon_type',
		'icon_param_name' => 'icon',
		'group'           => '',
		'include'         => 'all'
	);

	if ( is_array( $args ) ) {
		$args = wp_parse_args( $args, $defaults );
	} else {
		$defaults['group'] = $args;
		$args = $defaults;
	}

	$icon_families = vcex_get_icon_font_families( $module );

	$settings[] = array(
		'type'        => 'dropdown',
		'heading'     => __( 'Icon library', 'total' ),
		'param_name'  => $args['type_param_name'],
		'description' => __( 'Select icon library.', 'total' ),
		'group'       => $args['group'],
	);

	$settings_values = array();

	foreach ( $icon_families as $key => $val ) {

		if ( 'all' != $args['include'] && is_array( $args['include'] ) && ! in_array( $key, $args['include'] ) ) {
			continue;
		}

		$settings_values[$val['label']] = $key;

		$default = isset( $val['default'] ) ? $val['default'] : '';

		if ( 'fontawesome' == $key ) {
			$param_name = $args['icon_param_name'];
		} else {
			$param_name = $args['icon_param_name'] .'_'. $key;
		}

		$settings[$key] = array(
			'type'       => 'iconpicker',
			'heading'    => __( 'Icon', 'total' ),
			'param_name' => $param_name,
			'value'      => $default,
			'settings'   => array(
				'type'         => $key,
				'emptyIcon'    => true,
				'iconsPerPage' => 200,
			),
			'dependency' => array(
				'element' => $args['type_param_name'],
				'value'   => $key,
			),
			'group'      => $args['group'],
		);

		if ( ! empty( $val['source'] ) ) {
			$settings[$key]['settings']['source'] = $val['source'];
		}

	}

	$settings[0]['value'] = $settings_values;

	return $settings;
}

/**
 * Returns array for adding CSS Animation to VC modules
 *
 * @since 4.0
 */
function vcex_vc_map_add_css_animation( $args = array() ) {

	// Fallback pre VC 5.0
	if ( ! function_exists( 'vcex_vc_map_add_css_animation' ) ) {
		return array(
			'type' => 'dropdown',
			'heading' => __( 'Appear Animation', 'total' ),
			'param_name' => 'css_animation',
			'value' => array_flip( wpex_css_animations() ),
			'dependency' => array( 'element' => 'filter', 'value' => 'false' ),
		);
	}

	// New since VC 5.0
	$defaults = array(
		'type' => 'animation_style',
		'heading' => __( 'CSS Animation', 'total' ),
		'param_name' => 'css_animation',
		'value' => 'none',
		'settings' => array(
			'type' => 'in',
			'custom' => array(
				array(
					'label' => __( 'Default', 'total' ),
					'values' => array(
						__( 'Top to bottom', 'total' )      => 'top-to-bottom',
						__( 'Bottom to top', 'total' )      => 'bottom-to-top',
						__( 'Left to right', 'total' )      => 'left-to-right',
						__( 'Right to left', 'total' )      => 'right-to-left',
						__( 'Appear from center', 'total' ) => 'appear',
					),
				),
			),
		),
		'description' => __( 'Select type of animation for element to be animated when it "enters" the browsers viewport (Note: works only in modern browsers).', 'total' ),
	);
	$args = wp_parse_args( $args, $defaults );
	return apply_filters( 'vc_map_add_css_animation', $args );
}

/**
 * Returns animation class and loads animation js
 *
 * @since 3.5.0
 */
function vcex_get_css_animation( $css_animation = '' ) {
	if ( $css_animation && 'none' != $css_animation ) {
		wp_enqueue_script( 'waypoints' );
		wp_enqueue_style( 'animate-css' );
		return' wpb_animate_when_almost_visible wpb_' . $css_animation . ' ' . $css_animation;
	}
}

/**
 * Return responsive font-size data
 *
 * @since 4.0
 */
function vcex_get_module_responsive_data( $atts, $type = '' ) {

	if ( ! $atts ) {
		return; // No need to do anything if atts is empty
	}

	$return      = array();
	$parsed_data = array();
	$settings    = array( 'font_size' );

	if ( $type && ! is_array( $atts ) ) {
		$settings = array( $type );
		$atts = array( $type => $atts );
	}

	foreach ( $settings as $setting ) {
   
	   	if ( 'font_size' == $setting ) {

	   		// Get value from params
	   		$value = isset( $atts['font_size'] ) ? $atts['font_size'] : '';

			// Not needed for simple font_sizes
			if ( strpos( $value, '|' ) === false ) {
				break;
			}

			// Parse data to return array
			$data = vcex_parse_multi_attribute( $value );

			if ( ! $data && ! is_array( $data ) ) {
				break;
			}

			$new_data = array();

			// Sanitize
			foreach ( $data as $key => $val ) {
				$new_data[$key] = wpex_sanitize_data( $val, 'font_size' );
			}

			// Add to new array
			$parsed_data['font-size'] = $new_data;

		} // End font_size

	} // End foreach

	// Return
	if ( $parsed_data ) {

		// Return data
		$return['data'] = "data-wpex-rcss='" . json_encode( $parsed_data ) . "'";

		// Get unique ID
		$uniqid = isset( $atts['uniqid'] ) ? '.wpex-'. $atts['uniqid'] : uniqid( 'wpex-');
		$return['data'] .= " data-wpex-uniqid='." . $uniqid . "'";
		$return['uniqid'] = $uniqid;

		// Return array
		return $return;

	}

}

/**
 * Get Extra class
 *
 * @since 2.0.0
 */
function vcex_get_extra_class( $classes = '' ) {
	if ( $classes ) {
		return str_replace( '.', '', $classes );
	}
}


/**
 * Returns list of post types
 *
 * @since 2.1.0
 */
function vcex_get_post_types() {
	$post_types_list = array();
	$post_types = get_post_types( array(
		'public' => true
	) );
	if ( $post_types ) {
		foreach ( $post_types as $post_type ) {
			if ( 'revision' != $post_type && 'nav_menu_item' != $post_type && 'attachment' != $post_type ) {
				$post_types_list[$post_type] = $post_type;
			}
		}
	}
	return $post_types_list;
}

/**
 * Array of Google Font options
 *
 * @since 2.1.0
 */
function vcex_fonts_array() {

	// Default array
	$array = array(
		__( 'Default', 'total' ) => '',
	);

	// Add custom fonts
	if ( $custom_fonts = wpex_add_custom_fonts() ) {
		$array = array_merge( $array, wpex_add_custom_fonts() );
	}

	// Add standard fonts
	$std_fonts = wpex_standard_fonts();
	$array = array_merge( $array, $std_fonts );

	// Add Google Fonts
	if ( $google_fonts = wpex_google_fonts_array() ) {
		$array = array_merge( $array, $google_fonts );
	}

	// Return fonts
	return apply_filters( 'vcex_google_fonts_array', $array );

}

/**
 * Parses lightbox dimensions
 *
 * @since 2.1.2
 */
function vcex_parse_lightbox_dims( $dims = '' ) {

	// Return if no dims
	if ( ! $dims ) {
		return;
	}

	// Parse data
	$dims = explode( 'x', $dims );
    $w    = isset( $dims[0] ) ? $dims[0] : '1920';
    $h    = isset( $dims[1] ) ? $dims[1] : '1080';

    // Return dimensions
    return 'width:'. $w .',height:'. $h .'';
	
}

/**
 * Parses textarea HTML
 *
 * @since 2.1.2
 */
function vcex_parse_textarea_html( $html = '' ) {
	if ( $html && base64_decode( $html, true ) ) {
		return rawurldecode( base64_decode( strip_tags( $html ) ) );
	}
	return $html;
}

/**
 * Parses the font_control / typography param
 *
 * @since 2.0.0
 */
function vcex_parse_typography_param( $value ) {

	// Conter value to array
	$value = vc_parse_multi_attribute( $value );
	
	// Define defaults
	$defaults = array(
		'tag'               => '',
		'text_align'        => '',
		'font_size'         => '',
		'line_height'       => '',
		'color'             => '',
		'font_style_italic' => '',
		'font_style_bold'   => '',
		'font_family'       => '',
		'letter_spacing'    => '',
		'font_family'       => '',
	);

	// Parse values so keys exist
	$values = wp_parse_args( $value, $defaults );

	// Return values
	return $values;

}

/**
 * Url param to check for for filters
 *
 * @since 3.2.0
 */
function vcex_grid_filter_url_param() {
	return apply_filters( 'vcex_grid_filter_url_param', 'filter' );
}

/**
 * Return grid filter arguments
 *
 * @since 2.0.0
 */
function vcex_grid_filter_args( $atts = '', $query = '' ) {

	// Return if no attributes found
	if ( ! $atts ) {
		return;
	}

	// Define args
	$args = $include = array();

	// Don't get empty
	$args['hide_empty'] = true;

	// Taxonomy
	if ( ! empty( $atts['filter_taxonomy'] ) ) {
		$taxonomy = $atts['filter_taxonomy'];
	} elseif ( isset( $atts['taxonomy'] ) ) {
		$taxonomy = $atts['taxonomy']; // Fallback
	} else {
		$taxonomy = null;
	}

	// Define post type and taxonomy
	$post_type = ! empty( $atts['post_type'] ) ? $atts['post_type'] : '';

	// Define include/exclude category vars
	$include_cats = ! empty( $atts['include_categories'] ) ? vcex_string_to_array( $atts['include_categories'] ) : '';

	// Check if only 1 category is included
	// If so check if it's a parent item so we can display children as the filter links
	if ( $include_cats && '1' == count( $include_cats )
		&& $children = get_term_children( $include_cats[0], $taxonomy )
	) {
		$include = $children;
	}

	// Include only terms from current query
	if ( empty( $include ) && $query ) {

		// Pluck ids from query
		$post_ids = wp_list_pluck( $query->posts, 'ID' );

		// Loop through post ids
		foreach ( $post_ids as $post_id ) {

			// Get post terms
			$terms = wp_get_post_terms( $post_id, $taxonomy );

			// Make sure there is no errors with terms and post has terms
			if ( ! is_wp_error( $terms ) && $terms ) {

				// Loop through terms
				foreach( $terms as $term ) {

					// Store term id
					$term_id = $term->term_id;

					// Include terms if include_cats variable is empty
					if ( ! $include_cats ) {

						// Include term
						$include[$term_id] = $term_id;

						/* Include parent
						if ( $term->parent ) {
							$include[$term->parent] = $term->parent;
						}*/

					}

					// Include terms if include_cats is enabled and term is in var
					elseif ( $include_cats && in_array( $term_id, $include_cats ) ) {
						$include[$term_id] = $term_id;
					}

				}

			}

		}

		// Add included terms to include param
		$args['include'] = $include;

	}

	// Add to args
	if ( ! empty( $include ) ) {
		$args['include'] = $include;
	}
	if ( ! empty( $exclude ) ) {
		$args['exclude'] = $exclude;
	}

	// Apply filters @todo deprecate?
	if ( $post_type ) {
		$args = apply_filters( 'vcex_'. $post_type .'_grid_filter_args', $args );
	}

	// Return args
	return apply_filters( 'vcex_grid_filter_args', $args, $post_type );

}

/**
 * Convert to array
 *
 * @since 2.0.0
 */
function vcex_string_to_array( $value = array() ) {
	
	// Return wpex function if it exists  
	if ( function_exists( 'wpex_string_to_array' ) ) {
		return wpex_string_to_array( $value );
	}

	// Create our own return
	else {

		// Return null for empty array
		if ( empty( $value ) && is_array( $value ) ) {
			return null;
		}

		// Return if already array
		if ( ! empty( $value ) && is_array( $value ) ) {
			return $value;
		}

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
 * Generates various types of HTML based on a value
 *
 * @since 2.0.0
 */
function vcex_parse_old_design_js() {
	return WPEX_VCEX_DIR_URI . 'assets/parse-old-design.js';
}

/**
 * Generates various types of HTML based on a value
 *
 * @since 2.0.0
 */
function vcex_html( $type, $value, $trim = false ) {

	// Return nothing by default
	$return = '';

	// Return if value is empty
	if ( ! $value ) {
		return;
	}

	// Title attribute
	if ( 'id_attr' == $type ) {
		$value  = trim ( str_replace( '#', '', $value ) );
		$value  = str_replace( ' ', '', $value );
		if ( $value ) {
			$return = ' id="'. esc_attr( $value ) .'"';
		}
	}

	// Title attribute
	if ( 'title_attr' == $type ) {
		$return = ' title="'. esc_attr( $value ) .'"';
	}

	// Link Target
	elseif ( 'target_attr' == $type ) {
		if ( 'blank' == $value
			|| '_blank' == $value
			|| strpos( $value, 'blank' ) ) {
			$return = ' target="_blank"';
		}
	}

	// Link rel
	elseif ( 'rel_attr' == $type ) {
		if ( 'nofollow' == $value ) {
			$return = ' rel="nofollow"';
		}
	}

	// Return HTMl
	if ( $trim ) {
		return trim( $return );
	} else {
		return $return;
	}

}

/**
 * Returns array of image sizes for use in the Customizer
 *
 * @since 2.0.0
 */
function vcex_image_sizes() {
	$sizes = array(
		__( 'Custom Size', 'total' ) => 'wpex_custom',
	);
	$get_sizes = get_intermediate_image_sizes();
	array_unshift( $get_sizes, 'full' );
	$get_sizes = array_combine( $get_sizes, $get_sizes );
	$sizes     = array_merge( $sizes, $get_sizes );
	return $sizes;
}

/**
 * Notice when no posts are found
 *
 * @since 2.0.0
 */
function vcex_no_posts_found_message( $atts ) {
	if ( wpex_vc_is_inline() ) {
		return '<div class="vcex-no-posts-found">'. apply_filters( 'vcex_no_posts_found_message', esc_html__( 'No posts found for your query.', 'total' ) ) .'</div>';
	}
}

/**
 * Echos unique ID html for VC modules
 *
 * @since 2.0.0
 */
function vcex_unique_id( $id = '' ) {
	echo vcex_get_unique_id( $id );
}

/**
 * Returns unique ID html for VC modules
 *
 * @since 2.0.0
 */
function vcex_get_unique_id( $id = '' ) {
	if ( $id ) {
		return vcex_html( 'id_attr', $id );
	}
}

/**
 * Returns dummy image
 *
 * @since 2.0.0
 */
function vcex_dummy_image_url() {
	return WPEX_THEME_URI .'/images/dummy-image.jpg';
}

/**
 * Outputs dummy image
 *
 * @since 2.0.0
 */
function vcex_dummy_image() {
	echo '<img src="'. WPEX_THEME_URI .'/images/dummy-image.jpg" />';
}

/**
 * Used to enqueue styles for Visual Composer modules
 *
 * @since 2.0.0
 */
function vcex_enque_style( $type, $value = '' ) {

	// iLightbox
	if ( 'ilightbox' == $type ) {
		wpex_enqueue_ilightbox_skin( $value );
	}

	// Hover animation
	elseif ( 'hover-animations' == $type ) {
		wp_enqueue_style( 'wpex-hover-animations' );
	}

}

/**
 * Array of Icon box styles
 *
 * @since 2.0.0
 */
function vcex_icon_box_styles() {

	// Define array
	$array  = array(
		'one'   => __( 'Left Icon', 'total' ),
		'seven' => __( 'Right Icon', 'total' ),
		'two'   => __( 'Top Icon', 'total' ),
		'three' => __( 'Top Icon Style 2 - legacy', 'total' ),
		'four'  => __( 'Outlined and Top Icon - legacy', 'total' ),
		'five'  => __( 'Boxed and Top Icon - legacy', 'total' ),
		'six'   => __( 'Boxed and Top Icon Style 2 - legacy', 'total' ),
	);

	// Apply filters
	$array = apply_filters( 'vcex_icon_box_styles', $array );

	// Flip array around for use with VC
	$array = array_flip( $array ); 

	// Return array
	return $array;

}

/**
 * Array of orderby options
 *
 * @since 2.0.0
 */
function vcex_orderby_array() {
	return apply_filters( 'vcex_orderby', array(
		__( 'Default', 'total')             => '',
		__( 'Date', 'total')                => 'date',
		__( 'Title', 'total' )              => 'title',
		__( 'Name', 'total' )               => 'name',
		__( 'Modified', 'total')            => 'modified',
		__( 'Author', 'total' )             => 'author',
		__( 'Random', 'total')              => 'rand',
		__( 'Parent', 'total')              => 'parent',
		__( 'Type', 'total')                => 'type',
		__( 'ID', 'total' )                 => 'ID',
		__( 'Comment Count', 'total' )      => 'comment_count',
		__( 'Menu Order', 'total' )         => 'menu_order',
		__( 'Meta Key Value', 'total' )     => 'meta_value',
		__( 'Meta Key Value Num', 'total' ) => 'meta_value_num',
	) );
}

/**
 * Array of ilightbox skins
 *
 * @since 2.0.0
 * @todo deprecate this function and remove extra vcex_lightbox_skins settings from modules force people to use global setting
 */
function vcex_ilightbox_skins() {
	$skins = array(
		''  => __( 'Default', 'total' ),
	);
	$skins = array_merge( $skins, wpex_ilightbox_skins() );
	$skins = array_flip( $skins );
	return $skins;
}

/**
 * Border Radius Classname
 *
 * @since 1.4.0
 */
function vcex_get_border_radius_class( $val ) {
	if ( 'none' == $val || '' == $val ) {
		return;
	}
	return 'wpex-'. $val;
}

/**
 * Helper function for building links using link param
 *
 * @since 2.0.0
 */
function vcex_build_link( $link, $fallback = '' ) {

	// If empty return fallback
	if ( empty( $link ) ) {
		return $fallback;
	}

	// Return if there isn't any link
	if ( '||' == $link || '|||' == $link ) {
		return;
	}

	// Return simple link escaped (fallback for old textfield input)
	if ( false === strpos( $link, 'url:' ) ) {
		return esc_url( $link );
	}

	// Build link
	$link = vc_build_link( $link );

	// Sanitize
	$link = is_array( $link ) ? $link : '';

	// Return link
	return $link;

}

/**
 * Returns link data
 *
 * @since 2.0.0
 */
function vcex_get_link_data( $return, $link, $fallback = '' ) {

	$link = vcex_build_link( $link, $fallback );

	if ( 'url' == $return ) {
		if ( is_array( $link ) && ! empty( $link['url'] ) ) {
			return $link['url'];
		} else {
			return is_array( $link ) ? $fallback : $link;
		}
	}

	if ( 'title' == $return ) {
		if ( is_array( $link ) && ! empty( $link['title'] ) ) {
			return $link['title'];
		} else {
			return $fallback;
		}
	}

	if ( 'target' == $return ) {
		if ( is_array( $link ) && ! empty( $link['target'] ) ) {
			return $link['target'];
		} else {
			return $fallback;
		}
	}

	if ( 'rel' == $return ) {
		if ( is_array( $link ) && ! empty( $link['rel'] ) ) {
			return $link['rel'];
		} else {
			return $fallback;
		}
	}

}

/**
 * Returns correct icon class based on icon type
 *
 * @since 2.0.0
 */
function vcex_get_icon_class( $atts, $icon_location ) {

	// Define vars
	$icon = '';
	$icon_type = ! empty( $atts['icon_type'] ) ? $atts['icon_type'] : 'fontawesome';

	// Generate fontawesome icon class
	if ( 'fontawesome' == $icon_type && ! empty( $atts[$icon_location] ) ) {
		$icon = $atts[$icon_location];
		$icon = str_replace( 'fa-', '', $icon );
		$icon = str_replace( 'fa ', '', $icon );
		$icon = 'fa fa-'. $icon;
	} elseif ( ! empty( $atts[ $icon_location .'_'. $icon_type ] ) ) {
		$icon = $atts[ $icon_location .'_'. $icon_type ];
	}

	// Sanitize
	$icon = in_array( $icon, array( 'icon', 'none' ) ) ? '' : $icon;

	// Return icon class
	return $icon;

}

/**
 * Adds inner row margin to compensate for the VC negative margins
 *
 *
 * @since 2.0.0
 */
function vcex_offset_vc( $atts ) {

	// No offset added here
	if ( ! empty( $atts['full_width'] ) || ! empty( $atts['max_width'] ) ) {
		return;
	}

	// Get column spacing
	$spacing = ! empty( $atts['column_spacing'] ) ? $atts['column_spacing'] : '30';

	// Return if spacing set to 0px
	if ( '0px' == $spacing ) {
		return;
	}

	// Define offset class
	$classes = 'wpex-offset-vc-'. $spacing/2;

	// Parallax check
	if ( ! empty( $atts['vcex_parallax'] ) && ! empty( $atts['parallax_image'] ) ) {
		return $classes;
	}

	// Self hosted video
	if ( 'self_hosted' == $atts['video_bg'] && ! empty( $atts['video_bg_mp4'] ) ) {
		return $classes;
	}

	// Youtube videos
	if ( 'youtube' == $atts['video_bg'] && ! empty( $atts['video_bg_url'] ) ) {
		return $classes;
	}

	// Overlays
	$overlay = isset( $atts['wpex_bg_overlay'] ) ? $atts['wpex_bg_overlay'] : '';
	if ( $overlay ) {
		return $classes;
	}

	// Check for custom CSS
	if ( ! empty( $atts['css'] ) ) {
		if ( strpos( $atts['css'], 'background' )
			|| strpos( $atts['css'], 'border' )
		) {
			return $classes;
		}
	} elseif ( ! empty( $atts['center_row'] )
		|| ! empty( $atts['bg_image'] )
		|| ! empty( $atts['bg_color'] )
		|| ! empty( $atts['border_width'] )
	) {
		return $classes;
	}

}

/**
 * Returns video row background
 *
 * @since 2.0.0
 */
if ( ! function_exists( 'vcex_row_video' ) ) {
	function vcex_row_video( $atts ) {

		// Define output
		$output = '';

		// Return if disabled
		if ( empty( $atts['wpex_self_hosted_video_bg'] ) ) {
			return;
		}

		// Make sure at least one video is defined
		if ( empty( $atts['video_bg_webm'] ) && empty( $atts['video_bg_ogv'] ) && empty( $atts['video_bg_mp4'] ) ) {
			return;
		}

		// Get background image
		$bg_image = ! empty( $atts['bg_image'] ) ? $atts['bg_image'] : '';

		// Check sound
		$sound = apply_filters( 'vcex_self_hosted_row_video_sound', false );
		$sound = $sound ? '' : 'muted volume="0"';

		$output .= '<div class="wpex-video-bg-wrap">';

			$output .= '<video class="wpex-video-bg" poster="'. esc_url( $bg_image ) .'" preload="auto" autoplay="true" loop="loop" '. $sound .'>';
				
				if ( ! empty( $atts['video_bg_webm'] ) ) {
					$output .= '<source src="'. $atts['video_bg_webm'] .'" type="video/webm" />';
				}
				
				if ( ! empty( $atts['video_bg_ogv'] ) ) {
					$output .= '<source src="'. $atts['video_bg_ogv'] .'" type="video/ogg ogv" />';
				}
				
				if ( ! empty( $atts['video_bg_mp4'] ) ) {
					$output .= '<source src="'. $atts['video_bg_mp4'] .'" type="video/mp4" />';
				}
			
			$output .= '</video>';
		
		$output .= '</div>';

		// Video overlay fallack
		// @deprecated in 3.6.0
		if ( ! empty( $atts['video_bg_overlay'] ) && 'none' != $atts['video_bg_overlay'] ) {

			$output .= '<span class="wpex-video-bg-overlay '. $atts['video_bg_overlay'] .'"></span>';

		}

		return $output;

	}
}

/**
 * Returns row parallax background
 *
 * @since 2.0.0
 */
if ( ! function_exists( 'vcex_parallax_bg' ) ) {

	function vcex_parallax_bg( $atts ) {

		// Make sure parallax is enabled
		if ( empty( $atts['vcex_parallax'] ) ) {
			return;
		}

		// Return if a video is defined
		if ( ! empty( $atts['wpex_self_hosted_video_bg'] ) ) {
			return;
		}

		// Sanitize $bg_image
		if ( ! empty( $atts['parallax_image'] ) ) {
			$bg_image = wp_get_attachment_url( $atts['parallax_image'] );
		} elseif ( ! empty( $atts['bg_image'] ) ) {
			$bg_image = $atts['bg_image']; // Old deprecated setting
		} else {
			return;
		}

		// Load inline js
		vcex_inline_js( array( 'parallax' ) );

		// Sanitize data
		$parallax_style     = ! empty( $atts['parallax_style'] ) ? $atts['parallax_style'] : ''; // Default should be cover
		$parallax_speed     = ! empty( $atts['parallax_speed'] ) ? $atts['parallax_speed'] : '0.2';
		$parallax_direction = ! empty( $atts['parallax_direction'] ) ? $atts['parallax_direction'] : 'top';

		// Classes
		$classes = array( 'wpex-parallax-bg' );
		$classes[] = $parallax_style;
		if ( ! empty( $atts['parallax_mobile'] ) ) {
			$classes[] = 'not-mobile';
		}
		$classes = apply_filters( 'wpex_parallax_classes', $classes );
		$classes = implode( ' ', array_filter( $classes, 'trim' ) );

		return wpex_parse_html( 'div', array(
			'class'          => esc_attr( $classes ),
			'data-direction' => $parallax_direction,
			'data-velocity'  => '-'. abs( $parallax_speed ),
			'style'          => 'background-image:url('. esc_url( $bg_image ) .');',
		) );

	}

}

/**
 * Returns row overlay span
 *
 * @since 3.5.0
 */
function vcex_row_overlay( $atts ) {
	
	$overlay = isset( $atts['wpex_bg_overlay'] ) ? $atts['wpex_bg_overlay'] : '';
	
	if ( $overlay && 'none' != $overlay ) {

		$style = '';

		if ( ! empty( $atts['wpex_bg_overlay_color'] ) ) {
			$style .= 'background-color:'. $atts['wpex_bg_overlay_color'] .';';
		}

		if ( ! empty( $atts['wpex_bg_overlay_opacity'] ) ) {
			$style .= 'opacity:'. $atts['wpex_bg_overlay_opacity'] .';';
			$style .= '-webkit-opacity:'. $atts['wpex_bg_overlay_opacity'] .';';
			$style .= '-moz-opacity:'. $atts['wpex_bg_overlay_opacity'] .';';
		}

		return '<div class="wpex-bg-overlay-wrap"> '. wpex_parse_html( 'span', array(
			'class'      => 'wpex-bg-overlay '. $overlay,
			'style'      => $style,
			'data-style' => $style,
		) ) .'</div>';

	}

}

/**
 * Array of social links profiles to loop through
 *
 * @since 2.0.0
 */
function vcex_social_links_profiles() {
	if ( function_exists( 'wpex_topbar_social_options' ) ) {
		$profiles = wpex_topbar_social_options();
	}
	return apply_filters( 'vcex_social_links_profiles', $profiles );
}

/**
 * Array of pixel icons
 *
 * @since 1.4.0
 */
if ( ! function_exists( 'vcex_pixel_icons' ) ) {
	function vcex_pixel_icons() {
		return array(
			array( 'vc_pixel_icon vc_pixel_icon-alert' => __( 'Alert', 'total' ) ),
			array( 'vc_pixel_icon vc_pixel_icon-info' => __( 'Info', 'total' ) ),
			array( 'vc_pixel_icon vc_pixel_icon-tick' => __( 'Tick', 'total' ) ),
			array( 'vc_pixel_icon vc_pixel_icon-explanation' => __( 'Explanation', 'total' ) ),
			array( 'vc_pixel_icon vc_pixel_icon-address_book' => __( 'Address book', 'total' ) ),
			array( 'vc_pixel_icon vc_pixel_icon-alarm_clock' => __( 'Alarm clock', 'total' ) ),
			array( 'vc_pixel_icon vc_pixel_icon-anchor' => __( 'Anchor', 'total' ) ),
			array( 'vc_pixel_icon vc_pixel_icon-application_image' => __( 'Application Image', 'total' ) ),
			array( 'vc_pixel_icon vc_pixel_icon-arrow' => __( 'Arrow', 'total' ) ),
			array( 'vc_pixel_icon vc_pixel_icon-asterisk' => __( 'Asterisk', 'total' ) ),
			array( 'vc_pixel_icon vc_pixel_icon-hammer' => __( 'Hammer', 'total' ) ),
			array( 'vc_pixel_icon vc_pixel_icon-balloon' => __( 'Balloon', 'total' ) ),
			array( 'vc_pixel_icon vc_pixel_icon-balloon_buzz' => __( 'Balloon Buzz', 'total' ) ),
			array( 'vc_pixel_icon vc_pixel_icon-balloon_facebook' => __( 'Balloon Facebook', 'total' ) ),
			array( 'vc_pixel_icon vc_pixel_icon-balloon_twitter' => __( 'Balloon Twitter', 'total' ) ),
			array( 'vc_pixel_icon vc_pixel_icon-battery' => __( 'Battery', 'total' ) ),
			array( 'vc_pixel_icon vc_pixel_icon-binocular' => __( 'Binocular', 'total' ) ),
			array( 'vc_pixel_icon vc_pixel_icon-document_excel' => __( 'Document Excel', 'total' ) ),
			array( 'vc_pixel_icon vc_pixel_icon-document_image' => __( 'Document Image', 'total' ) ),
			array( 'vc_pixel_icon vc_pixel_icon-document_music' => __( 'Document Music', 'total' ) ),
			array( 'vc_pixel_icon vc_pixel_icon-document_office' => __( 'Document Office', 'total' ) ),
			array( 'vc_pixel_icon vc_pixel_icon-document_pdf' => __( 'Document PDF', 'total' ) ),
			array( 'vc_pixel_icon vc_pixel_icon-document_powerpoint' => __( 'Document Powerpoint', 'total' ) ),
			array( 'vc_pixel_icon vc_pixel_icon-document_word' => __( 'Document Word', 'total' ) ),
			array( 'vc_pixel_icon vc_pixel_icon-bookmark' => __( 'Bookmark', 'total' ) ),
			array( 'vc_pixel_icon vc_pixel_icon-camcorder' => __( 'Camcorder', 'total' ) ),
			array( 'vc_pixel_icon vc_pixel_icon-camera' => __( 'Camera', 'total' ) ),
			array( 'vc_pixel_icon vc_pixel_icon-chart' => __( 'Chart', 'total' ) ),
			array( 'vc_pixel_icon vc_pixel_icon-chart_pie' => __( 'Chart pie', 'total' ) ),
			array( 'vc_pixel_icon vc_pixel_icon-clock' => __( 'Clock', 'total' ) ),
			array( 'vc_pixel_icon vc_pixel_icon-fire' => __( 'Fire', 'total' ) ),
			array( 'vc_pixel_icon vc_pixel_icon-heart' => __( 'Heart', 'total' ) ),
			array( 'vc_pixel_icon vc_pixel_icon-mail' => __( 'Mail', 'total' ) ),
			array( 'vc_pixel_icon vc_pixel_icon-play' => __( 'Play', 'total' ) ),
			array( 'vc_pixel_icon vc_pixel_icon-shield' => __( 'Shield', 'total' ) ),
			array( 'vc_pixel_icon vc_pixel_icon-video' => __( 'Video', 'total' ) ),
		);
	}
}

/**
 * Sets image size to full if image height/width are empty
 *
 * @since 3.0.0
 */
function vcex_parse_image_size( $atts ) {
	$img_size = ( isset( $atts['img_size'] ) && 'wpex_custom' == $atts['img_size'] ) ? 'wpex_custom' : '';
	$img_size = empty( $atts['img_size'] ) ? 'wpex_custom' : '';
	if ( 'wpex_custom' == $img_size && empty( $atts['img_height'] ) && empty( $atts['img_width'] ) ) {
		$atts['img_size'] = 'full';
	}
	return $atts;
}

/**
 * Parses deprecated content settings in Total VC grid modules
 *
 * @since 3.0.0
 */
function vcex_parse_deprecated_grid_entry_content_css( $atts ) {

	// Disable border
	$content_border = ! empty( $atts['content_border'] ) ? $atts['content_border'] : '';
	if ( '0px' == $content_border || 'none' == $content_border ) {
		$atts['content_border'] = 'false';
	}

	// Parse css
	if ( empty( $atts['content_css'] ) ) {

		// Define css var
		$css = '';

		// Background Color - No Image
		$bg = ! empty( $atts['content_background'] ) ? $atts['content_background'] : '';
		if ( $bg ) {
			$css .= 'background-color: '. $bg .';';
		}

		// Border
		$border = ! empty( $atts['content_border'] ) ? $atts['content_border'] : '';
		if ( $border ) {
			if ( '0px' == $border || 'none' == $border ) {
				$css .= 'border: 0px none rgba(255,255,255,0.01);'; // reset border
			} else {
				$css .= 'border: '. $border .';';
			}
		}

		// Padding
		$padding = ! empty( $atts['content_padding'] ) ? $atts['content_padding'] : '';
		if ( $padding ) {
			$css .= 'padding: '. $padding .';';
		}

		// Margin
		$margin = ! empty( $atts['content_margin'] ) ? $atts['content_margin'] : '';
		if ( $margin ) {
			$css .= 'margin: '. $margin .';';
		}

		// Update css var
		if ( $css ) {
			$css = '.temp{'. $css .'}';
		}

		// Add css to attributes
		$atts['content_css'] = $css;

		// Unset old vars
		unset( $atts['content_background'] );
		unset( $atts['content_padding'] );
		unset( $atts['content_margin'] );
		unset( $atts['content_border'] );

	}

	// Return $atts
	return $atts;

}

/**
 * Parses deprecated css fields into new css_editor field
 *
 * @since 3.0.0
 */
function vcex_parse_deprecated_row_css( $atts, $return = 'temp_class' ) {

	// Return if disabled
	if ( ! apply_filters( 'vcex_parse_deprecated_row_css', true ) ) {
		return;
	}

	$new_css = '';

	// Margin top
	if ( ! empty( $atts['margin_top'] ) ) {
		$new_css .= 'margin-top: '. wpex_sanitize_data( $atts['margin_top'], 'px-pct' ) .';';
	}

	// Margin bottom
	if ( ! empty( $atts['margin_bottom'] ) ) {
		$new_css .= 'margin-bottom: '. wpex_sanitize_data( $atts['margin_bottom'], 'px-pct' ) .';';
	}

	// Margin right
	if ( ! empty( $atts['margin_right'] ) ) {
		$new_css .= 'margin-right: '. wpex_sanitize_data( $atts['margin_right'], 'px-pct' ) .';';
	}

	// Margin left
	if ( ! empty( $atts['margin_left'] ) ) {
		$new_css .= 'margin-left: '. wpex_sanitize_data( $atts['margin_left'], 'px-pct' ) .';';
	}

	// Padding top
	if ( ! empty( $atts['padding_top'] ) ) {
		$new_css .= 'padding-top: '. wpex_sanitize_data( $atts['padding_top'], 'px-pct' ) .';';
	}

	// Padding bottom
	if ( ! empty( $atts['padding_bottom'] ) ) {
		$new_css .= 'padding-bottom: '. wpex_sanitize_data( $atts['padding_bottom'], 'px-pct' ) .';';
	}

	// Padding right
	if ( ! empty( $atts['padding_right'] ) ) {
		$new_css .= 'padding-right: '. wpex_sanitize_data( $atts['padding_right'], 'px-pct' ) .';';
	}

	// Padding left
	if ( ! empty( $atts['padding_left'] ) ) {
		$new_css .= 'padding-left: '. wpex_sanitize_data( $atts['padding_left'], 'px-pct' ) .';';
	}

	// Border
	if ( ! empty( $atts['border_width'] ) && ! empty( $atts['border_color'] ) ) {
		$border_width = explode( ' ', $atts['border_width'] );
		$border_style = isset( $atts['border_style'] ) ? $atts['border_style'] : 'solid';
		$bcount = count( $border_width );
		if ( '1' == $bcount ) {
			$new_css .= 'border: '. $border_width[0] . ' '. $border_style .' '. $atts['border_color'] .';';
		} else {
			$new_css .= 'border-color: '. $atts['border_color'] .';';
			$new_css .= 'border-style: '. $border_style .';';
			if ( '2' == $bcount ) {
				$new_css .= 'border-top-width: '. $border_width[0] .';';
				$new_css .= 'border-bottom-width: '. $border_width[0] .';';
				$bw = isset( $border_width[1] ) ? $border_width[1] : '0px';
				$new_css .= 'border-left-width: '. $bw .';';
				$new_css .= 'border-right-width: '. $bw .';';
			} else {
				$new_css .= 'border-top-width: '. $border_width[0] .';';
				$bw = isset( $border_width[1] ) ? $border_width[1] : '0px';
				$new_css .= 'border-right-width: '. $bw .';';
				$bw = isset( $border_width[2] ) ? $border_width[2] : '0px';
				$new_css .= 'border-bottom-width: '. $bw .';';
				$bw = isset( $border_width[3] ) ? $border_width[3] : '0px';
				$new_css .= 'border-left-width: '. $bw .';';
			}
		}
	}

	// Background image
	if ( ! empty( $atts['bg_image'] ) ) {
		if ( 'temp_class' == $return ) {
			$bg_image = wp_get_attachment_url( $atts['bg_image'] ) .'?id='. $atts['bg_image'];
		} elseif ( 'inline_css' == $return ) {
			if ( is_numeric( $atts['bg_image'] ) ) {
				$bg_image = wp_get_attachment_url( $atts['bg_image'] );
			} else {
				$bg_image = $atts['bg_image'];
			}
		}
	}

	// Background Image & Color
	if ( ! empty( $bg_image ) && ! empty( $atts['bg_color'] ) ) {
		$style = ! empty( $atts['bg_style'] ) ? $atts['bg_style'] : 'stretch';
		$position = '';
		$repeat   = '';
		$size     = '';
		if ( 'stretch' == $style ) {
			$position = 'center';
			$repeat   = 'no-repeat';
			$size     = 'cover';
		}
		if ( 'fixed' == $style ) {
			$position = '0 0';
			$repeat   = 'no-repeat';
		}
		if ( 'repeat' == $style ) {
			$position = '0 0';
			$repeat   = 'repeat';
		}
		$new_css .= 'background: '. $atts['bg_color'] .' url('. $bg_image .');';
		if ( $position ) {
			$new_css .= 'background-position: '. $position .';';
		}
		if ( $repeat ) {
			$new_css .= 'background-repeat: '. $repeat .';';
		}
		if ( $size ) {
			$new_css .= 'background-size: '. $size .';';
		}
	}

	// Background Image - No Color
	if ( ! empty( $bg_image ) && empty( $atts['bg_color'] ) ) {
		$new_css .= 'background-image: url('. $bg_image .');'; // Add image
		$style = ! empty( $atts['bg_style'] ) ? $atts['bg_style'] : 'stretch'; // Generate style
		$position = '';
		$repeat   = '';
		$size     = '';
		if ( 'stretch' == $style ) {
			$position = 'center';
			$repeat   = 'no-repeat';
			$size     = 'cover';
		}
		if ( 'fixed' == $style ) {
			$position = '0 0';
			$repeat   = 'no-repeat';
		}
		if ( 'repeat' == $style ) {
			$position = '0 0';
			$repeat   = 'repeat';
		}
		if ( $position ) {
			$new_css .= 'background-position: '. $position .';';
		}
		if ( $repeat ) {
			$new_css .= 'background-repeat: '. $repeat .';';
		}
		if ( $size ) {
			$new_css .= 'background-size: '. $size .';';
		}
	}

	// Background Color - No Image
	if ( ! empty( $atts['bg_color'] ) && empty( $bg_image ) ) {
		$new_css .= 'background-color: '. $atts['bg_color'] .';';
	}

	// Return new css
	if ( $new_css ) {
		if ( 'temp_class' == $return ) {
			return '.temp{'. $new_css .'}';
		} elseif ( 'inline_css' == $return ) {
			return $new_css;
		}
	}

}

/**
 * Fallback to prevent JS error - DO NOT REMOVE!!!!!!
 *
 * @since 2.0.0
 */
if ( ! function_exists( 'vc_icon_element_fonts_enqueue' ) ) {
	function vc_icon_element_fonts_enqueue( $font ) {
		switch ( $font ) {
			case 'openiconic':
				wp_enqueue_style( 'vc_openiconic' );
				break;
			case 'typicons':
				wp_enqueue_style( 'vc_typicons' );
				break;
			case 'entypo':
				wp_enqueue_style( 'vc_entypo' );
				break;
			case 'linecons':
				wp_enqueue_style( 'vc_linecons' );
				break;
			case 'monosocial':
				wp_enqueue_style( 'vc_monosocialiconsfont' );
				break;
			default:
				do_action( 'vc_enqueue_font_icon_element', $font ); // hook to custom do enqueue style
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* - Deprecated Functions
/*-----------------------------------------------------------------------------------*/
function vcex_sanitize_data() {
	_deprecated_function( 'vcex_sanitize_data', '3.0.0', 'wpex_sanitize_data' );
}
function vcex_image_rendering() {
	return;
}
function vcex_inline_js() {
	return; // deprecated in 3.6.0 removed completely in  4.0
}