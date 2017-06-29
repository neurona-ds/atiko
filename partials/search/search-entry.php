<?php
/**
 * Search entry layout
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 3.6.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Check if post has thumbnail
$has_thumb = apply_filters( 'wpex_search_has_post_thumbnail', has_post_thumbnail() );

// Add classes to the post_class
$classes   = array();
$classes[] = 'search-entry';
$classes[] = 'clr';
if ( ! $has_thumb ) {
	$classes[] = 'search-entry-no-thumb';
} ?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $classes ); ?>>
	<?php if ( $has_thumb ) : ?>
		<?php get_template_part( 'partials/search/search-entry-thumbnail' ); ?>
	<?php endif; ?>
	<div class="search-entry-text"><?php

		// Display header
		get_template_part( 'partials/search/search-entry-header' );

		// Display excerpt
		get_template_part( 'partials/search/search-entry-excerpt' );

	?></div>
</article>