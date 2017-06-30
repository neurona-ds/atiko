<?php
/**
 * Visual Composer Terms Grid
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.0
 */

if ( ! class_exists( 'VCEX_Terms_Grid_Shortcode' ) ) {

	class VCEX_Terms_Grid_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.5.0
		 */
		public function __construct() {
			
			// Add shortcode
			add_shortcode( 'vcex_terms_grid', array( 'VCEX_Terms_Grid_Shortcode', 'output' ) );

			// Map to VC
			if ( function_exists( 'vc_lean_map' ) ) {
				vc_lean_map( 'vcex_terms_grid', array( 'VCEX_Terms_Grid_Shortcode', 'map' ) );
			}

			// Admin filters
			if ( is_admin() ) {

				// Edit forms on open
				add_filter( 'vc_edit_form_fields_attributes_vcex_terms_grid', array( 'VCEX_Terms_Grid_Shortcode', 'edit_form_fields' ) );

				// Suggest tax
				add_filter( 'vc_autocomplete_vcex_terms_grid_taxonomy_callback', 'vcex_suggest_taxonomies', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_terms_grid_taxonomy_render', 'vcex_render_taxonomies', 10, 1 );

				// Suggest terms
				add_filter( 'vc_autocomplete_vcex_terms_grid_exclude_terms_callback', 'vcex_suggest_terms', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_terms_grid_exclude_terms_render', 'vcex_render_terms', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_terms_grid_child_of_callback', 'vcex_suggest_terms', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_terms_grid_child_of_render', 'vcex_render_terms', 10, 1 );

			}

		}

		/**
		 * Edit form fields
		 *
		 * @since 3.5.0
		 */
		public static function edit_form_fields( $atts ) {
			$title_typo = ! empty( $atts['title_typo'] ) ? vcex_parse_typography_param( $atts['title_typo'] ) : '';
			if ( $title_typo && ! empty( $title_typo['font_family'] ) ) {
				$atts['title_font_family'] = $title_typo['font_family'];
			}
			$description_typo = ! empty( $atts['description_typo'] ) ? vcex_parse_typography_param( $atts['description_typo'] ) : '';
			if ( $description_typo && ! empty( $description_typo['font_family'] ) ) {
				$atts['description_font_family'] = $description_typo['font_family'];
			}
			return $atts;
		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.0
		 */
		public static function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_terms_grid.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.0
		 */
		public static function map() {
			
			// Strings
			$s_enable = __( 'Enable', 'total' );
			$s_yes    = __( 'Yes', 'total' );
			$s_no     = __( 'No', 'total' );
			$s_query  = __( 'Query', 'total' );
			$s_image  = __( 'Image', 'total' );
			$s_title  = __( 'Title', 'total' );
			
			// Return array
			return array(
				'name' => __( 'Categories Grid', 'total' ),
				'description' => __( 'Displays a grid of terms', 'total' ),
				'base' => 'vcex_terms_grid',
				'category' => wpex_get_theme_branding(),
				'icon' => 'vcex-terms-grid vcex-icon fa fa-th-large',
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
						'heading' => __( 'Custom Classes', 'total' ),
						'param_name' => 'classes',
						'admin_label' => true,
					),
					vcex_vc_map_add_css_animation(),
					array(
						'type' => 'vcex_visibility',
						'heading' => __( 'Visibility', 'total' ),
						'param_name' => 'visibility',
					),
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
						'value' => array( $s_yes => 'true', $s_no => 'false' ),
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
						'type' => 'autocomplete',
						'heading' => __( 'Taxonomy', 'total' ),
						'param_name' => 'taxonomy',
						'admin_label' => true,
						'std' => 'category',
						'settings' => array(
							'multiple' => false,
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
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Parent Terms Only', 'total' ),
						'param_name' => 'parent_terms',
						'value' => array( $s_no => false, $s_yes => true ),
						'group' => $s_query,
					),
					array(
						'type' => 'autocomplete',
						'heading' => __( 'Child Of', 'total' ),
						'param_name' => 'child_of',
						'settings' => array(
							'multiple' => true,
							'min_length' => 1,
							'groups' => true,
							'display_inline' => true,
							'delay' => 0,
							'auto_focus' => true,
						),
						'group' => $s_query,
					),
					array(
						'type' => 'autocomplete',
						'heading' => __( 'Exclude terms', 'total' ),
						'param_name' => 'exclude_terms',
						'settings' => array(
							'multiple' => true,
							'min_length' => 1,
							'groups' => true,
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
							__( 'ASC', 'total' ) => 'ASC',
							__( 'DESC', 'total' ) => 'DESC',
						),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Order By', 'total' ),
						'param_name' => 'orderby',
						'value' => array(
							__( 'Name', 'total' ) => 'name',
							__( 'Slug', 'total' ) => 'slug',
							__( 'Term Group', 'total' ) => 'term_group',
							__( 'Term ID', 'total' ) => 'term_id',
							'ID' => 'id',
							__( 'Description', 'total' ) => 'description',
						),
						'group' => $s_query,
					),
					// Image
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => $s_enable,
						'param_name' => 'img',
						'group' => $s_image,
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Image Size', 'total' ),
						'param_name' => 'img_size',
						'std' => 'full',
						'value' => vcex_image_sizes(),
						'group' => $s_image,
						'dependency' => array( 'element' => 'img', 'value' => 'true' ),
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
						'description' => __( 'Enter a width in pixels.', 'total' ),
						'group' => $s_image,
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
						'type' => 'vcex_image_hovers',
						'heading' => __( 'Image Hover', 'total' ),
						'param_name' => 'img_hover_style',
						'group' => $s_image,
						'dependency' => array( 'element' => 'img', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_image_filters',
						'heading' => __( 'Image Filter', 'total' ),
						'param_name' => 'img_filter',
						'group' => $s_image,
						'dependency' => array( 'element' => 'img', 'value' => 'true' ),
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
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Overlay Title', 'total' ),
						'param_name' => 'title_overlay',
						'dependency' => array( 'element' => 'title', 'value' => 'true' ),
						'group' => $s_title,
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Display Term Count', 'total' ),
						'param_name' => 'term_count',
						'std' => 'false',
						'value' => array( $s_no => 'false', $s_yes => 'true' ),
						'group' => $s_title,
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
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'title_font_weight',
						'group' => $s_title,
						'dependency' => array( 'element' => 'title', 'value' => 'true' ),
					),
					array(
						'type' => 'font_container',
						'param_name' => 'title_typo',
						'group' => $s_title,
						'settings' => array(
							'fields' => array(
								'tag' => 'span',
								'text_align',
								'font_size',
								'line_height',
								'color',
								'font_style_italic',
								'font_style_bold',
								//'font_family',
							),
						),
						'dependency' => array( 'element' => 'title', 'value' => 'true' ),
					),
					array(
						'type'  => 'textfield',
						'heading' => __( 'Bottom Margin', 'total' ),
						'param_name' => 'title_bottom_margin',
						'group' => $s_title,
						'dependency' => array( 'element' => 'title', 'value' => 'true' ),
					),
					// Description
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => $s_enable,
						'param_name' => 'description',
						'group' => __( 'Description', 'total' ),
						'dependency' => array( 'element' => 'title_overlay', 'value' => 'false' ),
					),
					array(
						'type'  => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'description_font_family',
						'group' => __( 'Description', 'total' ),
						'dependency' => array( 'element' => 'description', 'value' => 'true' ),
					),
					array(
						'type' => 'font_container',
						'param_name' => 'description_typo',
						'group' => __( 'Description', 'total' ),
						'settings' => array(
							'fields' => array(
								'font_size',
								'text_align',
								'line_height',
								'color',
								'font_style_italic',
								'font_style_bold',
								//'font_family',
							),
						),
						'dependency' => array( 'element' => 'description', 'value' => 'true' ),
					),
					array(
						'type' => 'css_editor',
						'heading' => __( 'CSS', 'total' ),
						'param_name' => 'entry_css',
						'group' => __( 'Entry CSS', 'total' ),
					),
				)
			);
		}

	}
}
new VCEX_Terms_Grid_Shortcode;