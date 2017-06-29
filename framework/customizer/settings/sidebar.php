<?php
/**
 * Sidebar Customizer Options
 *
 * @package Total WordPress Theme
 * @subpackage Customizer
 * @version 3.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$this->sections['wpex_sidebar'] = array(
	'title' => __( 'General', 'total' ),
	'settings' => array(
		array(
			'id' => 'has_widget_icons',
			'transport' => 'postMessage',
			'default' => '1',
			'control' => array (
				'label' => __( 'Widget Icons', 'total' ),
				'type' => 'checkbox',
				'desc' => __( 'Certain widgets include little icons such as the recent posts widget. Here you can toggle the icons on or off.', 'total' ),
			),
		),
		array(
			'id' => 'sidebar_background',
			'transport' => 'postMessage',
			'control' => array (
				'type' => 'color',
				'label' => __( 'Background', 'total' ),
			),
			'inline_css' => array(
				'target' => '#sidebar',
				'alter' => 'background-color',
			),
		),
		array(
			'id' => 'sidebar_padding',
			'transport' => 'postMessage',
			'control' => array (
				'type' => 'text',
				'label' => __( 'Padding', 'total' ),
				'description' => $padding_desc,
			),
			'inline_css' => array(
				'target' => '#sidebar',
				'alter' => 'padding',
			),
		),
		array(
			'id' => 'sidebar_text_color',
			'transport' => 'postMessage',
			'control' => array (
				'type' => 'color',
				'label' => __( 'Text Color', 'total' ),
			),
			'inline_css' => array(
				'target' => array(
					'#sidebar',
					'#sidebar p',
					'.widget-recent-posts-icons li .fa',
				),
				'alter' => 'color',
			),
		),
		array(
			'id' => 'sidebar_borders_color',
			'transport' => 'postMessage',
			'control' => array (
				'type' => 'color',
				'label' => __( 'Li & Calendar Borders', 'total' ),
			),
			'inline_css' => array(
				'target' => array(
					'#sidebar li',
					'#sidebar #wp-calendar thead th',
					'#sidebar #wp-calendar tbody td',
				),
				'alter' => 'border-color',
			),
		),
		array(
			'id' => 'sidebar_link_color',
			'transport' => 'postMessage',
			'control' => array (
				'type' => 'color',
				'label' => __( 'Link Color', 'total' ),
			),
			'inline_css' => array(
				'target' => '#sidebar a',
				'alter' => 'color',
			),
		),
		array(
			'id' => 'sidebar_link_color_hover',
			'transport' => 'postMessage',
			'control' => array (
				'type' => 'color',
				'label' => __( 'Link Color: Hover', 'total' ),
			),
			'inline_css' => array(
				'target' => '#sidebar a:hover',
				'alter' => 'color',
			),
		),
		/** Headings **/
		array(
			'id' => 'sidebar_headings_heading',
			'control' => array(
				'type' => 'wpex-heading',
				'label' => __( 'Headings', 'total' ),
			),
		),
		array(
			'id' => 'sidebar_headings',
			'default' => 'div',
			'transport' => 'postMessage',
			'control' => array (
				'label' => __( 'Tag', 'total' ),
				'type' => 'select',
				'choices' => array(
					'h2' => 'h2',
					'h3' => 'h3',
					'h4' => 'h4',
					'h5' => 'h5',
					'h6' => 'h6',
					'span' => 'span',
					'div' => 'div',
				),
			),
		),
		array(
			'id' => 'sidebar_headings_background',
			'transport' => 'postMessage',
			'control' => array (
				'type' => 'color',
				'label' => __( 'Background', 'total' ),
			),
			'inline_css' => array(
				'target' => '#sidebar .widget-title',
				'alter' => 'background-color',
			),
		),
		array(
			'id' => 'sidebar_headings_padding',
			'transport' => 'postMessage',
			'control' => array (
				'type' => 'text',
				'label' => __( 'Padding', 'total' ),
				'description' => $padding_desc,
			),
			'inline_css' => array(
				'target' => '#sidebar .widget-title',
				'alter' => 'padding',
			),
		),
		array(
			'id' => 'sidebar_headings_align',
			'transport' => 'postMessage',
			'control' =>  array(
				'type' => 'select',
				'label' => __( 'Text Align', 'total' ),
				'choices' => array(
					'default' => __( 'Default','total' ),
					'left' => __( 'Left','total' ),
					'right' => __( 'Right','total' ),
					'center' => __( 'Center','total' ),
				),
			),
			'inline_css' => array(
				'target' => '#sidebar .widget-title',
				'alter' => 'text-align',
			),
		),
	),
);