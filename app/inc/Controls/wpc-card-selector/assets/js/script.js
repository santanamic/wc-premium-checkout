wp.customize.controlConstructor['wpc_card_selector'] = wp.customize.Control.extend({
	ready: function() {
		'use strict';
		console.log( 'debugger' );
		var control = this,
			element = this.container.find( '.wpc-container-card' );		
			
		element.on( 'click', 'span.button', function( event ) {
			var ID = jQuery( this ).closest( '.wpc-control-card' ).data( 'id' );
			var URL = jQuery( this ).closest( '.wpc-control-card' ).data( 'link' );

			if ( URL != '' && URL != undefined ) {
				window.open( URL, '_blank' ).focus();
			}
			else if ( ID != '' && ID != undefined  ) {
				control.setting.set( ID );
				
				jQuery( element ).find( '.wpc-control-card' ).removeClass( 'active' );
				jQuery( this ).closest( '.wpc-control-card' ).addClass( 'active' );

				//wp.customize.previewer.refresh();
				//wp.customize.previewer.previewUrl();
			}
			
			wp.customize.bind( 'saved',			
				function() {
					jQuery( element ).find( '.wpc-control-card' )
						.each( function( index, item ) {
							item = jQuery( item );
							if ( control.setting.get() === item.data( 'id' ) ) {
								item.addClass( 'active' ).find( 'span.button' ).text( item.data( 'active-text' ) ).closest( '.wpc-control-card' );
							} else {
								jQuery( item ).removeClass( 'active' ).find( 'span.button' ).text( item.data( 'text' ) ).closest( '.wpc-control-card' );
							}
						} 
					);
				} 
			);
		} );
	}

});