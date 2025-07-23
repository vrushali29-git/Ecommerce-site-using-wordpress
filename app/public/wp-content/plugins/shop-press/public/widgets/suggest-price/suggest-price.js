( function ( $ ) {
	'use strict';
	$( document ).ready( function () {
		const form = $( document ).find( '.sp-suggest-price-form' );

		$( document ).on( 'click', '.sp-suggest-price', function ( e ) {
			e.preventDefault();

			if ( ! form.hasClass( 'sp-suggest-form-open' ) ) {
				form.addClass( 'sp-suggest-form-open' );
				form.fadeIn();
			} else {
				form.removeClass( 'sp-suggest-form-open' );
				form.fadeOut();
			}
		} );

		$( document ).on( 'click', '.sp-suggest-close', function ( e ) {
			form.removeClass( 'sp-suggest-form-open' );
			form.fadeOut();
		} );
	} );
} )( jQuery );
