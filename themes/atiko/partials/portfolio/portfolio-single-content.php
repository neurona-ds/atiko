<?php
/**
 * Portfolio single content
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<article class="single-portfolio-content entry clr"<?php wpex_schema_markup( 'entry_content' ); ?>><?php the_content(); ?></article>