( function( ) {
	
	if ( typeof wpexTinymce === 'undefined' || ! wpexTinymce ) {
		console.log( 'wpexTinymce is not defined' );
		return;
	}

	// Add editor button
	tinymce.PluginManager.add( 'wpex_shortcodes_mce_button', function( editor, url ) {

		// Get shortcodes
		var menuData = [];

		var shortcodes = wpexTinymce.shortcodes;
		
		jQuery.each( shortcodes, function( key, valueObj ) {

			var $obj = {
				text: valueObj.text,
				onclick: function() {
					editor.insertContent( valueObj.insert );
				}
			};
			
			menuData.push( $obj );

		} );

		// Add button data
		editor.addButton( 'wpex_shortcodes_mce_button', {
			
			text : wpexTinymce.btnLabel,
			type : 'menubutton',
			icon : false,
			menu : menuData

		} );

	} );

} ) ( );