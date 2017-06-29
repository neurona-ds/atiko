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

<header class="single-page-header<?php if ( 'full-screen' == wpex_content_area_layout() ) echo ' container'; ?>"><h1 class="single-page-title entry-title"<?php wpex_schema_markup( 'heading' ); ?>><?php the_title(); ?></h1></header>