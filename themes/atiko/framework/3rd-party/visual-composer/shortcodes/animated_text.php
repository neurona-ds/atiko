<?php
/**
 * Visual Composer Animated Text Shortcode
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.2
 */

if ( ! class_exists( 'VCEX_Animated_Text_Shortcode' ) ) {

	class VCEX_Animated_Text_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.5.0
		 */
		public function __construct() {
			
			// Add shortcode
			add_shortcode( 'vcex_animated_text', array( 'VCEX_Animated_Text_Shortcode', 'output' ) );

			// Map to VC
			if ( function_exists( 'vc_lean_map' ) ) {
				vc_lean_map( 'vcex_animated_text', array( 'VCEX_Animated_Text_Shortcode', 'map' ) );
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.0
		 */
		public static function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_animated_text.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.0
		 */
		public static function map() {

			$s_typo = __( 'Typography', 'total' );

			return array(
				'name' => __( 'Animated Text', 'total' ),
				'description' => __( 'Animated text', 'total' ),
				'base' => 'vcex_animated_text',
				'icon' => 'vcex-animated-text vcex-icon fa fa-text-width',
				'category' => wpex_get_theme_branding(),
				'params' => array(
					// General
					array(
						'type' => 'vcex_visibility',
						'heading' => __( 'Visibility', 'total' ),
						'param_name' => 'visibility',
					),
					vcex_vc_map_add_css_animation(),
					array(
						'type' => 'textfield',
						'heading' => __( 'Extra class name', 'total' ),
						'param_name' => 'el_class',
						'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total' ),
					),
					array(
						'type' => 'vcex_text_alignments',
						'heading' => __( 'Align', 'total' ),
						'param_name' => 'text_align',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Speed', 'total' ),
						'param_name' => 'speed',
						'std' => '0',
						'description' => __( 'Enter a value in milliseconds.', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Back Delay', 'total' ),
						'param_name' => 'back_delay',
						'std' => '500',
						'description' => __( 'Enter a value in milliseconds.', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Back Speed', 'total' ),
						'param_name' => 'back_speed',
						'std' => '0',
						'description' => __( 'Enter a value in milliseconds.', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Start Delay', 'total' ),
						'param_name' => 'start_delay',
						'std' => '0',
						'description' => __( 'Enter a value in milliseconds.', 'total' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Loop', 'total' ),
						'param_name' => 'loop',
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Cursor', 'total' ),
						'param_name' => 'type_cursor',
					),

					// Typography
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'color',
						'group' => $s_typo,
					),
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
						'type'  => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'font_family',
						'group' => $s_typo,
					),
					array(
						'type' => 'vcex_responsive_sizes',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'font_size',
						'group' => $s_typo,
						'target' => 'font-size',
					),
					array(
						'type' => 'vcex_font_weight',
						'param_name' => 'font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'group' => $s_typo,
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Font Style', 'total' ),
						'param_name' => 'font_style',
						'value' => array(
							__( 'Normal', 'total' ) => '',
							__( 'Italic', 'total' ) => 'italic',
						),
						'group' => $s_typo,
					),
					// Animated Text
					array(
						'type' => 'param_group',
						'param_name' => 'strings',
						'group' => __( 'Animated Text', 'total' ),
						'value' => urlencode( json_encode( array(
							array(
								'text' => __( 'Welcome', 'total' ),
							),
							array(
								'text' => __( 'Bienvenido', 'total' ),
							),
							array(
								'text' => __( 'Welkom', 'total' ),
							),
							array(
								'text' => __( 'Bienvenue', 'total' ),
							),
						) ) ),
						'params' => array(
							array(
								'type' => 'textfield',
								'heading' => __( 'Text', 'total' ),
								'param_name' => 'text',
								'admin_label' => true,
							),
						),
					),
					array(
						'type'  => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'animated_font_family',
						'dependency' => array( 'element' => 'static_text', 'value' => 'true' ),
						'group' => __( 'Animated Text', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'animated_color',
						'dependency' => array( 'element' => 'static_text', 'value' => 'true' ),
						'group' => __( 'Animated Text', 'total' ),
					),
					array(
						'type' => 'vcex_font_weight',
						'param_name' => 'animated_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'std' => '',
						'dependency' => array( 'element' => 'static_text', 'value' => 'true' ),
						'group' => __( 'Animated Text', 'total' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Font Style', 'total' ),
						'param_name' => 'animated_font_style',
						'value' => array(
							__( 'Normal', 'total' ) => '',
							__( 'Italic', 'total' ) => 'italic',
						),
						'dependency' => array( 'element' => 'static_text', 'value' => 'true' ),
						'group' => __( 'Animated Text', 'total' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Text Decoration', 'total' ),
						'param_name' => 'animated_text_decoration',
						'value' => array_flip( wpex_text_decorations() ),
						'dependency' => array( 'element' => 'static_text', 'value' => 'true' ),
						'group' => __( 'Animated Text', 'total' ),
					),
					array(
						'type' => 'css_editor',
						'heading' => __( 'CSS', 'total' ),
						'param_name' => 'animated_css',
						'group' => __( 'Animated Text', 'total' ),
					),
					
					// Static Text
					array(
						'type' => 'vcex_ofswitch',
						'heading' => __( 'Enable', 'total' ),
						'param_name' => 'static_text',
						'group' => __( 'Static Text', 'total' ),
						'std' => 'false',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Before', 'total' ),
						'param_name' => 'static_before',
						'group' => __( 'Static Text', 'total' ),
						'dependency' => array( 'element' => 'static_text', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'After', 'total' ),
						'param_name' => 'static_after',
						'group' => __( 'Static Text', 'total' ),
						'dependency' => array( 'element' => 'static_text', 'value' => 'true' ),
					),

					// Container Design
					array(
						'type' => 'css_editor',
						'heading' => __( 'Container Design', 'total' ),
						'param_name' => 'css',
						'group' => __( 'Container Design', 'total' ),
					),

				),
			);
		}
		
	}

}
new VCEX_Animated_Text_Shortcode;