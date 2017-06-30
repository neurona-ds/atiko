<?php
/**
 * Single blog post title
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<header class="single-blog-header clr">
	<h1 class="single-post-title entry-title"<?php wpex_schema_markup( 'headline' ); ?>><?php the_title(); ?></h1>
</header>