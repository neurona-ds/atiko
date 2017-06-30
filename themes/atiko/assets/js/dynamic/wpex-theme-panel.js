( function( $ ) {

	"use strict";
	
	$( document ).ready( function() {

		// Show notice
		$( '.wpex-checkbox' ).click( function() {
			$( '.wpex-theme-panel-updated' ).show();
		} );
		
		$( '.wpex-theme-panel .manage-right input[type="text"]' ).change( function() {
			$( '.wpex-theme-panel-updated' ).show();
		} );

		// Save on link click
		$( '.wpex-theme-panel-updated a' ).click( function( e ) {
			e.preventDefault();
			$( "#wpex-theme-panel-form #submit" ).click();
		} );

		// Checkbox change function - tweak classes
		$( '.wpex-checkbox' ).change(function() {
			var $this = $( this ),
				$parentTr = $this.parent( 'th' ).parent( '.wpex-module' );
			if ( $parentTr.hasClass( 'wpex-disabled' ) ) {
				$parentTr.removeClass( 'wpex-disabled' );
			} else {
				$parentTr.addClass( 'wpex-disabled' );
			}
		});

		// Module on click
		$( '.wpex-theme-panel-module-link' ).click( function() {
			$( '.wpex-theme-panel-updated' ).show();
			var $this     = $( this ),
				$ref      = $this.attr( 'href' ),
				$checkbox = $( $ref ).find( '.wpex-checkbox' ),
				$parentTr = $this.parents( '.wpex-module' );
			if ( $checkbox.is( ":checked" ) ) {
				$checkbox.attr( 'checked', false );
			} else {
				$checkbox.attr( 'checked', true );
			}
			if ( $parentTr.hasClass( 'wpex-disabled' ) ) {
				$parentTr.removeClass( 'wpex-disabled' );
			} else {
				$parentTr.addClass( 'wpex-disabled' );
			}
			return false;
		} );

		// Filter
		var $filter_buttons = $( '.wpex-filter-active button' );
		$filter_buttons.click( function() {
			var $filterBy = $( this ).data( 'filter-by' );
			$filter_buttons.removeClass( 'active' );
			$( this ).addClass( 'active' );
			$( '.wpex-module' ).removeClass( 'wpex-filterby-hide' );
			if ( 'active' == $filterBy ) {
				$( '.wpex-module' ).each( function() {
					if ( $( this ).hasClass( 'wpex-disabled' ) ) {
						$( this ).addClass( 'wpex-filterby-hide' );
					}
				} );
			} else if ( 'inactive' == $filterBy ) {
				$( '.wpex-module' ).each( function() {
					if ( ! $( this ).hasClass( 'wpex-disabled' ) ) {
						$( this ).addClass( 'wpex-filterby-hide' );
					}
				} );
			}
			return false;
		} );

		// Sort
		$( '.wpex-theme-panel-sort a' ).click( function() {
			var $data = $( this ).data( 'category' );
			$( '.wpex-theme-panel-sort a' ).removeClass( 'wpex-active-category' );
			$( this ).addClass( 'wpex-active-category' );
			if ( 'all' == $data ) {
				$( '.wpex-module' ).removeClass( 'wpex-sort-hide' );
			} else {
				$( '.wpex-module' ).addClass( 'wpex-sort-hide' );
				$( '.wpex-category-'+ $data ).each( function() {
					$( this ).removeClass( 'wpex-sort-hide' );
				} );
			}
			return false;
		} );

	} );

} ) ( jQuery );