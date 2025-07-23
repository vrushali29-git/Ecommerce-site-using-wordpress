/* global wc_cart_params */
jQuery( function ( $ ) {
	$( '.sp-cart-totals .cart-total-header a' ).on( 'click', function ( e ) {
		if ( $( this ).parent().hasClass( 'open' ) ) {
			$( this ).parent().removeClass( 'open' );
			$( this )
				.parents( '.sp-cart-totals' )
				.find( '.cart_totals .shop_table' )
				.removeClass( 'open' );
		} else {
			$( this ).parent().addClass( 'open' );
			$( this )
				.parents( '.sp-cart-totals' )
				.find( '.cart_totals .shop_table' )
				.addClass( 'open' );
		}

		return false;
	} );
} );
