<?php
/**
 * Custom Post Type Entry
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 4.0
 *
 * Total has built-in filters so you can override this output via a child theme
 * without editing this file manually
 *
 * @link http://wpexplorer-themes.com/total/snippets/cpt-entry-blocks/
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<article id="post-<?php the_ID(); ?>" <?php post_class( wpex_get_archive_grid_entry_class() ); ?>>

	<div class="cpt-entry-inner wpex-clr">
		<?php
		// Get layout blocks
		$blocks = wpex_entry_blocks();

		// Make sure blocks aren't empty and it's an array
		if ( ! empty( $blocks ) && is_array( $blocks ) ) :

			// Loop through blocks and get template part
			foreach ( $blocks as $block ) :

				// Callable output
				if ( 'the_content' != $block && is_callable( $block ) ) {

					call_user_func( $block );

				} else {

					get_template_part( 'partials/cpt/cpt-entry-'. $block, get_post_type() );

				}

			endforeach;

		endif; ?>

	</div><!-- .cpt-entry-inner -->

</article><!-- .cpt-entry -->