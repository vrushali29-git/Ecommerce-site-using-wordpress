jQuery( document ).ready( function ( $ ) {
	'use strict';

	const stockStatus = $(
		'#inventory_product_data input[name="_stock_status"]'
	);
	const manageStock = $(
		'#inventory_product_data input[name="_manage_stock"]'
	);
	const spFields = $( '.sp-backorder-fields' );

	manageStock.on( 'change', function () {
		const item = $( this );

		if ( true === item.prop( 'checked' ) ) {
			spFields.removeClass( 'sp-backorder-fields-show' );
		} else if ( 'onbackorder' === spFields.attr( 'status' ) ) {
			spFields.addClass( 'sp-backorder-fields-show' );
		}
	} );

	stockStatus.on( 'change', function () {
		const item = $( this );

		spFields.attr( { status: item.val() } );

		if ( 'onbackorder' === item.val() ) {
			spFields.addClass( 'sp-backorder-fields-show' );
		} else {
			spFields.removeClass( 'sp-backorder-fields-show' );
		}
	} );
} );
