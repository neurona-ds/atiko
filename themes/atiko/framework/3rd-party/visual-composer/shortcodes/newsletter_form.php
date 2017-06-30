<?php
/**
 * Visual Composer Newsletter Form
 *
 * @package Total WordPress Theme
 * @subpackage Visual Composer
 * @version 4.0
 */

if ( ! class_exists( 'VCEX_Newsletter_Shortcode' ) ) {

	class VCEX_Newsletter_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.5.0
		 */
		public function __construct() {
			
			// Add shortcode
			add_shortcode( 'vcex_newsletter_form', array( 'VCEX_Newsletter_Shortcode', 'output' ) );

			// Map to VC
			if ( function_exists( 'vc_lean_map' ) ) {
				vc_lean_map( 'vcex_newsletter_form', array( 'VCEX_Newsletter_Shortcode', 'map' ) );
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.0
		 */
		public static function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_newsletter_form.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.0
		 */
		public static function map() {

			$s_input  = __( 'Input', 'total' );
			$s_submit = __( 'Submit', 'total' );

			return array(
				'name' => __( 'Mailchimp Form', 'total' ),
				'description' => __( 'Newsletter subscription form', 'total' ),
				'base' => 'vcex_newsletter_form',
				'category' => wpex_get_theme_branding(),
				'icon' => 'vcex-newsletter vcex-icon fa fa-envelope',
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
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Full-Width on Mobile', 'total'),
						'param_name' => 'fullwidth_mobile',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Mailchimp Form Action', 'total' ),
						'param_name' => 'mailchimp_form_action',
						'admin_label' => true,
						'value' => '//domain.us1.list-manage.com/subscribe/post?u=numbers_go_here',
						'description' => __( 'Enter the MailChimp form action URL.', 'total' ) .' <a href="https://wpexplorer-themes.com/total/docs/mailchimp-form-action-url/" target="_blank">'. __( 'Learn More', 'total' ) .' &rarr;</a>',
					),

					// Input
					array(
						'type' => 'textfield',
						'heading' => __( 'Text', 'total' ),
						'param_name' => 'placeholder_text',
						'std' => __( 'Enter your email address', 'total' ),
						'group' => $s_input,
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background', 'total' ),
						'param_name' => 'input_bg',
						'dependency' => array(
							'element' => 'mailchimp_form_action',
							'not_empty' => true
						),
						'group' => $s_input,
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'input_color',
						'group' => $s_input,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Width', 'total' ),
						'param_name' => 'input_width',
						'group' => $s_input,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Height', 'total' ),
						'param_name' => 'input_height',
						'group' => $s_input,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Padding', 'total' ),
						'param_name' => 'input_padding',
						'description' => __( 'Please use the following format: top right bottom left.', 'total' ),
						'group' => $s_input,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Border', 'total' ),
						'param_name' => 'input_border',
						'description' => __( 'Please use the shorthand format: width style color. Enter 0px or "none" to disable border.', 'total' ),
						'group' => $s_input,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Border Radius', 'total' ),
						'param_name' => 'input_border_radius',
						'group' => $s_input,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'input_font_size',
						'group' => $s_input,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Letter Spacing', 'total' ),
						'param_name' => 'input_letter_spacing',
						'group' => $s_input,
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'input_weight',
						'group' => $s_input,
					),

					// Submit
					array(
						'type' => 'textfield',
						'heading' => __( 'Text', 'total' ),
						'param_name' => 'submit_text',
						'std' => __( 'Go', 'total' ),
						'group' => $s_submit,
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background', 'total' ),
						'param_name' => 'submit_bg',
						'group' => $s_submit,
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background: Hover', 'total' ),
						'param_name' => 'submit_hover_bg',
						'group' => $s_submit,
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'submit_color',
						'group' => $s_submit,
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color: Hover', 'total' ),
						'param_name' => 'submit_hover_color',
						'group' => $s_submit,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Margin Right', 'total' ),
						'param_name' => 'submit_position_right',
						'group' => $s_submit,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Height', 'total' ),
						'param_name' => 'submit_height',
						'group' => $s_submit,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Padding', 'total' ),
						'param_name' => 'submit_padding',
						'description' => __( 'Please use the following format: top right bottom left.', 'total' ),
						'group' => $s_submit,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Border', 'total' ),
						'param_name' => 'submit_border',
						'description' => __( 'Please use the shorthand format: width style color. Enter 0px or "none" to disable border.', 'total' ),
						'group' => $s_submit,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Border Radius', 'total' ),
						'param_name' => 'submit_border_radius',
						'group' => $s_submit,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'submit_font_size',
						'group' => $s_submit,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Letter Spacing', 'total' ),
						'param_name' => 'submit_letter_spacing',
						'group' => $s_submit,
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'submit_weight',
						'group' => $s_submit,
					),
				)
			);
		}

	}
}
new VCEX_Newsletter_Shortcode;