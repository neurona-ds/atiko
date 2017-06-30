<?php
/**
 * Visual Composer Users Grid
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.0
 */

if ( ! class_exists( 'VCEX_Users_Grid_Shortcode' ) ) {

	class VCEX_Users_Grid_Shortcode {

		/**
		 * Main constructor
		 *
		 * @since 4.0
		 */
		public function __construct() {
			
			// Add shortcode
			add_shortcode( 'vcex_users_grid', array( 'VCEX_Users_Grid_Shortcode', 'output' ) );

			// Map to VC
			if ( function_exists( 'vc_lean_map' ) ) {
				vc_lean_map( 'vcex_users_grid', array( 'VCEX_Users_Grid_Shortcode', 'map' ) );
			}

			// Admin functions
			if ( is_admin() ) {
				add_filter( 'vc_autocomplete_vcex_users_grid_role__in_callback', 'vcex_suggest_user_roles', 10, 1 );
				add_filter( 'vc_autocomplete_vcex_users_grid_role__in_render', 'vcex_render_user_roles', 10, 1 );
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 4.0
		 */
		public static function output( $atts, $content = null ) {
			ob_start();
			include( locate_template( 'vcex_templates/vcex_users_grid.php' ) );
			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 4.0
		 */
		public static function map() {
			
			// Strings
			$s_enable      = __( 'Enable', 'total' );
			$s_query       = __( 'Query', 'total' );
			$s_avatar      = __( 'Avatar', 'total' );
			$s_name        = __( 'Name', 'total' );
			$s_social      = __( 'Social', 'total' );
			$s_description = __( 'Description', 'total' );
			
			// Return array
			return array(
				'name' => __( 'Users Grid', 'total' ),
				'description' => __( 'Displays a grid of users', 'total' ),
				'base' => 'vcex_users_grid',
				'category' => wpex_get_theme_branding(),
				'icon' => 'vcex-terms-grid vcex-icon fa fa-users',
				'params' => array(
					// General
					array(
						'type' => 'textfield',
						'heading' => __( 'Unique Id', 'total' ),
						'param_name' => 'unique_id',
						'admin_label' => true,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Custom Classes', 'total' ),
						'param_name' => 'classes',
						'admin_label' => true,
					),
					vcex_vc_map_add_css_animation(),
					array(
						'type' => 'vcex_visibility',
						'heading' => __( 'Visibility', 'total' ),
						'param_name' => 'visibility',
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Grid Style', 'total' ),
						'param_name' => 'grid_style',
						'value' => array(
							__( 'Fit Columns', 'total' ) => 'fit_columns',
							__( 'Masonry', 'total' ) => 'masonry',
						),
						'edit_field_class' => 'vc_col-sm-3 vc_column clear',
					),
					array(
						'type' => 'vcex_grid_columns',
						'heading' => __( 'Columns', 'total' ),
						'param_name' => 'columns',
						'std' => '5',
						'edit_field_class' => 'vc_col-sm-3 vc_column',
					),
					array(
						'type' => 'vcex_column_gaps',
						'heading' => __( 'Gap', 'total' ),
						'param_name' => 'columns_gap',
						'edit_field_class' => 'vc_col-sm-3 vc_column',
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Responsive', 'total' ),
						'param_name' => 'columns_responsive',
						'value' => array( __( 'Yes', 'total' ) => 'true', __( 'No', 'false' ) => 'false' ),
						'edit_field_class' => 'vc_col-sm-3 vc_column',
						'dependency' => array( 'element' => 'columns', 'value' => array( '2', '3', '4', '5', '6', '7', '8', '9', '10' ) ),
					),
					array(
						'type' => 'vcex_grid_columns_responsive',
						'heading' => __( 'Responsive Settings', 'total' ),
						'param_name' => 'columns_responsive_settings',
						'dependency' => array( 'element' => 'columns_responsive', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => __( 'Link User to Author Page', 'total' ),
						'param_name' => 'link_to_author_page',
					),
					// Query
					array(
						'type' => 'autocomplete',
						'heading' => __( 'User Roles', 'total' ),
						'param_name' => 'role__in',
						'admin_label' => true,
						'std' => '',
						'settings' => array(
							'multiple' => true,
							'min_length' => 1,
							'groups' => false,
							'unique_values' => true,
							'display_inline' => true,
							'delay' => 0,
							'auto_focus' => true,
						),
						'group' => $s_query,
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Order', 'total' ),
						'param_name' => 'order',
						'group' => $s_query,
						'value' => array(
							__( 'ASC', 'total' ) => 'ASC',
							__( 'DESC', 'total' ) => 'DESC',
						),
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Order By', 'total' ),
						'param_name' => 'orderby',
						'value' => array(
							__( 'Display Name', 'total' ) => 'display_name',
							__( 'Nicename', 'total' ) => 'nicename',
							__( 'Login', 'total' ) => 'login',
							__( 'Registered', 'total' ) => 'registered',
							'ID' => 'ID',
							__( 'Email', 'total' ) => 'email',
						),
						'group' => $s_query,
					),
					// Image
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => $s_enable,
						'param_name' => 'avatar',
						'group' => $s_avatar,
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Size', 'total' ),
						'param_name' => 'avatar_size',
						'std' => '150',
						'group' => $s_avatar,
						'dependency' => array( 'element' => 'avatar', 'value' => 'true' ),
						'description' => __( 'Size of Gravatar to return (max is 512 for standard Gravatars)', 'total' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Meta Field', 'total' ),
						'param_name' => 'avatar_meta_field',
						'std' => '',
						'value' => vcex_image_sizes(),
						'group' => $s_avatar,
						'dependency' => array( 'element' => 'avatar', 'value' => 'true' ),
						'description' => __( 'Enter the "ID" of a custom user meta field to pull the avatar from there instead of searching for the user\'s Gravatar', 'total' ),
					),
					array(
						'type' => 'vcex_image_hovers',
						'heading' => __( 'CSS3 Image Hover', 'total' ),
						'param_name' => 'avatar_hover_style',
						'group' => $s_avatar,
					),
					// Name
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => $s_enable,
						'param_name' => 'name',
						'group' => $s_name,
					),
					array(
						'type' => 'dropdown',
						'heading' => __( 'Tag', 'total' ),
						'param_name' => 'name_heading_tag',
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
						'group' => $s_name,
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'name_color',
						'group' => $s_name,
						'std' => '',
						'dependency' => array( 'element' => 'name', 'value' => 'true' ),
					),
					array(
						'type'  => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'name_font_family',
						'group' => $s_name,
						'dependency' => array( 'element' => 'name', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'name_font_weight',
						'group' => $s_name,
						'dependency' => array( 'element' => 'name', 'value' => 'true' ),
					),
					array(
						'type'  => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'name_font_size',
						'group' => $s_name,
						'dependency' => array( 'element' => 'name', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_text_transforms',
						'heading' => __( 'Text Transform', 'total' ),
						'param_name' => 'name_text_transform',
						'group' => $s_name,
						'dependency' => array( 'element' => 'name', 'value' => 'true' ),
					),
					array(
						'type'  => 'textfield',
						'heading' => __( 'Bottom Margin', 'total' ),
						'param_name' => 'name_margin_bottom',
						'group' => $s_name,
						'dependency' => array( 'element' => 'name', 'value' => 'true' ),
					),
					// Description
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => $s_enable,
						'param_name' => 'description',
						'group' => $s_description,
					),
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Color', 'total' ),
						'param_name' => 'description_color',
						'group' => $s_description,
						'std' => '',
						'dependency' => array( 'element' => 'description', 'value' => 'true' ),
					),
					array(
						'type'  => 'vcex_font_family_select',
						'heading' => __( 'Font Family', 'total' ),
						'param_name' => 'description_font_family',
						'group' => $s_description,
						'dependency' => array( 'element' => 'description', 'value' => 'true' ),
					),
					array(
						'type' => 'vcex_font_weight',
						'heading' => __( 'Font Weight', 'total' ),
						'param_name' => 'description_font_weight',
						'group' => $s_description,
						'dependency' => array( 'element' => 'description', 'value' => 'true' ),
					),
					array(
						'type'  => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'description_font_size',
						'group' => $s_description,
						'dependency' => array( 'element' => 'description', 'value' => 'true' ),
					),
					// Social
					array(
						'type' => 'vcex_ofswitch',
						'std' => 'true',
						'heading' => $s_enable,
						'param_name' => 'social_links',
						'group' => $s_social,
					),
					array(
						'type' => 'vcex_social_button_styles',
						'heading' => __( 'Style', 'total' ),
						'param_name' => 'social_links_style',
						'std' => wpex_get_mod( 'staff_social_default_style', 'minimal-round' ),
						'group' => $s_social,
						'dependency' => array( 'element' => 'social_links', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Font Size', 'total' ),
						'param_name' => 'social_links_size',
						'group' => $s_social,
						'dependency' => array( 'element' => 'social_links', 'value' => 'true' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Padding', 'total' ),
						'param_name' => 'social_links_padding',
						'group' => $s_social,
						'description' => __( 'Please use the following format: top right bottom left.', 'total' ),
						'dependency' => array( 'element' => 'social_links', 'value' => 'true' ),
					),
					array(
						'type' => 'css_editor',
						'heading' => __( 'CSS', 'total' ),
						'param_name' => 'entry_css',
						'group' => __( 'Entry CSS', 'total' ),
					),
				)
			);
		}

	}
}
new VCEX_Users_Grid_Shortcode;