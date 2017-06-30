<?php
/**
 * Visual Composer Image Grid
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

// Define 3rd party attributes that do not exist in atts by default
$rml_folder = '';

// Get and extract shortcode attributes
$atts = vc_map_get_attributes( 'vcex_image_grid', $atts );
extract( $atts );

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

// If there aren't any images return
if ( empty( $image_ids ) ) {
	return;
}

// Otherwise if there are images lets turn it into an array
else {

	// Get image ID's
	if ( ! is_array( $image_ids ) ) {
		$attachment_ids = explode( ',', $image_ids );
	} else {
		$attachment_ids = $image_ids;
	}

}

// Lets do some things now that we have images
if ( ! empty ( $attachment_ids ) ) :

	// Declare vars
	$is_isotope = false;

	// Remove duplicate images
	$attachment_ids = array_unique( $attachment_ids );

	// Turn links into array
	if ( $custom_links ) {
		$custom_links = explode( ',', $custom_links );
	} else {
		$custom_links = array();
	}

	// Count items
	$attachment_ids_count = count( $attachment_ids );
	$custom_links_count   = count( $custom_links );

	// Add empty values to custom_links array for images without links
	if ( $attachment_ids_count > $custom_links_count ) {
		$count = 0;
		foreach( $attachment_ids as $val ) {
			$count++;
			if ( ! isset( $custom_links[$count] ) ) {
				$custom_links[$count] = '#';
			}
		}
	}

	// New custom links count
	$custom_links_count = count( $custom_links );

	// Remove extra custom links
	if ( $custom_links_count > $attachment_ids_count ) {
		$count = 0;
		foreach( $custom_links as $key => $val ) {
			$count ++;
			if ( $count > $attachment_ids_count ) {
				unset( $custom_links[$key] );
			}
		}
	}

	// Set links as the keys for the images
	$images_links_array = array_combine( $attachment_ids, $custom_links );

	// Pagination variables
	$posts_per_page = $posts_per_page ? $posts_per_page : '-1';
	$paged          = NULL;
	$no_found_rows  = true;
	if ( '-1' != $posts_per_page ) {
		if ( get_query_var( 'paged' ) ) {
			$paged = get_query_var( 'paged' );
		} elseif ( get_query_var( 'page' ) ) {
			$paged = get_query_var( 'page' );
		} else {
			$paged = 1;
		}
		$no_found_rows  = false;
	}

	// Randomize images
	if ( 'true' == $randomize_images ) {
		$orderby = 'rand';
	} else {
		$orderby = 'post__in';
	}

	// Lets create a new Query so the image grid can be paginated
	$wpex_query = new WP_Query(
		array(
			'post_type'      => 'attachment',
			//'post_mime_type'    => 'image/jpeg,image/gif,image/jpg,image/png',
			'post_status'    => 'any',
			'posts_per_page' => $posts_per_page,
			'paged'          => $paged,
			'post__in'       => $attachment_ids,
			'no_found_rows'  => $no_found_rows,
			'orderby'        => $orderby,
		)
	);

	// Display images if we found some
	if ( $wpex_query->have_posts() ) :

		// Sanitize params
		$overlay_style = $overlay_style ? $overlay_style : 'none';

		// Define isotope variable for masony and no margin grids
		if ( 'masonry' == $grid_style || 'no-margins' == $grid_style ) {
			$is_isotope = true;
		}

		// Link target
		$atts['link_target'] = $custom_links_target;

		// Wrap Classes
		$wrap_classes = array( 'vcex-module', 'vcex-image-grid', 'wpex-row', 'clr' );
		$wrap_classes[] = 'grid-style-'. $grid_style;
		if ( $columns_gap ) {
			$wrap_classes[] = 'gap-'. $columns_gap;
		}
		if ( $is_isotope ) {
			$wrap_classes[] = 'vcex-isotope-grid no-transition';
		}
		if ( 'no-margins' == $grid_style ) {
			$wrap_classes[] = 'vcex-no-margin-grid';
		}
		if ( 'lightbox' == $thumbnail_link ) {
			if ( 'true' == $lightbox_gallery ) {
				$wrap_classes[] = 'lightbox-group';
			}
		}
		if ( 'yes' == $rounded_image ) {
			$wrap_classes[] = 'wpex-rounded-images';
		}
		if ( $classes ) {
			$wrap_classes[] = vcex_get_extra_class( $classes );
		}
		if ( $visibility ) {
			$wrap_classes[] = $visibility;
		}

		// Wrap data attributes
		$wrap_data = array();
		if ( $is_isotope ) {
			$wrap_data[] = 'data-transition-duration="0.0"';
		}
		if ( 'lightbox' == $thumbnail_link ) {
			$lightbox_data = array();
			if ( $lightbox_skin ) {
				$lightbox_data[] = 'data-skin="'. $lightbox_skin .'"';
			}
			if ( $lightbox_path ) {
				if ( 'disabled' == $lightbox_path ) {
					$lightbox_data[] = 'data-thumbnails="false"';
				} else {
					$lightbox_data[] = 'data-path="'. $lightbox_path .'"';
				}
			}
			vcex_enque_style( 'ilightbox', $lightbox_skin );
			$wrap_data = array_merge( $wrap_data, $lightbox_data );
		}

		// Columns classes
		$columns_class = vcex_get_grid_column_class( $atts );
		
		// Entry Classes
		$entry_classes = array( 'vcex-image-grid-entry' );
		if ( $is_isotope ) {
			$entry_classes[] = 'vcex-isotope-entry';
		}
		if ( 'no-margins' == $grid_style ) {
			$entry_classes[] = 'vcex-no-margin-entry';
		}
		if ( $columns ) {
			$entry_classes[] = $columns_class;
		}
		if ( 'false' == $responsive_columns ) {
			$entry_classes[] = 'nr-col';
		} else {
			$entry_classes[] = 'col';
		}
		if ( $css_animation && 'none' != $css_animation ) {
			$entry_classes[] = vcex_get_css_animation( $css_animation );
		}

		// Figure classes - image + caption
		$figure_classes = array( 'vcex-image-grid-entry-figure', 'clr' );
		if ( $entry_css ) {
			$figure_classes[] = vc_shortcode_custom_css_class( $entry_css );
		}

		// Image classes
		$img_wrap_classes = array( 'vcex-image-grid-entry-img', 'clr' );
		if ( $hover_animation ) {
			$img_wrap_classes[] = wpex_hover_animation_class( $hover_animation );
			vcex_enque_style( 'hover-animations' );
		}
		if ( 'none' != $overlay_style ) {
			$overlay_classes  = wpex_overlay_classes( $overlay_style );
			$img_wrap_classes[] = $overlay_classes;
		}
		if ( $img_filter ) {
			$img_wrap_classes[] = wpex_image_filter_class( $img_filter );
		}
		if ( $img_hover_style ) {
			$img_wrap_classes[] = wpex_image_hover_classes( $img_hover_style );
		}

		// Lightbox class
		if ( 'true' == $lightbox_gallery ) {
			$lightbox_class = 'wpex-lightbox-group-item';
		} else {
			$lightbox_class = 'wpex-lightbox';
		}

		// Title style & title related vars
		if ( 'yes' == $title ) {
			$title_tag   = $title_tag ? $title_tag : 'h2';
			$title_type  = $title_type ? $title_type : 'title';
			$title_style = vcex_inline_style( array(
				'font_size'      => $title_size,
				'color'          => $title_color,
				'text_transform' => $title_transform,
				'line_height'    => $title_line_height,
				'margin'         => $title_margin,
				'font_weight'    => $title_weight,
				'font_family'    => $title_font_family,
			) );
			if ( $title_font_family ) {
				wpex_enqueue_google_font( $title_font_family );
			}
		}

		// Convert arrays to strings
		$wrap_classes     = implode( ' ', $wrap_classes );
		$wrap_data        = implode( ' ', $wrap_data );
		$img_wrap_classes = implode( ' ', $img_wrap_classes );
		$entry_classes    = implode( ' ', $entry_classes );
		$figure_classes   = implode( ' ', $figure_classes );

		// Apply filters
		$wrap_classes = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $wrap_classes, 'vcex_image_grid', $atts );

		// Wrap attributes
		$wrap_attrs = array(
			'id'    => vcex_get_unique_id( $unique_id ),
			'class' => $wrap_classes,
			'data'  => $wrap_data,
		);

		// Open CSS div
		if ( $css ) {

			$output .= '<div class="vcex-image-grid-css-wrapper '. vc_shortcode_custom_css_class( $css ).'">';
			
		}

		$output .= '<div '. wpex_parse_attrs( $wrap_attrs ) .'>';
			
			$count=0;
			while ( $wpex_query->have_posts() ) :
			$count++;

				// Get post from query
				$wpex_query->the_post();

				// Get post data and define main vars
				$post_id            = get_the_ID();
				$post_data          = wpex_get_attachment_data( $post_id );
				$post_link          = $post_data['url'];
				$post_alt           = esc_attr( $post_data['alt'] );
				$post_title_display = false;

				// Get original attachment ID - fix for WPML
				if ( $custom_links_count && WPEX_WPML_ACTIVE ) {
					global $sitepress;
					if ( $sitepress ) {
						$default_lang = $sitepress->get_default_language();
						$post_id = icl_object_id( $post_id, 'attachment', false, $default_lang );
					}
				}

				// Pluck array to see if item has custom link
				$post_url    = $images_links_array[$post_id];
				$post_url_tt = '';

				// Validate URl
				$post_url = ( '#' !== $post_url ) ? $post_url : '';

				// Set image HTML since we'll use it a lot later on
				$post_thumbnail = wpex_get_post_thumbnail( array(
					'size'       => $img_size,
					'attachment' => $post_id,
					'alt'        => $post_alt,
					'width'      => $img_width,
					'height'     => $img_height,
					'crop'       => $img_crop,
				) );

				$output .= '<div class="id-'. $post_id .' '. $entry_classes.' col-'. $count.'">';

					$output .= '<figure class="'. $figure_classes .'">';

						// Image wrap
						$output .= '<div class="'. $img_wrap_classes.'">';

							// Lightbox
							if ( 'lightbox' == $thumbnail_link ) :

								// Define lightbox vars
								$atts['lightbox_data'] = $lightbox_data;
								$lightbox_image        = wpex_get_lightbox_image( $post_id );
								$lightbox_url          = $lightbox_image;
								$video_url             = $post_data['video'];

								// Data attributes
								if ( 'false' != $lightbox_title ) {
									if ( 'title' == $lightbox_title ) {
										$atts['lightbox_data'][] = 'data-title="'. strip_tags( get_the_title( $post_id ) ) .'"';
									} elseif ( 'alt' == $lightbox_title ) {
										$atts['lightbox_data'][] = 'data-title="'. $post_alt .'"';
									}
								} else {
									$atts['lightbox_data'][] = 'data-show_title="false"';
								}

								// Caption data
								if ( 'false' != $lightbox_caption && $post_data['caption'] ) {
									$atts['lightbox_data'][] = 'data-caption="'. str_replace( '"',"'", $post_data['caption'] ) .'"';
								}

								// Video data
								if ( $video_url ) {
									$video_embed_url = wpex_sanitize_data( $video_url, 'embed_url' );
									$lightbox_url    = $video_embed_url ? $video_embed_url : $video_url;
									if ( $video_embed_url ) {
										$atts['lightbox_data']['data-type'] = 'data-type="iframe"';
										$smart_recognition = '';
									} else {
										$smart_recognition = ',smartRecognition:true';
									}
									$atts['lightbox_data']['data-options'] = 'data-options="iframeType:\'video\',thumbnail:\''. $lightbox_image .'\''. $smart_recognition .'"';
								}

								// Set data type to image for non-video lightbox
								else {
									$atts['lightbox_data'][] = 'data-type="image"';
								}

								// Convert data attributes to array
								$atts['lightbox_data'] = ' '. implode( ' ', $atts['lightbox_data'] );

								// Get title tag if enabled
								if ( 'true' == $link_title_tag ) {
									$post_url_tt = vcex_html( 'title_attr', $post_alt, false );
								}

								// Open link tag
								$output .= '<a href="'. esc_url( $lightbox_url ).'" class="vcex-image-grid-entry-img '. $lightbox_class.'"'. $post_url_tt . $atts["lightbox_data"].'>';

									// Display image
									$output .= $post_thumbnail;

									// Video icon overlay
									if ( $video_url && 'none' == $overlay_style ) {
										$output .= '<div class="overlay-icon"><span>&#9658;</span></div>';
									}

									// Inner link overlay html
									if ( 'none' != $overlay_style ) {

										ob_start();
										wpex_overlay( 'inside_link', $overlay_style, $atts );
										$output .= ob_get_clean();

									}

								$output .= '</a>';

							// Custom Links
							elseif ( 'custom_link' == $thumbnail_link && $post_url ) :

								// Get title tag if enabled
								if ( 'true' == $link_title_tag ) {
									$post_url_tt = vcex_html( 'title_attr', $post_alt, false );
								}

								// Link target
								$post_url_target =  vcex_html( 'target_attr', $atts['link_target'], false );

								// Open link tag
								$output .= '<a href="'. esc_url( $post_url ).'" class="vcex-image-grid-entry-img"'. $post_url_tt . $post_url_target .'>';

									// Display image
									$output .= $post_thumbnail;

									// Inner link overlay html
									if ( 'none' != $overlay_style ) {
										ob_start();
										wpex_overlay( 'inside_link', $overlay_style, $atts );
										$output .= ob_get_clean();
									}

								$output .= '</a>';

							// Attachment page
							elseif ( 'attachment_page' == $thumbnail_link ) :

								// Get title tag if enabled
								if ( 'true' == $link_title_tag ) {
									$post_url_tt = vcex_html( 'title_attr', $post_alt, false );
								}

								// Link target
								$post_url_target =  vcex_html( 'target_attr', $atts['link_target'], false );

								// Open link tag
								$output .= '<a href="'. get_permalink() .'" class="vcex-image-grid-entry-img"'. $post_url_tt . $post_url_target .'>';

									// Display image
									$output .= $post_thumbnail;

									// Inner link overlay html
									if ( 'none' != $overlay_style ) {
										ob_start();
										wpex_overlay( 'inside_link', $overlay_style, $atts );
										$output .= ob_get_clean();
									}

								$output .= '</a>';

							// Just the Image - no link
							else :

								// Display image
								$output .= $post_thumbnail;

								if ( 'none' != $overlay_style ) {
									ob_start();
									wpex_overlay( 'inside_link', $overlay_style, $atts );
									$output .= ob_get_clean();
								}

							endif;

							// Outside link overlay html
							if ( 'none' != $overlay_style ) {

								if ( 'custom_link' == $thumbnail_link && $post_url ) {
									$atts['overlay_link'] = $post_url;
								} elseif( 'lightbox' == $thumbnail_link && $lightbox_url ) {
									$atts['lightbox_link'] = $lightbox_url;
								}

								ob_start();
								wpex_overlay( 'outside_link', $overlay_style, $atts );
								$output .= ob_get_clean();

							}

						// Close image wrap
						$output .= '</div>';

						// If title is enabled
						if ( 'yes' == $title ) :

							// Get correct title
							if ( 'title' == $title_type ) {
								$post_title_display = get_the_title();
							} elseif ( 'alt' == $title_type ) {
								$post_title_display = $post_alt;
							} elseif ( 'caption' == $title_type ) {
								$post_title_display = get_the_excerpt();
							} elseif ( 'description' == $title_type ) {
								$post_title_display = get_the_content();
							}

							// Display title
							if ( $post_title_display ) :

								$output .= '<figcaption class="vcex-image-grid-entry-title">';
									$output .= '<'. $title_tag . $title_style .' class="entry-title">';
										$output .= $post_title_display;
									$output .= '</'. $title_tag .'>';
								$output .= '</figcaption>';

							endif;
						
						endif;

					$output .= '</figure>';

				$output .= '</div>';
				
				// Clear counter
				if ( $count == $columns ) {
					$count = 0;
				}
			
			// End while loop
			endwhile;

		$output .= '</div>';

		// Close CSS div
		if ( $css ) {
			$output .= '</div>';
		}

		// Paginate Posts
		if ( '-1' != $posts_per_page && 'true' == $pagination ) :

			$output .= wpex_pagination( $wpex_query, false );
		
		endif;

		endif; // End Query

		// Reset the post data to prevent conflicts with WP globals
		wp_reset_postdata();

		echo $output;

// End image check
endif;