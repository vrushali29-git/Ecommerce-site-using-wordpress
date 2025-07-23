function shoppress_compare_init( $ ) {
	/**
	 * Generate compare array for cookies
	 *
	 * @param Object id
	 * @returns HTML
	 */
	function CompareCookie( id ) {
		var cookies = document.cookie.match( /shoppress_woo_compare=(.*?);/i );

		if ( null === cookies ) {
			var cookies = document.cookie.match(
				/shoppress_woo_compare=(.*?)+[5D]/i
			);
		}

		cookies = null != cookies ? cookies[ 0 ] : '';

		var openb = '%5B',
			closeb = '%5D',
			and = '%2C',
			ShopPressCompare = cookies
				.replace( 'shoppress_woo_compare=' + openb, '' )
				.replace( closeb, '' )
				.replace( ';', '' )
				.replace( '%22%22', '' );

		if ( ShopPressCompare.search( id ) < 0 ) {
			var value = ShopPressCompare + and + id;
			window.document.cookie = `shoppress_woo_compare = ${
				openb + value + closeb
			}; path                = / `;
		} else {
			var value = ShopPressCompare.replace( and + id, '' );
			window.document.cookie = `shoppress_woo_compare = ${
				openb + value + closeb
			}; path                = / `;
		}
	}

	/**
	 * Generate compare array for cookies
	 *
	 * @param Object id
	 * @returns HTML
	 */
	function CompareNotice( data ) {
		if ( 0 == $( 'body .shoppress-popup-notices-wrap' ).length ) {
			$( 'body' ).append(
				'<div class="shoppress-popup-notices-wrap"></div>'
			);
		}

		if ( 'undefined' == typeof data.message_type ) {
			data.message_type = 'success';
		}
		$( 'body .shoppress-popup-notices-wrap' ).append(
			'<div class="shoppress-popup-notice ' +
				data.message_type +
				'" style="display:none;"> <p class="popup-notice-text">' +
				data.message +
				'</p>' +
				( data.button_text.length
					? ' <a href="' +
					  ( data.button_url.length ? data.button_url : '#' ) +
					  '">' +
					  data.button_text +
					  '</a>'
					: '' ) +
				'<div class="shoppress-close-notice"> <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 10 10" > <path d="M9.79.21a.717.717,0,0,1,0,1.014L5.956,5.057,9.674,8.776A.717.717,0,0,1,8.66,9.79L4.942,6.072l-3.6,3.6A.717.717,0,0,1,.326,8.66l3.6-3.6L.21,1.34A.717.717,0,0,1,1.224.326L4.942,4.043,8.776.21A.717.717,0,0,1,9.79.21Z" fill="#7f8da0" /> </svg> </div>'
		);

		var $new_notice = $(
			'body .shoppress-popup-notices-wrap .shoppress-popup-notice:last-child()'
		);
		$new_notice.fadeIn();

		$new_notice
			.find( '.shoppress-close-notice' )
			.on( 'click', function ( e ) {
				$( this )
					.closest( '.shoppress-popup-notice' )
					.fadeOut( 500 )
					.remove();
			} );

		setTimeout( function () {
			$(
				'.shoppress-popup-notices-wrap .shoppress-popup-notice:first-child'
			)
				.fadeOut( 500 )
				.remove();
		}, 5000 );
	}

	function ComparePopup( data ) {
		$( '.shoppress-compare-popup-wrap' ).remove();
		if ( data.data.popup_html.length ) {
			if (
				$( data.data.popup_html ).find( '.sp-compare' ).length ||
				$( data.data.popup_html ).find( '.sp-wrap' ).length
			) {
				$( 'body' ).append( data.data.popup_html );
				HighlightCompareFields();
				$( '.shoppress-compare-popup-wrap' ).css( 'display', 'block' );
			}
		}
	}

	function HighlightCompareFields() {
		const highlight_all_table_fields_with_different_values =
			'undefined' !==
			typeof shoppress_frontend.compare
				.highlight_all_table_fields_with_different_values
				? shoppress_frontend.compare
						.highlight_all_table_fields_with_different_values
				: false;
		if ( highlight_all_table_fields_with_different_values ) {
			jQuery( '.sp-compare-products-container tr.attribute' ).each(
				function ( i, tr ) {
					var $tr = jQuery( tr );
					var label_td_value = $tr.find( 'td:first-child' ).html();
					var first_td_value = $tr.find( 'td.attribute-value' ).length
						? $tr.find( 'td.attribute-value' ).html()
						: '';

					$tr.find(
						'td.attribute-value:nth-child(n+1):not(.ratings):not(.ratings-average)'
					).each( function ( i, td ) {
						var $td = $( td );
						console.log( first_td_value );
						console.log( $td.html() );

						if ( first_td_value !== $td.html() ) {
							if ( 0 === label_td_value.length ) {
								$tr.addClass( 'sp-highlight' );
								$tr.addClass( 'sp-highlight-without-label' );
							} else {
								$tr.addClass( 'sp-highlight' );
							}
						}
					} );
				}
			);
		}
	}

	$( document ).on(
		'click',
		'.sp-compare-button, .sp-compare-clear-all-products',
		function () {
			var $button = $( this );

			var is_compare_page =
				$( '.sp-compare-products-container' ).length &&
				$( '.sp-compare-products-container' ).closest(
					'shoppress-compare-popup-wrap'
				).length == 0
					? true
					: false;
			var wrap_classes =
				'.sp-single-compare, .sp-product-compare, .sp-compare-page-button';
			var $wrap =
				$button.closest( '.sp-compare-button-wrapper' ).length > 0
					? $button.closest( '.sp-compare-button-wrapper' )
					: $button;
			( datastatus =
				$wrap.attr( 'data-status' ) == 'yes' ? true : false ),
				( $id = $wrap.data( 'product_id' ) );
			if ( 'clear_all' === $id ) {
				var $buttons = $(
					'.sp-compare-button-wrapper, .sp-compare-button'
				);
			} else {
				var $buttons = $(
					'.sp-compare-button-wrapper[data-product_id="' +
						$id +
						'"], .sp-compare-button[data-product_id="' +
						$id +
						'"]'
				);

				CompareCookie( $id );
			}

			$button.addClass( 'loading' );
			// $wrap.css('opacity', '0.5');

			$.ajax( {
				type: 'POST',
				url: shoppress_frontend.ajax.url,
				data: {
					action: 'AddCompare',
					nonce: shoppress_frontend.ajax.nonce,
					product_id: $id,
				},
				success: function ( data, response ) {
					if ( data && data.data.message ) {
						$buttons
							.find( '.sp-compare-label' )
							.text( data.data.compare_label );

						if ( 'undefined' != typeof data.data.status ) {
							$buttons.attr( 'data-status', data.data.status );
						}

						// Remove the compare container if there is no item to display
						const compareItemsCount = data.data.items_count;
						if ( compareItemsCount === 0 ) {
							$( '.sp-compare-products-container' ).remove();
							$( '.shoppress-compare-popup-wrap' ).remove();
						}

						const compareShareLink =
							'undefined' !== typeof data.data.share_link
								? data.data.share_link
								: '';
						if ( compareShareLink.length ) {
							$( '.sp-compare .sp-compare-share' ).data(
								'compare_link',
								compareShareLink
							);
						}

						// Remove the html of the removed product from compare list
						const currentProduct = $( `.sp-c-pr-${ $id }` );
						if ( currentProduct.length ) {
							currentProduct.remove();
						}

						if ( is_compare_page ) {
							return;
						}

						if (
							shoppress_frontend.compare
								.display_popup_after_update
						) {
							if (
								'undefined' != typeof data.data.status &&
								'yes' === data.data.status
							) {
								ComparePopup( data );
							}
						} else {
							CompareNotice( data.data );
						}
					}
					$buttons.removeClass( 'loading' );
				},
				error: function ( data, response ) {
					if ( data && data.responseJSON.data.message ) {
						console.log( data, response );
					}

					if ( data.responseJSON.success === false ) {
						CompareNotice( data.responseJSON.data );
					}
					$buttons.removeClass( 'loading' );
				},
			} );
		}
	);

	$( document ).on( 'click', '.sp-compare-close-popup', function ( e ) {
		$( this ).closest( '.sp-compare-popup' ).remove();
	} );

	$( document ).on( 'click', '.sp-compare-share', function ( e ) {
		var $item = $( this );
		var compare_link = $item.data( 'compare_link' );
		var compare_text = $item.data( 'compare_title' );
		if ( $( '#sp-compare-share-popup' ).length ) {
			$( '#sp-compare-share-popup .sp-share-link-input' ).val(
				compare_link
			);

			$( '.sp-share-links .sp-share-link a' ).each( function ( i, link ) {
				var share_link = $( link ).data( 'pattern' );
				share_link = share_link.replace( '{url}', compare_link );
				share_link = share_link.replace( '{text}', compare_text );
				$( link ).attr( 'href', share_link );
			} );

			$( '#sp-compare-share-popup' ).fadeIn();
		}
	} );

	HighlightCompareFields();
}

( function ( $ ) {
	shoppress_compare_init( $ );
} )( jQuery );
