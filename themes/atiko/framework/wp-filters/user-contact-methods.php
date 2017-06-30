<?php
/**
 * Adds custom fields to the user contact methods
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wpex_user_contactmethods( $contactmethods ) {

	// Get settings to add
	$settings = wpex_get_user_social_profile_settings_array();

	if ( ! $settings ) {
		return $contactmethods;
	}

	// Get theme branding
	$branding = wpex_get_theme_branding();
	$branding = $branding ? $branding . ' - ' : '';

	// Loop through and add settings
	foreach ( $settings as $id => $label ) {
		$contactmethods[ 'wpex_'. $id ] = $branding . $label;
	}

	// Return fields
	return $contactmethods;

}
add_filter( 'user_contactmethods', 'wpex_user_contactmethods' );