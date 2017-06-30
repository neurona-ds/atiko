<?php
/**
 * Visual Composer Login Form
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.0
 */

if ( ! class_exists( 'VCEX_Login_Form' ) ) {

	class VCEX_Login_Form {

		/**
		 * Main constructor
		 *
		 * @since 3.5.0
		 */
		public function __construct() {
			
			// Add shortcode
			add_shortcode( 'vcex_login_form', array( 'VCEX_Login_Form', 'output' ) );

			// Map to VC
			if ( function_exists( 'vc_lean_map' ) ) {
				vc_lean_map( 'vcex_login_form', array( 'VCEX_Login_Form', 'map' ) );
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.0
		 */
		public static function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_login_form.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.0
		 */
		public static function map() {
			return array(
				'name' => __( 'Login Form', 'total' ),
				'description' => __( 'Adds a WordPress login form', 'total' ),
				'base' => 'vcex_login_form',
				'category' => wpex_get_theme_branding(),
				'icon' => 'vcex-login-form vcex-icon fa fa-unlock-alt',
				'params' => array(
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
					vcex_vc_map_add_css_animation(),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Remember Me', 'total' ),
						'param_name' => 'remember',
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Lost Password', 'total' ),
						'param_name' => 'lost_password',
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Register', 'total' ),
						'param_name' => 'register',
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Custom Register URL', 'total' ),
						'param_name' => 'register_url',
						'dependency' => array( 'element' => 'register', 'value' => 'true' ),
					),

					// Labels
					array(
						'type' => 'textfield',
						'heading' => __( 'Username Label', 'total' ),
						'param_name' => 'label_username',
						'group' =>  __( 'Labels', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Password Label', 'total' ),
						'param_name' => 'label_password',
						'group' =>  __( 'Labels', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Remember Me Label', 'total' ),
						'param_name' => 'label_remember',
						'group' =>  __( 'Labels', 'total' ),
						'dependency' => array( 'element' => 'remember', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Lost Password Label', 'total' ),
						'param_name' => 'lost_password_label',
						'dependency' => array( 'element' => 'lost_password', 'value' => 'true' ),
						'group' =>  __( 'Labels', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Register Label', 'total' ),
						'param_name' => 'register_label',
						'dependency' => array( 'element' => 'register', 'value' => 'true' ),
						'group' =>  __( 'Labels', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Button Label', 'total' ),
						'param_name' => 'label_log_in',
						'group' =>  __( 'Labels', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Redirect', 'total' ),
						'param_name' => 'redirect',
						'description' => __( 'Enter a URL to redirect the user after they successfully log in. Leave blank to redirect to the current page.','total'),
					),

					// Logged In Content
					array(
						'type' => 'textarea_html',
						'heading' => __( 'Logged in Content', 'total' ),
						'param_name' => 'content',
						'value' => __( 'You are currently logged in.', 'total' ) .' ' . '<a href="' . wp_logout_url( home_url() ) . '" title="'. __( 'Logout?', 'total' ) .'">'. __( 'Logout?', 'total' ) .'</a>',
						'description' => __( 'The content to displayed for logged in users.','total'),
					),

					// Typography
					array(
						'type' => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'text_font_size',
						'group' => __( 'Typography', 'total' ),
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'text_color',
						'group' => __( 'Typography', 'total' ),
					),

					// CSS
					array(
						'type' => 'dropdown',
						'heading' => __( 'Style', 'total' ),
						'param_name' => 'form_style',
						'std' => '',
						'value' => array_flip( wpex_get_form_styles() ),
						'group' => __( 'Design Options', 'total' ),
					),
					array(
						'type' => 'css_editor',
						'heading' => __( 'CSS', 'total' ),
						'param_name' => 'css',
						'group' => __( 'Design Options', 'total' ),
					),
				)
			);
		}

	}
}
new VCEX_Login_Form;