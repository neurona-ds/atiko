<?php
/**
 * Staff single content
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<article class="single-staff-content entry clr"<?php wpex_schema_markup( 'entry_content' ); ?>><?php the_content(); ?></article>

<?php
// Page links (for the <!-nextpage-> tag)
get_template_part( 'partials/link-pages' ); ?>