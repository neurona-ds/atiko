<?php
/**
 * Adds custom CSS to alter all main theme border colors
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start Class
if ( ! class_exists( 'WPEX_Theme_Border_Color' ) ) {
	
	class WPEX_Theme_Border_Color {

		/**
		 * Main constructor
		 *
		 * @since 2.0.0
		 */
		public function __construct() {
			if ( is_customize_preview() ) {
				add_action( 'wp_head', array( 'WPEX_Theme_Border_Color', 'customizer_css' ), 10 );
			} else {
				add_filter( 'wpex_head_css', array( 'WPEX_Theme_Border_Color', 'live_css' ), 1 );
			}
		}

		/**
		 * Returns array of elements and border style to apply
		 *
		 * @since 2.0.0
		 */
		private static function border_targets() {

			return apply_filters( 'wpex_border_color_elements', array(

				// General
				'.theme-heading span.text:after',
				'#comments .comment-body',
				'.centered-minimal-page-header',
				'.theme-heading.border-w-color',

				// Top bar
				'#top-bar-wrap',

				// Blog
				'.blog-entry.large-image-entry-style',
				'.blog-entry.grid-entry-style .blog-entry-inner',
				'.entries.left-thumbs .blog-entry.thumbnail-entry-style',

				// CPT
				'.cpt-entry.span_1_of_1',
				
				// Pagination
				'ul .page-numbers a,
				 a.page-numbers,
				 span.page-numbers',

				'.post-pagination',

				// Widgets
				'#main .wpex-widget-recent-posts-li:first-child,
				 #main .widget_categories li:first-child,
				 #main .widget_recent_entries li:first-child,
				 #main .widget_archive li:first-child,
				 #main .widget_recent_comments li:first-child,
				 #main .widget_product_categories li:first-child,
				 #main .widget_layered_nav li:first-child,
				 #main .widget-recent-posts-icons li:first-child,
				 #main .site-footer .widget_nav_menu li:first-child',

				'#main .wpex-widget-recent-posts-li,
				 #main .widget_categories li,
				 #main .widget_recent_entries li,
				 #main .widget_archive li,
				 #main .widget_recent_comments li,
				 #main .widget_product_categories li,
				 #main .widget_layered_nav li,
				 #main .widget-recent-posts-icons li,
				 #main .site-footer .widget_nav_menu li',

				'.modern-menu-widget',
				'.modern-menu-widget li',
				'.modern-menu-widget li ul',

				'#sidebar .widget_nav_menu a',
				'#sidebar .widget_nav_menu ul > li:first-child > a',


				// Modules
				'.vcex-divider-solid',
				'.vcex-blog-entry-details',
				'.theme-button.minimal-border',
				'.vcex-login-form',
				'.vcex-recent-news-entry'

			) );

		}

		/**
		 * Generates the CSS output
		 *
		 * @since 2.0.0
		 */
		private static function generate() {

			// Get border color
			$color = wpex_get_mod( 'main_border_color', '#eee' );

			// Check for theme mod
			if ( $color && '#eee' != $color && '#eeeeee' != $color ) {

				// Define css var
				$css = '';

				// Get array to loop through
				$borders = self::border_targets();

				// Borders
				if ( ! empty( $borders ) ) {
					$borders = implode( ',', $borders );
					$css .= $borders .'{border-color:'. $color .';}';
				}
				
				// Return CSS
				if ( $css ) {
					return '/*BORDER COLOR*/'. $css;
				}

			}

		}

		/**
		 * Customizer Output
		 *
		 * @since 4.0
		 */
		public static function customizer_css() {
			echo '<style type="text/css" id="wpex-borders-css">' . self::generate() . '</style>';
		}

		/**
		 * Live site output
		 *
		 * @since 4.0
		 */
		public static function live_css( $output ) {
			$accent_css = self::generate();
			if ( $accent_css ) {
				$output .= '/*BORDER COLOR*/'. $accent_css;
			}
			return $output;
		}

	}

}
new WPEX_Theme_Border_Color();