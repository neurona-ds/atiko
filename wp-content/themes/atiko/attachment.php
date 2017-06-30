<?php
/**
 * The template for displaying image attachments.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Total WordPress Theme
 * @subpackage Templates
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header(); ?>

	<div class="container clr">

		<?php wpex_hook_primary_before(); ?>

		<div id="primary" class="content-area">

			<?php wpex_hook_content_before(); ?>

			<div id="content" class="site-content">

				<?php wpex_hook_content_top(); ?>

				<?php
				while ( have_posts() ) : the_post();
					
					// Get attachment type
					$post_id        = get_the_ID();
					$mime_type      = get_post_mime_type( $post_id );
					$attachment_url = wp_get_attachment_url( $post_id );

					// Image attachments
					if ( strpos( $mime_type, 'image' . '/' ) !== false ) { ?>

						<div id="attachment-post-media"><?php echo wp_get_attachment_image( get_the_ID(), 'large' ); ?></div>
						<div id="attachment-post-content" class="entry clr"><?php the_content(); ?></div>
						<div id="attachment-post-footer">
							<?php
							$footer_links = array();
							$sizes = array( 'full', 'large', 'medium', 'thumbnail' );
							if ( $sizes ) {
								foreach ( $sizes as $size ) {
									$image = wp_get_attachment_image_src( $post_id, $size );
									if ( $image ) {
										$dims  = $image[1] . 'x' . $image[2];
										$name  = $size . ' (' . $dims . ')';
										if ( ! isset( $footer_links[$dims] ) ) {
											$footer_links[$dims] = '<a href="' . $image[0] . '" title="' . esc_attr( $name ) . '">' . esc_html( $name ) . '</a>';
										}
									}
								}
								echo '<strong>' . esc_html__( 'Downloads', 'total' ) . '</strong>: '. implode( ' | ', $footer_links );
							} ?>
						</div>

					<?php } elseif ( strpos( $mime_type, 'video' . '/' ) !== false ) { ?>
						
						<div id="attachment-post-media"><?php echo do_shortcode( '[video src="'. $attachment_url .'" width="9999"]' ); ?></div>
						
						<div id="attachment-post-content" class="entry clr"><?php the_content(); ?></div>

						<div id="attachment-post-footer">
							<?php
							$footer_details = array();
							$meta = wp_get_attachment_metadata( $post_id ); ?>
							<?php
							// Display video format
							if ( ! empty( $meta['fileformat'] ) ) { ?>
								<p><strong><?php esc_html_e( 'Format', 'total' ); ?>: </strong> <?php echo $meta['fileformat']; ?></p>
							<?php } ?>
							<?php
							// Display video format
							if ( ! empty( $meta['filesize'] ) ) { ?>
								<p><strong><?php esc_html_e( 'Size', 'total' ); ?>: </strong> <?php echo size_format( $meta['filesize'], 2 ); ?></p>
							<?php } ?>
							<?php
							// Display video length
							if ( ! empty( $meta['length_formatted'] ) ) { ?>
								<p><strong><?php esc_html_e( 'Length', 'total' ); ?>: </strong> <?php echo $meta['length_formatted']; ?></p>
							<?php } ?>
						</div>

					<?php } elseif ( 'application/pdf' == $mime_type ) { ?>

						<div id="attachment-post-media" class="entry clr"><object data="<?php echo wp_get_attachment_url($post->ID); ?>" type="application/pdf" width="100%" height="600px">
						</object></div>

					<?php } else { ?>

						<div id="attachment-post-content" class="entry clr"><?php the_content(); ?></div>

					<?php } ?>

				<?php endwhile; ?>

				<?php wpex_hook_content_bottom(); ?>

			</div><!-- #content -->

			<?php wpex_hook_content_after(); ?>

		</div><!-- #primary -->

		<?php wpex_hook_primary_after(); ?>

	</div><!-- .container -->

<?php get_footer(); ?>