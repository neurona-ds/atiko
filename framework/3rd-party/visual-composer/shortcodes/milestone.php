<?php
/**
 * Visual Composer Milestone
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.0
 */

if ( ! class_exists( 'VCEX_Milestone_Shortcode' ) ) {

	class VCEX_Milestone_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.5.0
		 */
		public function __construct() {
			
			// Add shortcode
			add_shortcode( 'vcex_milestone', array( 'VCEX_Milestone_Shortcode', 'output' ) );

			// Map to VC
			if ( function_exists( 'vc_lean_map' ) ) {
				vc_lean_map( 'vcex_milestone', array( 'VCEX_Milestone_Shortcode', 'map' ) );
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.0
		 */
		public static function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_milestone.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.0
		 */
		public static function map() {
			$s_number  = __( 'Number', 'total' );
			$s_caption = __( 'Caption', 'total' );
			$s_design  = __( 'Design options', 'total' );
			return array(
				'name' => __( 'Milestone', 'total' ),
				'description' => __( 'Animated counter', 'total' ),
				'base' => 'vcex_milestone',
				'icon' => 'vcex-milestone vcex-icon fa fa-medium',
				'category' => wpex_get_theme_branding(),
				'params' => array(
					// General
					array(
						'type' => 'textfield',
						'admin_label' => true,
						'heading' => __( 'Unique Id', 'total' ),
						'param_name' => 'unique_id',
					),
					array(
						'type' => 'textfield',
						'admin_label' => true,
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
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Animated', 'total' ),
						'param_name' => 'animated',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Speed', 'total' ),
						'param_name' => 'speed',
						'value' => '2500',
						'description' => __( 'The number of milliseconds it should take to finish counting.','total'),
					),
					// Number
					array(
						'type' => 'textfield',
						'admin_label' => true,
						'heading' => $s_number,
						'param_name' => 'number',
						'std' => '45',
						'group' => $s_number,
						'description' => __( 'Enter a PHP function name if you would like to return a dynamic number based on a custom function', 'total' )
						//'dependency' => array( 'element' => 'number_type', 'value' => 'static' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Decimal Places', 'total' ),
						'param_name' => 'decimals',
						'value' => '0',
						'group' => $s_number,
						//'dependency' => array( 'element' => 'number_type', 'value' => 'static' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Before', 'total' ),
						'param_name' => 'before',
						'group' => $s_number,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'After', 'total' ),
						'param_name' => 'after',
						'default' => '%',
						'group' => $s_number,
					),
					array(
						'type'  => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'number_font_family',
						'group' => $s_number,
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'number_color',
						'group' => $s_number,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'number_size',
						'group' => $s_number,
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'number_weight',
						'group' => $s_number,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Bottom Margin', 'total' ),
						'param_name' => 'number_bottom_margin',
						'group' => $s_number,
					),
					// caption
					array(
						'type' => 'textfield',
						'class' => 'vcex-animated-counter-caption',
						'heading' => $s_caption,
						'param_name' => 'caption',
						'value' => 'Awards Won',
						'admin_label' => true,
						'group' => $s_caption,
					),
					array(
						'type'  => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'caption_font_family',
						'group' => $s_caption,
					),
					array(
						'type' => 'colorpicker',
						'heading' => __(  'Color', 'total' ),
						'param_name' => 'caption_color',
						'group' => $s_caption,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'caption_size',
						'group' => $s_caption,
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'caption_font',
						'group' => $s_caption,
					),
					// Link
					array(
						'type' => 'textfield',
						'heading' => __( 'URL', 'total' ),
						'param_name' => 'url',
						'group' => __( 'Link', 'total' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'URL Target', 'total' ),
						'param_name' => 'url_target',
						'value' => array(
							__( 'Self', 'total') => 'self',
							__( 'Blank', 'total' ) => 'blank',
						),
						'group' => __( 'Link', 'total' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'URl Rel', 'total' ),
						'param_name' => 'url_rel',
						'value' => array(
							__( 'None', 'total') => '',
							__( 'Nofollow', 'total' ) => 'nofollow',
						),

						'group' => __( 'Link', 'total' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Link Container Wrap', 'total' ),
						'param_name' => 'url_wrap',
						'group' => __( 'Link', 'total' ),
						'description' => __( 'Apply the link to the entire wrapper?', 'total' ),
					),
					// CSS
					array(
						'type' => 'css_editor',
						'heading' => __( 'Design', 'total' ),
						'param_name' => 'css',
						'group' => $s_design,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Width', 'total' ),
						'param_name' => 'width',
						'group' => $s_design,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Border Radius', 'total' ),
						'param_name' => 'border_radius',
						'group' => $s_design,
					),
				)
			);
		}

	}
}
new VCEX_Milestone_Shortcode;