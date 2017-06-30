<?php
/**
 * Perform all main WooCommerce configurations for this theme
 *
 * @package Total WordPress Theme
 * @subpackage WooCommerce
 * @version 4.1
 *
 */

if ( ! class_exists( 'WPEX_WooCommerce_Config' ) ) {

	class WPEX_WooCommerce_Config {

		/**
		 * Main Class Constructor
		 *
		 * @since 2.0.0
		 */
		public function __construct() {

			// Add theme support
			add_theme_support( 'woocommerce' );

			// Define directory for Woo functions and classes
			define( 'WPEX_WOO_CONFIG_DIR', WPEX_FRAMEWORK_DIR . '3rd-party/woocommerce/' );

			// Helper functions => must load first
			require_once WPEX_WOO_CONFIG_DIR . 'woo-helpers.php';

			// Gallery support
			require_once WPEX_WOO_CONFIG_DIR . 'woo-product-gallery.php';

			// Thumbnails / Featured Images
			require_once WPEX_WOO_CONFIG_DIR . 'woo-thumbnails.php';

			// Menu cart
			require_once WPEX_WOO_CONFIG_DIR . 'woo-header-menu-cart.php';

			// Accent colors
			require_once WPEX_WOO_CONFIG_DIR . 'woo-accent-colors.php';

			// Add Customizer settings
			add_filter( 'wpex_customizer_panels', array( 'WPEX_WooCommerce_Config', 'customizer_settings' ) );

			// These filters/actions must run on init
			add_action( 'init', array( 'WPEX_WooCommerce_Config', 'init' ) );

			// Register Woo sidebar
			add_filter( 'widgets_init', array( 'WPEX_WooCommerce_Config', 'register_woo_sidebar' ) );

			// Add Woo VC modules
			add_filter( 'vcex_builder_modules', array( 'WPEX_WooCommerce_Config', 'vc_modules' ) );

			// Set correct page ID for shop page
			add_filter( 'wpex_post_id', array( 'WPEX_WooCommerce_Config', 'shop_id' ), 10 );

			// Display correct sidebar for products
			add_filter( 'wpex_get_sidebar', array( 'WPEX_WooCommerce_Config', 'display_woo_sidebar' ) );

			// Set correct post layouts
			add_filter( 'wpex_post_layout_class', array( 'WPEX_WooCommerce_Config', 'layouts' ) );

			// Disable WooCommerce main page title
			add_filter( 'woocommerce_show_page_title', '__return_false' );

			// Alter page header title
			add_filter( 'wpex_title', array( 'WPEX_WooCommerce_Config', 'title_config' ) );

			// Show/hide main page header
			add_filter( 'wpex_display_page_header', array( 'WPEX_WooCommerce_Config', 'display_page_header' ) );

			// Make sure CSS loads on shop page
			if ( WPEX_VC_ACTIVE ) {
				add_filter( 'wpex_vc_css_ids', array( 'WPEX_WooCommerce_Config', 'shop_vc_css' ) );
			}

			// Get shop slider shortcode
			add_filter( 'wpex_post_slider_shortcode', array( 'WPEX_WooCommerce_Config', 'shop_slider_shortcode' ) );

			// Display shop slider
			add_filter( 'wpex_has_post_slider', array( 'WPEX_WooCommerce_Config', 'display_shop_slider' ) );

			// Alter page header subheading
			add_filter( 'wpex_post_subheading', array( 'WPEX_WooCommerce_Config', 'alter_subheadings' ) );

			// Show/hide category description
			add_filter( 'wpex_has_term_description_above_loop', array( 'WPEX_WooCommerce_Config', 'term_description_above_loop' ) );

			// Show/hide social share on products
			add_filter( 'wpex_has_social_share', array( 'WPEX_WooCommerce_Config', 'post_social_share' ) );

			// Show/hide next/prev on products
			add_filter( 'wpex_has_next_prev', array( 'WPEX_WooCommerce_Config', 'next_prev' ) );

			// Disable category page header image by default
			add_filter( 'wpex_term_page_header_image_enabled', array( 'WPEX_WooCommerce_Config', 'term_page_header_image_enabled' ) );

			// Scripts
			add_action( 'woocommerce_enqueue_styles', array( 'WPEX_WooCommerce_Config', 'remove_styles' ) );
			add_action( 'wp_enqueue_scripts', array( 'WPEX_WooCommerce_Config', 'add_custom_scripts' ) );

			// Social share
			add_action( 'woocommerce_after_single_product_summary', 'wpex_social_share', 11 );

			// Add HTML to product entries
			add_action( 'woocommerce_before_shop_loop_item', array( 'WPEX_WooCommerce_Config', 'add_shop_loop_item_inner_div' ) );
			add_action( 'woocommerce_after_shop_loop_item', array( 'WPEX_WooCommerce_Config', 'close_shop_loop_item_inner_div' ) );
			add_action( 'woocommerce_before_shop_loop_item', array( 'WPEX_WooCommerce_Config', 'add_shop_loop_item_out_of_stock_badge' ) );

			// Add wrapper around product entry details to align buttons
			add_action( 'woocommerce_before_shop_loop_item_title', array( 'WPEX_WooCommerce_Config', 'loop_details_open' ), 99 );
			add_action( 'woocommerce_after_shop_loop_item', array( 'WPEX_WooCommerce_Config', 'loop_details_close' ), 9 );

			// Product post
			add_action( 'woocommerce_after_single_product_summary', array( 'WPEX_WooCommerce_Config', 'clear_summary_floats' ), 1 );

			// Alter the sale tag
			add_filter( 'woocommerce_sale_flash', array( 'WPEX_WooCommerce_Config', 'woocommerce_sale_flash' ), 10, 3 );

			// Alter shop posts per page
			add_filter( 'loop_shop_per_page', array( 'WPEX_WooCommerce_Config', 'loop_shop_per_page' ), 20 );
			
			// Alter shop columns
			add_filter( 'loop_shop_columns', array( 'WPEX_WooCommerce_Config', 'loop_shop_columns' ) );
			
			// Alter related product args
			add_filter( 'woocommerce_output_related_products_args', array( 'WPEX_WooCommerce_Config', 'related_product_args' ) );

			// Tweak Woo pagination args
			add_filter( 'woocommerce_pagination_args', array( 'WPEX_WooCommerce_Config', 'pagination_args' ) );
			
			// Alter shop page redirect
			add_filter( 'woocommerce_continue_shopping_redirect', array( 'WPEX_WooCommerce_Config', 'continue_shopping_redirect' ) );

			// Alter post class classes
			add_filter( 'post_class', array( 'WPEX_WooCommerce_Config', 'add_product_entry_classes' ), 40, 3 );
			
			// Alter product category entry classes
			add_filter( 'product_cat_class', array( 'WPEX_WooCommerce_Config', 'product_cat_class' ) );

			// Alter product tag cloud widget args
			add_filter( 'woocommerce_product_tag_cloud_widget_args', array( 'WPEX_WooCommerce_Config', 'tag_cloud_widget_args' ) );

			// Add new typography settings
			add_filter( 'wpex_typography_settings', array( 'WPEX_WooCommerce_Config', 'typography_settings' ) );

			// Remove demo store notice from wp_footer place top of site
			remove_action( 'wp_footer', 'woocommerce_demo_store' );
			add_action( 'wpex_hook_wrap_top', 'woocommerce_demo_store', 0 );

			// Alter shop icons
			add_filter( 'wpex_head_css', array( 'WPEX_WooCommerce_Config', 'custom_cart_icon_css' ) );

			// Add new overlay styles
			add_filter( 'wpex_overlay_styles_array', array( 'WPEX_WooCommerce_Config', 'woo_image_overlays' ) );

		} // End __construct

		/*-------------------------------------------------------------------------------*/
		/* -  Start Class Functions
		/*-------------------------------------------------------------------------------*/

		/**
		 * Adds Customizer settings for WooCommerce
		 *
		 * @since 4.0
		 */
		public static function customizer_settings( $panels ) {
			$panels['woocommerce'] = array(
				'title'    => __( 'WooCommerce', 'total' ),
				'settings' => WPEX_WOO_CONFIG_DIR . 'woo-customizer-settings.php'
			);
			return $panels;
		}

		/**
		 * Runs on Init.
		 * You can't remove certain actions in the constructor because it's too early.
		 *
		 * @since 2.0.0
		 */
		public static function init() {

			// Remove category descriptions, these are added already by the theme
			remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );

			// Alter WooCommerce category thumbnail
			remove_action( 'woocommerce_before_subcategory_title', 'woocommerce_subcategory_thumbnail', 10 );
			add_action( 'woocommerce_before_subcategory_title', array( 'WPEX_WooCommerce_Config', 'subcategory_thumbnail' ), 10 );

			// Remove loop product thumbnail function and add our own that pulls from template parts
			remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
			add_action( 'woocommerce_before_shop_loop_item_title', array( 'WPEX_WooCommerce_Config', 'loop_product_thumbnail' ), 10 );

			// Remove coupon from checkout
			//remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );

			// Remove single meta
			if ( ! wpex_get_mod( 'woo_product_meta', true ) ) {
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
			}

			// Alter upsells
			remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
			if ( '0' != wpex_get_mod( 'woocommerce_upsells_count', '4' ) ) {
				add_action( 'woocommerce_after_single_product_summary', array( 'WPEX_WooCommerce_Config', 'upsell_display' ), 15 );
			}

			// Remove related products if count is set to 0
			if ( '0' == wpex_get_mod( 'woocommerce_related_count', '4' ) ) {
				remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
			}

			// Alter crossells
			remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
			if ( '0' != wpex_get_mod( 'woocommerce_cross_sells_count', '4' ) ) {
				add_action( 'woocommerce_cart_collaterals', array( 'WPEX_WooCommerce_Config', 'cross_sell_display' ) );
			}

			// Remove result count if disabled
			if ( ! wpex_get_mod( 'woo_shop_result_count', true ) ) {
				remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
			}

			// Remove orderby if disabled
			if ( ! wpex_get_mod( 'woo_shop_sort', true ) ) {
				remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
			}

		}

		/**
		 * Register new WooCommerce sidebar.
		 *
		 * @since 2.0.0
		 */
		public static function register_woo_sidebar() {

			// Return if custom sidebar disabled
			if ( ! wpex_get_mod( 'woo_custom_sidebar', true ) ) {
				return;
			}

			// Get correct sidebar heading tag
			$heading_tag = wpex_get_mod( 'sidebar_headings', 'div' );
			$heading_tag = $heading_tag ? $heading_tag : 'div';

			// Register new woo_sidebar widget area
			register_sidebar( array(
				'name'          => __( 'WooCommerce Sidebar', 'total' ),
				'id'            => 'woo_sidebar',
				'before_widget' => '<div id="%1$s" class="sidebar-box widget %2$s clr">',
				'after_widget'  => '</div>',
				'before_title'  => '<' . $heading_tag . ' class="widget-title">',
				'after_title'   => '</' . $heading_tag . '>',
			) );

		}

		/**
		 * Set correct page id for main shop page
		 *
		 * @since 3.6.0
		 */
		public static function shop_id( $id ) {

			// Set correct ID for shop page
			// We only have to check if ID is currently empty
			if ( ! $id
				&& is_shop()
				&& $shop_id = wpex_parse_obj_id( wc_get_page_id( 'shop' ) )
			) {
				$id = $shop_id;
			}

			// Return page id
			return $id;

		}

		/**
		 * Display WooCommerce sidebar.
		 *
		 * @since 2.0.0
		 */
		public static function display_woo_sidebar( $sidebar ) {

			// Alter sidebar display to show woo_sidebar where needed
			if ( wpex_get_mod( 'woo_custom_sidebar', true ) && is_woocommerce() && is_active_sidebar( 'woo_sidebar' ) ) {
				$sidebar = 'woo_sidebar';
			}

			// Return correct sidebar
			return $sidebar;

		}

		/**
		 * Returns correct title for WooCommerce pages.
		 *
		 * @since 2.0.0
		 */
		public static function title_config( $title ) {

			// Shop title
			if ( is_shop() ) {
				$shop_id = wpex_parse_obj_id( wc_get_page_id( 'shop' ), 'page' );
				$title   = $shop_id ? get_the_title( $shop_id ) : '';
				$title   = $title ? $title : $title = __( 'Shop', 'total' );
			}

			// Product title
			elseif ( is_product() ) {
				$title = wpex_get_translated_theme_mod( 'woo_shop_single_title' );
				$title = $title ? $title : __( 'Shop', 'total' );
			}

			// Checkout
			elseif ( is_order_received_page() ) {
				$title = __( 'Order Received', 'total' );
			}

			// Return title
			return $title;

		}

		/**
		 * Hooks into the wpex_display_page_header and returns false if page header is disabled via the customizer.
		 *
		 * @since 2.0.0
		 */
		public static function display_page_header( $return ) {
			if ( is_shop() && ! wpex_get_mod( 'woo_shop_title', true ) ) {
				$return = false;
			}
			return $return;
		}

		/**
		 * Tweaks the post layouts for WooCommerce archives and single product posts.
		 *
		 * @since 2.0.0
		 */
		public static function layouts( $class ) {
			if ( wpex_is_woo_shop() ) {
				$class = wpex_get_mod( 'woo_shop_layout', 'full-width' );
			} elseif ( wpex_is_woo_tax() ) {
				$class = wpex_get_mod( 'woo_shop_layout', 'full-width' );
			} elseif ( wpex_is_woo_single() ) {
				$class = wpex_get_mod( 'woo_product_layout', 'full-width' );
			} elseif ( function_exists( 'is_account_page' ) && is_account_page() ) {
				$class = 'full-width';

			}
			return $class;
		}

		/**
		 * Remove WooCommerce styles not needed for this theme.
		 *
		 * @since 2.0.0
		 * @link  http://docs.woothemes.com/document/disable-the-default-stylesheet/
		 */
		public static function remove_styles( $enqueue_styles ) {
			if ( is_array( $enqueue_styles ) ) {
				unset( $enqueue_styles['woocommerce-layout'] );
				unset( $enqueue_styles['woocommerce_prettyPhoto_css'] );
				if ( isset( $enqueue_styles['woocommerce-smallscreen'] ) && ! is_account_page() ) {
					unset( $enqueue_styles['woocommerce-smallscreen'] ); // Enable small screen tables on Account page only
				}
			}
			return $enqueue_styles;
		}

		/**
		 * Add Custom scripts
		 *
		 * @since 2.0.0
		 */
		public static function add_custom_scripts() {

			// General WooCommerce Custom CSS
			wp_enqueue_style(
				'wpex-woocommerce',
				wpex_asset_url( 'css/wpex-woocommerce.css' ),
				array(),
				WPEX_THEME_VERSION
			);

			// WooCommerce Responsiveness
			if ( wpex_is_layout_responsive() ) {
				wp_enqueue_style(
					'wpex-woocommerce-responsive',
					wpex_asset_url( 'css/wpex-woocommerce-responsive.css' ),
					array( 'wpex-woocommerce' ),
					WPEX_THEME_VERSION,
					'only screen and (max-width: 768px)'
				);
			}

			// Increment JS
			if ( is_singular( 'product' ) || is_cart() ) {
				wp_enqueue_script(
					'wpex-wc-quantity-increment',
					wpex_asset_url( 'js/dynamic/wc-quantity-increment-min.js' ),
					array( 'jquery' ),
					WPEX_THEME_VERSION,
					true
				);
			}

		}

		/**
		 * Change onsale text.
		 *
		 * @since 2.0.0
		 */
		public static function woocommerce_sale_flash( $text, $post, $_product ) {
			return '<span class="onsale">'. esc_html__( 'Sale', 'total' ) . '</span>';
		}

		/**
		 * Returns correct posts per page for the shop
		 *
		 * @since 3.0.0
		 */
		public static function loop_shop_per_page() {
			$posts_per_page = wpex_get_mod( 'woo_shop_posts_per_page' );
			$posts_per_page = $posts_per_page ? $posts_per_page : '12';
			return $posts_per_page;
		}

		/**
		 * Change products per row for the main shop.
		 *
		 * @since 2.0.0
		 */
		public static function loop_shop_columns() {
			$columns = wpex_get_mod( 'woocommerce_shop_columns' );
			$columns = $columns ? $columns : '4';
			return $columns;
		}

		/**
		 * Change products per row for upsells.
		 *
		 * @since 2.0.0
		 */
		public static function upsell_display() {

			// Get count
			$count = wpex_get_mod( 'woocommerce_upsells_count' );
			$count = $count ? $count : '4';

			// Get columns
			$columns = wpex_get_mod( 'woocommerce_upsells_columns' );
			$columns = $columns ? $columns : '4';

			// Alter upsell display
			woocommerce_upsell_display( $count, $columns );

		}

		/**
		 * Change products per row for crossells.
		 *
		 * @since 2.0.0
		 */
		public static function cross_sell_display() {

			// Get count
			$count = wpex_get_mod( 'woocommerce_cross_sells_count' );
			$count = $count ? $count : '2';

			// Get columns
			$columns = wpex_get_mod( 'woocommerce_cross_sells_columns' );
			$columns = $columns ? $columns : '2';

			// Alter cross-sell display
			woocommerce_cross_sell_display( $count, $columns );

		}

		/**
		 * Change category thumbnail.
		 *
		 * @since 2.0.0
		 */
		public static function subcategory_thumbnail( $category ) {

			// Get attachment id
			$attachment      = get_woocommerce_term_meta( $category->term_id, 'thumbnail_id', true  );
			$attachment_data = wpex_get_attachment_data( $attachment );

			// Get alt
			if ( ! empty( $attachment_data['alt'] ) ) {
				$alt = $attachment_data['alt'];
			} else {
				$alt = $category->name;
			}

			// Return thumbnail if attachment is defined
			if ( $attachment ) {

				wpex_post_thumbnail( array(
					'attachment' => $attachment,
					'size'       => 'shop_category',
					'alt'        => esc_attr( $alt ),
				) );

			}

			// Display placeholder
			else {

				echo '<img src="'. wc_placeholder_img_src() . '" alt="'. esc_html__( 'Placeholder Image', 'total' ) . '" />';

			}

		}

		/**
		 * Alter the related product arguments.
		 *
		 * @since 2.0.0
		 */
		public static function related_product_args() {

			// Get global vars
			global $product, $orderby, $related;

			// Get posts per page
			$posts_per_page = wpex_get_mod( 'woocommerce_related_count' );
			$posts_per_page = $posts_per_page ? $posts_per_page : '4';

			// Get columns
			$columns = wpex_get_mod( 'woocommerce_related_columns' );
			$columns = $columns ? $columns : '4';

			// Return array
			return array(
				'posts_per_page' => $posts_per_page,
				'columns'        => $columns,
			);

		}

		/**
		 * Adds an opening div "product-inner" around product entries.
		 *
		 * @since 2.0.0
		 */
		public static function add_shop_loop_item_inner_div() {
			echo '<div class="product-inner clr">';
		}

		/**
		 * Closes the "product-inner" div around product entries.
		 *
		 * @since 2.0.0
		 */
		public static function close_shop_loop_item_inner_div() {
			echo '</div><!-- .product-inner .clr -->';
		}

		/**
		 * Clear floats after single product summary.
		 *
		 * @since 2.0.0
		 */
		public static function clear_summary_floats() {
			echo '<div class="wpex-clear-after-summary wpex-clear"></div>';
		}

		/**
		 * Adds an out of stock tag to the products.
		 *
		 * @since 2.0.0
		 */
		public static function add_shop_loop_item_out_of_stock_badge() {
			if ( ! wpex_woo_product_instock() ) { ?>
				<div class="outofstock-badge">
					<?php echo apply_filters( 'wpex_woo_outofstock_text', esc_html__( 'Out of Stock', 'total' ) ); ?>
				</div><!-- .product-entry-out-of-stock-badge -->
			<?php }
		}

		/**
		 * Returns our product thumbnail from our template parts based on selected style in theme mods.
		 *
		 * @since 2.0.0
		 */
		public static function loop_product_thumbnail() {
			if ( function_exists( 'wc_get_template' ) ) {
				// Get entry product media style
				$style = wpex_get_mod( 'woo_product_entry_style' );
				$style = $style ? $style : 'image-swap';
				// Get entry product media template part
				wc_get_template( 'loop/thumbnail/' . $style . '.php' );
			}
		}

		/**
		 * Tweaks pagination arguments.
		 *
		 * @since 2.0.0
		 */
		public static function pagination_args( $args ) {
			$args['prev_text'] = '<i class="fa fa-angle-left"></i>';
			$args['next_text'] = '<i class="fa fa-angle-right"></i>';
			return $args;
		}

		/**
		 * Alter continue shoping URL.
		 *
		 * @since 2.0.0
		 */
		public static function continue_shopping_redirect( $return_to ) {
			if ( $shop_id  = wc_get_page_id( 'shop' ) ) {
				$shop_id   = wpex_parse_obj_id( $shop_id, 'page' );
				$return_to = get_permalink( $shop_id );
			}
			return $return_to;
		}

		/**
		 * Hooks into the wpex_has_post_slider function and returns true for the shop if
		 * a slider is defined via the customizer.
		 *
		 * @since 2.0.0
		 */
		public static function display_shop_slider( $return ) {
			if ( is_shop() && wpex_get_mod( 'woo_shop_slider' ) ) {
				$return = true;
			}
			return $return;
		}

		/**
		 * The shop post slider
		 *
		 * @since 2.0.0
		 */
		public static function shop_slider_shortcode( $slider ) {
			if ( is_shop() && ! $slider ) {
				$slider = wpex_get_mod( 'woo_shop_slider' );
			}
			return $slider;
		}

		/**
		 * Alters subheading for the shop.
		 *
		 * @since 2.0.0
		 */
		public static function alter_subheadings( $subheading ) {

			// Woo Taxonomies
			if ( wpex_is_woo_tax() ) {
				if ( 'under_title' == wpex_get_mod( 'woo_category_description_position', 'under_title' ) ) {
					$subheading = term_description();
				} else {
					$subheading = NULL;
				}
			}

			// Orderby, search...etc
			if ( is_shop() ) {
				if ( ! empty( $_GET['s'] ) ) {
					$subheading = __( 'Search results for:', 'total' ) . ' <span>&quot;' . $_GET['s'] . '&quot;</span>';
				}
			}

			// Return subheading
			return $subheading;

		}

		/**
		 * Alters subheading for the shop.
		 *
		 * @since 2.0.0
		 */
		public static function term_description_above_loop( $return ) {

			// Check if enabled
			if ( wpex_is_woo_tax() && 'above_loop' == wpex_get_mod( 'woo_category_description_position' ) ) {
				$return = true;
			}

			// Return bool
			return $return;

		}

		/**
		 * Enable post social share if enabled.
		 *
		 * @since 2.0.0
		 */
		public static function post_social_share( $return ) {
			if ( is_singular( 'product' ) ) {
				if ( wpex_get_mod( 'social_share_woo', false )  ) {
					$return = true;
				} else {
					$return = false;
				}
			}
			return $return;
		}

		/**
		 * Add classes to WooCommerce product entries.
		 *
		 * @since 2.0.0
		 */
		public static function add_product_entry_classes( $classes, $class = '', $post_id = '' ) {
			global $product, $woocommerce_loop;
			if ( $product
				&& ! empty( $woocommerce_loop['columns'] )
				&& is_array( $classes )
				&& in_array( get_post_type( $post_id ), array( 'product', 'product_variation' ) )
				&& in_array( 'wpex-woo-entry', $classes )
			) {
				$classes[] = 'col';
				$columns = isset( $woocommerce_loop['columns'] ) ? $woocommerce_loop['columns'] : 4;
				$classes[] = wpex_grid_class( $columns );
				$key = array_search( 'wpex-woo-entry', $classes );
				unset( $classes[$key] );
			}
			return $classes;
		}

		/**
		 * Disables the next/previous links if disabled via the customizer.
		 *
		 * @since 2.0.0
		 */
		public static function next_prev( $return ) {
			if ( is_woocommerce() && is_singular( 'product' ) && ! wpex_get_mod( 'woo_next_prev', true ) ) {
				$return = false;
			}
			return $return;
		}

		/**
		 * Disable term page header image by default on Woo cats
		 *
		 * @since 3.6.0
		 */
		public static function term_page_header_image_enabled( $bool ) {
			if ( is_tax( 'product_cat' ) ) {
				$bool = false;
			}
			return $bool;
		}

		/**
		 * Alter WooCommerce category classes
		 *
		 * @since 3.0.0
		 */
		public static function product_cat_class( $classes ) {
			global $woocommerce_loop;
			$classes[] = 'col';
			$classes[] = wpex_grid_class( $woocommerce_loop['columns'] );
			return $classes;
		}

		/**
		 * Alter product tag cloud widget args
		 *
		 * @since 4.2
		 */
		public static function tag_cloud_widget_args( $args ) {
			$args['largest']  = '0.923';
			$args['smallest'] = '0.923';
			$args['unit']     = 'em';
			return $args;
		}

		/**
		 * Add typography options for the WooCommerce product title
		 *
		 * @since 3.0.0
		 */
		public static function typography_settings( $settings ) {
			$settings['woo_entry_title'] = array(
				'label' => __( 'WooCommerce Entry Title', 'total' ),
				'target' => '.woocommerce ul.products li.product .woocommerce-loop-product__title,.woocommerce ul.products li.product .woocommerce-loop-category__title',
				'margin' => true,
			);
			$settings['woo_product_title'] = array(
				'label' => __( 'WooCommerce Product Title', 'total' ),
				'target' => '.woocommerce div.product .product_title',
				'margin' => true,
			);
			$settings['woo_post_tabs_title'] = array(
				'label' => __( 'WooCommerce Tabs Title', 'total' ),
				'target' => '.woocommerce-tabs h2',
				'margin' => true,
			);
			$settings['woo_upsells_related_title'] = array(
				'label' => __( 'WooCommerce Up-Sells & Related Title', 'total' ),
				'target' => '.woocommerce .upsells.products h2, .woocommerce .related.products h2',
				'margin' => true,
			);
			return $settings;
		}

		/**
		 * Add shop ID to list of VC id's for custom field CSS.
		 *
		 * @since 2.0.0
		 */
		public static function shop_vc_css( $ids ) {
			if ( is_shop() && $shop_id = wpex_parse_obj_id( wc_get_page_id( 'shop' ) ) ) {
				$ids[] = $shop_id;
			}
			return $ids;
		}

		/**
		 * Add custom VC modules
		 *
		 * @since 3.5.3
		 */
		public static function vc_modules( $modules ) {
			$modules[] = 'woocommerce_carousel';
			return $modules;
		}

		/**
		 * Open details wrapper
		 *
		 * @since 3.6.0
		 */
		public static function loop_details_open() {
			echo '<div class="product-details match-height-content">';
		}

		/**
		 * Close details wrapper
		 *
		 * @since 3.6.0
		 */
		public static function loop_details_close() {
			echo '</div><!-- .product-details -->';
		}

		/**
		 * Alter cart icons
		 *
		 * @since 4.0
		 */
		public static function custom_cart_icon_css( $css ) {
			$icon_class   = esc_html( wpex_get_mod( 'woo_menu_icon_class' ) );
			$icon_content = $icon_class ? $icon_class : 'shopping-cart';
			if ( $icon_class && 'shopping-cart' != $icon_class ) {
				if ( 'shopping-basket' == $icon_class ) {
					$icon_content = '\f291';
				} elseif ( 'shopping-bag' == $icon_class ) {
					$icon_content = '\f290';
				}
				if ( $icon_content ) {
					$css .= '.woocommerce ul.products li.product .added_to_cart:after,
						.woocommerce .widget_shopping_cart_content .buttons .wc-forward:not(checkout):after, .woocommerce .woocommerce-error a.button.wc-forward:before, .woocommerce .woocommerce-info a.button.wc-forward:before, .woocommerce .woocommerce-message a.button.wc-forward:before { content: "' . $icon_content . '"; }';
				}
			}
			return $css;
		}

		/**
		 * Custom WooCommerce Image Overlays
		 *
		 * @since 4.0
		 */
		public static function woo_image_overlays( $overlays ) {
			$overlays['title-price-hover'] = __( 'Title + Price Hover', 'total' );
			return $overlays;
		}

	}

}

new WPEX_WooCommerce_Config();