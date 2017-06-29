<?php
/**
 * Toggle Bar Panel
 *
 * @package Total WordPress Theme
 * @subpackage Customizer
 * @version 3.6.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// General
$this->sections['wpex_togglebar'] = array(
	'title' => __( 'General', 'total' ),
	'settings' => array(
		array(
			'id' => 'toggle_bar',
			'default' => true,
			'control' => array(
				'label' => __( 'Enable', 'total' ),
				'type' => 'checkbox',
				'desc' => __( 'If you disable this option we recommend you go to the Customizer Manager and disable the section as well so the next time you work with the Customizer it will load faster.', 'total' ),
			),
		),
		array(
			'id' => 'toggle_bar_page',
			'default' => '',
			'control' => array(
				'label' => __( 'Content', 'total' ),
				'type' => 'wpex-dropdown-pages',
				'active_callback' => 'wpex_cac_has_togglebar',
			),
		),
		array(
			'id' => 'toggle_bar_visibility',
			'transport' => 'postMessage',
			'default' => 'always-visible',
			'control' => array(
				'label' => __( 'Visibility', 'total' ),
				'type' => 'select',
				'choices' => wpex_visibility(),
				'active_callback' => 'wpex_cac_has_togglebar',
			),
		),
		array(
			'id' => 'toggle_bar_display',
			'default' => 'overlay',
			'control' => array(
				'label' => __( 'Display', 'total' ),
				'type' => 'select',
				'choices' => array(
					'overlay' => __( 'Overlay', 'total' ),
					'inline' => __( 'Inline', 'total' ),
				),
				'active_callback' => 'wpex_cac_has_togglebar',
			),
		),
		array(
			'id' => 'toggle_bar_animation',
			'default' => 'fade',
			'control' => array(
				'label' => __( 'Animation', 'total' ),
				'type' => 'select',
				'choices' => array(
					'fade' => __( 'Fade', 'total' ),
					'fade-slide' => __( 'Fade & Slide Down', 'total' ),
				),
				'active_callback' => 'wpex_cac_has_togglebar_animation',
			),
		),
		array(
			'id' => 'toggle_bar_button_icon',
			'default' => 'plus',
			'control' => array(
				'label' => __( 'Button Icon', 'total' ),
				'type' => 'wpex-fa-icon-select',
				'active_callback' => 'wpex_cac_has_togglebar',
			),
		),
		array(
			'id' => 'toggle_bar_button_icon_active',
			'default' => 'minus',
			'control' => array(
				'label' => __( 'Button Icon: Active', 'total' ),
				'type' => 'wpex-fa-icon-select',
				'active_callback' => 'wpex_cac_has_togglebar',
			),
		),
		array(
			'id' => 'toggle_bar_bg',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Content Background', 'total' ),
				'active_callback' => 'wpex_cac_has_togglebar',
			),
			'inline_css' => array(
				'target' => '#toggle-bar-wrap',
				'alter' => 'background',
			),
		),
		array(
			'id' => 'toggle_bar_border',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Content Border', 'total' ),
				'active_callback' => 'wpex_cac_has_togglebar',
			),
			'inline_css' => array(
				'target' => '#toggle-bar-wrap',
				'alter' => 'border-color',
				'important' => true,
			),
		),
		array(
			'id' => 'toggle_bar_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Content Color', 'total' ),
				'active_callback' => 'wpex_cac_has_togglebar',
			),
			'inline_css' => array(
				'target' => array(
					'#toggle-bar-wrap',
					'#toggle-bar-wrap strong',
				),
				'alter' => 'color',
			),
		),
		array(
			'id' => 'toggle_bar_btn_bg',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Button Background', 'total' ),
				'active_callback' => 'wpex_cac_has_togglebar',
			),
			'inline_css' => array(
				'target' => '.toggle-bar-btn',
				'alter' => array( 'border-top-color', 'border-right-color' ),
			),
		),
		array(
			'id' => 'toggle_bar_btn_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Button Color', 'total' ),
				'active_callback' => 'wpex_cac_has_togglebar',
			),
			'inline_css' => array(
				'target' => '.toggle-bar-btn span.fa',
				'alter' => 'color',
			),
		),
		array(
			'id' => 'toggle_bar_btn_hover_bg',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Button Hover Background', 'total' ),
				'active_callback' => 'wpex_cac_has_togglebar',
			),
			'inline_css' => array(
				'target' => '.toggle-bar-btn:hover',
				'alter' => array( 'border-top-color', 'border-right-color' ),
			),
		),
		array(
			'id' => 'toggle_bar_btn_hover_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Button Hover Color', 'total' ),
				'active_callback' => 'wpex_cac_has_togglebar',
			),
			'inline_css' => array(
				'target' => '.toggle-bar-btn:hover span.fa',
				'alter' => 'color',
			),
		),
	)
);