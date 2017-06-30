// Woo Product gallery lightbox for Total WordPress theme
// Used when product slider is disabled
// Copyright 2017 - All Rights Reserved
( function( $ ) {
    'use strict';

    if ( typeof wpex === 'undefined' ) {
        return;
    }

    var lightboxSettings = wpexLocalize.iLightbox;

    $( '.woocommerce-product-gallery__wrapper' ).each( function() {

        var $item = $( this ).find( '.woocommerce-product-gallery__image > a' );

        $item.iLightBox( lightboxSettings );

    } );
   
} ) ( jQuery );