<?php
/**
 * Creates a global object for various theme settings.
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.0
 *
 * @deprecated 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main Class
 *
 * @since 3.0.0
 */
class WPEX_Global_Theme_Object {

	/**
	 * vc_css_ids variable
	 *
	 * Save array of post ids that need fetching to load VC css classes
	 *
	 * @since 3.0.0
	 */
	public $vc_css_ids = array();

	/**
	 * Generate object
	 *
	 * @since 3.0.0
	 */
	public function generate_obj() {

		// Loop through methods and save vars
		$methods = get_class_methods( $this );
		foreach ( $methods as $method ) {
			if ( 'generate_obj' != $method ) {
				$return = $this->$method();
				if ( $return ) {
					$this->$method = $return;
				} else {
					$this->$method = false;
				}
			}
		}

		// Return this
		return $this;

	}

	/**
	 * Store current post ID
	 *
	 * @since 3.0.0
	 */
	private function post_id() {
		return wpex_get_current_post_id();
	}

	/**
	 * Returns correct theme skin
	 *
	 * @since 3.0.0
	 */
	private function skin() {
		if ( function_exists( 'wpex_active_skin' ) ) {
			return wpex_active_skin();
		}
	}

	/**
	 * Checks if the current post/page is using the Visual Composer
	 *
	 * @since 3.0.0
	 */
	private function has_composer() {
		return wpex_post_has_vc_content();
	}

	/**
	 * Checks if we are in the front-end composer mode
	 *
	 * @since 3.0.0
	 */
	private function vc_is_inline() {
		if ( wpex_vc_is_inline() ) {
			return true;
		}
	}

	/**
	 * Checks if retina is enabled
	 *
	 * @since 3.0.0
	 */
	private function retina() {
		return wpex_is_retina_enabled();
	}

	/**
	 * Main Layout Style
	 *
	 * @since 3.0.0
	 */
	private function main_layout() {
		return wpex_site_layout();
	}

	/**
	 * Checks if responsive is enabled
	 *
	 * @since 3.0.0
	 */
	private function responsive() {
		return wpex_is_layout_responsive();
	}

	/**
	 * Returns correct post layout
	 *
	 * @since 3.0.0
	 */
	private function post_layout() {
		return wpex_content_area_layout();
	}

	/**
	 * Checks if header builder is enabled
	 *
	 * @since 3.0.0
	 */
	private function header_builder() {
		return wpex_header_builder_id();
	}

	/**
	 * Checks if footer builder is enabled
	 *
	 * @since 3.0.0
	 */
	private function footer_builder() {
		return wpex_footer_builder_id();
	}

	/**
	 * Checks if header is enabled
	 *
	 * @since 3.0.0
	 */
	private function has_header() {
		return wpex_has_header();
	}

	/**
	 * Checks if header overlay is enabled
	 *
	 * @since 3.0.0
	 */
	private function has_overlay_header() {
		return wpex_has_overlay_header();
	}

	/**
	 * Header overlay style
	 *
	 * @since 3.0.0
	 */
	private function header_overlay_style() {
		return wpex_overlay_header_style();
	}

	/**
	 * Header overlay logo
	 *
	 * @since 3.0.0
	 */
	private function header_overlay_logo() {
		return wpex_overlay_header_logo_img();
	}
	
	/**
	 * Returns header style
	 *
	 * @since 3.0.0
	 */
	private function header_style() {
		return wpex_header_style();
	}

	/**
	 * Returns header logo string
	 *
	 * @since 4.0
	 */
	private function header_logo_title() {
		return wpex_header_logo_title();
	}

	/**
	 * Returns header logo
	 *
	 * @since 3.0.0
	 */
	private function header_logo() {
		return wpex_header_logo_img();
	}

	/**
	 * Returns header logo URL
	 *
	 * @since 4.0
	 */
	private function header_logo_url() {
		return wpex_header_logo_url();
	}

	/**
	 * Returns header logo icon
	 *
	 * @since 4.0
	 */
	private function header_logo_icon() {
		return wpex_header_logo_icon();
	}

