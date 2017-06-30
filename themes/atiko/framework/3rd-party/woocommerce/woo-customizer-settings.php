<?php
/**
 * WooCommerce Customizer Settings
 *
 * @package Total WordPress Theme
 * @subpackage WooCommerce
 * @version 4.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Strings
$refresh_desc = __( 'You must save your options and refresh your live site to preview changes to this setting. You may have to also add or remove an item from the cart to clear the WooCommerce cache.', 'total' );
$refresh_desc_2 = __( 'You must save your options and refresh your live site to preview changes to this setting.', 'total' );

// General
$this->sections['wpex_woocommerce_general'] = array(
	'title' => __( 'General', 'total' ),
	'panel' => 'wpex_woocommerce',
	'settings' => array(
		array(
			'id' => 'woo_custom_sidebar',
			'default' => true,
			'control' => array(
				'label' => __( 'Custom WooCommerce Sidebar', 'total' ),
				'type' => 'checkbox',
			),
		),
		array(
			'id' => 'woo_menu_icon_display',
			'default' => 'icon_count',
			'transport' => 'postMessage',
			'control' => array(
				'label' => __( 'Menu Cart: Display', 'total' ),
				'type' => 'select',
				'choices' => array(
					'disabled' => __( 'Disabled', 'total' ),
					'icon' => __( 'Icon', 'total' ),
					'icon_total' => __( 'Icon And Cart Total', 'total' ),
					'icon_count' => __( 'Icon And Cart Count', 'total' ),
				),
				'desc' => $refresh_desc,
			),
		),
		array(
			'id' => 'woo_menu_icon_class',
			'default' => 'shopping-cart',
			'transport' => 'postMessage',
			'control' => array(
				'label' => __( 'Shop Icon', 'total' ),
				'type' => 'select',
				'choices' => array(
					'shopping-cart' => __( 'Shopping Cart', 'total' ),
					'shopping-bag' => __( 'Shopping Bag', 'total' ),
					'shopping-basket' => __( 'Shopping Basket', 'total' ),
				),
				'desc' => $refresh_desc,
			),
		),
		array(
			'id' => 'woo_menu_icon_style',
			'default' => 'drop_down',
			'transport' => 'postMessage',
			'control' => array(
				'label' => __( 'Menu Cart: Style', 'total' ),
				'type' => 'select',
				'choices' => array(
					'drop_down' => __( 'Drop-Down', 'total' ),
					'overlay' => __( 'Open Cart Overlay', 'total' ),
					'store' => __( 'Go To Store', 'total' ),
					'custom-link' => __( 'Custom Link', 'total' ),
				),
				'desc' => $refresh_desc,
			),
		),
		array(
			'id' => 'woo_menu_icon_custom_link',
			'transport' => 'postMessage',
			'control' => array(
				'label' => __( 'Menu Cart: Custom Link', 'total' ),
				'type' => 'text',
				'desc' => $refresh_desc,
			),
		),
	)
);

// Archives
$this->sections['wpex_woocommerce_archives'] = array(
	'title' => __( 'Shop & Archives', 'total' ),
	'panel' => 'wpex_woocommerce',
	'settings' => array(
		array(
			'id' => 'woo_shop_title',
			'default' => 'on',
			'control' => array(
				'label' => __( 'Shop Title', 'total' ),
				'type' => 'checkbox',
			),
		),
		array(
			'id' => 'woo_entry_equal_height',
			'control' => array(
				'label' => __( 'Equal Heights', 'total' ),
				'type' => 'checkbox',
				'desc' => __( 'Enabling this setting will display all the add to cart buttons in the same spot for each entry.', 'total' ),
			),
		),
		array(
			'id' => 'woo_shop_slider',
			'control' => array(
				'label' => __( 'Shop Slider', 'total' ),
				'type' => 'text',
			),
		),
		array(
			'id' => 'woo_shop_posts_per_page',
			'default' => '12',
			'control' => array(
				'label' => __( 'Shop Posts Per Page', 'total' ),
				'type' => 'text',
				'desc' => __( 'You must save your options and refresh your live site to preview changes to this setting.', 'total' ),
			),
		),
		array(
			'id' => 'woo_shop_layout',
			'default' => 'full-width',
			'control' => array(
				'label' => __( 'Layout', 'total' ),
				'type' => 'select',
				'choices' => $post_layouts,
			),
		),
		array(
			'id' => 'woocommerce_shop_columns',
			'default' => '4',
			'control' => array(
				'label' => __( 'Shop Columns', 'total' ),
				'type' => 'select',
				'choices' => wpex_grid_columns(),

			),
		),
		array(
			'id' => 'woo_category_description_position',
			'default' => 'under_title',
			'control' => array(
				'label' => __( 'Category Description Position', 'total' ),
				'type' => 'select',
				'choices' => array(
					'under_title' => __( 'Under Title', 'total' ),
					'above_loop' => __( 'Above Loop', 'total' ),
				),

			),
		),
		array(
			'id' => 'woo_shop_sort',
			'default' => 'on',
			'transport' => 'postMessage',
			'control' => array(
				'label' => __( 'Shop Sort', 'total' ),
				'type' => 'checkbox',
				'desc' => __( 'You must save your options and refresh your live site to preview changes to this setting.', 'total' ),
			),
		),
		array(
			'id' => 'woo_shop_result_count',
			'default' => 'on',
			'transport' => 'postMessage',
			'control' => array(
				'label' => __( 'Shop Result Count', 'total' ),
				'type' => 'checkbox',
				'desc' => __( 'You must save your options and refresh your live site to preview changes to this setting.', 'total' ),
			),
		),
		array(
			'id' => 'woo_product_entry_style',
			'default' => 'image-swap',
			'control' => array(
				'label' => __( 'Product Entry Media', 'total' ),
				'type' => 'select',
				'choices' => array(
					'featured-image' => __( 'Featured Image', 'total' ),
					'image-swap' => __( 'Image Swap', 'total' ),
					'gallery-slider' => __( 'Gallery Slider', 'total' ),
				),
			),
		),
	)
);

// Single
$this->sections['wpex_woocommerce_single'] = array(
	'title' => __( 'Single', 'total' ),
	'panel' => 'wpex_woocommerce',
	'settings' => array(
		array(
			'id' => 'woo_shop_single_title',
			'default' => __( 'Store', 'total' ),
			'control' => array(
				'label' => __( 'Page Header Title', 'total' ),
				'type' => 'text',
			),
		),
		array(
			'id' => 'woo_product_layout',
			'default' => 'full-width',
			'control' => array(
				'label' => __( 'Layout', 'total' ),
				'type' => 'select',
				'choices' => $post_layouts,
			),
		),
		array(
			'id' => 'woo_product_gallery_slider',
			'default' => true,
			'control' => array(
				'label' => __( 'Product Gallery Slider', 'total' ),
				'type' => 'checkbox',
			),
		),
		array(
			'id' => 'woo_product_gallery_zoom',
			'default' => true,
			'control' => array(
				'label' => __( 'Product Gallery Zoom', 'total' ),
				'type' => 'checkbox',
			),
		),
		array(
			'id' => 'woo_product_gallery_lightbox',
			'default' => 'total',
			'transport' => 'postMessage',
			'control' => array(
				'label' => __( 'Product Gallery Lightbox', 'total' ),
				'type' => 'select',
				'choices' => array(
					'disabled' => __( 'Disabled', 'total' ),
					'total' => __( 'Theme Lightbox', 'total' ),
					'woo' => __( 'WooCommerce Lightbox', 'total' ),
				),
				'desc' => $refresh_desc_2,
			),
		),
		array(
			'id' => 'woocommerce_gallery_thumbnails_count',
			'default' => 5,
			'control' => array(
				'label' => __( 'Gallery Thumbnails Columns', 'total' ), 
				'type' => 'select',
				'choices' => array(
					'1' => 1,
					'2' => 2,
					'3' => 3,
					'4' => 4,
					'5' => 5,
					'6' => 6,
				),
			),
		),
		array(
			'id' => 'woocommerce_upsells_count',
			'default' => '4',
			'control' => array(
				'label' => __( 'Up-Sells Count', 'total' ), 
				'type' => 'text',
			),
		),
		array(
			'id' => 'woocommerce_upsells_columns',
			'default' => '4',
			'control' => array(
				'label' => __( 'Up-Sells Columns', 'total' ), 
				'type' => 'select',
				'choices' => wpex_grid_columns(),
			),
		),
		array(
			'id' => 'woocommerce_related_count',
			'default' => '4',
			'control' => array(
				'label' => __( 'Related Items Count', 'total' ), 
				'type' => 'text',
			),
		),
		array(
			'id' => 'woocommerce_related_columns',
			'default' => '4',
			'control' => array(
				'label' => __( 'Related Products Columns', 'total' ),
				'type' => 'select',
				'choices' => wpex_grid_columns(),
			),
		),
		array(
			'id' => 'woo_single_gallery_include_thumbnail',
			'default' => true,
			'control' => array(
				'label' => __( 'Include Featured Image in Gallery', 'total' ),
				'type' => 'checkbox',
			),
		),
		array(
			'id' => 'woo_product_meta',
			'default' => 'on',
			'transport' => 'postMessage',
			'control' => array(
				'label' => __( 'Product Meta', 'total' ),
				'type' => 'checkbox',
				'desc' => $refresh_desc_2,
			),
		),
		array(
			'id' => 'social_share_woo',
			'default' => false,
			'control' => array(
				'label' => __( 'Social Share', 'total' ),
				'type' => 'checkbox',
			),
		),
		array(
			'id' => 'woo_next_prev',
			'default' => 'on',
			'control' => array(
				'label' => __( 'Next & Previous Links', 'total' ),
				'type' => 'checkbox',
			),
		),
	),
);

// Cart
$this->sections['wpex_woocommerce_cart'] = array(
	'title' => __( 'Cart', 'total' ),
	'panel' => 'wpex_woocommerce',
	'settings' => array(
		array(
			'id' => 'woocommerce_cross_sells_count',
			'default' => '2',
			'control' => array(
				'label' => __( 'Cross-Sells Count', 'total' ),
				'type' => 'text',
			),
		),
		array(
			'id' => 'woocommerce_cross_sells_columns',
			'default' => '2',
			'control' => array(
				'label' => __( 'Cross-Sells Columns', 'total' ),
				'type' => 'select',
				'choices' => wpex_grid_columns(),
			),
		),
	),
);


// Styling
$this->sections['wpex_woocommerce_styling'] = array(
	'title' => __( 'Styling', 'total' ),
	'panel' => 'wpex_woocommerce',
	'settings' => array(
		array(
			'id' => 'onsale_bg',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'On Sale Background', 'total' ),
			),
			'inline_css' => array(
				'target' => '.woocommerce span.onsale',
				'alter' => 'background-color',
			),
		),
		array(
			'id' => 'onsale_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'On Sale Color', 'total' )
			),
			'inline_css' => array(
				'target' => '.woocommerce span.onsale',
				'alter' => 'color',
			),
		),
		array(
			'id' => 'woo_product_title_link_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Product Entry Title Color', 'total' )
			),
			'inline_css' => array(
				'target' => array(
					'.woocommerce ul.products li.product .woocommerce-loop-product__title,.woocommerce ul.products li.product .woocommerce-loop-category__title',
				),
				'alter' => 'color',
			),
		),
		array(
			'id' => 'woo_product_title_link_color_hover',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Product Entry Title Color: Hover', 'total' )
			),
			'inline_css' => array(
				'target' => array(
					'.woocommerce ul.products li.product .woocommerce-loop-product__title:hover,.woocommerce ul.products li.product .woocommerce-loop-category__title:hover',
				),
				'alter' => 'color',
			),
		),
		array(
			'id' => 'woo_price_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Global Price Color', 'total' )
			),
			'inline_css' => array(
				'target' => array(
					'.price',
					'.amount',
					'.woocommerce ul.products li.product .price .amount',
					'.woocommerce .widget_price_filter .price_slider_amount .from, .woocommerce .widget_price_filter .price_slider_amount .to'
				),
				'alter' => 'color',
			),
		),
		array(
			'id' => 'woo_product_entry_price_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Product Entry Price Color', 'total' )
			),
			'inline_css' => array(
				'target' => array(
					'.woocommerce ul.products li.product .price',
					'.woocommerce ul.products li.product .price .amount',
				),
				'alter' => 'color',
			),
		),
		array(
			'id' => 'woo_single_price_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Single Product Price Color', 'total' )
			),
			'inline_css' => array(
				'target' => array(
					'.woocommerce .summary .price',
					'.woocommerce .summary .amount',
				),
				'alter' => 'color',
			),
		),
		array(
			'id' => 'woo_stars_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Star Ratings Color', 'total' )
			),
			'inline_css' => array(
				'target' => array(
					'.woocommerce p.stars a',
					'.woocommerce .star-rating',
				),
				'alter' => 'color',
			),
		),
		array(
			'id' => 'woo_single_tabs_active_border_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Product Tabs Active Border Color', 'total' )
			),
			'inline_css' => array(
				'target' => array(
					'.woocommerce div.product .woocommerce-tabs ul.tabs li.active a',
				),
				'alter' => 'border-color',
			),
		),
		array(
			'id' => 'woo_button_bg',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Woo Button Background', 'total' )
			),
			'inline_css' => array(
				'target' => array(
					'.woocommerce input#submit',
					'.woocommerce .button',
					'a.wc-forward',
				),
				'alter' => 'background',
				'important' => true,
			),
		),
		array(
			'id' => 'woo_button_color',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Woo Button Color', 'total' )
			),
			'inline_css' => array(
				'target' => array(
					'.woocommerce input#submit',
					'.woocommerce .button',
					'a.wc-forward',
				),
				'alter' => 'color',
				'important' => true,
			),
		),
		array(
			'id' => 'woo_button_border_radius',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'text',
				'label' => __( 'Woo Button Border Radius', 'total' )
			),
			'inline_css' => array(
				'target' => array(
					'.woocommerce input#submit',
					'.woocommerce .button',
					'a.wc-forward',
				),
				'alter' => 'border-radius',
				'important' => true,
				'sanitize' => 'px',
			),
		),
		array(
			'id' => 'woo_button_bg_hover',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Woo Button Hover: Background', 'total' )
			),
			'inline_css' => array(
				'target' => array(
					'.woocommerce input#submit:hover',
					'.woocommerce .button:hover',
					'a.wc-forward:hover',
				),
				'alter' => 'background',
				'important' => true,
			),
		),
		array(
			'id' => 'woo_button_color_hover',
			'transport' => 'postMessage',
			'control' => array(
				'type' => 'color',
				'label' => __( 'Woo Button Hover: Color', 'total' )
			),
			'inline_css' => array(
				'target' => array(
					'.woocommerce input#submit:hover',
					'.woocommerce .button:hover',
					'a.wc-forward:hover',
				),
				'alter' => 'color',
				'important' => true,
			),
		),
	),
);