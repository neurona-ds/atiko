<?php
/**
 * Edit post link
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 3.6.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Edit text
if ( is_page() ) {
    $edit_text = esc_html__( 'Edit This Page', 'total' );
} elseif( is_singular( 'post' ) ) {
    $edit_text = esc_html__( 'Edit This Post', 'total' );
} else {
	$edit_text = null;
}

// Display edit post link
edit_post_link(
    $edit_text,
    '<div class="post-edit clr">', ' <a href="#" class="hide-post-edit" title="'. esc_html__( 'Hide Post Edit Links', 'total' ) .'" aria-hidden="true"><span class="fa fa-times"></span></a></div>'
);