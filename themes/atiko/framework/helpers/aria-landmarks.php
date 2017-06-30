<?php
/**
 * Helper function for adding aria landmarks
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.2
 */


function wpex_aria_landmark( $location ) {
	echo wpex_get_aria_landmark( $location );
}

function wpex_get_aria_landmark( $location ) {

	if ( ! wpex_get_mod( 'aria_landmarks_enable', false ) ) {
		return;
	}

	$landmark = '';

	if ( $location == 'header' ) {
		$landmark = 'role="banner"';
	}

	elseif ( $location == 'site_navigation' ) {
		$landmark = 'role="navigation" aria-label="' . esc_attr__( 'Primary Menu', 'total' ) . '"';
	}

	elseif ( $location == 'searchform' ) {
		$landmark = 'role="search"';
	}

	elseif ( $location == 'main' ) {
		$landmark = 'role="main"';
	}

	elseif ( $location == 'main' ) {
		$landmark = 'role="main"';
	}

	elseif ( $location == 'sidebar' ) {
		$landmark = 'role="complementary"';
	}

	elseif ( $location == 'copyright' ) {
		$landmark = 'role="contentinfo"';
	}

	elseif ( $location == 'footer_bottom_menu' ) {
		$landmark = 'role="navigation" aria-label="' . esc_attr__( 'Footer Menu', 'total' ) . '"';
	}

	$landmark = apply_filters( 'wpex_get_aria_landmark', $landmark, $location );

	if ( $landmark ) {
		return ' ' . $landmark;
	}

}