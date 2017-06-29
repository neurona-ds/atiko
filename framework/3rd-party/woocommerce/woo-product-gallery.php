<?php
/**
 * Theme tweaks for WooCommerce images
 *
 * @package Total WordPress Theme
 * @subpackage WooCommerce
 * @version 4.2.1
 *
 */

if ( ! class_exists( 'WPEX_WooCommerce_Product_Gallery' ) ) {

	class WPEX_WooCommerce_Product_Gallery {

		/**
		 * Main Class Constructor
		 *
		 * @since 4.1
		 */
		public function __construct() {

			// Enable product gallery
			if ( wpex_get_mod( 'woo_product_gallery_slider', true ) ) {
				add_theme_support( 'wc-product-gallery-slider' );
			}

			// Enable product gallery zoom support
			if ( wpex_get_mod( 'woo_product_gallery_zoom', true ) ) {
				add_theme_support( 'wc-product-gallery-zoom' );
			}

			// Enqueue lightbox scripts
			if ( 'total' == wpex_get_mod( 'woo_product_gallery_lightbox', 'total' ) ) {
				add_action( 'wp_enqueue_scripts', array( 'WPEX_WooCommerce_Product_Gallery', 'lightbox_scripts' ), 40 ); // Lightbox css is registered at priority 20
			} elseif ( 'woo' == wpex_get_mod( 'woo_product_gallery_lightbox', 'total' ) ) {
				add_theme_support( 'wc-product-gallery-lightbox' );
			}

			// Custom product gallery flexslider options
			//add_filter( 'woocommerce_single_product_carousel_options', array( 'WPEX_WooCommerce_Product_Gallery', 'flexslider_options' ) );

			// Custom gallery CSS
			add_filter( 'wpex_head_css', array( 'WPEX_WooCommerce_Product_Gallery', 'custom_css' ) );

		}

		/**
		 * Add Scripts
		 *
		 * @since 4.1
		 */
		public static function lightbox_scripts() {
			if ( ! is_product() ) {
				return;
			}
			wpex_enqueue_ilightbox_skin();
			if ( wpex_get_mod( 'woo_product_gallery_slider', true ) ) {
				$file = 'js/dynamic/wpex-wc-product-gallery-slider-lightbox.js';
			} else {
				$file = 'js/dynamic/wpex-wc-product-gallery-lightbox.js';
			}
			wp_enqueue_script(
				'wpex-wc-product-gallery-lightbox',
				wpex_asset_url( $file ),
				array( 'jquery', WPEX_THEME_JS_HANGLE ),
				WPEX_THEME_VERSION,
				true
			);
		}

		/**
		 * Custom product gallery flexslider options
		 *
		 * Not used at the moment due to WooCommerce bugs
		 *
		 * @since 4.1
		 */
		public static function flexslider_options( $options ) {
			//$options['smoothHeight']  = true; // will be added in next woo update
			//$options['directionNav'] = true; // Not sure if I like it
			/*if ( is_product() ) {
				$product = wc_get_product();
				if ( ! $product->get_attributes() ) {
					$options['animationLoop'] = true; // Breaks slider when using variable products :(
				}
			}*/
			// $options['animation']    = 'fade'; // Currently bugged in Woo 3.0
			return $options;
		}

		/**
		 * Custom CSS for gallery
		 *
		 * @since 4.1
		 */
		public static function custom_css( $css ) {
			if ( is_product() ) {
				$thumb_cols = wpex_get_mod( 'woocommerce_gallery_thumbnails_count' );
				$thumb_cols = $thumb_cols ? intval( $thumb_cols ) : 5;
				if ( $thumb_cols && 5 !== $thumb_cols ) {
					$css .= '.woocommerce div.product div.images .flex-control-thumbs li:nth-child(4n+1) {clear: none;}';
					$css .= '.product-variation-thumbs a, .woocommerce div.product div.images .flex-control-thumbs li { width:' . 100 / $thumb_cols . '%;}';
					$css .= '.woocommerce div.product div.images .flex-control-thumbs li:nth-child('. $thumb_cols .'n+1) {clear: both;}';
				}
			}
			return $css;
		}

	}

}
new WPEX_WooCommerce_Product_Gallery;