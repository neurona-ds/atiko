<?php
/**
 * Create custom gallery output for the WP gallery shortcode
 *
 * @package Total WordPress theme
 * @subpackage Framework
 * @version 4.1
 */

// Render the class
if ( ! class_exists( 'WPEX_Custom_WP_Gallery' ) ) {

	class WPEX_Custom_WP_Gallery {

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			add_filter( 'wpex_image_sizes', array( 'WPEX_Custom_WP_Gallery', 'add_image_sizes' ), 999 );
			add_filter( 'post_gallery', array( 'WPEX_Custom_WP_Gallery', 'output' ), 10, 2 );
		}

		/**
		 * Adds image sizes for your galleries to the image sizes panel
		 *
		 * @since 2.0.0
		 */
		public static function add_image_sizes( $sizes ) {
			$sizes['gallery'] = array(
				'label'   => esc_html__( 'WordPress Gallery', 'total' ),
				'width'   => 'gallery_image_width',
				'height'  => 'gallery_image_height',
				'crop'    => 'gallery_image_crop',
				'section' =>  'other',
			);
			return $sizes;
		}
		
		/**
		 * Tweaks the default WP Gallery Output
		 *
		 * @since 1.0.0
		 */
	   public static function output( $output, $attr ) {

			// Main Variables
			global $post, $wp_locale, $instance;
			$instance++;
			static $instance = 0;
			$output          = '';

			// Sanitize orderby statement
			if ( isset( $attr['orderby'] ) ) {
				$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
				if ( ! $attr['orderby'] ) {
					unset( $attr['orderby'] );
				}
			}

			// Get shortcode attributes
			extract( shortcode_atts( array(
				'order'      => 'ASC',
				'orderby'    => 'menu_order ID',
				'id'         => $post->ID,
				'columns'    => 3,
				'include'    => '',
				'exclude'    => '',
				'img_height' => '',
				'img_width'  => '',
				'size'       => '',
				'crop'       => '',
			), $attr ) );

			// Get post ID
			$id = intval( $id );

			if ( 'RAND' == $order ) {
				$orderby = 'none';
			}

			if ( ! empty( $include ) ) {
				$include      = preg_replace( '/[^0-9,]+/', '', $include );
				$_attachments = get_posts(
					array(
						'include'        => $include,
						'post_status'    => '',
						'inherit'        => '',
						'post_type'      => 'attachment',
						'post_mime_type' => 'image',
						'order'          => $order,
						'orderby'        => $orderby
					)
				);

			$attachments = array();
				foreach ( $_attachments as $key => $val ) {
					$attachments[$val->ID] = $_attachments[$key];
				}
			} elseif ( ! empty( $exclude ) ) {
				$exclude     = preg_replace( '/[^0-9,]+/', '', $exclude );
				$attachments = get_children( array(
					'post_parent'    => $id,
					'exclude'        => $exclude,
					'post_status'    => 'inherit',
					'post_type'      => 'attachment',
					'post_mime_type' => 'image',
					'order'          => $order,
					'orderby'        => $orderby) );
			} else {
				$attachments = get_children( array(
					'post_parent'    => $id,
					'post_status'    => 'inherit',
					'post_type'      => 'attachment',
					'post_mime_type' => 'image',
					'order'          => $order,
					'orderby'        => $orderby
				) );
			}

			if ( empty( $attachments ) ) {
				return '';
			}

			if ( is_feed() ) {
				$output = "\n";
				$size   = $size ? $size : 'thumbnail';
				foreach ( $attachments as $attachment_id => $attachment )
					$output .= wp_get_attachment_link( $attachment_id, $size, true ) . "\n";
				return $output;
			}

			// Get columns #
			$columns = intval( $columns );

			// Set cropping sizes
			if ( $columns > 1 ) {
				$img_width  = $img_width ? $img_width : wpex_get_mod( 'gallery_image_width' );
				$img_height = $img_height ? $img_height : wpex_get_mod( 'gallery_image_height' );
			}

			// Sanitize Data
			$size = $size ? $size : 'large';
			$size = ( $img_width || $img_height ) ? 'wpex_custom' : $size;
			$crop = $crop ? $crop : 'center-center';

			// Float
			$float = is_rtl() ? 'right' : 'left';

			// Load lightbox skin stylesheet
			wpex_enqueue_ilightbox_skin();


			// Begin output
			$output .= '<div id="gallery-'. esc_attr( $instance ) .'" class="wpex-gallery wpex-row lightbox-group clr">';
				
				// Begin Loop
				$count  = 0;
				foreach ( $attachments as $attachment_id => $attachment ) {

					// Increase counter for clearing floats
					$count++;

					// Attachment Vars
					$attachment_id   = $attachment->ID;
					$attachment_data = wpex_get_attachment_data( $attachment_id );
					$caption         = $attachment_data['caption'];
					$alt             = $attachment_data['alt'];
					$video           = $attachment_data['video'];

					// Sanitize Video URL
					if ( $video ) {
						$video_embed_url = wpex_sanitize_data( $video, 'embed_url' );
						$video           = $video_embed_url ? $video_embed_url : $video;
					}

					// Get lightbox image
					$lightbox_image = wpex_get_lightbox_image( $attachment_id );

					// Set correct lightbox URL
					$lightbox_url = $video ? $video : $lightbox_image;

					// Set correct data values
					if ( $video ) {
						$lightbox_data = ' data-type="iframe" data-options="iframeType:\'video\',thumbnail: \''. $lightbox_image .'\'"';
					} else {
						$lightbox_data = ' data-type="image"';
						if ( $attachment_data['caption'] ) {
							$lightbox_data .= ' data-caption="'. str_replace( '"',"'", $attachment_data['caption'] ) .'"';
						}
					}

					// Add title for lightbox
					if ( wpex_get_mod( 'lightbox_titles', true ) && $alt ) {
						$lightbox_data .= ' data-title="' . esc_attr( $alt ) . '"';
					} else {
						$lightbox_data .= ' data-title="false"';
					}

					// Entry classes
					$entry_classes = array( 'gallery-item' );
					$entry_classes[] = wpex_grid_class( $columns );
					$entry_classes[] = 'nr-col';
					$entry_classes[] = 'col-'. $count;
					$entry_classes = apply_filters( 'wpex_wp_gallery_entry_classes', $entry_classes );
					$entry_classes = implode( ' ', $entry_classes );
			
					// Start Gallery Item
					$output .= '<figure class="'. esc_attr( $entry_classes ) .'">';
					
						// Display image
						$output .= '<a href="'. esc_url( $lightbox_url ) .'" class="wpex-lightbox-group-item"'. $lightbox_data .'>';

							$output .= wpex_get_post_thumbnail( array(
								'attachment' => $attachment_id,
								'size'       => $size,
								'width'      => $img_width,
								'height'     => $img_height,
								'crop'       => $crop,
								'alt'        => $alt,
							) );

						$output .= '</a>';

						// Display Caption
						if ( trim ( $caption ) ) {

							// Front end composer doesn't like the figcaption class
							if ( wpex_vc_is_inline() ) {

								$output .= '<div class="gallery-caption">';

									$output .= wp_kses_post( wptexturize( $caption ) );

								$output .= '</div>';

							} else {

								$output .= '<figcaption class="gallery-caption">';

									$output .= wp_kses_post( wptexturize( $caption ) );

								$output .= '</figcaption>';

							}
						}
						
					// Close gallery item div
					$output .= '</figure>';

					// Reset counter
					if ( $count == $columns ) {

						$count = '0';

					}
					
				}

			// Close gallery div
			$output .= "</div>\n";

			return $output;
		}

	}

	new WPEX_Custom_WP_Gallery;

}