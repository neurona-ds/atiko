<?php
/**
 * Display dashboard thumbnails if enabled
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Return if disabled
if ( ! apply_filters( 'wpex_dashboard_thumbnails', true ) ) {
	return;			
}

function wpex_posts_columns( $defaults ){
	$defaults['wpex_post_thumbs'] = esc_html__( 'Featured Image', 'total' );
	return $defaults;
}

add_filter( 'manage_post_posts_columns', 'wpex_posts_columns' );
add_filter( 'manage_page_posts_columns', 'wpex_posts_columns' );

// @todo move these to config files? Use filter to loop through dashboard types?
// @todo add support for Custom Post Types UI plugin
add_filter( 'manage_portfolio_posts_columns', 'wpex_posts_columns' );
add_filter( 'manage_testimonials_posts_columns', 'wpex_posts_columns' );
add_filter( 'manage_staff_posts_columns', 'wpex_posts_columns' );

function wpex_posts_custom_columns( $column_name, $id ) {
	if ( $column_name == 'wpex_post_thumbs' ) {
		if ( has_post_thumbnail( $id ) ) {
			the_post_thumbnail(
				'thumbnail',
				array( 'style' => 'width:80px;height:80px;max-width:100%;' )
			);
		} else {
			echo '&mdash;';
		}
	}
}
add_action( 'manage_posts_custom_column', 'wpex_posts_custom_columns', 10, 2 );
add_action( 'manage_pages_custom_column', 'wpex_posts_custom_columns', 10, 2 );