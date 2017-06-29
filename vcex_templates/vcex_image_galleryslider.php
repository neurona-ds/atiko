<?php
/**
 * Visual Composer Image Gallery
 *
 * @package Total WordPress Theme
 * @subpackage VC Templates
 * @version 4.2.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Not needed in admin ever
if ( is_admin() ) {
	return;
}

// Required VC functions
if ( ! function_exists( 'vc_map_get_attributes' ) || ! function_exists( 'vc_shortcode_custom_css_class' ) ) {
	vcex_function_needed_notice();
	return;
}

// Define output var
$output = '';

// Get and extract shortcode attributes
extract( vc_map_get_attributes( 'vcex_image_galleryslider', $atts ) );

// Get images from post gallery
if ( 'true' == $post_gallery ) {
	$image_ids = wpex_get_gallery_ids();
}

// Get images based on Real Media folder
elseif ( defined( 'RML_VERSION' ) && $rml_folder ) {
	$rml_query = new WP_Query( array(
		'post_status'    => 'inherit',
		'posts_per_page' => $posts_per_page,
		'post_type'      => 'attachment',
		'orderby'        => 'rml', // Order by custom order of RML
		'rml_folder'     => $rml_folder,
		'fields'         => 'ids',
	) );
	if ( $rml_query->have_posts() ) {
		$image_ids = $rml_query->posts;
	}
}

// If there aren't any images lets display a notice
if ( empty( $image_ids ) ) {
	return;
}

// Otherwise if there are images lets turn it into an array
else {

	// Get image ID's
	if ( ! is_array( $image_ids ) ) {
		$attachments = explode( ',', $image_ids );
	} else {
		$attachments = $image_ids;
	}

}

// Turn links into array
if ( $custom_links && 'custom_link' == $thumbnail_link ) {

	// Remove duplicate images
	$attachments = array_unique( $attachments );

	// Turn links into array
	if ( $custom_links ) {
		$custom_links = explode( ',', $custom_links );
	} else {
		$custom_links = array();
	}

	// Count items
	$attachments_count = count( $attachments );
	$custom_links_count   = count( $custom_links );

	// Add empty values to custom_links array for images without links
	if ( $attachments_count > $custom_links_count ) {
		$count = 0;
		foreach( $attachments as $val ) {
			$count++;
			if ( ! isset( $custom_links[$count] ) ) {
				$custom_links[$count] = '#';
			}
		}
	}

	// New custom links count
	$custom_links_count = count( $custom_links );

	// Remove extra custom links
	if ( $custom_links_count > $attachments_count ) {
		$count = 0;
		foreach( $custom_links as $key => $val ) {
			$count ++;
			if ( $count > $attachments_count ) {
				unset( $custom_links[$key] );
			}
		}
	}

	// Set links as the keys for the images
	$attachments = array_combine( $attachments, $custom_links );

} else {

	$attachments = array_combine( $attachments, $attachments );
	
};

// Output gallery
if ( $attachments ) :

	// Load scripts
	if ( 'lightbox' == $thumbnail_link ) {
		vcex_enque_style( 'ilightbox', $lightbox_skin );
	}

	// Sanitize data and declare main vars
	$wrap_attributes    = array();
	$caption_attributes = array();
	$slideshow          = wpex_vc_is_inline() ? 'false' : $slideshow;
	$thumbnails_columns = $thumbnails_columns ? $thumbnails_columns : '5';
	$lazy_load          = 'true' == $lazy_load ? true : false;

	// Slider attributes
	$wrap_attributes[] = 'data-thumbnails="true"';
	$wrap_attributes[] = 'data-thumbnail-height="auto"';
	if ( in_array( $animation, array( 'fade', 'fade_slides' ) ) ) {
		$wrap_attributes[] = 'data-fade="true"';
	}
	if ( 'true' == $randomize ) {
		$wrap_attributes[] = 'data-shuffle="true"';
	}
	if ( 'true' == $loop ) {
		$wrap_attributes[] = 'data-loop="true"';
	}
	if ( 'false' == $slideshow ) {
		$wrap_attributes[] = 'data-auto-play="false"';
	}
	if ( $slideshow && $slideshow_speed ) {
		$wrap_attributes[] = 'data-auto-play-delay="'. $slideshow_speed .'"';
	}
	if ( 'false' == $direction_nav ) {
		$wrap_attributes[] = 'data-arrows="false"';
	}
	if ( 'false' == $control_nav ) {
		$wrap_attributes[] = 'data-buttons="false"';
	}
	if ( 'false' == $direction_nav_hover ) {
		$wrap_attributes[] = 'data-fade-arrows="false"';
	}
	if ( $animation_speed ) {
		$wrap_attributes[] = 'data-animation-speed="'. intval( $animation_speed ) .'"';
	}
	if ( $height_animation ) {
		$height_animation = intval( $height_animation );
		$height_animation = 0 == $height_animation ? '0.0' : $height_animation;
		$wrap_data[] = 'data-height-animation-duration="'. $height_animation .'"';
	}

	// Caption attributes
	if ( 'true' == $caption ) {

		// Caption attributes
		if ( $caption_position ) {
			$caption_attributes[] = ' data-position="'. $caption_position .'"';
		}
		if ( $caption_show_transition ) {
			$caption_attributes[] = ' data-show-transition="'. $caption_show_transition .'"';
		}
		if ( $caption_hide_transition ) {
			$caption_attributes[] = ' data-hide-transition="'. $caption_hide_transition .'"';
		}
		if ( $caption_width ) {
			$caption_attributes[] = ' data-width="'. wpex_sanitize_data( $caption_width, 'px-pct' ) .'"';
		}
		if ( $caption_horizontal ) {
			$caption_attributes[] = ' data-horizontal="'. intval( $caption_horizontal ) .'"';
		}
		if ( $caption_vertical ) {
			$caption_attributes[] = ' data-vertical="'. intval( $caption_vertical ) .'"';
		}
		if ( $caption_delay ) {
			$caption_attributes[] = ' data-show-delay="'. intval( $caption_delay ) .'"';
		}
		if ( empty( $caption_show_transition ) && empty( $caption_hide_transition ) ) {
			$caption_attributes[] = ' data-sp-static="false"';
		}
		$caption_attributes = implode( ' ', $caption_attributes );

		// Caption classes
		$caption_classes = array( 'wpex-slider-caption', 'sp-layer', 'sp-padding', 'clr' );
		if ( $caption_visibility ) {
			$caption_classes[] = $caption_visibility;
		}
		if ( $caption_style ) {
			$caption_classes[] = 'sp-'. $caption_style;
		}
		if ( 'true' == $caption_rounded ) {
			$caption_classes[] = 'sp-rounded';
		}
		$caption_classes = implode( ' ', $caption_classes );

		// Caption style
		$caption_inline_style = vcex_inline_style( array(
			'font_size' => $caption_font_size,
			'padding'   => $caption_padding,
		) );

	}

	// Main Classes
	$wrap_classes = array( 'vcex-module', 'wpex-slider', 'slider-pro', 'no-margin-thumbnails', 'vcex-image-gallery-slider', 'clr' );
	if ( $classes ) {
		$wrap_classes[] = vcex_get_extra_class( $classes );
	}
	if ( $visibility ) {
		$wrap_classes[] = $visibility;
	}
	if ( 'lightbox' == $thumbnail_link ) {
		$wrap_classes[] = ' lightbox-group';
		if ( $lightbox_skin ) {
			$wrap_attributes[] = 'data-skin="'. $lightbox_skin .'"';
			vcex_enque_style( 'ilightbox', $lightbox_skin );
		}
		if ( $lightbox_path ) {
			$wrap_attributes[] = 'data-path="'. $lightbox_path .'"';
		}
	}

	// Convert arrays to strings
	$wrap_classes    = implode( ' ', $wrap_classes );
	$wrap_attributes = ' '. implode( ' ', $wrap_attributes );

	// Apply filters
	$wrap_classes = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $wrap_classes, 'vcex_image_galleryslider', $atts );

	// Open animation wrapper
	if ( $css_animation && 'none' != $css_animation ) {
		$output .= '<div class="'. vcex_get_css_animation( $css_animation ) .'">';
	}

	// Open css wrapper
	if ( $css ) {
		$output .= '<div class="vcex-image-gallery-slider-css-wrap '. vc_shortcode_custom_css_class( $css ) .'">';
	}

	// Preloader image
	$output .= '<div class="wpex-slider-preloaderimg '. $visibility .'">';

		$first_attachment = reset( $attachments );

		$output .= wpex_get_post_thumbnail( array(
			'attachment' => current( array_keys( $attachments ) ),
			'size'       => $img_size,
			'crop'       => $img_crop,
			'width'      => $img_width,
			'height'     => $img_height,
		) );

	$output .= '</div>';

	// Main output begins
	$output .= '<div class="'. $wrap_classes .'"'. vcex_get_unique_id( $unique_id ) . $wrap_attributes .'>';

		$output .= '<div class="wpex-slider-slides sp-slides">';

			// Loop through attachments
			foreach ( $attachments as $attachment => $custom_link ) :
			
				// Attachment VARS
				$custom_link      = ( '#' != $custom_link ) ? $custom_link : '';
				$attachment_link  = get_post_meta( $attachment, '_wp_attachment_url', true );
				$attachment_data  = wpex_get_attachment_data( $attachment );
				$caption_type     = $caption_type ? $caption_type : 'caption';
				$caption_output   = $attachment_data[$caption_type];
				$attachment_video = $attachment_data['video'];

				// Get and crop image if needed
				$attachment_img = wpex_get_post_thumbnail( array(
					'attachment'  => $attachment,
					'size'        => $img_size,
					'crop'        => $img_crop,
					'width'       => $img_width,
					'height'      => $img_height,
					'alt'         => $attachment_data['alt'],
					'lazy_load'   => $lazy_load,
					'retina_data' => 'retina',
				) );

				$output .= '<div class="wpex-slider-slide sp-slide">';

					$output .= '<div class="wpex-slider-media">';

						// Display video
						if ( $attachment_video ) :

							$output .= '<div class="wpex-slider-video responsive-video-wrap">';
						
								$output .= wpex_video_oembed( $attachment_video, 'sp-video', array(
									'youtube' => array(
										'enablejsapi' => '1',
									)
								) );

							$output .= '</div>';

						else :

							// Lightbox links
							if ( 'lightbox' == $thumbnail_link ) :

								$lightbox_data_attributes = '';
								if ( $lightbox_title && 'none' != $lightbox_title ) {
									if ( 'title' == $lightbox_title && $attachment_data['title'] ) {
										$lightbox_data_attributes .= ' data-title="'. esc_attr( $attachment_data['title'] ) .'"';
									} elseif ( esc_attr( $attachment_data['alt'] ) ) {
										$lightbox_data_attributes .= ' data-title="'. esc_attr( $attachment_data['alt'] ) .'"';
									}
								} else {
									$lightbox_data_attributes .= ' data-show_title="false"';
								}

								// Caption data
								if ( $attachment_data['caption'] && 'false' != $lightbox_caption ) {
									$lightbox_data_attributes .= ' data-caption="'. str_replace( '"',"'", $attachment_data['caption'] ) .'"';
								}

								$output .= '<a href="'. wpex_get_lightbox_image( $attachment ) .'" title="'. esc_attr( $attachment_data['alt' ] ) .'" class="vcex-galleryslider-entry-img wpex-lightbox-group-item"'. $lightbox_data_attributes .'>';
									$output .= $attachment_img;
								$output .= '</a>';

							// Custom links
							elseif ( 'custom_link' == $thumbnail_link ) :

								if ( $custom_link ) {

									$output .= '<a href="'. esc_url( $custom_link ) .'"'. vcex_html( 'title_attr', $attachment_data['title'] ) .''. vcex_html( 'target_attr', $custom_links_target ) .'>';
										$output .= $attachment_img;
									$output .= '</a>';

								} else {

									$output .= $attachment_img;

								}

							// No links
							else :

								$output .= $attachment_img;

							endif; // End link check

						endif; // End video check

						// Display caption
						if ( empty( $attachment_video ) && 'true' == $caption && $caption_output ) :

							$output .= '<div class="'. $caption_classes .'"'. $caption_attributes .''. $caption_inline_style .'>';

								if ( in_array( $caption_type, array( 'description', 'caption' ) ) ) :

									$output .= wpautop( $caption_output );

								else :

									$output .= $caption_output;

								endif;

							$output .= '</div>';

						endif;

					$output .= '</div>';

				$output .= '</div>';

			endforeach;
			
		$output .= '</div>';

		$output .= '<div class="wpex-slider-thumbnails sp-nc-thumbnails cols-'. $thumbnails_columns .'">';

			// Loop through attachments to display thumbnails
			foreach ( $attachments as $attachment => $custom_link ) :

				// Output thumbnail image
				$output .= wpex_get_post_thumbnail( array(
					'attachment'  => $attachment,
					'size'        => 'wpex_custom',
					'width'       => $img_thumb_width,
					'height'      => $img_thumb_height,
					'crop'        => false,
					'class'       => 'wpex-slider-thumbnail sp-nc-thumbnail',
					'retina_data' => 'retina',
					//'lazy_load'  => $lazy_load, // Not possible for this module
				) );

			endforeach;

		$output .= '</div>';

	$output .= '</div>';

	// Close css wrapper
	if ( $css ) {
		$output .= '</div>';
	}

	// Close animation wrapper
	if ( $css_animation && 'none' != $css_animation ) {
		$output .= '</div>';
	}

	// Output shortcode html
	echo $output;

endif;