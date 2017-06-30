<?php
/**
 * Visual Composer configuration file
 *
 * @package Total WordPress Theme
 * @subpackage Visual Composer
 * @version 4.2
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start class
class WPEX_Visual_Composer_Config {

	/**
	 * Start things up
	 *
	 * @since 1.6.0
	 */
	public function __construct() {

		// Define useful Paths
		define( 'WPEX_VCEX_DIR', WPEX_FRAMEWORK_DIR . '3rd-party/visual-composer/' );
		define( 'WPEX_VCEX_DIR_URI', WPEX_FRAMEWORK_DIR_URI . '3rd-party/visual-composer/' );

		// Global post CSS
		require_once WPEX_VCEX_DIR . 'vc-global-post-css.php';

		// Include helper functions and classes
		require_once WPEX_VCEX_DIR . 'vc-helpers.php';
		require_once WPEX_VCEX_DIR . 'helpers/build-query.php';
		require_once WPEX_VCEX_DIR . 'helpers/inline-style.php';
		require_once WPEX_VCEX_DIR . 'helpers/autocomplete.php';

		// Disable Welcome message
		require_once WPEX_VCEX_DIR . 'vc-disable-welcome.php';

		// Remove core elements
		require_once WPEX_VCEX_DIR . 'vc-remove-elements.php';

		// Register accent colors
		require_once WPEX_VCEX_DIR . 'vc-accent-color.php';

		// Alter core vc modules
		require_once WPEX_VCEX_DIR . 'shortcode-mods/vc_section.php';
		require_once WPEX_VCEX_DIR . 'shortcode-mods/vc_row.php';
		require_once WPEX_VCEX_DIR . 'shortcode-mods/vc_column.php';
		require_once WPEX_VCEX_DIR . 'shortcode-mods/vc_single_image.php';
		require_once WPEX_VCEX_DIR . 'shortcode-mods/vc_column_text.php';
		require_once WPEX_VCEX_DIR . 'shortcode-mods/vc_tabs_tour.php';
		require_once WPEX_VCEX_DIR . 'shortcode-mods/vc_toggle.php';

		require_once WPEX_VCEX_DIR . 'shortcode-mods/vc-add-params.php';
		require_once WPEX_VCEX_DIR . 'shortcode-mods/vc-modify-params.php';

		// Parse attributes
		require_once WPEX_VCEX_DIR . 'parse-atts/row-atts.php';

		// Custom Grid builder modules (must load early)
		require_once WPEX_VCEX_DIR . 'shortcodes/grid_item-post_video.php';
		require_once WPEX_VCEX_DIR . 'shortcodes/grid_item-post_meta.php';
		require_once WPEX_VCEX_DIR . 'shortcodes/grid_item-post_excerpt.php';
		require_once WPEX_VCEX_DIR . 'shortcodes/grid_item-post_terms.php';

		// Add new parameter types
		if ( function_exists( 'vc_add_shortcode_param' ) ) {

			require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-attach-images.php';
			require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-font-family-select.php';
			require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-responsive-sizes.php';
			require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-overlay-select.php';
			require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-visibility-select.php';
			require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-font-weights-select.php';
			require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-font-icon-family-select.php';
			require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-social-button-styles-select.php';
			require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-hover-css-animations-select.php';
			require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-lightbox-skins-select.php'; // @todo deprecate
			require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-ofswitch.php';
			//require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-button-set.php'; // @todo finish adding

			require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-image-hovers-select.php';
			require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-image-filters-select.php';
			require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-image-crop-locations-select.php';

			require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-grid-columns-select.php';
			require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-grid-columns-responsive.php';
			require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-grid-columns-gap-select.php';

			require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-text-transforms-select.php';
			require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-text-alignments-select.php';

			require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-button-styles-select.php';
			require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-button-colors-select.php';

			require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-carousel-arrow-styles-select.php';
			require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-carousel-arrow-positions-select.php';

		}

		// Add custom templates
		// @to do Add custom templates - best done via new media library style.
		require_once WPEX_VCEX_DIR . 'vc-templates.php';

		// Add shortcodes to the tinyMCE editor
		require_once WPEX_VCEX_DIR . 'vcex-tinymce-shortcodes.php';

		// Alter the font container tags
		require_once WPEX_VCEX_DIR . 'vc-font-container.php';

		// Disable functions for non active VC licenses
		if ( vcex_theme_mode_check() ) {
			require_once WPEX_VCEX_DIR . 'vc-disable-design-options.php';
			require_once WPEX_VCEX_DIR . 'vc-disable-updater.php';
			require_once WPEX_VCEX_DIR . 'vc-disable-template-library.php';
		}

		// Remove default templates => Do not edit due to extension plugin and snippets
		add_filter( 'vc_load_default_templates', '__return_empty_array' );

		// Run on init
		add_action( 'init', array( 'WPEX_Visual_Composer_Config', 'init' ), 20 );

		// Tweak scripts
		add_action( 'wp_enqueue_scripts', array( 'WPEX_Visual_Composer_Config', 'load_composer_front_css' ), 0 );
		add_action( 'wp_enqueue_scripts', array( 'WPEX_Visual_Composer_Config', 'load_remove_styles' ) );
		add_action( 'vc_frontend_editor_render',  array( 'WPEX_Visual_Composer_Config', 'remove_editor_font_awesome' ) );
		add_action( 'wp_footer', array( 'WPEX_Visual_Composer_Config', 'remove_footer_scripts' ) );

		// Admin/iFrame scrips
		add_action( 'admin_enqueue_scripts', array( 'WPEX_Visual_Composer_Config', 'admin_scripts' ) );
		add_action( 'vc_load_iframe_jscss', array( 'WPEX_Visual_Composer_Config', 'iframe_scripts' ) );

		// Popup scripts
		add_action( 'vc_frontend_editor_enqueue_js_css', array( 'WPEX_Visual_Composer_Config', 'popup_scripts' ) );
		add_action( 'vc_backend_editor_enqueue_js_css', array( 'WPEX_Visual_Composer_Config', 'popup_scripts' ) );

		// Replace lightbox - @todo finish after next big VC update since things will change
		//if ( wpex_get_mod( 'replace_vc_lightbox', true ) ) {
			//require_once WPEX_VCEX_DIR . 'vc-replace-prettyphoto.php';
		//}

		// Hide VC buttons in media library attachments in the backend for multi-sites
		if ( is_multisite() ) {
			add_action( 'admin_head', array( 'WPEX_Visual_Composer_Config', 'hide_vc_buttons_media_library' ) );
		}

		// Add Customizer settings
		add_filter( 'wpex_customizer_panels', array( 'WPEX_Visual_Composer_Config', 'customizer_settings' ) );

	}

	/**
	 * Functions that run on init
	 *
	 * @since 2.0.0
	 */
	public static function init() {

		// Remove purchase notice
		wpex_remove_class_filter( 'admin_notices', 'Vc_License', 'adminNoticeLicenseActivation', 10 );

		// Remove editor VC logo - this is a useless function
		add_filter( 'vc_nav_front_logo', array( 'WPEX_Visual_Composer_Config', 'editor_nav_logo' ) );

		// Remove templatera notice
		remove_action( 'admin_notices', 'templatera_notice' );

		// Set default post types for VC
		if ( function_exists( 'vc_set_default_editor_post_types' ) ) {
			vc_set_default_editor_post_types( array( 'page', 'portfolio', 'staff' ) );
		}

		// Include custom modules
		if ( function_exists( 'vc_lean_map' )
			&& class_exists( 'WPBakeryShortCode' )
			&& wpex_get_mod( 'extend_visual_composer', true )
		) {
			self::total_custom_vc_shortcodes();
		}

	}

	/**
	 * Override editor logo
	 *
	 * @since 3.0.0
	 * @deprecated 4.0
	 */
	public static function editor_nav_logo() {
		return '<div id="vc_logo" class="vc_navbar-brand">' . esc_html__( 'Visual Composer', 'total' ) . '</div>';
	}

	/**
	 * Load js_composer_front CSS eaerly on for easier modification
	 *
	 * @since  2.1.3
	 */
	public static function load_composer_front_css() {
		wp_enqueue_style( 'js_composer_front' );
	}

	/**
	 * Load and remove stylesheets
	 *
	 * @since 2.0.0
	 */
	public static function load_remove_styles() {

		// Add Scripts
		wp_enqueue_style(
			'wpex-visual-composer',
			wpex_asset_url( 'css/wpex-visual-composer.css' ),
			array( 'wpex-style', 'js_composer_front' ),
			WPEX_THEME_VERSION
		);

		wp_enqueue_style(
			'wpex-visual-composer-extend',
			wpex_asset_url( 'css/wpex-visual-composer-extend.css' ),
			array( 'wpex-style', 'js_composer_front' ),
			WPEX_THEME_VERSION
		);

		/* Remove Scripts to fix Customizer issue with jQuery UI
		 * Fixed in WP 4.4
		 * @deprecated 3.3.0
		if ( is_customize_preview() ) {
			wp_deregister_script( 'wpb_composer_front_js' );
			wp_dequeue_script( 'wpb_composer_front_js' );
		}*/

	}

	/**
	 * Remove scripts from backend editor
	 *
	 * @since 3.6.0
	 */
	public static function remove_editor_font_awesome() {
		wp_deregister_style( 'font-awesome' );
		wp_dequeue_style( 'font-awesome' );
	}

	/**
	 * Remove scripts hooked in too late for me to remove on wp_enqueue_scripts
	 *
	 * @since 2.1.0
	 */
	public static function remove_footer_scripts() {

		// JS
		wp_dequeue_script( 'vc_pageable_owl-carousel' );
		wp_dequeue_script( 'vc_grid-js-imagesloaded' );

		// Styles conflict with Total owl carousel styles
		wp_deregister_style( 'vc_pageable_owl-carousel-css-theme' );
		wp_dequeue_style( 'vc_pageable_owl-carousel-css-theme' );
		wp_deregister_style( 'vc_pageable_owl-carousel-css' );
		wp_dequeue_style( 'vc_pageable_owl-carousel-css' );

	}

	/**
	 * Admin Scripts
	 *
	 * @since 1.6.0
	 */
	public static function admin_scripts( $hook ) {

		$hooks = array(
			'edit.php',
			'post.php',
			'post-new.php',
			'widgets.php', // Support VC widget plugin
		);

		if ( ! in_array( $hook, $hooks ) ) {
			return;
		}

		wp_enqueue_style(
			'vcex-admin',
			wpex_asset_url( 'css/wpex-visual-composer-admin.css' ),
			array(),
			WPEX_THEME_VERSION
		);

		if ( is_rtl() ) {
			wp_enqueue_style(
				'vcex-admin-rtl',
				wpex_asset_url( 'css/wpex-visual-composer-admin-rtl.css' ),
				array(),
				WPEX_THEME_VERSION
			);
		}

	}

	/**
	 * iFrame Scripts
	 *
	 * @since 4.0
	 */
	public static function iframe_scripts() {

		wp_enqueue_style(
			'vcex-iframe-css',
			wpex_asset_url( 'css/wpex-visual-composer-iframe.css' ),
			array(),
			WPEX_THEME_VERSION
		);

	}

	/**
	 * Popup Window Scripts
	 *
	 * @since 4.0
	 */
	public static function popup_scripts() {

		wp_enqueue_script(
			'wpex-chosen-js',
			wpex_asset_url( 'lib/chosen/chosen.jquery.min.js' ),
			array( 'jquery' ),
			'1.4.1',
			true
		);

		wp_enqueue_style(
			'wpex-chosen-css',
			wpex_asset_url( 'lib/chosen/chosen.min.css' ),
			false,
			'1.4.1'
		);

	}

	/**
	 * Maps custom shortcodes for the VC
	 *
	 * @since 2.1.0
	 */
	public static function total_custom_vc_shortcodes() {
		
		// Array of new modules to add to the VC
		$vcex_modules = apply_filters( 'vcex_builder_modules', array(
			'shortcode',
			'spacing',
			'divider',
			'divider_dots',
			'divider_multicolor',
			'heading',
			'button',
			'leader',
			'animated_text',
			'icon_box',
			'teaser',
			'feature',
			'list_item',
			'bullets',
			'pricing',
			'skillbar',
			'icon',
			'milestone',
			'countdown',
			'social_links',
			'navbar',
			'searchbar',
			'login_form',
			'form_shortcode',
			'newsletter_form',
			'image_swap',
			'image_galleryslider',
			'image_flexslider',
			'image_carousel',
			'image_grid',
			'recent_news',
			'blog_grid',
			'blog_carousel',
			'post_type_grid',
			'post_type_archive',
			'post_type_slider',
			'post_type_carousel',
			'callout' => array(
				'file' =>  WPEX_VCEX_DIR . 'shortcodes/callout/callout.php',
			),
			'post_terms',
			'terms_grid',
			'terms_carousel',
			'users_grid',
		) );

		// Load mapping files
		if ( ! empty( $vcex_modules ) ) {
			foreach ( $vcex_modules as $key => $val ) {
				if ( is_array( $val ) ) {
					$condition = isset( $val['condition'] ) ? $val['condition'] : true;
					$file      = isset( $val['file'] ) ? $val['file'] : WPEX_VCEX_DIR . 'shortcodes/' . $key . '.php';
					if ( $condition ) {
						require_once $file;
					}
				} else {
					$file = WPEX_VCEX_DIR . 'shortcodes/' . $val . '.php';
					require_once $file;
				}
			}
		}
		
	}

	/**
	 * Hide VC buttons in media library attachments in the backend for multi-sites
	 *
	 * @since 4.0
	 */
	public static function hide_vc_buttons_media_library() {
		$screen = get_current_screen();
		if ( isset( $screen->id ) && 'attachment' == $screen->id ) {
			echo '<style>.composer-switch{display:none!important;}</style>';
		}

	}

	/**
	 * Adds Customizer settings for VC
	 *
	 * @since 4.0
	 */
	public static function customizer_settings( $panels ) {
		$panels['visual_composer'] = array(
			'title'      => __( 'Visual Composer', 'total' ),
			'settings'   => WPEX_VCEX_DIR . 'vc-customizer-settings.php',
			'is_section' => true,
		);
		return $panels;
	}

}
new WPEX_Visual_Composer_Config();