<?php
/**
 * Helper functions for returning/generating post thumbnails
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.1
 */

/**
 * Returns thumbnail sizes
 *
 * @since 2.0.0
 */
function wpex_get_thumbnail_sizes( $size = '' ) {

	global $_wp_additional_image_sizes;

	$sizes = array(
		'full'  => array(
			'width'  => '9999',
			'height' => '9999',
			'crop'   => 0,
		),
	);
	$get_intermediate_image_sizes = get_intermediate_image_sizes();

	// Create the full array with sizes and crop info
	foreach( $get_intermediate_image_sizes as $_size ) {

		if ( in_array( $_size, array( 'thumbnail', 'medium', 'large' ) ) ) {

			$sizes[ $_size ]['width']   = get_option( $_size . '_size_w' );
			$sizes[ $_size ]['height']  = get_option( $_size . '_size_h' );
			$sizes[ $_size ]['crop']    = (bool) get_option( $_size . '_crop' );

		} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {

			$sizes[ $_size ] = array(
				'width'     => $_wp_additional_image_sizes[ $_size ]['width'],
				'height'    => $_wp_additional_image_sizes[ $_size ]['height'],
				'crop'      => $_wp_additional_image_sizes[ $_size ]['crop']
			);

		}

	}

	// Get only 1 size if found
	if ( $size ) {
		if ( isset( $sizes[ $size ] ) ) {
			return $sizes[ $size ];
		} else {
			return false;
		}
	}

	// Return sizes
	return $sizes;
}

/**
 * Generates a retina image
 *
 * @since 2.0.0
 */
function wpex_generate_retina_image( $attachment, $width, $height, $crop, $size = '' ) {
	return wpex_image_resize( array(
		'attachment' => $attachment,
		'width'      => $width,
		'height'     => $height,
		'crop'       => $crop,
		'return'     => 'url',
		'retina'     => true,
		'size'       => $size, // Used to update metadata accordingly
	) );
}

/**
 * Echo post thumbnail url
 *
 * @since 2.0.0
 */
function wpex_post_thumbnail_url( $args = array() ) {
	echo wpex_get_post_thumbnail_url( $args );
}

/**
 * Return post thumbnail url
 *
 * @since 2.0.0
 */
function wpex_get_post_thumbnail_url( $args = array() ) {
	$args['return'] = 'url';
	return wpex_get_post_thumbnail( $args );
}

/**
 * Return post thumbnail src
 *
 * @since 4.0
 */
function wpex_get_post_thumbnail_src( $args = array() ) {
	$args['return'] = 'src';
	return wpex_get_post_thumbnail( $args );
}

/**
 * Outputs the img HTMl thubmails used in the Total VC modules
 *
 * @since 2.0.0
 */
function wpex_post_thumbnail( $args = array() ) {
	echo wpex_get_post_thumbnail( $args );
}

/**
 * Returns correct HTMl for post thumbnails
 *
 * @since 2.0.0
 */
