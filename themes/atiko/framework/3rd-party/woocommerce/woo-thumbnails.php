<?php
/**
 * Theme tweaks for WooCommerce images
 *
 * @package Total WordPress Theme
 * @subpackage WooCommerce
 * @version 4.0
 *
 */

if ( ! class_exists( 'WPEX_WooCommerce_Thumbnails' ) ) {

	class WPEX_WooCommerce_Thumbnails {


		/**
		 * Main Class Constructor
		 *
		 * @since 4.0
		 */
		public function __construct() {

			// Admin only functions
			if ( is_admin() ) {

				// Remove image size settings in Woo Product Display tab
				add_filter( 'woocommerce_product_settings', array( 'WPEX_WooCommerce_Thumbnails', 'remove_product_settings' ) );

				// Add WooCommerce tab to Total image sizes panel
				add_filter( 'wpex_image_sizes_tabs', array( 'WPEX_WooCommerce_Thumbnails', 'image_sizes_tabs' ), 10 );

			}

			// Add image sizes to Total panel
			add_filter( 'wpex_image_sizes', array( 'WPEX_WooCommerce_Thumbnails', 'add_image_sizes' ), 99 );
			
			// Filter image sizes and return cropped versions
			if ( wpex_get_mod( 'image_resizing', true ) ) {

				// @todo Creates double checks in image resize functions
				// Need to remove all total wpex functions and then apply retina to Woo images via another method
				add_filter( 'wp_get_attachment_image_src', array( 'WPEX_WooCommerce_Thumbnails', 'attachment_image_src' ), 50, 4 );

				// Alter cart thumbnail
				add_filter( 'woocommerce_cart_item_thumbnail', array( 'WPEX_WooCommerce_Thumbnails', 'cart_item_thumbnail' ), 10, 3 );

			}

		}

		/**
		 * Remove image size settings in Woo Product Display tab.
		 *
		 * @since 2.0.0
		 */
		public static function remove_product_settings( $settings ) {
			$remove = array(
				'image_options',
				'shop_catalog_image_size',
				'shop_single_image_size',
				'shop_thumbnail_image_size',
				'woocommerce_enable_lightbox'
			);
			foreach( $settings as $key => $val ) {
				if ( isset( $val['id'] ) && in_array( $val['id'], $remove ) ) {
					unset( $settings[$key] );
				}
			}
			return $settings;
		}

		/**
		 * Add WooCommerce tab to Total image sizes panel.
		 *
		 * @since 3.3.2
		 */
		public static function image_sizes_tabs( $array ) {
			$array['woocommerce'] = 'WooCommerce';
			return $array;
		}

		/**
		 * Add image sizes to Total panel.
		 *
		 * @since 2.0.0
		 */
		public static function add_image_sizes( $sizes ) {
			return array_merge( $sizes, array(
				'shop_catalog' => array(
					'label'   => __( 'Product Entry', 'total' ),
					'width'   => 'woo_entry_width',
					'height'  => 'woo_entry_height',
					'crop'    => 'woo_entry_image_crop',
					'section' => 'woocommerce',
				),
				'shop_single' => array(
					'label'   => __( 'Product Post', 'total' ),
					'width'   => 'woo_post_width',
					'height'  => 'woo_post_height',
					'crop'    => 'woo_post_image_crop',
					'section' => 'woocommerce',
				),
				'shop_single_thumbnail' => array(
					'label'   => __( 'Product Post Thumbnail', 'total' ),
					'width'   => 'woo_post_thumb_width',
					'height'  => 'woo_post_thumb_height',
					'crop'    => 'woo_post_thumb_crop',
					'section' => 'woocommerce',
				),
				'shop_thumbnail_cart' => array(
					'label'     => __( 'Shop & Cart Thumbnail', 'total' ),
					'width'     => 'woo_shop_thumbnail_width',
					'height'    => 'woo_shop_thumbnail_height',
					'crop'      => 'woo_shop_thumbnail_crop',
					'section'   => 'woocommerce',
				),
				'shop_category' => array(
					'label'     => __( 'Product Category Entry', 'total' ),
					'width'     => 'woo_cat_entry_width',
					'height'    => 'woo_cat_entry_height',
					'crop'      => 'woo_cat_entry_image_crop',
					'section'   => 'woocommerce',
				)
			) );
		}

		/**
		 * Filter image sizes and return cropped versions where we aren't altering the HTML
		 *
		 * @since 4.0
		 */		
		public static function attachment_image_src( $image, $attachment_id, $size, $icon ) {

			if ( $image ) {

				// Shop single
				if ( 'shop_single' == $size ) {

					$dims = wpex_get_thumbnail_sizes( 'shop_single' );

					$generate_image = wpex_image_resize( array(
						'attachment' => $attachment_id,
						'size'       => 'shop_single',
						'height'     => isset( $dims['height'] ) ? $dims['height'] : '',
						'width'      => isset( $dims['width'] ) ? $dims['width'] : '',
						'crop'       => isset( $dims['crop'] ) ? $dims['crop'] : '',
						'image_src'  => $image, // IMPORTANT !!
					) );

					$image = $generate_image ? $generate_image : $image;

				}

				// Shop thumbnail
				elseif ( 'shop_thumbnail' == $size ) {

					$dims = wpex_get_thumbnail_sizes( 'shop_single_thumbnail' );

					$generate_image = wpex_image_resize( array(
						'attachment' => $attachment_id,
						'size'       => 'shop_single_thumbnail',
						'height'     => isset( $dims['height'] ) ? $dims['height'] : '',
						'width'      => isset( $dims['width'] ) ? $dims['width'] : '',
						'crop'       => isset( $dims['crop'] ) ? $dims['crop'] : '',
						'image_src'  => $image, // IMPORTANT !!
					) );

					$image = $generate_image ? $generate_image : $image;
					
				}

			}

			// Return src
			return $image;

		}

		/**
		 * Alter the cart item thumbnail size
		 *
		 * Needed to add retina support and properly crop images
		 *
		 * @since 4.0
		 */
		public static function cart_item_thumbnail( $thumb, $cart_item, $cart_item_key ) {
			if ( ! empty( $cart_item['variation_id'] )
				&& $thumbnail = get_post_thumbnail_id( $cart_item['variation_id'] )
			) {
				return wpex_get_post_thumbnail( array(
					'size'       => 'shop_thumbnail_cart',
					'attachment' => $thumbnail,
				) );
			} elseif ( isset( $cart_item['product_id'] )
				&& $thumbnail = get_post_thumbnail_id( $cart_item['product_id'] )
			) {
				return wpex_get_post_thumbnail( array(
					'size'       => 'shop_thumbnail_cart',
					'attachment' => $thumbnail,
				) );
			} else {
				return wc_placeholder_img();
			}
			return $thumb;
		}

	}

}
new WPEX_WooCommerce_Thumbnails;