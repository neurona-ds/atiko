<?php
/**
 * Custom Post Type Entry Media
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 4.0
 *
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get current post data
$post_id   = get_the_ID();
$post_type = get_post_type();

// Array of supported media
$supported_media = apply_filters( 'wpex_cpt_entry_supported_media', array(
	'video',
	'audio',
	//'gallery', @todo support by default? Needs tweaks for multiple column grids
	'thumbnail',
), $post_type );

// Video
if ( in_array( 'video', $supported_media ) && $video = wpex_get_post_video( $post_id ) ) : ?>

	<div class="cpt-entry-media entry-media clr"><?php wpex_post_video_html( $video ); ?></div>

<?php
// Audio
elseif ( in_array( 'audio', $supported_media ) && $audio = wpex_get_post_audio( $post_id ) ) : ?>

	<div class="cpt-entry-media entry-media clr"><?php wpex_post_audio_html( $audio ); ?></div>

<?php
// Gallery images
elseif ( in_array( 'gallery', $supported_media ) && wpex_post_has_gallery( $post_id ) ) :

	get_template_part( 'partials/cpt/cpt-entry-gallery' );

// Thumbnail
elseif ( in_array( 'thumbnail', $supported_media ) ) :

	// Thumbnail args
	$thumb_args = apply_filters( 'wpex_'. $post_type .'_entry_thumbnail_args', array(
		'size'          => $post_type .'_archive',
		'alt'           => wpex_get_esc_title(),
		'schema_markup' => true
	), $post_type );

	// Get thumbnail
	$thumbnail = wpex_get_post_thumbnail( $thumb_args );

	// Display featured image
	if ( $thumbnail ) :

		// Get overlay style
		$overlay = apply_filters( 'wpex_'. $post_type .'_entry_overlay_style', null );
		$overlay = $overlay ? $overlay : 'none'; // Important check, don't remove! ?>

		<div class="cpt-entry-media entry-media clr <?php echo wpex_overlay_classes( $overlay ); ?>">
			<a href="<?php wpex_permalink(); ?>" title="<?php wpex_esc_title(); ?>" rel="bookmark" class="cpt-entry-media-link <?php wpex_entry_image_animation_classes(); ?>">
				<?php echo $thumbnail; ?>
				<?php if ( $overlay ) wpex_overlay( 'inside_link', $overlay ); ?>
			</a>
			<?php wpex_overlay( 'outside_link', $overlay ); ?>
		</div>

	<?php endif; ?>

<?php endif; ?>