<?php
/**
 * The page header displays at the top of all single pages and posts
 * See framework/page-header.php for all page header related functions.
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 4.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Check if post has terms if so then show next/prev from the same_cat
if ( wpex_get_mod( 'next_prev_in_same_term', true  ) ) {
	$has_terms = wpex_post_has_terms( get_the_ID() );
	$same_cat  = $has_terms;
} else {
	$same_cat = false;
}
$same_cat = apply_filters( 'wpex_next_prev_in_same_term', $same_cat );

$has_terms = $same_cat ? $has_terms : false; // Added check for filter

// Get taxonomy for same_term filter
if ( $same_cat ) {
	$taxonomy = wpex_get_post_type_cat_tax();
	$taxonomy = apply_filters( 'wpex_next_prev_same_cat_taxonomy', $taxonomy );
} else {
	$taxonomy = 'category';
}

// Exclude terms
$excluded_terms = apply_filters( 'wpex_next_prev_excluded_terms', null );

// Check if order is set to reverse
$reverse_order = apply_filters( 'wpex_nex_prev_reverse', wpex_get_mod( 'next_prev_reverse_order', false ) );

// Texts
$prev_text = wpex_get_mod( 'next_prev_prev_text' );
$prev_text = $prev_text ? esc_html( $prev_text ) : '%title';

$next_text = wpex_get_mod( 'next_prev_next_text' );
$next_text = $next_text ? esc_html( $next_text ) : '%title';

// Previous post link title
$prev_post_link_title = '<span class="fa fa-angle-double-left"></span>' . $prev_text;
$prev_post_link_title = apply_filters( 'wpex_prev_post_link_title', $prev_post_link_title );

// Next post link title
$next_post_link_title = $next_text . '<span class="fa fa-angle-double-right"></span>';
$next_post_link_title = apply_filters( 'wpex_next_post_link_title', $next_post_link_title );

// Reverse titles
if ( $reverse_order ) {
	$prev_post_link_title_tmp = $prev_post_link_title;
	$next_post_link_title_tmp = $next_post_link_title;
	$prev_post_link_title     = $next_post_link_title_tmp;
	$next_post_link_title     = $prev_post_link_title_tmp;
}

// Get post links
if ( $has_terms || wpex_is_post_in_series() ) {
	$prev_link = get_previous_post_link( '%link', $prev_post_link_title, $same_cat, $excluded_terms, $taxonomy );
	$next_link = get_next_post_link( '%link', $next_post_link_title, $same_cat, $excluded_terms, $taxonomy );
} else {
	$prev_link = get_previous_post_link( '%link', $prev_post_link_title, false );
	$next_link = get_next_post_link( '%link', $next_post_link_title, false );
}

// Display next and previous links
if ( $prev_link || $next_link ) :

	if ( $reverse_order ) {
		$prev_link_output = '<li class="post-prev">' . $next_link . '</li>';
		$next_link_output = '<li class="post-next">' . $prev_link . '</li>';
	} else {
		$prev_link_output = '<li class="post-prev">' . $prev_link . '</li>';
		$next_link_output = '<li class="post-next">' . $next_link . '</li>';
	} ?>

	<div class="post-pagination-wrap clr">

		<ul class="post-pagination container clr">
			<?php echo $prev_link_output; ?>
			<?php echo $next_link_output; ?>
		</ul><!-- .post-post-pagination -->

	</div><!-- .post-pagination-wrap -->

<?php endif; ?>