( function( $ ) {

	"use strict";

	$( document ).ready(function() {
		TotalThemeDemoImporter.init();
	} );

	var TotalThemeDemoImporter = {

		// Holds data about each type of content that needs to be imported.
		// Example:
		// 'xml_data': {
		//	 'input_name': 'wpex_import_xml',
		//	 'action': 'wpex_post_import_xml_data',
		//	 'method': 'ajax_post_import_xml_data',
		//	 'preloader': 'Importing XML Data'
		// }
		importData: {},

		allowPopupClosing: true,

		init: function() {
			var that = this;

			// Lazy load the screenshots
			$( 'img.wpex-lazyload' ).lazyload({
				effect: "fadeIn"
			} );

			// When a screenshot is clicked, get the name of the corresponding demo
			// and load the corresponding content for the popup
			$( '.wpex-demo .theme-screenshot' ).click( function( event ) {
				event.preventDefault();

				// show spinner
				$( this ).find( '.wpex-demo-spinner' ).addClass( 'wpex-visible-spinner' );
				
				that.loadDemo( $( this ).parents( '.wpex-demo' ).attr( 'data-demo' ) );
			} );

			this.loadImportData();

			// instantiate isotope
			var demosSelector = $( '.wpex-demos-select' ).isotope({
				itemSelector: '.wpex-demo',
				layoutMode: 'fitRows'
			} );

			// filter demos based on the search box text
			$( '.wpex-demos-search-box' ).on('input', function() {
				var currentInput = $( this ).val().toLowerCase();

				demosSelector.isotope({
					filter: function() {
						return $( this ).find( '.wpex-demo-name' ).text().toLowerCase().indexOf( currentInput ) !== -1;
					}
				} );

				// load images for the currently visible demos
				setTimeout( function() {
					$( 'img.wpex-lazyload' ).lazyload();
				}, 500 );

				// remove the category selection
				if ( currentInput ) {
					$( '.wpex-selected-category' ).removeClass( 'wpex-selected-category' );
				} else {
					$( '.wpex-all-cat' ).addClass( 'wpex-selected-category' );
				}


			} );

			// filter demos based on the selected category
			$( '.wpex-demos-categories select' ).on( 'change', function() {
				var currentCategory = $( this ).val().toLowerCase();

				demosSelector.isotope( {

					filter: function() {

						if ( currentCategory === 'all' ) {
							return true;
						}

						return $( this ).attr( 'data-categories' ).toLowerCase().indexOf( currentCategory ) !== -1;
					}
					
				} );

				// load images for the currently visible demos
				setTimeout( function() {
					$( 'img.wpex-lazyload' ).lazyload();
				}, 500 );

			} );

		},

		// Loads an object that contains data about each type of importable content.
		// The data consists of the name of the AJAX action, the name of the HTML
		// checkbox field (which are the data we need in JavaScript), and more.
		loadImportData:function() {
			var that = this;

			$.ajax({
				url: wpex_js_vars.ajaxurl,
				type: 'get',

				data: {
					action: 'wpex_get_import_data',
					demo_name: name,
					get_import_data_nonce: wpex_js_vars.get_import_data_nonce
				},

				complete: function( data ) {
					that.importData = $.parseJSON( data.responseText );
				}
			} );
		},

		// Load the popup content of the demo with AJAX
		loadDemo: function( name ) {
			var that = this;

			$.ajax({
				url: wpex_js_vars.ajaxurl,
				type: 'get',

				data: {
					action: 'wpex_get_selected_demo_data',
					demo_name: name,
					get_selected_demo_data_nonce: wpex_js_vars.get_selected_demo_data_nonce
				},

				complete: function( data ) {
					that.initPopup( data );

					// hide spinner
					$( '.wpex-visible-spinner' ).removeClass( 'wpex-visible-spinner' );
				}
			} );
		},

		// Launch the popup and populate it with the appropriate content for the selected demo
		initPopup: function( data ) {
			var that = this;

			// attach the HTML
			$( data.responseText ).appendTo( $( '.wpex-submit-popup-content' ) );
			
			// Show the popup
			$( '.wpex-submit-popup-wrap' ).toggleClass( 'wpex-show' );

			// When 'Next' is clicked, remove the 'notices' and show the 'content to import' 
			$( '.wpex-popup-selected-next' ).click( function( event ) {
				event.preventDefault();

				if ( $( this ).hasClass( 'disabled' ) === false ) {
					$( '.wpex-selected-notice' ).hide();
					$( '.wpex-selected-content-to-import' ).show();
				}
			} );

			// Hide the popup when 'Close' is clicked
			$( '.wpex-popup-selected-close' ).click( function( event ) {
				event.preventDefault();

				that.closePopup();
			} );

			// when the background behind the popup is clicked, close the popup
			$( '.wpex-submit-popup-wrap' ).click( function( event ){
				if ( $( event.target ).hasClass( 'wpex-submit-popup' ) && that.allowPopupClosing === true ) {
					that.closePopup();
				}
			} );

			// Enable and disable the 'Import XML Attachments' checkbox based on
			// whether 'Import XML Data' is checked
			$( '#wpex_import_xml' ).change( function( event ) {
				if( $( this ).is( ':checked' ) === false ) {
					$( '#wpex_import_xml_attachments' ).attr({ 'checked': false, 'disabled': 'disabled' } );
				} else {
					$( '#wpex_import_xml_attachments' ).attr({ 'checked': true }).removeAttr( 'disabled' );
				}
			} );

			// Handle the submit action
			$( '#wpex-demo-import-form' ).submit(function( event ) {
				event.preventDefault();

				// Get the name of the demo and the security nonce
				var demo = $( this ).find( '[name="wpex_import_demo"]' ).val(),
					nonce = $( this ).find( '[name="wpex_import_demo_nonce"]' ).val(),
					contentToImport = [];

				// Iterate through the form's input fields and check which fields are selected
				// in order to determine what content will be imported
				$( this ).find( 'input[type="checkbox"]' ).each( function() {
					if ( $( this ).is( ':checked' ) === true ) {
						contentToImport.push( $( this ).attr( 'name' ) );
					}
				} );

				// Hide the checkboxes and show the importing preloader
				$( '.wpex-selected-content-to-import' ).hide();
				$( '.wpex-preloader' ).show();

				// Start importing the content
				that.importContent({
					demo: demo,
					nonce: nonce,
					contentToImport: contentToImport,
					isXML: $( '#wpex_import_xml' ).is( ':checked' )
				} );
			} );

			// Handle the installation/activation of the plugin
			$( '.activate-plugin, .install-plugin' ).click(function( event ) {
				event.preventDefault();

				if ( $( this ).hasClass( 'disabled' ) ) {
					return;
				}

				// Get a reference ot the link
				var $link = $( this ),

					$pluginRow = $link.parents( '.wpex-required-plugin' ),

					// Parse the URL of the link in order to extract all the passed variabled
					url = $.lightURLParse( $link.attr( 'href' ) ),

					// Get a reference to the HTML field which will display whether the plugin was activated or not
					$actionResult = $link.parents( '.wpex-required-plugin' ).find( '.wpex-plugin-action-result' ),

				action;

				// Assign the appropriate AJAX action based on which link was clicked
				if ( url.action === 'install-plugin' ) {
					action = 'wpex_post_install_plugin';
				} else if ( url.action === 'activate' ) {
					action = 'wpex_post_activate_plugin';
				}

				// Disable the installation/activation links for the other plugins
				$( '.wpex-required-plugin a' ).addClass( 'disabled' );

				var activationLimit,
					timerStart = Date.now(),
					preloaderDots = '',
					preloaderMessage = url.action === 'install-plugin' ? wpex_strings.installingPlugin : wpex_strings.activatingPlugin,
					preloaderInterval = setInterval(function() {
						$actionResult.text( preloaderMessage + preloaderDots );
						preloaderDots = preloaderDots.length === 5 ? '' : preloaderDots + '.';
				}, 200);

				this.allowPopupClosing = false;

				// Tell the server to activate the plugin
				var ajaxRequest = $.ajax({
					url: wpex_js_vars.ajaxurl,
					type: 'post',
					data: {
						action: action,
						wpex_plugin_nonce: url._wpnonce,
						wpex_plugin_slug: url.plugin
					},
					complete: function( data ) {
						clearInterval( preloaderInterval );
						clearTimeout( activationLimit );

						that.allowPopupClosing = true;
						
						// Display the result of the action
						if ( data.responseText === 'successful installation' || data.responseText === 'successful activation' ) {

							// Replace the link with simple text
							$link.after( '<span>' + $link.text() + '</span>' ).remove();

							// Display the 'success' icon
							$actionResult.empty().removeClass().addClass( 'wpex-plugin-action-success' );

							// Unmark this plugin as required
							$pluginRow.removeClass( 'wpex-required-plugin' );
						} else if ( data.status === 500 ) {
							$actionResult.empty().text( wpex_js_vars.plugin_failed_activation_memory ).addClass( 'wpex-plugin-action-failed' );
						} else if ( typeof data.responseText !== 'undefined' && data.responseText.indexOf( 'target' ) !== -1 ) {
							$actionResult.empty().html( data.responseText ).addClass( 'wpex-plugin-action-failed' );
						} else {
							$actionResult.empty().html( wpex_js_vars.plugin_failed_activation ).addClass( 'wpex-plugin-action-failed' );
						}

						// Re-enable all the links
						$( '.wpex-required-plugin .disabled' ).removeClass( 'disabled' );

						// If there are no required plugins left, re-enable the 'next' button
						if ( $( '.wpex-required-plugin' ).length === 0 ) {
							$( '.wpex-popup-selected-next' ).removeClass( 'disabled' );
						}

						var actionType = url.action === 'install-plugin' ? 'installed' : 'activated';

						console.log( 'The plugin was ' + actionType + ' in ' + ( ( Date.now() - timerStart ) / 1000 ).toString() + ' seconds.' );
					}
				} );
				
				// Set a time limit of 30 seconds for the activation process. If the plugin
				// doesn't activate in that interval, display an error message and allow the
				// user to try again.
				if ( url.action === 'activate' || url.action === 'install-plugin' ) {
					activationLimit = setTimeout(function() {

						// Abort the AJAX request
						ajaxRequest.abort();

						// Allow the popup to be closed
						that.allowPopupClosing = true;

						// Display an error message
						$actionResult.empty().html( wpex_js_vars.plugin_failed_activation_retry ).addClass( 'wpex-plugin-action-failed' );

						// Re-enable all the links
						$( '.wpex-required-plugin .disabled' ).removeClass( 'disabled' );
					}, 30000);
				}
			} );
		},

		// Close the popup and remove the loaded HTML content
		closePopup: function() {
			$( '.wpex-submit-popup-wrap' ).removeClass( 'wpex-show' );
			
			$( '.wpex-submit-popup-content' ).one( 'transitionend', function() {
				$( '.wpex-submit-popup-content' ).empty();
			} );
		},

		// Recursive function that will import the selected content
		importContent: function( importData ) {
			var that = this,
				currentContent,
				importingLimit,
				timerStart = Date.now(),
				ajaxData = {
					wpex_import_demo: importData.demo,
					wpex_import_demo_nonce: importData.nonce
				};

			this.allowPopupClosing = false;

			// When all the selected content has been imported
			if ( importData.contentToImport.length === 0 ) {
				
				// show the "import complete" screen after 1 second
				setTimeout(function() {
					$( '.wpex-preloader' ).hide();
					$( '.wpex-import-complete' ).show();
				}, 1000);

				// Notify the server that the importing process is complete
				$.ajax({
					url: wpex_js_vars.ajaxurl,
					type: 'post',
					data: {
						action: 'wpex_post_import_complete',
						wpex_import_demo: importData.demo,
						wpex_import_demo_nonce: importData.nonce,
						wpex_import_is_xml: importData.isXML
					},
					complete: function( data ) {
						
					}
				} );

				this.allowPopupClosing = true;

				// Stop the recursive function
				return;
			}

			// Iterate through the list of importable content in order to get some data for
			// the content that was selected to be imported.
			for ( var key in this.importData ) {

				// Check if the current item in the iteration is in the list of importable content
				var contentIndex = $.inArray( this.importData[ key ][ 'input_name' ], importData.contentToImport );

				// If it is:
				if ( contentIndex !== -1 ) {

					// Get a reference to the current content
					currentContent = key;

					// Remove the current content from the list of remaining importable content
					importData.contentToImport.splice( contentIndex, 1 );

					// Get the AJAX action name that corresponds to the current content
					ajaxData.action = this.importData[ key ]['action'];

					// If the current content is 'XML Data' check if 'XML Attachments' is also selected
					// because they will need to be imported at the same time					
					if ( key === 'xml_data' ) {
						var xmlAttachmentsIndex = $.inArray( 'wpex_import_xml_attachments', importData.contentToImport );
						
						if ( xmlAttachmentsIndex !== -1 ) {
							importData.contentToImport.splice( xmlAttachmentsIndex, 1 );
							ajaxData.wpex_import_xml_attachments = 'true';
						}
					}

					// After an item is found get out of the loop and execute the rest of the function
					break;
				}
			}

			// Tell the user which content is currently being imported
			$( '.wpex-import-status' ).append( '<p class="wpex-importing">' + this.importData[ currentContent ]['preloader'] + '</p>' );

			// Tell the server to import the current content
			var ajaxRequest = $.ajax({
				url: wpex_js_vars.ajaxurl,
				type: 'post',
				data: ajaxData,
				complete: function( data ) {
					clearTimeout( importingLimit );

					// Indicates if the importing of the content can continue. It will not, if a serious
					// error is encountered
					var continueProcess = true;

					if ( data.status ) {
						console.log( 'Response status: ' + data.status );
					}

					// Check if the importing of the content was successful or if there was any error
					if ( data.status === 500 || data.status === 502 || data.status === 503 ) {
						$( '.wpex-importing' )
							.addClass( 'wpex-importing-failed' )
							.removeClass( 'wpex-importing' )
							.text( wpex_js_vars.content_importing_error + ' '+ data.status );
					} else if ( data.responseText.indexOf( 'successful import' ) !== -1 ) {
						$( '.wpex-importing' ).addClass( 'wpex-imported' ).removeClass( 'wpex-importing' );
					} else {
						var errors = $.parseJSON( data.responseText ),
							errorMessage = '';

						// Iterate through the list of errors
						for ( var error in errors ) {
							errorMessage += errors[ error ];

							// If there was an error with the importing of the XML file,
							// the entire process should stop.
							if ( error === 'xml_import_error' ) {
								continueProcess = false;
							}
						}

						// Display the error message
						$( '.wpex-importing' )
							.addClass( 'wpex-importing-failed' )
							.removeClass( 'wpex-importing' )
							.text( errorMessage );

						that.allowPopupClosing = true;
					}

					// Continue with the loading only if an important error was not encountered
					if ( continueProcess === true ) {

						// Load the next content in the list
						that.importContent( importData );
					}

					console.log( that.importData[ currentContent ]['preloader'] + ': ' + ( ( Date.now() - timerStart ) / 1000 ).toString() + ' seconds.' );
				}
			} );

			// Set a time limit of 15 minutes for the importing process.
			importingLimit = setTimeout(function() {

				// Abort the AJAX request
				ajaxRequest.abort();

				// Allow the popup to be closed
				that.allowPopupClosing = true;

				$( '.wpex-importing' )
					.addClass( 'wpex-importing-failed' )
					.removeClass( 'wpex-importing' )
					.text( wpex_js_vars.content_importing_error );
			}, 15 * 60 * 1000);
		}

	};

} ) ( jQuery );

(function( $ ) {

	$.lightURLParse = function( url ) {
		var urlArray = url.split( '?' )[1].split( '&' ),
			result = [];

		$.each( urlArray, function( index, element ) {
			var elementArray = element.split( '=' );
			result[ elementArray[ 0 ] ] = elementArray[ 1 ];
		} );

		return result;
	};

})( jQuery );