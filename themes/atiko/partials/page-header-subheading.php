<?php
/**
 * Page subheading output
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Display subheading if there is one
if ( $subheading = wpex_page_header_subheading_content() ) : ?>
	<div class="page-subheading clr"><?php echo do_shortcode( wp_kses_post( $subheading ) ); ?></div>
<?php endif; ?>