	/**
	 * Returns retina header logo
	 *
	 * @since 3.0.0
	 */
	private function retina_header_logo() {
		return wpex_header_logo_img_retina();
	}

	/**
	 * Returns retina header logo height
	 *
	 * @since 3.5.3
	 */
	private function retina_header_logo_height() {
		return wpex_header_logo_img_retina_height();
	}

	/**
	 * Fixed header style
	 *
	 * @since 3.4.0
	 */
	private function fixed_header_style() {
		return wpex_sticky_header_style();
	}

	/**
	 * Check if has fixed header
	 *
	 * @since 3.0.0
	 */
	private function has_fixed_header() {
		return wpex_has_sticky_header();
	}

	/**
	 * Check if shrink fixed header is enabled
	 * Only enabled for header styles one and five
	 *
	 * @since 3.0.0
	 */
	private function shrink_fixed_header() {
		return wpex_has_shrink_sticky_header();
	}

	/**
	 * Returns fixed header logo
	 *
	 * @since 3.0.0
	 */
	private function fixed_header_logo() {
		return wpex_sticky_header_logo_img();
	}

	/**
	 * Returns fixed header logo
	 *
	 * @since 3.0.0
	 */
	private function fixed_header_logo_retina() {
		return wpex_sticky_header_logo_img_retina();
	}

	/**
	 * Returns fixed header logo height
	 *
	 * @since 3.0.0
	 */
	private function fixed_header_logo_retina_height() {
		return wpex_sticky_header_logo_img_retina_height();
	}

	/**
	 * Header Aside Content check so we can load custom CSS from the customizer
	 *
	 * @since 3.0.0
	 */
	private function header_aside_content() {
		return wpex_header_aside_content();
	}

	/**
	 * Get header menu location
	 *
	 * @since 3.4.0
	 */
	private function header_menu_location() {
		return wpex_header_menu_location();
	}

	/**
	 * Check if header has menu
	 *
	 * Was used for Customizer conditional only
	 *
	 * @since 3.4.0
	 */
	private function has_header_menu() {
		return wpex_header_has_menu();
	}

	/**
	 * Check if search is enabled in the menu // Fallback setting
	 *
	 * @since 3.0.0
	 */
	private function has_menu_search() {
		return wpex_header_menu_supports_search();
	}

	/**
	 * Returns menu search style
	 *
	 * @since 3.0.0
	 */
	private function menu_search_style() {
		return wpex_header_menu_search_style();
	}

	/**
	 * Returns header menu cart style
	 *
	 * @since 3.0.0
	 */
	private function menu_cart_style() {
		if ( function_exists( 'wpex_header_menu_cart_style' ) ) {
			return wpex_header_menu_cart_style();
		}
	}

	/**
	 * Returns mobile menu style
	 *
	 * @since 3.0.0
	 */
	private function mobile_menu_style() {
		return wpex_header_menu_mobile_style();
	}

	/**
	 * Returns mobile menu toggle style
	 *
	 * @since 3.0.0
	 */
	private function mobile_menu_toggle_style() {
		return wpex_header_menu_mobile_toggle_style();
	}

	/**
	 * Check if the mobile menu is enabled or not
	 *
	 * @since 2.1.04
	 */
	private function has_mobile_menu() {
		return wpex_header_has_mobile_menu();
	}

	/**
	 * Returns sidebar menu source
	 *
	 * @since 3.0.0
	 */
	private function sidr_menu_source( $id = '' ) {
		return wpex_sidr_menu_source( $id );
	}

	/**
	 * Returns correct post slider shortcode
	 *
	 * @since 1.6.0
	 */
	private function post_slider_shortcode() {
		return wpex_get_post_slider_shortcode();
	}

	/**
	 * Checks if the page has a slider
	 *
	 * @since 3.0.0
	 */
	private function has_post_slider() {
		return wpex_post_has_slider();
	}

	/**
	 * Returns post slider position
	 *
	 * @since 3.0.0
	 */
	private function post_slider_position() {
		return wpex_post_slider_position();
	}

	/**
	 * Checks if the topbar is enabled
	 *
	 * @since 3.0.0
	 */
	private function has_top_bar() {
		return wpex_has_topbar();
	}

