<?php
/**
 * Breadcrumbs output
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 4.0
 */

// Return if breadcrumbs are disabled
if ( ! wpex_has_breadcrumbs() ) {
	return;
}

// Custom breadcrumbs
if ( $custom_breadcrumbs = apply_filters( 'wpex_custom_breadcrumbs', null ) ) {
	echo wp_kses_post( $custom_breadcrumbs );
	return;
}

if ( class_exists( 'WPEX_Breadcrumbs' ) ) {

	// Generate breadcrumbs (stores trail in $ouput var)
	$breadcrumbs = new WPEX_Breadcrumbs();

	// Echo breadcrumbs
	echo $breadcrumbs->output;

}