/**
 * Customizer controls
 *
 * @version 4.2
 */

( function( api, $, window, document, undefined ) {

    "use strict"

    // Bail if customizer object isn't in the DOM.
    if ( ! wp || ! wp.customize ) {
        console.log( 'wp or wp.customize objects not found.' );
        return; 
    }

    // Controls
    var controls = [
        'wpex-dropdown-pages',
        'wpex-font-family',
        'wpex-fa-icon-select'
    ];

    _.each( controls, function( control ) {

        api.controlConstructor[control] = api.Control.extend( {

            ready: function() {

                this.container.find( 'select' ).chosen( {
                    width           : '100%',
                    search_contains : true
                } );

            }

        } );

    } );

} ( wp.customize, jQuery, window, document ) );