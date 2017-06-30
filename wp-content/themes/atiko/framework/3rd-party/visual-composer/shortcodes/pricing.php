<?php
/**
 * Visual Composer Pricing
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.2.1
 */

if ( ! class_exists( 'VCEX_Pricing_Shortcode' ) ) {

	class VCEX_Pricing_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 3.5.0
		 */
		public function __construct() {
			
			// Add shortcode
			add_shortcode( 'vcex_pricing', array( 'VCEX_Pricing_Shortcode', 'output' ) );

			// Map to VC
			if ( function_exists( 'vc_lean_map' ) ) {
				vc_lean_map( 'vcex_pricing', array( 'VCEX_Pricing_Shortcode', 'map' ) );
			}

			// Admin filters
			if ( is_admin() ) {

				// Edit form fields
				add_filter( 'vc_edit_form_fields_attributes_vcex_pricing', array( 'VCEX_Pricing_Shortcode', 'edit_form_fields' ) );

			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.0
		 */
		public static function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_pricing.php' ) );
			return ob_get_clean();
		}

		/**
		 * Alter module fields on edit
		 *
		 * @since 3.5.0
		 */
		public static function edit_form_fields( $atts ) {

			if ( ! empty( $atts['button_url'] ) && false === strpos( $atts['button_url'], 'url:' ) ) {
				$url = 'url:'. $atts['button_url'] .'|';
				$atts['button_url'] = $url;
			}

			return $atts;

		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.0
		 */
		public static function map() {

			// Strings
			$s_plan     = __( 'Plan', 'total' );
			$s_cost     = __( 'Cost', 'total' );
			$s_per      = __( 'Per', 'total' );
			$s_features = __( 'Features', 'total' );
			$s_button   = __( 'Button', 'total' );

			// Return array
			return array(
				'name' => __( 'Pricing Table', 'total' ),
				'description' => __( 'Insert a pricing column', 'total' ),
				'base' => 'vcex_pricing',
				'category' => wpex_get_theme_branding(),
				'icon' => 'vcex-pricing vcex-icon fa fa-usd',
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
						'param_name' => 'el_class',
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
					// Plan
					array(
						'type' => 'vcex_ofswitch',
						'heading' => __( 'Featured', 'total'),
						'param_name' => 'featured',
						'group' => $s_plan,
						'std' => 'no',
						'vcex' => array(
							'on'  => 'yes',
							'off' => 'no',
						),
					),
					array(
						'type' => 'textfield',
						'heading' => $s_plan,
						'param_name' => 'plan',
						'group' => $s_plan,
						'std' => __( 'Basic', 'total' ),
						'admin_label' => true,
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background', 'total' ),
						'param_name' => 'plan_background',
						'group' => $s_plan,
						'dependency' => array( 'element' => 'plan', 'not_empty' => true ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'plan_color',
						'group' => $s_plan,
						'dependency' => array( 'element' => 'plan', 'not_empty' => true ),
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'plan_weight',
						'group' => $s_plan,
						'dependency' => array( 'element' => 'plan', 'not_empty' => true ),
					),
					array(
						'type' => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'plan_font_family',
						'group' => $s_plan,
					),
					array(
						'type' => 'vcex_text_transforms',
						'heading' => __( 'Text Transform', 'total' ),
						'param_name' => 'plan_text_transform',
						'group' => $s_plan,
						'dependency' => array( 'element' => 'plan', 'not_empty' => true ),
					),
					array(
						'type' => 'vcex_responsive_sizes',
						'target' => 'font-size',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'plan_size',
						'group' => $s_plan,
						'dependency' => array( 'element' => 'plan', 'not_empty' => true ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Letter Spacing', 'total' ),
						'param_name' => 'plan_letter_spacing',
						'group' => $s_plan,
						'dependency' => array( 'element' => 'plan', 'not_empty' => true ),
						'description' => __( 'Please enter a px value.', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Padding', 'total' ),
						'param_name' => 'plan_padding',
						'description' => __( 'Please use the following format: top right bottom left.', 'total' ),
						'group' => $s_plan,
						'dependency' => array( 'element' => 'plan', 'not_empty' => true ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Margin', 'total' ),
						'param_name' => 'plan_margin',
						'description' => __( 'Please use the following format: top right bottom left.', 'total' ),
						'group' => $s_plan,
						'dependency' => array( 'element' => 'plan', 'not_empty' => true ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Border', 'total' ),
						'param_name' => 'plan_border',
						'description' => __( 'Please use the shorthand format: width style color. Enter 0px or "none" to disable border.', 'total' ),
						'group' => $s_plan,
						'dependency' => array( 'element' => 'plan', 'not_empty' => true ),
					),
					// Cost
					array(
						'type' => 'textfield',
						'heading' => $s_cost,
						'param_name' => 'cost',
						'group' => $s_cost,
						'std' => '$20',
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background', 'total' ),
						'param_name' => 'cost_background',
						'group' => $s_cost,
						'dependency' => array( 'element' => 'cost', 'not_empty' => true ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'cost_color',
						'group' => $s_cost,
						'dependency' => array( 'element' => 'cost', 'not_empty' => true ),
					),
					array(
						'type' => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'cost_font_family',
						'group' => $s_cost,
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'cost_weight',
						'group' => $s_cost,
						'dependency' => array( 'element' => 'cost', 'not_empty' => true ),
					),
					array(
						'type' => 'vcex_responsive_sizes',
						'target' => 'font-size',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'cost_size',
						'group' => $s_cost,
						'dependency' => array( 'element' => 'cost', 'not_empty' => true ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Padding', 'total' ),
						'param_name' => 'cost_padding',
						'description' => __( 'Please use the following format: top right bottom left.', 'total' ),
						'group' => $s_cost,
						'dependency' => array( 'element' => 'cost', 'not_empty' => true ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Border', 'total' ),
						'param_name' => 'cost_border',
						'description' => __( 'Please use the shorthand format: width style color. Enter 0px or "none" to disable border.', 'total' ),
						'group' => $s_cost,
						'dependency' => array( 'element' => 'cost', 'not_empty' => true ),
					),
					// Per
					array(
						'type' => 'textfield',
						'heading' => $s_per,
						'param_name' => 'per',
						'group' => $s_per,
						'dependency' => array( 'element' => 'cost', 'not_empty' => true ),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Display', 'total' ),
						'param_name' => 'per_display',
						'value' => array(
							__( 'Default', 'total' ) => '',
							__( 'Inline', 'total' ) => 'inline',
							__( 'Block', 'total' ) => 'block',
							__( 'Inline-Block', 'total' ) => 'inline-block',
						),
						'group' => $s_per,
						'dependency' => array( 'element' => 'per', 'not_empty' => true ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'per_color',
						'group' => $s_per,
						'dependency' => array( 'element' => 'per', 'not_empty' => true ),
					),
					array(
						'type' => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'per_font_family',
						'group' => $s_per,
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'per_weight',
						'group' => $s_per,
						'dependency' => array( 'element' => 'per', 'not_empty' => true ),
					),
					array(
						'type' => 'vcex_text_transforms',
						'heading' => __( 'Text Transform', 'total' ),
						'param_name' => 'per_transform',
						'group' => $s_per,
						'dependency' => array( 'element' => 'per', 'not_empty' => true ),
					),
					array(
						'type' => 'vcex_responsive_sizes',
						'target' => 'font-size',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'per_size',
						'group' => $s_per,
						'dependency' => array( 'element' => 'per', 'not_empty' => true ),
					),
					// Features
					array(
						'type' => 'textarea_html',
						'heading' => $s_features,
						'param_name' => 'content',
						'value' => '<ul>
							<li>30GB Storage</li>
							<li>512MB Ram</li>
							<li>10 databases</li>
							<li>1,000 Emails</li>
							<li>25GB Bandwidth</li>
						</ul>',
						'description' => __('Enter your pricing content. You can use a UL list as shown by default but anything would really work!','total'),
						'group' => $s_features,
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'font_color',
						'group' => $s_features,
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background', 'total' ),
						'param_name' => 'features_bg',
						'group' => $s_features,
					),
					array(
						'type' => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'font_family',
						'group' => $s_features,
					),
					array(
						'type' => 'vcex_responsive_sizes',
						'target' => 'font-size',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'font_size',
						'group' => $s_features,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Padding', 'total' ),
						'param_name' => 'features_padding',
						'description' => __( 'Please use the following format: top right bottom left.', 'total' ),
						'group' => $s_features,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Border', 'total' ),
						'param_name' => 'features_border',
						'description' => __( 'Please use the shorthand format: width style color. Enter 0px or "none" to disable border.', 'total' ),
						'group' => $s_features,
					),
					// Button
					array(
						'type' => 'textarea_raw_html',
						'heading' => __( 'Custom Button HTML', 'total' ),
						'param_name' => 'custom_button',
						'description' => __( 'Enter your custom button HTML, such as your paypal button code.', 'total' ),
						'group' => $s_button,
					),
					array(
						'type' => 'vc_link',
						'heading' => __( 'URL', 'total' ),
						'param_name' => 'button_url',
						'group' => $s_button,
						'dependency' => array( 'element' => 'custom_button', 'is_empty' => true ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'false',
						'heading' => __( 'Local Scroll?', 'total' ),
						'param_name' => 'button_local_scroll',
						'group' => $s_button,
						'dependency' => array( 'element' => 'custom_button', 'is_empty' => true ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Text', 'total' ),
						'param_name' => 'button_text',
						'value' => __( 'Text', 'total' ),
						'group' => $s_button,
						'dependency' => array( 'element' => 'custom_button', 'is_empty' => true ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Area Background', 'total' ),
						'param_name' => 'button_wrap_bg',
						'group' => $s_button,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Area Padding', 'total' ),
						'param_name' => 'button_wrap_padding',
						'description' => __( 'Please use the following format: top right bottom left.', 'total' ),
						'group' => $s_button,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Area Border', 'total' ),
						'param_name' => 'button_wrap_border',
						'description' => __( 'Please use the shorthand format: width style color. Enter 0px or "none" to disable border.', 'total' ),
						'group' => $s_button,
					),
					array(
						'type' => 'vcex_button_styles',
						'heading' => __( 'Style', 'total' ),
						'param_name' => 'button_style',
						'group' => $s_button,
							'dependency' => array( 'element' => 'custom_button', 'is_empty' => true ),
					),
					array(
						'type' => 'vcex_button_colors',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'button_style_color',
						'group' => $s_button,
						'dependency' => array( 'element' => 'custom_button', 'is_empty' => true ),
					),
					array(
						'type' => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'button_font_family',
						'group' => $s_button,
						'dependency' => array( 'element' => 'custom_button', 'is_empty' => true ),
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'button_weight',
						'group' => $s_button,
						'dependency' => array( 'element' => 'custom_button', 'is_empty' => true ),
					),
					array(
						'type' => 'vcex_text_transforms',
						'heading' => __( 'Text Transform', 'total' ),
						'param_name' => 'button_transform',
						'group' => $s_button,
						'dependency' => array( 'element' => 'custom_button', 'is_empty' => true ),
					),
					array(
						'type' => 'vcex_responsive_sizes',
						'target' => 'font-size',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'button_size',
						'group' => $s_button,
						'dependency' => array( 'element' => 'custom_button', 'is_empty' => true ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background', 'total' ),
						'param_name' => 'button_bg_color',
						'group' => $s_button,
							'dependency' => array( 'element' => 'custom_button', 'is_empty' => true ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Background: Hover', 'total' ),
						'param_name' => 'button_hover_bg_color',
						'group' => $s_button,
							'dependency' => array( 'element' => 'custom_button', 'is_empty' => true ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'button_color',
						'group' => $s_button,
							'dependency' => array( 'element' => 'custom_button', 'is_empty' => true ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color: Hover', 'total' ),
						'param_name' => 'button_hover_color',
						'group' => $s_button,
						'dependency' => array( 'element' => 'custom_button', 'is_empty' => true ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Border Radius', 'total' ),
						'param_name' => 'button_border_radius',
						'group' => $s_button,
						'dependency' => array( 'element' => 'custom_button', 'is_empty' => true ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Letter Spacing', 'total' ),
						'param_name' => 'button_letter_spacing',
						'group' => $s_button,
						'dependency' => array( 'element' => 'custom_button', 'is_empty' => true ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Padding', 'total' ),
						'param_name' => 'button_padding',
						'description' => __( 'Please use the following format: top right bottom left.', 'total' ),
						'group' => $s_button,
						'dependency' => array( 'element' => 'custom_button', 'is_empty' => true ),
					),
					//Icons
					array(
						'type' => 'dropdown',
						'heading' => __( 'Icon library', 'total' ),
						'param_name' => 'icon_type',
						'description' => __( 'Select icon library.', 'total' ),
						'std' => 'fontawesome',
						'value' => array(
							__( 'Font Awesome', 'total' ) => 'fontawesome',
							__( 'Open Iconic', 'total' ) => 'openiconic',
							__( 'Typicons', 'total' ) => 'typicons',
							__( 'Entypo', 'total' ) => 'entypo',
							__( 'Linecons', 'total' ) => 'linecons',
							__( 'Pixel', 'total' ) => 'pixelicons',
						),
						'group' => __( 'Button Icons', 'total' ),
						'dependency' => array( 'element' => 'custom_button', 'is_empty' => true ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon Left', 'total' ),
						'param_name' => 'button_icon_left',
						'settings' => array(
							'emptyIcon' => true,
							'iconsPerPage' => 200,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'fontawesome' ),
						'group' => __( 'Button Icons', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon Left', 'total' ),
						'param_name' => 'button_icon_left_openiconic',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'openiconic',
							'iconsPerPage' => 200,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'openiconic' ),
						'group' => __( 'Button Icons', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon Left', 'total' ),
						'param_name' => 'button_icon_left_typicons',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'typicons',
							'iconsPerPage' => 200,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'typicons' ),
						'group' => __( 'Button Icons', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon Left', 'total' ),
						'param_name' => 'button_icon_left_entypo',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'entypo',
							'iconsPerPage' => 300,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'entypo' ),
						'group' => __( 'Button Icons', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon Left', 'total' ),
						'param_name' => 'button_icon_left_linecons',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'linecons',
							'iconsPerPage' => 200,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'linecons' ),
						'group' => __( 'Button Icons', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon Left', 'total' ),
						'param_name' => 'button_icon_left_pixelicons',
						'settings' => array(
							'emptyIcon' => false,
							'type' => 'pixelicons',
							'source' => vcex_pixel_icons(),
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'pixelicons' ),
						'group' => __( 'Button Icons', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon Right', 'total' ),
						'param_name' => 'button_icon_right',
						'settings' => array(
							'emptyIcon' => true,
							'iconsPerPage' => 200,
						),
						'dependency' => array(
							'element' => 'icon_type',
							'value' => 'fontawesome',
						),
						'group' => __( 'Button Icons', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon Right', 'total' ),
						'param_name' => 'button_icon_right_openiconic',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'openiconic',
							'iconsPerPage' => 200,
						),
						'dependency' => array(
							'element' => 'icon_type',
							'value' => 'openiconic',
						),
						'group' => __( 'Button Icons', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon Right', 'total' ),
						'param_name' => 'button_icon_right_typicons',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'typicons',
							'iconsPerPage' => 200,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'typicons' ),
						'group' => __( 'Button Icons', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon Right', 'total' ),
						'param_name' => 'button_icon_right_entypo',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'entypo',
							'iconsPerPage' => 300,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'entypo' ),
						'group' => __( 'Button Icons', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon Right', 'total' ),
						'param_name' => 'button_icon_right_linecons',
						'settings' => array(
							'emptyIcon' => true,
							'type' => 'linecons',
							'iconsPerPage' => 200,
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'linecons' ),
						'group' => __( 'Button Icons', 'total' ),
					),
					array(
						'type' => 'iconpicker',
						'heading' => __( 'Icon Right', 'total' ),
						'param_name' => 'button_icon_right_pixelicons',
						'settings' => array(
							'emptyIcon' => false,
							'type' => 'pixelicons',
							'source' => vcex_pixel_icons(),
						),
						'dependency' => array( 'element' => 'icon_type', 'value' => 'pixelicons' ),
						'group' => __( 'Button Icons', 'total' ),
					),
					// CSS
					array(
						'type' => 'css_editor',
						'heading' => __( 'Design Options', 'total' ),
						'param_name' => 'css',
						'group' => __( 'CSS', 'total' ),
					),
				)
			);
		}

	}
}
new VCEX_Pricing_Shortcode;