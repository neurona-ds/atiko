<?php
/**
 * Main Loop
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 3.6.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Add to counter
global $wpex_count;
$wpex_count++;

// Include template part
wpex_get_template_part( 'cpt_entry', get_post_type() );

// Reset counter to clear floats
if ( wpex_get_grid_entry_columns() == $wpex_count ) {
	$wpex_count=0;
}