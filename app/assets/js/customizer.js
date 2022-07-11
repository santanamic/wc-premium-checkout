(function( $, api ) {
	
	/* Plugin Active Desactive */
	api( 'wpc', function( value ) {
		value.bind( function( is_active ) {
			
			$.post( 
				wpc.ajaxurl,
				{
					'action': 'wpc_tmp_preview',
					'status': Number( is_active ),
					'security' : wpc.nonce
				}, 
				function( response ) {
					wp.customize.previewer.refresh();
				}
			);
	
			
		});
	});
	
})( jQuery, wp.customize );