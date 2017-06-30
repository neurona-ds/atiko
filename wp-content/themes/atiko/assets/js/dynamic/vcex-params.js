( function( $ ) {
	'use strict';

	/********************************
	/***** Chosen Selects ***********
	/********************************/
	if ( $.fn.chosen !== undefined ) {

		$( '.vcex-chosen' ).chosen( {
			search_contains: true,
			inherit_select_classes: true
		} );

		/*
		var $selects = $( '.vc_edit-form-tab select' );

		if ( $selects.length ) {
			$selects.addClass( 'vc-chosen' ).chosen( {
				disable_search_threshold: 100,
				inherit_select_classes: true
			} );
		}*/

	}

	/********************************
	/***** On/Off Switch ************
	/********************************/
	var $switch = $( '.vcex-ofswitch .vcex-btn' );

	$switch.on( 'click', function() {

		var $this        = $( this ),
			$parent      = $this.parent( '.vcex-ofswitch' ),
			$hiddenInput = $parent.find( '.vcex-hidden-input' );

		$parent.find( '.vcex-btn' ).removeClass( 'vcex-active' );
		$this.addClass( 'vcex-active' );
		$hiddenInput.val( $this.data( 'value' ) ).trigger( 'change' );

	} );



	/********************************
	/***** Responsive Columns *******
	/********************************/
	$( '.vcex-responsive-column-select' ).on( 'change', function( e ) {

		var valArray = [];

		var $parent      = $( this ).closest( '.vcex-responsive-columns-param' ),
			$allSettings = $parent.find( 'select' ),
			$hiddenInput = $parent.find( 'input.columns_responsive_settings' );

			$allSettings.each( function( index, el ) {

				var $this = $( this ),
					val   = $this.val();

				if ( val ) {
					var parsed = $this.attr( 'name' ) + ':' + val;
					valArray.push( parsed );
				}

			} );

			$hiddenInput.val( valArray.join('|') );

	} );



	/********************************
	/***** Responsive Sizes *******
	/********************************/

	// Responsive font sizes check
	$( ".vc_shortcode-param[data-vc-shortcode-param-name='responsive_text'], .vc_shortcode-param[data-vc-shortcode-param-name='responsive_font_size']" ).each( function() {
	
		var $this                    = $( this ),
			$input                   = $this.find( '.wpb-input' ),
			$prevFontSize            = $this.prev(),
			$prevFontSizeInputs      = $prevFontSize.find( '.vcex-input' ),
			$prevFontSizeItems       = $prevFontSize.find( '.vcex-item' ),
			$prevFontSizeFirstItem   = $prevFontSize.find( '.vcex-item-1' ),
			$prevFontSizeHiddenField = $prevFontSize.find( 'input.vcex_responsive_sizes_field' );

		if ( $input.val() == 'true' ) {
			$prevFontSizeItems.hide();
			$prevFontSizeFirstItem.show();
			$prevFontSizeHiddenField.val( $prevFontSizeFirstItem.find( '.vcex-input' ).val() );
		}

		$input.on( 'input change', function( e ) {

			if ( $input.val() == 'true' ) {

				$prevFontSizeItems.hide();
				$prevFontSizeFirstItem.show();
				$prevFontSizeHiddenField.val( $prevFontSizeFirstItem.find( '.vcex-input' ).val() );

			} else {
				if ( $prevFontSizeFirstItem.find( '.vcex-input' ).val() ) {
					$prevFontSizeItems.show();
				}
				$prevFontSizeHiddenField.val( vcexGetResponsiveSizeValue( $prevFontSizeInputs ) );

			}

		} );

	} );

	// Update hidden field with correct values
	$( '.vcex-rs-param .vcex-input' ).on( 'input', function( e ) {

		var $this = $( this ),
			$parent = $this.closest( '.vcex-rs-param' ),
			$topParent = $parent.closest( '.wpb_el_type_vcex_responsive_sizes' ),
			$nextResponsiveSetting = $topParent.next().find( 'select.responsive_text' ),
			$allSettings = $parent.find( '.vcex-input' ),
			$hiddenInput = $parent.find( 'input.vcex_responsive_sizes_field' );

			if ( $this.parent().hasClass( 'vcex-item-1' ) ) {

				if ( $this.val() ) {
					$parent.find( '.vcex-item' ).show();
				} else {
					$parent.find( '.vcex-item' ).not( '.vcex-item-1' ).hide();
					$hiddenInput.val( '' );
					return;
				}

				if ( $nextResponsiveSetting && 'true' == $nextResponsiveSetting.val() ) {
					$parent.find( '.vcex-item' ).not( '.vcex-item-1' ).hide();
					$hiddenInput.val( $this.val() );
					return;
				}

			}

			$hiddenInput.val( vcexGetResponsiveSizeValue( $allSettings ) );

	} );

	function vcexGetResponsiveSizeValue( $allSettings ) {

		var valArray = [],
			$firstInput = '';

		$allSettings.each( function( index, el ) {

			var $this = $( this ),
				val   = $this.val();

			if ( $this.parent().hasClass( 'vcex-item-1' ) ) {
				$firstInput = $this;
			}

			if ( val ) {
				var parsed = $this.attr( 'name' ) + ':' + val;
				valArray.push( parsed );
			}

		} );

		if ( valArray.length == 1 && $firstInput.val() ) {
			return $firstInput.val();
		} else {
			return valArray.join('|');
		}

	}

} ( jQuery ) );