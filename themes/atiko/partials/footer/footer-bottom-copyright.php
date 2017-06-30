<?php
/**
 * Footer bottom content
 *
 * @package Total WordPress Theme
 * @subpackage Partials
 * @version 4.2
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get copyright info
$copyright = wpex_get_mod( 'footer_copyright_text', 'Copyright <a href="#">Your Business LLC.</a> - All Rights Reserved' );

// Translate the theme option
$copyright = wpex_translate_theme_mod( 'footer_copyright_text', $copyright );

// Return if there isn't any copyright content to display
if ( ! $copyright ) {
	return;
} ?>

<div id="copyright" class="clr"<?php wpex_aria_landmark( 'copyright' ); ?>>
	<?php echo do_shortcode( wp_kses_post( $copyright ) ); ?>
</div><!-- #copyright -->