<?php
/**
 * Visual Composer Searchbar
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 3.5.0
 */

if ( ! class_exists( 'VCEX_Searchbar_Shortcode' ) ) {

	class VCEX_Searchbar_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.5.0
		 */
		public function __construct() {
			
			// Add shortcode
			add_shortcode( 'vcex_searchbar', array( 'VCEX_Searchbar_Shortcode', 'output' ) );

			// Map to VC
			if ( function_exists( 'vc_lean_map' ) ) {
				vc_lean_map( 'vcex_searchbar', array( 'VCEX_Searchbar_Shortcode', 'map' ) );
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.0
		 */
		public static function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_searchbar.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.0
		 */
		public static function map() {
			return array(
				'name' => __( 'Search Bar', 'total' ),
				'description' => __( 'Custom search form', 'total' ),
				'base' => 'vcex_searchbar',
				'icon' => 'vcex-searchbar vcex-icon fa fa-search',
				'category' => wpex_get_theme_branding(),
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
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Full-Width on Mobile', 'total'),
						'param_name' => 'fullwidth_mobile',
					),
					// Query
					array(
						'type' => 'textfield',
						'heading' => __( 'Advanced Search', 'total' ),
						'param_name' => 'advanced_query',
						'group' => __( 'Query', 'total' ),
						'description' => __( 'Example: ', 'total' ) . 'post_type=portfolio&taxonomy=portfolio_category&term=advertising',
					),
					// Widths
					array(
						'type' => 'textfield',
						'heading' => __( 'Wrap Width', 'total' ),
						'param_name' => 'wrap_width',
						'group' => __( 'Widths', 'total' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Position', 'total' ),
						'param_name' => 'wrap_float',
						'group' => __( 'Widths', 'total' ),
						'dependency' => array( 'element' => 'wrap_width', 'not_empty' => true ),
						'value' => array(
							__( 'Left', 'total' )   => '',
							__( 'Center', 'total' ) => 'center',
							__( 'Right', 'total' )  => 'right',
						),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Input Width', 'total' ),
						'param_name' => 'input_width',
						'group' => __( 'Widths', 'total' ),
						'description' => '70%',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Button Width', 'total' ),
						'param_name' => 'button_width',
						'group' => __( 'Widths', 'total' ),
						'description' => '28%',
					),

					// Input
					array(
						'type' => 'textfield',
						'heading' => __( 'Placeholder', 'total' ),
						'param_name' => 'placeholder',
						'group' => __( 'Input', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'input_color',
						'group' => __( 'Input', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'input_font_size',
						'group' => __( 'Input', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Letter Spacing', 'total' ),
						'param_name' => 'input_letter_spacing',
						'group' => __( 'Input', 'total' ),
					),
					array(
						'type' => 'vcex_text_transforms',
						'heading' => __( 'Text Transform', 'total' ),
						'param_name' => 'input_text_transform',
						'group' => __( 'Input', 'total' ),
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'input_font_weight',
						'group' => __( 'Input', 'total' ),
					),
					array(
						'type' => 'css_editor',
						'heading' => __( 'Design', 'total' ),
						'param_name' => 'css',
						'group' => __( 'Input', 'total' ),
					),
					// Submit
					array(
						'type' => 'textfield',
						'heading' => __( 'Button Text', 'total' ),
						'param_name' => 'button_text',
						'group' => __( 'Submit', 'total' ),
					),
					array(
						'type' => 'vcex_text_transforms',
						'heading' => __( 'Text Transform', 'total' ),
						'param_name' => 'button_text_transform',
						'group' => __( 'Submit', 'total' ),
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'button_font_weight',
						'group' => __( 'Submit', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'button_font_size',
						'group' => __( 'Submit', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Letter Spacing', 'total' ),
						'param_name' => 'button_letter_spacing',
						'group' => __( 'Submit', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Border Radius', 'total' ),
						'param_name' => 'button_border_radius',
						'group' => __( 'Submit', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background', 'total' ),
						'param_name' => 'button_bg',
						'group' => __( 'Submit', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background: Hover', 'total' ),
						'param_name' => 'button_bg_hover',
						'group' => __( 'Submit', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'button_color',
						'group' => __( 'Submit', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color: Hover', 'total' ),
						'param_name' => 'button_color_hover',
						'group' => __( 'Submit', 'total' ),
					),
				)
			);
		}

	}
}
new VCEX_Searchbar_Shortcode;