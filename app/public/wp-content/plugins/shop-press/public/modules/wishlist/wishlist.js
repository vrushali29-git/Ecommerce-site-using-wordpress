function shoppress_wishlist_init( $ ) {
	function setCookie( cname, cvalue, exdays ) {
		const d = new Date();
		d.setTime( d.getTime() + exdays * 24 * 60 * 60 * 1000 );
		let expires = 'expires=' + d.toUTCString();
		document.cookie = cname + '=' + cvalue + ';' + expires + ';path=/';
	}

	function getCookie( cname ) {
		let name = cname + '=';
		let decodedCookie = decodeURIComponent( document.cookie );
		let ca = decodedCookie.split( ';' );
		for ( let i = 0; i < ca.length; i++ ) {
			let c = ca[ i ];
			while ( c.charAt( 0 ) == ' ' ) {
				c = c.substring( 1 );
			}
			if ( c.indexOf( name ) == 0 ) {
				return c.substring( name.length, c.length );
			}
		}
		return '';
	}
	/**
	 * Generate wishlist array for cookies
	 *
	 * @param int id
	 * @param string wishlist_id
	 * @returns String
	 */
	function WishlistCookie( id, wishlist_key = '' ) {
		var cookies = getCookie( 'shoppress_wishlist' );

		if ( null != cookies && 'undefined' !== typeof cookies[ 1 ] ) {
			cookies = JSON.parse( cookies );
		} else {
			cookies = {};
		}

		var saved_wishlist_key =
			'undefined' !== typeof cookies[ id ] ? cookies[ id ] : '';
		if ( saved_wishlist_key.length ) {
			delete cookies[ id ];
		} else {
			cookies[ id ] = wishlist_key;
		}

		setCookie( 'shoppress_wishlist', JSON.stringify( cookies ), 10 );
	}

	/**
	 * Generate wishlist array for cookies
	 *
	 * @param Object id
	 * @returns HTML
	 */
	function WishlistNotice( data ) {
		if ( 0 == $( 'body .shoppress-popup-notices-wrap' ).length ) {
			$( 'body' ).append(
				'<div class="shoppress-popup-notices-wrap"></div>'
			);
		}

		if ( 'undefined' == typeof data.message_type ) {
			data.message_type = 'success';
		}

		var action = 'yes' === data.status ? 'add' : 'remove';

		if (
			'undefined' !==
			typeof shoppress_frontend.wishlist[ action ].notice_status
		) {
			if ( ! shoppress_frontend.wishlist[ action ].notice_status ) {
				return;
			}
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

	function copyInputValToClipboard( element ) {
		var $temp = $( '<input>' );
		$( 'body' ).append( $temp );
		$temp.val( $( element ).val() ).select();
		document.execCommand( 'copy' );
		$temp.remove();
	}

	$( document ).on( 'click', '.sp-wishlist-share', function ( e ) {
		var $item = $( this );
		var wishlist_link = $item.data( 'wishlist_link' );
		var wishlist_text = $item.data( 'wishlist_title' );
		if ( $( '#sp-wishlist-share-popup' ).length ) {
			$( '#sp-wishlist-share-popup .sp-wishlist-link' ).val(
				wishlist_link
			);

			$( '.sp-wishlist-share-links .sp-wishlist-share-link a' ).each(
				function ( i, link ) {
					var share_link = $( link ).data( 'pattern' );
					share_link = share_link.replace( '{url}', wishlist_link );
					share_link = share_link.replace( '{text}', wishlist_text );
					$( link ).attr( 'href', share_link );
				}
			);

			$( '#sp-wishlist-share-popup' ).fadeIn();
		}
	} );

	$( document ).on(
		'click',
		'.sp-wishlist-link-wrapper .sp-copy-to-clipboard',
		function ( e ) {
			copyInputValToClipboard(
				$( this )
					.closest( '.sp-wishlist-link-wrapper' )
					.find( '.sp-wishlist-link' )
			);
		}
	);

	$( document ).on(
		'click',
		'.sp-wishlist-loop, .sp-wishlist, .sp-rmf-wishlist',
		function () {
			var $button = $( this ),
				$wrap =
					$button.closest( '.sp-wishlist-button-container' ).length >
					0
						? $button.closest( '.sp-wishlist-button-container' )
						: $button.closest( '.sp-wishlist' );
			$wrap = $wrap.length ? $wrap : $button;
			var $id = $wrap.data( 'product_id' ),
				datastatus =
					$wrap.attr( 'data-status' ) == 'yes' ? true : false,
				$wishlist_key = $wrap.data( 'wishlist_key' );
			var $buttons = $(
				'.sp-wishlist-button-container[data-product_id="' +
					$id +
					'"], .sp-wishlist[data-product_id="' +
					$id +
					'"]'
			);
			var is_my_wishlist =
				$button.closest( '.sp-my-wishlist' ).length > 0 ? true : false;
			var is_menu_wishlist =
				$button.closest( '#sp-wishlist-dd-menu_wishlist' ).length > 0
					? true
					: false;
			var is_drawer =
				$button.closest( '.sp-drawer' ).length > 0 ? true : false;
			var is_new_wishlist = 'add_new' == $wishlist_key ? true : false;

			if ( $button.find( 'a' ).length ) {
				return;
			}

			if (
				! $wishlist_key &&
				$( '#sp-multi-wishlist-popup' ).length &&
				! $button.hasClass( 'sp-add-to-wishlist-multi-wishlist' )
			) {
				$(
					'#sp-multi-wishlist-popup #sp-add-to-wishlist-multi-wishlist'
				).data( 'product_id', $id );
				// $('#sp-multi-wishlist-popup #sp-add-to-wishlist-multi-wishlist').data('wishlist_key','');
				$( '#sp-multi-wishlist-popup #sp-wishlist-key' ).trigger(
					'change'
				);

				$( '#sp-new-wishlist-title' ).val( '' );
				$( '.sp-wishlist-share-status[value="private"]' ).trigger(
					'click'
				);
				$( '#sp-new-wishlist-author' ).val( '' );
				$( '#sp-new-wishlist-author-email' ).val( '' );
				$( '#sp-multi-wishlist-popup' ).fadeIn();
				return;
			}

			$buttons.addClass( 'loading' );

			var data = {
				action: 'AddWishlist',
				nonce: shoppress_frontend.ajax.nonce,
				product_id: $id,
				wishlist_key: $wishlist_key,
			};

			if ( is_new_wishlist ) {
				data[ 'wishlist_title' ] = $( '#sp-new-wishlist-title' ).val();
				data[ 'wishlist_share_status' ] = $(
					'.sp-wishlist-share-status:checked'
				).val();
				data[ 'author_name' ] = $( '#sp-new-wishlist-author' ).val();
				data[ 'author_email' ] = $(
					'#sp-new-wishlist-author-email'
				).val();
			}

			$.ajax( {
				type: 'POST',
				url: shoppress_frontend.ajax.url,
				data: data,
				success: function ( data, response ) {
					$buttons
						.find( '.sp-wishlist-label' )
						.html( data.data.wishlist_label );

					if ( 'undefined' != typeof data.data.status ) {
						$buttons.attr( 'data-status', data.data.status );
						$buttons.data( 'status', data.data.status );
					}

					if ( 'undefined' != typeof data.data.wishlist_key ) {
						$buttons.attr(
							'data-wishlist_key',
							data.data.wishlist_key
						);
						$buttons.data( 'wishlist_key', data.data.wishlist_key );

						if ( is_new_wishlist ) {
							$(
								'#sp-multi-wishlist-popup #sp-wishlist-key'
							).prepend(
								'<option value="' +
									data.data.wishlist_key +
									'">' +
									$( '#sp-new-wishlist-title' ).val() +
									'</option>'
							);
						}

						WishlistCookie( $id, data.data.wishlist_key );
					}

					$( '.sp-wishlist-items .sp-wishlist-dd' ).html(
						data.data.wishlist_menu
					);

					$( '.sp-header-wishlist.dropdown .sp-wishlist-items' ).html(
						data.data.wishlist_menu
					);
					$( '.sp-header-wishlist.dropdown .sp-wishlist-items' )
						.find( '.sp-wishlist-title' )
						.remove();

					$( '.sp-mini-wishlist-drawer .sp-wishlist-items' ).html(
						data.data.wishlist_menu
					);
					$( '.sp-mini-wishlist-drawer .sp-wishlist-items' )
						.find( '.sp-wishlist-title' )
						.remove();

					if ( is_drawer ) {
						$button
							.closest( '.sp-drawer' )
							.addClass( 'sp-open-drawer' );
					}

					if (
						$button.hasClass(
							'sp-add-to-wishlist-multi-wishlist'
						) &&
						'success' === data.data.message_type
					) {
						$( '#sp-multi-wishlist-popup' ).fadeOut();
					}

					if ( is_my_wishlist ) {
						var $Item = $button.closest(
							'.sp-wishlist-button-container, .sp-my-wishlist-item'
						);

						if ( 1 === $( '.sp-my-wishlist .sp-product' ).length ) {
							window.location.reload();
						}

						$Item.fadeOut( 500, function () {
							$Item.remove();
						} );
					} else if (
						'undefined' != typeof data.data.popup_html &&
						data.data.popup_html.length
					) {
						$( 'body' ).append( data.data.popup_html );
					} else {
						WishlistNotice( data.data );
					}

					$( '.sp-wishlist-items-count' ).html(
						data.data.wishlist_count
					);

					$buttons.removeClass( 'loading' );
				},
				error: function ( data, response ) {
					console.log( data );
					WishlistNotice( data.responseJSON.data );
					// $wrap.css('opacity', '1');
					$buttons.removeClass( 'loading' );
				},
			} );
		}
	);

	$( '#sp-multi-wishlist-popup #sp-wishlist-key' ).on( 'change', function () {
		$( this )
			.closest( '#sp-multi-wishlist-popup' )
			.find( '#sp-add-to-wishlist-multi-wishlist' )
			.data( 'wishlist_key', $( this ).val() );

		if ( 'add_new' === $( this ).val() ) {
			$( '.sp-new-wishlist' ).fadeIn();
		} else {
			$( '.sp-new-wishlist' ).fadeOut();
		}
	} );

	$( document ).on( 'change', '.sp-wishlist-bulk-actions', function ( e ) {
		var action = $( this ).val();
		if ( 'move_to_another_wishlist' === action ) {
			$( '.sp-wishlist-move-to-wrapper' ).fadeIn();
		} else {
			$( '.sp-wishlist-move-to-wrapper' ).fadeOut();
		}
	} );
	$( '.sp-wishlist-bulk-actions' ).trigger( 'change' );

	$( document ).on( 'click', '.sp-run-bulk-action', function ( e ) {
		var $items_wrapper = $( '.my-wishlist-table' );
		var product_ids = [];
		var selected_ids = $(
			'[name="sp-wishlist-products[]"]',
			$items_wrapper
		).serializeArray();
		$.each( selected_ids, function ( i, product ) {
			product_ids.push( product.value );
		} );

		var action2 = $( '.sp-wishlist-bulk-actions' ).val();

		var data = {
			action: 'sp-my-wishlist-run-action',
			action2: action2,
			product_ids: product_ids,
			nonce: shoppress_frontend.ajax.nonce,
			current_wishlist_key: $items_wrapper.data( 'wishlist_key' ),
		};

		if ( 'move_to_another_wishlist' === action2 ) {
			data[ 'move_to' ] = $( '.sp-wishlist-move-to' ).val();
		}

		$.ajax( {
			type: 'POST',
			url: shoppress_frontend.ajax.url,
			data: data,
			success: function ( data, response ) {
				if ( data.success ) {
					switch ( action2 ) {
						case 'move_to_another_wishlist':
						case 'remove_wishlist':
							$.each( product_ids, function ( i, product_id ) {
								$(
									'.sp-wishlist-' + product_id,
									$items_wrapper
								)
									.fadeOut( 500 )
									.remove();
							} );
							break;
						case 'add_to_cart':
							break;
					}
				}

				$( document.body ).trigger( 'sp_update_fragments', [
					data.data,
				] );
			},
			error: function ( data, response ) {},
		} );
	} );
}

function shoppress_menu_wishlist_init( $ ) {
	$( document ).on( 'click', '.sp-header-wishlist', function ( e ) {
		if ( ! $( e.target ).is( 'a' ) ) {
			e.preventDefault();
		}

		const dropdown = $( e.target )
			.closest( '.sp-open-dropdown' )
			.find( '.sp-wishlist-dd' );
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
}

( function ( $ ) {
	shoppress_wishlist_init( $ );
	shoppress_menu_wishlist_init( $ );
} )( jQuery );
