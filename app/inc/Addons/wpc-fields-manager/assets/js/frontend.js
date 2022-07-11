(function( $ ) {

	$.validator.addMethod( 'pattern', function( value, element, param ) {
		if ( this.optional(element) ) {
			return true;
		}
		if ( typeof param === 'string' ) {
			param = new RegExp( param );
		}
		return param.test( value );
		}, 'Invalid format.'
	);

	$( document ).ready(function () {
		
		var checkout_form = $('form.checkout');

		checkout_form.on('checkout_place_order', function () {
			
			if ( !checkout_form.valid() ) {
				$( '.error:visible' ).closest( '.form-row' )[0].scrollIntoView( true );
				
				return false 
			}
			
			return true;
		});

	});

})( jQuery );