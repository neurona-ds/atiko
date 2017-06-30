<?php
/**
 * Visual Composer Image Grid
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.1
 */

if ( ! class_exists( 'VCEX_Image_Grid' ) ) {

	class VCEX_Image_Grid {

		/**
		 * Main constructor
		 *
		 * @since 3.5.0
		 */
		public function __construct() {
			
			// Add shortcode
			add_shortcode( 'vcex_image_grid', array( 'VCEX_Image_Grid', 'output' ) );

			// Map to VC
			if ( function_exists( 'vc_lean_map' ) ) {
				vc_lean_map( 'vcex_image_grid', array( 'VCEX_Image_Grid', 'map' ) );
			}

			// Admin filters
			if ( is_admin() ) {
				add_filter( 'vc_edit_form_fields_attributes_vcex_image_grid', 'vcex_parse_image_size' );
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.0
		 */
		public static function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_image_grid.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.0
		 */
		public static function map() {

			// Get overlays and remove some that don't make sense
			$overlays = wpex_overlay_styles_array();
			if ( is_array( $overlays ) ) {
				unset( $overlays['thumb-swap'] );
				unset( $overlays['category-tag'] );
				unset( $overlays['category-tag-two'] );
				$overlays = array_flip( $overlays );
			}

			// Return array
			return array(
				'name' => __( 'Image Grid', 'total' ),
				'description' => __( 'Responsive image gallery', 'total' ),
				'base' => 'vcex_image_grid',
				'icon' => 'vcex-image-grid vcex-icon fa fa-picture-o',
				'category' => wpex_get_theme_branding(),
				'params' => array(
					// General
					array(
						'type' => 'vcex_attach_images',
						'heading' => __( 'Attach Images', 'total' ),
						'param_name' => 'image_ids',
						'group' => __( 'Gallery', 'total' ),
						'description' => __( 'Click the plus icon to add images to your gallery. Once images are added they can be drag and dropped for sorting.', 'total' ),
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
					array(
						'type' => 'textfield',
						'heading' => __( 'Unique Id', 'total' ),
						'param_name' => 'unique_id',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Extra class name', 'total' ),
						'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total' ),
						'param_name' => 'classes',
					),
					array(
						'type' => 'vcex_visibility',
						'heading' => __( 'Visibility', 'total' ),
						'param_name' => 'visibility',
					),
					vcex_vc_map_add_css_animation(),
					array(
						'type' => 'vcex_hover_animations',
						'heading' => __( 'Hover Animation', 'total'),
						'param_name' => 'hover_animation',
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Grid Style', 'total' ),
						'param_name' => 'grid_style',
						'value' => array(
							__( 'Fit Rows', 'total' ) => 'default',
							__( 'Masonry', 'total' ) => 'masonry',
							__( 'No Margins', 'total' ) => 'no-margins',
						),
						'edit_field_class' => 'vc_col-sm-3 vc_column clear',
					),
					array(
						'type' => 'vcex_grid_columns',
						'heading' => __( 'Columns', 'total' ),
						'param_name' => 'columns',
						'std' => '4',
						'edit_field_class' => 'vc_col-sm-3 vc_column',
					),
					array(
						'type' => 'vcex_column_gaps',
						'heading' => __( 'Gap', 'total' ),
						'param_name' => 'columns_gap',
						'edit_field_class' => 'vc_col-sm-3 vc_column',
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Responsive', 'total' ),
						'param_name' => 'responsive_columns',
						'value' => array(
							__( 'Yes', 'total' ) => 'true',
							__( 'No', 'total' ) => 'false',
						),
						'edit_field_class' => 'vc_col-sm-3 vc_column',
						'dependency' => array( 'element' => 'columns', 'value' => array( '2', '3', '4', '5', '6', '7', '8', '9', '10' ) ),
					),
					array(
						'type' => 'vcex_grid_columns_responsive',
						'heading' => __( 'Responsive Settings', 'total' ),
						'param_name' => 'columns_responsive_settings',
						'dependency' => array( 'element' => 'responsive_columns', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Randomize Images', 'total' ),
						'param_name' => 'randomize_images',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Images Per Page', 'total' ),
						'param_name' => 'posts_per_page',
						'value' => '-1',
						'description' => __( 'This will enable pagination for your gallery. Enter -1 or leave blank to display all images without pagination.', 'total' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Pagination', 'total' ),
						'param_name' => 'pagination',
					),
					// Links
					array(
						'type' => 'dropdown',
						'heading' => __( 'Image Link', 'total' ),
						'param_name' => 'thumbnail_link',
						'std' => 'lightbox',
						'value' => array(
							__( 'None', 'total' ) => 'none',
							__( 'Lightbox', 'total' ) => 'lightbox',
							__( 'Attachment Page', 'total' ) => 'attachment_page',
							__( 'Custom Links', 'total' ) => 'custom_link',
						),
						'group' => __( 'Links', 'total' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Link Title Attribute', 'total' ),
						'param_name' => 'link_title_tag',
						'group' => __( 'Links', 'total' ),
						'dependency' => array( 'element' => 'thumbnail_link', 'value' => array( 'lightbox', 'attachment_page', 'custom_link' ) ),
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
						'heading' => __( 'Lightbox Title', 'total' ),
						'param_name' => 'lightbox_title',
						'value' => array(
							__( 'Alt', 'total' ) => 'alt',
							__( 'Title', 'total' ) => 'title',
							__( 'None', 'total' ) => 'false',
						),
						'group' => __( 'Links', 'total' ),
						'dependency' => array( 'element' => 'thumbnail_link', 'value' => 'lightbox' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Lightbox Gallery', 'total' ),
						'param_name' => 'lightbox_gallery',
						'group' => __( 'Links', 'total' ),
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
						'group' => __( 'Links', 'total' ),
						'dependency' => array( 'element' => 'lightbox_gallery', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Lightbox Caption', 'total' ),
						'param_name' => 'lightbox_caption',
						'group' => __( 'Links', 'total' ),
						'dependency' => array( 'element' => 'thumbnail_link', 'value' => 'lightbox' ),
					),
					array(
						'type' => 'exploded_textarea',
						'heading' => __( 'Custom links', 'total' ),
						'param_name' => 'custom_links',
						'description' => __( 'Enter links for each slide here. Divide links with linebreaks (Enter). For images without a link enter a # symbol. And don\'t forget to include the http:// at the front.', 'total' ),
						'dependency' => array( 'element' => 'thumbnail_link', 'value' => array( 'custom_link' ) ),
						'group' => __( 'Links', 'total' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Link Target', 'total' ),
						'param_name' => 'custom_links_target',
						'group' => __( 'Links', 'total' ),
						'value' => array(
							__( 'Same window', 'total' ) => '_self',
							__( 'New window', 'total' ) => '_blank'
						),
						'dependency' => array( 'element' => 'thumbnail_link', 'value' => array( 'custom_link' ) ),
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
						'group' => __( 'Image', 'total' ),
						'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Image Crop Width', 'total' ),
						'param_name' => 'img_width',
						'group' => __( 'Image', 'total' ),
						'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Image Crop Height', 'total' ),
						'param_name' => 'img_height',
						'description' => __( 'Enter a height in pixels. Leave empty to disable vertical cropping and keep image proportions.', 'total' ),
						'group' => __( 'Image', 'total' ),
						'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Rounded Image?', 'total' ),
						'param_name' => 'rounded_image',
						'group' => __( 'Image', 'total' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Image Overlay', 'total' ),
						'param_name' => 'overlay_style',
						'value' => $overlays,
						'group' => __( 'Image', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Overlay Excerpt Length', 'total' ),
						'param_name' => 'overlay_excerpt_length',
						'value' => '15',
						'group' => __( 'Image', 'total' ),
						'dependency' => array( 'element' => 'overlay_style', 'value' => 'title-excerpt-hover' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Overlay Button Text', 'total' ),
						'param_name' => 'overlay_button_text',
						'group' => __( 'Image', 'total' ),
						'dependency' => array( 'element' => 'overlay_style', 'value' => 'hover-button' ),
					),
					array(
						'type' => 'vcex_image_hovers',
						'heading' => __( 'CSS3 Image Hover', 'total' ),
						'param_name' => 'img_hover_style',
						'group' => __( 'Image', 'total' ),
					),
					array(
						'type' => 'vcex_image_filters',
						'heading' => __( 'Image Filter', 'total' ),
						'param_name' => 'img_filter',
						'group' => __( 'Image', 'total' ),
					),
					// Title
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'no',
						'heading' => __( 'Enable', 'total' ),
						'param_name' => 'title',
						'vcex' => array(
							'off' => 'no',
							'on' => 'yes'
						),
						'group' => __( 'Title', 'total' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Tag', 'total' ),
						'param_name' => 'title_tag',
						'value' => array(
							__( 'Default', 'total' ) => '',
							'h2' => 'h2',
							'h3' => 'h3',
							'h4' => 'h4',
							'div' => 'div',
						),
						'group' => __( 'Title', 'total' ),
						'dependency' => array( 'element' => 'title', 'value' => 'yes' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Based On', 'total' ),
						'param_name' => 'title_type',
						'value' => array(
							__( 'Default', 'total' ) => '',
							__( 'Title', 'total' ) => 'title',
							__( 'Alt', 'total' ) => 'alt',
							__( 'Caption', 'total' ) => 'caption',
							__( 'Description', 'total' ) => 'description',
						),
						'group' => __( 'Title', 'total' ),
						'dependency' => array( 'element' => 'title', 'value' => 'yes' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'title_color',
						'group' => __( 'Title', 'total' ),
						'dependency' => array( 'element' => 'title', 'value' => 'yes' ),
					),
					array(
						'type'  => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'title_font_family',
						'group' => __( 'Title', 'total' ),
						'dependency' => array( 'element' => 'title', 'value' => 'yes' ),
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'title_weight',
						'group' => __( 'Title', 'total' ),
						'dependency' => array( 'element' => 'title', 'value' => 'yes' ),
					),
					array(
						'type' => 'vcex_text_transforms',
						'heading' => __( 'Text Transform', 'total' ),
						'param_name' => 'title_transform',
						'group' => __( 'Title', 'total' ),
						'dependency' => array( 'element' => 'title', 'value' => 'yes' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'title_size',
						'group' => __( 'Title', 'total' ),
						'dependency' => array( 'element' => 'title', 'value' => 'yes' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Line Height', 'total' ),
						'param_name' => 'title_line_height',
						'group' => __( 'Title', 'total' ),
						'dependency' => array( 'element' => 'title', 'value' => 'yes' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Margin', 'total' ),
						'param_name' => 'title_margin',
						'group' => __( 'Title', 'total' ),
						'description' => __( 'Please use the following format: top right bottom left.', 'total' ),
						'dependency' => array( 'element' => 'title', 'value' => 'yes' ),
					),
					// Entry CSS
					array(
						'type' => 'css_editor',
						'heading' => __( 'Entry CSS', 'total' ),
						'param_name' => 'entry_css',
						'group' => __( 'Entry CSS', 'total' ),
					),
					// CSS
					array(
						'type' => 'css_editor',
						'heading' => __( 'Wrap CSS', 'total' ),
						'param_name' => 'css',
						'group' => __( 'Wrap CSS', 'total' ),
					),
				)
			);

		}

	}
}
new VCEX_Image_Grid;