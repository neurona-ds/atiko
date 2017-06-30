<?php
/**
 * The template for displaying Author bios.
 *
 * @package Total WordPress Theme
 * @subpackage Templates
 * @version 4.2
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get global post
global $post;

// Return if post is empty
if ( ! $post ) {
	return;
}

// Define author bio data
$data = array(
	'post_author' => $post->post_author,
	'avatar_size' => apply_filters( 'wpex_author_bio_avatar_size', 74 ),
	'author_name' => get_the_author(),
	'posts_url'   => get_author_posts_url( $post->post_author ),
	'description' => get_the_author_meta( 'description', $post->post_author ),
);

// Get author avatar
$data['avatar'] = get_avatar( $post->post_author, $data['avatar_size'] );

// Apply filters so we can tweak the author bio output
$data = apply_filters( 'wpex_post_author_bio_data', $data );

// Extract variables
extract( $data );

// Only display if description exists
if ( $description ) : ?>

	<section class="author-bio clr<?php if ( ! $avatar ) echo ' no-avatar'; ?>">

		<?php if ( $avatar ) : ?>

			<div class="author-bio-avatar">

				<a href="<?php echo esc_url( $posts_url ); ?>" title="<?php esc_attr_e( 'Visit Author Page', 'total' ); ?>">
					<?php
					// Display author avatar
					echo wpex_sanitize_data( $avatar, 'img' ); ?>
				</a>

			</div><!-- .author-bio-avatar -->
			
		<?php endif; ?>

		<div class="author-bio-content clr">

			<h4 class="author-bio-title">
				<a href="<?php echo esc_url( $posts_url ); ?>" title="<?php esc_attr_e( 'Visit Author Page', 'total' ); ?>"><?php echo strip_tags( $author_name ); ?></a>
			</h4><!-- .author-bio-title -->

			<?php
			// Outputs the author description if one exists
			if ( $description ) : ?>

				<div class="author-bio-description clr">
					<?php echo wpautop( do_shortcode( wp_kses_post( $description ) ) ); ?>
				</div><!-- author-bio-description -->

			<?php endif; ?>

			<?php
			// Display author social links if there are social links defined
			if ( wpex_author_has_social( $post_author ) ) :

				// Get social button class
				$class = wpex_get_social_button_class( wpex_get_mod( 'author_box_social_style', 'flat-color-round' ) ); ?>

				<div class="author-bio-social clr">

					<?php
					// Display twitter url
					if ( $url = get_the_author_meta( 'wpex_twitter', $post_author ) ) : ?>
						<a href="<?php echo esc_url( $url ); ?>" title="Twitter" class="wpex-twitter tooltip-up <?php echo esc_attr( $class ); ?>">
							<span class="fa fa-twitter"></span>
							<span class="screen-reader-text">Twitter</span>
						</a>
					<?php endif; ?>

					<?php
					// Display facebook url
					if ( $url = get_the_author_meta( 'wpex_facebook', $post_author ) ) : ?>
						<a href="<?php echo esc_url( $url ); ?>" title="Facebook" class="wpex-facebook tooltip-up <?php echo esc_attr( $class ); ?>">
							<span class="fa fa-facebook"></span>
							<span class="screen-reader-text">Facebook</span>
						</a>
					<?php endif; ?>

					<?php
					// Display google plus url
					if ( $url = get_the_author_meta( 'wpex_googleplus', $post_author ) ) : ?>
						<a href="<?php echo esc_url( $url ); ?>" title="Google Plus" class="wpex-google-plus tooltip-up <?php echo esc_attr( $class ); ?>">
							<span class="fa fa-google-plus"></span>
							<span class="screen-reader-text">Google Plus</span>
						</a>
					<?php endif; ?>

					<?php
					// Display Linkedin url
					if ( $url = get_the_author_meta( 'wpex_linkedin', $post_author ) ) : ?>
						<a href="<?php echo esc_url( $url ); ?>" title="LinkedIn" class="wpex-linkedin tooltip-up <?php echo esc_attr( $class ); ?>">
							<span class="fa fa-linkedin"></span>
							<span class="screen-reader-text">LinkedIn</span>
						</a>
					<?php endif; ?>

					<?php
					// Display pinterest plus url
					if ( $url = get_the_author_meta( 'wpex_pinterest', $post_author ) ) : ?>
						<a href="<?php echo esc_url( $url ); ?>" title="Pinterest" class="wpex-pinterest tooltip-up <?php echo esc_attr( $class ); ?>">
							<span class="fa fa-pinterest"></span>
							<span class="screen-reader-text">Pinterest</span>
						</a>
					<?php endif; ?>

					<?php
					// Display instagram plus url
					if ( $url = get_the_author_meta( 'wpex_instagram', $post_author ) ) : ?>
						<a href="<?php echo esc_url( $url ); ?>" title="Instagram" class="wpex-instagram tooltip-up <?php echo esc_attr( $class ); ?>">
							<span class="fa fa-instagram"></span>
							<span class="screen-reader-text">Instagram</span>
						</a>
					<?php endif; ?>

				</div><!-- .author-bio-social -->

			<?php endif; ?>

		</div><!-- .author-bio-content -->

	</section><!-- .author-bio -->

<?php endif; ?>