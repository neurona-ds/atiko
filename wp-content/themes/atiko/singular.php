<?php
/**
 * The template for displaying all pages, single posts and attachments
 *
 * This is a new template file that WordPress introduced in
 * version 4.3. Note that it uses conditional logic to display
 * different content based on the post type.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Total WordPress Theme
 * @subpackage Templates
 * @version 4.0
 */

get_header(); ?>

	<div id="content-wrap" class="container clr">

		<?php wpex_hook_primary_before(); ?>

		<div id="primary" class="content-area clr">

			<?php wpex_hook_content_before(); ?>

			<div id="content" class="site-content clr">

				<?php wpex_hook_content_top(); ?>

				<?php
				// Start loop
				while ( have_posts() ) : the_post();

					// Single Page
					if ( is_singular( 'page' ) ) {

						wpex_get_template_part( 'page_single_blocks' );

					}

					// Single posts
    				elseif ( is_singular( 'post' ) ) {

    					wpex_get_template_part( 'blog_single_blocks' );

    				}

					// Portfolio Posts
					elseif ( is_singular( 'portfolio' ) && WPEX_PORTFOLIO_IS_ACTIVE ) {

						wpex_get_template_part( 'portfolio_single_blocks' );

					}

					// Staff Posts
					elseif ( is_singular( 'staff' ) && WPEX_STAFF_IS_ACTIVE ) {

						wpex_get_template_part( 'staff_single_blocks' );

					}

					// Testimonials Posts
					elseif ( is_singular( 'testimonials' ) && WPEX_TESTIMONIALS_IS_ACTIVE ) {

						wpex_get_template_part( 'testimonials_single_blocks' );

					}

					// All other post types - when customizing your custom post types it's best to create
					// a new singular-{post_type}.php file to prevent any possible conflicts in the future
					// rather then altering the template part.
					else {

						wpex_get_template_part( 'cpt_single_blocks', get_post_type() );

  					}

				endwhile; ?>

				<?php wpex_hook_content_bottom(); ?>

			</div><!-- #content -->

			<?php wpex_hook_content_after(); ?>

		</div><!-- #primary -->

		<?php wpex_hook_primary_after(); ?>

	</div><!-- .container -->

<?php get_footer(); ?>