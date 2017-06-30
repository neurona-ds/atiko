<?php
/**
 * Visual Composer Carousel
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.0
 */

if ( ! class_exists( 'VCEX_Blog_Carousel_Shortcode' ) ) {

	class VCEX_Blog_Carousel_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.5.0
		 */
		public function __construct() {
			
			// Add shortcode
			add_shortcode( 'vcex_blog_carousel', array( 'VCEX_Blog_Carousel_Shortcode', 'output' ) );

			// Map to VC
			if ( function_exists( 'vc_lean_map' ) ) {
				vc_lean_map( 'vcex_blog_carousel', array( 'VCEX_Blog_Carousel_Shortcode', 'map' ) );
			}

			// Admin filters
			if ( is_admin() ) {

				// Get autocomplete suggestion
				add_filter( 'vc_autocomplete_vcex_blog_carousel_include_categories_callback', 'vcex_suggest_categories', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_blog_carousel_exclude_categories_callback', 'vcex_suggest_categories', 10, 1 );

				// Render autocomplete suggestions
				add_filter( 'vc_autocomplete_vcex_blog_carousel_include_categories_render', 'vcex_render_categories', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_blog_carousel_exclude_categories_render', 'vcex_render_categories', 10, 1 );

				// Set image height to full if crop/width are empty
				add_filter( 'vc_edit_form_fields_attributes_vcex_blog_carousel', 'vcex_parse_image_size' );

				// Move content design elements into new entry CSS field
				add_filter( 'vc_edit_form_fields_attributes_vcex_blog_carousel', 'vcex_parse_deprecated_grid_entry_content_css' );

			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.0
		 */
		public static function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_blog_carousel.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.0
		 */
		public static function map() {

			// Save re-usable strings
			$s_default     = __( 'Default', 'total' );
			$s_enable      = __( 'Enable', 'total' );
			$s_title       = __( 'Title', 'total' );
			$s_content_css = __( 'Content CSS', 'total' );
			$s_query       = __( 'Query', 'total' );
			$s_image       = __( 'Image', 'total' );
			$s_excerpt     = __( 'Excerpt', 'total' );
			$s_button      = __( 'Button', 'total' );

			// Return array
			return array(
				'name' => __( 'Blog Carousel', 'total' ),
				'description' => __( 'Recent blog posts carousel', 'total' ),
				'base' => 'vcex_blog_carousel',
				'category' => wpex_get_theme_branding(),
				'icon' => 'vcex-blog-carousel vcex-icon fa fa-pencil',
				'params' => array(
					// General
					array(
						'type' => 'textfield',
						'heading' => __( 'Unique Id', 'total' ),
						'param_name' => 'unique_id',
						'admin_label' => true,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Extra class name', 'total' ),
						'param_name' => 'classes',
						'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total' ),
					),
					array(
						'type' => 'vcex_visibility',
						'heading' => __( 'Visibility', 'total' ),
						'param_name' => 'visibility',
					),
					vcex_vc_map_add_css_animation(),
					array(
						'type' => 'vcex_ofswitch',
						'heading' => __( 'Arrows?', 'total' ),
						'param_name' => 'arrows',
						'std' => 'true',
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
						'dependency' => array( 'element' => 'arrows', 'value' => 'true' ),
						'std' => 'default',
					),
					array(
						'type' => 'vcex_ofswitch',
						'heading' => __( 'Dots?', 'total' ),
						'param_name' => 'dots',
						'std' => 'false',
					),
					array(
						'type' => 'vcex_ofswitch',
						'heading' => __( 'Auto Play', 'total' ),
						'param_name' => 'auto_play',
						'std' => 'false',
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
						'heading' => __( 'Infinite Loop', 'total' ),
						'param_name' => 'infinite_loop',
						'std' => 'true',
					),
					array(
						'type' => 'vcex_ofswitch',
						'heading' => __( 'Center Item', 'total' ),
						'param_name' => 'center',
						'std' => 'false',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Animation Speed', 'total' ),
						'param_name' => 'animation_speed',
						'value' => '150',
						'description' => __( 'Default is 150 milliseconds. Enter 0.0 to disable.', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Items To Display', 'total' ),
						'param_name' => 'items',
						'value' => '4',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Tablet: Items To Display', 'total' ),
						'param_name' => 'tablet_items',
						'value' => '3',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Mobile Landscape: Items To Display', 'total' ),
						'param_name' => 'mobile_landscape_items',
						'value' => '2',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Mobile Portrait: Items To Display', 'total' ),
						'param_name' => 'mobile_portrait_items',
						'value' => '1',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Items To Scrollby', 'total' ),
						'param_name' => 'items_scroll',
						'value' => '1',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Margin Between Items', 'total' ),
						'param_name' => 'items_margin',
						'value' => '15',
					),
					// Query
					array(
						'type' => 'textfield',
						'heading' => __( 'Post Count', 'total' ),
						'param_name' => 'count',
						'value' => '8',
						'group' => $s_query,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Offset', 'total' ),
						'param_name' => 'offset',
						'group' => $s_query,
						'description' => __( 'Number of post to displace or pass over. Warning: Setting the offset parameter overrides/ignores the paged parameter and breaks pagination. The offset parameter is ignored when posts per page is set to -1.', 'total' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'heading' => __( 'Ignore Sticky Posts', 'total' ),
						'param_name' => 'ignore_sticky_posts',
						'group' => $s_query,
						'std' => 'false',
					),
					array(
						'type' => 'autocomplete',
						'heading' => __( 'Include Categories', 'total' ),
						'param_name' => 'include_categories',
						'param_holder_class' => 'vc_not-for-custom',
						'admin_label' => true,
						'settings' => array(
							'multiple' => true,
							'min_length' => 1,
							'groups' => true,
							'unique_values' => true,
							'display_inline' => true,
							'delay' => 0,
							'auto_focus' => true,
						),
						'group' => $s_query,
					),
					array(
						'type' => 'autocomplete',
						'heading' => __( 'Exclude Categories', 'total' ),
						'param_name' => 'exclude_categories',
						'param_holder_class' => 'vc_not-for-custom',
						'admin_label' => true,
						'settings' => array(
							'multiple' => true,
							'min_length' => 1,
							'groups' => true,
							'unique_values' => true,
							'display_inline' => true,
							'delay' => 0,
							'auto_focus' => true,
						),
						'group' => $s_query,
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Order', 'total' ),
						'param_name' => 'order',
						'group' => $s_query,
						'value' => array(
							$s_default => '',
							__( 'DESC', 'total' ) => 'DESC',
							__( 'ASC', 'total' ) => 'ASC',
						),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Order By', 'total' ),
						'param_name' => 'orderby',
						'value' => vcex_orderby_array(),
						'group' => $s_query,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Orderby: Meta Key', 'total' ),
						'param_name' => 'orderby_meta_key',
						'group' => $s_query,
						'dependency' => array(
							'element' => 'orderby',
							'value' => array( 'meta_value_num', 'meta_value' ),
						),
					),
					// Image
					array(
						'type' => 'vcex_ofswitch',
						'heading' => $s_enable,
						'param_name' => 'media',
						'group' => $s_image,
						'std' => 'true',
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Image Links To', 'total' ),
						'param_name' => 'thumbnail_link',
						'value' => array(
							$s_default => '',
							__( 'Post', 'total' ) => 'post',
							__( 'Lightbox', 'total' ) => 'lightbox',
							__( 'None', 'total' ) => 'none',
						),
						'group' => $s_image,
						'dependency' => array( 'element' => 'media', 'value' => 'true' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Image Size', 'total' ),
						'param_name' => 'img_size',
						'std' => 'wpex_custom',
						'value' => vcex_image_sizes(),
						'group' => $s_image,
						'dependency' => array( 'element' => 'media', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_image_crop_locations',
						'heading' => __( 'Image Crop Location', 'total' ),
						'param_name' => 'img_crop',
						'std' => 'center-center',
						'group' => $s_image,
						'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom', ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Image Crop Width', 'total' ),
						'param_name' => 'img_width',
						'group' => $s_image,
						'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom', ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Image Crop Height', 'total' ),
						'param_name' => 'img_height',
						'description' => __( 'Enter a height in pixels. Leave empty to disable vertical cropping and keep image proportions.', 'total' ),
						'group' => $s_image,
						'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom', ),
					),
					array(
						'type' => 'vcex_overlay',
						'heading' => __( 'Image Overlay', 'total' ),
						'param_name' => 'overlay_style',
						'group' => $s_image,
						'dependency' => array( 'element' => 'media', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Overlay Button Text', 'total' ),
						'param_name' => 'overlay_button_text',
						'group' => $s_image,
						'dependency' => array( 'element' => 'overlay_style', 'value' => 'hover-button' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Overlay Excerpt Length', 'total' ),
						'param_name' => 'overlay_excerpt_length',
						'value' => '15',
						'group' => $s_image,
						'dependency' => array( 'element' => 'overlay_style', 'value' => 'title-excerpt-hover' ),
					),
					array(
						'type' => 'vcex_image_hovers',
						'heading' => __( 'Image Hover', 'total' ),
						'param_name' => 'img_hover_style',
						'group' => $s_image,
						'dependency' => array( 'element' => 'media', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_image_filters',
						'heading' => __( 'Image Filter', 'total' ),
						'param_name' => 'img_filter',
						'group' => $s_image,
						'dependency' => array( 'element' => 'media', 'value' => 'true' ),
					),
					// Title
					array(
						'type' => 'vcex_ofswitch',
						'heading' => $s_enable,
						'param_name' => 'title',
						'group' => $s_title,
						'std' => 'true',
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'content_heading_color',
						'group' => $s_title,
						'dependency' => array( 'element' => 'title', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'content_heading_size',
						'group' => $s_title,
						'dependency' => array( 'element' => 'title', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __(  'Margin', 'total' ),
						'param_name' => 'content_heading_margin',
						'description' => __( 'Please use the following format: top right bottom left.', 'total' ),
						'group' => $s_title,
						'dependency' => array( 'element' => 'title', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Line Height', 'total' ),
						'param_name' => 'content_heading_line_height',
						'group' => $s_title,
						'dependency' => array( 'element' => 'title', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'content_heading_weight',
						'group' => $s_title,
						'std' => '',
						'dependency' => array( 'element' => 'title', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_text_transforms',
						'heading' => __( 'Text Transform', 'total' ),
						'param_name' => 'content_heading_transform',
						'group' => $s_title,
						'dependency' => array( 'element' => 'title', 'value' => 'true' ),
					),
					// Date
					array(
						'type' => 'vcex_ofswitch',
						'heading' => $s_enable,
						'param_name' => 'date',
						'group' => __( 'Date', 'total' ),
						'std' => 'true',
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'date_color',
						'group' => __( 'Date', 'total' ),
						'dependency' => array( 'element' => 'date', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'date_font_size',
						'group' => __( 'Date', 'total' ),
						'dependency' => array( 'element' => 'date', 'value' => 'true' ),
					),
					// Excerpt
					array(
						'type' => 'vcex_ofswitch',
						'heading' => $s_enable,
						'param_name' => 'excerpt',
						'group' => $s_excerpt,
						'std' => 'true',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Length', 'total' ),
						'param_name' => 'excerpt_length',
						'value' => '15',
						'description' => __( 'Enter how many words to display for the excerpt. To display the full post content enter "-1". To display the full post content up to the "more" tag enter "9999".', 'total' ),
						'group' => $s_excerpt,
						'dependency' => array( 'element' => 'excerpt', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'content_font_size',
						'group' => $s_excerpt,
						'dependency' => array( 'element' => 'excerpt', 'value' => 'true' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Text Color', 'total' ),
						'param_name' => 'content_color',
						'group' => $s_excerpt,
						'dependency' => array( 'element' => 'excerpt', 'value' => 'true' ),
					),
					// Readmore
					array(
						'type' => 'vcex_ofswitch',
						'heading' => $s_enable,
						'param_name' => 'read_more',
						'group' => $s_button,
						'std' => 'false',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Text', 'total' ),
						'param_name' => 'read_more_text',
						'group' => $s_button,
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_button_styles',
						'heading' => __( 'Style', 'total' ),
						'param_name' => 'readmore_style',
						'group' => $s_button,
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_button_colors',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'readmore_style_color',
						'group' => $s_button,
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'heading' => __( 'Arrow', 'total' ),
						'param_name' => 'readmore_rarr',
						'group' => $s_button,
						'std' => 'false',
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'readmore_size',
						'group' => $s_button,
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Border Radius', 'total' ),
						'param_name' => 'readmore_border_radius',
						'description' => __( 'Please enter a px value.', 'total' ),
						'group' => $s_button,
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Padding', 'total' ),
						'param_name' => 'readmore_padding',
						'description' => __( 'Please use the following format: top right bottom left.', 'total' ),
						'group' => $s_button,
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Margin', 'total' ),
						'param_name' => 'readmore_margin',
						'description' => __( 'Please use the following format: top right bottom left.', 'total' ),
						'group' => $s_button,
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background', 'total' ),
						'param_name' => 'readmore_background',
						'group' => $s_button,
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'readmore_color',
						'group' => $s_button,
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background: Hover', 'total' ),
						'param_name' => 'readmore_hover_background',
						'group' => $s_button,
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color: Hover', 'total' ),
						'param_name' => 'readmore_hover_color',
						'group' => $s_button,
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
					),
					// Content CSS
					array(
						'type' => 'css_editor',
						'heading' => __( 'CSS', 'total' ),
						'param_name' => 'content_css',
						'group' => $s_content_css,
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Style', 'total' ),
						'param_name' => 'style',
						'value' => array(
							$s_default => 'default',
							__( 'No Margins', 'total' ) => 'no-margins',
						),
						'group' => $s_content_css,
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Alignment', 'total' ),
						'param_name' => 'content_alignment',
						'value' => array(
							$s_default => '',
							__( 'Left', 'total' ) => 'left',
							__( 'Right', 'total' ) => 'right',
							__( 'Center', 'total' ) => 'center',
						),
						'group' => $s_content_css,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Opacity', 'total' ),
						'param_name' => 'content_opacity',
						'description' => __( 'Enter a value between "0" and "1".', 'total' ),
						'group' => $s_content_css,
					),
					// Hidden/Deprecated fields
					array( 'type' => 'hidden', 'param_name' => 'content_background' ),
					array( 'type' => 'hidden', 'param_name' => 'content_margin' ),
					array( 'type' => 'hidden', 'param_name' => 'content_padding' ),
					array( 'type' => 'hidden', 'param_name' => 'content_border' ),
				),
			);
		}

	}

}
new VCEX_Blog_Carousel_Shortcode;