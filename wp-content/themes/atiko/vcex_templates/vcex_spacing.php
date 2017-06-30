<?php
/**
 * Visual Composer Spacing
 *
 * @package Total WordPress Theme
 * @subpackage VC Templates
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Not needed in admin ever
if ( is_admin() ) {
    return;
}

// Get and extract shortcode attributes
extract( vc_map_get_attributes( 'vcex_spacing', $atts ) );

// Core class
$classes = 'vcex-spacing';

// Custom Class
if ( $class ) {
    $classes .= ' '. vcex_get_extra_class( $class );
}

// Visiblity Class
if ( $visibility ) {
    $classes .= ' '. $visibility;
}

// Front-end composer class
if ( wpex_vc_is_inline() ) {
    $classes .= ' vc-spacing-shortcode';
}

// Apply filters
$classes = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $classes, 'vcex_spacing', $atts );

// Echo output
echo '<div class="'. $classes .'" style="height:'. wpex_sanitize_data( $size, 'px-pct' ) .'"></div>';