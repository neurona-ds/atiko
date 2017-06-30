<?php
/**
 * Visual Composer Heading
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.0
 */

if ( ! class_exists( 'VCEX_Heading_Shortcode' ) ) {

	class VCEX_Heading_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.5.0
		 */
		public function __construct() {
			
			// Add shortcode
			add_shortcode( 'vcex_heading', array( 'VCEX_Heading_Shortcode', 'output' ) );

			// Map to VC
			if ( function_exists( 'vc_lean_map' ) ) {
				vc_lean_map( 'vcex_heading', array( 'VCEX_Heading_Shortcode', 'map' ) );
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.0
		 */
		public static function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_heading.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.0
		 */
		public static function map() {

			// Strings
			$s_yes  = __( 'Yes', 'total' );
			$s_no   = __( 'No', 'total' );
			$s_typo = __( 'Typography', 'total' );
			$s_link = __( 'Link', 'total' );

			// Return array
			return array(
				'name' => __( 'Heading', 'total' ),
				'description' => __( 'A better heading module', 'total' ),
				'base' => 'vcex_heading',
				'category' => wpex_get_theme_branding(),
				'icon' => 'vcex-heading vcex-icon fa fa-font',
				'params' => array(
					
					// General
					array(
						'type' => 'vcex_visibility',
						'heading' => __( 'Visibility', 'total' ),
						'param_name' => 'visibility',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Extra class name', 'total' ),
						'param_name' => 'el_class',
						'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total' ),
					),
					vcex_vc_map_add_css_animation(),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Text Source', 'total' ),
						'param_name' => 'source',
						'value' => array(
							__( 'Custom Text', 'total' ) => '',
							__( 'Post or Page Title', 'total' ) => 'post_title',
							__( 'Custom Field', 'total' ) => 'custom_field',
						),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Custom Field', 'total' ),
						'param_name' => 'custom_field',
						'dependency' => array( 'element' => 'source', 'value' => 'custom_field' ),
					),
					array(
						'type' => 'textarea_safe',
						'heading' => __( 'Text', 'total' ),
						'param_name' => 'text',
						'value' => __( 'Heading', 'total' ),
						'admin_label' => true, // Bad when user uses html.. ??
						'vcex_rows' => 2,
						'description' => __( 'HTML Supported', 'total' ),
						'dependency' => array( 'element' => 'source', 'is_empty' => true ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Accent Border Color', 'total' ),
						'param_name' => 'inner_bottom_border_color',
						'dependency' => array( 'element' => 'style', 'value' => 'bottom-border-w-color' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Border Color', 'total' ),
						'param_name' => 'inner_bottom_border_color_main',
						'dependency' => array( 'element' => 'style', 'value' => 'bottom-border-w-color' ),
					),

					// Typography
					array(
						'type' => 'dropdown',
						'heading' => __( 'Tag', 'total' ),
						'param_name' => 'tag',
						'value' => array(
							__( 'Default', 'total' ) => '',
							'h1' => 'h1',
							'h2' => 'h2',
							'h3' => 'h3',
							'h4' => 'h4',
							'h5' => 'h5',
							'div' => 'div',
							'span' => 'span',
						),
						'group' => $s_typo,
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'color',
						'group' => $s_typo,
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color: Hover', 'total' ),
						'param_name' => 'color_hover',
						'group' => $s_typo,
					),
					array(
						'type' => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'font_family',
						'group' => $s_typo,
					),
					array(
						'type' => 'vcex_responsive_sizes',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'font_size',
						'description' => __( 'You can enter a px or em value. Example 13px or 1em.', 'total' ),
						'group' => $s_typo,
						'target' => 'font-size',
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Auto Responsive Font Size', 'total' ),
						'param_name' => 'responsive_text',
						'group' => $s_typo,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Minimum Font Size', 'total' ),
						'param_name' => 'min_font_size',
						'dependency' => array( 'element' => 'responsive_text', 'value' => 'true' ),
						'group' => $s_typo,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Line Height', 'total' ),
						'param_name' => 'line_height',
						'group' => $s_typo,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Letter Spacing', 'total' ),
						'param_name' => 'letter_spacing',
						'group' => $s_typo,
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Italic', 'total' ),
						'param_name' => 'italic',
						'value' => array( $s_no => 'false', $s_yes => 'true' ),
						'group' => $s_typo,
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'font_weight',
						'group' => $s_typo,
					),
					array(
						'type' => 'vcex_text_alignments',
						'heading' => __( 'Text Align', 'total' ),
						'param_name' => 'text_align',
						'group' => $s_typo,
					),
					// Link
					array(
						'type' => 'vc_link',
						'heading' => __( 'URL', 'total' ),
						'param_name' => 'link',
						'group' => $s_link,
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Link: Local Scroll', 'total' ),
						'param_name' => 'link_local_scroll',
						'value' => array(
							__( 'No', 'total' ) => 'false',
							__( 'Yes', 'total' ) => 'true',
						),
						'group' => $s_link,
					),
					// Icon
					array(
						'type' => 'dropdown',
						'heading' => __( 'Icon library', 'total' ),
						'param_name' => 'icon_type',
						'description' => __( 'Select icon library.', 'total' ),
						'value' => array(
							__( 'Font Awesome', 'total' ) => 'fontawesome',
							__( 'Open Iconic', 'total' ) => 'openiconic',
							__( 'Typicons', 'total' ) => 'typicons',
							__( 'Entypo', 'total' ) => 'entypo',
							__( 'Linecons', 'total' ) => 'linecons',
							__( 'Pixel', 'total' ) => 'pixelicons',
						),
						'group' => __( 'Icon', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon', 'total' ),
						'param_name' => 'icon',
						'value' => '',
						'settings' => array(
							'emptyIcon' => true,
							'iconsPerPage' => 200,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'fontawesome' ),
						'group' => __( 'Icon', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon', 'total' ),
						'param_name' => 'icon_openiconic',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'openiconic',
							'iconsPerPage' => 200,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'openiconic' ),
						'group' => __( 'Icon', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon', 'total' ),
						'param_name' => 'icon_typicons',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'typicons',
							'iconsPerPage' => 200,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'typicons' ),
						'group' => __( 'Icon', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon', 'total' ),
						'param_name' => 'icon_entypo',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'entypo',
							'iconsPerPage' => 300,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'entypo' ),
						'group' => __( 'Icon', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon', 'total' ),
						'param_name' => 'icon_linecons',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'linecons',
							'iconsPerPage' => 200,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'linecons' ),
						'group' => __( 'Icon', 'total' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Position', 'total' ),
						'param_name' => 'icon_position',
						'value' => array(
							__( 'Left', 'total' ) => 'left',
							__( 'Right', 'total' )  => 'right',
						),
						'group' => __( 'Icon', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'icon_color',
						'group' => __( 'Icon', 'total' ),
					),

					// Design
					array(
						'type' => 'dropdown',
						'heading' => __( 'Style', 'total' ),
						'param_name' => 'style',
						'value' => array(
							__( 'Plain', 'total' ) => 'plain',
							__( 'Bottom Border With Color', 'total' ) => 'bottom-border-w-color',
							__( 'Graphical', 'total' ) => 'graphical',
						),
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'css_editor',
						'heading' => __( 'CSS', 'total' ),
						'param_name' => 'css',
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Add Design to Inner Span', 'total' ),
						'param_name' => 'add_css_to_inner',
						'group' => __( 'Design', 'total' ),
						'description' => __( 'Enable to add the background, padding, border, etc only around your text and icons and not the whole heading container.', 'total' ),
						'dependency' => array( 'element' => 'style', 'value' => 'plain' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Width', 'total' ),
						'param_name' => 'width',
						'description' => __( 'Enter a custom width instead of using breaks to slim down your content width.', 'total' ),
						'group' => __( 'Design', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background: Hover', 'total' ),
						'param_name' => 'background_hover',
						'group' => __( 'Design', 'total' ),
						'dependency' => array( 'element' => 'style', 'value' => 'plain' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'White Text On Hover', 'total' ),
						'param_name' => 'hover_white_text',
						'value' => array(
							$s_no => 'false',
							$s_yes => 'true',
						),
						'group' => __( 'Design', 'total' ),
						'dependency' => array( 'element' => 'style', 'value' => 'plain' ),
					),
				)

			);
		}

	}
}
new VCEX_Heading_Shortcode;