<?php
/**
 * Enqueue front end theme scripts [CSS & JS]
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Core theme CSS
 *
 * @since 4.0
 */
function wpex_enqueue_front_end_main_css() {

	// Remove other font awesome scripts
	wp_deregister_style( 'font-awesome' );
	wp_deregister_style( 'fontawesome' );

	// Register hover-css for use with shortcodes
	wp_register_style(
		'wpex-hover-animations',
		wpex_asset_url( 'lib/hover-css/hover-css.min.css' ),
		array(),
		'2.0.1'
	);

	// Main style.css File
	wp_enqueue_style(
		WPEX_THEME_STYLE_HANGLE,
		get_stylesheet_uri(),
		array(),
		WPEX_THEME_VERSION
	);

}
add_action( 'wp_enqueue_scripts', 'wpex_enqueue_front_end_main_css' );

/**
 * Browser dependent CSS
 *
 * @since 4.0
 */
function wpex_enqueue_front_end_browser_dependent_css() {

	// IE8 Stylesheet
	wp_enqueue_style( 'wpex-ie8',
		apply_filters( 'wpex_ie8_stylesheet', wpex_asset_url( 'css/wpex-ie8.css' ) ),
		false,
		WPEX_THEME_VERSION
	);
	wp_style_add_data( 'wpex-ie8', 'conditional', 'IE 8' );

	// IE9 Stylesheet
	wp_enqueue_style( 'wpex-ie9',
		apply_filters( 'wpex_ie9_stylesheet', wpex_asset_url( 'css/wpex-ie9.css' ) ),
		false,
		WPEX_THEME_VERSION
	);
	wp_style_add_data( 'wpex-ie9', 'conditional', 'IE 9' );

}
add_action( 'wp_enqueue_scripts', 'wpex_enqueue_front_end_browser_dependent_css', 40 );

/**
 * Load RTL CSS right before responsive to prevent conflicts
 *
 * @since 4.0
 */
function wpex_enqueue_front_end_rtl_css() {

	if ( ! is_RTL() ) {
		return;
	}

	wp_enqueue_style(
		'wpex-rtl',
		wpex_asset_url( 'css/wpex-rtl.css' ),
		array(),
		WPEX_THEME_VERSION
	);

}
add_action( 'wp_enqueue_scripts', 'wpex_enqueue_front_end_rtl_css', 98 );

/**
 * Load responsive CSS => must be added last!
 *
 * @since 4.0
 */
function wpex_enqueue_front_end_responsive_css() {
	
	wp_enqueue_style(
		'wpex-responsive',
		wpex_asset_url( 'css/wpex-responsive.css' ),
		array(),
		WPEX_THEME_VERSION
	);

}

if ( wpex_is_layout_responsive() ) {
	add_action( 'wp_enqueue_scripts', 'wpex_enqueue_front_end_responsive_css', 99 );
}

/**
 * Load theme js => High priority so that it loads after after js_composer
 *
 * @since 4.0
 */
