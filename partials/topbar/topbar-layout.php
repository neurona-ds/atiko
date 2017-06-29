<?php
/**
 * Topbar layout
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Classes
$classes = 'clr';
if ( wpex_get_mod( 'top_bar_sticky' ) ) {
	$classes .= ' wpex-top-bar-sticky';
}
if ( $visibility = wpex_get_mod( 'top_bar_visibility' ) ) {
	$classes .= ' ' . $visibility;
}
if ( 'full-width' == wpex_site_layout() && wpex_get_mod( 'top_bar_fullwidth' ) ) {
	$classes .= ' wpex-full-width';
} ?>

<?php wpex_hook_topbar_before(); ?>

	<div id="top-bar-wrap" class="<?php echo esc_attr( $classes ); ?>">
		<div id="top-bar" class="clr container">
			<?php wpex_hook_topbar_inner(); ?>
		</div><!-- #top-bar -->
	</div><!-- #top-bar-wrap -->

<?php wpex_hook_topbar_after(); ?>