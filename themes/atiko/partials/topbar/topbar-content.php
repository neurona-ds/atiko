<?php
/**
 * Topbar content
 *
 * @package Total WordPress Theme
 * @subpackage Partials
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get topbar content
$content = wpex_topbar_content();

// Display topbar content
if ( $content || has_nav_menu( 'topbar_menu' ) ) : ?>

	<div id="top-bar-content" class="<?php echo esc_attr( wpex_topbar_content_classes() ); ?>">

		<?php
		// Get topbar menu
		get_template_part( 'partials/topbar/topbar-menu' ); ?>

		<?php
		// Check if there is content for the topbar
		if ( $content ) : ?>

			<?php
			// Display top bar content
			echo do_shortcode( $content ); ?>

		<?php endif; ?>

	</div><!-- #top-bar-content -->

<?php endif; ?>