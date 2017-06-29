// Woo Product gallery lightbox for Total WordPress theme
// Used when product slider is enabled
// Copyright 2017 - All Rights Reserved
( function( $ ) {
    'use strict';

    if ( typeof wpex === 'undefined' ) {
        return;
    }

    var lightboxSettings = wpexLocalize.iLightbox;

    function wpexWcGalleryLightboxSet() {

        var $gallery = $( '.woocommerce-product-gallery__wrapper' );

        $( $gallery ).each( function() {

            var $this  = $( this );

            $this.css( 'cursor', 'pointer' );

            $this.on( 'click', function( event ) {

                var $items  = $this.find( '.woocommerce-product-gallery__image > a' );
                var images  = [];
                var active = '';

                $items.each( function() {
                    var $this   = $( this );
                    var $parent = $this.parent();
                    if ( ! $parent.hasClass( 'clone' ) ) {
                        var $href = $this.attr( 'href' );
                        if ( $parent.hasClass( 'flex-active-slide' ) ) {
                            active = $href;
                        }
                        images.push( $href );
                    }
                } );

                if ( images.length > 1 ) {

                    var activeIndex = $.inArray( active, images );

                    lightboxSettings.startFrom = parseInt( activeIndex );

                    $.iLightBox( images, lightboxSettings );

                } else {

                    lightboxSettings.controls = false;
                    lightboxSettings.infinite = false;

                    $.iLightBox( images, lightboxSettings );

                }

            } );

        } );

    }

    wpex.config.$window.on( 'load', function() {
        wpexWcGalleryLightboxSet();
    } );
   
} ) ( jQuery );