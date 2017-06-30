<?php
/**
 * Used for your site wide breadcrumbs
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start Class
if ( ! class_exists( 'WPEX_Breadcrumbs' ) ) {

	class WPEX_Breadcrumbs {
		public $output = '';

		/**
		 * Main constructor
		 *
		 * @since 3.0.9
		 */
		public function __construct() {
			$this->generate_crumbs();
		}

		/**
		 * Outputs the generated breadcrumbs
		 *
		 * @since      3.0.9
		 * @deprecated 3.6.0 // Will be removed at some point since it's not needed
		 */
		public function display( $echo = true ) {
			if ( $echo ) {
				echo $this->output; // @note Already sanitized
			} else {
				return $this->output;
			}
		}

		/* Generates the breadcrumbs and updates the $trail var
		 *
		 * @since 3.0.9
		 */
		private function generate_crumbs() {

			// Globals
			global $wp_query, $wp_rewrite;

			// Define main variables
			$breadcrumb = $path = '';
			$trail = array();

			// Home text
			$home_text = wpex_get_translated_theme_mod( 'breadcrumbs_home_title' );
			$home_text = $home_text ? $home_text : '<span class="fa fa-home"></span><span class="display-none">'. esc_html__( 'Home', 'total' ) .'</span>';

			// Default arguments
			$args = apply_filters( 'wpex_breadcrumbs_args', array(
				'home_text'        => $home_text,
				'home_link'        => home_url( '/' ),
				'separator'        => wpex_element( 'angle_right' ),
				'front_page'       => false,
				'show_posts_page'  => true,
				'show_parents'     => true,
				'cpt_find_parents' => false,
			) );

			// Extract args for easy variable naming.
			extract( $args );

			/*-----------------------------------------------------------------------------------*/
			/*  - Homepage link
			/*-----------------------------------------------------------------------------------*/
			$scope = self::get_scope();
			$home_text = do_shortcode( wp_kses_post( $home_text ) );
			$trail['trail_start'] = '<span '. $scope .' class="trail-begin"><a href="'. esc_url( $home_link ) .'" title="'. esc_attr( get_bloginfo( 'name' ) ) .'" rel="home" itemprop="url"><span itemprop="title">'. $home_text .'</span></a></span>';

			/*-----------------------------------------------------------------------------------*/
			/*  - Front Page
			/*-----------------------------------------------------------------------------------*/
			if ( is_front_page() && ! $front_page ) {
				$trail = false;
			}

			/*-----------------------------------------------------------------------------------*/
			/*  - Homepage or posts page
			/*-----------------------------------------------------------------------------------*/
			elseif ( is_home() ) {
				$home_page = get_page( $wp_query->get_queried_object_id() );
				if ( is_object( $home_page ) ) {
					$trail = array_merge( $trail, self::get_post_parents( $home_page->post_parent, '' ) );
					$trail['trail_end'] = get_the_title( $home_page->ID );
				} else {
					$trail = false;
				}
			}

			/*-----------------------------------------------------------------------------------*/
			/*  - Singular: Page, Post, Attachment...etc
			/*-----------------------------------------------------------------------------------*/
			elseif ( is_singular() ) {
				
				// Get singular vars
				$post      = $wp_query->get_queried_object();
				$post_id   = absint( $wp_query->get_queried_object_id() );
				$post_type = $post->post_type;
				$parent    = $show_parents ? $post->post_parent : '';
				$trail['post_type_archive'] = ''; // Add empty post type trail for custom types

				// Pages
				if ( 'page' == $post_type ) {


					// Woo pages
					if ( WPEX_WOOCOMMERCE_ACTIVE ) {

						// Add shop page to cart
						if ( is_cart() || is_checkout() ) {

							// Get shop data
							$shop_data  = self::get_shop_data();
							$shop_url   = $shop_data['url'];
							$shop_title = $shop_data['title'];

							// Add shop link
							if ( $shop_url && $shop_title ) {
								$trail['shop'] = self::get_crumb_html( $shop_title, $shop_url, 'trail-shop' );
							}

						}

						// Add cart to checkout
						if ( is_checkout() && $cart_id = wpex_parse_obj_id( wc_get_page_id( 'cart' ), 'page' ) ) {
							$trail['cart'] = self::get_crumb_html( get_the_title( $cart_id ), get_permalink( $cart_id ), 'trail-cart' );
						}

					}

					// Add page parents
					if ( empty( $path ) && $parent ) {
						$trail = array_merge( $trail, self::get_post_parents( $parent ) );
					}

				}

				// Standard (blog) Posts
				elseif ( 'post' == $post_type ) {

					// Main Blog URL
					if ( $page_id = wpex_parse_obj_id( wpex_get_mod( 'blog_page' ), 'page' ) ) {
						if ( $show_parents && $parents = self::get_post_parents( $page_id ) ) {
							$trail = array_merge( $trail, $parents );
						} else {
							$trail['blog'] = self::get_crumb_html( get_the_title( $page_id ), get_permalink( $page_id ), 'trail-blog-url' );
						}
					}

					// Categories
					if ( $terms = self::get_post_terms( 'category' ) ) {
						$trail['categories'] = '<span class="trail-post-categories">'. $terms .'</span>';
					}

				}

				// Tribe Events Posts
				elseif ( 'tribe_events' == $post_type ) {

					if ( function_exists( 'tribe_get_events_link' ) ) {
						if ( $page_id = wpex_parse_obj_id( wpex_get_mod( 'tribe_events_main_page' ), 'page' ) ) {
							$title = get_the_title( $page_id );
							$link  = get_permalink( $page_id );
						} else {
							$title = __( 'All Events', 'total' );
							$link  = tribe_get_events_link();
						}
						$trail['tribe_events'] = self::get_crumb_html( $title, $link, 'trail-all-events' );
					}

				}
				
				// Portfolio Posts
				elseif ( 'portfolio' == $post_type ) {

					// Portfolio main page
					if ( $page_id = wpex_parse_obj_id( wpex_get_mod( 'portfolio_page' ), 'page' ) ) {
						if ( $show_parents && $parents = self::get_post_parents( $page_id ) ) {
							$trail = array_merge( $trail, $parents );
						} else {
							$trail['portfolio'] = self::get_crumb_html( get_the_title( $page_id ), get_permalink( $page_id ), 'trail-portfolio-url' );
						}
					}

					// Portfolio Categories
					if ( $terms = self::get_post_terms( 'portfolio_category' ) ) {
						$trail['categories'] = '<span class="trail-post-categories">'. $terms .'</span>';
					}

				}
				
				// Staff Post Type
				elseif ( 'staff' == $post_type ) {

					// Staff main page
					if ( $page_id = wpex_parse_obj_id( wpex_get_mod( 'staff_page' ), 'page' ) ) {
						if ( $show_parents && $parents = self::get_post_parents( $page_id ) ) {
							$trail = array_merge( $trail, $parents );
						} else {
							$trail['staff'] = self::get_crumb_html( get_the_title( $page_id ), get_permalink( $page_id ), 'trail-staff-url' );
						}
					}

					// Staff Categories
					if ( $terms = self::get_post_terms( 'staff_category' ) ) {
						$trail['categories'] = '<span class="trail-post-categories">'. $terms .'</span>';
					}

				}
				
				// Testimonials Post Type
				elseif ( 'testimonials' == $post_type ) {

					// Testimonials main page
					if ( $page_id = wpex_parse_obj_id( wpex_get_mod( 'testimonials_page' ), 'page' ) ) {
						if ( $show_parents && $parents = self::get_post_parents( $page_id ) ) {
							$trail = array_merge( $trail, $parents );
						} else {
							$trail['testimonials'] = self::get_crumb_html( get_the_title( $page_id ), get_permalink( $page_id ), 'trail-testimonials-url' );
						}
					}

					// Testimonials Categories
					if ( $terms = self::get_post_terms( 'testimonials_category' ) ) {
						$trail['categories'] = '<span class="trail-post-categories">'. $terms .'</span>';
					}

				}

				// Products
				elseif ( 'product' == $post_type ) {

					// Get shop data
					$shop_data  = self::get_shop_data();
					$shop_url   = $shop_data['url'];
					$shop_title = $shop_data['title'];

					// Add shop page to product post
					if ( $shop_url && $shop_title ) {
						$trail['shop'] = self::get_crumb_html( $shop_title, $shop_url, 'trail-shop' );
					}

					// Add categories to product post
					if ( $terms = self::get_post_terms( 'product_cat' ) ) {
						$trail['categories'] = '<span class="trail-post-categories">'. $terms .'</span>';
					}

					// Add cart to product post
					if ( apply_filters( 'wpex_breadcrumbs_single_product_cart', false )
						&& $page_id = wpex_parse_obj_id( wc_get_page_id( 'cart' ) )
					) {
						$trail['cart'] = self::get_crumb_html( get_the_title( $page_id ), get_permalink( $page_id ), 'trail-cart' );
					}

				}

				// Other custom post types
				else {

					$post_type_object = get_post_type_object( $post_type );
					
					// Add $front to the path
					if ( 'post' == $post_type || ( $post_type_object->rewrite['with_front'] && $wp_rewrite->front ) ) {
						$path .= trailingslashit( $wp_rewrite->front );
					}

					// Add slug to $path
					if ( ! empty( $post_type_object->rewrite['slug'] ) ) {
						$path .= $post_type_object->rewrite['slug'];
					}

					// If archive page exists add to trail
					if ( ! empty( $post_type_object->has_archive ) && ! is_singular( 'product' ) ) {

						$trail['post_type_archive'] = self::get_crumb_html( $post_type_object->labels->name, get_post_type_archive_link( $post_type ), 'trail-type-archive' );

					}

					// No archive, lets try and look for parents?
					elseif ( $cpt_find_parents && ! empty( $path ) ) {

						$trail = array_merge( $trail, self::get_post_parents( '', $path ) );

					}

					// Do not display home link if it's also the parent link
					if ( $parent == get_option( 'page_on_front' ) ) {
						$parent = '';
					}

					// If the post type path is empty (and not attachment) display parents
					if ( empty( $path ) && $parent ) {
						$trail = array_merge( $trail, self::get_post_parents( $parent ) );
					}

					// Add empty category to array for addition of taxonomies via filters
					$trail['categories'] = '';

				}

				// End trail with post title
				if ( $post_title = get_the_title( $post_id ) ) {
					if ( $trim_title = wpex_get_mod( 'breadcrumbs_title_trim' ) ) {
						$post_title = wp_trim_words( $post_title, $trim_title );
					}
					$trail['trail_end'] = $post_title;
				}

			}

			/*-----------------------------------------------------------------------------------*/
			/*  - Archives
			/*-----------------------------------------------------------------------------------*/
			elseif ( is_archive() ) {

				/*-----------------------------------------------------------------------------------*/
				/*  - Post Type Archive
				/*-----------------------------------------------------------------------------------*/
				if ( is_post_type_archive() ) {

					// Shop Archive
					if ( function_exists( 'is_shop' ) && is_shop() ) {
						global $woocommerce;
						if ( sizeof( $woocommerce->cart->cart_contents ) > 0 ) {
							if ( $page_id = wpex_parse_obj_id( wc_get_page_id( 'cart' ), 'page' ) ) {
								$trail['cart'] = self::get_crumb_html( get_the_title( $page_id ), get_permalink( $page_id ), 'trail-cart' );
							}
						}

						// Store shop data
						$shop_data = self::get_shop_data();

						// Add shop page title to trail end
						if ( $shop_data['title'] ) {
							$trail['trail_end'] = $shop_data['title'];
						} else {
							$trail['trail_end'] = post_type_archive_title( '', false );
						}
						
					}
				
					// Topics Post Type Archive
					elseif ( is_post_type_archive( 'topic' ) ) {

						if ( is_object( $forum_obj = get_post_type_object( 'forum' ) ) ) {

							if ( $forums_link = get_post_type_archive_link( 'forum' ) ) {
								$trail['forum'] = self::get_crumb_html( $forum_obj->labels->name, $forums_link, 'trail-forum' );
							}

							$trail['trail_end'] = $forum_obj->labels->name;

						}

					// All other post type archives
					} else {

						// Get post type object
						$post_type_object = get_post_type_object( get_query_var( 'post_type' ) );

						/*

						// Add $front to $path
						if ( $post_type_object->rewrite['with_front'] && $wp_rewrite->front ) {
							$path .= trailingslashit( $wp_rewrite->front );
						}

						// Add slug to $path
						if ( ! empty( $post_type_object->rewrite['archive'] ) ) {
							$path .= $post_type_object->rewrite['archive'];
						}

						// If patch exists check for parents
						if ( ! empty( $path ) ) {
							$trail = array_merge( $trail, self::get_post_parents( '', $path ) );
						}

						*/

						// Add post type name to trail end
						$trail['trail_end'] = $post_type_object->labels->name;

					}
					
				}

				/*-----------------------------------------------------------------------------------*/
				/*  - Taxonomy Archive
				/*-----------------------------------------------------------------------------------*/
				elseif ( ! is_search() && ( is_tax() || is_category() || is_tag() ) ) {

					// Get some taxonomy variables
					$term     = $wp_query->get_queried_object();
					$taxonomy = get_taxonomy( $term->taxonomy );
					
					// Link to main portfolio page
					if ( wpex_is_portfolio_tax() ) {
						if ( $page_id = wpex_parse_obj_id( wpex_get_mod( 'portfolio_page' ), 'page' ) ) {
							if ( $show_parents && $parents = self::get_post_parents( $page_id ) ) {
								$trail = array_merge( $trail, $parents );
							} else {
								$trail['portfolio'] = self::get_crumb_html( get_the_title( $page_id ), get_permalink( $page_id ), 'trail-portfolio-url' );
							}
						}
					}
					
					// Link to main staff page
					elseif ( wpex_is_staff_tax() ) {
						if ( $page_id = wpex_parse_obj_id( wpex_get_mod( 'staff_page' ), 'page' ) ) {
							if ( $show_parents && $parents = self::get_post_parents( $page_id ) ) {
								$trail = array_merge( $trail, $parents );
							} else {
								$trail['staff'] = self::get_crumb_html( get_the_title( $page_id ), get_permalink( $page_id ), 'trail-staff-url' );
							}
						}
					}
					
					// Testimonials Tax
					elseif ( wpex_is_testimonials_tax() ) {
						if ( $page_id = wpex_parse_obj_id( wpex_get_mod( 'testimonials_page' ), 'page' ) ) {
							if ( $show_parents && $parents = self::get_post_parents( $page_id ) ) {
								$trail = array_merge( $trail, $parents );
							} else {
								$trail['testimonials'] = self::get_crumb_html( get_the_title( $page_id ), get_permalink( $page_id ), 'trail-testimonials-url' );
							}
						}
					}

					// Display main blog page on Categories & archives
					elseif ( is_category() || is_tag() ) {
						if ( $page_id = wpex_parse_obj_id( wpex_get_mod( 'blog_page' ), 'page' ) ) {
							if ( $show_parents && $parents = self::get_post_parents( $page_id ) ) {
								$trail = array_merge( $trail, $parents );
							} else {
								$trail['blog'] = self::get_crumb_html( get_the_title( $page_id ), get_permalink( $page_id ), 'trail-blog-url' );
							}
						}
					}

					// Woo Product Tax
					elseif ( wpex_is_woo_tax() ) {

						// Get shop data
						$shop_data  = self::get_shop_data();
						$shop_url   = $shop_data['url'];
						$shop_title = $shop_data['title'];

						// Add shop page to product post
						if ( $shop_url && $shop_title ) {
							$trail['shop'] = self::get_crumb_html( $shop_title, $shop_url, 'trail-shop' );
						}

					}

					// Get the path to the term archive. Use this to determine if a page is present with it.
					if ( is_category() ) {
						$path = get_option( 'category_base' );
					} elseif ( is_tag() ) {
						$path = get_option( 'tag_base' );
					} else {
						if ( $taxonomy->rewrite['with_front'] && $wp_rewrite->front ) {
							$path = trailingslashit( $wp_rewrite->front );
						}
						$path .= $taxonomy->rewrite['slug'];
					}

					// Get parent pages if they exist
					if ( $path ) {
						$trail = array_merge( $trail, self::get_post_parents( '', $path ) );
					}

					// Add term parents
					if ( is_taxonomy_hierarchical( $term->taxonomy ) && $term->parent ) {
						$trail = array_merge( $trail, $this->get_term_parents( $term ) );
					}

					// Add term name to trail end
					$trail['trail_end'] = $term->name;

				}

				/*-----------------------------------------------------------------------------------*/
				/*  - Author Archive
				/*-----------------------------------------------------------------------------------*/
				elseif ( is_author() ) {

					// Add main blog
					if ( $page_id = wpex_parse_obj_id( wpex_get_mod( 'blog_page' ), 'page' ) ) {
						if ( $show_parents && $parents = self::get_post_parents( $page_id ) ) {
							$trail = array_merge( $trail, $parents );
						} else {
							$trail['blog'] = self::get_crumb_html( get_the_title( $page_id ), get_permalink( $page_id ), 'trail-blog-url' );
						}
					}

					// Add the author display name to end
					$trail['trail_end'] = get_the_author_meta( 'display_name', get_query_var( 'author' ) );

				}

				/*-----------------------------------------------------------------------------------*/
				/*  - Time Archive
				/*-----------------------------------------------------------------------------------*/
				elseif ( is_time() ) {

					// Display minute and hour
					if ( get_query_var( 'minute' ) && get_query_var( 'hour' ) ) {
						$trail['trail_end'] = get_the_time( 'g:i a' );
					}

					// Display minute only
					elseif ( get_query_var( 'minute' ) ) {
						$trail['trail_end'] = sprintf( esc_html__( 'Minute %1$s', 'total' ), get_the_time( 'i' ) );
					}

					// Display hour only
					elseif ( get_query_var( 'hour' ) ) {
						$trail['trail_end'] = get_the_time( 'g a' );
					}

				}

				/*-----------------------------------------------------------------------------------*/
				/*  - Date Archive
				/*-----------------------------------------------------------------------------------*/
				elseif ( is_date() ) {

					// If $front is set check for parents
					if ( $wp_rewrite->front ) {
						$trail = array_merge( $trail, self::get_post_parents( '', $wp_rewrite->front ) );
					}

					// Day archive
					if ( is_day() ) {

						// Link to year archive
						$title = date_i18n( 'Y', strtotime( get_the_time( 'Y' ) ) );
						$link  = get_year_link( get_the_time( 'Y' ) );
						$trail['year'] = self::get_crumb_html( $title, $link, 'trail-year' );

						// Link to month archive
						$title = date_i18n( 'F', strtotime( get_the_time( 'F' ) ) );
						$link  = get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) );
						$trail['month'] = self::get_crumb_html( $title, $link, 'trail-month' );

						// Add time to end
						$trail['trail_end'] = date_i18n( 'j', strtotime( get_the_time( 'j' ) ) );

					}

					// Week archive
					elseif ( get_query_var( 'w' ) ) {

						// Link to year archive
						$title = date_i18n( 'Y', strtotime( get_the_time( 'Y' ) ) );
						$link  = get_year_link( get_the_time( 'Y' ) );
						$trail['year'] = self::get_crumb_html( $title, $link, 'trail-year' );

						// Add week to end
						$trail['trail_end'] = sprintf( esc_html__( 'Week %1$s', 'total' ), get_the_time( 'W' ) );

					}

					// Month archive
					elseif ( is_month() ) {

						// Link to year archive
						$title = date_i18n( 'Y', strtotime( get_the_time( 'Y' ) ) );
						$link  = get_year_link( get_the_time( 'Y' ) );
						$trail['year'] = self::get_crumb_html( $title, $link, 'trail-year' );

						// Add month to end
						$trail['trail_end'] = date_i18n( 'F', strtotime( get_the_time( 'F' ) ) );

					}

					// Year archive
					elseif ( is_year() ) {
						$trail['trail_end'] = date_i18n( 'Y', strtotime( get_the_time( 'Y' ) ) );
					}

				}
			}

			/*-----------------------------------------------------------------------------------*/
			/*  - Search
			/*-----------------------------------------------------------------------------------*/
			elseif ( is_search() ) {
				$trail['trail_end'] = esc_html__( 'Search Results', 'total' );
			}

			/*-----------------------------------------------------------------------------------*/
			/*  - 404
			/*-----------------------------------------------------------------------------------*/
			elseif ( is_404() ) {
				$trail['trail_end'] = esc_html__( '404 Error Page', 'total' );;
			}

			/*-----------------------------------------------------------------------------------*/
			/*  - Tribe Calendar Month
			/*-----------------------------------------------------------------------------------*/
			elseif ( function_exists( 'tribe_is_month' ) && tribe_is_month() ) {
				$trail['trail_end'] = esc_html__( 'Events Calendar', 'total' );
			}

			/*-----------------------------------------------------------------------------------*/
			/*  - Create and return the breadcrumbs
			/*-----------------------------------------------------------------------------------*/

			// Add a before trail_end empty item for easier addition of items before the title
			if ( isset( $trail['trail_end'] ) ) {
				$trail_end = $trail['trail_end'];
				unset( $trail['trail_end'] );
				$trail['pre_trail_end'] = '';
				$trail['trail_end'] = $trail_end;
			}

			// Apply filters so developers can alter the trail
			$trail = apply_filters( 'wpex_breadcrumbs_trail', $trail );

			// Return trail
			if ( $trail && is_array( $trail ) ) {

				// Remove empty vals
				$trail = array_filter( $trail );

				// Add to trail
				if ( isset( $trail['trail_end'] ) ) {
					$trail['trail_end'] = '<span class="trail-end">'. esc_html( $trail['trail_end'] ) .'</span>';
				}

				// Get classes
				$classes = self::wrap_classes();

				// Open Breadcrumbs
				$breadcrumb = '<nav class="'. esc_attr( $classes ) .'"><span class="breadcrumb-trail">';

				// Join all trail items into a string
				$all_count = count( $trail );
				$count     = 0;
				foreach ( $trail as $key => $val ) {
					$count++;
					$breadcrumb .= $val;
					if ( $all_count != $count ) {
						$breadcrumb .= '<span class="sep sep-'. $count .'"> '. $separator .' </span>';
					}
				}

				// Close breadcrumbs
				$breadcrumb .= '</span></nav>';

			}

			// Update output var
			$this->output = $breadcrumb;

		} // End generate_crumbs

		/**
		 * Generate single crumb html
		 *
		 * @since 3.6.0
		 */
		public static function get_crumb_html( $label, $link, $class = '', $rel = '' ) {
			if ( ! $link ) {
				return; // Link required
			}
			$scope = self::get_scope();
			$class = $class ? ' class="'. esc_attr( $class ) .'"': '';
			$rel   = $rel ? ' rel="'. esc_attr( $rel ) .'"': '';
			return '<span '. $scope . $class . $rel .'><a href="'. esc_url( $link ) .'" title="'. esc_attr( $label ) .'" itemprop="url"><span itemprop="title">'. esc_html( $label ) .'</span></a></span>';
		}

		/**
		 * Returns correct scope for crumbs
		 *
		 * @since 3.6.0
		 */
		public static function get_scope() {
			return 'itemscope itemtype="http://data-vocabulary.org/Breadcrumb"';
		}

		/**
		 * Display terms
		 *
		 * @since 1.0.0
		 */
		public static function get_post_terms( $taxonomy = '' ) {

			// Make sure taxonomy exists
			if ( ! $taxonomy || ! taxonomy_exists( $taxonomy ) ) {
				return null;
			}

			// Terms empty by default
			$terms = apply_filters( 'wpex_breadcrumbs_terms', null, $taxonomy );

			// Return terms if filtered
			if ( $terms ) {
				return $terms;
			}

			// Get terms
			$list_terms = array();
			$args       = apply_filters( 'wpex_breadcrumbs_wp_get_post_terms_args', array() );
			$terms      = wp_get_post_terms( get_the_ID(), $taxonomy, $args );

			// Return if no terms are found
			if ( ! $terms ) {
				return;
			}

			// Itemscope
			$itemscope = self::get_scope();

			// Check if it should display all terms or only first term
			$all_terms = wpex_get_mod( 'breadcrumbs_first_cat_only' ) ? false : true;

			// Loop through terms
			if ( apply_filters( 'wpex_breadcrumbs_terms_all', $all_terms ) ) {

				foreach ( $terms as $term ) {

					// Translation fixes
					$translated_term = wpex_parse_obj_id( $term->term_id, 'term', $taxonomy );
					if ( $term->term_id != $translated_term ) {
						$term = get_term( $translated_term, $taxonomy );
					}

					$list_terms[] = '<span '. $itemscope .'><a href="'. esc_url( get_term_link( $term->term_id, $taxonomy ) ) .'" title="'. esc_attr( $term->name ) .'" class="term-'. $term->term_id .'" itemprop="url"><span itemprop="title">'. $term->name .'</span></a></span>';
				}

			}

			// Return first term only
			else {
				$list_terms[] = '<span '. $itemscope .'><a href="'. esc_url( get_term_link( $terms[0]->term_id, $taxonomy ) ) .'" title="'. esc_attr( $terms[0]->name ) .'" class="term-'. $terms[0]->term_id .'" itemprop="url"><span itemprop="title">'. $terms[0]->name .'</span></a></span>';
			}

			// Sanitize terms
			$terms = ! empty( $list_terms ) ? implode( ', ', $list_terms ) : '';

			// Turn into comma seperated string
			return $terms;

		}

		/**
		 * Searches for post parents and adds them to the trail
		 *
		 * @since 1.0.0
		 */
		public static function get_post_parents( $post_id = '', $path = '' ) {

			// Define new trail var
			$trail = array();

			// Return empty array if the post id and path are both empty
			if ( empty( $post_id ) && empty( $path ) ) {
				return $trail;
			}

			// Return if path equals /
			if ( '/' == $path ) {
				return $trail;
			}

			// Define empty parents array
			$parents = array();

			// If the post ID is empty, use the path to get the ID.
			if ( empty( $post_id ) ) {

				// Get parent post by the path.
				$parent_page = get_page_by_path( $path );

				// Try to get by title with single word
				if ( empty( $parent_page ) ) {
					$parent_page = get_page_by_title( $path );
				}

				// Try again based on title with multiple words
				if ( empty( $parent_page ) ) {
					$parent_page = get_page_by_title ( str_replace( array('-', '_'), ' ', $path ) );
				}

				// Parent is found so lets return the ID
				if ( ! empty( $parent_page ) ) {
					$post_id = $parent_page->ID;
				}

			}

			// If a post ID and path is set, search for a post by the given path.
			if ( $post_id == 0 && ! empty( $path ) ) {

				// Separate post names into separate paths by '/'.
				$path = trim( $path, '/' );
				preg_match_all( "/\/.*?\z/", $path, $matches );

				// If matches are found for the path.
				if ( isset( $matches ) ) {

					// Reverse the array of matches to search for posts in the proper order.
					$matches = array_reverse( $matches );

					// Loop through each of the path matches.
					foreach ( $matches as $match ) {

						// If a match is found.
						if ( isset( $match[0] ) ) {

							// Get the parent post by the given path.
							$path = str_replace( $match[0], '', $path );
							$parent_page = get_page_by_path( trim( $path, '/' ) );

							// If a parent post is found, set the $post_id and break out of the loop.
							if ( ! empty( $parent_page ) && $parent_page->ID > 0 ) {
								$post_id = $parent_page->ID;
								break;
							}
						}
					}
				}
			}

			if ( ! $post_id ) {
				return $trail;
			}

			// Loop through and add parents to parents array
			while ( $post_id ) {

				// Get the post by ID.
				$page = get_page( $post_id );

				// Add the post link to the array
				$parents[] = self::get_crumb_html( get_the_title( $post_id ), get_permalink( $post_id ), 'trail-parent' );

				// Set the parent post's parent to the post ID.
				$post_id = $page ? $page->post_parent : '';

			}

			// If parent pages are found reverse order so they are correct
			if ( $parents ) {
				$trail = array_reverse( $parents );
			}

			// Return parent posts
			return $trail;

		}

		/**
		 * Searches for term parents and adds them to the trail
		 *
		 * @since 3.0.9
		 */
		private function get_term_parents( $term = '' ) {

			// New trail
			$trail = array();

			// Term check
			if ( empty( $term->taxonomy ) ) {
				return $trail;
			}

			// Define parents array and get term taxonomy
			$parents  = array();
			$taxonomy = $term->taxonomy;

			// Get parents
			if ( is_taxonomy_hierarchical( $taxonomy ) && $term->parent != 0 ) {

				// While there is a parent ID, add the parent term link to the $parents array.
				$count='';
				while ( $term->parent != 0 ) {
					$count ++;

					// Get term
					$term = get_term( $term->parent, $taxonomy );

					// Add the formatted term link to the array of parent terms.
					$parents['parent_term_'. $count ] = self::get_crumb_html( $term->name, get_term_link( $term, $taxonomy ), 'trail-parent-term' );

				}

				// If we have parent terms, reverse the array to put them in the proper order for the trail.
				if ( ! empty( $parents ) ) {
					$trail = array_reverse( $parents );
				}

			}

			// Return the trail of parent terms.
			return $trail;

		} // End get_term_parents

		/**
		 * Get the parent category if only one term exists for the post
		 *
		 * @since 3.3.2
		 */
		public static function get_singular_first_cat_parents( $taxonomy = '' ) {

			// Default empty trail
			$trail = array();

			// Make sure taxonomy exists
			if ( ! $taxonomy || ! taxonomy_exists( $taxonomy ) ) {
				return null;
			}

			// Get terms
			$terms = wp_get_post_terms( get_the_ID(), $taxonomy );

			// Get parent
			if ( $terms && isset( $terms[0] ) and 1 == count( $terms ) ) {
				$term = get_term( $terms[0] );
				$trail = $this->get_term_parents( $term );
			}

			// Return trail
			return $trail;

		}

		/**
		 * Gets Woo Shop data
		 *
		 * @since 3.0.9
		 */
		public static function get_shop_data( $return = '' ) {

			// Define data
			$data = array(
				'id'    => '',
				'url'   => '',
				'title' => esc_html__( 'Shop', 'total' ),
			);

			// If wc_get_page_id function doesn't exist return empty data array
			if ( ! function_exists( 'wc_get_page_id' ) ) {
				return $data;
			}

			// Get Woo page ID
			$id = intval( wpex_parse_obj_id( wc_get_page_id( 'shop' ), 'page' ) );

			// Get data from ID
			if ( $id ) {
				$data['id']    = $id;
				$data['url']   = get_permalink( $id );
				$data['title'] = get_the_title( $id );
			}

			// Apply filters to title
			$data['title'] = apply_filters( 'wpex_breadcrumbs_shop_title', $data['title'] );

			// Return data
			return $data;

		}

		/**
		 * Returns breadcrumbs classes
		 *
		 * @since 4.0
		 */
		public static function wrap_classes() {

			// Breadcrumbs position
			$position = wpex_get_mod( 'breadcrumbs_position', 'absolute' );

			// Alter position based on header style
			$header_style = wpex_page_header_style();
			if ( 'background-image' == $header_style || 'centered' == $header_style || 'centered-minimal' == $header_style ) {
				$position = 'under-title';
			}

			// Apply filters to position
			$position = apply_filters( 'wpex_breadcrumbs_position', $position );

			// Sanitize position
			$position = ( $position && 'default' != $position ) ? $position : 'absolute';

			// Breadcrumbs classes
			$classes = array( 'site-breadcrumbs', 'wpex-clr' );

			// Add position class
			if ( $position ) {

				// Main class
				$classes[] = 'position-'. $position;

				// Fix for when crumbs are too long
				if ( 'absolute' == $position ) {
					$classes[] = 'has-js-fix';
				}

			}

			// Apply filters to classes
			$classes = apply_filters( 'wpex_breadcrumbs_classes', $classes );

			// Turn classes into string
			if ( $classes && is_array( $classes ) ) {
				$classes = implode( ' ', $classes );
			}

			// Return classes
			return $classes;

		}

	}

}