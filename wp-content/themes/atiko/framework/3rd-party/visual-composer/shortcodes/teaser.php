<?php
/**
 * Visual Composer Teaser
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.0
 */

if ( ! class_exists( 'VCEX_Teaser_Shortcode' ) ) {

	class VCEX_Teaser_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.5.0
		 */
		public function __construct() {
			
			// Add shortcode
			add_shortcode( 'vcex_teaser', array( 'VCEX_Teaser_Shortcode', 'output' ) );

			// Map to VC
			if ( function_exists( 'vc_lean_map' ) ) {
				vc_lean_map( 'vcex_teaser', array( 'VCEX_Teaser_Shortcode', 'map' ) );
			}

			// Admin filters
			if ( is_admin() ) {
				add_filter( 'vc_edit_form_fields_attributes_vcex_teaser', 'vcex_parse_image_size' );
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.0
		 */
		public static function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_teaser.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.0
		 */
		public static function map() {

			$s_heading = __( 'Heading', 'total' );

			return array(
				'name' => __( 'Teaser Box', 'total' ),
				'description' => __( 'A teaser content box', 'total' ),
				'base' => 'vcex_teaser',
				'category' => wpex_get_theme_branding(),
				'icon' => 'vcex-teaser vcex-icon fa fa-file-text-o',
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
						'type' => 'vcex_hover_animations',
						'heading' => __( 'Hover Animation', 'total'),
						'param_name' => 'hover_animation',
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Text Align', 'total' ),
						'param_name' => 'text_align',
						'value' => array(
							__( 'Default', 'total' ) => '',
							__( 'Center', 'total' ) => 'center',
							__( 'Left', 'total' ) => 'left',
							__( 'Right', 'total' ) => 'right',
						),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Style', 'total' ),
						'param_name' => 'style',
						'value' => array(
							__( 'Default', 'total' ) => '',
							__( 'Plain', 'total' ) => 'one',
							__( 'Boxed 1 - Legacy', 'total' ) => 'two',
							__( 'Boxed 2 - Legacy', 'total' ) => 'three',
							__( 'Outline - Legacy', 'total' ) => 'four',
						),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Padding', 'total' ),
						'param_name' => 'padding',
						'description' => __( 'Please use the following format: top right bottom left.', 'total' ),
						'dependency' => array( 'element' => 'style', 'value' => 'two' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background Color', 'total' ),
						'param_name' => 'background',
						'dependency' => array( 'element' => 'style', 'value' => array( 'two', 'three' ) ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Border Color', 'total' ),
						'param_name' => 'border_color',
						'dependency' => array( 'element' => 'style', 'value' => array( 'two', 'three', 'four' ) ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Border Radius', 'total' ),
						'param_name' => 'border_radius',
						'dependency' => array( 'element' => 'style', 'value' => array( 'two', 'three', 'four' ) ),
					),

					// Heading
					array(
						'type' => 'textfield',
						'heading' => $s_heading,
						'param_name' => 'heading',
						'value' => 'Sample Heading',
						'group' => $s_heading,
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Heading Color', 'total' ),
						'param_name' => 'heading_color',
						'group' => $s_heading,
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Heading Type', 'total' ),
						'param_name' => 'heading_type',
						'group' => $s_heading,
						'value' => array(
							'h2' => 'h2',
							'h3' => 'h3',
							'h4' => 'h4',
							'h5' => 'h5',
							'div' => 'div',
						),
					),
					array(
						'type'  => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'heading_font_family',
						'group' => $s_heading,
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Heading Font Weight', 'total' ),
						'param_name' => 'heading_weight',
						'group' => $s_heading,
					),
					array(
						'type' => 'vcex_text_transforms',
						'heading' => __( 'Heading Text Transform', 'total' ),
						'param_name' => 'heading_transform',
						'group' => $s_heading,
					),
					array(
						'type' => 'vcex_responsive_sizes',
						'target' => 'font-size',
						'heading' => __( 'Heading Font Size', 'total' ),
						'param_name' => 'heading_size',
						'group' => $s_heading,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Heading Margin', 'total' ),
						'param_name' => 'heading_margin',
						'description' => __( 'Please use the following format: top right bottom left.', 'total' ),
						'group' => $s_heading,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Heading Letter Spacing', 'total' ),
						'param_name' => 'heading_letter_spacing',
						'group' => $s_heading,
					),
					// Content
					array(
						'type' => 'textarea_html',
						'holder' => 'div',
						'heading' => __( 'Content', 'total' ),
						'param_name' => 'content',
						'value' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed faucibus feugiat convallis. Integer nec eros et risus condimentum tristique vel vitae arcu.',
						'group' => __( 'Content', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Content Margin', 'total' ),
						'param_name' => 'content_margin',
						'description' => __( 'Please use the following format: top right bottom left.', 'total' ),
						'group' => __( 'Content', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Content Padding', 'total' ),
						'param_name' => 'content_padding',
						'description' => __( 'Please use the following format: top right bottom left.', 'total' ),
						'group' => __( 'Content', 'total' ),
					),
					array(
						'type' => 'vcex_responsive_sizes',
						'target' => 'font-size',
						'heading' => __( 'Content Font Size', 'total' ),
						'param_name' => 'content_font_size',
						'group' => __( 'Content', 'total' ),
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Content Font Weight', 'total' ),
						'param_name' => 'content_font_weight',
						'group' => __( 'Content', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Content Font Color', 'total' ),
						'param_name' => 'content_color',
						'group' => __( 'Content', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Content Background', 'total' ),
						'param_name' => 'content_background',
						'group' => __( 'Content', 'total' ),
					),
					// Media
					array(
						'type' => 'attach_image',
						'heading' => __( 'Image', 'total' ),
						'param_name' => 'image',
						'group' => __( 'Media', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Video link', 'total' ),
						'param_name' => 'video',
						'description' => __( 'Enter in a video URL that is compatible with WordPress\'s built-in oEmbed feature.', 'total' ),
						'group' => __( 'Media', 'total' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Image Style', 'total' ),
						'param_name' => 'img_style',
						'value' => array(
							__( 'Default', 'total' ) => '',
							__( 'Stretch', 'total' ) => 'stretch',
						),
						'group' => __( 'Media', 'total' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Image Align', 'total' ),
						'param_name' => 'img_align',
						'value' => array(
							__( 'Default', 'total' ) => '',
							__( 'Left', 'total' ) => 'left',
							__( 'Center', 'total' ) => 'center',
							__( 'Right', 'total' ) => 'right',
						),
						'group' => __( 'Media', 'total' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Image Size', 'total' ),
						'param_name' => 'img_size',
						'std' => 'wpex_custom',
						'value' => vcex_image_sizes(),
						'group' => __( 'Media', 'total' ),
					),
					array(
						'type' => 'vcex_image_crop_locations',
						'heading' => __( 'Image Crop Location', 'total' ),
						'param_name' => 'img_crop',
						'std' => 'center-center',
						'group' => __( 'Media', 'total' ),
						'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Image Crop Width', 'total' ),
						'param_name' => 'img_width',
						'group' => __( 'Media', 'total' ),
						'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Image Crop Height', 'total' ),
						'param_name' => 'img_height',
						'description' => __( 'Enter a height in pixels. Leave empty to disable vertical cropping and keep image proportions.', 'total' ),
						'group' => __( 'Media', 'total' ),
						'dependency' => array( 'element' => 'img_size', 'value' => 'wpex_custom' ),
					),
					array(
						'type' => 'vcex_image_filters',
						'heading' => __( 'Image Filter', 'total' ),
						'param_name' => 'img_filter',
						'group' => __( 'Media', 'total' ),
					),
					array(
						'type' => 'vcex_image_hovers',
						'heading' => __( 'CSS3 Image Hover', 'total' ),
						'param_name' => 'img_hover_style',
						'group' => __( 'Media', 'total' ),
					),
					// Link
					array(
						'type' => 'vc_link',
						'heading' => __( 'URL', 'total' ),
						'param_name' => 'url',
						'group' => __( 'Link', 'total' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Link: Local Scroll', 'total' ),
						'param_name' => 'url_local_scroll',
						'group' => __( 'Link', 'total' ),
						'value' => array(
							__( 'False', 'total' ) => '',
							__( 'True', 'total' ) => 'true',
						),
					),
					// CSS
					array(
						'type' => 'css_editor',
						'heading' => __( 'Design Options', 'total' ),
						'param_name' => 'css',
						'group' => __( 'Design Options', 'total' ),
					),
				)
			);
		}

	}
}
new VCEX_Teaser_Shortcode;