<?php
/**
 * Visual Composer Staff Grid
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.0
 */

if ( ! class_exists( 'VCEX_Staff_Grid_Shortcode' ) ) {

	class VCEX_Staff_Grid_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.5.0
		 */
		public function __construct() {
			
			// Add shortcode
			add_shortcode( 'vcex_staff_grid', array( 'VCEX_Staff_Grid_Shortcode', 'output' ) );

			// Map to VC
			if ( function_exists( 'vc_lean_map' ) ) {
				vc_lean_map( 'vcex_staff_grid', array( 'VCEX_Staff_Grid_Shortcode', 'map' ) );
			}

			// Admin filters
			if ( is_admin() ) {

				// Get autocomplete suggestion
				add_filter( 'vc_autocomplete_vcex_staff_grid_include_categories_callback', 'vcex_suggest_staff_categories', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_staff_grid_exclude_categories_callback', 'vcex_suggest_staff_categories', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_staff_grid_filter_active_category_callback', 'vcex_suggest_staff_categories', 10, 1 );

				// Render autocomplete suggestions
				add_filter( 'vc_autocomplete_vcex_staff_grid_include_categories_render', 'vcex_render_staff_categories', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_staff_grid_exclude_categories_render', 'vcex_render_staff_categories', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_staff_grid_filter_active_category_render', 'vcex_render_staff_categories', 10, 1 );

				// Move content design elements into new entry CSS field
				add_filter( 'vc_edit_form_fields_attributes_vcex_staff_grid', 'vcex_parse_deprecated_grid_entry_content_css' );

				// Set image height to full if crop/width are empty
				add_filter( 'vc_edit_form_fields_attributes_vcex_staff_grid', 'vcex_parse_image_size' );

			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.0
		 */
		public static function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_staff_grid.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.0
		 */
		public static function map() {

			// Strings
			$s_enable      = __( 'Enable', 'total' );
			$s_query       = __( 'Query', 'total' );
			$s_filter      = __( 'Filter', 'total' );
			$s_image       = __( 'Image', 'total' );
			$s_title       = __( 'Title', 'total' );
			$s_position    = __( 'Position', 'total' );
			$s_social      = __( 'Social', 'total' );
			$s_excerpt     = __( 'Excerpt', 'total' );
			$s_content_css = __( 'Content CSS', 'total' );
			$s_entry_css   = __( 'Entry CSS', 'total' );
			$s_categories  = __( 'Categories', 'total' );
			
			// Return array
			return array(
				'name' => __( 'Staff Grid', 'total' ),
				'description' => __( 'Recent staff posts grid', 'total' ),
				'base' => 'vcex_staff_grid',
				'category' => wpex_get_theme_branding(),
				'icon' => 'vcex-staff-grid vcex-icon fa fa-users',
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
							__( 'No Margins', 'total' ) => 'no_margins',
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
					array(
						'type' => 'dropdown',
						'heading' => __( '1 Column Style', 'total' ),
						'param_name' => 'single_column_style',
						'value' => array(
							__( 'Default', 'total') => '',
							__( 'Left Image And Right Content', 'total' ) => 'left_thumbs',
						),
						'dependency' => array( 'element' => 'columns', 'value' => '1' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Equal Heights?', 'total' ),
						'param_name' => 'equal_heights_grid',
						'description' => __( 'Enable so the content area for each entry is the same height.', 'total' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Link Target', 'total' ),
						'param_name' => 'link_target',
						'value' => array(
							__( 'Default', 'total') => '',
							__( 'Blank', 'total' ) => 'blank',
						),
					),
					// Query
					array(
						'type' => 'textfield',
						'heading' => __( 'Posts Per Page', 'total' ),
						'param_name' => 'posts_per_page',
						'value' => '9',
						'description' => __( 'When pagination is disabled this is also used for the post count.', 'total' ),
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
						'heading' => __( 'Pagination', 'total' ),
						'param_name' => 'pagination',
						'group' => $s_query,
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
							'groups' => false,
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
						'description' => __( 'Enables a category filter to show and hide posts based on their categories. This does not load posts via AJAX, but rather filters items currently on the page.', 'total' ),
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
						'admin_label' => true,
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
						'type' => 'dropdown',
						'heading' => __( 'Image Links To', 'total' ),
						'param_name' => 'thumb_link',
						'value' => array(
							__( 'Post', 'total') => 'post',
							__( 'Lightbox', 'total' ) => 'lightbox',
							__( 'Nowhere', 'total' ) => 'nowhere',
						),
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
					array(
						'type' => 'vcex_overlay',
						'heading' => __( 'Image Overlay', 'total' ),
						'param_name' => 'overlay_style',
						'group' => $s_image,
						'dependency' => array( 'element' => 'entry_media', 'value' => 'true' ),
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
						'dependency' => array( 'element' => 'entry_media', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_image_filters',
						'heading' => __( 'Image Filter', 'total' ),
						'param_name' => 'img_filter',
						'group' => $s_image,
						'dependency' => array( 'element' => 'entry_media', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_lightbox_skins',
						'heading' => __( 'Lightbox Skin', 'total' ),
						'param_name' => 'lightbox_skin',
						'value' => vcex_ilightbox_skins(),
						'group' => $s_image,
						'dependency' => array( 'element' => 'thumb_link', 'value' => 'lightbox' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Lightbox Gallery', 'total' ),
						'param_name' => 'thumb_lightbox_gallery',
						'group' => $s_image,
						'dependency' => array( 'element' => 'thumb_link', 'value' => 'lightbox' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Lightbox Title', 'total' ),
						'param_name' => 'thumb_lightbox_title',
						'group' => $s_image,
						'dependency' => array( 'element' => 'thumb_link', 'value' => 'lightbox' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Lightbox Excerpt', 'total' ),
						'param_name' => 'thumb_lightbox_caption',
						'group' => $s_image,
						'dependency' => array( 'element' => 'thumb_link', 'value' => 'lightbox' ),
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
						'type' => 'dropdown',
						'heading' => __( 'Links To', 'total' ),
						'param_name' => 'title_link',
						'value' => array(
							__( 'Post', 'total') => 'post',
							__( 'Lightbox', 'total') => 'lightbox',
							__( 'Nowhere', 'total' ) => 'nowhere',
						),
						'group' => $s_title,
						'dependency' => array( 'element' => 'title', 'value' => 'true' ),
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
						'heading' => __( 'Line Height', 'total' ),
						'param_name' => 'content_heading_line_height',
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
					// Position
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => $s_enable,
						'param_name' => 'position',
						'group' => $s_position,
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Position Font Color', 'total' ),
						'param_name' => 'position_color',
						'group' => $s_position,
						'dependency' => array( 'element' => 'position', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Position Font Size', 'total' ),
						'param_name' => 'position_size',
						'group' => $s_position,
						'dependency' => array( 'element' => 'position', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __(  'Font Weight', 'total' ),
						'param_name' => 'position_weight',
						'group' => $s_position,
						'dependency' => array( 'element' => 'position', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Position Margin', 'total' ),
						'param_name' => 'position_margin',
						'group' => $s_position,
						'dependency' => array( 'element' => 'position', 'value' => 'true' ),
						'description' => __( 'Please use the following format: top right bottom left.', 'total' ),
					),
					// Categories
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => $s_enable,
						'param_name' => 'show_categories',
						'group' => $s_categories,
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Show Only The First Category?', 'total' ),
						'param_name' => 'show_first_category_only',
						'dependency' => array( 'element' => 'show_categories', 'value' => 'true' ),
						'group' => $s_categories,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'categories_font_size',
						'group' => $s_categories,
						'dependency' => array( 'element' => 'show_categories', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Padding', 'total' ),
						'param_name' => 'categories_margin',
						'group' => $s_categories,
						'dependency' => array( 'element' => 'show_categories', 'value' => 'true' ),
						'description' => __( 'Please use the following format: top right bottom left.', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'categories_color',
						'group' => $s_categories,
						'dependency' => array( 'element' => 'show_categories', 'value' => 'true' ),
					),
					// Social
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => $s_enable,
						'param_name' => 'social_links',
						'group' => $s_social,
					),
					array(
						'type' => 'vcex_social_button_styles',
						'heading' => __( 'Style', 'total' ),
						'param_name' => 'social_links_style',
						'std' => wpex_get_mod( 'staff_social_default_style', 'minimal-round' ),
						'group' => $s_social,
						'dependency' => array( 'element' => 'social_links', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'social_links_size',
						'group' => $s_social,
						'dependency' => array( 'element' => 'social_links', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Padding', 'total' ),
						'param_name' => 'social_links_margin',
						'group' => $s_social,
						'description' => __( 'Please use the following format: top right bottom left.', 'total' ),
						'dependency' => array( 'element' => 'social_links', 'value' => 'true' ),
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
						'group' => $s_excerpt,
						'description' => __( 'Enter how many words to display for the excerpt. To display the full post content enter "-1". To display the full post content up to the "more" tag enter "9999".', 'total' ),
						'dependency' => array( 'element' => 'excerpt', 'value' => 'true' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'content_color',
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
					// Button
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => $s_enable,
						'param_name' => 'read_more',
						'group' => __( 'Button', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Text', 'total' ),
						'param_name' => 'read_more_text',
						'value' => __( 'read more', 'total' ),
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_button_styles',
						'heading' => __( 'Style', 'total' ),
						'param_name' => 'readmore_style',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_button_colors',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'readmore_style_color',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Arrow', 'total' ),
						'param_name' => 'readmore_rarr',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'readmore_size',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Border Radius', 'total' ),
						'param_name' => 'readmore_border_radius',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Padding', 'total' ),
						'param_name' => 'readmore_padding',
						'description' => __( 'Please use the following format: top right bottom left.', 'total' ),
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Margin', 'total' ),
						'param_name' => 'readmore_margin',
						'description' => __( 'Please use the following format: top right bottom left.', 'total' ),
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background', 'total' ),
						'param_name' => 'readmore_background',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'readmore_color',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background: Hover', 'total' ),
						'param_name' => 'readmore_hover_background',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color: Hover', 'total' ),
						'param_name' => 'readmore_hover_color',
						'group' => __( 'Button', 'total' ),
						'dependency' => array( 'element' => 'read_more', 'value' => 'true' ),
					),
					// Content CSS
					array(
						'type' => 'css_editor',
						'heading' => $s_content_css,
						'param_name' => 'content_css',
						'group' => $s_content_css,
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Content Alignment', 'total' ),
						'param_name' => 'content_alignment',
						'value' => array(
							__( 'Default', 'total' ) => '',
							__( 'Left', 'total' ) => 'left',
							__( 'Right', 'total' ) => 'right',
							__( 'Center', 'total' ) => 'center',
						),
						'group' => $s_content_css,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Content Opacity', 'total' ),
						'param_name' => 'content_opacity',
						'description' => __( 'Enter a value between "0" and "1".', 'total' ),
						'group' => $s_content_css,
					),
					// Entry CSS
					array(
						'type' => 'css_editor',
						'heading' => $s_entry_css,
						'param_name' => 'entry_css',
						'group' => $s_entry_css,
					),
					// Hidden fields
					array( 'type' => 'hidden', 'param_name' => 'content_background' ),
					array( 'type' => 'hidden', 'param_name' => 'content_border' ),
					array( 'type' => 'hidden', 'param_name' => 'content_margin' ),
					array( 'type' => 'hidden', 'param_name' => 'content_padding' ),
				)
			);
		}

	}
}
new VCEX_Staff_Grid_Shortcode;