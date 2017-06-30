<?php
/**
 * Yoast SEO Configuration Class
 *
 * @package Total WordPress Theme
 * @subpackage 3rd Party
 * @version 4.0
 */

if ( ! class_exists( 'WPEX_Yoast_Config' ) ) {

	class WPEX_Yoast_Config {

		/**
		 * Start things up
		 *
		 * @version 3.3.0
		 */
		public function __construct() {

			// Add support for Yoast SEO breadcrumb settings in the WP Customizer
			add_theme_support( 'yoast-seo-breadcrumbs' );

			// Customize theme breadcrumbs
			if ( function_exists( 'yoast_breadcrumb' ) ) {
				add_filter( 'wpex_custom_breadcrumbs', array( 'WPEX_Yoast_Config', 'breacrumbs' ) );
			}

			// Filter the ancestors of the yoast seo breadcrumbs
			add_filter( 'wpseo_breadcrumb_links', array( 'WPEX_Yoast_Config', 'wpseo_breadcrumb_links' ) );

			// Trim the title
			add_filter( 'wp_seo_get_bc_title', array( 'WPEX_Yoast_Config', 'trim_title' ) );

		}

		/**
		 * Override breadcrumbs
		 *
		 * @version 3.3.5
		 */
		public static function breacrumbs() {

			if ( current_theme_supports( 'yoast-seo-breadcrumbs' ) ) {

				// Define classes variable
				$classes = '';

				// Get classes from main class
				if ( class_exists( 'WPEX_Breadcrumbs' ) ) {
					$classes = WPEX_Breadcrumbs::wrap_classes();
				}

				// Breadcrumbs settings
				$before  = '<nav class="'. esc_attr( $classes ) .'"><span class="breadcrumb-trail">';
				$after   = '</span></nav>';
				$display = false;

				// Return breadcrumbs
				return yoast_breadcrumb( $before, $after, $display );

			}

		}

		/**
		 * Filter the ancestors of the yoast seo breadcrumbs
		 * Adds the portfolio, staff, testimonials and blog links
		 *
		 * @version 3.3.0
		 */
		public static function wpseo_breadcrumb_links( $links ) {

			global $post;
			$new_breadcrumb = '';

			// Loop through items
			$types = array( 'portfolio', 'staff', 'testimonials', 'post' );
			foreach ( $types as $type ) {
				if ( is_singular( $type ) ) {
					if ( 'post' == $type ) {
						$type = 'blog';
					}
					$page_id = wpex_parse_obj_id( wpex_get_mod( $type .'_page' ), 'page' );
					if ( $page_id ) {
						$page_title     = get_the_title( $page_id );
						$page_permalink = get_permalink( $page_id );
						if ( $page_permalink && $page_title ) {
							$new_breadcrumb[] = array(
								'url'  => $page_permalink,
								'text' => $page_title,
							);
						}
					}
				}
			} // End foreach loop

			// Combine new crumb
			if ( $new_breadcrumb ) {
				array_splice( $links, 1, -2, $new_breadcrumb );
			}

			// Return links
			return $links;

		}

		/**
		 * Trim the Yoast SEO title
		 *
		 * @version 3.3.2
		 */
		public static function trim_title( $title ) {
			$trim = wpex_get_mod( 'wpex_ybtt_trim_title' );
			$trim = $trim ? $trim : wpex_get_mod( 'breadcrumbs_title_trim' );
			$trim = absint( $trim );
			if ( $trim ) {
				$title = wp_trim_words( $title, $trim );
			}
			return $title;
		}

	}

}
new WPEX_Yoast_Config();