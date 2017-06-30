<?php
/**
 * Visual Composer Post Terms
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.0
 */

if ( ! class_exists( 'VCEX_Post_Terms_Shortcode' ) ) {

	class VCEX_Post_Terms_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.6.0
		 */
		public function __construct() {
			
			// Add main shortcode
			add_shortcode( 'vcex_post_terms', array( 'VCEX_Post_Terms_Shortcode', 'output' ) );

			// Add grid item shortcode @todo
			//add_shortcode( 'vcex_gitem_post_terms', array( 'VCEX_Post_Terms_Shortcode', 'gitem_output' ) );

			// Map to VC
			if ( function_exists( 'vc_lean_map' ) ) {
				vc_lean_map( 'vcex_post_terms', array( 'VCEX_Post_Terms_Shortcode', 'map' ) );
			}

			// Admin filters
			if ( is_admin() ) {

				// Suggest tax
				add_filter( 'vc_autocomplete_vcex_post_terms_taxonomy_callback', 'vcex_suggest_taxonomies', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_post_terms_taxonomy_render', 'vcex_render_taxonomies', 10, 1 );

				// Suggest terms
				add_filter( 'vc_autocomplete_vcex_post_terms_exclude_terms_callback', 'vcex_suggest_terms', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_post_terms_exclude_terms_render', 'vcex_render_terms', 10, 1 );

			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.6.0
		 */
		public static function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_post_terms.php' ) );
			return ob_get_clean();
		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.6.0
		 */
		public static function gitem_output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_post_terms.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.6.0
		 */
		public static function map() {
			
			// Strings
			$s_enable = __( 'Enable', 'total' );
			$s_link   = __( 'Link', 'total' );
			$s_design = __( 'Design', 'total' );

			// Return array
			return array(
				'name' => __( 'Post Terms', 'total' ),
				'description' => __( 'Display your post terms.', 'total' ),
				'base' => 'vcex_post_terms',
				'category' => wpex_get_theme_branding(),
				'icon' => 'vcex-post-terms vcex-icon fa fa-folder',
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
						'admin_label' => true,
						'heading' => __( 'Extra class name', 'total' ),
						'param_name' => 'classes',
						'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total' ),
					),
					vcex_vc_map_add_css_animation(),
					array(
						'type' => 'vcex_visibility',
						'heading' => __( 'Visibility', 'total' ),
						'param_name' => 'visibility',
					),
					array(
						'type' => 'autocomplete',
						'heading' => __( 'Taxonomy', 'total' ),
						'param_name' => 'taxonomy',
						'admin_label' => true,
						'std' => '',
						'settings' => array(
							'multiple' => false,
							'min_length' => 1,
							'groups' => false,
							'unique_values' => true,
							'display_inline' => true,
							'delay' => 0,
							'auto_focus' => true,
						),
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
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Order', 'total' ),
						'param_name' => 'order',
						'value' => array(
							__( 'ASC', 'total' ) => 'ASC',
							__( 'DESC', 'total' ) => 'DESC',					),
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
					),
					// Link
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Link to Archive?', 'total' ),
						'param_name' => 'archive_link',
						'group' => $s_link,
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Link Target', 'total' ),
						'param_name' => 'target',
						'value' => array(
							__( 'Self', 'total' ) => '',
							__( 'Blank', 'total' ) => 'blank',
						),
						'group' => $s_link,
					),
					// Design
					array(
						'type' => 'vcex_button_styles',
						'heading' => __( 'Style', 'total' ),
						'param_name' => 'button_style',
						'group' => $s_design,
					),
					array(
						'type' => 'vcex_button_colors',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'button_color_style',
						'group' => $s_design,
					),
					array(
						'type' => 'vcex_text_alignments',
						'heading' => __( 'Align', 'total' ),
						'param_name' => 'button_align',
						'group' => $s_design,
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Size', 'total' ),
						'param_name' => 'button_size',
						'std' => '',
						'value' => array(
							__( 'Default', 'total' ) => '',
							__( 'Small', 'total' ) => 'small',
							__( 'Medium', 'total' ) => 'medium',
							__( 'Large', 'total' ) => 'large',
						),
						'group' => $s_design,
					),
					array(
						'type' => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'button_font_family',
						'group' => $s_design,
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background', 'total' ),
						'param_name' => 'button_background',
						'group' => $s_design,
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background: Hover', 'total' ),
						'param_name' => 'button_hover_background',
						'group' => $s_design,
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'button_color',
						'group' => $s_design,
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color: Hover', 'total' ),
						'param_name' => 'button_hover_color',
						'group' => $s_design,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'button_font_size',
						'group' => $s_design,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Letter Spacing', 'total' ),
						'param_name' => 'button_letter_spacing',
						'group' => $s_design,
					),
					array(
						'type' => 'vcex_text_transforms',
						'heading' => __( 'Text Transform', 'total' ),
						'param_name' => 'button_text_transform',
						'group' => $s_design,
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'button_font_weight',
						'group' => $s_design,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Border Radius', 'total' ),
						'param_name' => 'button_border_radius',
						'description' => __( 'Please enter a px value.', 'total' ),
						'group' => $s_design,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Padding', 'total' ),
						'param_name' => 'button_padding',
						'description' => __( 'Please use the following format: top right bottom left.', 'total' ),
						'group' => $s_design,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Margin', 'total' ),
						'param_name' => 'button_margin',
						'description' => __( 'Please use the following format: top right bottom left.', 'total' ),
						'group' => $s_design,
					),
				)
			);
		}

	}
}
new VCEX_Post_Terms_Shortcode;