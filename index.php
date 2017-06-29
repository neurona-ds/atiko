<?php
/**
 * The div template file.
 *
 * This is the most generic template file in a WordPress theme and one of the
 * two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * For example, it puts together the home page when no home.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Total WordPress Theme
 * @subpackage Templates
 * @version 3.6.0
 */

get_header(); ?>

	<div id="content-wrap" class="container clr">

		<?php wpex_hook_primary_before(); ?>

		<div id="primary" class="content-area clr">

			<?php wpex_hook_content_before(); ?>

			<div id="content" class="site-content">

				<?php wpex_hook_content_top(); ?>

				<?php
				// Display posts if there are in fact posts to display
				if ( have_posts() ) :
					
					/*-----------------------------------------------------------------------------------*/
					/*  - Standard Post Type (BLOG)
					/*  - See framework/conditionals.php
					/*  - Blog entries use partials/blog/blog-entry.php for their output
					/*-----------------------------------------------------------------------------------*/
					if ( wpex_is_blog_query() ) : ?>

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
						// Display post pagination (next/prev - 1,2,3,4..)
						wpex_blog_pagination(); ?>

					<?php
					/*-----------------------------------------------------------------------------------*/
					/*  - Custom post type archives display
					/*  - All non standard post type entries use partials/cpt/cpt-entry-{post_type}.php for their output
					/*-----------------------------------------------------------------------------------*/
					else : ?>

						<div class="<?php echo esc_attr( wpex_get_archive_grid_class() ); ?>">

							<?php
							// Define counter for clearing floats
							$wpex_count = 0;
							while ( have_posts() ) : the_post();

								// Add to counter
								$wpex_count++;

								// Get custom post type entry
								wpex_get_template_part( 'cpt_entry', get_post_type() );

								// Reset counter to clear floats
								if ( wpex_get_grid_entry_columns() == $wpex_count ) {
									$wpex_count=0;
								}

							// End loop
							endwhile; ?>

						</div>

						<?php wpex_pagination(); ?>

					<?php endif; ?>

				<?php
				// Show message because there aren't any posts
				else : ?>

					<article class="clr"><?php esc_html_e( 'No Posts found.', 'total' ); ?></article>

				<?php endif; ?>

				 <?php wpex_hook_content_bottom(); ?>

			</div><!-- #content -->

		<?php wpex_hook_content_after(); ?>

		</div><!-- #primary -->

		<?php wpex_hook_primary_after(); ?>

	</div><!-- .container -->
	
<?php get_footer(); ?>