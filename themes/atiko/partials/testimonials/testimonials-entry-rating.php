<?php
/**
 * Outputs the testimonial entry rating
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get and display rating
if ( $rating = wpex_get_star_rating() ) : ?>
	<div class="testimonial-entry-rating clr"><?php echo $rating; // Already sanitized via wpex_get_star_rating function ?></div>
<?php endif; ?>