<?php
/**
 * Single Custom Post Type Layout
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 4.0
 *
 * Total has built-in filters so you can override this output via a child theme
 * without editing this file manually
 *
 * @link http://wpexplorer-themes.com/total/snippets/cpt-single-blocks/
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div id="single-blocks" class="wpex-clr">

	<?php
	// Get layout blocks
	$blocks = wpex_single_blocks();

	// Make sure we have blocks
	if ( ! empty( $blocks ) && is_array( $blocks ) ) :

		// Loop through blocks and get template part
		foreach ( $blocks as $block ) :
			
			// Media not needed for this position
			if ( 'media' == $block && get_post_meta( get_the_ID(), 'wpex_post_media_position', true ) ) {
				continue;
			}

			// Callable output
			if ( 'the_content' != $block && is_callable( $block ) ) {

				call_user_func( $block );

			}

			// Get template part output
			else {
			
				get_template_part( 'partials/cpt/cpt-single-'. $block, get_post_type() );

			}

		endforeach;

	endif; ?>

</div><!-- #single-blocks -->