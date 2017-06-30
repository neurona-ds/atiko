<?php
/**
 * Adds schema markup to the authors post link
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wpex_author_posts_link_schema( $link ) {

	// Add schema markup
	$schema = wpex_get_schema_markup( 'author_link' );
	if ( $schema ) {
		$link = str_replace( 'rel="author"', 'rel="author"' . $schema, $link );
	}

	// Return link
	return $link;

}
add_filter( 'the_author_posts_link', 'wpex_author_posts_link_schema' );