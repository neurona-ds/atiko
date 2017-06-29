<?php
/**
 * Configure the Tribe Events Plugin
 *
 * @package Total WordPress Theme
 * @subpackage Configs
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start Class
if ( ! class_exists( 'WPEX_Tribe_Events_Config' ) ) {

	class WPEX_Tribe_Events_Config {

		/**
		 * Start things up
		 *
		 * @since 2.0.0
		 */
		public function __construct() {

			// Define constants
			define( 'WPEX_TRIBE_EVENTS_DIR', WPEX_FRAMEWORK_DIR . '3rd-party/tribe-events/' );
			define( 'WPEX_TRIBE_EVENTS_COMMUNITY_ACTIVE', class_exists( 'Tribe__Events__Community__Main' ) );

			// Include helper functions
			require_once WPEX_TRIBE_EVENTS_DIR . 'tribe-events-helpers.php';

			// Add Customizer settings
			add_filter( 'wpex_customizer_panels', array( 'WPEX_Tribe_Events_Config', 'customizer_settings' ) );

			// Add custom sidebar
			add_filter( 'widgets_init', array( 'WPEX_Tribe_Events_Config', 'register_events_sidebar' ), 10 );

			// Add new accent colors
			add_filter( 'wpex_accent_backgrounds', array( 'WPEX_Tribe_Events_Config', 'accent_backgrounds' ) );

			// Back-end functions
			if ( is_admin() ) {

				// Enable metabox settings
				add_filter( 'wpex_main_metaboxes_post_types', array( 'WPEX_Tribe_Events_Config', 'metaboxes' ), 10 );

			}

			// Front-end functions
			else {

				// Filter body classes
				add_filter( 'body_class', array( 'WPEX_Tribe_Events_Config', 'body_class' ), 10 );

				// Custom CSS
				add_action( 'wp_enqueue_scripts', array( 'WPEX_Tribe_Events_Config', 'load_custom_stylesheet' ), 10 );

				// Set correct page ID for post type archive
				add_filter( 'wpex_post_id', array( 'WPEX_Tribe_Events_Config', 'page_id' ), 10 );

				// Configure layouts
				add_filter( 'wpex_post_layout_class', array( 'WPEX_Tribe_Events_Config', 'layouts' ), 10 );

				// Alter main title
				add_filter( 'wpex_page_header_title_args', array( 'WPEX_Tribe_Events_Config', 'page_header_title' ), 10 );

				// Add event meta after title
				add_filter( 'wpex_post_subheading', array( 'WPEX_Tribe_Events_Config', 'post_subheading' ), 10 );

				// Display custom sidebar
				add_filter( 'wpex_get_sidebar', array( 'WPEX_Tribe_Events_Config', 'display_events_sidebar' ), 10 );

				// Disable next/previous links
				add_filter( 'wpex_has_next_prev', array( 'WPEX_Tribe_Events_Config', 'next_prev' ) );

				// Redirect page used for page settings to the homepage
				if ( wpex_get_mod( 'tribe_events_main_page' ) && ! is_admin() ) {
					add_filter( 'template_redirect', array( 'WPEX_Tribe_Events_Config', 'redirects' ) );
				}

				// Edit post link for community events
				if ( WPEX_TRIBE_EVENTS_COMMUNITY_ACTIVE ) {
					add_filter( 'get_edit_post_link', array( 'WPEX_Tribe_Events_Config', 'get_edit_post_link' ), 40 );
				}

			}

		}

		/**
		 * Filter body classes
		 *
		 * @since 2.0.0
		 */
		public static function body_class( $classes ) {
			if ( wpex_get_mod( 'tribe_events_page_header_details', true ) && is_singular( 'tribe_events' ) ) {
				$classes[] = 'tribe-page-header-details';
			}
			return $classes;
		}

		/**
		 * Load custom CSS file for tweaks
		 *
		 * @since 2.0.0
		 */
		public static function load_custom_stylesheet() {

			// Main events CSS
			wp_enqueue_style( 'wpex-tribe-events', wpex_asset_url( 'css/wpex-tribe-events.css' ) );

			// Community events CSS
			if ( WPEX_TRIBE_EVENTS_COMMUNITY_ACTIVE ) {
				wp_enqueue_style(
					'wpex-tribe-events-community',
					wpex_asset_url( 'css/wpex-tribe-events-community.css' ),
					array( 'tribe_events-community-styles' )
				);
			}

		}

		/**
		 * Set correct page id for main events page
		 *
		 * @since 3.6.0
		 */
		public static function page_id( $id ) {

			// Alter page ID
			if ( is_post_type_archive( 'tribe_events' ) && $page_id = wpex_get_tribe_events_main_page_id() ) {
				$id = $page_id;
			}

			// Return page id
			return $id;

		}

		/**
		 * Alter the post layouts for all events
		 *
		 * @since 2.0.0
		 */
		public static function layouts( $class ) {

			// Return full-width for event posts and archives
			if ( wpex_is_tribe_events() ) {
				if ( is_singular( 'tribe_events' ) ) {
					$class = wpex_get_mod( 'tribe_events_single_layout', 'full-width' );
				} else {
					$class = wpex_get_mod( 'tribe_events_archive_layout', 'full-width' );
				}
			}

			// Full width for community edit
			if ( WPEX_TRIBE_EVENTS_COMMUNITY_ACTIVE ) {

				// My events
				if ( tribe_is_community_edit_event_page() || tribe_is_community_my_events_page() ) {
					$class = wpex_get_mod( 'tribe_events_community_my_events_layout', 'full-width' );
				}

			}

			// Return class
			return $class;

		}

		/**
		 * Add the Page Settings metabox to the events calendar
		 *
		 * @since 2.0.0
		 */
		public static function metaboxes( $types ) {
			$types['tribe_events'] = 'tribe_events';
			return $types;
		}

		/**
		 * Alter the main page header title text for tribe events
		 *
		 * @since 2.0.0
		 */
		public static function page_header_title( $args ) {

			// Fixes issue with search results
			if ( is_search() ) {
				return $args;
			}

			// Customize title for event pages
			if ( tribe_is_event_category() ) {
				$main_page = wpex_get_tribe_events_main_page_id();
				$args['string'] = $main_page ? get_the_title( $main_page ) : __( 'Events Calendar', 'total' );
			} elseif ( tribe_is_month() ) {
				$post_id = wpex_get_current_post_id();
				$args['string'] = $post_id ? get_the_title( $post_id ) : __( 'Events Calendar', 'total' );
			} elseif ( tribe_is_event() && ! tribe_is_day() && ! is_single() ) {
				$args['string'] = __( 'Events List', 'total' );
			} elseif ( tribe_is_day() ) {
				$args['string'] = __( 'Single Day Events', 'total' );
			} elseif ( is_singular( 'tribe_events' ) ) {
				if ( wpex_get_mod( 'tribe_events_page_header_details', true ) ) {
					$args['html_tag'] = 'h1';
					$args['string']   = single_post_title( '', false );
				} else {
					$obj = get_post_type_object( 'tribe_events' );
					$args['string'] = $obj->labels->name;
				}
			}

			// Return title
			return $args;

		}

		/**
		 * Alter the post subheading for events
		 *
		 * @since 3.6.0
		 */
		public static function post_subheading( $subheading ) {
			if ( wpex_get_mod( 'tribe_events_page_header_details', true ) && is_singular( 'tribe_events' ) ) {
				$subheading = '<div class="page-subheading-extra clr">';
					$subheading .= tribe_events_event_schedule_details( get_the_ID(), '<div class="schedule"><span class="fa fa-calendar-o"></span>', '</div>' );
				if ( $cost = tribe_get_cost( null, true ) ) {
					$subheading .= '<div class="cost"><span class="fa fa-money"></span>' . $cost . '</div>';
				}
				$subheading .= '</div>';
			}
			return $subheading;
		}

		/**
		 * Register a new events sidebar area
		 *
		 * @since 2.0.0
		 */
		public static function register_events_sidebar() {
			$headings = wpex_get_mod( 'sidebar_headings', 'div' );
			$headings = $headings ? $headings : 'div';
			register_sidebar( array(
				'name'          => __( 'Events Sidebar', 'total' ),
				'id'            => 'tribe_events_sidebar',
				'before_widget' => '<div id="%1$s" class="sidebar-box widget %2$s clr">',
				'after_widget'  => '</div>',
				'before_title'  => '<' . $headings . ' class="widget-title">',
				'after_title'   => '</' . $headings . '>',
			) );
		}

		/**
		 * Alter main sidebar to display events sidebar
		 *
		 * @since 2.0.0
		 */
		public static function display_events_sidebar( $sidebar ) {
			if ( wpex_is_tribe_events() && is_active_sidebar( 'tribe_events_sidebar' ) ) {
				$sidebar = 'tribe_events_sidebar';
			}
			return $sidebar;
		}

		/**
		 * Disables the next/previous links for tribe events because they already have some.
		 *
		 * @since 2.0.0
		 */
		public static function next_prev( $return ) {
			if ( is_singular( 'tribe_events' ) ) {
				$return = false;
			}
			return $return;
		}

		/**
		 * Adds background accents for tribe events
		 *
		 * @since 2.0.0
		 */
		public static function accent_backgrounds( $backgrounds ) {
			return array_merge( $backgrounds, array(
				'#tribe-events .tribe-events-button',
				'#tribe-events .tribe-events-button:hover',
				'#tribe_events_filters_wrapper input[type=submit]',
				'.tribe-events-button',
				'.tribe-events-button.tribe-active:hover',
				'.tribe-events-button.tribe-inactive',
				'.tribe-events-button:hover',
				'.tribe-events-calendar td.tribe-events-present div[id*=tribe-events-daynum-]',
				'.tribe-events-calendar td.tribe-events-present div[id*=tribe-events-daynum-]>a',
				'#my-events .button, #my-events .button:hover',
				'#add-new .button, #add-new .button:hover',
				'.table-menu-btn, .table-menu-btn:hover',
			) );
		}

		/**
		 * Adds Customizer settings for Tribe Events
		 *
		 * @since 3.3.3
		 */
		public static function customizer_settings( $panels ) {
			$panels['tribe_events'] = array(
				'title'      => __( 'Tribe Events', 'total' ),
				'is_section' => true,
				'settings'   => WPEX_TRIBE_EVENTS_DIR . 'tribe-events-customizer-settings.php'
			);
			return $panels;
		}

		/**
		 * Redirects
		 *
		 * @since 3.6.0
		 */
		public static function redirects() {

			// Check for main page
			if ( $page_id = wpex_get_mod( 'tribe_events_main_page' ) ) {

				// Redirect on page as long as it's not posts page to prevent endless loop
				if ( is_page( $page_id )
					&& $page_id != get_option( 'page_for_posts' )
				) {

					// Get archive link
					$archive_link = get_post_type_archive_link( 'tribe_events' );

					// Set redirect
					$redirect = $archive_link ? $archive_link : home_url( '/' );

					// Redirect and exit for security
					wp_redirect( esc_url( $redirect ), 301 );
					exit();
				}

			}

		}

		/**
		 * Edit post link
		 *
		 * @since 3.6.0
		 */
		public static function get_edit_post_link( $url ) {
			if ( is_singular( 'tribe_events' ) && class_exists( 'Tribe__Events__Community__Main' ) ) {
				$url = esc_url( Tribe__Events__Community__Main::instance()->getUrl( 'edit', get_the_ID(), null, Tribe__Events__Main::POSTTYPE ) );
			}
			return $url;
		}

	}

}

new WPEX_Tribe_Events_Config();