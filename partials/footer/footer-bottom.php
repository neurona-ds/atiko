<?php
/**
 * Footer bottom content
 *
 * @package Total WordPress Theme
 * @subpackage Partials
 * @version 4.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Classes
$classes = 'clr';
if ( $align = wpex_get_mod( 'bottom_footer_text_align' ) ) {
	$classes .= ' text'. $align;
} ?>

<?php wpex_hook_footer_bottom_before(); ?>

<div id="footer-bottom" class="<?php echo esc_attr( $classes ); ?>"<?php wpex_schema_markup( 'footer_bottom' ); ?>>
	<div id="footer-bottom-inner" class="container clr">
		<?php wpex_hook_footer_bottom_inner(); ?>
	</div><!-- #footer-bottom-inner -->
</div><!-- #footer-bottom -->

<?php wpex_hook_footer_bottom_after(); ?>