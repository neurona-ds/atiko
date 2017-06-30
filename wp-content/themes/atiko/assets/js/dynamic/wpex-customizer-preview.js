/**
 * Update Customizer settings live.
 *
 * @version 4.1
 */

;( function( api, $, window, document, undefined ) {

    "use strict"

    // Bail if customizer object isn't in the DOM.
    if ( ! wp || ! wp.customize ) {
        console.log( 'wp or wp.customize objects not found.' );
        return; 
    }

    // Declare variables
    var wpexinCSS         = {},
        body              = $( 'body' ),
        head              = $( 'head' ),
        siteheader        = $( '#site-header' ),
        topBarWrap        = $( '#top-bar-wrap' ),
        navWrap           = $( '#site-navigation-wrap' ),
        visibilityClasses = [
            'hidden-phone',
            'hidden-tablet',
            'hidden-tablet-landscape',
            'hidden-tablet-portrait',
            'hidden-desktop',
            'visible-desktop',
            'visible-phone',
            'visible-tablet',
            'visible-tablet-landscape',
            'visible-tablet-portrait'
        ];

    /******** General *********/

        var $arrow = $( '#site-scroll-top > span' );
        if ( $arrow.length ) {
            api( 'scroll_top_arrow', function( value ) {
                value.bind( function( newval ) {
                    $arrow.removeClass();
                    $arrow.addClass( 'fa' );
                    $arrow.addClass( 'fa-'+ newval );
                } );
            } );
        }

        var $pagination = $( '.wpex-pagination, .woocommerce-pagination' );
        if ( $pagination.length ) {
            api( 'pagination_align', function( value ) {
                value.bind( function( newval ) {
                    $pagination.removeClass( 'wpex-center wpex-left wpex-right' );
                    $pagination.addClass( 'wpex-'+ newval );
                } );
            } );
        }

    /******** Layouts *********/    

    api( 'boxed_dropdshadow', function( value ) {
        value.bind( function( newval ) {
            if ( newval ) {
                body.addClass( 'wrap-boxshadow' );
            } else {
                body.removeClass( 'wrap-boxshadow' );
            }
        } );
    } );
    
    /******** ToggleBar *********/

        api( 'toggle_bar_visibility', function( value ) {
            value.bind( function( newval ) {
                var bar = $( '.toggle-bar-btn' );
                if ( bar.length ) {
                    $.each( visibilityClasses, function( i, v ) {
                        bar.removeClass( v );
                    } );
                    bar.addClass( newval );
                }
            } );
        } );


    /******** TOPBAR *********/

        if ( topBarWrap.length ) {
            api( 'top_bar_visibility', function( value ) {
                value.bind( function( newval ) {
                    if ( topBarWrap.length ) {
                        $.each( visibilityClasses, function( i, v ) {
                            topBarWrap.removeClass( v );
                        } );
                        topBarWrap.addClass( newval );
                    }
                } );
            } );

            api( 'top_bar_fullwidth', function( value ) {
                value.bind( function( newval ) {
                    if ( newval ) {
                        topBarWrap.addClass( 'wpex-full-width' );
                    } else {
                        topBarWrap.removeClass( 'wpex-full-width' );
                    }
                } );
            } );
        }

    /******** HEADER *********/

        // Full-width header
        api( 'full_width_header', function( value ) {
            value.bind( function( newval ) {
                if ( newval && siteheader.length ) {
                    siteheader.addClass( 'wpex-full-width' );
                } else {
                    siteheader.removeClass( 'wpex-full-width' );
                }
            } );
        } );

        // Text Logo Icon
        api( 'logo_icon', function( value ) {
            value.bind( function( newval ) {
                var icon = $( '#site-logo-fa-icon' );
                if ( newval && 'none' !== newval && icon.length ) {
                    icon.show();
                    icon.removeClass();
                    icon.addClass( 'fa' );
                    icon.addClass( 'fa-'+ newval );
                } else {
                    icon.hide();
                }
            } );
        } );

        // Header Vertical Style - Fixed or not fixed
        api( 'vertical_header_style', function( value ) {
            value.bind( function( newval ) {
                if ( newval ) {
                    body.addClass( 'wpex-fixed-vertical-header' );
                } else {
                    body.removeClass( 'wpex-fixed-vertical-header' );
                }
            } );
        } );

        // Header borders
        api( 'header_menu_disable_borders', function( value ) {
            var $headerTwoNav = $( '.navbar-style-two' );
            value.bind( function( newval ) {
                if ( newval ) {
                    $headerTwoNav.addClass( 'no-borders' );
                } else {
                    $headerTwoNav.removeClass( 'no-borders' );
                }
            } );
        } );

        // Header Center
        api( 'header_menu_center', function( value ) {
            var $headerTwoNav = $( '.navbar-style-two' );
            value.bind( function( newval ) {
                if ( newval ) {
                    $headerTwoNav.addClass( 'center-items' );
                } else {
                    $headerTwoNav.removeClass( 'center-items' );
                }
            } );
        } );

        // Header aside visibility
        api( 'header_aside_visibility', function( value ) {
            value.bind( function( newval ) {
                var el = $( '#header-aside' );
                if ( el.length ) {
                    $.each( visibilityClasses, function( i, v ) {
                        el.removeClass( v );
                    } );
                    el.addClass( newval );
                }
            } );
        } );

    /******** NAVBAR *********/

        api( 'menu_dropdown_style', function( value ) {
            value.bind( function( newval ) {
                var headerClasses = siteheader.attr( 'class' ).split( ' ' );
                for(var i = 0; i < headerClasses.length; i++) {
                    if(headerClasses[i].indexOf( 'wpex-dropdown-style-' ) != -1) {
                        siteheader.removeClass(headerClasses[i]);
                    }
                }
                siteheader.addClass( 'wpex-dropdown-style-'+ newval );
            } );
        } );

        api( 'menu_dropdown_dropshadow', function( value ) {
            value.bind( function( newval ) {
                var headerClasses = siteheader.attr( 'class' ).split( ' ' );
                for(var i = 0; i < headerClasses.length; i++) {
                    if(headerClasses[i].indexOf( 'wpex-dropdowns-shadow-' ) != -1) {
                        siteheader.removeClass(headerClasses[i]);
                    }
                }
                siteheader.addClass( 'wpex-dropdowns-shadow-'+ newval );
            } );
        } );

    /******** Mobile Menu *********/

        api( 'mobile_menu_toggle_text', function( value ) {
            value.bind( function( newval ) {
                $( '.mobile-menu-toggle .wpex-text, #wpex-mobile-menu-navbar .wpex-text' ).text( newval );
            } );
        } );

        api( 'full_screen_mobile_menu_style', function( value ) {
            value.bind( function( newval ) {
                $( '.full-screen-overlay-nav' ).removeClass( 'white' ).removeClass( 'black' );
                $( '.full-screen-overlay-nav' ).addClass( newval );
            } );
        } );

    /******** Sidebar *********/
        
        api( 'has_widget_icons', function( value ) {
            value.bind( function( newval ) {
                if ( newval ) {
                    body.addClass( 'sidebar-widget-icons' );
                } else {
                    body.removeClass( 'sidebar-widget-icons' );
                }
            } );
        } );

    /******** Sidebar *********/

        api( 'sidebar_headings', function( value ) {
            value.bind( function( newval ) {
                var headings = $( '.sidebar-box .widget-title' );
                headings.each( function() {
                    $(this).replaceWith( '<' + newval +' class="widget-title">' + this.innerHTML + '</' + newval +'>' );
                } );
            } );
        } );

    /******** Blog *********/

        api( 'blog_single_header_custom_text', function( value ) {
            var title = $( 'body.single-post .page-header-title' );
            if ( title.length ) {
                var ogTitle = title.html();
                value.bind( function( newval ) {
                    if ( newval ) {
                        title.html( newval );
                    } else {
                        title.html( ogTitle );
                    }
                } );
            }
        } );

        api( 'blog_related_title', function( value ) {
            var heading = $( '.related-posts-title span.text' );
            if ( heading.length ) {
                var ogheading = heading.html();
                value.bind( function( newval ) {
                    if ( newval ) {
                        heading.html( newval );
                    } else {
                        heading.html( ogheading );
                    }
                } );
            }
        } );

    /******** Portfolio *********/

        api( 'portfolio_related_title', function( value ) {
            var heading = $( '.related-portfolio-posts-heading span.text' );
            if ( heading.length ) {
                var ogheading = heading.html();
                value.bind( function( newval ) {
                    if ( newval ) {
                        heading.html( newval );
                    } else {
                        heading.html( ogheading );
                    }
                } );
            }
        } );

    /******** Staff *********/

        api( 'staff_related_title', function( value ) {
            var heading = $( '.related-staff-posts-heading span.text' );
            if ( heading.length ) {
                var ogheading = heading.html();
                value.bind( function( newval ) {
                    if ( newval ) {
                        heading.html( newval );
                    } else {
                        heading.html( ogheading );
                    }
                } );
            }
        } );

    /******** Footer *********/

        api( 'footer_headings', function( value ) {
            value.bind( function( newval ) {
                var headings = $( '.footer-widget .widget-title' );
                headings.each( function() {
                    $(this).replaceWith( '<' + newval +' class="widget-title">' + this.innerHTML + '</' + newval +'>' );
                } );
            } );
        } );

    /******** Footer *********/

        api( 'footer_widgets_gap', function( value ) {
            var widgets = $( '#footer-widgets' );
            value.bind( function( newval ) {
                var classes = widgets.attr("class").split( ' ' );
                if ( classes ) {
                    $.each(classes, function(i, c) {
                        if (c.indexOf("gap-") == 0) {
                            widgets.removeClass(c);
                        }
                    } );
                }
                if ( newval ) {
                    widgets.addClass( 'gap-'+ newval );
                }
            } );
        } );

    /**
     * Live design options
     *
     */
    wpexinCSS = {

        /**
         * Get and loop through inline css options
         *
         * @since 4.1
         */
        init : function() {

            if ( typeof wpexStylingOps === 'undefined' ) {
                return;
            }

            //console.log( wpexStylingOps );

            _.each( wpexStylingOps, function( settings, id ) {

                wpexinCSS.setStyle( settings, id );

            } );
            
        },

        /**
         * Set styles
         *
         * @since 4.1
         */
        setStyle : function( settings, id ) {

            var styleId     = 'wpex-customizer-' + id,
                target      = settings.target,
                property    = settings.alter,
                sanitize    = settings.sanitize ? settings.sanitize : '',
                important   = settings.important ? '!important' : '',
                media_query = settings.media_query ? settings.media_query : '';

            if ( Object.prototype.toString.call( target ) === '[object Array]' ) {
                target = target.toString(); // Convert target arrays to strings
            }

            api( id, function( value ) {

                value.bind( function( newval ) {

                    // Remove style
                    if ( newval === '' || typeof newval === "undefined" ) {
                        $( '#' + styleId ).remove();
                    }

                    // Build style
                    else {

                        var style  = '<style id="' + styleId + '" type="text/css">';

                            if ( media_query ) {
                                style += '@media only screen and ' + media_query + '{';
                            }

                                if ( typeof property === 'string' ) {

                                    // Sanitize newval
                                    if ( sanitize && newval ) {

                                        if ( 'px' === sanitize ) {

                                            if ( newval.indexOf( 'px' ) == -1
                                                && newval.indexOf( 'em' ) == -1
                                                && newval.indexOf( '%' ) == -1
                                            ) {

                                                newval = parseInt( newval ); // set to integer
                                                newval = newval + 'px'; // Add px

                                            }

                                        }

                                        if ( 'font-size' === sanitize ) {

                                            if ( newval.indexOf( 'px' ) == -1
                                                && newval.indexOf( 'em' ) == -1
                                            ) {
                                                newval = newval + 'px';
                                            }

                                        }

                                    } // End sanitize 

                                    // Add style
                                    style += target + '{' + property + ':' + newval + important + ';}';


                                } else {

                                    $.each( property, function( index, value ) {
                                        style += target + '{' + value + ':' + newval + important + ';}';
                                    } );

                                }

                            if ( media_query ) {
                                style += '}';
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

        }

    };

    wpexinCSS.init();
        
} ( wp.customize, jQuery, window, document ) );