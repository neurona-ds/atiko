<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package Total WordPress Theme
 * @subpackage Templates
 * @version 3.3.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get site header
get_header(); ?>

	<div id="content-wrap" class="container clr">

		<?php wpex_hook_primary_before(); ?>

		<div id="primary" class="content-area clr">

			<?php wpex_hook_content_before(); ?>

			<div id="content" class="site-content">

				<?php wpex_hook_content_top(); ?>

				<?php
				// Check if there are search results
				if ( have_posts() ) : ?>

					<div id="search-entries" class="clr">

						<?php
						// Display blog style search results
						if ( 'blog' == wpex_search_results_style() ) : ?>

							<div id="blog-entries" class="<?php wpex_blog_wrap_classes(); ?>">

								<?php
								// Define counter for clearing floats
								$wpex_count = 0;

								// Start div loop
								while ( have_posts() ) : the_post();

									// Add to counter
									$wpex_count++;

									// Get blog entry layout
									wpex_get_template_part( 'blog_entry' );

									// Reset counter to clear floats
									if ( wpex_blog_entry_columns() == $wpex_count ) {
										$wpex_count=0;
									}

								// End loop
								endwhile; ?>

							</div><!-- #blog-entries -->

						<?php
						// Display custom style for search entries
						else : ?>

							<?php while ( have_posts() ) : the_post(); ?>

								<?php wpex_get_template_part( 'search_entry' ); ?>

							<?php endwhile; ?>

						<?php endif; ?>

					</div><!-- #search-entries -->

					<?php
					// Display pagination
					wpex_pagination(); ?>

				<?php
				// No search results found
				else : ?>

					<?php get_template_part( 'partials/search/search-no-results' ); ?>

				<?php endif; ?>

				<?php wpex_hook_content_bottom(); ?>

			</div><!-- #content -->

			<?php wpex_hook_content_after(); ?>

		</div><!-- #primary -->

		<?php wpex_hook_primary_after(); ?>

	</div><!-- #content-wrap -->

<?php
// Get site footer
get_footer(); ?>