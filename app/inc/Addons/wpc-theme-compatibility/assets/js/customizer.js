(function( $, api ) {
	
	api.bind( 'ready', function() {
		
		$.post( 
			ajaxurl,
			{
				'action': 'wpc_theme_compatibility_print_enquetes',
				'security': wpc_theme_compatibility.nonce
			}, 
			function( response ) {
				
				var content,
					control_styles  = api.control( 'wpc_theme_compatibility_removed_styles' ).container.find( 'select.wpc-multiple-select' ),
					control_scripts = api.control( 'wpc_theme_compatibility_removed_scripts' ).container.find( 'select.wpc-multiple-select' ),
					styles_value    = control_styles.data( 'value' ).split( ',' ),
					scripts_value   = control_scripts.data( 'value' ).split( ',' );
				
				if( typeof response !== 'undefined' ) {
					content = JSON.parse( response );
					
					control_styles.WPC_select2({ data: content['styles'], tags: true, tokenSeparators: [','] }).val( styles_value ).trigger( 'change' );
					control_scripts.WPC_select2({ data: content['scripts'], tags: true, tokenSeparators: [','] }).val( scripts_value ).trigger( 'change' );		
				}
			}
		);
		
	} );

})( jQuery, wp.customize );