	/**
	 * Returns topbar content
	 *
	 * @since 3.0.0
	 */
	private function top_bar_content() {
		return wpex_topbar_content();
	}

	/**
	 * Returns topbar content
	 *
	 * @since 3.0.0
	 */
	private function top_bar_social_alt() {
		return wpex_topbar_social_alt_content();
	}	

	/**
	 * Returns correct toggle_bar_content_id
	 *
	 * @since 3.0.0
	 */
	private function toggle_bar_content_id() {
		return wpex_togglebar_content_id();
	}

	/**
	 * Checks if the topbar is enabled
	 *
	 * @since 3.0.0
	 */
	private function has_togglebar() {
		return wpex_has_togglebar();
	}

	/**
	 * Returns page header style
	 *
	 * @since 3.0.0
	 */
	private function page_header_style() {
		return wpex_page_header_style();
	}

	/**
	 * Checks if the page header is enabled
	 *
	 * @since 3.0.0
	 */
	private function has_page_header() {
		return wpex_has_page_header();
	}

	/**
	 * Checks if the page header has a title
	 *
	 * @since 3.0.0
	 */
	private function has_page_header_title() {
		return wpex_has_page_header_title();
	}

	/**
	 * Returns current page header title background
	 *
	 * @since 3.0.0
	 */
	private function page_header_bg_image() {
		return wpex_page_header_background_image();
	}

	/**
	 * Returns page subheading
	 *
	 * @since 3.0.0
	 */
	private function get_page_subheading() {
		return wpex_page_header_subheading_content();
	}

	/**
	 * Checks if the page header has subheading
	 *
	 * @since 3.0.0
	 */
	private function has_page_header_subheading() {
		return wpex_page_header_has_subheading();
	}

	/**
	 * Checks if breadcrumbs are enabled
	 *
	 * @since 3.0.0
	 */
	private function has_breadcrumbs() {
		return wpex_has_breadcrumbs();
	}

	/**
	 * Checks if the footer is enabled
	 *
	 * @since 3.0.0
	 */
	private function has_footer() {
		return wpex_has_footer();
	}

	/**
	 * Checks if footer widgets are enabled
	 *
	 * @since 3.0.0
	 */
	private function has_footer_widgets() {
		return wpex_footer_has_widgets();
	}

	/**
	 * Checks if footer widgets are enabled
	 *
	 * @since 3.0.0
	 */
	private function has_footer_reveal() {
		return wpex_footer_has_reveal();
	}

	/**
	 * Checks if footer callout is enabled
	 *
	 * @since 3.0.0
	 */
	private function has_footer_callout() {
		return wpex_has_callout();
	}

	/**
	 * Footer callout content
	 *
	 * @since 3.0.0
	 */
	private function footer_callout_content() {
		return wpex_callout_content();
	}

	/**
	 * Checks if social share is enabled
	 *
	 * @since 3.0.0
	 */
	private function has_social_share() {
		return wpex_has_social_share();
	}

	/**
	 * No longer used but keeping to prevent errors
	 *
	 * @since 3.0.0
	 */
	private function is_mobile() {
		return false;
	}

}

/**
 * Helper function: Returns global object or property from global object
 * IMPORTANT: Must be loaded on init to prevent issues with the Visual Composer
 *
 * @since 2.1.0
 */
function wpex_global_obj( $key = null ) {
	global $wpex_theme;
	if ( $key ) {

		// Key must exist in the global var else lets try and get it
		if ( isset( $wpex_theme->$key ) ) {
			return $wpex_theme->$key;
		}

		// Key doesn't exist in object, lets re-generate our class
		// Maybe we should make a helper function for this...
		else {
			global $wpex_theme;
			$wpex_theme = new WPEX_Global_Theme_Object;
			$wpex_theme = $wpex_theme->generate_obj();
			if ( isset( $wpex_theme->$key ) ) {
				return $wpex_theme->$key;
			}
		}
	}

	// No key defined so parse whole global object and return global var
	else {
		global $wpex_theme;
		$wpex_theme = new WPEX_Global_Theme_Object;
		$wpex_theme = $wpex_theme->generate_obj();
		return $wpex_theme;
	}

}