wp.customize.controlConstructor['wpc_multiple_select'] = wp.customize.Control.extend({

	ready: function() {

		'use strict';

		var control  = this,
		    element  = this.container.find( 'select' ),
		    selectDataValue = this.container.find( 'select' ).data( 'value' ),
		    selectValue,
		    WPC_select2Options = {
				escapeMarkup: function( markup ) {
					return markup;
				}
		    };

		jQuery( element ).WPC_select2( WPC_select2Options ).on( 'change', function() {
			selectValue = jQuery( this ).val();
			//control.setting.set( selectValue );
			if ( null === selectValue ) {
				control.setting.set( '' );
			} else {
				control.setting.set( selectValue );
			}
		});			

		if ( jQuery( element ).find( 'option' ).length > 0 ) {
			jQuery( element ).WPC_select2();
			if ( selectDataValue != '' ) {
				var selectValueArray = selectDataValue.split( ',' );
				jQuery( element ).val( selectValueArray ).trigger( 'change' );			
			}
		}


	}

});