( function ( $ ) {
	$( '.sp-an-close' ).on( 'click', function () {
		var data = {
			action: 'shoppress_announcement',
			nonce: shoppressAnnouncement.nonce,
		};

		$.ajax( {
			type: 'POST',
			url: shoppressAnnouncement.ajax_url,
			data: data,
			success: function ( response ) {
				$( '#sp-admin-announcement' ).remove();
				console.log( response );
			},
			error: function ( err ) {
				console.log( err );
			},
		} );
	} );
} )( jQuery );
