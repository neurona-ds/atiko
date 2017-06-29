<?php
/**
 * Portfolio single layout
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div id="single-blocks" class="wpex-clr">

	<?php
	// Single layout blocks
	$blocks = wpex_portfolio_single_blocks();

	// Make sure we have blocks
	if ( $blocks && is_array( $blocks ) ) :

		// Loop through blocks and get template part
		foreach ( $blocks as $block ) :

			// Callable output
			if ( 'the_content' != $block && is_callable( $block ) ) {

				call_user_func( $block );

			}

			// Template part output
			else {

				get_template_part( 'partials/portfolio/portfolio-single-'. $block );

			}

		endforeach;

	endif; ?>

</div><!-- #single-blocks -->