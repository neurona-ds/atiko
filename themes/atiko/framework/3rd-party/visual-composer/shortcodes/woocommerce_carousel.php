<?php
/**
 * Visual Composer WooCommerce Carousel
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
* @version 4.0
 */

if ( ! class_exists( 'VCEX_Woocommerce_Carousel_Shortcode' ) ) {

	class VCEX_Woocommerce_Carousel_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.5.0
		 */
		public function __construct() {
			
			// Add shortcode
			add_shortcode( 'vcex_woocommerce_carousel', array( 'VCEX_Woocommerce_Carousel_Shortcode', 'output' ) );

			// Map to VC
			if ( function_exists( 'vc_lean_map' ) ) {
				vc_lean_map( 'vcex_woocommerce_carousel', array( 'VCEX_Woocommerce_Carousel_Shortcode', 'map' ) );
			}

			// Admin filters
			if ( is_admin() ) {

				// Get autocomplete suggestion
				add_filter( 'vc_autocomplete_vcex_woocommerce_carousel_include_categories_callback', 'vcex_suggest_product_categories', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_woocommerce_carousel_exclude_categories_callback', 'vcex_suggest_product_categories', 10, 1 );

				// Render autocomplete suggestions
				add_filter( 'vc_autocomplete_vcex_woocommerce_carousel_include_categories_render', 'vcex_render_product_categories', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_woocommerce_carousel_exclude_categories_render', 'vcex_render_product_categories', 10, 1 );

			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.0
		 */
		public static function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_woocommerce_carousel.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.0
		 */
		public static function map() {

			$s_yes    = __( 'Yes', 'total' );
			$s_no     = __( 'No', 'total' );
			$s_image  = __( 'Image', 'total' );
			$s_title  = __( 'Title', 'total' );
			$s_design = __( 'Design', 'total' );

			return array(
				'name' => __( 'WooCommerce Carousel', 'total' ),
				'description' => __( 'Recent woocommerce posts carousel', 'total' ),
				'base' => 'vcex_woocommerce_carousel',
				'category' => wpex_get_theme_branding(),
				'icon' => 'vcex-woocommerce-carousel vcex-icon fa fa-shopping-cart',
				'params' => array(
					// General
					array(
						'type' => 'textfield',
						'heading' => __( 'Unique Id', 'total' ),
						'description' => __( 'Give your main element a unique ID.', 'total' ),
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
						'heading' => __( 'Arrows?', 'total' ),
						'param_name' => 'arrows',
						'value' => array(
							$s_yes => 'true',
							$s_no => 'false',
						),
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
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Dots?', 'total' ),
						'param_name' => 'dots',
						'value' => array(
							$s_no => 'false',
							$s_yes => 'true',
						),
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
						'type' => 'dropdown',
						'heading' => __( 'Auto Play', 'total' ),
						'param_name' => 'auto_play',
						'value' => array(
							$s_yes => 'true',
							$s_no => 'false',
						),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Timeout Duration in milliseconds', 'total' ),
						'param_name' => 'timeout_duration',
						'value' => '5000',
						'dependency' => array( 'element' => 'auto_play', 'value' => 'true' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Infinite Loop', 'total' ),
						'param_name' => 'infinite_loop',
						'value' => array(
							$s_yes => 'true',
							$s_no => 'false',
						),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Center Item', 'total' ),
						'param_name' => 'center',
						'value' => array(
							$s_no => 'false',
							$s_yes => 'true',
						),
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
						'type' => 'textfield',
						'heading' => __( 'Post Count', 'total' ),
						'param_name' => 'count',
						'value' => '8',
						'group' => __( 'Query', 'total' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Featured Products Only', 'total' ),
						'param_name' => 'featured_products_only',
						'group' => __( 'Query', 'total' ),
						'value' => array(
							$s_no => '',
							$s_yes => true,
						),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Exclude Out of Stock Products', 'total' ),
						'param_name' => 'exclude_products_out_of_stock',
						'group' => __( 'Query', 'total' ),
						'value' => array(
							$s_no => '',
							$s_yes => true,
						),
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
						'group' => __( 'Query', 'total' ),
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
						'group' => __( 'Query', 'total' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Order', 'total' ),
						'param_name' => 'order',
						'group' => __( 'Query', 'total' ),
						'value' => array(
							__( 'Default', 'total' ) => '',
							__( 'DESC', 'total' ) => 'DESC',
							__( 'ASC', 'total' ) => 'ASC',
						),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Order By', 'total' ),
						'param_name' => 'orderby',
						'value' => vcex_orderby_array(),
						'group' => __( 'Query', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Orderby: Meta Key', 'total' ),
						'param_name' => 'orderby_meta_key',
						'group' => __( 'Query', 'total' ),
						'dependency' => array(
							'element' => 'orderby',
							'value' => array( 'meta_value_num', 'meta_value' ),
						),
					),
					// Image
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
					),
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
						'description' => __( 'Enter a width in pixels.', 'total' ),
						'group' => $s_image,
						'dependency' => array(
							'element' => 'img_size',
							'value' => 'wpex_custom',
						),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Image Crop Height', 'total' ),
						'param_name' => 'img_height',
						'dependency' => array(
							'element' => 'img_size',
							'value' => 'wpex_custom',
						),
						'description' => __( 'Enter a height in pixels. Leave empty to disable vertical cropping and keep image proportions.', 'total' ),
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
						'type' => 'textfield',
						'heading' => __( 'Overlay Excerpt Length', 'total' ),
						'param_name' => 'overlay_excerpt_length',
						'value' => '15',
						'group' => $s_image,
						'dependency' => array( 'element' => 'overlay_style', 'value' => 'title-excerpt-hover' ),
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
					// Title
					array(
						'type' => 'dropdown',
						'heading' => __( 'Display Title', 'total' ),
						'param_name' => 'title',
						'value' => array(
							$s_yes => 'true',
							$s_no => 'false',
						),
						'group' => $s_title,
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'content_heading_color',
						'group' => $s_title,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'content_heading_size',
						'description' => __( 'You can use em or px values, but you must define them.', 'total' ),
						'group' => $s_title,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Margin', 'total' ),
						'param_name' => 'content_heading_margin',
						'description' => __( 'Please use the following format: top right bottom left.', 'total' ),
						'group' => $s_title,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Line Height', 'total' ),
						'param_name' => 'content_heading_line_height',
						'description' => __( 'Enter a numerical, pixel or percentage value.', 'total' ),
						'group' => $s_title,
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'content_heading_weight',
						'description' => __( 'Note: Not all font families support every font weight.', 'total' ),
						'group' => $s_title,
					),
					array(
						'type' => 'vcex_text_transforms',
						'heading' => __( 'Text Transform', 'total' ),
						'param_name' => 'content_heading_transform',
						'group' => $s_title,
					),
					// Price
					array(
						'type' => 'dropdown',
						'heading' => __( 'Display Price', 'total' ),
						'param_name' => 'price',
						'value' => array(
							$s_yes => 'true',
							$s_no => 'false',
						),
						'group' => __( 'Price', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'content_color',
						'group' => __( 'Price', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'content_font_size',
						'group' => __( 'Price', 'total' ),
						'description' => __( 'You can use em or px values, but you must define them.', 'total' ),
					),
					// Design
					array(
						'type' => 'dropdown',
						'heading' => __( 'Style', 'total' ),
						'param_name' => 'style',
						'value' => array(
							__( 'Default', 'total') => 'default',
							__( 'No Margins', 'total' ) => 'no-margins',
						),
						'group' => $s_design,
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Content Background', 'total' ),
						'param_name' => 'content_background',
						'group' => $s_design,
					),
					array(
						'type' => 'vcex_text_alignments',
						'heading' => __( 'Content Alignment', 'total' ),
						'param_name' => 'content_alignment',
						'group' => $s_design,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Content Margin', 'total' ),
						'param_name' => 'content_margin',
						'description' => __( 'Please use the following format: top right bottom left.', 'total' ),
						'group' => $s_design,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Content Padding', 'total' ),
						'param_name' => 'content_padding',
						'description' => __( 'Please use the following format: top right bottom left.', 'total' ),
						'group' => $s_design,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Content Border', 'total' ),
						'param_name' => 'content_border',
						'description' => __( 'Please use the shorthand format: width style color. Enter 0px or "none" to disable border.', 'total' ),
						'group' => $s_design,
					),
					// HIDDEN
					array(
						'type' => 'hidden',
						'param_name' => 'entry_output',
					),
				),
			);
		}

	}
}
new VCEX_Woocommerce_Carousel_Shortcode;