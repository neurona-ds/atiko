<?php
/**
 * Visual Composer Single Image Configuration
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start Class
if ( ! class_exists( 'VCEX_Single_Image_Config' ) ) {
	
	class VCEX_Single_Image_Config {

		/**
		 * Main constructor
		 *
		 * @since 2.0.0
		 */
		public function __construct() {
			
			// Tweak default params
			add_action( 'wpex_vc_modify_params', array( 'VCEX_Single_Image_Config', 'update_params' ) );
			
			// Add custom total params
			add_filter( 'wpex_vc_add_params', array( 'VCEX_Single_Image_Config', 'add_params') );
			
			// Tweak values on edit
			add_filter( 'vc_edit_form_fields_attributes_vc_single_image', array( 'VCEX_Single_Image_Config', 'edit_form_fields') );
			
			// Alter classes on front-end
			add_filter( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, array( 'VCEX_Single_Image_Config', 'shortcode_classes' ), 99, 3 );
			
			// Alter attributes before display on front-end
			add_filter( 'shortcode_atts_vc_single_image', array( 'VCEX_Single_Image_Config', 'parse_attributes' ), 99 );

			// Custom output
			add_filter( 'vc_shortcode_output', array( 'VCEX_Single_Image_Config', 'custom_output' ), 10, 3 );

		}

		/**
		 * Update default params
		 *
		 * @since 3.0.0
		 */
		public static function update_params( $params ) {

			$s_link = __( 'Link', 'total' );

			$params['vc_single_image'] = array(
				'el_id' => array(
					'weight' => 98
				),
				'el_class' => array(
					'weight' => 98
				),
				'css_animation' => array(
					'weight' => 98
				),
				'css' => array(
					'weight' => -1
				),
				'img_size' => array(
					'value' => 'full'
				),
				'img_link_target' => array(
					'value' => array(
						__( 'Local', 'total' ) => 'local',
					),
					'dependency' => array(
						'element' => 'onclick',
						'value' => array(
							'custom_link',
						),
					),
					'group' => $s_link,
				),
				'onclick' => array(
					'group' => $s_link,
				),
				'link' => array(
					'group' => $s_link,
				),
			);

			return $params;

		}

		/**
		 * Adds new params for the VC Single_Images
		 *
		 * @since 2.0.0
		 */
		public static function add_params( $params ) {

			$params['vc_single_image'] = array(

				array(
					'type'=> 'vcex_visibility',
					'heading' => __( 'Visibility', 'total' ),
					'param_name' => 'visibility',
					'weight' => 99,
				),

				array(
					'type' => 'dropdown',
					'heading' => __( 'Image alignment', 'total' ),
					'param_name' => 'alignment',
					'value' => array(
						__( 'Default', 'total' ) => '',
						__( 'Left', 'total' ) => 'left',
						__( 'Right', 'total' ) => 'right',
						__( 'Center', 'total' ) => 'center',
					),
					'description' => __( 'Select image alignment.', 'total' )
				),

				array(
					'type' => 'textfield',
					'heading' => __( 'Over Image Caption', 'total' ),
					'param_name' => 'img_caption',
					'description' => __( 'Use this field to add a caption to any single image with a link.', 'total' ),
				),

				array(
					'type' => 'vcex_image_filters',
					'heading' => __( 'Image Filter', 'total' ),
					'param_name' => 'img_filter',
					'description' => __( 'Select an image filter style.', 'total' ),
				),

				array(
					'type' => 'vcex_image_hovers',
					'heading' => __( 'Image Hover', 'total' ),
					'param_name' => 'img_hover',
					'description' => __( 'Select your preferred image hover effect. Please note this will only work if the image links to a URL or a large version of itself. Please note these effects may not work in all browsers.', 'total' ),
				),


				array(
					'type' => 'textfield',
					'heading' => __( 'Video, SWF, Flash, URL Lightbox', 'total' ),
					'param_name' => 'lightbox_video',
					'description' => __( 'Enter the URL to a video, SWF file, flash file or a website URL to open in lightbox.', 'total' ),
					'group' => __( 'Custom Lightbox', 'total' ),
				),


				array(
					'type' => 'dropdown',
					'heading' => __( 'Lightbox Type', 'total' ),
					'param_name' => 'lightbox_iframe_type',
					'value' => array(
						__( 'Auto Detect - Slow', 'total' ) => '', // @todo deprecate this or make it last in options
						__( 'URL', 'total' ) => 'url',
						__( 'Youtube, Vimeo, Embed or Iframe', 'total' ) => 'video_embed',
						__( 'HTML5', 'total' ) => 'html5',
						__( 'Quicktime', 'total' ) => 'quicktime',
					),
					'description' => __( 'Auto detect depends on the iLightbox API, so by choosing your type it speeds things up and you also allows for HTTPS support.', 'total' ),
					'group' => __( 'Custom Lightbox', 'total' ),
					'dependency' => array( 'element' => 'lightbox_video', 'not_empty' => true ),
				),

				array(
					'type' => 'vcex_ofswitch',
					'heading' => __( 'Video Overlay Icon?', 'total' ),
					'param_name' => 'lightbox_video_overlay_icon',
					'group' => __( 'Custom Lightbox', 'total' ),
					'std' => 'false',
					'dependency' => array( 'element' => 'lightbox_iframe_type', 'value' => 'video_embed' ),
				),

				array(
					'type' => 'textfield',
					'heading' => __( 'HTML5 Webm URL', 'total' ),
					'param_name' => 'lightbox_video_html5_webm',
					'description' => __( 'Enter the URL to a video, SWF file, flash file or a website URL to open in lightbox.', 'total' ),
					'group' => __( 'Custom Lightbox', 'total' ),
					'dependency' => array( 'element' => 'lightbox_iframe_type', 'value' => 'html5' ),
				),

				array(
					'type' => 'textfield',
					'heading' => __( 'Lightbox Dimensions', 'total' ),
					'param_name' => 'lightbox_dimensions',
					'description' => __( 'Enter a custom width and height for your lightbox pop-up window. Use format widthxheight. Example: 900x600.', 'total' ),
					'group' => __( 'Custom Lightbox', 'total' ),
					'dependency' => array( 'element' => 'lightbox_video', 'not_empty' => true ),
				),

				array(
					'type' => 'attach_image',
					'admin_label' => false,
					'heading' => __( 'Custom Image Lightbox', 'total' ),
					'param_name' => 'lightbox_custom_img',
					'description' => __( 'Select a custom image to open in lightbox format', 'total' ),
					'group' => __( 'Custom Lightbox', 'total' ),
				),

				array(
					'type' => 'attach_images',
					'admin_label' => false,
					'heading' => __( 'Gallery Lightbox', 'total' ),
					'param_name' => 'lightbox_gallery',
					'description' => __( 'Select images to create a lightbox Gallery.', 'total' ),
					'group' => __( 'Custom Lightbox', 'total' ),
				),

				array(
					'type' => 'hidden',
					'param_name' => 'rounded_image',
				)

			);

			return $params;

		}

		/**
		 * Alter fields on edit
		 *
		 * @since 2.0.0
		 */
		public static function edit_form_fields( $atts ) {
			if ( ! empty( $atts['rounded_image'] )
				&& 'yes' == $atts['rounded_image']
				&& empty( $atts['style'] )
			) {
				$atts['style'] = 'vc_box_circle';
				unset( $atts['rounded_image'] );
			}
			if ( ! empty( $atts['link'] ) && empty( $atts['onclick'] ) ) {
				$atts['onclick'] = 'custom_link';
			}
			return $atts;
		}

		/**
		 * Parse attributes on front-end
		 *
		 * @since 4.0
		 */
		public static function parse_attributes( $atts ) {

			// Custom lightbox
			if ( ! empty( $atts['lightbox_gallery'] ) ) {
				$atts['link'] = '#';
				$atts['onclick'] = 'custom_link';
			} elseif ( ! empty( $atts['lightbox_custom_img'] ) ) {
				if ( $lb_image = wpex_get_lightbox_image( $atts['lightbox_custom_img'] ) ) {
					$atts['link'] = $lb_image;
					$atts['onclick'] = 'wpex_lightbox';
				}
			} elseif ( ! empty( $atts['lightbox_video'] ) ) {

				$atts['lightbox_video'] = set_url_scheme( esc_url( $atts['lightbox_video'] ) );
				$atts['onclick'] = 'wpex_lightbox'; // Since we use total functions

				// Check if perhaps the iFrame is a video and if so set type to video_embed
				if ( strpos( $atts['lightbox_video'], 'youtube' ) !== false
					|| strpos( $atts['lightbox_video'], 'vimeo' ) !== false
				) {
					$atts['lightbox_iframe_type'] = 'video_embed';
				}

				if ( isset( $atts['lightbox_iframe_type'] ) && 'video_embed' == $atts['lightbox_iframe_type'] ) {
					$embed_url = wpex_sanitize_data( $atts['lightbox_video'], 'embed_url' );
					$atts['link'] = $embed_url ? $embed_url : $atts['lightbox_video'];
				} else {
					$atts['link'] = $atts['lightbox_video'];
				}

			} elseif ( ! empty( $atts['onclick'] )
				&& ! empty( $atts['image'] )
				&& 'img_link_large' == $atts['onclick']
			) {
				$atts['onclick'] = 'wpex_lightbox'; // Since we use total functions
				$atts['link'] = wpex_get_lightbox_image( $atts['image'] );
			}

			// Local scroll
			if ( isset( $atts['img_link_target'] ) && 'local' == $atts['img_link_target'] ) {
				$atts['img_link_target'] = '_self';
			}

			// Return attributes
			return $atts;

		}

		/**
		 * Tweak shortcode classes
		 *
		 * @since 4.0
		 */
		public static function shortcode_classes( $class_string, $tag, $atts ) {

			if ( 'vc_single_image' != $tag ) {
				return $class_string;
			}

			if ( ! empty( $atts['visibility'] ) ) {
				$class_string .= ' '. $atts['visibility'];
			}

			if ( ! empty( $atts['img_filter'] ) ) {
				$class_string .= ' '. wpex_image_filter_class( $atts['img_filter'] );
			}

			if ( ( ! empty( $atts['onclick'] ) && 'wpex_lightbox' == $atts['onclick'] ) ) {
				$class_string .= ' wpex-lightbox'; // MUST BE LAST FOR ADDING DATA ATTRIBUTES !!!
			}

			return $class_string;

		}

		/**
		 * Add custom HTML to ouput
		 *
		 * @since 4.0
		 */
		public static function custom_output( $output, $obj, $atts ) {

			// Check needed settings
			if ( ! $obj->settings( 'base' ) ) {
				return $output;
			}

			// Only tweaks neeed for single image
			if ( 'vc_single_image' != $obj->settings( 'base' ) ) {
				return $output;
			}

			// Check if lightbox CSS should enqueue
			if ( ( ! empty( $atts['onclick'] ) && 'img_link_large' == $atts['onclick'] )
				|| ! empty( $atts['lightbox_gallery'] )
				|| ! empty( $atts['lightbox_custom_img'] )
				|| ! empty( $atts['lightbox_video'] )
			) {
				wpex_enqueue_ilightbox_skin();
			}

			// Add over image caption
			if ( ! empty( $atts['img_caption'] ) ) {
				$caption = '<span class="wpb_single_image_caption">' . wp_kses_post( $atts['img_caption'] ) . '</span>';
				$output  = str_replace( '</figure>', $caption . '</figure>', $output );
			}

			// Add video overlay icon
			if ( isset( $atts['lightbox_video_overlay_icon'] )
				&& 'true' == $atts['lightbox_video_overlay_icon']
			) {
				$icon   = '<div class="overlay-icon"><span>&#9658;</span></div>';
				$output = str_replace( '</a>', $icon . '</a>', $output );
			}

			// Add hover classes
			if ( ! empty( $atts['img_hover'] ) ) {
				$class  = wpex_image_hover_classes( $atts['img_hover'] );
				$output = str_replace( 'vc_single_image-wrapper', 'vc_single_image-wrapper ' . $class, $output );
			}

			// Add local scroll classes
			if ( isset( $atts['img_link_target'] ) && 'local' == $atts['img_link_target'] ) {
				$output = str_replace( 'vc_single_image-wrapper', 'vc_single_image-wrapper local-scroll-link', $output );
			}

			// Lightbox gallery
			if ( ! empty( $atts['lightbox_gallery'] ) ) {
				$gallery_ids = explode( ',', $atts['lightbox_gallery'] );
				if ( $gallery_ids && is_array( $gallery_ids ) ) {
					$lb_images = '';
					foreach ( $gallery_ids as $id ) {
						$lb_images .= wpex_get_lightbox_image( $id ) . ',';
					}
					if ( $lb_images ) {
						$lb_images = rtrim( $lb_images, ',' );
						$output = str_replace( '<a', '<a data-gallery="' . $lb_images . '"', $output );
						$output = str_replace( 'vc_single_image-wrapper', 'vc_single_image-wrapper wpex-lightbox-gallery', $output );
					}
				}
			}

			// Add Lightbox data attributes
			if ( ! empty( $atts['lightbox_video'] )
				&& empty( $atts['lightbox_custom_img'] )
				&& empty( $atts['lightbox_gallery'] )
			) {

				// Check if perhaps the iFrame is a video and if so set type to video_embed
				if ( strpos( $atts['lightbox_video'], 'youtube' ) !== false
					|| strpos( $atts['lightbox_video'], 'vimeo' ) !== false
				) {
					$atts['lightbox_iframe_type'] = 'video_embed';
				}

				$lb_data = array();
				$lb_dimensions  = isset( $atts['lightbox_dimensions'] ) ? $atts['lightbox_dimensions'] : '';
				$lb_dimensions  = vcex_parse_lightbox_dims( $lb_dimensions );
				$lb_iframe_type = isset( $atts['lightbox_iframe_type'] ) ? $atts['lightbox_iframe_type'] : '';

				if ( 'video_embed' == $lb_iframe_type ) {
					$lb_data['data-type']        = 'iframe';
					$lb_data['data-show_title']  = 'false';
					if ( $lb_dimensions ) {
						$lb_data['data-options'] = $lb_dimensions;
					} else {
						$lb_data['data-options'] = 'iframeType:\'video\'';
					}
				} elseif ( 'url' == $lb_iframe_type ) {
					$lb_data['data-type']       = 'iframe';
					$lb_data['data-options']    = $lb_dimensions;
					$lb_data['data-show_title'] ='false';
				} elseif ( 'html5' == $lb_iframe_type ) {
					$poster = '';
					if ( ! empty( $atts['img_id'] ) ) {
						$poster = wp_get_attachment_image_src( $atts['img_id'], 'full' );
						$poster = $poster[0];
					}
					$webem = isset( $atts['lightbox_video_html5_webm'] ) ? $atts['lightbox_video_html5_webm'] : '';
					$lb_data['data-type']    = 'video';
					$lb_data['data-options'] = $lb_dimensions .',html5video: { webm: \''. $webem .'\', poster: \''. $poster .'\' }';
					$lb_data['data-show_title'] ='false';
				} elseif ( 'quicktime' == $lb_iframe_type ) {
					$lb_data['data-type']    = 'video';
					$lb_data['data-options'] = $lb_dimensions;
					$lb_data['data-show_title'] ='false';
				}

				// Auto detect style
				else {

					if ( $lb_dimensions ) {
						$lb_data['data-options'] = $lb_dimensions;
					}

				}

				if ( $lb_data ) {
					$lb_data = wpex_parse_attrs( $lb_data );
					$output = str_replace( '<a', '<a ' . $lb_data . ' ', $output );
				}


			}

			// Add output
			return $output;

		}

	}

}
new VCEX_Single_Image_Config();