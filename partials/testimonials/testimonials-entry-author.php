<?php
/**
 * Outputs the testimonial entry author
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 4.2
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Display author if defined
if ( $author = get_post_meta( get_the_ID(), 'wpex_testimonial_author', true ) ) : ?>
	<span class="testimonial-entry-author entry-title"><?php echo wp_kses_post( $author ); ?></span>
<?php endif; ?>