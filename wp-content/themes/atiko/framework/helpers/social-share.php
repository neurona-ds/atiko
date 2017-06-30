<?php
/**
 * Social share functions
 *
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.0
 */

/**
 * Returns social sharing sites
 *
 * @since 2.0.0
 */
function wpex_social_share_sites() {
    $sites = wpex_get_mod( 'social_share_sites', array( 'twitter', 'facebook', 'google_plus', 'pinterest' ) );
    $sites = apply_filters( 'wpex_social_share_sites', $sites );
    if ( $sites && ! is_array( $sites ) ) {
        $sites = explode( ',', $sites );
    }
    return $sites;
}

/**
 * Returns correct social share position
 *
 * @since 2.0.0
 */
function wpex_social_share_position() {
    $position = wpex_get_mod( 'social_share_position' );
    $position = $position ? $position : 'horizontal';
    return apply_filters( 'wpex_social_share_position', $position );
}

/**
 * Returns correct social share style
 *
 * @since 2.0.0
 */
function wpex_social_share_style() {
    $style = wpex_get_mod( 'social_share_style' );
    $style = $style ? $style : 'flat';
    return apply_filters( 'wpex_social_share_style', $style );
}

/**
 * Returns the social share heading
 *
 * @since 2.0.0
 */
function wpex_social_share_heading() {
    $heading = wpex_get_translated_theme_mod( 'social_share_heading', __( 'Please Share This', 'total' ) );
    return apply_filters( 'wpex_social_share_heading', $heading );
}