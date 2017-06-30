<?php
/**
 * Tribe Events Customizer Options
 *
 * @package Total WordPress Theme
 * @subpackage Custom Post Types UI - Taxonomy Settings
 * @version 3.6.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Add settings
$this->sections['wpex_bbpress'] = array(
	'title' => esc_html__( 'bbPress', 'total' ),
	'settings' => array(
		array(
			'id' => 'bbpress_forums_layout',
			'transport' => 'refresh',
			'control' => array(
				'label' => esc_html__( 'Forum Archive Layout', 'total' ),
				'type' => 'select',
				'choices' => $post_layouts,
			),
		),
		array(
			'id' => 'bbpress_single_forum_layout',
			'transport' => 'refresh',
			'control' => array(
				'label' => esc_html__( 'Single Forum Layout', 'total' ),
				'type' => 'select',
				'choices' => $post_layouts,
			),
		),
		array(
			'id' => 'bbpress_topics_layout',
			'transport' => 'refresh',
			'control' => array(
				'label' => esc_html__( 'Topics Archive Layout', 'total' ),
				'type' => 'select',
				'choices' => $post_layouts,
			),
		),
		array(
			'id' => 'bbpress_single_topic_layout',
			'transport' => 'refresh',
			'control' => array(
				'label' => esc_html__( 'Single Topic Layout', 'total' ),
				'type' => 'select',
				'choices' => $post_layouts,
			),
		),
		array(
			'id' => 'bbpress_user_layout',
			'transport' => 'refresh',
			'control' => array(
				'label' => esc_html__( 'User Page Layout', 'total' ),
				'type' => 'select',
				'choices' => $post_layouts,
			),
		),
	)
);