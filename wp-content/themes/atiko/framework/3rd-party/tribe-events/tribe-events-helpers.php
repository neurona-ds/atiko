<?php
/**
 * Helper functions for tribe events
 *
 * @package Total WordPress Theme
 * @subpackage Configs
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check if currently on a tribe events page
 *
 * @since 4.0
 */
function wpex_is_tribe_events() {
	if ( is_search() ) {
		return false; // fixes some bugs
	}
	if ( tribe_is_event()
		|| tribe_is_event_category()
		|| tribe_is_in_main_loop()
		|| tribe_is_view()
		|| tribe_is_day()
		|| is_singular( 'tribe_events' ) ) {
		return true;
	}
}

/**
 * Displays event date
 *
 * @since 3.3.3
 */
function wpex_get_tribe_event_date( $instance = '' ) {
	return apply_filters(
		'wpex_get_tribe_event_date',
		tribe_get_start_date( get_the_ID(), false, get_option( 'date_format' ) ),
		$instance
	);
}

/**
 * Gets correct tribe events page ID
 *
 * @since 3.3.3
 */
function wpex_get_tribe_events_main_page_id() {

	// Check customizer setting
	if ( $mod = wpex_get_mod( 'tribe_events_main_page' ) ) {
		return $mod;
	}

	// Check from slug
	elseif ( class_exists( 'Tribe__Settings_Manager' ) ) {
		$page_slug = Tribe__Settings_Manager::get_option( 'eventsSlug', 'events' );
		if ( $page_slug && $page = get_page_by_path( $page_slug ) ) {
			return $page->ID;
		}
	}

}