( function ( $ ) {
	$( '.sp-message-close' ).on( 'click', function () {
		const msgData = $( '.sp-message' ).data( 'message' );

		$( '.sp-message' ).remove();

		var data = {
			action: 'shoppress_admin_message',
			nonce: shoppressMessage.nonce,
			msgData,
		};

		$.ajax( {
			type: 'POST',
			url: shoppressMessage.ajax_url,
			data: data,
			success: function ( response ) {
				console.log( response );
			},
			error: function ( err ) {
				console.log( err );
			},
		} );
	} );

	$( '.sp-pro-close' ).on( 'click', function () {
		const msgPro = $( '.sp-go-pro' ).data( 'message' );

		$( '.sp-go-pro' ).remove();

		var data = {
			action: 'shoppress_admin_message',
			nonce: shoppressMessage.nonce,
			msgPro,
		};

		$.ajax( {
			type: 'POST',
			url: shoppressMessage.ajax_url,
			data: data,
			success: function ( response ) {
				console.log( response );
			},
			error: function ( err ) {
				console.log( err );
			},
		} );
	} );
} )( jQuery );
