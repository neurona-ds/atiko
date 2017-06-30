<?php
/**
 * Customizer => Footer Bottom
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
$this->sections['wpex_footer_bottom'] = array(
	'title' => __( 'General', 'total' ),
	'settings' => array(
		array(
			'id' => 'footer_bottom',
			'transport' => 'partialRefresh',
			'default' => true,
			'control' => array(
				'label' => __( 'Bottom Footer Area', 'total' ),
				'type' => 'checkbox',
				'desc' => __( 'If you disable this option we recommend you go to the Customizer Manager and disable the section as well so the next time you work with the Customizer it will load faster.', 'total' ),
			),
		),
		array(
			'id' => 'footer_copyright_text',
			'transport' => 'partialRefresh',
			'default' => 'Copyright <a href="#">Your Business LLC.</a> - All Rights Reserved',
			'control' => array(
				'label' => __( 'Copyright', 'total' ),
				'type' => 'textarea',
				'active_callback' => 'wpex_cac_has_footer_bottom',
			),
		),
		array(
			'id' => 'bottom_footer_text_align',
			'transport' => 'partialRefresh',
			'control' =>  array(
				'type' => 'select',
				'label' => __( 'Text Align', 'total' ),
				'choices' => array(
					'default' => __( 'Default','total' ),
					'left' => __( 'Left','total' ),
					'right' => __( 'Right','total' ),
					'center' => __( 'Center','total' ),
				),
				'active_callback'=> 'wpex_cac_has_footer_bottom',
			),
		),
		array(
			'id' => 'bottom_footer_padding',
			'transport' => 'postMessage',
			'control' =>  array(
				'type' => 'text',
				'label' => __( 'Padding', 'total' ),
				'description' => $padding_desc,
				'active_callback'=> 'wpex_cac_has_footer_bottom',
			),
			'inline_css' => array(
				'target' => '#footer-bottom-inner',
				'alter' => 'padding',
			),
		),
		array(
			'id' => 'bottom_footer_background',
			'transport' => 'postMessage',
			'control' =>  array(
				'type' => 'color',
				'label' => __( 'Background', 'total' ),
				'active_callback'=> 'wpex_cac_has_footer_bottom',
			),
			'inline_css' => array(
				'target' => '#footer-bottom',
				'alter' => 'background',
			),
		),
		array(
			'id' => 'bottom_footer_color',
			'transport' => 'postMessage',
			'control' =>  array(
				'type' => 'color',
				'label' => __( 'Color', 'total' ),
				'active_callback'=> 'wpex_cac_has_footer_bottom',
			),
			'inline_css' => array(
				'target' => array(
					'#footer-bottom',
					'#footer-bottom p',
				),
				'alter' => 'color',
			),
		),
		array(
			'id' => 'bottom_footer_link_color',
			'transport' => 'postMessage',
			'control' =>  array(
				'type' => 'color',
				'label' => __( 'Links', 'total' ),
				'active_callback'=> 'wpex_cac_has_footer_bottom',
			),
			'inline_css' => array(
				'target' => '#footer-bottom a',
				'alter' => 'color',
			),
		),
		array(
			'id' => 'bottom_footer_link_color_hover',
			'transport' => 'postMessage',
			'control' =>  array(
				'type' => 'color',
				'label' => __( 'Links: Hover', 'total' ),
				'active_callback'=> 'wpex_cac_has_footer_bottom',
			),
			'inline_css' => array(
				'target' => '#footer-bottom a:hover',
				'alter' => 'color',
			),
		),
	),
);