<?php
/**
 * Visual Composer Post Type Carousel
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.0
 */

if ( ! class_exists( 'VCEX_Post_Type_Carousel_Shortcode' ) ) {

	class VCEX_Post_Type_Carousel_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.5.0
		 */
		public function __construct() {
			
			// Add shortcode
			add_shortcode( 'vcex_post_type_carousel', array( 'VCEX_Post_Type_Carousel_Shortcode', 'output' ) );

			// Map to VC
			if ( function_exists( 'vc_lean_map' ) ) {
				vc_lean_map( 'vcex_post_type_carousel', array( 'VCEX_Post_Type_Carousel_Shortcode', 'map' ) );
			}

			// Admin filters
			if ( is_admin() ) {

				// Get autocomplete suggestion
				add_filter( 'vc_autocomplete_vcex_post_type_carousel_tax_query_taxonomy_callback', 'vcex_suggest_taxonomies', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_post_type_carousel_filter_taxonomy_callback', 'vcex_suggest_taxonomies', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_post_type_carousel_tax_query_terms_callback', 'vcex_suggest_terms', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_post_type_carousel_author_in_callback', 'vcex_suggest_users', 10, 1 );

				// Render autocomplete suggestions
				add_filter( 'vc_autocomplete_vcex_post_type_carousel_filter_taxonomy_render', 'vcex_render_taxonomies', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_post_type_carousel_tax_query_taxonomy_render', 'vcex_render_taxonomies', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_post_type_carousel_tax_query_terms_render', 'vcex_render_terms', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_post_type_carousel_author_in_render', 'vcex_render_users', 10, 1 );

			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.0
		 */
		public static function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_post_type_carousel.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.0
		 */
		public static function map() {

			// Strings
			$s_enable  = __( 'Enable', 'total' );
			$s_query   = __( 'Query', 'total' );
			$s_title   = __( 'Title', 'total' );
			$s_image   = __( 'Image', 'total' );
			$s_date    = __( 'Date', 'total' );
			$s_excerpt = __( 'Excerpt', 'total' );
			$s_button      = __( 'Button', 'total' );

			// Return array
			return array(
				'name' => __( 'Post Types Carousel', 'total' ),
				'description' => __( 'Posts carousel', 'total' ),
				'base' => 'vcex_post_type_carousel',
				'category' => wpex_get_theme_branding(),
				'icon' => 'vcex-post-type-carousel vcex-icon fa fa-files-o',
				'params' => array(
					// General
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
						'type' => 'dropdown',
						'heading' => __( 'Style', 'total' ),
						'param_name' => 'style',
						'value' => array(
							__( 'Default', 'total') => 'default',
							__( 'No Margins', 'total' ) => 'no-margins',
						),
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
						'dependency' => array( 'element' => 'arrows', 'value' => 'true' ),
						'std' => 'default',
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Dots?', 'total' ),
						'param_name' => 'dots',
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
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
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
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Center Item', 'total' ),
						'param_name' => 'center',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Animation Speed', 'total' ),
						'param_name' => 'animation_speed',
						'value' => '150',
						'description' => __( 'Default is 150 milliseconds. Enter 0.0 to disable.', 'total' ),
					),
					// Query
					array(
						'type' => 'posttypes',
						'heading' => __( 'Post types', 'total' ),
						'param_name' => 'post_types',
						'std' => 'post',
						'group' => $s_query,
						'admin_label' => true,
					),
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
						'std' => 'false',
						'heading' => __( 'Ignore Sticky Posts', 'total' ),
						'param_name' => 'ignore_sticky_posts',
						'group' => $s_query,
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Post With Thumbnails Only', 'total' ),
						'param_name' => 'thumbnail_query',
						'group' => $s_query,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Limit By Post ID\'s', 'total' ),
						'param_name' => 'posts_in',
						'group' => $s_query,
						'description' => __( 'Seperate by a comma.', 'total' ),
					),
					array(
						'type' => 'autocomplete',
						'heading' => __( 'Limit By Author', 'total' ),
						'param_name' => 'author_in',
						'settings' => array(
							'multiple' => true,
							'min_length' => 1,
							'groups' => false,
							'unique_values' => true,
							'display_inline' => true,
							'delay' => 0,
							'auto_focus' => true,
						),
						'group' => $s_query,
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Query by Taxonomy', 'total' ),
						'param_name' => 'tax_query',
						'value' => array(
							__( 'No', 'total' ) => 'false',
							__( 'Yes', 'total') => 'true',
						),
						'group' => $s_query,
					),
					array(
						'type' => 'autocomplete',
						'heading' => __( 'Taxonomy Name', 'total' ),
						'param_name' => 'tax_query_taxonomy',
						'dependency' => array(
							'element' => 'tax_query',
							'value' => 'true',
						),
						'settings' => array(
							'multiple' => false,
							'min_length' => 1,
							'groups' => false,
							'display_inline' => true,
							'delay' => 0,
							'auto_focus' => true,
						),
						'group' => $s_query,
						'description' => __( 'If you do not see your taxonomy in the dropdown you can still enter the taxonomy name manually.', 'total' ),
					),
					array(
						'type' => 'autocomplete',
						'heading' => __( 'Terms', 'total' ),
						'param_name' => 'tax_query_terms',
						'dependency' => array( 'element' => 'tax_query', 'value' => 'true' ),
						'settings' => array(
							'multiple' => true,
							'min_length' => 1,
							'groups' => true,
							'display_inline' => true,
							'delay' => 0,
							'auto_focus' => true,
						),
						'group' => $s_query,
						'description' => __( 'If you do not see your terms in the dropdown you can still enter the term slugs manually seperated by a space.', 'total' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Order', 'total' ),
						'param_name' => 'order',
						'group' => $s_query,
						'value' => array(
							__( 'Default', 'total' ) => 'default',
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
						'dependency' => array( 'element' => 'orderby', 'value' => array( 'meta_value_num', 'meta_value' ) ),
					),
					// Image
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => $s_enable,
						'param_name' => 'media',
						'group' => $s_image,
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Image Links To', 'total' ),
						'param_name' => 'thumbnail_link',
						'value' => array(
							__( 'Default', 'total') => '',
							__( 'Post', 'total') => 'post',
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
						'std' => 'full',
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
						'std' => 'true',
						'heading' => $s_enable,
						'param_name' => 'title',
						'group' => $s_title,
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
						'heading' => __( 'Margin', 'total' ),
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
						'std' => 'true',
						'heading' => $s_enable,
						'param_name' => 'date',
						'group' => $s_date,
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'date_color',
						'group' => $s_date,
						'dependency' => array( 'element' => 'date', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'date_font_size',
						'group' => $s_date,
						'dependency' => array( 'element' => 'date', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Margin', 'total' ),
						'param_name' => 'date_margin',
						'description' => __( 'Please use the following format: top right bottom left.', 'total' ),
						'group' => $s_date,
						'dependency' => array( 'element' => 'title', 'value' => 'true' ),
					),

					// Excerpt
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => $s_enable,
						'param_name' => 'excerpt',
						'group' => $s_excerpt,
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
						'std' => 'false',
						'heading' => $s_enable,
						'param_name' => 'read_more',
						'group' => $s_button,
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
						'std' => 'false',
						'heading' => __( 'Arrow', 'total' ),
						'param_name' => 'readmore_rarr',
						'group' => $s_button,
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
						'group' => __( 'Content CSS', 'total' ),
					),
					array(
						'type' => 'vcex_text_alignments',
						'heading' => __( 'Alignment', 'total' ),
						'param_name' => 'content_alignment',
						'group' => __( 'Content CSS', 'total' ),
					),
					// Entry CSS
					array(
						'type' => 'css_editor',
						'heading' => __( 'CSS', 'total' ),
						'param_name' => 'entry_css',
						'group' => __( 'Entry CSS', 'total' ),
					),
				),
			);
		}

	}
}
new VCEX_Post_Type_Carousel_Shortcode;