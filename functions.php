<?php
/**
 * Functions.php => This file loads all the needed files for the theme to work properly.
 *
 * IMPORTANT : DO NOT USE AN "ILLEGAL" COPY OF THIS THEME
 * IMPORTANT : DO NOT EVER EDIT THIS FILE
 * IMPORTANT : DO NOT COPY AND PASTE THIS FILE INTO YOUR CHILD THEME
 * IMPORTANT : DO NOT EVER COPY AND PASTE ANYTHING FROM THIS FILE TO YOUR CHILD THEME BECAUSE IT WILL CAUSE ERRORS
 * IMPORTANT : DO USE HOOKS, FILTERS & TEMPLATE PARTS TO ALTER THIS THEME VIA A CHILD THEME
 *
 * Total is a very powerful theme and virtually anything can be customized
 * via a child theme. If you need any help altering a function, just let us know we are here to help!
 *
 * Advanced customizations aren't included with your purchase but if it's a simple task we can assist :)
 *
 * Theme Docs        : https://wpexplorer-themes.com/total/docs/
 * Request Support   : https://wpexplorer-themes.com/total/docs/how-to-request-support/
 * Theme Snippets    : https://wpexplorer-themes.com/total/snippets/
 * Using Hooks       : https://wpexplorer-themes.com/total/docs/action-hooks/
 * Filters Reference : https://www.wpexplorer.com/docs/total-wordpress-theme-filters/
 * Theme Support     : https://wpexplorer-themes.com/support/ (valid purchase required)
 *
 * @package Total WordPress Theme
 * @subpackage Templates
 * @version 4.2.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Core Constants
define( 'TOTAL_THEME_ACTIVE', true );
define( 'WPEX_THEME_VERSION', '4.2.1' );
define( 'WPEX_VC_SUPPORTED_VERSION', '5.1.1' );

define( 'WPEX_THEME_DIR', get_template_directory() );
define( 'WPEX_THEME_URI', get_template_directory_uri() );

// Start up class
class WPEX_Theme_Setup {

	/**
	 * Main Theme Class Constructor
	 *
	 * Loads all necessary classes, functions, hooks, configuration files and actions for the theme.
	 * Everything starts here.
	 *
	 * @since 1.6.0
	 *
	 */
	public function __construct() {

		// Perform actions after updating the theme => Run before anything else
		require_once WPEX_THEME_DIR . '/framework/updates/after-update.php';

		// Define globals
		// You can alter any Customizer setting by modifying this global var via any specific action hook.
		global $wpex_theme_mods;

		// Gets all theme mods and stores them in an easily accessable global var to limit DB requests & filter checks
		$wpex_theme_mods = get_theme_mods();

		// Functions used to retrieve theme mods.
		// Loaded early so it can be used on all hooks.
		// Requires $wpex_theme_mods global var to be defined first => look up!
		require_once WPEX_THEME_DIR . '/framework/get-mods.php';

		// Define constants
		self::constants();

		// Load all core theme function files
		// Load Before classes and addons so we can make use of them => IMPORTANT!!!
		self::main_includes();

		// Load all the theme addons => Must run on this hook!!!
		add_action( 'after_setup_theme', array( 'WPEX_Theme_Setup', 'addons' ), 2 );

		// Load configuration classes (post types & 3rd party plugins)
		// Must load first so it can use hooks defined in the classes
		add_action( 'after_setup_theme', array( 'WPEX_Theme_Setup', 'configs' ), 3 );

		// Load framework classes
		add_action( 'after_setup_theme', array( 'WPEX_Theme_Setup', 'classes' ), 4 );

		// Setup theme => add_theme_support, register_nav_menus, load_theme_textdomain, etc
		// Must run on 10 priority or else child theme locale will be overritten
		add_action( 'after_setup_theme', array( 'WPEX_Theme_Setup', 'theme_setup' ), 10 );

		// Defines hooks and adds theme actions
		// Moved to after_setup_theme hook in v3.6.0 so it can be accessed earlier if needed
		// to remove actions
		add_action( 'after_setup_theme', array( 'WPEX_Theme_Setup', 'hooks_actions' ), 10 );

		// Disable WP responsive images if retina is enabled
		// @todo should we disable always by default?? - Maybe add new setting to Theme Panel
		if ( wpex_is_retina_enabled() ) {
			add_filter( 'wp_calculate_image_srcset', '__return_false' );
		}

		// Include deprecated functions if enabled
		if ( wpex_load_deprecated_functions() ) {
			require_once WPEX_FRAMEWORK_DIR . 'deprecated/deprecated.php';
		}

	} // End constructor

	/**
	 * Defines the constants for use within the theme.
	 *
	 * @since 2.0.0
	 */
	public static function constants() {

		// Theme branding
		define( 'WPEX_THEME_BRANDING', wpex_get_mod( 'theme_branding', 'Total' ) );

		// Theme Panel slug
		define( 'WPEX_THEME_PANEL_SLUG', 'wpex-panel' );
		define( 'WPEX_ADMIN_PANEL_HOOK_PREFIX', 'theme-panel_page_' . WPEX_THEME_PANEL_SLUG );

		// Framework Paths
		define( 'WPEX_FRAMEWORK_DIR', WPEX_THEME_DIR . '/framework/' );
		define( 'WPEX_FRAMEWORK_DIR_URI', WPEX_THEME_URI . '/framework/' );

		// Classes directory
		define( 'WPEX_ClASSES_DIR', WPEX_FRAMEWORK_DIR . 'classes/' );

		// Main CSS handle
		define( 'WPEX_THEME_STYLE_HANGLE', 'wpex-style' );
		define( 'WPEX_THEME_JS_HANGLE', 'wpex-core' );

		// Check if plugins are active
		define( 'WPEX_VC_ACTIVE', class_exists( 'Vc_Manager' ) );
		define( 'WPEX_BBPRESS_ACTIVE', class_exists( 'bbPress' ) );
		define( 'WPEX_WOOCOMMERCE_ACTIVE', class_exists( 'WooCommerce' ) );
		define( 'WPEX_WPML_ACTIVE', class_exists( 'SitePress' ) );

		// Active post types
		define( 'WPEX_PORTFOLIO_IS_ACTIVE', wpex_get_mod( 'portfolio_enable', true ) );
		define( 'WPEX_STAFF_IS_ACTIVE', wpex_get_mod( 'staff_enable', true ) );
		define( 'WPEX_TESTIMONIALS_IS_ACTIVE', wpex_get_mod( 'testimonials_enable', true ) );

	}

	/**
	 * Defines all theme hooks and runs all needed actions for theme hooks.
	 *
	 * @since 2.0.0
	 */
	public static function hooks_actions() {

		// Register hooks (needed in admin for Custom Actions panel)
		require_once WPEX_FRAMEWORK_DIR . 'hooks/hooks.php';

		// Core theme hooks and actions // if running in backend it breaks VC grid builder
		if ( ! is_admin() ) {
			require_once WPEX_FRAMEWORK_DIR . 'hooks/actions.php';
			require_once WPEX_FRAMEWORK_DIR . 'hooks/partials.php';
		}

	}

	/**
	 * Framework functions
	 * IMPORTANT: Load before Classes & Addons so we can make use of them
	 *
	 * @since 2.0.0
	 */
	public static function main_includes() {

		// Core
		require_once WPEX_FRAMEWORK_DIR . 'conditionals.php';
		require_once WPEX_FRAMEWORK_DIR . 'core-functions.php';
		require_once WPEX_FRAMEWORK_DIR . 'template-parts.php';
		require_once WPEX_FRAMEWORK_DIR . 'arrays.php';
		require_once WPEX_FRAMEWORK_DIR . 'shortcodes.php';

		// Helper functions
		require_once WPEX_FRAMEWORK_DIR . 'helpers/js-localize-data.php';
		require_once WPEX_FRAMEWORK_DIR . 'helpers/aria-landmarks.php';
		require_once WPEX_FRAMEWORK_DIR . 'helpers/post-thumbnails.php';
		require_once WPEX_FRAMEWORK_DIR . 'helpers/post-types.php';
		require_once WPEX_FRAMEWORK_DIR . 'helpers/translations.php';
		require_once WPEX_FRAMEWORK_DIR . 'helpers/fonts.php';
		require_once WPEX_FRAMEWORK_DIR . 'helpers/schema-markup.php';
		require_once WPEX_FRAMEWORK_DIR . 'helpers/overlays.php';
		require_once WPEX_FRAMEWORK_DIR . 'helpers/social-share.php';
		require_once WPEX_FRAMEWORK_DIR . 'helpers/videos.php';
		require_once WPEX_FRAMEWORK_DIR . 'helpers/audio.php';
		require_once WPEX_FRAMEWORK_DIR . 'helpers/excerpts.php'; // needs to load in front/back for ajax

		// Filters
		require_once WPEX_FRAMEWORK_DIR . 'wp-filters/honor-ssl-for-attachements.php';

		// Actions
		require_once WPEX_FRAMEWORK_DIR . 'wp-actions/widgets-init.php';

		// Actions
		require_once WPEX_FRAMEWORK_DIR . 'wp-actions/enqueue-scripts.php';
		require_once WPEX_FRAMEWORK_DIR . 'wp-actions/post-redirects.php';
		require_once WPEX_FRAMEWORK_DIR . 'wp-actions/meta-viewport.php';
		require_once WPEX_FRAMEWORK_DIR . 'wp-actions/meta-generator.php';
		require_once WPEX_FRAMEWORK_DIR . 'wp-actions/x-ua-compatible-headers.php';
		require_once WPEX_FRAMEWORK_DIR . 'wp-actions/remove-emoji-scripts.php';

		// Filters
		require_once WPEX_FRAMEWORK_DIR . 'wp-filters/body-class.php';
		require_once WPEX_FRAMEWORK_DIR . 'wp-filters/post-class.php';
		require_once WPEX_FRAMEWORK_DIR . 'wp-filters/pre-get-posts.php';
		require_once WPEX_FRAMEWORK_DIR . 'wp-filters/comments-link.php';
		require_once WPEX_FRAMEWORK_DIR . 'wp-filters/tagcloud-args.php';
		require_once WPEX_FRAMEWORK_DIR . 'wp-filters/allow-shortcodes.php';
		require_once WPEX_FRAMEWORK_DIR . 'wp-filters/oembed.php';
		require_once WPEX_FRAMEWORK_DIR . 'wp-filters/wp-list-categories-args.php';
		require_once WPEX_FRAMEWORK_DIR . 'wp-filters/next-previous-posts-exclude.php';
		require_once WPEX_FRAMEWORK_DIR . 'wp-filters/kses-allowed-protocols.php';
		require_once WPEX_FRAMEWORK_DIR . 'wp-filters/singular-pagination-fix.php';
		require_once WPEX_FRAMEWORK_DIR . 'wp-filters/move-comment-form-fields.php';
		require_once WPEX_FRAMEWORK_DIR . 'wp-filters/authors-posts-link-schema.php';
		require_once WPEX_FRAMEWORK_DIR . 'wp-filters/custom-password-protected-form.php';
		require_once WPEX_FRAMEWORK_DIR . 'wp-filters/attachment-image-attributes.php';

		// Helper functions
		require_once WPEX_FRAMEWORK_DIR . 'helpers/togglebar.php';
		require_once WPEX_FRAMEWORK_DIR . 'helpers/topbar.php';
		require_once WPEX_FRAMEWORK_DIR . 'helpers/header.php';
		require_once WPEX_FRAMEWORK_DIR . 'helpers/header-menu.php';
		require_once WPEX_FRAMEWORK_DIR . 'helpers/title.php';
		require_once WPEX_FRAMEWORK_DIR . 'helpers/post-slider.php';
		require_once WPEX_FRAMEWORK_DIR . 'helpers/page-header.php';
		require_once WPEX_FRAMEWORK_DIR . 'helpers/callout.php';
		require_once WPEX_FRAMEWORK_DIR . 'helpers/footer.php';
		require_once WPEX_FRAMEWORK_DIR . 'helpers/pagination.php';
		require_once WPEX_FRAMEWORK_DIR . 'helpers/blog.php';
		require_once WPEX_FRAMEWORK_DIR . 'helpers/instagram-feed.php';

		/*** Admin only functions ***/
		if ( is_admin() ) {

			// Actions
			require_once WPEX_FRAMEWORK_DIR . 'wp-actions/delete-term-data.php';
			require_once WPEX_FRAMEWORK_DIR . 'wp-actions/after-switch-theme.php';
			require_once WPEX_FRAMEWORK_DIR . 'wp-actions/admin-enqueue-scripts.php';

			// Filters
			require_once WPEX_FRAMEWORK_DIR . 'wp-filters/disable-wp-update-check.php';
			require_once WPEX_FRAMEWORK_DIR . 'wp-filters/tiny-mce-font-sizes.php';
			require_once WPEX_FRAMEWORK_DIR . 'wp-filters/tiny-mce-buttons.php';
			require_once WPEX_FRAMEWORK_DIR . 'wp-filters/dashboard-thumbnails.php';
			require_once WPEX_FRAMEWORK_DIR . 'wp-filters/user-contact-methods.php';

		}

	}

	/**
	 * Include Theme Panel class which loads various add-on functions
	 *
	 * @since 2.0.0
	 */
	public static function addons() {
		require_once WPEX_FRAMEWORK_DIR . 'addons/theme-panel.php';
	}

	/**
	 * Configs for post types and 3rd party plugins.
	 *
	 * @since 2.0.0
	 */
	public static function configs() {

		// Portfolio
		if ( WPEX_PORTFOLIO_IS_ACTIVE ) {
			require_once WPEX_FRAMEWORK_DIR . 'post-types/portfolio/portfolio-config.php';
		}

		// Staff
		if ( WPEX_STAFF_IS_ACTIVE ) {
			require_once WPEX_FRAMEWORK_DIR . 'post-types/staff/staff-config.php';
		}

		// Testimonias
		if ( WPEX_TESTIMONIALS_IS_ACTIVE ) {
			require_once WPEX_FRAMEWORK_DIR . 'post-types/testimonials/testimonials-config.php';
		}

		// WooCommerce
		if ( WPEX_WOOCOMMERCE_ACTIVE && wpex_woo_version_supported() ) {
			require_once WPEX_FRAMEWORK_DIR . '3rd-party/woocommerce/woo-config.php';
		}

		// Visual Composer
		if ( WPEX_VC_ACTIVE && wpex_has_vc_mods() ) {
			require_once WPEX_FRAMEWORK_DIR . '3rd-party/visual-composer/vc-config.php';
		}

		// The Events Calendar
		if ( class_exists( 'Tribe__Events__Main' ) ) {
			require_once WPEX_FRAMEWORK_DIR . '3rd-party/tribe-events/tribe-events-config.php';
		}

		// WPML
		if ( WPEX_WPML_ACTIVE ) {
			require_once WPEX_FRAMEWORK_DIR . '3rd-party/wpml.php';
		}

		// Polylang
		if ( class_exists( 'Polylang' ) ) {
			require_once WPEX_FRAMEWORK_DIR . '3rd-party/polylang.php';
		}

		// bbPress
		if ( WPEX_BBPRESS_ACTIVE ) {
			require_once WPEX_FRAMEWORK_DIR . '3rd-party/bbpress/bbpress-config.php';
		}

		// BuddyPress
		if ( function_exists( 'buddypress' ) ) {
			require_once WPEX_FRAMEWORK_DIR . '3rd-party/buddypress/buddypress-config.php';
		}

		// Sensei
		if ( function_exists( 'Sensei' ) ) {
			require_once WPEX_FRAMEWORK_DIR . '3rd-party/sensei.php';
		}

		// Yoast SEO
		if ( defined( 'WPSEO_VERSION' ) ) {
			require_once WPEX_FRAMEWORK_DIR . '3rd-party/yoast.php';
		}

		// Contact From 7
		if ( defined( 'WPCF7_VERSION' ) ) {
			require_once WPEX_FRAMEWORK_DIR . '3rd-party/contact-form-7.php';
		}

		// Real Media library
		if ( defined( 'RML_VERSION' ) ) {
			require_once WPEX_FRAMEWORK_DIR . '3rd-party/real-media-library/rml-config.php';
		}

		// Revolution
		if ( class_exists( 'RevSlider' ) ) {
			require_once WPEX_FRAMEWORK_DIR . '3rd-party/revslider.php';
		}

		// LayerSlider
		if ( class_exists( 'LS_Sliders' ) ) {
			require_once WPEX_FRAMEWORK_DIR . '3rd-party/layerslider.php';
		}

		// JetPack
		if ( class_exists( 'Jetpack' ) ) {
			require_once WPEX_FRAMEWORK_DIR . '3rd-party/jetpack.php';
		}

		// Gravity Forms
		if ( class_exists( 'RGForms' ) ) {
			require_once WPEX_FRAMEWORK_DIR . '3rd-party/gravity-forms.php';
		}

		// Post types UI
		if ( function_exists( 'cptui_init' ) ) {
			require_once WPEX_FRAMEWORK_DIR . '3rd-party/cpt-ui/types.php';
		}

	}

	/**
	 * Framework Classes
	 *
	 * @since 2.0.0
	 */
	public static function classes() {

		// Sanitize input
		require_once WPEX_ClASSES_DIR . 'sanitize-data.php';

		// iLightbox
		require_once WPEX_ClASSES_DIR . 'ilightbox.php';

		// Image Resize
		require_once WPEX_ClASSES_DIR . 'image-resize.php';

		// Gallery metabox
		require_once WPEX_ClASSES_DIR . 'gallery-metabox.php';

		// Term colors - @todo
		//require_once WPEX_ClASSES_DIR . 'term-colors.php';

		// Post Series
		if ( wpex_get_mod( 'post_series_enable', true ) ) {
			require_once WPEX_ClASSES_DIR . 'post-series.php';
		}

		// Custom WP header
		if ( wpex_get_mod( 'header_image_enable' ) ) {
			require_once WPEX_ClASSES_DIR . 'custom-header.php';
		}

		// Term meta
		require_once WPEX_ClASSES_DIR . 'term-meta.php';

		// Term thumbnails
		if ( wpex_get_mod( 'term_thumbnails_enable', true ) ) {
			require_once WPEX_ClASSES_DIR . 'term-thumbnails.php';
		}

		// Remove post type slugs
		if ( wpex_get_mod( 'remove_posttype_slugs' ) ) {
			require_once WPEX_ClASSES_DIR . 'remove-post-type-slugs.php';
		}

		// Image sizes panel
		if ( wpex_get_mod( 'image_sizes_enable', true ) ) {
			require_once WPEX_ClASSES_DIR . 'image-sizes.php';
		}

		// Admin only classes
		if ( is_admin() ) {

			// Recommend plugins
			if ( wpex_recommended_plugins() && wpex_get_mod( 'recommend_plugins_enable', true ) ) {
				require_once WPEX_ClASSES_DIR . 'tgmpa/class-tgm-plugin-activation.php';
				require_once WPEX_FRAMEWORK_DIR . '3rd-party/tgm-plugin-activation.php';
			}

			// Plugins updater
			require_once WPEX_ClASSES_DIR . 'plugins-updater.php';

			// Category meta
			require_once WPEX_ClASSES_DIR . 'category-meta.php';

			// Metabox => Page Settings
			if ( apply_filters( 'wpex_metaboxes', true ) ) {
				require_once WPEX_ClASSES_DIR . 'post-metabox.php';
			}

			// Custom attachment fields
			require_once WPEX_ClASSES_DIR . 'attachment-fields.php';

		}

		// Custom CSS
		require_once WPEX_ClASSES_DIR . 'custom-css.php';

		// Accent color
		require_once WPEX_ClASSES_DIR . 'accent-color.php';

		// Border color
		require_once WPEX_ClASSES_DIR . 'border-colors.php';

		// Site backgrounds
		require_once WPEX_ClASSES_DIR . 'site-backgrounds.php';

		// Advanced styling
		require_once WPEX_ClASSES_DIR . 'advanced-styling.php';

		// Breadcrumbs class
		require_once WPEX_ClASSES_DIR . 'breadcrumbs.php';

		// Disable Google Services
		if ( wpex_disable_google_services() ) {
			require_once WPEX_ClASSES_DIR . 'disable-google-services.php';
		}

		// Customizer must load last to take advantage of all functions before it
		require_once WPEX_FRAMEWORK_DIR . 'customizer/customizer.php';

	}

	/**
	 * Adds basic theme support functions and registers the nav menus
	 *
	 * @since 1.6.0
	 */
	public static function theme_setup() {

		// Load text domain
		load_theme_textdomain( 'total', WPEX_THEME_DIR . '/languages' );

		// Get globals
		global $content_width;

		// Set content width based on theme's default design
		if ( ! isset( $content_width ) ) {
			$content_width = 980;
		}

		// Register theme navigation menus
		register_nav_menus( array(
			'topbar_menu'     => __( 'Top Bar', 'total' ),
			'main_menu'       => __( 'Main/Header', 'total' ),
			'mobile_menu_alt' => __( 'Mobile Menu Alternative', 'total' ),
			'mobile_menu'     => __( 'Mobile Icons', 'total' ),
			'footer_menu'     => __( 'Footer', 'total' ),
		) );

		// Declare theme support
		add_theme_support( 'post-formats', array( 'video', 'gallery', 'audio', 'quote', 'link' ) );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'html5' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Enable excerpts for pages.
		add_post_type_support( 'page', 'excerpt' );

		// Add styles to the WP editor
		add_editor_style( 'assets/css/wpex-editor-style.css' );
		add_editor_style( wpex_asset_url( 'lib/font-awesome/css/font-awesome.min.css' ) );

	}

}
new WPEX_Theme_Setup;