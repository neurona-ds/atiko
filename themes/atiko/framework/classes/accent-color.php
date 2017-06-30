<?php
/**
 * Adds custom CSS to the site to tweak the main accent colors
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start Class
if ( ! class_exists( 'WPEX_Accent_Color' ) ) {
	
	class WPEX_Accent_Color {

		/**
		 * Main constructor
		 *
		 * @since 2.0.0
		 */
		public function __construct() {
			if ( is_customize_preview() ) {
				add_action( 'wp_head', array( 'WPEX_Accent_Color', 'customizer_css' ), 10 );
			} else {
				add_filter( 'wpex_head_css', array( 'WPEX_Accent_Color', 'live_css' ), 1 );
			}
		}

		/**
		 * Generates arrays of elements to target
		 *
		 * @since 2.0.0
		 */
		private static function arrays( $return ) {

			// Texts
			$texts = apply_filters( 'wpex_accent_texts', array(
				'a',
				'.wpex-accent-color',
				'#site-navigation .dropdown-menu a:hover',
				'#site-navigation .dropdown-menu > .current-menu-item > a',
				'#site-navigation .dropdown-menu > .current-menu-parent > a',
				'h1 a:hover',
				'h2 a:hover',
				'a:hover h2',
				'h3 a:hover',
				'h4 a:hover',
				'h5 a:hover',
				'h6 a:hover',
				'.entry-title a:hover',
				'.modern-menu-widget a:hover',
				'.theme-button.outline',
				'.theme-button.clean',
			) );

			// Backgrounds
			$backgrounds = apply_filters( 'wpex_accent_backgrounds', array(
				// #4a97c2
				'.wpex-accent-bg',
				'.background-highlight',
				'input[type="submit"]',
				'.theme-button',
				'button',
				'.theme-button.outline:hover',
				'.active .theme-button',
				'.theme-button.active',
				'#main .tagcloud a:hover',
				'.post-tags a:hover',
				'.wpex-carousel .owl-dot.active',
				'.navbar-style-one .menu-button > a > span.link-inner',
				'.wpex-carousel .owl-prev',
				'.wpex-carousel .owl-next',
				'body #header-two-search #header-two-search-submit',
				// #3b86b0
				'.theme-button:hover',
				'.modern-menu-widget li.current-menu-item a',
				'#sidebar .widget_nav_menu .current-menu-item > a',
				'#wp-calendar caption',
				'#site-scroll-top:hover',
				'input[type="submit"]:hover',
				'button:hover',
				'.wpex-carousel .owl-prev:hover',
				'.wpex-carousel .owl-next:hover',
				'#site-navigation .menu-button > a > span.link-inner',
				'#site-navigation .menu-button > a > span.link-inner:hover',
				'.navbar-style-six .dropdown-menu > .current-menu-item > a',
				'.navbar-style-six .dropdown-menu > .current-menu-parent > a',
			) );

			// Borders
			$borders = apply_filters( 'wpex_accent_borders', array(
				'.theme-button.outline',
				'#searchform-dropdown',
				'.toggle-bar-btn:hover' => array( 'top', 'right' ),
				'body #site-navigation-wrap.nav-dropdown-top-border .dropdown-menu > li > ul' => array( 'top' ),
				'.theme-heading.border-w-color span.text' => array( 'bottom' ),
			) );

			// Return array
			if ( 'texts' == $return ) {
				return $texts;
			} elseif ( 'backgrounds' == $return ) {
				return $backgrounds;
			} elseif ( 'borders' == $return ) {
				return $borders;
			}


		}

		/**
		 * Generates the CSS output
		 *
		 * @since 2.0.0
		 */
		private static function generate() {

			// Get custom accent
			$default_accent = '#3b86b0';
			$custom_accent  = wpex_get_mod( 'accent_color' );

			// Return if accent color is empty or equal to default
			if ( ! $custom_accent || ( $default_accent == $custom_accent ) ) {
				return;
			}

			// Define empty css var
			$css = '';

			// Get arrays
			$texts       = self::arrays( 'texts' );
			$backgrounds = self::arrays( 'backgrounds' );
			$borders     = self::arrays( 'borders' );

			// Texts
			if ( ! empty( $texts ) ) {
				$css .= implode( ',', $texts ) .'{color:'. $custom_accent .';}';
			}

			// Backgrounds
			if ( ! empty( $backgrounds ) ) {
				$css .= implode( ',', $backgrounds ) .'{background-color:'. $custom_accent .';}';
			}

			// Borders
			if ( ! empty( $borders ) ) {
				foreach ( $borders as $key => $val ) {
					if ( is_array( $val ) ) {
						$css .= $key .'{';
						foreach ( $val as $key => $val ) {
							$css .= 'border-'. $val .'-color:'. $custom_accent .';';
						}
						$css .= '}'; 
					} else {
						$css .= $val .'{border-color:'. $custom_accent .';}';
					}
				}
			}
			
			// Return CSS
			if ( $css ) {
				return $css;
			}

		}

		/**
		 * Customizer Output
		 *
		 * @since 4.0
		 */
		public static function customizer_css() {
			echo '<style type="text/css" id="wpex-accent-css">' . self::generate() . '</style>';
		}

		/**
		 * Live site output
		 *
		 * @since 4.0
		 */
		public static function live_css( $output ) {
			$accent_css = self::generate();
			if ( $accent_css ) {
				$output .= '/*ACCENT COLOR*/'. $accent_css;
			}
			return $output;
		}

	}

}
new WPEX_Accent_Color();