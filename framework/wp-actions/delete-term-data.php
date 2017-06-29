<?php
/**
 * Redirect single posts if redirect custom field is being used.
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wpex_delete_term_data( $term_id ) {

	// Sanitize term ID
	$term_id = absint( $term_id );

	// If term id is defined
	if ( $term_id ) {

		// Get terms data
		$term_data = get_option( 'wpex_term_data' );

		// Remove key with term data
		if ( $term_data && isset( $term_data[$term_id] ) ) {
			unset( $term_data[$term_id] );
			update_option( 'wpex_term_data', $term_data );
		}

	}

}
add_action( 'delete_term', 'wpex_delete_term_data', 5 );