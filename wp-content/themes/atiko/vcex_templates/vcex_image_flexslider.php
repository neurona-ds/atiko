<?php
/**
 * Visual Composer Image Slider
 *
 * @package Total WordPress Theme
 * @subpackage VC Templates
 * @version 4.1
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
extract( vc_map_get_attributes( 'vcex_image_flexslider', $atts ) );

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
	$attachments_count  = count( $attachments );
	$custom_links_count = count( $custom_links );

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
	
}

// Output images
if ( $attachments ) :

	// Load scripts
	if ( 'lightbox' == $thumbnail_link ) {
		vcex_enque_style( 'ilightbox', $lightbox_skin );
	}

	// Sanitize data and declare main vars
	$caption_data = array();
	$wrap_data    = array();
	$slideshow    = wpex_vc_is_inline() ? 'false' : $slideshow;
	$lazy_load    = 'true' == $lazy_load ? true : false;

	// Slider attributes
	if ( in_array( $animation, array( 'fade', 'fade_slides' ) ) ) {
		$wrap_data[] = 'data-fade="true"';
	}
	if ( 'true' == $randomize ) {
		$wrap_data[] = 'data-shuffle="true"';
	}
	if ( 'true' == $loop ) {
		$wrap_data[] = ' data-loop="true"';
	}
	if ( 'false' == $slideshow ) {
		$wrap_data[] = 'data-auto-play="false"';
	}
	if ( $slideshow && $slideshow_speed ) {
		$wrap_data[] = 'data-auto-play-delay="'. $slideshow_speed .'"';
	}
	if ( 'false' == $direction_nav ) {
		$wrap_data[] = 'data-arrows="false"';
	}
	if ( 'false' == $control_nav ) {
		$wrap_data[] = 'data-buttons="false"';
	}
	if ( 'false' == $direction_nav_hover ) {
		$wrap_data[] = 'data-fade-arrows="false"';
	}
	if ( 'true' == $control_thumbs ) {
		$wrap_data[] = 'data-thumbnails="true"';
	}
	if ( 'true' == $control_thumbs && 'true' == $control_thumbs_pointer ) {
		$wrap_data[] = 'data-thumbnail-pointer="true"';
	}
	if ( $animation_speed ) {
		$wrap_data[] = 'data-animation-speed="'. intval( $animation_speed ) .'"';
	}
	if ( $height_animation ) {
		$height_animation = intval( $height_animation );
		$height_animation = 0 == $height_animation ? '0.0' : $height_animation;
		$wrap_data[] = 'data-height-animation-duration="'. $height_animation .'"';
	}
	if ( $control_thumbs_height ) {
		$wrap_data[] = 'data-thumbnail-height="'. intval( $control_thumbs_height ) .'"';
	}
	if ( $control_thumbs_width ) {
		$wrap_data[] = 'data-thumbnail-width="'. intval( $control_thumbs_width ) .'"';
	}

	// Caption attributes and classes
	if ( 'true' == $caption ) {

		// Sanitize vars
		$caption_width = $caption_width ? $caption_width : '100%';

		// Caption attributes
		if ( $caption_position ) {
			$caption_data[] = 'data-position="'. $caption_position .'"';
		}
		if ( $caption_show_transition ) {
			$caption_data[] = 'data-show-transition="'. $caption_show_transition .'"';
		}
		if ( $caption_hide_transition ) {
			$caption_data[] = 'data-hide-transition="'. $caption_hide_transition .'"';
		}
		if ( $caption_width ) {
			$caption_data[] = 'data-width="'. wpex_sanitize_data( $caption_width, 'px-pct' ) .'"';
		}
		if ( $caption_horizontal ) {
			$caption_data[] = 'data-horizontal="'. intval( $caption_horizontal ) .'"';
		}
		if ( $caption_vertical ) {
			$caption_data[] = 'data-vertical="'. intval( $caption_vertical ) .'"';
		}
		if ( $caption_delay ) {
			$caption_data[] = 'data-show-delay="'. intval( $caption_delay ) .'"';
		}
		if ( empty( $caption_show_transition ) && empty( $caption_hide_transition ) ) {
			$caption_data[] = 'data-sp-static="false"';
		}
		$caption_data = $caption_data ? ' '. implode( ' ', $caption_data ) : '';

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
	$wrap_classes = array( 'vcex-module', 'wpex-slider', 'slider-pro', 'vcex-image-slider', 'clr' );
	if ( $classes ) {
		$wrap_classes[] = vcex_get_extra_class( $classes );
	}
	if ( 'false' == $img_strech ) {
		$wrap_classes[] = 'no-stretch';
	}
	if ( $visibility ) {
		$wrap_classes[] = $visibility;
	}
	if ( 'lightbox' == $thumbnail_link ) {
		$wrap_classes[] = 'lightbox-group';
		if ( $lightbox_skin ) {
			$wrap_data[] = 'data-skin="'. esc_attr( $lightbox_skin ) .'"';
			vcex_enque_style( 'ilightbox', $lightbox_skin );
		}
		if ( $lightbox_path ) {
			$wrap_data[] = 'data-path="'. esc_attr( $lightbox_path ) .'"';
		}
		if ( 'none' == $lightbox_title ) {
			$wrap_data[] = 'data-show_title="false"';
		}
	}

	// Convert arrays into strings
	$wrap_classes = implode( ' ', $wrap_classes );
	$wrap_data    = $wrap_data ? ' '. implode( ' ', $wrap_data ) : '';

	// Apply filters
	$wrap_classes = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $wrap_classes, 'vcex_image_flexslider', $atts );

	// Open animation wrapper
	if ( $css_animation && 'none' != $css_animation ) {
		$output .= '<div class="'. vcex_get_css_animation( $css_animation ) .'">';
	}

	// Open css wrapper
	if ( $css ) {
		$output .= '<div class="vcex-image-slider-css-wrap '. vc_shortcode_custom_css_class( $css ) .'">';
	}

	$preloader_classes = 'wpex-slider-preloaderimg';
	if ( 'false' == $img_strech ) {
		$preloader_classes .= ' no-stretch';
	}
	if ( $visibility ) {
		$preloader_classes .= ' '. $visibility;
	}

	$output .= '<div class="'. esc_attr( $preloader_classes ) .'">';

		$first_attachment = reset( $attachments );
		$output .= wpex_get_post_thumbnail( array(
			'attachment' => current( array_keys( $attachments ) ),
			'size'       => $img_size,
			'crop'       => $img_crop,
			'width'      => $img_width,
			'height'     => $img_height,
		) );

	$output .= '</div>';

	$output .= '<div class="'. $wrap_classes .'"'. vcex_get_unique_id( $unique_id ) . $wrap_data .'>';

		$output .= '<div class="wpex-slider-slides sp-slides">';

			// Loop through attachments
			foreach ( $attachments as $attachment => $custom_link ) :

				// Define main vars
				$custom_link      = ( '#' != $custom_link ) ? $custom_link : '';
				$attachment_link  = get_post_meta( $attachment, '_wp_attachment_url', true );
				$attachment_data  = wpex_get_attachment_data( $attachment );
				$caption_enabled  = ( 'true' == $caption ) ? true : false;
				$caption_type     = $caption_type ? $caption_type : 'caption';
				$caption_output   = $caption_enabled ? $attachment_data[$caption_type] : '';
				$attachment_video = $attachment_data['video'];

				// Generate img HTML
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

				// Image or video needed
				if ( $attachment_img || $attachment_video ) {

					$output .= '<div class="wpex-slider-slide sp-slide">';

						$output .= '<div class="wpex-slider-media">';

							// Check if the current attachment has a video
							if ( $attachment_video ) {

								if ( 'true' != $video_captions ) {
									$caption_enabled = false;
								}

								// Output video
								$output .= '<div class="wpex-slider-video responsive-video-wrap">';

									$output .= wpex_video_oembed( $attachment_video, 'sp-video', array(
										'youtube' => array(
											'enablejsapi' => '1',
										)
									) );
									
								$output .= '</div>';

								//$output .= '<a href="'. $attachment_video .'" class="sp-video">'. $attachment_img .'</a>';

							} elseif( $attachment_img ) {

								// Lightbox links
								if ( 'lightbox' == $thumbnail_link ) {

									// Data attributes
									$lightbox_data_attributes = ' data-type="image"';
									if ( 'title' == $lightbox_title && $attachment_data['title'] ) {
										$lightbox_data_attributes .= ' data-title="'. $attachment_data['title'] .'"';
									} elseif ( 'alt' == $lightbox_title ) {
										$lightbox_alt = get_post_meta( $attachment, '_wp_attachment_image_alt', true );
										if ( $lightbox_alt ) {
											$lightbox_data_attributes .= ' data-title="'. esc_attr( $lightbox_alt ) .'"';
										} else {
											$lightbox_data_attributes .= ' data-title="false"';
										}
									}

									// Caption data
									if ( $attachment_data['caption'] && 'false' != $lightbox_caption ) {
										$lightbox_data_attributes .= ' data-caption="'. str_replace( '"',"'", $attachment_data['caption'] ) .'"';
									}

									$output .= '<a href="'. wpex_get_lightbox_image( $attachment ) .'" title="'. esc_attr( $attachment_data['title'] ) .'" class="vcex-flexslider-entry-img wpex-slider-media-link wpex-lightbox-group-item"'. $lightbox_data_attributes .'>';
										$output .= $attachment_img;
									$output .= '</a>';
								
								// Custom Links
								} elseif ( 'custom_link' == $thumbnail_link ) {

									// Custom link
									if ( $custom_link ) {

										$output .= '<a href="'. esc_url( $custom_link ) .'"'. vcex_html( 'title_attr', $attachment_data['title'] ) .''. vcex_html( 'target_attr', $custom_links_target ) .' class="wpex-slider-media-link">';
											
											$output .= $attachment_img;

										$output .= '</a>';

									// No link
									} else {

										$output .= $attachment_img;

									}

								// Just images, no links
								} else {

									// Display the main slider image
									$output .= $attachment_img;

								}

							}

							// Display caption if enabled and there is one
							if ( $caption_enabled && $caption_output ) {

								$output .= '<div class="'. $caption_classes .'"'. $caption_data .''. $caption_inline_style .'>';

									if ( in_array( $caption_type, array( 'description', 'caption' ) ) ) :

										$output .= wpautop( $caption_output );

									else :

										$output .= $caption_output;

									endif;

								$output .= '</div>';

							}

						$output .= '</div>';

					$output .= '</div>';

				}

			endforeach;

		$output .= '</div>';

		if ( 'true' == $control_thumbs ) {

			$output .= '<div class="wpex-slider-thumbnails sp-thumbnails">';

				foreach ( $attachments as $attachment => $custom_link ) :

					$output .= wpex_get_post_thumbnail( array(
						'attachment'  => $attachment,
						'size'        => $img_size,
						'crop'        => $img_crop,
						'width'       => $img_width,
						'height'      => $img_height,
						'class'       => 'wpex-slider-thumbnail sp-thumbnail',
						'lazy_load'   => $lazy_load,
						'retina_data' => 'retina',
					) );

				endforeach;

			$output .= '</div>';

		}

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