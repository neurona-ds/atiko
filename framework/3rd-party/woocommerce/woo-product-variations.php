<?php
/**
 * Perform all main WooCommerce configurations for this theme
 *
 * @package Total WordPress Theme
 * @subpackage WooCommerce
 * @version 4.0
 *
 * @deprecated 4.1
 *
 */

if ( ! class_exists( 'WPEX_WC_Product_Variations' ) ) {

	class WPEX_WC_Product_Variations {

		/**
		 * Main Class Constructor
		 *
		 * @since 4.0
		 */
		public function __construct() {
			add_filter( 'wc_additional_variation_images_gallery_images_class', array( $this, 'custom_class' ) );
			add_filter( 'wc_additional_variation_images_custom_swap', '__return_true' );
			add_filter( 'wc_additional_variation_images_custom_reset_swap', '__return_true' );
			add_filter( 'wc_additional_variation_images_custom_original_swap', '__return_true' );
			add_filter( 'wc_additional_variation_images_get_first_image', '__return_true' );
			add_action( 'wp_enqueue_scripts', array( $this, 'custom_js' ) );
		}

		/**
		 * Alter default class for the variations
		 *
		 * @since 3.6.0
		 */
		public function custom_class() {
			return '.product-variation-thumbs';
		}

		/**
		 * Loads custom js for variations
		 *
		 * @since 3.6.0
		 */
		public function custom_js() {
			if ( is_product() && wc_get_product()->is_type( 'variable' ) ){
				wp_enqueue_script(
					'wpex-wc-additional-variation-images',
					wpex_asset_url( 'js/dynamic/wc-variations.js' ),
					array( 'jquery' ),
					WPEX_THEME_VERSION,
					true
				);
			}
		}

	}

}
new WPEX_WC_Product_Variations;