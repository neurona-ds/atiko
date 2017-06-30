( function( $ ) {
	'use strict';
	$( document ).on( 'ready', function() {
		var $settings = wpexAnimsition;
		$settings.inDuration  = parseInt( $settings.inDuration );
		$settings.outDuration = parseInt( $settings.outDuration );
		$( '.animsition' ).animsition( $settings );
	} );
} ) ( jQuery );