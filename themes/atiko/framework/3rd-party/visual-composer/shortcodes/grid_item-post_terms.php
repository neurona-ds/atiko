<?php
/**
 * Visual Composer Grid Item Post Terms
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.2
 *
 */

if ( ! class_exists( 'VCEX_Post_Terms_Grid_Item_Shortcode' ) ) {

	class VCEX_Post_Terms_Grid_Item_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 4.2
		 */
		public function __construct() {
			
			add_shortcode( 'vcex_gitem_post_terms', array( 'VCEX_Post_Terms_Grid_Item_Shortcode', 'add_shortcode' ) );
			
			add_filter( 'vc_grid_item_shortcodes', array( 'VCEX_Post_Terms_Grid_Item_Shortcode', 'vc_map' ) );
			
			add_filter( 'vc_gitem_template_attribute_vcex_gitem_post_terms', array( 'VCEX_Post_Terms_Grid_Item_Shortcode', 'template' ), 10, 2 );

			// Admin filters
			if ( is_admin() ) {

				// Suggest tax
				add_filter( 'vc_autocomplete_vcex_gitem_post_terms_taxonomy_callback', 'vcex_suggest_taxonomies', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_gitem_post_terms_taxonomy_render', 'vcex_render_taxonomies', 10, 1 );

				// Suggest terms
				add_filter( 'vc_autocomplete_vcex_gitem_post_terms_exclude_terms_callback', 'vcex_suggest_terms', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_gitem_post_terms_exclude_terms_render', 'vcex_render_terms', 10, 1 );

			}


		}

		/**
		 * Add Shortcde
		 *
		 * @since 4.2
		 */
		public static function add_shortcode( $atts ) {
			return '{{ vcex_gitem_post_terms:' . http_build_query( (array) $atts ) . ' }}';
		}

		/**
		 * Map Shortcode to VC
		 *
		 * @since 4.2
		 */
		public static function vc_map( $shortcodes ) {

			$s_enable = __( 'Enable', 'total' );
			$s_link   = __( 'Link', 'total' );
			$s_typo   = __( 'Typopgraphy', 'total' );

			$shortcodes['vcex_gitem_post_terms'] = array(
				'name'        => __( 'Post Terms', 'total' ),
				'base'        => 'vcex_gitem_post_terms',
				'icon'        => 'vcex-gitem-post-terms vcex-icon fa fa-folder',
				'category'    => WPEX_THEME_BRANDING,
				'description' => __( 'Display your post terms.', 'total' ),
				'post_type'   => Vc_Grid_Item_Editor::postType(),
				'params'      => array(
					array(
						'type' => 'textfield',
						'heading' => __( 'Post ID', 'total' ),
						'param_name' => 'post_id',
						'description' => __( 'Leave empty to use current post or post in loop.', 'total' ),
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
						'group' => $s_typo,
					),
					array(
						'type' => 'vcex_button_colors',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'button_color_style',
						'group' => $s_typo,
					),
					array(
						'type' => 'vcex_text_alignments',
						'heading' => __( 'Align', 'total' ),
						'param_name' => 'button_align',
						'group' => $s_typo,
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
						'group' => $s_typo,
					),
					array(
						'type' => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'button_font_family',
						'group' => $s_typo,
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background', 'total' ),
						'param_name' => 'button_background',
						'group' => $s_typo,
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background: Hover', 'total' ),
						'param_name' => 'button_hover_background',
						'group' => $s_typo,
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'button_color',
						'group' => $s_typo,
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color: Hover', 'total' ),
						'param_name' => 'button_hover_color',
						'group' => $s_typo,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'button_font_size',
						'group' => $s_typo,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Letter Spacing', 'total' ),
						'param_name' => 'button_letter_spacing',
						'group' => $s_typo,
					),
					array(
						'type' => 'vcex_text_transforms',
						'heading' => __( 'Text Transform', 'total' ),
						'param_name' => 'button_text_transform',
						'group' => $s_typo,
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'button_font_weight',
						'group' => $s_typo,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Border Radius', 'total' ),
						'param_name' => 'button_border_radius',
						'description' => __( 'Please enter a px value.', 'total' ),
						'group' => $s_typo,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Padding', 'total' ),
						'param_name' => 'button_padding',
						'description' => __( 'Please use the following format: top right bottom left.', 'total' ),
						'group' => $s_typo,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Margin', 'total' ),
						'param_name' => 'button_margin',
						'description' => __( 'Please use the following format: top right bottom left.', 'total' ),
						'group' => $s_typo,
					),
					array(
						'type' => 'css_editor',
						'heading' => __( 'CSS', 'total' ),
						'param_name' => 'css',
						'group' => __( 'Design Options', 'total' ),
					),
				)
			);
			return $shortcodes;
		}

		/**
		 * Display template
		 *
		 * @since 4.2
		 */
		public static function template( $value, $data ) {

			extract( array_merge( array(
				'output' => '',
				'post'   => null,
				'data'   => '',
			), $data ) );

			$atts = array();

			parse_str( $data, $atts );

			$atts = vc_map_get_attributes( 'vcex_gitem_post_terms', $atts );

			ob_start();
			include( locate_template( 'vcex_templates/vcex_gitem_post_terms.php' ) );
			return ob_get_clean();

		}

	}
}
new VCEX_Post_Terms_Grid_Item_Shortcode;