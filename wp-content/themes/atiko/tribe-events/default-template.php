<?php
/**
 * Default Page Template for "The Events Calendar Plugin"
 * Must keep file so all hooks are properly included
 *
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 3.3.0
 */

get_header(); ?>

	<div id="content-wrap" class="container clr">

		<?php wpex_hook_primary_before(); ?>

		<div id="primary" class="content-area clr">

			<?php wpex_hook_content_before(); ?>

			<div id="content" class="site-content clr">

				<?php wpex_hook_content_top(); ?>

				<article class="clr">

					<div id="tribe-events-pg-template">
						<?php tribe_events_before_html(); ?>
						<?php tribe_get_view(); ?>
						<?php tribe_events_after_html(); ?>
					</div> <!-- #tribe-events-pg-template -->

				</article><!-- #post -->

				<?php wpex_hook_content_bottom(); ?>

			</div><!-- #content -->

			<?php wpex_hook_content_after(); ?>

		</div><!-- #primary -->

		<?php wpex_hook_primary_after(); ?>

	</div><!-- #content-wrap -->

<?php get_footer(); ?>