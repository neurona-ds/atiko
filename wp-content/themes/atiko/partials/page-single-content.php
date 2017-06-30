<?php
/**
 * Page Content
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="single-page-content entry clr"><?php the_content(); ?></div>

<?php
// Page links (for the <!-nextpage-> tag)
get_template_part( 'partials/link-pages' ); ?>