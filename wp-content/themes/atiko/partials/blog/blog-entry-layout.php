<?php
/**
 * Blog entry layout
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get post data
$post_format = get_post_format();
$entry_style = wpex_blog_entry_style();

// Quote format is completely different
if ( 'quote' == $post_format ) :

	// Get quote entry content
	get_template_part( 'partials/blog/blog-entry-quote' );

	// Don't run any other code in this file
	return;

endif;

// Get layout blocks
$blocks = wpex_blog_entry_layout_blocks(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class( wpex_blog_entry_classes() ); ?>>

	<div class="blog-entry-inner clr">

		<?php
		// Thumbnail entry style uses different layout
		if ( 'thumbnail-entry-style' == $entry_style ) : ?>

			<?php
			// Get media
			get_template_part( 'partials/blog/media/blog-entry', $post_format ); ?>

			<div class="blog-entry-content entry-details clr">

				<?php
				// Loop through entry blocks
				foreach ( $blocks as $block ) :

					// Callable output
					if ( is_callable( $block ) ) {

						call_user_func( $block );

					}

					// Display the entry title
					elseif ( 'title' == $block ) {

						get_template_part( 'partials/blog/blog-entry-title' );

					}
					
					// Display the entry meta
					elseif ( 'meta' == $block ) {

						get_template_part( 'partials/blog/blog-entry-meta' );

					}

					// Display the entry excerpt or content
					elseif ( 'excerpt_content' == $block ) {

						get_template_part( 'partials/blog/blog-entry-content' );

					}

					// Display the readmore button
					elseif ( 'readmore' == $block ) {

						if ( wpex_has_readmore() ) {

							get_template_part( 'partials/blog/blog-entry-readmore' );

						}

					}

					// Display the readmore button
					elseif ( 'social_share' == $block ) {

						wpex_social_share();

					}

					// Custom Blocks
					else {

						get_template_part( 'partials/blog/blog-entry-'. $block );

					}

				// End block loop
				endforeach; ?>

			</div><!-- blog-entry-content -->

		<?php

		// Other entry styles
		else :
			
			// Loop through composer blocks and output layout
			foreach ( $blocks as $block ) :

				// Callable output
				if ( is_callable( $block ) ) {

					call_user_func( $block );

				}

				// Featured media
				elseif ( 'featured_media' == $block ) {

					get_template_part( 'partials/blog/media/blog-entry', $post_format );

				}

				// Display the entry header
				elseif ( 'title' == $block ) {

					get_template_part( 'partials/blog/blog-entry-title' );

				}
				
				// Display the entry meta
				elseif ( 'meta' == $block ) {

					get_template_part( 'partials/blog/blog-entry-meta' );

				}

				// Display the entry excerpt or content
				elseif ( 'excerpt_content' == $block ) {

					get_template_part( 'partials/blog/blog-entry-content' );

				}

				// Display the readmore button
				elseif ( 'readmore' == $block ) {

					if ( wpex_has_readmore() ) {

						get_template_part( 'partials/blog/blog-entry-readmore' );

					}

				}

				// Display the readmore button
				elseif ( 'social_share' == $block ) {

					wpex_social_share();

				}

				// Custom Blocks
				else {

					get_template_part( 'partials/blog/blog-entry-'. $block );

				}

			// End block loop
			endforeach;

		// End block check
		endif; ?>

	</div><!-- .blog-entry-inner -->

</article><!-- .blog-entry -->