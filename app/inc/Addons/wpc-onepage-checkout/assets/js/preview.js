(function( $, api ) {
	
	/* Checkout Logo */
	api( 'wpc_theme_onepage_checkout_logo', function( value ) {
		value.bind( function( url ) {
			$( '.logo img' ).attr( 'src', url );
		});
	});

	/* Place Order Button */
	api( 'wpc_theme_onepage_checkout_order_button_color', function( value ) {
		value.bind( function( color ) {
			$( '#place_order' ).css( 'background-color', color );
			$( '#place_order' ).css( 'border-color', color );
		});
	});

	/* Title Label */
	api( 'wpc_theme_onepage_checkout_content_primary_color', function( value ) {
		value.bind( function( color ) {
			$( '.content-box-title' ).css( 'background-color', color );
			$( '#coupon-send' ).css( 'background-color', color );
			$( '#coupon-send' ).css( 'border-color', color );
			$( '.steps .steps-item.steps-item-is-current .steps-item-icon' ).css( 'background', color );
		});
	});

	/* Background Color */
	api( 'wpc_theme_onepage_checkout_background_color', function( value ) {
		value.bind( function( color ) {
			$( '#wpc-wrapper' ).css( 'background-color', color );
		});
	});

	/* Header Color */
	api( 'wpc_theme_onepage_checkout_header_color', function( value ) {
		value.bind( function( color ) {
			$( '.header' ).css( 'background-color', color );
			
			if( '#00000000' === color ) {
				$( '.header' ).css( 'border-color', '#00000000' );
			} else {
				$( '.header' ).css( 'border-color', '#ebebeb' );
			}
		});
	});

	/* Form Layout */
	api( 'wpc_theme_onepage_checkout_form_layout', function( value ) {
		value.bind( function( myclass ) {
			if( $( 'body' ).hasClass( 'inline-form' ) && 'inline-form' !== myclass ) {
				$( 'body' ).removeClass( 'inline-form' );
			} else {
				$( 'body' ).addClass( 'inline-form' );
			}
		});
	});
	
})( jQuery, wp.customize );