<?php
/**
 * Visual Composer Terms Carousel
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.0
 */

if ( ! class_exists( 'VCEX_Terms_Carousel_Shortcode' ) ) {

	class VCEX_Terms_Carousel_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.5.3
		 */
		public function __construct() {
			
			// Add shortcode
			add_shortcode( 'vcex_terms_carousel', array( 'VCEX_Terms_Carousel_Shortcode', 'output' ) );

			// Map to VC
			if ( function_exists( 'vc_lean_map' ) ) {
				vc_lean_map( 'vcex_terms_carousel', array( 'VCEX_Terms_Carousel_Shortcode', 'map' ) );
			}

			// Admin filters
			if ( is_admin() ) {
				add_filter( 'vc_autocomplete_vcex_terms_carousel_exclude_terms_callback', 'vcex_suggest_terms', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_terms_carousel_exclude_terms_render', 'vcex_render_terms', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_terms_carousel_taxonomy_callback', 'vcex_suggest_taxonomies', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_terms_carousel_taxonomy_render', 'vcex_render_taxonomies', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_terms_carousel_child_of_callback', 'vcex_suggest_terms', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_terms_carousel_child_of_render', 'vcex_render_terms', 10, 1 );
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.3
		 */
		public static function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_terms_carousel.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.3
		 */
		public static function map() {

			// Strings
			$s_enable = __( 'Enable', 'total' );

			// Return array
			return array(
				'name' => __( 'Categories Carousel', 'total' ),
				'description' => __( 'Carousel of taxonomy terms', 'total' ),
				'base' => 'vcex_terms_carousel',
				'category' => wpex_get_theme_branding(),
				'icon' => 'vcex-terms-carousel vcex-icon fa fa-th-large',
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
					array(
						'type' => 'textfield',
						'heading' => __( 'Items To Display', 'total' ),
						'param_name' => 'items',
						'value' => '4',
						'admin_label' => true,
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
						'group' => __( 'Query', 'total' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Parent Terms Only', 'total' ),
						'param_name' => 'parent_terms',
						'group' => __( 'Query', 'total' ),
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
						'group' => __( 'Query', 'total' ),
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
						'group' => __( 'Query', 'total' ),
					),
					// Image
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => $s_enable,
						'param_name' => 'img',
						'group' => __( 'Image', 'total' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Image Size', 'total' ),
						'param_name' => 'img_size',
						'std' => 'full',
						'value' => vcex_image_sizes(),
						'group' => __( 'Image', 'total' ),
						'dependency' => array( 'element' => 'img', 'value' => 'true' ),
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
						'description' => __( 'Enter a width in pixels.', 'total' ),
						'group' => __( 'Image', 'total' ),
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
						'type' => 'vcex_image_hovers',
						'heading' => __( 'Image Hover', 'total' ),
						'param_name' => 'img_hover_style',
						'group' => __( 'Image', 'total' ),
						'dependency' => array( 'element' => 'img', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_image_filters',
						'heading' => __( 'Image Filter', 'total' ),
						'param_name' => 'img_filter',
						'group' => __( 'Image', 'total' ),
						'dependency' => array( 'element' => 'img', 'value' => 'true' ),
					),
					// Title
					array(
					'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => $s_enable,
						'param_name' => 'title',
						'std' => 'true',
						'group' => __( 'Title', 'total' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Overlay Title', 'total' ),
						'param_name' => 'title_overlay',
						'dependency' => array( 'element' => 'title', 'value' => 'true' ),
						'group' => __( 'Title', 'total' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Display Term Count', 'total' ),
						'param_name' => 'term_count',
						'group' => __( 'Title', 'total' ),
						'dependency' => array( 'element' => 'title', 'value' => 'true' ),
					),
					array(
						'type'  => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'title_font_family',
						'group' => __( 'Title', 'total' ),
						'dependency' => array( 'element' => 'title', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'title_font_weight',
						'group' => __( 'Title', 'total' ),
						'dependency' => array( 'element' => 'title', 'value' => 'true' ),
					),
					array(
						'type' => 'font_container',
						'param_name' => 'title_typo',
						'group' => __( 'Title', 'total' ),
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
						'group' => __( 'Title', 'total' ),
						'dependency' => array( 'element' => 'title', 'value' => 'true' ),
					),
					// Description
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => $s_enable,
						'param_name' => 'description',
						'std' => 'true',
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
				),
			);
		}

	}
}
new VCEX_Terms_Carousel_Shortcode;