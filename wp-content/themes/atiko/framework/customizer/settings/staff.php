<?php
/**
 * Staff Customizer Options
 *
 * @package Total WordPress Theme
 * @subpackage Customizer
 * @version 3.6.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Single blocks
$blocks = apply_filters( 'wpex_staff_single_blocks', array(
	'title'    => __( 'Post Title', 'total' ),
	'meta'    => __( 'Meta', 'total' ),
	'media'    => __( 'Media', 'total' ),
	'content'  => __( 'Content', 'total' ),
	'share'    => __( 'Social Share', 'total' ),
	'comments' => __( 'Comments', 'total' ),
	'related'  => __( 'Related Posts', 'total' ),
) );

// General
$this->sections['wpex_staff_general'] = array(
	'title' => __( 'General', 'total' ),
	'panel' => 'wpex_staff',
	'settings' => array(
		array(
			'id' => 'staff_social_default_style',
			'default' => 'minimal-round',
			'control' => array(
				'label' => __( 'Default Social Style', 'total' ),
				'type' => 'select',
				'choices' => $social_styles,
			),
		),
	)
);

// Archives
$this->sections['wpex_staff_archives'] = array(
	'title' => __( 'Archives', 'total' ),
	'panel' => 'wpex_staff',
	'desc' => __( 'The following options are for the post type category and tag archives.', 'total' ),
	'settings' => array(
		array(
			'id' => 'staff_archive_layout',
			'default' => 'full-width',
			'control' => array(
				'label' => __( 'Layout', 'total' ),
				'type' => 'select',
				'choices' => $post_layouts,
			),
		),
		array(
			'id' => 'staff_archive_grid_style',
			'default' => 'fit-rows',
			'control' => array(
				'label' => __( 'Grid Style', 'total' ),
				'type' => 'select',
				'choices' => array(
					'fit-rows' => __( 'Fit Rows','total' ),
					'masonry' => __( 'Masonry','total' ),
					'no-margins' => __( 'No Margins','total' ),
				),
			),
		),
		array(
			'id' => 'staff_archive_grid_equal_heights',
			'default' => '',
			'control' => array(
				'label' => __( 'Equal Heights', 'total' ),
				'type' => 'checkbox',
				'desc'   => __( 'Displays the content containers (with the title and excerpt) in equal heights. Will NOT work with the "Masonry" layouts.', 'total' ),
			),
		),
		array(
			'id' => 'staff_entry_columns',
			'default' => '3',
			'control' => array(
				'label' => __( 'Columns', 'total' ),
				'type' => 'select',
				'choices' => wpex_grid_columns(),
			),
		),
		array(
			'id' => 'staff_archive_grid_gap',
			'control' => array(
				'label' => __( 'Gap', 'total' ),
				'type' => 'select',
				'choices' => wpex_column_gaps(),
			),
		),
		array(
			'id' => 'staff_archive_posts_per_page',
			'default' => '12',
			'control' => array(
				'label' => __( 'Posts Per Page', 'total' ),
				'type' => 'text',
			),
		),
		array(
			'id' => 'staff_entry_overlay_style',
			'default' => 'none',
			'control' => array(
				'label' => __( 'Archives Entry: Image Overlay', 'total' ),
				'type' => 'select',
				'choices' => wpex_overlay_styles_array()
			),
		),
		array(
			'id' => 'staff_entry_details',
			'default' => true,
			'control' => array(
				'label' => __( 'Archives Entry: Details', 'total' ),
				'type' => 'checkbox',
			),
		),
		array(
			'id' => 'staff_entry_position',
			'default' => true,
			'control' => array(
				'label' => __( 'Archives Entry: Position', 'total' ),
				'type' => 'checkbox',
			),
		),
		array(
			'id' => 'staff_entry_excerpt_length',
			'default' => '20',
			'control' => array(
				'label' => __( 'Archives Entry: Excerpt Length', 'total' ),
				'type' => 'text',
				'desc' => __( 'Enter 0 or leave blank to disable', 'total' ),
			),
		),
		array(
			'id' => 'staff_entry_social',
			'default' => true,
			'control' => array(
				'label' => __( 'Archives Entry: Social Links', 'total' ),
				'type' => 'checkbox',
			),
		),
	),
);

// Single
$this->sections['wpex_staff_single'] = array(
	'title' => __( 'Single', 'total' ),
	'panel' => 'wpex_staff',
	'settings' => array(
		array(
			'id' => 'staff_single_layout',
			'default' => 'right-sidebar',
			'control' => array(
				'label' => __( 'Layout', 'total' ),
				'type' => 'select',
				'choices' => $post_layouts,
			),
		),
		array(
			'id' => 'staff_single_header_position',
			'default' => true,
			'control' => array(
				'label' => __( 'Display Position Under Title', 'total' ),
				'type' => 'checkbox',
			),
		),
		array(
			'id' => 'staff_next_prev',
			'default' => true,
			'control' => array(
				'label' => __( 'Next & Previous Links', 'total' ),
				'type' => 'checkbox',
			),
		),
		array(
			'id' => 'staff_related_title',
			'transport' => 'postMessage',
			'control' => array(
				'label' => __( 'Related Posts Title', 'total' ),
				'type' => 'text',
				'active_callback' => 'wpex_cac_has_staff_related',
			),
		),
		array(
			'id' => 'staff_related_count',
			'default' => '3',
			'control' => array(
				'label' => __( 'Related Posts Count', 'total' ),
				'type' => 'text',
				'active_callback' => 'wpex_cac_has_staff_related',
			),
		),
		array(
			'id' => 'staff_related_columns',
			'default' => '3',
			'control' => array(
				'label' => __( 'Related Posts Columns', 'total' ),
				'type' => 'select',
				'choices' => wpex_grid_columns(),
				'active_callback' => 'wpex_cac_has_staff_related',
			),
		),
		array(
			'id' => 'staff_related_excerpts',
			'default' => true,
			'control' => array(
				'label' => __( 'Related Posts Content', 'total' ),
				'type' => 'checkbox',
				'active_callback' => 'wpex_cac_has_staff_related',
			),
		),
		array(
			'id' => 'staff_post_composer',
			'default' => 'content,related',
			'control' => array(
				'label' => __( 'Post Layout Elements', 'total' ),
				'type' => 'wpex-sortable',
				'choices' => $blocks,
				'desc' => __( 'Click and drag and drop elements to re-order them.', 'total' ),
			),
		),
	),
);

// Unset vars
unset( $blocks );