<?php
/**
 * WooCommerce accent colors
 *
 * @package Total WordPress Theme
 * @subpackage WooCommerce
 * @version 4.1
 *
 */

if ( ! class_exists( 'WPEX_WooCommerce_Accent_Colors' ) ) {

	class WPEX_WooCommerce_Accent_Colors {

		/**
		 * Main Class Constructor
		 *
		 * @since 4.1
		 */
		public function __construct() {
			add_filter( 'wpex_accent_texts', array( 'WPEX_WooCommerce_Accent_Colors', 'accent_texts' ) );
			add_filter( 'wpex_accent_borders', array( 'WPEX_WooCommerce_Accent_Colors', 'accent_borders' ) );
			add_filter( 'wpex_accent_backgrounds', array( 'WPEX_WooCommerce_Accent_Colors', 'accent_backgrounds' ) );
			add_filter( 'wpex_border_color_elements', array( 'WPEX_WooCommerce_Accent_Colors', 'border_color_elements' ) );
		}


		/**
		 * Adds border accents for WooCommerce styles.
		 *
		 * @since 4.1
		 */
		public static function accent_texts( $texts ) {
			return array_merge( array(
				'.woocommerce ul.products li.product .woocommerce-loop-product__title',
				'.woocommerce ul.products li.product .woocommerce-loop-category__title',
				'.woocommerce .woocommerce-error a.button',
				'.woocommerce .woocommerce-info a.button',
				'.woocommerce .woocommerce-message a.button',
			), $texts );
		}

		/**
		 * Adds border accents for WooCommerce styles.
		 *
		 * @since 4.1
		 */
		public static function accent_borders( $borders ) {
			return array_merge( array(
				'#current-shop-items-dropdown' => array( 'top' ),
				'.woocommerce div.product .woocommerce-tabs ul.tabs li.active a' => array( 'bottom' ),
			), $borders );
		}

		/**
		 * Adds border accents for WooCommerce styles.
		 *
		 * @since 4.1
		 */
		public static function accent_backgrounds( $backgrounds ) {
			return array_merge( array(
				'p.demo_store',
				'.woocommerce #respond input#submit',
				'.woocommerce a.button',
				'.woocommerce button.button',
				'.woocommerce input.button',
				'.woocommerce ul.products li.product .added_to_cart',
				'.woocommerce #respond input#submit.alt',
				'.woocommerce a.button.alt',
				'.woocommerce button.button.alt',
				'.woocommerce input.button.alt',
				'.woocommerce #respond input#submit:hover',
				'.woocommerce a.button:hover',
				'.woocommerce button.button:hover',
				'.woocommerce input.button:hover',
				'.woocommerce ul.products li.product .added_to_cart:hover',
				'.woocommerce #respond input#submit.alt:hover',
				'.woocommerce a.button.alt:hover',
				'.woocommerce button.button.alt:hover',
				'.woocommerce input.button.alt:hover',
				'.woocommerce-MyAccount-navigation li.is-active a',
				'.woocommerce .widget_price_filter .ui-slider .ui-slider-range',
				'.woocommerce .widget_price_filter .ui-slider .ui-slider-handle',
			), $backgrounds );
		}

		/**
		 * Adds border color elements for WooCommerce styles.
		 *
		 * @since 4.1
		 */
		public static function border_color_elements( $elements ) {
			return array_merge( array(

				// Product
				'.product_meta',
				'.woocommerce div.product .woocommerce-tabs ul.tabs',

				// Account
				'#customer_login form.login, #customer_login form.register,
				p.myaccount_user',

				// Widgets
				'.woocommerce ul.product_list_widget li:first-child,
				.woocommerce .widget_shopping_cart .cart_list li:first-child,
				.woocommerce.widget_shopping_cart .cart_list li:first-child',
				'.woocommerce ul.product_list_widget li,
				.woocommerce .widget_shopping_cart .cart_list li,
				.woocommerce.widget_shopping_cart .cart_list li',

				// Tables
				'.woocommerce-checkout #payment ul.payment_methods,
				.woocommerce table.shop_table,
				.woocommerce table.shop_table td,
				.woocommerce-cart .cart-collaterals .cart_totals tr td,
				.woocommerce-cart .cart-collaterals .cart_totals tr th,
				.woocommerce table.shop_table tbody th,
				.woocommerce table.shop_table tfoot td,
				.woocommerce table.shop_table tfoot th,
				.woocommerce .order_details,
				.woocommerce .cart-collaterals .cross-sells,
				.woocommerce-page .cart-collaterals .cross-sells,
				.woocommerce .cart-collaterals .cart_totals,
				.woocommerce-page .cart-collaterals .cart_totals,
				.woocommerce .cart-collaterals h2, .woocommerce .cart-collaterals h2,
				.woocommerce ul.order_details, .woocommerce .shop_table.order_details tfoot th,
				.woocommerce .shop_table.customer_details th,
				.woocommerce-checkout #payment ul.payment_methods,
				.woocommerce .col2-set.addresses .col-1, .woocommerce .col2-set.addresses .col-2,
				.woocommerce-cart .cart-collaterals .cart_totals .order-total th,
				.woocommerce-cart .cart-collaterals .cart_totals .order-total td',

				// Checkout
				'.woocommerce form.login,
				.woocommerce form.register,
				.woocommerce-checkout #payment,
				#add_payment_method #payment ul.payment_methods,
				.woocommerce-cart #payment ul.payment_methods,
				.woocommerce-checkout #payment ul.payment_methods',

			), $elements );
		}	

	}

}
new WPEX_WooCommerce_Accent_Colors;