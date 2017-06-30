// Deprecated in 4.1
// @todo remove file
( function( $ ) {
    'use strict';

    function wpexWooVariationLightboxReset() {

		var $zooms = $( '.product-variation-thumbs' ).find( '.zoom' );
				
		// Loop through thumbnail links
		$zooms.each( function( i ) {
			var $this = $( this );
			if ( ! $this.attr( 'href' ) ) {
				$zooms.splice(i, 1);
			}
		} );

		// Get items count
		var $itemsCount = $zooms.length;

		// Return if no items
		if ( $itemsCount < 1 ) {
			return;
		}

		// Tweak classes for single items
		if ( $itemsCount == 1 ) {
			$( '.product-variation-thumbs' ).removeClass( 'lightbox-group' );
			$zooms.removeClass( 'wpex-lightbox-group-item' ).addClass( 'wpex-lightbox' ).data( 'show_title', false );
		} else {
			$( '.product-variation-thumbs' ).addClass( 'lightbox-group' );
			$zooms.data( 'show_title', false ).addClass( 'wpex-lightbox-group-item' );
		}

		// Get lightbox data
		var $iLightboxData = $( '.lightbox-group', $( '.images' ) ).eq(0).data( 'ilightbox' );

		// Add lightbox
		wpex.iLightbox( $( '.images' ) );

	}

	/*** Variation Images ***/
	var $originalImages = $( '.product-variation-thumbs' ).html();

	$( 'body' ).on( 'wc_additional_variation_images_frontend_image_swap_callback wc_additional_variation_images_frontend_ajax_default_image_swap_callback wc_additional_variation_images_frontend_on_reset wc_additional_variation_images_frontend_on_reset_done', function( e, response, o_gallery_images, o_main_images ) {

		// Remove items
		$( '.product-variation-thumbs' ).html();

		switch( e.type ) {
			
			case 'wc_additional_variation_images_frontend_image_swap_callback':
				$( '.product-variation-thumbs' ).html( response.gallery_images );
				wpexWooVariationLightboxReset();
			break;
			
			case 'wc_additional_variation_images_frontend_ajax_default_image_swap_callback':
			break;
			
			case 'wc_additional_variation_images_frontend_on_reset':
			break;

			case 'wc_additional_variation_images_frontend_on_reset_done':
				$( '.product-variation-thumbs' ).html( $originalImages );
				wpexWooVariationLightboxReset();
			break;

		}
	} );

	/*** Single Variation
    $( '.single_variation_wrap ').on( 'show_variation hide_variation', function( e ) {

    	var $images = $( '.wpex-single-product-images' );
    	if ( ! $images.length ) return;

		var $slider = $images.find( '.wpex-slider' );

		// Slider swaps
		if ( $slider.length ) {
			var $sliderImages = $slider.find( 'img' );
			var $sliderData   = $slider.data( 'sliderPro' );
			if ( $sliderData ) {
				$sliderData.update();
				$sliderData.gotoSlide( 0 );
				//wpex.iLightbox( $images );
				$sliderImages.each( function() {
					var $this = $( this );
					var $src  = $this.attr( 'src' );
					//$this.parent().find( 'a' ).attr( 'href', $src ).attr( "data-o_href", "").attr("data-o_href", m);
					//jQuery('[data-rel="ilightbox[product]"]').removeClass("ilightbox-enabled").iLightBox().destroy();
					//SF.lightbox.init()
				} );
			}
		}

		// Single image swaps
		else if( $images.length ) {
			var $lightboxImg = $images.find( '.zoom' );
			if ( $lightboxImg.length ) {
				//console.log( window.iLightBox );
				//window.iLightBox.destroy();
				//wpex.iLightbox( $images );
			}

		}

    } ); ***/

} ) ( jQuery );