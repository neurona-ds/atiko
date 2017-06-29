<?php
/**
 * Contains utility methods for the plugin
 * 
 * @version 4.0
 */
class WPEX_Demo_Importer_Utils {

	/**
	 * Gets and returns url body using wp_remote_get
	 *
	 * @since 1.1.0
	 */
	public static function remote_get( $url ) {

		// Get data
		$response = wp_remote_get( $url );

		// Check for errors
		if ( is_wp_error( $response ) ) {
			return false;
		}

		// 404 errors
		if ( wp_remote_retrieve_response_code( $response ) == 403 ) {
			$response = wp_remote_get( 'https://raw.githubusercontent.com/wpexplorer/total-sample-data/master/demos.json' );
		}

		// Check response type
		if ( is_wp_error( $response ) || ( wp_remote_retrieve_response_code( $response ) != 200 ) ) {
			return false;
		}

		// Get remote body val
		$body = wp_remote_retrieve_body( $response );

		// Return data
		if ( ! empty( $body ) ) {
			return $body;
		} else {
			return false;
		}

	}

} ?>