<?php
/**
 * Testimonials Customizer Options
 *
 * @package Total WordPress Theme
 * @subpackage Customizer
 * @version 4.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// General
$this->sections['wpex_testimonials'] = array(
	'title' => __( 'General', 'total' ),
	'settings' => array(
		array(
			'id' => 'testimonials_archive_layout',
			'default' => 'full-width',
			'control' => array(
				'label' => __( 'Archive Layout', 'total' ),
				'type' => 'select',
				'choices' => $post_layouts,
			),
		),
		array(
			'id' => 'testimonials_entry_columns',
			'default' => '4',
			'control' => array(
				'label' => __( 'Archive Columns', 'total' ), 
				'type' => 'select',
				'choices' => wpex_grid_columns(),
			),
		),
		array(
			'id' => 'testimonials_archive_grid_gap',
			'control' => array(
				'label' => __( 'Archive Gap', 'total' ),
				'type' => 'select',
				'choices' => wpex_column_gaps(),
			),
		),
		array(
			'id' => 'testimonials_archive_posts_per_page',
			'default' => '12',
			'control' => array(
				'label' => __( 'Archive Posts Per Page', 'total' ),
				'type' => 'number',
			),
		),
		array(
			'id' => 'testimonial_entry_title',
			'control' => array(
				'label' => __( 'Archive Entry Title', 'total' ), 
				'type' => 'checkbox',
			),
		),
		array(
			'id' => 'testimonial_post_style',
			'default' => 'blockquote',
			'control' => array(
				'label' => __( 'Single Style', 'total' ),
				'type' => 'select',
				'choices' => array(
					'blockquote' => __( 'Testimonial', 'total' ),
					'standard' => __( 'Standard', 'total' ),
				),
			),
		),
		array(
			'id' => 'testimonials_single_layout',
			'default' => 'right-sidebar',
			'control' => array(
				'label' => __( 'Single Layout', 'total' ),
				'type' => 'select',
				'choices' => $post_layouts,
			),
		),
		array(
			'id' => 'testimonials_comments',
			'control' => array(
				'label' => __( 'Comments', 'total' ), 
				'type' => 'checkbox',
			),
		),
		array(
			'id' => 'testimonials_next_prev',
			'default' => 1,
			'control' => array(
				'label' => __( 'Next & Previous Links', 'total' ),
				'type' => 'checkbox',
			),
		),
		array(
			'id' => 'testimonials_entry_img_size',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'text',
				'label' => __( 'Entry Image Size', 'total' ),
				'desc' => __( 'Default size is 45px.', 'total' ),
			),
			'inline_css' => array(
				'target' => '.testimonial-entry-thumb img',
				'alter' => array( 'width', 'height' ),
				'sanitize' => 'px',
			),
		),
		array(
			'id' => 'testimonial_entry_bg',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Entry Background', 'total' ),
			),
			'inline_css' => array(
				'target' => '.testimonial-entry-content',
				'alter' => 'background',
			),
		),
		array(
			'id' => 'testimonial_entry_pointer_bg',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Entry Pointer Background', 'total' ),
			),
			'inline_css' => array(
				'target' => '.testimonial-caret',
				'alter' => 'border-top-color',
			),
		),
		array(
			'id' => 'testimonial_entry_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Entry Color', 'total' ),
			),
			'inline_css' => array(
				'target' => array(
					'.testimonial-entry-content',
					'.testimonial-entry-content a',
				),
				'alter' => 'color',
			),
		),
	),
);