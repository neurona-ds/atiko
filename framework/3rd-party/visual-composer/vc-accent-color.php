<?php
/**
 * Visual Composer Accent Colors
 *
 * @package Total WordPress Theme
 * @subpackage Visual Composer
 * @since 3.6.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start class
if ( ! class_exists( 'WPEX_VC_Accents' ) ) {

	class WPEX_VC_Accents {

		/**
		 * Start things up
		 *
		 * @since 3.6.0
		 */
		public function __construct() {
			add_filter( 'wpex_accent_texts', array( 'WPEX_VC_Accents', 'accent_texts' ) );
			add_filter( 'wpex_accent_borders', array( 'WPEX_VC_Accents', 'accent_borders' ) );
			add_filter( 'wpex_accent_backgrounds', array( 'WPEX_VC_Accents', 'accent_backgrounds' ) );
		}

		/**
		 * Adds border accents for WooCommerce styles
		 *
		 * @since 2.1.0
		 */
		public static function accent_texts( $texts ) {
			return array_merge( array(
				'.wpex-carousel-woocommerce .wpex-carousel-entry-details',
			), $texts );
		}

		/**
		 * Adds border accents for WooCommerce styles
		 *
		 * @since 2.1.0
		 */
		public static function accent_borders( $borders ) {
			return array_merge( array(
				'.vcex-heading-bottom-border-w-color .vcex-heading-inner' => array( 'bottom' ),
				'.wpb_tabs.tab-style-alternative-two .wpb_tabs_nav li.ui-tabs-active a' => array( 'bottom' ),
			), $borders );
		}

		/**
		 * Adds border accents for WooCommerce styles
		 *
		 * @since 2.1.0
		 */
		public static function accent_backgrounds( $backgrounds ) {
			return array_merge( array(
				'.vcex-skillbar-bar',
				'.vcex-icon-box.style-five.link-wrap:hover',
				'.vcex-icon-box.style-four.link-wrap:hover',
				'.vcex-recent-news-date span.month',
				'.vcex-pricing.featured .vcex-pricing-header',
				'.vcex-testimonials-fullslider .sp-button:hover',
				'.vcex-testimonials-fullslider .sp-selected-button',
				'.vcex-social-links a:hover',
				'.vcex-testimonials-fullslider.light-skin .sp-button:hover',
				'.vcex-testimonials-fullslider.light-skin .sp-selected-button',
				'.vcex-divider-dots span',
				'.vcex-testimonials-fullslider .sp-button.sp-selected-button',
				'.vcex-testimonials-fullslider .sp-button:hover',
			), $backgrounds );
		}

	}

}
new WPEX_VC_Accents;