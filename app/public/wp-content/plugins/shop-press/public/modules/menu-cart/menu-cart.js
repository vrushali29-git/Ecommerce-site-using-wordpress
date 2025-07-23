( function ( $ ) {
	'use strict';
	$( document ).ready( function () {
		$( 'body' ).click( function ( evt ) {
			if ( evt.target.id == 'sp-mc-dd-menu_cart' ) {
				return;
			}
			if ( $( evt.target ).closest( '.sp-mc-item' ).length ) {
				return;
			}

			const dropdown = $( document ).find( '.sp-mc-dd' );
			dropdown.removeClass( 'dd-open' );
			dropdown.fadeOut();
		} );

		$( document ).on( 'click', '.sp-header-cart', function ( e ) {
			e.preventDefault();

			const dropdown = $( e.target )
				.closest( '.sp-open-dropdown' )
				.find( '.sp-mc-dd' );
			var is_opened = dropdown.hasClass( 'sp-dropdown-opened' );

			$( '.sp-dropdown-opened' )
				.removeClass( '.sp-dropdown-opened' )
				.fadeOut();

			if ( ! is_opened ) {
				dropdown.addClass( 'sp-dropdown-opened' );
				dropdown.fadeIn();
			} else {
				dropdown.removeClass( 'sp-dropdown-opened' );
				dropdown.fadeOut();
			}
		} );
	} );
} )( jQuery );
