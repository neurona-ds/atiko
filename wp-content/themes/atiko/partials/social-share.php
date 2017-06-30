<?php
/**
 * Social Share Buttons Output
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Disabled if post is password protected or if disabled
if ( ! wpex_has_social_share() ) {
	return;
}

// Custom social share shortcode
if ( $custom_social = apply_filters( 'wpex_custom_social_share', wpex_get_mod( 'social_share_shortcode', false ) ) ) : ?>
	
	<div class="wpex-social-share-wrap clr <?php if ( 'full-screen' == wpex_content_area_layout() ) echo ' container'; ?>">
		<div class="wpex-social-share position-horizontal clr"><?php echo do_shortcode( wp_kses_post( $custom_social ) ); ?></div>
	</div><!-- .wpex-social-share-wrap -->

	<?php return; ?>

<?php endif;

// Get sharing sites
$sites = wpex_social_share_sites();

// Return if there aren't any sites enabled
if ( empty( $sites ) ) {
	return;
}

// Declare main vars
$position = wpex_social_share_position();
$style    = wpex_social_share_style();
$heading  = wpex_social_share_heading();
$url      = apply_filters( 'wpex_social_share_url', wpex_get_current_url() );

// Singular data
if ( $post_id = wpex_get_current_post_id() ) {

	$title = html_entity_decode( wpex_get_esc_title() );

	// Get summary for pinterest and linkedin links
	if ( in_array( 'pinterest', $sites ) || in_array( 'linkedin', $sites ) ) {

		$summary = wp_strip_all_tags( wpex_get_excerpt( array(
			'post_id'         => $post_id,
			'length'          => '40',
			'echo'            => false,
			'ignore_more_tag' => true,
			'more'            => '',
			'context'         => 'social_share',
		) ) );

	}

}

// Most likely an archive
else {

	$title   = get_the_archive_title();
	$summary = get_the_archive_description();

} ?>

<div class="wpex-social-share-wrap clr position-<?php echo esc_attr( $position ); ?><?php if ( 'full-screen' == wpex_content_area_layout() ) echo ' container'; ?>">

	<?php
	// Display heading if enabled
	if ( 'horizontal' == $position ) : ?>

		<?php
		// Display heading
		wpex_heading( array(
			'content'		=> $heading,
			'classes'		=> array( 'social-share-title' ),
			'apply_filters'	=> 'social_share',
		) ); ?>

	<?php endif; ?>

	<ul class="wpex-social-share position-<?php echo esc_attr( $position ); ?> style-<?php echo esc_attr( $style ); ?> clr">

		<?php
		// Loop through sites
		foreach ( $sites as $site ) :

			// Twitter
			if ( 'twitter' == $site ) {

				// Get SEO meta and use instead if they exist
				if ( defined( 'WPSEO_VERSION' ) ) {
					if ( $meta = get_post_meta( $post_id, '_yoast_wpseo_twitter-title', true ) ) {
						$title = $meta;
					}
					if ( $meta = get_post_meta( $post_id, '_yoast_wpseo_twitter-description', true ) ) {
						$title = $title .': '. $meta;
						$title = $title;
					}
				}

				// Get twitter handle
				$handle = wpex_get_mod( 'social_share_twitter_handle' ); ?>

				<li class="share-twitter">
					<a href="https://twitter.com/share?text=<?php echo rawurlencode( $title ); ?>&amp;url=<?php echo rawurlencode( esc_url( $url ) ); ?><?php if ( $handle ) echo '&amp;via='. esc_attr( $handle ); ?>" title="<?php esc_html_e( 'Share on Twitter', 'total' ); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">
						<span class="fa fa-twitter"></span>
						<span class="social-share-button-text"><?php esc_html_e( 'Tweet', 'total' ); ?></span>
					</a>
				</li>

			<?php }
			// Facebook
			elseif ( 'facebook' == $site ) { ?>

				<li class="share-facebook">
					<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo rawurlencode( esc_url( $url ) ); ?>" title="<?php esc_html_e( 'Share on Facebook', 'total' ); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">
						<span class="fa fa-facebook"></span>
						<span class="social-share-button-text"><?php esc_html_e( 'Share', 'total' ); ?></span>
					</a>
				</li>

			<?php }
			// Google+
			elseif ( 'google_plus' == $site ) { ?>

				<li class="share-googleplus">
					<a href="https://plus.google.com/share?url=<?php echo rawurlencode( esc_url( $url ) ); ?>" title="<?php esc_html_e( 'Share on Google+', 'total' ); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">
						<span class="fa fa-google-plus"></span>
						<span class="social-share-button-text"><?php esc_html_e( 'Plus one', 'total' ); ?></span>
					</a>
				</li>

			<?php }
			// Pinterest
			elseif ( 'pinterest' == $site ) { ?>

				<li class="share-pinterest">
					<a href="https://www.pinterest.com/pin/create/button/?url=<?php echo rawurlencode( esc_url( $url ) ); ?>&amp;media=<?php echo wp_get_attachment_url( get_post_thumbnail_id( $post_id ) ); ?>&amp;description=<?php echo rawurlencode( $summary ); ?>" title="<?php esc_html_e( 'Share on Pinterest', 'total' ); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">
						<span class="fa fa-pinterest"></span>
						<span class="social-share-button-text"><?php esc_html_e( 'Pin It', 'total' ); ?></span>
					</a>
				</li>

			<?php }
			// LinkedIn
			elseif ( 'linkedin' == $site ) { ?>

				<li class="share-linkedin">
					<a href="https://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo rawurlencode( esc_url( $url ) ); ?>&amp;title=<?php echo rawurlencode( $title ); ?>&amp;summary=<?php echo rawurlencode( $summary ); ?>&amp;source=<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php esc_html_e( 'Share on LinkedIn', 'total' ); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">
						<span class="fa fa-linkedin"></span>
						<span class="social-share-button-text"><?php esc_html_e( 'Share', 'total' ); ?></span>
					</a>
				</li>

			<?php } ?>

		<?php endforeach; ?>

	</ul>

</div>