(function( $ ) {
	
	/* Login Form */
	$( '.checkouttogglelogin' ).on( 'click', function(e) {
		$( '.login-container' ).fadeToggle();
		$( this ).closest( 'html' ).toggleClass( 'overflowhidden' );
	} );

})( jQuery );