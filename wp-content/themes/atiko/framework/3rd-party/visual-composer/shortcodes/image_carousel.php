<?php
/**
 * Visual Composer Image Carousel
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.2
 */

if ( ! class_exists( 'VCEX_Image_Carousel' ) ) {

	class VCEX_Image_Carousel {

		/**
		 * Main constructor
		 *
		 * @since 3.5.0
		 */
		public function __construct() {
			
			// Add shortcode
			add_shortcode( 'vcex_image_carousel', array( 'VCEX_Image_Carousel', 'output' ) );

			// Map to VC
			if ( function_exists( 'vc_lean_map' ) ) {
				vc_lean_map( 'vcex_image_carousel', array( 'VCEX_Image_Carousel', 'map' ) );
			}

			// Admin filters
			if ( is_admin() ) {

				// Move content design elements into new entry CSS field
				add_filter( 'vc_edit_form_fields_attributes_vcex_image_carousel', 'vcex_parse_deprecated_grid_entry_content_css' );
				
				// Set image height to full if crop/width are empty
				add_filter( 'vc_edit_form_fields_attributes_vcex_image_carousel', 'vcex_parse_image_size' );

			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.0
		 */
		public static function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_image_carousel.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.0
		 */
		public static function map() {

			// Strings
			$s_links   = __( 'Links', 'total' );
			$s_image   = __( 'Image', 'total' );
			$s_caption = __( 'Caption', 'total' );

			// Return array
			return array(
				'name' => __( 'Image Carousel', 'total' ),
				'description' => __( 'Image based jQuery carousel', 'total' ),
				'base' => 'vcex_image_carousel',
				'category' => wpex_get_theme_branding(),
				'icon' => 'vcex-image-carousel vcex-icon fa fa-picture-o',
				'params' => array(
					// Gallery
					array(
						'type' => 'vcex_attach_images',
						'heading'  => __( 'Attach Images', 'total' ),
						'param_name' => 'image_ids',
						'group' => __( 'Gallery', 'total' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'admin_label' => true,
						'std' => 'false',
						'heading'  => __( 'Post Gallery', 'total' ),
						'param_name' => 'post_gallery',
						'group' => __( 'Gallery', 'total' ),
						'description' => __( 'Enable to display images from the current post "Image Gallery".', 'total' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading'  => __( 'Randomize Images', 'total' ),
						'param_name' => 'randomize_images',
						'group' => __( 'Gallery', 'total' ),
					),
					// General
					array(
						'type' => 'textfield',
						'heading'  => __( 'Unique Id', 'total' ),
						'param_name' => 'unique_id',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Extra class name', 'total' ),
						'param_name' => 'classes',
						'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total' ),
					),
					vcex_vc_map_add_css_animation(),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Style', 'total' ),
						'param_name' => 'style',
						'value' => array(
							__( 'Default', 'total' ) => 'default',
							__( 'No Margins', 'total' ) => 'no-margins',
						),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Animation Speed', 'total' ),
						'param_name' => 'animation_speed',
						'value' => '150',
						'description' => __( 'Default is 150 milliseconds. Enter 0.0 to disable.', 'total' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Arrows?', 'total' ),
						'param_name' => 'arrows',
					),
					array(
						'type' => 'vcex_carousel_arrow_styles',
						'heading' => __( 'Arrows Style', 'total' ),
						'param_name' => 'arrows_style',
						'dependency' => array( 'element' => 'arrows', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_carousel_arrow_positions',
						'heading' => __( 'Arrows Position', 'total' ),
						'param_name' => 'arrows_position',
						'std' => 'default',
						'dependency' => array( 'element' => 'arrows', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Dots?', 'total' ),
						'param_name' => 'dots',
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Auto Width', 'total' ),
						'param_name' => 'auto_width',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Items To Display', 'total' ),
						'param_name' => 'items',
						'value' => '4',
						'dependency' => array( 'element' => 'auto_width', 'value' => 'false' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Auto Height?', 'total' ),
						'param_name' => 'auto_height',
						'dependency' => array( 'element' => 'items', 'value' => '1' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Tablet: Items To Display', 'total' ),
						'param_name' => 'tablet_items',
						'value' => '3',
						'dependency' => array( 'element' => 'auto_width', 'value' => 'false' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Mobile Landscape: Items To Display', 'total' ),
						'param_name' => 'mobile_landscape_items',
						'value' => '2',
						'dependency' => array( 'element' => 'auto_width', 'value' => 'false' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Mobile Portrait: Items To Display', 'total' ),
						'param_name' => 'mobile_portrait_items',
						'value' => '1',
						'dependency' => array( 'element' => 'auto_width', 'value' => 'false' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Items To Scrollby', 'total' ),
						'param_name' => 'items_scroll',
						'value' => '1',
						'dependency' => array( 'element' => 'auto_width', 'value' => 'false' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Margin Between Items', 'total' ),
						'param_name' => 'items_margin',
						'value' => '15',
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Auto Play', 'total' ),
						'param_name' => 'auto_play',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Timeout Duration in milliseconds', 'total' ),
						'param_name' => 'timeout_duration',
						'value' => '5000',
						'dependency' => array( 'element' => 'auto_play', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Infinite Loop', 'total' ),
						'param_name' => 'infinite_loop',
						'std' => 'true',
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Center Item', 'total' ),
						'param_name' => 'center',
					),
					// Image
					array(
						'type' => 'dropdown',
						'heading' => __( 'Image Size', 'total' ),
						'param_name' => 'img_size',
						'std' => 'wpex_custom',
						'value' => vcex_image_sizes(),
						'group' => $s_image,
					),
					array(
						'type' => 'vcex_image_crop_locations',
						'heading' => __( 'Image Crop Location', 'total' ),
						'param_name' => 'img_crop',
						'std' => 'center-center',
						'group' => $s_image,
						'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Image Crop Width', 'total' ),
						'param_name' => 'img_width',
						'group' => $s_image,
						'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Image Crop Height', 'total' ),
						'param_name' => 'img_height',
						'description' => __( 'Enter a height in pixels. Leave empty to disable vertical cropping and keep image proportions.', 'total' ),
						'group' => $s_image,
						'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'no',
						'vcex' => array(
							'on' => 'yes',
							'off' => 'no',
						),
						'heading' => __( 'Rounded Image?', 'total' ),
						'param_name' => 'rounded_image',
						'group' => $s_image,
					),
					array(
						'type' => 'vcex_overlay',
						'heading' => __( 'Image Overlay', 'total' ),
						'param_name' => 'overlay_style',
						'group' => $s_image,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Overlay Button Text', 'total' ),
						'param_name' => 'overlay_button_text',
						'group' => $s_image,
						'dependency' => array( 'element' => 'overlay_style', 'value' => 'hover-button' ),
					),
					array(
						'type' => 'vcex_image_hovers',
						'heading' => __( 'CSS3 Image Hover', 'total' ),
						'param_name' => 'img_hover_style',
						'group' => $s_image,
					),
					array(
						'type' => 'vcex_image_filters',
						'heading' => __( 'Image Filter', 'total' ),
						'param_name' => 'img_filter',
						'group' => $s_image,
					),
					// Links
					array(
						'type' => 'dropdown',
						'heading' => __( 'Image Link', 'total' ),
						'param_name' => 'thumbnail_link',
						'std' => 'none',
						'value' => array(
							__( 'None', 'total' ) => 'none',
							__( 'Lightbox', 'total' )  => 'lightbox',
							__( 'Custom Links', 'total' ) => 'custom_link',
						),
						'group' => $s_links,
					),
					array(
						'type' => 'exploded_textarea',
						'heading'  => __( 'Custom links', 'total' ),
						'param_name' => 'custom_links',
						'description' => __( 'Enter links for each slide here. Divide links with linebreaks (Enter). For images without a link enter a # symbol. And don\'t forget to include the http:// at the front.', 'total'),
						'group' => $s_links,
						'dependency' => array( 'element' => 'thumbnail_link', 'value' => 'custom_link' ),
					),
					array(
						'type' => 'dropdown',
						'heading'  => __( 'Custom link target', 'total' ),
						'param_name' => 'custom_links_target',
						'description' => __( 'Select where to open custom links.', 'total'),
						'group' => $s_links,
						'value' => array(
							__( 'Same window', 'total' ) => 'self',
							__( 'New window', 'total' ) => '_blank'
						),
						'dependency' => array( 'element' => 'thumbnail_link', 'value' => 'custom_link' ),
					),
					array(
						'type' => 'vcex_lightbox_skins',
						'heading' => __( 'Lightbox Skin', 'total' ),
						'param_name' => 'lightbox_skin',
						'group' => $s_links,
						'dependency' => array( 'element' => 'thumbnail_link', 'value' => 'lightbox' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Lightbox Thumbnails Placement', 'total' ),
						'param_name' => 'lightbox_path',
						'value' => array(
							__( 'Horizontal', 'total' ) => '',
							__( 'Vertical', 'total' ) => 'vertical',
							__( 'Disabled', 'total' ) => 'disabled',
						),
						'group' => $s_links,
						'dependency' => array( 'element' => 'thumbnail_link', 'value' => 'lightbox' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Lightbox Title', 'total' ),
						'param_name' => 'lightbox_title',
						'value' => array(
							__( 'Alt', 'total' ) => 'alt',
							__( 'Title', 'total' ) => 'title',
							__( 'None', 'total' ) => 'false',
						),
						'group' => $s_links,
						'dependency' => array( 'element' => 'thumbnail_link', 'value' => 'lightbox' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Lightbox Caption', 'total' ),
						'param_name' => 'lightbox_caption',
						'group' => $s_links,
						'dependency' => array( 'element' => 'thumbnail_link', 'value' => 'lightbox' ),
					),
					// Title
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'no',
						'vcex' => array(
							'on' => 'yes',
							'off' => 'no',
						),
						'heading' => __( 'Title', 'total' ),
						'param_name' => 'title',
						'group' => __( 'Title', 'total' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Title Based On Image', 'total' ),
						'param_name' => 'title_type',
						'value' => array(
							__( 'Default', 'total' ) => 'default',
							__( 'Title', 'total' ) => 'title',
							__( 'Alt', 'total' )  => 'alt',
						),
						'group' => __( 'Title', 'total' ),
						'dependency' => array( 'element' => 'title', 'value' => 'yes' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'content_heading_color',
						'group' => __( 'Title', 'total' ),
						'dependency' => array( 'element' => 'title', 'value' => 'yes' ),
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'content_heading_weight',
						'group' => __( 'Title', 'total' ),
						'dependency' => array( 'element' => 'title', 'value' => 'yes' ),
					),
					array(
						'type' => 'vcex_text_transforms',
						'heading' => __( 'Text Transform', 'total' ),
						'param_name' => 'content_heading_transform',
						'group' => __( 'Title', 'total' ),
						'dependency' => array( 'element' => 'title', 'value' => 'yes' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'content_heading_size',
						'dependency' => array( 'element' => 'title', 'value' => 'yes' ),
						'group' => __( 'Title', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Margin', 'total' ),
						'param_name' => 'content_heading_margin',
						'description' => __( 'Please use the following format: top right bottom left.', 'total' ),
						'dependency' => array( 'element' => 'title', 'value' => 'yes' ),
					),
					// Caption
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'no',
						'vcex' => array(
							'on' => 'yes',
							'off' => 'no',
						),
						'heading' => __( 'Display Caption', 'total' ),
						'param_name' => 'caption',
						'group' => $s_caption,
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'content_color',
						'group' => $s_caption,
						'dependency' => array( 'element' => 'caption', 'value' => 'yes' ),
					),
					array(
						'type' => 'textfield',
						'heading'  => __( 'Font Size', 'total' ),
						'param_name' => 'content_font_size',
						'group' => $s_caption,
						'dependency' => array( 'element' => 'caption', 'value' => 'yes' ),
					),
					// Design
					array(
						'type' => 'css_editor',
						'heading' => __( 'Content CSS', 'total' ),
						'param_name' => 'content_css',
						'group' => __( 'Content CSS', 'total' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Content Alignment', 'total' ),
						'param_name' => 'content_alignment',
						'group' => __( 'Content CSS', 'total' ),
						'value' => array(
							__( 'Default', 'total' ) => '',
							__( 'Left', 'total' ) => 'left',
							__( 'Right', 'total' ) => 'right',
							__( 'Center', 'total') => 'center',
						),
					),
					// Entry CSS
					array(
						'type' => 'css_editor',
						'heading' => __( 'Entry CSS', 'total' ),
						'param_name' => 'entry_css',
						'group' => __( 'Entry CSS', 'total' ),
					),
				),
			);
		}

	}
}
new VCEX_Image_Carousel;