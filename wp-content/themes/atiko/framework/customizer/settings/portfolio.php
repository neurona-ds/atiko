<?php
/**
 * Portfolio Customizer Options
 *
 * @package Total WordPress Theme
 * @subpackage Customizer
 * @version 3.6.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Single Blocks
$blocks = apply_filters( 'wpex_portfolio_single_blocks', array(
	'title'    => __( 'Post Title', 'total' ),
	'meta'     => __( 'Post Meta', 'total' ),
	'media'    => __( 'Media', 'total' ),
	'content'  => __( 'Content', 'total' ),
	'share'    => __( 'Social Share', 'total' ),
	'comments' => __( 'Comments', 'total' ),
	'related'  => __( 'Related Posts', 'total' ),
) );

// Archives
$this->sections['wpex_portfolio_archives'] = array(
	'title' => __( 'Archives & Entries', 'total' ),
	'panel' => 'wpex_portfolio',
	'desc' => __( 'The following options are for the post type category and tag archives.', 'total' ),
	'settings' => array(
		array(
			'id' => 'portfolio_archive_layout',
			'default' => 'full-width',
			'control' => array(
				'label' => __( 'Layout', 'total' ),
				'type' => 'select',
				'choices' => $post_layouts,
			),
		),
		array(
			'id' => 'portfolio_archive_grid_style',
			'default' => 'fit-rows',
			'control' => array(
				'label' => __( 'Grid Style', 'total' ),
				'type' => 'select',
				'choices'   => array(
					'fit-rows' => __( 'Fit Rows','total' ),
					'masonry' => __( 'Masonry','total' ),
					'no-margins' => __( 'No Margins','total' ),
				),
			),
		),
		array(
			'id' => 'portfolio_entry_columns',
			'default' => '4',
			'control' => array(
				'label' => __( 'Columns', 'total' ),
				'type' => 'select',
				'choices' => wpex_grid_columns(),
			),
		),
		array(
			'id' => 'portfolio_archive_grid_gap',
			'control' => array(
				'label' => __( 'Gap', 'total' ),
				'type' => 'select',
				'choices' => wpex_column_gaps(),
			),
		),
		array(
			'id' => 'portfolio_archive_grid_equal_heights',
			'default' => '',
			'control' => array(
				'label' => __( 'Equal Heights', 'total' ),
				'type' => 'checkbox',
				'active_callback' => 'wpex_portfolio_style_supports_equal_heights',
			),
		),
		array(
			'id' => 'portfolio_archive_posts_per_page',
			'default' => '12',
			'control' => array(
				'label' => __( 'Posts Per Page', 'total' ),
				'type' => 'text',
			),
		),
		array(
			'id' => 'portfolio_entry_overlay_style',
			'default' => 'none',
			'control' => array(
				'label' => __( 'Archives Entry: Image Overlay', 'total' ),
				'type' => 'select',
				'choices' => wpex_overlay_styles_array(),
			),
		),
		array(
			'id' => 'portfolio_entry_details',
			'default' => true,
			'control' => array(
				'label' => __( 'Archives Entry: Details', 'total' ),
				'type' => 'checkbox',
			),
		),
		array(
			'id' => 'portfolio_entry_excerpt_length',
			'default' => '20',
			'control' => array(
				'label' => __( 'Archives Entry: Excerpt Length', 'total' ),
				'type' => 'text',
			),
		),
	),
);


// Single
$this->sections['wpex_portfolio_single'] = array(
	'title' => __( 'Single', 'total' ),
	'panel' => 'wpex_portfolio',
	'settings' => array(
		array(
			'id' => 'portfolio_single_layout',
			'default' => 'full-width',
			'control' => array(
				'label' => __( 'Layout', 'total' ),
				'type' => 'select',
				'choices' => $post_layouts,
			),
		),
		array(
			'id' => 'portfolio_next_prev',
			'default' => true,
			'control' => array(
				'label' => __( 'Next & Previous Links', 'total' ),
				'type' => 'checkbox',
			),
		),
		array(
			'id' => 'portfolio_related_title',
			'transport' => 'postMessage',
			'default' => __( 'Related Projects', 'total' ),
			'control' => array(
				'label' => __( 'Related Posts Title', 'total' ),
				'type' => 'text',
				'active_callback' => 'wpex_cac_has_portfolio_related',
			),
		),
		array(
			'id' => 'portfolio_related_count',
			'default' => 4,
			'control' => array(
				'label' => __( 'Related Posts Count', 'total' ),
				'type' => 'number',
				'active_callback' => 'wpex_cac_has_portfolio_related',
			),
		),
		array(
			'id' => 'portfolio_related_columns',
			'default' => '4',
			'control' => array(
				'label' => __( 'Related Posts Columns', 'total' ),
				'type' => 'select',
				'choices'   => wpex_grid_columns(),
				'active_callback' => 'wpex_cac_has_portfolio_related',
			),
		),
		array(
			'id' => 'portfolio_related_excerpts',
			'default' => true,
			'control' => array(
				'label' => __( 'Related Posts Content', 'total' ),
				'type' => 'checkbox',
				'active_callback' => 'wpex_cac_has_portfolio_related',
			),
		),
		array(
			'id' => 'portfolio_post_composer',
			'default' => 'content,share,related',
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