<?php
/**
 * Visual Composer Countdown
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.0
 */

if ( ! class_exists( 'VCEX_Countdown_Shortcode' ) ) {

	class VCEX_Countdown_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.5.3
		 */
		public function __construct() {
			
			// Add shortcode
			add_shortcode( 'vcex_countdown', array( 'VCEX_Countdown_Shortcode', 'output' ) );

			// Map to VC
			if ( function_exists( 'vc_lean_map' ) ) {
				vc_lean_map( 'vcex_countdown', array( 'VCEX_Countdown_Shortcode', 'map' ) );
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.3
		 */
		public static function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_countdown.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.3
		 */
		public static function map() {

			// Strings
			$s_yes  = __( 'Yes', 'total' );
			$s_no   = __( 'No', 'total' );
			$s_typo = __( 'Typography', 'total' );

			// Array
			return array(
				'name' => __( 'Countdown', 'total' ),
				'description' => __( 'Animated countdown clock', 'total' ),
				'base' => 'vcex_countdown',
				'icon' => 'vcex-countdown vcex-icon fa fa-clock-o',
				'category' => wpex_get_theme_branding(),
				'params' => array(
					// General
					array(
						'type' => 'textfield',
						'admin_label' => true,
						'heading' => __( 'Extra class name', 'total' ),
						'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total' ),
						'param_name' => 'el_class',
					),
					array(
						'type' => 'vcex_visibility',
						'heading' => __( 'Visibility', 'total' ),
						'param_name' => 'visibility',
					),
					vcex_vc_map_add_css_animation(),
					array(
						'type' => 'textfield',
						'heading' => __( 'End Year', 'total' ),
						'param_name' => 'end_year',
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'End Month', 'total' ),
						'param_name' => 'end_month',
						'value' => array( '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12' ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'End Day', 'total' ),
						'param_name' => 'end_day',
						'value' => array( '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31' ),
					),

					// Typography
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'color',
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
						'target' => 'font-size',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'font_size',
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
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Italic', 'total' ),
						'param_name' => 'italic',
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

					// Translations
					array(
						'type' => 'textfield',
						'heading' => __( 'Days', 'total' ),
						'param_name' => 'days',
						'group' =>  __( 'Strings', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Hours', 'total' ),
						'param_name' => 'hours',
						'group' =>  __( 'Strings', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Minutes', 'total' ),
						'param_name' => 'minutes',
						'group' =>  __( 'Strings', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Seconds', 'total' ),
						'param_name' => 'seconds',
						'group' =>  __( 'Strings', 'total' ),
					),
				)
			);
		}

	}
}
new VCEX_Countdown_Shortcode;