<?php
/**
 * Returns the post title
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define title args
$args = array();

// Single post markup
if ( is_singular( 'post' ) ) {
	$blog_single_header = wpex_get_mod( 'blog_single_header', 'custom_text' );
	if ( 'custom_text' == $blog_single_header || 'first_category' == $blog_single_header ) {
		$args['html_tag'] = 'span';
		$args['schema_markup'] = '';
	}
}

// Singular CPT
elseif ( is_singular() && ( ! is_singular( 'post' ) && ! is_singular( 'page' ) && ! is_singular( 'attachment' ) ) ) {
	$args['html_tag'] = 'span';
	$args['schema_markup'] = '';
}

// Apply filters
$args = apply_filters( 'wpex_page_header_title_args', $args );

// Parse args to prevent empty attributes and extract
extract( wp_parse_args( $args, array(
	'html_tag'      => 'h1',
	'string'        => wpex_title(),
	'schema_markup' => wpex_get_schema_markup( 'headline' )
) ) );

// Display title
if ( ! empty( $string ) ) {
	echo '<'. strip_tags( $html_tag ) .' class="page-header-title wpex-clr"'. $schema_markup .'><span>'. wpex_sanitize_data( $string, 'html' ) .'</span></'. strip_tags( $html_tag ) .'>';
}