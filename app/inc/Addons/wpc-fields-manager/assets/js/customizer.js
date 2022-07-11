(function( $, api ) {
	
	addFieldError = function( field, msg ) { 
		field.attr( 'style', 'border-color: red' ).after( 
			'<span class="field-error" style="color: red;font-size: 11px;line-height: 1.2;display: block;margin: 5px 0;text-align: center;">' + msg + '</span>' 
		);
	}
	removeFieldError = function( field ) { 
		field.attr( 'style', '' ).next( '.field-error' ).remove();
	}
	
	api.bind( 'ready', function() {
		var manager = api.control( 'wpc_field_manager' ),
			reset   = api.control( 'wpc_field_manager_reset' );
		
			manager.container.find( 'select[name="type"]' ).on( 'change wpc_field_manager_trigger_control', function() { 
				var type = $( this ).val(),
					field = $( this ).closest( '.item-field' );
				
				if ( 'select' === type || 'radio' === type ) {
					field.find( '.arrangement-field-options' ).show();
				} else {
					field.find( '.arrangement-field-options' ).hide()
				}
				
				if ( true === field.data( 'custom-field' ) ) {
					$( this ).removeAttr( 'disabled' );
					$( this ).find( 'option[value="default"]' ).remove();
				}
			} );
			
			manager.container.on( 'addItemField', function( e, item ) {
				var $item   = $( item ),
					group   = $item.closest( '.field-group.active' ),
					groupId = group.data( 'id' ),
					fieldId = $item.data( 'id' );

				$item.find( 'select[name="type"], input[name="id"]' ).removeAttr( 'disabled' ).change().find( 'option[value="default"]' ).remove();
				$item.find( 'input[name="id"]' ).val( groupId + '_' + fieldId );
				//$item.find( 'select[name="required"], select[name="enabled"], select[name="inemail"], select[name="inorder"]' ).val( 'yes' ).change();
				$item.closest( '.item-field' ).attr( 'data-id', groupId + '_' + fieldId );
			} );
			manager.container.find( 'input[data-field="id"]' ).on( 'change wpc_field_manager_trigger_control', function() {
				var field = $( this ).closest( '.item-field' ),
					value  = $( this ).val();

				field.attr( 'data-id', value );

				if ( true === field.data( 'custom-field' ) ) {
					$( this ).removeAttr( 'disabled' );
				}
			} );
			reset.container.on( 'click', 'input[type="button"]', function() {
				var userOption = confirm( wpc_field_manager.i18n.confirm_reset_settings ); 

				if ( true === userOption ) {
					
					$.post( ajaxurl, {
						'action': 'wpc_field_manager_reset_settings',
						'security': wpc_field_manager.nonce
					}, 
					function( response ) {
						
						if ( response.success == true ) {
							$( window ).off( 'beforeunload' );
							alert( response.data.message );
							document.location.reload( true );
						} else {
							alert( response.data.message );
						}
					}
				);
			}
		} );
		
		$( 'input[data-field="id"], select[name="type"]' ).trigger( 'wpc_field_manager_trigger_control' );

	} );

})( jQuery, wp.customize );