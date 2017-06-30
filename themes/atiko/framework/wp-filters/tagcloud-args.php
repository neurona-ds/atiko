<?php
/**
 * Alters the default WordPress tag cloud widget arguments.
 * Makes sure all font sizes for the cloud widget are set to 1em.
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wpex_widget_tag_cloud_args( $args ) {
	$args['largest']  = '0.923';
	$args['smallest'] = '0.923';
	$args['unit']     = 'em';
	return $args;
}
add_filter( 'widget_tag_cloud_args', 'wpex_widget_tag_cloud_args' );