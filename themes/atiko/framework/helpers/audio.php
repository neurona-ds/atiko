<?php
/**
 * Helper functions for getting and displaying audio
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.0
 */

/**
 * Returns post audio
 *
 * @since 2.0.0
 */
function wpex_get_post_audio( $id = '' ) {

	// Define video variable
	$audio = '';

	// Get correct ID
	$id = $id ? $id : get_the_ID();

	// Check for self-hosted first
	if ( $self_hosted = get_post_meta( $id, 'wpex_post_self_hosted_media', true ) ) {
		$audio = $self_hosted;
	}

	// Check for wpex_post_audio custom field
	elseif ( $post_audio = get_post_meta( $id, 'wpex_post_audio', true ) ) {
		$audio = $post_audio;
	}

	// Check for post oembed
	elseif ( $post_oembed = get_post_meta( $id, 'wpex_post_oembed', true ) ) {
		$audio = $post_oembed;
	}

	// Check old redux custom field last
	elseif ( $self_hosted = get_post_meta( $id, 'wpex_post_self_hosted_shortcode_redux', true ) ) {
		$audio = $self_hosted;
	}

	// Apply filters & return
	return apply_filters( 'wpex_get_post_audio', $audio );

}

/**
 * Echo post audio HTML
 *
 * @since 2.0.0
 */
function wpex_post_audio_html( $audio = '' ) {
	echo wpex_get_post_audio_html( $audio );
}

/**
 * Returns post audio
 *
 * @since 2.0.0
 */
function wpex_get_post_audio_html( $audio = '' ) {

	// Get audio
	$audio = $audio ? $audio : wpex_get_post_audio();

	// Return if audio is empty
	if ( ! $audio ) {
		return;
	}

	// Get oembed code and return
	if ( ! is_wp_error( $oembed = wp_oembed_get( $audio ) ) && $oembed ) {
		return '<div class="responsive-audio-wrap">'. $oembed .'</div>';
	}

	// Display using oembed if self-hosted
	else {
		$audio = ( is_numeric( $audio ) ) ? wp_get_attachment_url( $audio ) : $audio;
		return wp_audio_shortcode( array(
			'src' => $audio,
		) );
	}

}