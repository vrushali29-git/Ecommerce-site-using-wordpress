( function ( $ ) {
	/**
	 * @param $scope The Widget wrapper element as a jQuery element
	 * @param $ The jQuery alias
	 */
	var WidgetWishlistHandler = function ( $scope, $ ) {
		const input = $scope.find( '.sp-ajax-search-input' );
		const result = $scope.find( '.sp-ajax-search-result' );
		const cat = $scope.find( 'select' );
		const form = $scope.find( '.sp-ajax-search form' );
		const productList = $( 'ul.products' );

		input.on( 'input', function () {
			$.ajax( {
				type: 'POST',
				url: shoppress_frontend.ajax.url,
				data: {
					action: 'sp_ajax_search',
					nonce: shoppress_frontend.ajax.nonce,
					s: input.val(),
					cat: cat.val(),
					limit: result.data( 'limit' ),
				},
				success: function ( res ) {
					result.html( res.content );
				},
				error: function ( res ) {
					console.log( res );
				},
			} );

			if ( ! input.val() ) {
				result.hide();
			} else {
				result.show();
			}
		} );

		form.on( 'submit', function ( e ) {
			e.preventDefault();

			result.hide();

			$.ajax( {
				type: 'POST',
				url: shoppress_frontend.ajax.url,
				data: {
					action: 'sp_ajax_search',
					nonce: shoppress_frontend.ajax.nonce,
					s: input.val(),
					cat: cat.val(),
					res: true,
				},
				success: function ( res ) {
					productList.html( res.content );
				},
				error: function ( res ) {
					console.log( res );
				},
			} );
		} );
	};

	// Make sure you run this code under Elementor.
	$( window ).on( 'elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/sp-ajax-search.default',
			WidgetWishlistHandler
		);
	} );
} )( jQuery );
