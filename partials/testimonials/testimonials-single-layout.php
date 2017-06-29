<?php
/**
 * Testimonials single post layout
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 4.0
 *
 * @todo Allow display of the title in the testimonial seperate from archive entry title setting
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div id="single-blocks" class="clr">

	<div class="entry-content entry wpex-clr">

		<?php

		// "Quote" style
		if ( 'blockquote' == wpex_get_mod( 'testimonial_post_style', 'blockquote' ) ) :

			get_template_part( 'partials/testimonials/testimonials-entry' );

		// Display full content
		else :

			the_content();

		endif; ?>

	</div><!-- .entry-content -->

	<?php
	// Displays comments if enabled
	if ( wpex_get_mod( 'testimonials_comments', false ) && comments_open() ) : ?>

		<div id="testimonials-post-comments" class="clr"><?php comments_template(); ?></div>

	<?php endif; ?>

</div><!-- #single-blocks -->