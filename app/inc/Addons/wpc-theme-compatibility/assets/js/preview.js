(function( $, api ) {
	
	//Live preview for custom CSS control
	api( 'wpc_theme_compatibility_custom_css', function( value ) {
		value.bind( function( css ) {
			$( '#wpc-theme-compatibility-css-saved' ).text( css );
		});
	});
	
})( jQuery, wp.customize );