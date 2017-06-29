<?php
/**
 * WooCommerce helper functions
 * This functions only load if WooCommerce is enabled because
 * they should be used within Woo loops only.
 *
 * @package Total WordPress Theme
 * @subpackage WooCommerce
 * @version 4.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Outputs placeholder image
 *
 * @since 1.0.0
 */
function wpex_woo_placeholder_img() {
	if ( function_exists( 'wc_placeholder_img_src' ) && wc_placeholder_img_src() ) {
		$placeholder = '<img src="'. wc_placeholder_img_src() .'" alt="'. esc_attr__( 'Placeholder Image', 'total' ) .'" class="woo-entry-image-main" />';
		$placeholder = apply_filters( 'wpex_woo_placeholder_img_html', $placeholder );
		if ( $placeholder ) {
			echo $placeholder;
		}
	}
}

/**
 * Check if product is in stock
 *
 * @since 1.0.0
 */
function wpex_woo_product_instock() {
	global $product;
	if ( ! $product || ( $product && $product->is_in_stock() ) ) {
		return true;
	}
}

/**
 * Outputs product price
 *
 * @since 1.0.0
 */
function wpex_woo_product_price( $post_id = '' ) {
	echo wpex_get_woo_product_price( $post_id );
}

/**
 * Returns product price
 *
 * @since 1.0.0
 */
function wpex_get_woo_product_price( $post_id = '' ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	if ( 'product' == get_post_type( $post_id ) ) {
		$product = wc_get_product( $post_id );
		$price   = $product->get_price_html();
		if ( $price ) {
			return $price;
		}
	}
}