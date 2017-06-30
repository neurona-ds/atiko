<?php
/**
 * Topbar social profiles
 *
 * @package Total WordPress Theme
 * @subpackage Partials
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Return if disabled
if ( ! wpex_get_mod( 'top_bar_social', true ) && ! wpex_topbar_social_alt_content() )  {
	return;
}

// Get social options array
$social_options = wpex_topbar_social_options();

// Return if $social_options array is empty
if ( empty( $social_options ) ) {
	return;
}

// Add classes based on topbar style
$classes = '';
$topbar_style = wpex_get_mod( 'top_bar_style', 'one' );
if ( 'one' == $topbar_style ) {
	$classes = 'top-bar-right';
} elseif ( 'two' == $topbar_style ) {
	$classes = 'top-bar-left';
} elseif ( 'three' == $topbar_style ) {
	$classes = 'top-bar-centered';
}

// Display Social alternative
if ( $social_alt = wpex_topbar_social_alt_content() ) : ?>

	<div id="top-bar-social-alt" class="clr <?php echo esc_attr( $classes ); ?>"><?php

		echo do_shortcode( $social_alt );

	?></div><!-- #top-bar-social-alt -->

<?php return; endif; ?>

<?php
// Get Social options
$profiles = wpex_get_mod( 'top_bar_social_profiles' );

// Return if there aren't any profiles defined and define var
if ( ! $profiles ) {
	return;
}

// Get theme mods
$style               = wpex_get_mod( 'top_bar_social_style' );
$style               = $style ? $style : 'none';
$link_target         = wpex_get_mod( 'top_bar_social_target', 'blank' );
$social_button_class = wpex_get_social_button_class( $style );
if ( $style == 'colored-icons' ) {
	$colored_icons_url = apply_filters( 'top_bar_social_img_url', wpex_asset_url( '/images/social' ) );
} ?>

<div id="top-bar-social" class="clr <?php echo esc_attr( $classes ); ?> social-style-<?php echo esc_attr( $style ); ?>">

	<?php
	// Loop through social options
	foreach ( $social_options as $key => $val ) :

		// Get URL from the theme mods
		$url = isset( $profiles[$key] ) ? $profiles[$key] : '';

		// Display if there is a value defined
		if ( $url ) :

			// Sanitize key
			$key = esc_html( $key );

			// Escape URL except for the following keys
			if ( ! in_array( $key, array( 'skype', 'email', 'phone' ) ) ) {
				$url = esc_url( $url );
			}

			// Sanitize email and remove link target
			if ( 'email' == $key ) {
				$sanitize_email = sanitize_email( $url );
				if ( is_email( $url ) ) {
					$link_target = '';
					$sanitize_email = antispambot( $sanitize_email );
					$url = 'mailto:'. $sanitize_email;
				} elseif( strpos( $url, 'mailto' ) !== false ) {
					$link_target = '';
				}
			}

			// Sanitize phone number
			if ( 'phone' == $key && strpos( $url, 'tel' ) == false ) {
				$url = 'tel:'. $url;
			}

			// Parse attributes for links
			$attrs = apply_filters( 'wpex_topbar_social_link_attrs', array(
				'href'   => $url,
				'title'  => esc_attr( $val['label'] ),
				'target' => $link_target,
				'class'  => 'wpex-'. $key .' '. $social_button_class,
			), $key );

			// Set link content
			if ( $style == 'colored-icons' ) {

				$content = '<img src="'. esc_url( $colored_icons_url ) .'/'. $key .'.png" alt="'. esc_attr( $val['label'] ) .'" />';

			// Font Awesome Icons
			} else {

				$content = '<span class="'. esc_attr( $val['icon_class'] ) .'" aria-hidden="true"></span><span class="screen-reader-text">'. esc_attr( $val['label'] ) .'</span>';

			}

			// Generate link HTML based on attributes and content
			echo wpex_parse_html( 'a', $attrs, $content );

		endif;

	endforeach; ?>

</div><!-- #top-bar-social -->