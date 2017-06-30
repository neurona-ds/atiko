<?php
/**
 * Fetch Instagram feed
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 3.6.0
 */

// Prevent direct file access
if ( ! defined ( 'ABSPATH' ) ) {
	exit;
}

function wpex_fetch_instagram_feed( $username = '', $slice = 4 ) {

	// Sanitize input and get transient
	$username           = strtolower( $username );
	$sanitized_username = sanitize_title_with_dashes( $username );
	$transient_name     = 'wpex-instagram-feed-'. $sanitized_username .'-'. $slice;
	$instagram          = get_transient( $transient_name );

	// Clear transient
	if ( ! empty( $_GET['wpex_clear_transients'] ) ) {
		$instagram = delete_transient( $transient_name );
	}

	// Fetch instagram items
	if ( ! $instagram ) {

		$remote = wp_remote_get( 'http://instagram.com/'. trim( $username ) );

		if ( is_wp_error( $remote ) ) {
			return new WP_Error( 'site_down', esc_html__( 'Unable to communicate with Instagram.', 'total' ) );
		}

		if ( 200 != wp_remote_retrieve_response_code( $remote ) ) {
			return new WP_Error( 'invalid_response', esc_html__( 'Instagram did not return a 200.', 'total' ) );
		}

		$shards      = explode( 'window._sharedData = ', $remote['body'] );
		$insta_json  = explode( ';</script>', $shards[1] );
		$insta_array = json_decode( $insta_json[0], TRUE );

		if ( ! $insta_array ) {
			return new WP_Error( 'bad_json', esc_html__( 'Instagram has returned invalid data.', 'total' ) );
		}

		// Old style
		if ( isset( $insta_array['entry_data']['UserProfile'][0]['userMedia'] ) ) {
			$images = $insta_array['entry_data']['UserProfile'][0]['userMedia'];
			$type = 'old';

		}

		// New style
		elseif ( isset( $insta_array['entry_data']['ProfilePage'][0]['user']['media']['nodes'] ) ) {
			$images = $insta_array['entry_data']['ProfilePage'][0]['user']['media']['nodes'];
			$type = 'new';
		}

		// Invalid json data
		else {
			return new WP_Error( 'bad_json_2', esc_html__( 'Instagram has returned invalid data.', 'total' ) );
		}

		// Invalid data
		if ( ! is_array( $images ) ) {
			return new WP_Error( 'bad_array', esc_html__( 'Instagram has returned invalid data.', 'total' ) );
		}

		$instagram = array();

		switch ( $type ) {

			case 'old':

				foreach ( $images as $image ) {

					if ( $image['user']['username'] == $username ) {
						$image['link'] = preg_replace( "/^http:/i", "", $image['link'] );
						$image['images']['thumbnail'] = preg_replace( "/^http:/i", "", $image['images']['thumbnail'] );
						$image['images']['standard_resolution'] = preg_replace( "/^http:/i", "", $image['images']['standard_resolution'] );
						$image['images']['low_resolution'] = preg_replace( "/^http:/i", "", $image['images']['low_resolution'] );
						$instagram[] = array(
							'description' => $image['caption']['text'],
							'link'        => $image['link'],
							'time'        => $image['created_time'],
							'comments'    => $image['comments']['count'],
							'likes'       => $image['likes']['count'],
							'thumbnail'   => $image['images']['thumbnail'],
							'large'       => $image['images']['standard_resolution'],
							'small'       => $image['images']['low_resolution'],
							'type'        => $image['type'],
						);
					}
				}

			break;

			default:

				foreach ( $images as $image ) {

					$image['thumbnail_src'] = preg_replace( '/^https?\:/i', '', $image['thumbnail_src'] );
					$image['display_src'] = preg_replace( '/^https?\:/i', '', $image['display_src'] );

					$image['thumbnail_src'] = preg_replace( '/^https?\:/i', '', $image['thumbnail_src'] );
					$image['display_src'] = preg_replace( '/^https?\:/i', '', $image['display_src'] );

					// handle both types of CDN url
					if ( (strpos( $image['thumbnail_src'], 's640x640' ) !== false ) ) {
						$image['thumbnail'] = str_replace( 's640x640', 's160x160', $image['thumbnail_src'] );
						$image['small'] = str_replace( 's640x640', 's320x320', $image['thumbnail_src'] );
					} else {
						$urlparts = wp_parse_url( $image['thumbnail_src'] );
						$pathparts = explode( '/', $urlparts['path'] );
						array_splice( $pathparts, 3, 0, array( 's160x160' ) );
						$image['thumbnail'] = '//' . $urlparts['host'] . implode('/', $pathparts);
						$pathparts[3] = 's320x320';
						$image['small'] = '//' . $urlparts['host'] . implode('/', $pathparts);
					}

					$image['large'] = $image['thumbnail_src'];

					if ( $image['is_video'] == true ) {
						$type = 'video';
					} else {
						$type = 'image';
					}

					$instagram[] = array(
						'description'   => esc_html__( 'Instagram Image', 'total' ),
						'link'		    => '//instagram.com/p/' . $image['code'],
						'time'		    => $image['date'],
						'comments'	    => $image['comments']['count'],
						'likes'		    => $image['likes']['count'],
						'thumbnail_src' => isset( $image['thumbnail_src'] ) ? $image['thumbnail_src'] : '',
						'display_src'   => $image['display_src'],
						'thumbnail'	 	=> $image['thumbnail'],
						'small'         => $image['small'],
						'large'         => $image['large'],
						'original'      => $image['display_src'],
						'type'          => $type,
					);

				}

			break;

		}

		// Set transient if not empty
		if ( ! empty( $instagram ) ) {
			$instagram = serialize( $instagram );
			set_transient(
				$transient_name,
				$instagram,
				apply_filters( 'wpex_instagram_widget_cache_time', HOUR_IN_SECONDS*2 )
			);
		}

	}

	// Return array
	if ( ! empty( $instagram )  ) {
		if ( ! is_array( $instagram ) && 1 != $instagram ) {
			$instagram = unserialize( $instagram );
		}
		if ( is_array( $instagram ) ) {
			return array_slice( $instagram, 0, $slice );
		}
	}

	// No images returned
	else {

		return new WP_Error( 'no_images', esc_html__( 'Instagram did not return any images.', 'total' ) );

	}

}