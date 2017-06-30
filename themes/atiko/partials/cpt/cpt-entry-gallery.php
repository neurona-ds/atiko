<?php
/**
 * Used to display the custom post type entry gallery slider
 *
 * @package Total WordPress Theme
 * @subpackage Partials
 * @version 4.0
 */

// Get attachments
$attachments = wpex_get_gallery_ids( get_the_ID() );

// Attachments needed
if ( ! $attachments ) {
	return;
}

// Get post type
$type = get_post_type();

// Check if lightbox is enabled
$lightbox_enabled = wpex_gallery_is_lightbox_enabled() ? true : false;

// Load lightbox skin stylesheet
if ( $lightbox_enabled ) {
	wpex_enqueue_ilightbox_skin();
}

// Slider arguments
$slider_data_args = array(
	'filter_tag' => 'wpex_'. $type .'_entry_gallery',
);

// Thumbnail args
$thumb_args = apply_filters( 'wpex_'. $type .'_entry_thumbnail_args', array(
	'size'          => $type .'_archive',
	'schema_markup' => true,
) ); ?>

<div class="cpt-entry-media entry-media clr">

	<div class="gallery-format-post-slider wpex-clr">

		<div class="wpex-slider-preloaderimg">
			<?php
			// Display first image as a placeholder while the others load
			$thumb_args['attachment'] = $attachments[0];
			$thumb_args['alt'] = get_post_meta( $attachments[0], '_wp_attachment_image_alt', true );
			echo wpex_get_post_thumbnail( $thumb_args ); ?>
		</div><!-- .wpex-slider-preloaderimg -->

		<div class="wpex-slider slider-pro" <?php wpex_slider_data( $slider_data_args ); ?>>

			<div class="wpex-slider-slides sp-slides <?php if ( $lightbox_enabled ) echo 'lightbox-group'; ?>">

				<?php
				// Loop through attachments
				foreach ( $attachments as $attachment ) :

					// Get attachment data
					$attachment_data  = wpex_get_attachment_data( $attachment );
					$attachment_alt   = $attachment_data['alt'];
					$attachment_video = $attachment_data['video'];

					// Get image output
					$thumb_args['attachment'] = $attachment;
					$thumb_args['alt']        = $attachment_alt;
					$attachment_html          = wpex_get_post_thumbnail( $thumb_args );

					// Generate video
					if ( $attachment_video ) {
						$attachment_video = wpex_video_oembed( $attachment_video, 'sp-video', array(
							'youtube' => array(
								'enablejsapi' => '1',
							)
						) );
					} ?>

					<div class="wpex-slider-slide sp-slide">

						<?php
						// Display attachment video
						if ( $attachment_video ) : ?>

							<div class="wpex-slider-video responsive-video-wrap"><?php echo $attachment_video; ?></div>

						<?php
						// Display attachment image
						else : ?>

							<div class="wpex-slider-media clr">

								<?php
								// Display with lightbox
								if ( $lightbox_enabled ) :

									if ( apply_filters( 'wpex_cpt_gallery_lightbox_title', false ) ) {
										$title_data_attr = ' data-title="'. esc_attr( $attachment_alt ) .'"';
									} else {
										$title_data_attr = ' data-show_title="false"';
									} ?>

									<a href="<?php echo wpex_get_lightbox_image( $attachment ); ?>" title="<?php echo $attachment_alt; ?>" data-type="image" class="wpex-lightbox-group-item"<?php echo $title_data_attr; ?>><?php echo $attachment_html; ?></a>

								<?php
								// Display single image
								else : ?>

									<?php echo $attachment_html; ?>

									<?php if ( ! empty( $attachment_data['caption'] ) ) : ?>

										<div class="wpex-slider-caption sp-layer sp-black sp-padding clr" data-position="bottomCenter" data-show-transition="up" data-hide-transition="down" data-width="100%" data-show-delay="500">
											<?php echo wp_kses_post( $attachment_data['caption'] ); ?>
										</div><!-- .wpex-slider-caption -->

									<?php endif; ?>

								<?php endif; ?>

							</div><!-- .wpex-slider-media -->

						<?php endif; ?>

					</div><!-- .wpex-slider-slide sp-slide -->

				<?php endforeach; ?>

			</div><!-- .wpex-slider-slides .sp-slides -->

			<?php
			// Show thumbnails if enabled
			if ( apply_filters( 'wpex_'. $type .'_gallery_slider_has_thumbnails', true ) ) : ?>

				<div class="wpex-slider-thumbnails sp-thumbnails">

					<?php
					// Loop through and display thumbnails
					foreach ( $attachments as $attachment ) :

						$thumb_args['attachment'] = $attachment;
						$thumb_args['alt']        = get_post_meta( $attachments, '_wp_attachment_image_alt', true );
						$thumb_args['class']      = 'wpex-slider-thumbnail sp-thumbnail';
						echo wpex_get_post_thumbnail( $thumb_args );

					endforeach; ?>

				</div><!-- .wpex-slider-thumbnails -->

			<?php endif; ?>

		</div><!-- .wpex-slider .slider-pro -->

	</div><!-- .gallery-format-post-slider -->

</div><!-- .cpt-entry-gallery -->