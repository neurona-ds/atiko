<?php
/**
 * Alter posts query
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wpex_pre_get_posts( $query ) {

	// Only alter main query
	if ( ! $query->is_main_query() ) {
		return;
	}

	// Search pagination
	if ( is_search() ) {
		$query->set( 'posts_per_page', wpex_get_mod( 'search_posts_per_page', '10' ) );
		return;
	}

	// Exclude categories from the main blog
	if ( ( is_home() || is_page_template( 'templates/blog.php' ) ) ) {
		$cats = wpex_blog_exclude_categories();
		if ( $cats ) {
			$query->set( 'category__not_in', $cats );
		}
		return;
	}

	// Category pagination
	if ( $query->is_category() ) {
		$terms = get_terms( 'category' );
		if ( ! empty( $terms ) ) {
			foreach ( $terms as $term ) {
				if ( is_category( $term->slug ) ) {
					$term_id    = $term->term_id;
					$term_data  = get_option( "category_$term_id" );
					if ( $term_data ) {
						if ( ! empty( $term_data['wpex_term_posts_per_page'] ) ) {
							$query->set( 'posts_per_page', $term_data['wpex_term_posts_per_page'] );
							return;
						}
					}
				}
			}
		}
	}

}
add_action( 'pre_get_posts', 'wpex_pre_get_posts' );