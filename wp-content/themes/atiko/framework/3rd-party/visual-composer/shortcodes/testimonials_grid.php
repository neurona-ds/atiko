<?php
/**
 * Visual Composer Testimonials Grid
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.0
 */

if ( ! class_exists( 'VCEX_Testimonials_Grid_Shortcode' ) ) {

	class VCEX_Testimonials_Grid_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.5.0
		 */
		public function __construct() {
			
			// Add shortcode
			add_shortcode( 'vcex_testimonials_grid', array( 'VCEX_Testimonials_Grid_Shortcode', 'output' ) );

			// Map to VC
			if ( function_exists( 'vc_lean_map' ) ) {
				vc_lean_map( 'vcex_testimonials_grid', array( 'VCEX_Testimonials_Grid_Shortcode', 'map' ) );
			}

			// Admin filters
			if ( is_admin() ) {

				// Get autocomplete suggestion
				add_filter( 'vc_autocomplete_vcex_testimonials_grid_include_categories_callback', 'vcex_suggest_testimonials_categories', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_testimonials_grid_exclude_categories_callback', 'vcex_suggest_testimonials_categories', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_testimonials_grid_filter_active_category_callback', 'vcex_suggest_testimonials_categories', 10, 1 );

				// Render autocomplete suggestions
				add_filter( 'vc_autocomplete_vcex_testimonials_grid_include_categories_render', 'vcex_render_testimonials_categories', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_testimonials_grid_exclude_categories_render', 'vcex_render_testimonials_categories', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_testimonials_grid_filter_active_category_render', 'vcex_render_testimonials_categories', 10, 1 );
				
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.0
		 */
		public static function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_testimonials_grid.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.0
		 */
		public static function map() {
			// Repeatable strings
			$s_enable  = __( 'Enable', 'total' );
			$s_query   = __( 'Query', 'total' );
			$s_filter  = __( 'Filter', 'total' );
			$s_image   = __( 'Image', 'total' );
			$s_title   = __( 'Title', 'total' );
			$s_details = __( 'Details', 'total' );
			$s_content = __( 'Content', 'total' );
			// Return array
			return array(
				'name' => __( 'Testimonials Grid', 'total' ),
				'description' => __( 'Recent testimonials post grid', 'total' ),
				'base' => 'vcex_testimonials_grid',
				'category' => wpex_get_theme_branding(),
				'icon' => 'vcex-testimonials-grid vcex-icon fa fa-comments-o',
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
						'heading' => __( 'Grid Style', 'total' ),
						'param_name' => 'grid_style',
						'value' => array(
							__( 'Fit Columns', 'total' ) => 'fit_columns',
							__( 'Masonry', 'total' ) => 'masonry',
						),
						'edit_field_class' => 'vc_col-sm-3 vc_column clear',
					),
					array(
						'type' => 'vcex_grid_columns',
						'heading' => __( 'Columns', 'total' ),
						'param_name' => 'columns',
						'std' => '3',
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
						'param_name' => 'columns_responsive',
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
						'dependency' => array( 'element' => 'columns_responsive', 'value' => 'true' ),
					),
					// Query
					array(
						'type' => 'textfield',
						'heading' => __( 'Posts Per Page', 'total' ),
						'param_name' => 'posts_per_page',
						'value' => '-1',
						'group' => $s_query,
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Pagination', 'total' ),
						'param_name' => 'pagination',
						'group' => $s_query,
					),
					array(
						'type' => 'autocomplete',
						'heading' => __( 'Include Categories', 'total' ),
						'param_name' => 'include_categories',
						'param_holder_class' => 'vc_not-for-custom',
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
						'admin_label' => true,
					),
					array(
						'type' => 'autocomplete',
						'heading' => __( 'Exclude Categories', 'total' ),
						'param_name' => 'exclude_categories',
						'param_holder_class' => 'vc_not-for-custom',
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
						'admin_label' => true,
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Order', 'total' ),
						'param_name' => 'order',
						'group' => $s_query,
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
					// Filter
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => $s_enable,
						'param_name' => 'filter',
						'group' => $s_filter,
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Display All Link?', 'total' ),
						'param_name' => 'filter_all_link',
						'group' => $s_filter,
						'dependency' => array( 'element' => 'filter', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'no',
						'heading' => __( 'Center Filter Links', 'total' ),
						'param_name' => 'center_filter',
						'vcex' => array(
							'off' => 'no',
							'on' => 'yes',
						),
						'group' => $s_filter,
						'dependency' => array( 'element' => 'filter', 'value' => 'true' ),
					),
					array(
						'type' => 'autocomplete',
						'heading' => __( 'Default Active Category', 'total' ),
						'param_name' => 'filter_active_category',
						'param_holder_class' => 'vc_not-for-custom',
						'settings' => array(
							'multiple' => false,
							'min_length' => 1,
							'groups' => false,
							'unique_values' => true,
							'display_inline' => true,
							'delay' => 0,
							'auto_focus' => true,
						),
						'group' => $s_filter,
						'dependency' => array( 'element' => 'filter', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Custom Filter "All" Text', 'total' ),
						'param_name' => 'all_text',
						'group' => $s_filter,
						'dependency' => array( 'element' => 'filter_all_link', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_button_styles',
						'heading' => __( 'Button Style', 'total' ),
						'param_name' => 'filter_button_style',
						'group' => $s_filter,
						'std' => 'minimal-border',
						'dependency' => array( 'element' => 'filter', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_button_colors',
						'heading' => __( 'Button Color', 'total' ),
						'param_name' => 'filter_button_color',
						'group' => $s_filter,
						'dependency' => array( 'element' => 'filter', 'value' => 'true' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Layout Mode', 'total' ),
						'param_name' => 'masonry_layout_mode',
						'value' => array(
							__( 'Masonry', 'total' ) => 'masonry',
							__( 'Fit Rows', 'total' ) => 'fitRows',
						),
						'group' => $s_filter,
						'dependency' => array( 'element' => 'filter', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Custom Filter Speed', 'total' ),
						'param_name' => 'filter_speed',
						'description' => __( 'Default is 0.4 seconds. Enter 0.0 to disable.', 'total' ),
						'group' => $s_filter,
						'dependency' => array( 'element' => 'filter', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'filter_font_size',
						'group' => $s_filter,
						'dependency' => array( 'element' => 'filter', 'value' => 'true' ),
					),
					// Image
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => $s_enable,
						'param_name' => 'entry_media',
						'group' => $s_image,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Border Radius', 'total' ),
						'param_name' => 'img_border_radius',
						'group' => $s_image,
						'dependency' => array( 'element' => 'entry_media', 'value' => 'true' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Image Size', 'total' ),
						'param_name' => 'img_size',
						'std' => 'wpex_custom',
						'value' => vcex_image_sizes(),
						'group' => $s_image,
						'dependency' => array( 'element' => 'entry_media', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_image_crop_locations',
						'heading' => __( 'Image Crop Location', 'total' ),
						'param_name' => 'img_crop',
						'std' => 'center-center',
						'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
						'group' => $s_image,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Image Crop Width', 'total' ),
						'param_name' => 'img_width',
						'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
						'group' => $s_image,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Image Crop Height', 'total' ),
						'param_name' => 'img_height',
						'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
						'description' => __( 'Enter a height in pixels. Leave empty to disable vertical cropping and keep image proportions.', 'total' ),
						'group' => $s_image,
					),
					// Title
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => $s_enable,
						'param_name' => 'title',
						'group' => $s_title,
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Link to Post', 'total' ),
						'param_name' => 'title_link',
						'group' => $s_title,
						'dependency' => array( 'element' => 'title', 'value' => 'true' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'HTML Tag', 'total' ),
						'param_name' => 'title_tag',
						'group' => $s_title,
						'std' => 'h2',
						'value' => array(
							'h2' => 'h2',
							'h3' => 'h3',
							'h4' => 'h4',
							'h5' => 'h5',
							'h6' => 'h6',
							'div' => 'div',
						),
						'dependency' => array( 'element' => 'title', 'value' => 'true' ),
					),
					array(
						'type'  => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'title_font_family',
						'group' => $s_title,
						'dependency' => array( 'element' => 'title', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'title_font_size',
						'group' => $s_title,
						'dependency' => array( 'element' => 'title', 'value' => 'true' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'title_color',
						'group' => $s_title,
						'dependency' => array( 'element' => 'title', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Bottom Margin', 'total' ),
						'param_name' => 'title_bottom_margin',
						'group' => $s_title,
						'dependency' => array( 'element' => 'title', 'value' => 'true' ),
					),
					// Author
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Author', 'total' ),
						'param_name' => 'author',
						'group' => $s_details,
					),
					// Company
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Company', 'total' ),
						'param_name' => 'company',
						'group' => $s_details,
					),
					// Rating
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Rating', 'total' ),
						'param_name' => 'rating',
						'group' => $s_details,
					),
					// Content
					array(
						'type' => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'content_font_size',
						'group' => $s_content,
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'content_color',
						'group' => $s_content,
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Excerpt', 'total' ),
						'param_name' => 'excerpt',
						 'group' => $s_content,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Excerpt Length', 'total' ),
						'param_name' => 'excerpt_length',
						'value' => '20',
						'dependency' => array( 'element' => 'excerpt', 'value' => 'true' ),
						'group' => $s_content,
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Read More', 'total' ),
						'param_name' => 'read_more',
						'dependency' => array( 'element' => 'excerpt', 'value' => 'true' ),
						'group' => $s_content,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Read More Text', 'total' ),
						'param_name' => 'read_more_text',
						'value' => __( 'read more', 'total' ),
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
						'group' => $s_content,
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Read More Arrow', 'total' ),
						'param_name' => 'read_more_rarr',
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
						'group' => $s_content,
					),
				),
			);
		}

	}
}
new VCEX_Testimonials_Grid_Shortcode;