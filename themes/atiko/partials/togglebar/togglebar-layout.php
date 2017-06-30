<?php
/**
 * Togglebar output
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div id="toggle-bar-wrap" class="<?php echo esc_attr( wpex_togglebar_classes() ); ?>">
	<div id="toggle-bar" class="container wpex-clr">
		<?php wpex_get_template_part( 'togglebar_content' ); ?>
	</div><!-- #toggle-bar -->
</div><!-- #toggle-bar-wrap -->