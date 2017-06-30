<?php
/**
 * Function used to resize and crop images
 * 
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start Class
if ( ! class_exists( 'WPEX_Image_Resize' ) ) {

	class WPEX_Image_Resize {
		private static $instance = null;

		/**
		 * No initialization allowed
		 */
		private function __construct() {}

		/**
		 * No cloning allowed
		 */
		private function __clone() {}

		/**
		 * No unserializing allowed
		 */
		private function __wakeup() {}

		/**
		 * Singleton
		 *
		 * @since 4.0
		 */
		public static function getInstance() {

			if ( self::$instance == null ) {
                self::$instance = new self;
            }

			return self::$instance;

		}

		/**
		 * Run image resizing function
		 *
		 * @since 4.0
		 */
		public function process( $args ) {

			// Sanitize args
			$args = self::sanitize_args( $args );

			// Return if args are null
			if ( ! $args ) {
				return;
			}

			// Extract args
			extract( $args );

			// Define intermediate size
			$intermediate_size = $size;

			// Return full size (no extra checks needed and no retina required)
			if ( 'full' == $size ) {

				if ( $retina ) {
					return;
				}

				if ( $image_src ) {
					return $image_src;
				}

				if ( $src = wp_get_attachment_image_src( $attachment, 'full', false ) ) {
					$result = self::parse_attachment_src( $src );
					return self::parse_result( $result, $args );
				}

			}

			// Get upload path & dir
			$upload_info = wp_upload_dir();
			$upload_dir  = $upload_info[ 'basedir' ];
			$upload_url  = $upload_info[ 'baseurl' ];

			// Get image path
			if ( $attachment ) {

				$meta     = wp_get_attachment_metadata( $attachment );
				$img_path = get_attached_file( $attachment );
				$rel_path = str_replace( $upload_dir, '', $img_path );

			} elseif ( $image ) {

				// Set correct url scheme
				$image = set_url_scheme( $image );

				// Image isn't in uploads so we can't dynamically resize it,
				// so return full url and empty width/height
				if ( strpos( $image, $upload_url ) === false ) {
					return self::parse_result( array(
						'url'    => $image,
						'width'  => '',
						'height' => '',
					), $args );
				}

				$meta     = ''; // no meta for direct image input
				$rel_path = str_replace( $upload_url, '', $image );
				$img_path = $upload_dir . $rel_path;

			}

			// Make sure file exists
			if ( ! file_exists( $img_path ) ) {
				if ( ! empty( $image ) ) {
					return $image;
				}
				return;
			}

			// Get image info
			$info = pathinfo( $img_path );
			$ext  = $info['extension'];
			list( $orig_w, $orig_h ) = getimagesize( $img_path ); // Better then getting from meta as meta could be wrong
			$meta = is_array( $meta ) ? $meta : array( $meta ); // Sanitize meta to prevent errors

			// Define empty vars
			$img_url = '';

			// Check what the image size would be after resizing/cropping
			$dst_dims = image_resize_dimensions( $orig_w, $orig_h, $width, $height, $crop );
			$dst_w    = $dst_dims[4];
			$dst_h    = $dst_dims[5];

			// Define crop_suffix for custom crop locations
			// Used for destination and for saving meta
			$crop_suffix = '';
			if ( $crop && is_array( $crop ) ) {
				$crop_suffix = array_combine( $crop, $crop );
				$crop_suffix = implode( '-', $crop_suffix );
			}

			// Define Intermediate size name
			// Must be defined early on so retina image dst_w and dst_he matches non-retina image
			if ( $meta ) {
				
				// If no size is defined then intermediate size should be the crop_suffix + height&width
				if ( ! $intermediate_size ) {
					if ( $crop_suffix ) {
						$intermediate_size = 'wpex_'. $crop_suffix . '-'. $dst_w .'x'. $dst_h;
					} else {
						$intermediate_size = 'wpex_'. $dst_w .'x'. $dst_h;
					}
				}

				// Retina intermediate size should be same as intermediate size with added @2x
				if ( $intermediate_size && $retina ) {
					$intermediate_size = $intermediate_size .'@2x';
				}

			}
			
			// Check that the file size is smaller then the destination size
			// If it's not smaller then we don't have to do anything but return the original image
			if ( $orig_w > $dst_w || $orig_h > $dst_h ) {

				// Define image saving destination
				$dst_rel_path = str_replace( '.'. $ext, '', $rel_path );

				// Suffix
				$suffix = $dst_w .'x'. $dst_h;

				// Generate correct suffix based on crop_suffix and destination sizes
				$suffix = $crop_suffix ? $crop_suffix .'-'. $suffix : $suffix;

				// Check original image destination
				$destfilename = $upload_dir . $dst_rel_path .'-'. $suffix .'.'. $ext;

				// Retina should only be generated if the target image size already exists
				// No need to create a retina version for a non-existing image
				if ( $retina && file_exists( $destfilename ) && getimagesize( $destfilename ) ) {

					// Return if the destination width or height aren't at least 2x as big
					if ( ( $orig_w < $dst_w * 2 ) || ( $orig_h < $dst_h * 2 ) ) {
						return;
					}

					// Set retina version to @2x the output of the default cropped image
					$dst_dims = image_resize_dimensions( $orig_w, $orig_h, $dst_w * 2, $dst_h * 2, $crop );
					$dst_w    = $dst_dims[4];
					$dst_h    = $dst_dims[5];

					// Set correct resize dimensions for retina images
					$width  = $width * 2;
					$height = $height * 2;

					// Add retina sufix
					$suffix = $suffix .'@2x';

					// Update destfilename to include retina suffix
					$destfilename = $upload_dir . $dst_rel_path .'-'. $suffix .'.'. $ext;

				}

				// If file exists set image url else generate image
				if ( file_exists( $destfilename ) && getimagesize( $destfilename ) ) {

					$img_url = $upload_url . $dst_rel_path . '-' . $suffix . '.' . $ext;
			
				}

				// Cached image doesn't exist so lets try and create it
				// Can not use image_make_intermediate_size() unfortunately because it
				// does not allow for custom naming conventions :(
				else {
					
					$editor = wp_get_image_editor( $img_path );
					
					// Create image
					if ( ! is_wp_error( $editor ) && ! is_wp_error( $editor->resize( $width, $height, $crop ) ) ) {

						// Get resized file
						$filename = $editor->generate_filename( $suffix );
						$editor   = $editor->save( $filename );

						// Set new image url from resized image
						if ( ! is_wp_error( $editor ) ) {
							$path    = str_replace( $upload_dir, '', $editor['path'] );
							$img_url = $upload_url . $path;
						}

					}

				} // End cache check

			} // End size check

			// If dynamic image couldn't be created return original image
			if ( ! $img_url ) {

				if ( $image_src ) {
					return $image_src;
				}

				if ( $src = wp_get_attachment_image_src( $attachment, 'full', false ) ) {
					$result = self::parse_attachment_src( $src );
					return self::parse_result( $result, $args );
				}

			}

			// Update attachment meta data if custom size not found (fallback for images already created)
			// or if the sizes don't match up
			if ( $meta ) {
		
				// Update meta if needed
				if ( ! array_key_exists( $intermediate_size, $meta['sizes'] )
					|| ( $dst_w != $meta['sizes'][$intermediate_size]['width'] || $dst_h != $meta['sizes'][$intermediate_size]['height'] )
				) {

					// Get destination filename
					$dst_filename = basename( str_replace( $upload_url .'/', '', $img_url ) );

					// Check correct mime type
					$mime_type = wp_check_filetype( $img_url );
					$mime_type = isset( $mime_type['type'] ) ? $mime_type['type'] : '';

					// Add cropped image to image meta
					$meta['sizes'][$intermediate_size] = array(
						'file'         => $dst_filename,
						'width'        => $dst_w,
						'height'       => $dst_h,
						'mime-type'    => $mime_type,
						'wpex_dynamic' => true,
					);

					// Update meta
					wp_update_attachment_metadata( $attachment, $meta );

					// Set intermediate var to true
					$is_intermediate = true;

				}

				// Return image url pased through the 'wp_get_attachment_image_src' wp filter
				// This should provide better support to 3rd party image plugins.
				if ( ! $image_src ) {
					$src = wp_get_attachment_image_src( $attachment, $intermediate_size, false );
					if ( isset( $src[0] ) ) {
						$img_url = $src[0];
					}
				}

			}

			// Return result
			if ( $image_src || 'src' == $return ) {
				return array( $img_url, $dst_w, $dst_h, $is_intermediate );
			} else {
				return self::parse_result( array(
					'url'             => $img_url,
					'width'           => $dst_w,
					'height'          => $dst_h,
					'is_intermediate' => $is_intermediate,
				), $args );
			}

		}

		/**
		 * Sanitize arguments
		 *
		 * @since 1.0.0
		 */
		private static function sanitize_args( $args ) {

			// Default args
			$defaults = array(
				'attachment'      => '',
				'image'           => '',
				'width'           => '9999',
				'height'          => '9999',
				'crop'            => 'center-center',
				'retina'          => false,
				'return'          => 'array',
				'size'            => '',
				'is_intermediate' => false,
				'image_src'       => null, // Allows cropping inside the wp_get_attachment_image_src filter
			);

			// Parse args
			$args = wp_parse_args( $args, $defaults );

			// Return null if there isn't any image or attachment
			if ( ! $args['attachment'] && ! $args['image'] ) {
				return null;
			}

			// Sanitize width and height to make sure they are integers
			$args['width']  = intval( $args['width'] );
			$args['height'] = intval( $args['height'] );

			// Check width if empty or greater then 9999 set to 9999
			if ( ! $args['width'] || $args['width'] >= 9999 ) {
				$args['width'] = 9999;
			}

			// Check height if empty or greater then 9999 set to 9999
			if ( ! $args['height'] || $args['height'] >= 9999 ) {
				$args['height'] = 9999;
			}

			// If image width and height equal '9999' simply return "full" size
			if ( 9999 == $args['width'] && 9999 == $args['height'] ) {
				$args['size'] = 'full';
			}

			// Crop can't be empty - needs to default to center-center - important initial check!!!
			if ( empty( $args['crop'] ) ) {
				$args['crop'] = 'center-center';
			}

			// If height is greater then 9999 set crop to false
			if ( $args['height'] >= '9999' || $args['width'] >= '9999' ) {
				$args['crop'] = false;
			}

			// Sanitize crop if not false
			if ( $args['crop'] ) {

				// center-center crop needs to be set to true because it's the default crop location
				// and prevent's a prefix from being added to the image suffix
				if ( 'center-center' == $args['crop'] || 'true' == $args['crop'] || true === $args['crop'] ) {

					$args['crop'] = true;

				}

				// Convert crop location into array
				else {

					// Set crop location if defined in format 'left-top' and turn into array
					$crop_locations = array_flip( wpex_image_crop_locations() );

					if ( in_array( $args['crop'], $crop_locations ) ) {
						$args['crop'] = explode( '-', $args['crop'] );
					}

				}

			}

			// Sanitize return
			if ( $args['image_src'] ) {
				$args['return'] = 'src';
			}

			// Set correct args
			return $args;

		}

		/**
		 * Parses the attachment src
		 *
		 * @since 4.0
		 */
		public static function parse_attachment_src( $src ) {
			return array(
				'url'             => isset( $src[0] ) ? $src[0] : '',
				'width'           => isset( $src[1] ) ? $src[1] : '',
				'height'          => isset( $src[2] ) ? $src[2] : '',
				'is_intermediate' => isset( $src[3] ) ? $src[3] : '',
			);
		}

		/**
		 * Return correct result
		 *
		 * @since 4.0
		 */
		public static function parse_result( $result, $args ) {
			if ( 'array' == $args['return'] ) {
				return $result;
			} else {
				return $result['url'];
			}
		}

	}
	
}

// Helper function for resizing images using the WPEX_Image_Resize class
if ( ! function_exists( 'wpex_image_resize' ) ) {
	function wpex_image_resize( $args ) {
		$new = WPEX_Image_Resize::getInstance();
		return $new->process( $args );
	}
}