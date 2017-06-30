<?php
/**
 * Image attachments param
 *
 * Used to crop images to 80px to speed things up.
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function vcex_attach_images_param( $settings, $value, $tag, $single = false ) {

	$output = '';
	$param_value = wpb_removeNotExistingImgIDs( $value );
	$output .= '<input type="hidden" class="wpb_vc_param_value gallery_widget_attached_images_ids '
	           . $settings['param_name'] . ' '
	           . $settings['type'] . '" name="' . $settings['param_name'] . '" value="' . $value . '"/>';
	$output .= '<div class="gallery_widget_attached_images">';
	$output .= '<ul class="gallery_widget_attached_images_list">';
	if ( '' !== $param_value ) {
		$images = explode( ',', $value );
		if ( $images ) {
			foreach ( $images as $image ) {
				if ( is_numeric( $image ) ) {
					if ( wpex_get_mod( 'image_resizing', true ) ) {
						$thumb_src = wpex_image_resize( array(
							'attachment' => $image,
							'width'      => 80,
							'height'     => 80,
							'retina'     => false,
							'return'     => 'url',
						) );
						echo $image;
					} else {
						$thumb_src = wp_get_attachment_image_src( $image, 'thumbnail' );
						$thumb_src = isset( $thumb_src[0] ) ? $thumb_src[0] : '';
					}
				} else {
					$thumb_src = $image;
				}
				if ( $thumb_src ) {
					$output .= '
					<li class="added">
						<img rel="' . esc_attr( $image ) . '" src="' . esc_url( $thumb_src ) . '" />
						<a href="#" class="vc_icon-remove"><i class="vc-composer-icon vc-c-icon-close"></i></a>
					</li>';
				}
			}
		}
	}
	$output .= '</ul>';
	$output .= '</div>';
	$output .= '<div class="gallery_widget_site_images">';
	$output .= '</div>';
	if ( true === $single ) {
		$output .= '<a class="gallery_widget_add_images" href="#" use-single="true" title="'
		           . __( 'Add image', 'js_composer' ) . '"><i class="vc-composer-icon vc-c-icon-add"></i>' . __( 'Add image', 'js_composer' ) . '</a>'; //class: button
	} else {
		$output .= '<a class="gallery_widget_add_images" href="#" title="'
		           . __( 'Add images', 'js_composer' ) . '"><i class="vc-composer-icon vc-c-icon-add"></i>' . __( 'Add images', 'js_composer' ) . '</a>'; //class: button
	}
	return $output;

}

vc_add_shortcode_param(
	'vcex_attach_images',
	'vcex_attach_images_param'
);