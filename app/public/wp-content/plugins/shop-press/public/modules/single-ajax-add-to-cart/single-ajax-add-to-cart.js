( function ( $ ) {
	'use strict';
	var show_notice = function ( html_element, $target ) {
		if ( ! $target ) {
			$target =
				$( '.woocommerce-notices-wrapper:first' ) ||
				$( '.wc-empty-cart-message' ).closest( '.woocommerce' ) ||
				$( '.woocommerce-cart-form' );
		}
		$target.prepend( html_element );
	};

	$( document ).on( 'submit', 'form.cart', function ( e ) {
		if (
			0 == $( this ).find( '[name="action"]' ).length ||
			'shoppress_single_add_to_cart_by_ajax' !==
				$( this ).find( '[name="action"]' ).val()
		) {
			return;
		}

		// Check form submitter
		const submitter = e.originalEvent.submitter;
		if ( submitter.name !== 'add-to-cart' ) {
			return e;
		}

		e.preventDefault();
		var $submit_button = $( this ).find( '.single_add_to_cart_button' );
		var data = new FormData( $( this )[ 0 ] );

		if ( null === data.get( 'product_id' ) ) {
			data.append( 'product_id', $submit_button.val() );
		}

		$submit_button.addClass( 'loading' );

		$.ajax( {
			method: 'post',
			processData: false,
			contentType: false,
			cache: false,
			data: data,
			enctype: $( this ).attr( 'ectype' ),
			url: shoppress_frontend.ajax.url,
			success: function ( response ) {
				if ( ! response ) {
					return;
				}

				show_notice( response.message_html );

				$submit_button.removeClass( 'loading' );

				$( document.body ).trigger( 'added_to_cart', [
					response.fragments,
					response.cart_hash,
					$submit_button,
				] );

				if ( 'undefined' !== typeof response.message_html ) {
					var $notices =
						$( '.woocommerce-notices-wrapper:first' ) ||
						$( '.wc-empty-cart-message' ).closest(
							'.woocommerce'
						) ||
						$( '.woocommerce-cart-form' );

					if (
						$notices.offset().top &&
						jQuery( document ).scrollTop() > $notices.offset().top
					) {
						$( [
							document.documentElement,
							document.body,
						] ).animate(
							{
								scrollTop: $notices.offset().top,
							},
							1000
						);
					}
				}

				$( document ).trigger( 'shoppress_single_add_to_cart_by_ajax' );
			},
		} );
	} );
} )( jQuery );