function wpex_enqueue_front_end_js() {

	// First lets make sure html5 shiv is on the site
	wp_enqueue_script(
		'wpex-html5shiv',
		wpex_asset_url( 'js/dynamic/html5.js' ),
		array(),
		WPEX_THEME_VERSION,
		false
	);
	wp_script_add_data( 'wpex-html5shiv', 'conditional', 'lt IE 9' );

	// Get localized array
	$localize_array = wpex_js_localize_data();

	// Comment reply
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// Load minified js
	if ( wpex_get_mod( 'minify_js_enable', true ) ) {
		wp_enqueue_script( WPEX_THEME_JS_HANGLE, wpex_asset_url( 'js/wpex.min.js' ), array( 'jquery' ), WPEX_THEME_VERSION, true );
	}

	// Load all non-minified js
	else {

		// Touch Swipe - do we need it? @todo
		wp_enqueue_script( 'wpex-touch-swipe', wpex_asset_url( 'js/core/touch-swipe.js' ), array( 'jquery' ), WPEX_THEME_VERSION, true );

		wp_enqueue_script( 'wpex-tocca', wpex_asset_url( 'js/core/Tocca.min.js' ), array( 'jquery' ), '1.0.0', true );

		wp_enqueue_script( 'wpex-easing', wpex_asset_url( 'js/core/jquery.easing.js' ), array( 'jquery' ), '1.3.2', true );

		wp_enqueue_script( 'wpex-superfish', wpex_asset_url( 'js/core/superfish.js' ), array( 'jquery' ), WPEX_THEME_VERSION, true );

		wp_enqueue_script( 'wpex-supersubs', wpex_asset_url( 'js/core/supersubs.js' ), array( 'jquery' ), WPEX_THEME_VERSION, true );

		wp_enqueue_script( 'wpex-hoverintent', wpex_asset_url( 'js/core/hoverintent.js' ), array( 'jquery' ), WPEX_THEME_VERSION, true );

		wp_enqueue_script( 'wpex-tipsy', wpex_asset_url( 'js/core/tipsy.js' ), array( 'jquery' ), WPEX_THEME_VERSION, true );

		wp_enqueue_script( 'wpex-typed', wpex_asset_url( 'js/core/typed.js' ), array( 'jquery' ), WPEX_THEME_VERSION, true );

		wp_enqueue_script( 'wpex-images-loaded', wpex_asset_url( 'js/core/images-loaded.js' ), array( 'jquery' ), WPEX_THEME_VERSION, true );

		wp_enqueue_script( 'wpex-isotope', wpex_asset_url( 'js/core/isotope.js' ), array( 'jquery' ), '2.2.2', true );

		wp_enqueue_script( 'wpex-leanner-modal', wpex_asset_url( 'js/core/leanner-modal.js' ), array( 'jquery' ), WPEX_THEME_VERSION, true );

		wp_enqueue_script( 'wpex-sliderpro', wpex_asset_url( 'js/core/jquery.sliderPro.js' ), array( 'jquery' ), '1.3', true );

		wp_enqueue_script( 'wpex-sliderpro-customthumbnails', wpex_asset_url( 'js/core/jquery.sliderProCustomThumbnails.js' ), array( 'jquery', 'wpex-sliderpro' ), false, true );

		wp_enqueue_script( 'wpex-owl-carousel', wpex_asset_url( 'js/core/wpex.owl.carousel.js' ), array( 'jquery' ), WPEX_THEME_VERSION, true );

		wp_enqueue_script( 'wpex-countUp', wpex_asset_url( 'js/core/countUp-jquery.js' ), array( 'jquery' ), WPEX_THEME_VERSION, true );

		wp_enqueue_script( 'wpex-appear', wpex_asset_url( 'js/core/appear.js' ), array( 'jquery' ), WPEX_THEME_VERSION, true );

		wp_enqueue_script( 'wpex-sidr', wpex_asset_url( 'js/core/sidr.js' ), array( 'jquery' ), WPEX_THEME_VERSION, true );

		wp_enqueue_script( 'wpex-custom-select', wpex_asset_url( 'js/core/jquery.customSelect.js' ), array( 'jquery' ), WPEX_THEME_VERSION, true );

		wp_enqueue_script( 'wpex-equal-heights', wpex_asset_url( 'js/core/jquery.wpexEqualHeights.js' ), array( 'jquery' ), WPEX_THEME_VERSION, true );

		wp_enqueue_script( 'wpex-mousewheel', wpex_asset_url( 'js/core/jquery.mousewheel.js' ), array( 'jquery' ), WPEX_THEME_VERSION, true );

		wp_enqueue_script( 'wpex-scrolly', wpex_asset_url( 'js/core/scrolly.js' ), array( 'jquery' ), WPEX_THEME_VERSION, true );

		wp_enqueue_script( 'wpex-ilightbox', wpex_asset_url( 'js/core/ilightbox.js' ), array( 'jquery' ), WPEX_THEME_VERSION, true );

		// Core global functions
		wp_enqueue_script( WPEX_THEME_JS_HANGLE, wpex_asset_url( 'js/functions.js' ), array( 'jquery' ), WPEX_THEME_VERSION, true );

	}

	// Localize core js
	wp_localize_script( WPEX_THEME_JS_HANGLE, 'wpexLocalize', $localize_array );

	// Retina.js
	if ( wpex_is_retina_enabled() ) {
		wp_enqueue_script( 'wpex-retina', wpex_asset_url( 'js/dynamic/retina.js' ), array( 'jquery' ), '0.0.2', true );
		wp_localize_script( 'wpex-retina', 'wpexRetina', array(
			'mode' => wpex_get_mod( 'retina_mode', 1 )
		) );
	}

}
add_action( 'wp_enqueue_scripts', 'wpex_enqueue_front_end_js' );