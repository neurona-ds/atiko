/**
 * Update Typography Customizer settings live.
 *
 * @version 4.2
 */

;( function( api, $, window, document, undefined ) {

    "use strict"

    // Bail if customizer object isn't in the DOM.
    if ( ! wp || ! wp.customize ) {
        console.log( 'wp or wp.customize objects not found.' );
        return; 
    }

    var wpexCustomizerTypography = {},
        body                     = $( 'body' ),
        head                     = $( 'head' ),
        stdFonts                 = wpexTypo.stdFonts,
        customFonts              = wpexTypo.customFonts,
        googleFontsUrl           = wpexTypo.googleFontsUrl,
        googleFontsSuffix        = wpexTypo.googleFontsSuffix;

    /**
     * Live Typography CSS
     *
     */
    wpexCustomizerTypography = {

        /**
         * Get and loop through inline css options
         *
         * @since 4.1
         */
        init : function() {

            if ( typeof wpexTypo === 'undefined' ) {
                return;
            }

            var attributes = wpexTypo.attributes;

            _.each( wpexTypo.settings, function( settings, key ) {

                var target   = settings['target'],
                    excludes = settings.exclude ? settings.exclude : [];

                _.each( attributes, function( attribute ) {

                    if ( $.inArray( attribute, excludes ) > -1 ) {
                        return;
                    }

                    var settingID = key + '_typography[' + attribute + ']';

                    wpexCustomizerTypography.setStyle( key, settingID, attribute, target );

                } );

            } );
            
        },

        /**
         * Set styles
         *
         * @since 4.1
         */
        setStyle : function( key, settingID, attribute, target ) {

            var styleId     = 'wpex-customizer-' + key + '-' + attribute,
                target      = target,
                property    = attribute;

            if ( Object.prototype.toString.call( target ) === '[object Array]' ) {
                target = target.toString(); // Convert target arrays to strings
            }

            api( settingID, function( value ) {

                value.bind( function( newval ) {

                    // Load Google font scripts
                    if ( 'font-family' == attribute ) {
                        wpexCustomizerTypography.setGoogleFonts( key, newval );
                    }

                    // Remove style
                    if ( newval === '' || typeof newval === "undefined" ) {
                        $( '#' + styleId ).remove();
                    }

                    // Build style
                    else {

                        var style  = '<style id="' + styleId + '" type="text/css">';

                            if ( typeof property === 'string' ) {

                                // Sanitize data
                                if ( 'font-size' === attribute || 'letter-spacing' === attribute ) {

                                    if ( newval.indexOf( 'px' ) == -1
                                        && newval.indexOf( 'em' ) == -1
                                    ) {
                                        newval = newval + 'px';
                                    }

                                }

                                // Add style
                                style += target + '{' + property + ':' + newval + ';}';


                            } else {

                                $.each( property, function( index, value ) {
                                    style += target + '{' + value + ':' + newval + ';}';
                                } );

                            }

                        style += '</style>';

                        // Update previewer.
                        if ( $( '#' + styleId ).length !== 0 ) {
                            $( '#' + styleId ).replaceWith( style );
                        } else {
                            $( style ).appendTo( head );
                        }

                    }

                } );

            } );

        },

        /**
         * Load Google Font
         *
         * @since 4.1
         */
        setGoogleFonts : function( key, newval ) {

            var fontScriptID = 'wpex-customizer-' + key + '-font-stylesheet';
            var link = $( '#' + fontScriptID );

            // Remove script if it already exists
            if ( link.length ) {
                link.remove();
            }

            // Return if value is empty
            if ( ! newval ) {
                return;
            }

            // Custom or standard fonts
            if ( ( $.inArray( newval, customFonts ) > -1 ) || ( $.inArray( newval, stdFonts ) > -1 ) ) {
                return;
            }

            // Google font handle + href
            var fontHandle     = newval.trim().toLowerCase().replace( " ", "-" ),
                fontScriptHref = newval.replace( " ", "%20" );
            
            fontScriptHref = fontScriptHref.replace( ",", "%2C" );
            fontScriptHref = wpexTypo.googleFontsUrl + "/css?family=" + newval +  ":" + wpexTypo.googleFontsSuffix;

            // Append Google Font if newval isn't empty
            if ( newval ) {
                head.append( '<link id="' + fontScriptID +'" rel="stylesheet" type="text/css" href="'+ fontScriptHref +'">' );
            }

        }

    };

    wpexCustomizerTypography.init();

}( wp.customize, jQuery, window, document ) );