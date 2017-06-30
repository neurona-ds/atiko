<?php
/**
 * Visual Composer Staff Social
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.0
 */

function vcex_staff_social_vc_map() {
	return array(
		'name' => __( 'Staff Social Links', 'total' ),
		'description' => __( 'Single staff social links', 'total' ),
		'base' => 'staff_social',
		'category' => wpex_get_theme_branding(),
		'icon' => 'vcex-staff-social vcex-icon fa fa-share-alt',
		'params' => array(
			vcex_vc_map_add_css_animation(),
			array(
				'type' => 'autocomplete',
				'heading' => __( 'Staff Member ID', 'total' ),
				'param_name' => 'post_id',
				'admin_label' => true,
				'param_holder_class' => 'vc_not-for-custom',
				'description' => __( 'Select a staff member to display their social links. By default it will diplay the current staff member links.', 'total'),
				'settings' => array(
					'multiple' => false,
					'min_length' => 1,
					'groups' => false,
					'unique_values' => true,
					'display_inline' => true,
					'delay' => 0,
					'auto_focus' => true,
				),
			),
			array(
				'type' => 'vcex_social_button_styles',
				'heading' => __( 'Style', 'total' ),
				'param_name' => 'style',
				'std' => wpex_get_mod( 'staff_social_default_style', 'minimal-round' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Link Target', 'total' ),
				'param_name' => 'link_target',
				'value' => array(
					__( 'Blank', 'total' ) => 'blank',
					__( 'Self', 'total') => 'self',
				),
			),
			array(
				'type' => 'textfield',
				'heading' => __( 'Font Size', 'total' ),
				'param_name' => 'font_size',
			),
			array(
				'type' => 'css_editor',
				'heading' => __( 'CSS', 'total' ),
				'param_name' => 'css',
				'group' => __( 'CSS', 'total' ),
			),
		)
	);
}
vc_lean_map( 'staff_social', 'vcex_staff_social_vc_map' );

// Get autocomplete suggestion
add_filter( 'vc_autocomplete_staff_social_post_id_callback', 'vcex_suggest_staff_members', 10, 1 );

// Render autocomplete suggestions
add_filter( 'vc_autocomplete_staff_social_post_id_render', 'vcex_render_staff_members', 10, 1 );