<?php
/**
 * Single related posts
 *
 * @package Total WordPress Theme
 * @subpackage Partials
 * @version 4.2
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Return if disabled
if ( ! wpex_get_mod( 'blog_related', true ) ) {
	return;
}

// Number of columns for entries
$wpex_columns = apply_filters( 'wpex_related_blog_posts_columns', wpex_get_mod( 'blog_related_columns', '3' ) );

// Query args
$args = array(
	'posts_per_page' => wpex_get_mod( 'blog_related_count', '3' ),
	'orderby'        => 'rand',
	'post__not_in'   => array( get_the_ID() ),
	'no_found_rows'  => true,
	'tax_query'      => array(
		'relation'  => 'AND',
		array(
			'taxonomy' => 'post_format',
			'field'    => 'slug',
			'terms'    => array( 'post-format-quote', 'post-format-link' ),
			'operator' => 'NOT IN',
		),
	),
);

// Query items fom same category
if ( apply_filters( 'wpex_related_in_same_cat', true ) ) {
	$cats = wp_get_post_terms( get_the_ID(), 'category', array(
		'fields' => 'ids',
	) );
	if ( $cats ) {
		$args['category__in'] = $cats;
	}
}

// If content is disabled make sure items have featured images
if ( ! wpex_get_mod( 'blog_related_excerpt', true ) ) {
	$args['meta_key'] = '_thumbnail_id';
}

// Apply filters to arguments for child theme editing.
$args = apply_filters( 'wpex_blog_post_related_query_args', $args );

// Related query arguments
$wpex_related_query = new wp_query( $args );

// If the custom query returns post display related posts section
if ( $wpex_related_query->have_posts() ) :

	// Wrapper classes
	$classes = 'related-posts clr';
	if ( 'full-screen' == wpex_content_area_layout() ) {
		$classes .= ' container';
	} ?>

	<div class="<?php echo esc_attr( $classes ); ?>">

		<?php get_template_part( 'partials/blog/blog-single-related', 'heading' ); ?>

		<div class="wpex-row clr">
			<?php $wpex_count = 0; ?>
			<?php foreach( $wpex_related_query->posts as $post ) : setup_postdata( $post ); ?>
				<?php $wpex_count++; ?>
				<?php include( locate_template( 'partials/blog/blog-single-related-entry.php' ) ); ?>
				<?php if ( $wpex_columns == $wpex_count ) $wpex_count=0; ?>
			<?php endforeach; ?>
		</div><!-- .wpex-row -->

	</div><!-- .related-posts -->

<?php endif; ?>

<?php wp_reset_postdata(); ?>