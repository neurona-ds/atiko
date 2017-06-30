<?php
/**
 * Single portfolio meta
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get meta sections
$sections = wpex_portfolio_single_meta_sections();

// Make sure the meta should display
if ( ! empty( $sections ) ) : ?>

	<ul id="portfolio-single-meta" class="meta wpex-clr">

		<?php
		// Loop through meta sections
		foreach ( $sections as $section ) : ?>

			<?php
			// Date
			if ( 'date' == $section ) : ?>

				<li class="meta-date"><span class="fa fa-clock-o" aria-hidden="true"></span><time class="updated" datetime="<?php the_date('Y-m-d');?>"<?php wpex_schema_markup( 'publish_date' ); ?>><?php echo get_the_date(); ?></time></li>

			<?php
			// Author
			elseif ( 'author' == $section ) : ?>

				<li class="meta-author"><span class="fa fa-user" aria-hidden="true"></span><span class="vcard author"<?php wpex_schema_markup( 'author_name' ); ?>><?php the_author_posts_link(); ?></span></li>

			<?php
			// Categories
			elseif ( 'categories' == $section ) : ?>

				<?php if ( $categories = wpex_get_list_post_terms( 'portfolio_category' ) ) {?>

					<li class="meta-category"><span class="fa fa-folder-o" aria-hidden="true"></span><?php echo $categories; ?></li>

				<?php } ?>

			<?php
			// Comments
			elseif ( 'comments' == $section ) : ?>

				<?php if ( comments_open() && ! post_password_required() ) { ?>

					<li class="meta-comments comment-scroll"><span class="fa fa-comment-o" aria-hidden="true"></span><?php comments_popup_link( esc_html__( '0 Comments', 'total' ), esc_html__( '1 Comment',  'total' ), esc_html__( '% Comments', 'total' ), 'comments-link' ); ?></li>

				<?php } ?>

			<?php
			// Display Custom Meta Block
			elseif ( $key != 'meta' ) :

				// Note: Callable check needs to be here because of 'date'
				if ( is_callable( $val ) ) { ?>

					<li class="meta-<?php echo esc_attr( $key ); ?>"><?php echo call_user_func( $val ); ?></li>

				<?php } else { ?>

					<li class="meta-<?php echo esc_attr( $val ); ?>"><?php get_template_part( 'partials/meta/'. $val ); ?></li>

				<?php } ?>

			<?php endif; ?>

		<?php endforeach; ?>

	</ul><!-- #portfolio-single-meta -->

<?php endif; ?>