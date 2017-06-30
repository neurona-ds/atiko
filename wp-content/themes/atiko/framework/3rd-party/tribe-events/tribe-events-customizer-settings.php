<?php
/**
 * Tribe Events Customizer Options
 *
 * @package Total WordPress Theme
 * @subpackage Custom Post Types UI - Taxonomy Settings
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// General
$this->sections['wpex_tribe_events'] = array(
	'title' => __( 'Tribe Events', 'total' ),
	'settings' => array(
		array(
			'id' => 'tribe_events_main_page',
			'default' => '',
			'control' => array(
				'label' => __( 'Main Page', 'total' ),
				'type' => 'dropdown-pages',
			),
		),
		array(
			'id' => 'tribe_events_archive_layout',
			'default' => 'full-width',
			'control' => array(
				'label' => __( 'Archives Layout', 'total' ),
				'type' => 'select',
				'choices' => $post_layouts,
			),
		),
		array(
			'id' => 'tribe_events_single_layout',
			'default' => 'full-width',
			'control' => array(
				'label' => __( 'Single Layout', 'total' ),
				'type' => 'select',
				'choices' => $post_layouts,
			),
		),
		array(
			'id' => 'tribe_events_page_header_details',
			'default' => 'true',
			'control' => array(
				'label' => __( 'Display Event Details in Page Header', 'total' ),
				'type' => 'checkbox',
			),
		),
	),
);