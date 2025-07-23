( function ( $ ) {
	'use strict';
	$( document ).ready( function () {
		$( document ).on( 'click', '.sp-quick-view', function () {
			var $this = $( this ),
				$wrap = $this.closest( '.sp-quick-view' ),
				$id = $wrap.data( 'product_id' );

			$this.addClass( 'loading' );
			$wrap.css( 'opacity', '0.5' );

			$.ajax( {
				type: 'POST',
				url: shoppress_frontend.ajax.url,
				data: {
					action: 'quick_view_ajax',
					nonce: shoppress_frontend.ajax.nonce,
					product_id: $id,
				},
				success: function ( data ) {
					if ( data.content ) {
						$( '#sp-quick-view-html' ).html( data.content );
					} else {
						$( '#sp-quick-view-html' ).html( data );
					}

					$( '#sp-quick-view-overlay' ).fadeIn();

					$this.removeClass( 'loading' );
					$wrap.css( 'opacity', '' );

					$( document ).trigger( 'quick_view_loaded' );
				},
				error: function ( data ) {
					console.log( data );
					$this.removeClass( 'loading' );
				},
			} );
		} );

		$( document ).on( 'click', '#sp-close-quick-view', function () {
			$( '#sp-quick-view-overlay' ).fadeOut();
			$( this )
				.closest( '#sp-quick-view-content' )
				.find( '#sp-quick-view-html' )
				.html( '' );
		} );
	} );
} )( jQuery );
