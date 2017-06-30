<?php
/**
 * Visual Composer Image Gallery
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.1
 */

if ( ! class_exists( 'VCEX_Image_Gallery_Slider' ) ) {

	class VCEX_Image_Gallery_Slider {

		/**
		 * Main constructor
		 *
		 * @since 3.5.0
		 */
		public function __construct() {
			
			// Add shortcode
			add_shortcode( 'vcex_image_galleryslider', array( 'VCEX_Image_Gallery_Slider', 'output' ) );

			// Map to VC
			if ( function_exists( 'vc_lean_map' ) ) {
				vc_lean_map( 'vcex_image_galleryslider', array( 'VCEX_Image_Gallery_Slider', 'map' ) );
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.0
		 */
		public static function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_image_galleryslider.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.0
		 */
		public static function map() {
			return array(
				'name' => __( 'Gallery Slider', 'total' ),
				'description' => __( 'Image slider with thumbnail navigation', 'total' ),
				'base' => 'vcex_image_galleryslider',
				'category' => wpex_get_theme_branding(),
				'icon' => 'vcex-image-gallery-slider vcex-icon fa fa-picture-o',
				'params' => array(
					// Gallery
					array(
						'type' => 'vcex_attach_images',
						'heading' => __( 'Attach Images', 'total' ),
						'param_name' => 'image_ids',
						'description' => __( 'You can display captions by giving your images a caption and you can also display videos by adding an image that has a Video URL defined for it.', 'total' ),
						'group' => __( 'Gallery', 'total' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'admin_label' => true,
						'heading' => __( 'Post Gallery', 'total' ),
						'param_name' => 'post_gallery',
						'group' => __( 'Gallery', 'total' ),
						'description' => __( 'Enable to display images from the current post "Image Gallery".', 'total' ),
					),
					// General
					array(
						'type' => 'vcex_visibility',
						'heading' => __( 'Visibility', 'total' ),
						'param_name' => 'visibility',
					),
					vcex_vc_map_add_css_animation(),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Lazy Load', 'total' ),
						'param_name' => 'lazy_load',
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Randomize', 'total' ),
						'param_name' => 'randomize',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Unique Id', 'total' ),
						'param_name' => 'unique_id',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Extra class name', 'total' ),
						'param_name' => 'classes',
						'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Animation', 'total' ),
						'param_name' => 'animation',
						'value' => array(
							__( 'Slide', 'total' ) => 'slide',
							__( 'Fade', 'total' ) => 'fade_slides',
						),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Loop', 'total' ),
						'param_name' => 'loop',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Auto Height Animation', 'total' ),
						'std' => '500',
						'param_name' => 'height_animation',
						'description' => __( 'You can enter "0.0" to disable the animation completely.', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Animation Speed', 'total' ),
						'param_name' => 'animation_speed',
						'std' => '600',
						'description' => __( 'Enter a value in milliseconds.', 'total' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Auto Play', 'total' ),
						'param_name' => 'slideshow',
						'description' => __( 'Enable automatic slideshow? Disabled in front-end composer to prevent page "jumping".', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Auto Play Delay', 'total' ),
						'param_name' => 'slideshow_speed',
						'std' => '5000',
						'description' => __( 'Enter a value in milliseconds.', 'total' ),
						'dependency' => array( 'element' => 'slideshow', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Arrows', 'total' ),
						'param_name' => 'direction_nav',
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Arrows on Hover', 'total' ),
						'param_name' => 'direction_nav_hover',
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Dot Navigation', 'total' ),
						'param_name' => 'control_nav',
					),
					// Image
					array(
						'type' => 'dropdown',
						'heading' => __( 'Image Size', 'total' ),
						'param_name' => 'img_size',
						'std' => 'wpex_custom',
						'value' => vcex_image_sizes(),
						'group' => __( 'Image', 'total' ),
					),
					array(
						'type' => 'vcex_image_crop_locations',
						'heading' => __( 'Image Crop Location', 'total' ),
						'param_name' => 'img_crop',
						'std' => 'center-center',
						'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
						'group' => __( 'Image', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Image Crop Width', 'total' ),
						'param_name' => 'img_width',
						'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
						'group' => __( 'Image', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Image Crop Height', 'total' ),
						'param_name' => 'img_height',
						'description' => __( 'Enter a height in pixels. Leave empty to disable vertical cropping and keep image proportions.', 'total' ),
						'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
						'group' => __( 'Image', 'total' )
					),
					// Thumbnails
					array(
						'type' => 'dropdown',
						'heading' => __( 'Columns', 'total' ),
						'param_name' => 'thumbnails_columns',
						'std' => '',
						'description' => __( 'This specific slider displays the thumbnails in "rows" if you want your thumbnails displayed under the slider as a carousel, use the "Image Slider" module instead.', 'total' ),
						'group' => __( 'Thumbnails', 'total' ),
						'value' => array(
							__( 'Default', 'total' ) => '',
							'6' => '6',
							'5' => '5',
							'4' => '4',
							'3' => '3',
							'2' => '2',
						),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Image Crop Width', 'total' ),
						'param_name' => 'img_thumb_width',
						'value' => '',
						'description' => __( 'Enter a width in pixels for your thumbnail image width. This won\'t increase the grid, its only used so you can alter the cropping to your preferred proportions.', 'total' ),
						'group' => __( 'Thumbnails', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Image Crop Height', 'total' ),
						'param_name' => 'img_thumb_height',
						'value' => '',
						'description' => __( 'Enter a width in pixels for your thumbnail image height.', 'total' ),
						'group' => __( 'Thumbnails', 'total' ),
					),
					// Caption
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Enable', 'total' ),
						'param_name' => 'caption',
						'group' => __( 'Caption', 'total' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Based On', 'total' ),
						'param_name' => 'caption_type',
						'std' => 'caption',
						'value' => array(
							__( 'Title', 'total' ) => 'title',
							__( 'Caption', 'total' ) => 'caption',
							__( 'Description', 'total' ) => 'description',
							__( 'Alt', 'total' ) => 'alt',
						),
						'dependency' => array( 'element' => 'caption', 'value' => 'true' ),
						'group' => __( 'Caption', 'total' ),
					),
					array(
						'type' => 'vcex_visibility',
						'heading' => __( 'Visibility', 'total' ),
						'param_name' => 'caption_visibility',
						'dependency' => array( 'element' => 'caption', 'value' => 'true' ),
						'group' => __( 'Caption', 'total' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Style', 'total' ),
						'param_name' => 'caption_style',
						'value' => array(
							__( 'Black', 'total' ) => 'black',
							__( 'White', 'total' ) => 'white',
						),
						'dependency' => array( 'element' => 'caption', 'value' => 'true' ),
						'group' => __( 'Caption', 'total' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Rounded?', 'total' ),
						'param_name' => 'caption_rounded',
						'dependency' => array( 'element' => 'caption', 'value' => 'true' ),
						'group' => __( 'Caption', 'total' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Position', 'total' ),
						'param_name' => 'caption_position',
						'std' => 'bottomCenter',
						'value' => array(
							__( 'Bottom Center', 'total' ) => 'bottomCenter',
							__( 'Bottom Left', 'total' ) => 'bottomLeft',
							__( 'Bottom Right', 'total' ) => 'bottomRight',
							__( 'Top Center', 'total' ) => 'topCenter',
							__( 'Top Left', 'total' ) => 'topLeft',
							__( 'Top Right', 'total' ) => 'topRight',
							__( 'Center Center', 'total' ) => 'centerCenter',
							__( 'Center Left', 'total' ) => 'centerLeft',
							__( 'Center Right', 'total' ) => 'centerRight',
						),
						'dependency' => array( 'element' => 'caption', 'value' => 'true' ),
						'group' => __( 'Caption', 'total' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Show Transition', 'total' ),
						'param_name' => 'caption_show_transition',
						'std' => 'up',
						'value' => array(
							__( 'None', 'total' ) => 'false',
							__( 'Up', 'total' ) => 'up',
							__( 'Down', 'total' ) => 'down',
							__( 'Left', 'total' ) => 'left',
							__( 'Right', 'total' ) => 'right',
						),
						'dependency' => array( 'element' => 'caption', 'value' => 'true' ),
						'group' => __( 'Caption', 'total' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Hide Transition', 'total' ),
						'param_name' => 'caption_hide_transition',
						'std' => 'down',
						'value' => array(
							__( 'None', 'total' ) => 'false',
							__( 'Up', 'total' ) => 'up',
							__( 'Down', 'total' ) => 'down',
							__( 'Left', 'total' ) => 'left',
							__( 'Right', 'total' ) => 'right',
						),
						'dependency' => array( 'element' => 'caption', 'value' => 'true' ),
						'group' => __( 'Caption', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Width', 'total' ),
						'param_name' => 'caption_width',
						'dependency' => array( 'element' => 'caption', 'value' => 'true' ),
						'value' => '100%',
						'description' => __( 'Enter a pixel or percentage value. You can also enter "auto" for content dependent width.', 'total' ),
						'group' => __( 'Caption', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Font-Size', 'total' ),
						'param_name' => 'caption_font_size',
						'dependency' => array( 'element' => 'caption', 'value' => 'true' ),
						'group' => __( 'Caption', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Padding', 'total' ),
						'param_name' => 'caption_padding',
						'dependency' => array( 'element' => 'caption', 'value' => 'true' ),
						'description' => __( 'Please use the following format: top right bottom left.', 'total' ),
						'group' => __( 'Caption', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Horizontal Offset', 'total' ),
						'param_name' => 'caption_horizontal',
						'dependency' => array( 'element' => 'caption', 'value' => 'true' ),
						'group' => __( 'Caption', 'total' ),
						'description' => __( 'Please enter a px value.', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Vertical Offset', 'total' ),
						'param_name' => 'caption_vertical',
						'dependency' => array( 'element' => 'caption', 'value' => 'true' ),
						'group' => __( 'Caption', 'total' ),
						'description' => __( 'Please enter a px value.', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Delay', 'total' ),
						'param_name' => 'caption_delay',
						'std' => '500',
						'dependency' => array( 'element' => 'caption', 'value' => 'true' ),
						'group' => __( 'Caption', 'total' ),
						'description' => __( 'Enter a value in milliseconds.', 'total' ),
					),
					// Links
					array(
						'type' => 'dropdown',
						'heading' => __( 'Image Link', 'total' ),
						'param_name' => 'thumbnail_link',
						'value' => array(
							__( 'None', 'total' ) => 'none',
							__( 'Lightbox', 'total' ) => 'lightbox',
							__( 'Custom Links', 'total' ) => 'custom_link',
						),
						'group' => __( 'Links', 'total' ),
					),
					array(
						'type' => 'exploded_textarea',
						'heading' => __( 'Custom links', 'total' ),
						'param_name' => 'custom_links',
						'description' => __( 'Enter links for each slide here. Divide links with linebreaks (Enter). For images without a link enter a # symbol.', 'total' ),
						'dependency' => array( 'element' => 'thumbnail_link', 'value' => array( 'custom_link' ) ),
						'group' => __( 'Links', 'total' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __('Custom link target', 'total' ),
						'param_name' => 'custom_links_target',
						'dependency' => array( 'element' => 'thumbnail_link', 'value' => 'custom_link' ),
						'value' => array(
							__( 'Same window', 'total' ) => 'self',
							__( 'New window', 'total' ) => '_blank'
						),
						'group' => __( 'Links', 'total' ),
					),
					array(
						'type' => 'vcex_lightbox_skins',
						'heading' => __( 'Lightbox Skin', 'total' ),
						'param_name' => 'lightbox_skin',
						'group' => __( 'Links', 'total' ),
						'dependency' => array( 'element' => 'thumbnail_link', 'value' => 'lightbox' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Lightbox Thumbnails Placement', 'total' ),
						'param_name' => 'lightbox_path',
						'value' => array(
							__( 'Horizontal', 'total' ) => 'horizontal',
							__( 'Vertical', 'total' ) => 'vertical',
						),
						'group' => __( 'Links', 'total' ),
						'dependency' => array( 'element' => 'thumbnail_link', 'value' => 'lightbox' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Lightbox Title', 'total' ),
						'param_name' => 'lightbox_title',
						'value' => array(
							__( 'None', 'total' ) => 'none',
							__( 'Alt', 'total' ) => 'alt',
							__( 'Title', 'total' ) => 'title',
						),
						'group' => __( 'Links', 'total' ),
						'dependency' => array( 'element' => 'thumbnail_link', 'value' => 'lightbox' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'enable',
						'vcex' => array(
							'on' => 'enable',
							'off' => 'false',
						),
						'heading' => __( 'Lightbox Caption', 'total' ),
						'param_name' => 'lightbox_caption',
						'group' => __( 'Links', 'total' ),
						'dependency' => array( 'element' => 'thumbnail_link', 'value' => 'lightbox' ),
					),
					// CSS
					array(
						'type' => 'css_editor',
						'heading' => __( 'CSS', 'total' ),
						'param_name' => 'css',
						'group' => __( 'Design', 'total' ),
					),

				)
			);
		}

	}
}
new VCEX_Image_Gallery_Slider;