function wpex_get_post_thumbnail( $args = array() ) {

	// Default args
	$defaults = array(
		'attachment'    => '',                       // Int		|	Image post ID
		'size'          => '',                       // Str		|	WP defined image size
		'width'         => '',                       // Int		|	Custom image width
		'height'        => '',                       // Int		|	Custom image height
		'crop'          => 'center-center',          // Str		|	Crop location
		'return'        => 'html',                   // Str		|	Return html or src
		'style'         => '',                       // Str		|	Adds inline styles
		'alt'           => '',                       // Str		|	Custom alt tag for image
		'class'         => '',                       // Str		|	Add custom classes
		'attributes'    => array(),                  // Array	|	Custom attributes @added in 4.1
		'retina'        => wpex_is_retina_enabled(), // Bool	|	Check if retina is enabled
		'retina_data'   => 'at2x',                   // Str		|	Attribute used for retina
		'schema_markup' => false,                    // Bool	|	Add schema markup or not
		'placeholder'   => false,                    // Bool	|	Fallback placeholder
		'lazy_load'     => false,                    // Bool	|	Used for sliders
		'apply_filters' => '',                       // Str		|	Name for custom filter
		'filter_arg1'   => '',                       // Var		|	Argument 1 to pass to the filter if added
	);

	// Parse args
	$args = wp_parse_args( $args, $defaults );

	// Apply filters = Must run here !!
	if ( $args['apply_filters'] ) {
		$args = apply_filters( $args['apply_filters'], $args, $args['filter_arg1'] );
	}

	// If attachment is empty get attachment from current post
	if ( empty( $args['attachment'] ) ) {
		$args['attachment'] = get_post_thumbnail_id();
	}

	// Extract args
	extract( $args );

	// Return dummy image
	if ( 'dummy' == $attachment || $placeholder ) {
		return '<img src="'. esc_url( wpex_placeholder_img_src() ) .'" />';
	}

	// If size is empty but width/height are defined set size to wpex_custom
	if ( ! $size && ( $width || $height ) ) {
		$size = 'wpex_custom';
	} else {
		$size = $size ? $size : 'full'; // default size should be full if not defined
	}

	// Set size var to null if set to custom
	$size = ( 'wpex-custom' == $size || 'wpex_custom' == $size ) ? null : $size;

	// If image width and height equal '9999' set image size to full
	if ( '9999' == $width && '9999' == $height ) {
		$size = $size ? $size : 'full';
	}

	// Extra attributes for html return
	if ( 'html' == $return ) {

		// Define attributes for html output
		$attr = $attributes;

		// Add custom class if defined
		if ( $class ) {
			$attr['class'] = $class;
		}

		// Add style
		if ( $style ) {
			$attr['style'] = $style;
		}

		// Add schema markup
		if ( $schema_markup ) {
			$attr['itemprop'] = 'image';
		}

		// Add alt
		if ( $alt ) {
			$attr['alt'] = $alt;
		}

	}

	// On demand resizing
	// Custom Total output (needs to run even when image_resizing is disabled for custom image cropping in VC and widgets)
	if ( 'full' != $size && ( wpex_get_mod( 'image_resizing', true ) || ( $width || $height ) ) ) {

		// Get corrent dimentions for image size
		if ( $size ) {
			$dims   = wpex_get_thumbnail_sizes( $size );
			$width  = $dims['width'];
			$height = $dims['height'];
			$crop   = ! empty( $dims['crop'] ) ? $dims['crop'] : $crop; // important check
		}

		// Crop standard image
		$image = wpex_image_resize( array(
			'attachment' => $attachment,
			'size'       => $size,
			'width'      => $width,
			'height'     => $height,
			'crop'       => $crop,
		) );

		// Generate retina version
		if ( $retina ) {
			$retina_img = wpex_generate_retina_image( $attachment, $width, $height, $crop, $size );
		}

		// Return image
		if ( $image ) {

			// Return image URL
			if ( 'url' == $return ) {
				return $image['url'];
			}

			// Return src
			if ( 'src' == $return ) {
				return array(
					$image['url'],
					$image['width'],
					$image['height'],
					$image['is_intermediate'],
				);
			}

			// Return image HTMl
			elseif ( 'html' == $return ) {

				// Generate alt if not defined
				if ( ! $alt ) {

					$alt = trim( strip_tags( get_post_meta( $attachment, '_wp_attachment_image_alt', true ) ) );

					// Get img alt from description if alt doesn't exit
					if ( ! $alt && $img_description = get_post_field( 'post_excerpt', $attachment ) ) {
						$alt = trim( strip_tags( $img_description ) );
					}

					// Otherwide get alt from the title
					else if ( $img_title = get_the_title( $attachment ) ) {
						$alt = trim( strip_tags( $img_title ) );
						$alt = str_replace( '_', ' ', $alt );
						$alt = str_replace( '-', ' ', $alt );
					}

				}

				// Add alt attribute
				$attr['alt'] = ucwords( $alt );

				// Add retina attributes
				if ( ! empty( $retina_img ) ) {
					$attr['data-'. $retina_data] = $retina_img;
				} else {
					$attr['data-no-retina'] = '';
				}

				// Add attributes
				$add_attr = '';
				$attr = array_map( 'esc_attr', $attr ); // Sanitize attributes
				foreach ( $attr as $name => $value ) {
					$add_attr .= ' '. $name .'="'. esc_attr( $value ) .'"';
				}

				// Return img
				if ( $lazy_load ) {
					return '<img src="'. wpex_asset_url( 'images/blank.gif' ) .'" data-src="'. esc_url( $image['url'] ) .'" width="'. intval( $image['width'] ) .'" height="'. intval( $image['height'] ) .'"'. $add_attr .' />';
				} else {
					return '<img src="'. esc_url( $image['url'] ) .'" width="'. intval( $image['width'] ) .'" height="'. intval( $image['height'] ) .'"'. $add_attr .' />';
				}

			}

		}

	}

	// Return image from add_image_size
	// If on-the-fly is disabled for defined sizes or image size is set to "full"
	else {

		// Return image URL
		if ( 'url' == $return ) {
			$src = wp_get_attachment_image_src( $attachment, $size, false );
			return $src[0];
		}

		// Return src
		elseif ( 'src' == $return ) {
			return wp_get_attachment_image_src( $attachment, $size, false );
		}

		// Return image HTML
		elseif ( 'html' == $return ) {
			return wp_get_attachment_image( $attachment, $size, false, $attr );
		}

	}

}