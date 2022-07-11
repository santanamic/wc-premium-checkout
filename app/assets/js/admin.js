/**
 * Premium Checkout sripts
 *
 * @since 1.0.0
 */
(function( $ ) {
  'use strict';
  var wpc = {

    cache: {},

    init: function() {
      this.cacheElements();
      this.bindEvents();
    },

    cacheElements: function() {
      this.cache = {
        $window: $( window ),
        $document: $( document )
      };
    },

    bindEvents: function() {
      var self = this;
	  
      self.cache.$document.on( 'ready', function() {
        self.tabbedNav();
      });
    },
	
    tabbedNav: function() {
      var self = this,
        $wrap = $( '.about-wrap' );

      // Hide all panels
      $( 'div.panel', $wrap ).hide();

      this.cache.$window.on( 'load', function() {
        var tab = self.getParameterByName( 'tab' ),
          hashTab = window.location.hash.substr( 1 );

        if ( tab ) {
          $( '.nav-tab-wrapper a[href="#' + tab + '"]', $wrap ).click();
        } else if ( hashTab ) {
          $( '.nav-tab-wrapper a[href="#' + hashTab + '"]', $wrap ).click();
        } else {
          $( 'div.panel:not(.hidden)', $wrap ).first().show();
        }
      });

      // Listen for the click event.
      $( '.nav-tab-wrapper a', $wrap ).on( 'click', function() {

        // Deactivate and hide all tabs & panels.
        $( '.nav-tab-wrapper a', $wrap ).removeClass( 'nav-tab-active' );
        $( 'div.panel', $wrap ).hide();

        // Activate and show the selected tab and panel.
        $( this ).addClass( 'nav-tab-active' );
        $( 'div' + $( this ).attr( 'href' ), $wrap ).show();

        return false;
      });
    },

    getParameterByName: function( name ) {
      var regex, results;
      name = name.replace( /[\[]/, '\\[' ).replace( /[\]]/, '\\]' );
      regex = new RegExp( '[\\?&]' + name + '=([^&#]*)' );
      results = regex.exec( location.search );
      return null === results ? '' : decodeURIComponent( results[1].replace( /\+/g, ' ' ) );
    }

  };

  wpc.init();

})( jQuery );
