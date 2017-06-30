<?php
/**
 * Blog single post related heading
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 3.6.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Output heading
wpex_heading( array(
	'content'		=> wpex_blog_related_heading(),
	'classes'		=> array( 'related-posts-title' ),
	'apply_filters'	=> 'blog_related',
) );