function closestParent( element, selector ) {
	while ( element ) {
		if ( element.matches( selector ) ) {
			return element;
		}
		element = element.parentElement;
	}
	return null;
}

function shoppress_drawer_init() {
	jQuery( document ).on( 'click', '.sp-drawer-click', function ( e ) {
		e.preventDefault();
		var target_id =
			'undefined' !== typeof jQuery( this ).data( 'drawer-target' )
				? jQuery( this ).data( 'drawer-target' )
				: '';
		var $this = jQuery( this ),
			$wrap = target_id
				? jQuery( '#' + target_id )
				: $this.closest( '.sp-drawer' ),
			$content_wrap = $wrap.find( '.sp-drawer-content-wrap' );
		var is_open = $wrap.hasClass( 'sp-open-drawer' );

		jQuery( '.sp-drawer' ).removeClass( 'sp-open-drawer' );

		if ( is_open ) {
			$wrap.removeClass( 'sp-open-drawer' );
		} else {
			$wrap.addClass( 'sp-open-drawer' );
		}
	} );

	jQuery( document ).on( 'click', '.sp-drawer-close', function ( e ) {
		jQuery( this ).closest( '.sp-drawer' ).removeClass( 'sp-open-drawer' );
	} );
}

function shoppress_accordion_init() {
	document.addEventListener( 'click', function ( event ) {
		var target = event.target;

		if (
			target.classList.contains( 'sp-accordion-item-header' ) ||
			target.closest( '.sp-accordion-item-header' ) !== null
		) {
			var wrap = closestParent( target, '.sp-accordions-container' );
			var item = closestParent( target, '.sp-accordion-item' );
			var itemContent = item.querySelector(
				'.sp-accordion-item-content'
			);
			var isOpen = item.classList.contains( 'open' );
			var allItems = wrap.querySelectorAll( '.sp-accordion-item' );

			allItems.forEach( function ( accItem ) {
				accItem.classList.remove( 'open' );
			} );

			var height = itemContent.scrollHeight;
			itemContent.style.maxHeight = isOpen ? '' : height + 'px';

			if ( ! isOpen ) {
				item.classList.add( 'open' );
			}
		}
	} );

	function closestParent( element, selector ) {
		while ( element ) {
			if ( element.matches( selector ) ) {
				return element;
			}
			element = element.parentElement;
		}
		return null;
	}
}

function shoppress_quantity_controls_init() {
	document.addEventListener( 'click', function ( e ) {
		if (
			e.target.classList.contains( 'sp-quantity-control' ) ||
			e.target.closest( '.sp-quantity-control' ) !== null
		) {
			e.preventDefault();

			var controlBtn = e.target.classList.contains(
				'sp-quantity-control'
			)
				? e.target
				: e.target.closest( '.sp-quantity-control' );
			var inputWrap = closestParent(
				e.target,
				'.sp-quantity-input-modern-wrap'
			);
			var input = inputWrap.querySelector( 'input[type=number]' );

			if ( controlBtn.classList.contains( 'minus' ) ) {
				input.stepDown();
			} else {
				input.stepUp();
			}

			const event = new Event( 'change', { bubbles: true } );
			input.dispatchEvent( event );
		}
	} );
}

function sp_slider_init() {
	const slider = jQuery( '.sp-slider' );
	const hasSlider = slider.length !== 0;

	if ( ! hasSlider ) {
		return false;
	}

	jQuery.each( slider, function ( index, element ) {
		const item = jQuery( element );
		if ( item.hasClass( 'sp-slider-init' ) === false ) {
			item.slick();
			item.addClass( 'sp-slider-init' );
		}
	} );
}

function sp_product_slider_init() {
	const sliderWarp = jQuery( '.sp-products-slider' );
	const hasSlider = sliderWarp.length !== 0;
	const hasSlick = 'slick' in jQuery.fn && typeof jQuery.fn.slick === 'function';

	if ( ! hasSlider ) {
		return false;
	}

	jQuery.each( sliderWarp, function ( index, element ) {
		const item = jQuery( element );
		const attrs = item.data( 'spslider' ) ?? {};
		const products = item.find( '.products' );
		const hasProducts = products.length !== 0;

		if ( hasSlick && hasProducts && products.hasClass( 'sp-slider-init' ) === false ) {
			products.slick( attrs );
			products.addClass( 'sp-slider-init' );
		}
	} );
}

function sp_thumbnail_loop_slider() {
	const sliderLink = jQuery( '.sp-loop-slider a' );
	const hasLink = sliderLink.length !== 0;

	if ( ! hasLink ) {
		return false;
	}

	sliderLink.on( 'click', function ( e ) {
		const target = jQuery( e.target );

		if (
			target.closest( '.slick-arrow' ).length ||
			target.closest( '.slick-dots' ).length
		) {
			e.preventDefault();
		}
	} );
}

( 'use strict' );
( function ( $ ) {
	var show_notice = function ( html_element, $target ) {
		if ( ! $target ) {
			$target =
				$( '.woocommerce-notices-wrapper:first' ) ||
				$( '.wc-empty-cart-message' ).closest( '.woocommerce' ) ||
				$( '.woocommerce-cart-form' );
		}
		if ( $target.hasClass( 'woocommerce-notices-wrapper' ) ) {
			$target.empty();
		}
		$target.prepend( html_element );
	};

	$( document ).ready( function () {
		sp_slider_init();
		sp_product_slider_init();
		sp_thumbnail_loop_slider();
		shoppress_drawer_init();
		shoppress_accordion_init();
		shoppress_quantity_controls_init();

		$( document.body ).on( 'sp_update_fragments', function ( e, data ) {
			if ( 'undefined' !== typeof data.fragments ) {
				$.each( data.fragments, function ( key, value ) {
					$( key ).replaceWith( value );
				} );
			}
			if ( 'undefined' !== typeof data.message_html ) {
				show_notice( data.message_html );
			}

			$( document.body ).trigger( 'sp_fragments_refreshed' );
		} );

		$( document ).on( 'click', '.sp-addtocart', function () {
			let elementButton = $( this );
			elementButton.addClass( 'loading' );
			let id = $( this ).data( 'product_id' );

			if ( $( '.post-' + id + ' .product_type_variable' ).length > 0 ) {
				window.location.href = $(
					'.post-' + id + ' .product_type_variable'
				).attr( 'href' );
			} else {
				$( '.post-' + id + ' .add_to_cart_button' )
					.first()
					.click();
				// jQuery('.sp-mc-item .sp-header-cart span').html(parseInt(jQuery('.sp-mc-item .sp-header-cart span').html())+1);
				setTimeout( function () {
					$.ajax( {
						type: 'POST',
						url: shoppress_frontend.ajax.url,
						data: {
							action: 'sp_mc_update',
							nonce: shoppress_frontend.ajax.nonce,
						},
						success: function ( data ) {
							$( '.sp-mc-item' ).html( data.content );
							$(
								'.sp-cart-items-count, .sp-mc-item .sp-header-cart span'
							).html( data.count );
							$( '.elementor-widget-kata-plus-cart .count' ).html(
								data.count
							);
							elementButton.removeClass( 'loading' );
						},
						error: function ( data ) {
							console.log( data );
							elementButton.removeClass( 'loading' );
						},
					} );
				}, 1000 );
			}
		} );

		$( document.body ).on(
			'removed_from_cart',
			function ( event, fragments, cart_hash, $button ) {
				var is_mini_cart_drawer =
					$button.closest( '#sp-mini-cart-drawer' ).length > 0
						? true
						: false;
				var is_mc_cart_menu_drawer =
					$button.closest( '#sp-mc-drawer-menu_cart' ).length > 0
						? true
						: false;

				if ( is_mini_cart_drawer ) {
					var html = $( fragments[ '#sp-mini-cart-drawer' ] )
						.clone()
						.wrap( '<div />' );
					html = $( html ).addClass( 'sp-open-drawer' );
					fragments[ '#sp-mini-cart-drawer' ] = html;
				} else if ( is_mc_cart_menu_drawer ) {
					var html = $( fragments[ '#sp-mc-drawer-menu_cart' ] )
						.clone()
						.wrap( '<div />' );
					html = $( html ).addClass( 'sp-open-drawer' );
					fragments[ '#sp-mc-drawer-menu_cart' ] = html;
				}
			}
		);

		$( document ).on( 'click', '.sp-close-popup', function () {
			$( this ).closest( '.sp-popup-overlay' ).fadeOut();
		} );

		$( document ).on( 'click', function ( e ) {
			var is_dropdown_link =
				$( e.target ).hasClass( 'sp-open-dropdown' ) ||
				$( e.target ).closest( '.sp-open-dropdown' ).length
					? true
					: false;
			if (
				! is_dropdown_link &&
				0 == $( e.target ).closest( '.sp-dropdown-opened' ).length
			) {
				$( '.sp-dropdown-opened' ).fadeOut( 500 );
				$( '.sp-dropdown-opened' ).removeClass( 'sp-dropdown-opened' );
			}
		} );

		// Convert dropdown to drawer on mobile
		if ( $( window ).width() <= 480 ) {
			$( '[class*="dropdown"]' ).each( function () {
				var className = $( this ).attr('class');
				if ( className.includes('sp-header-toggle') ) {
					$( this ).attr( 'class', function ( i, className ) {
						return className.replace( /dropdown/g, 'drawer' );
					} );
				}
			} );
		}


		$( document ).on(
			'added_to_cart',
			function ( event, fragments, cart_hash, $button ) {
				if (
					shoppress_frontend.mini_cart_drawer
						?.open_mini_cart_drawer_on_add_to_cart
				) {
					setTimeout( function () {
						$( '#sp-mini-cart-drawer' ).addClass(
							'sp-open-drawer'
						);

						$( document ).trigger(
							'sp-auto-opened-mini-cart-drawer'
						);
					}, 500 );
				}
			}
		);

		$( document ).on( 'sp-auto-opened-mini-cart-drawer', function ( e ) {
			if (
				'undefined' !==
					typeof shoppress_frontend.mini_cart_drawer
						.open_mini_cart_drawer_on_add_to_cart &&
				shoppress_frontend.mini_cart_drawer
					.open_mini_cart_drawer_on_add_to_cart &&
				'undefined' !==
					typeof shoppress_frontend.mini_cart_drawer
						.auto_close_mini_cart_drawer &&
				shoppress_frontend.mini_cart_drawer.auto_close_mini_cart_drawer
			) {
				setTimeout( function () {
					$( '#sp-mini-cart-drawer' ).removeClass( 'sp-open-drawer' );
				}, shoppress_frontend.mini_cart_drawer.auto_close_mini_cart_drawer_time * 1000 );
			}
		} );

		$( document ).on( 'submit', 'form.sp-form-coupon', function ( e ) {
			e.preventDefault();
			var form = $( this );
			form.block( {
				message: null,
				overlayCSS: { background: '#FFF', opacity: 0.6 },
			} );
			$.post(
				shoppress_frontend.ajax.url,
				{
					action: 'sp_apply_coupon',
					coupon_code: form.find( '[name="coupon_code"]' ).val(),
				},
				function ( data ) {
					if ( ! data ) {
						return;
					}

					$( document.body ).on(
						'sp_fragments_refreshed',
						function () {
							$( '#sp-mini-cart-drawer' ).addClass(
								'sp-open-drawer'
							);
						}
					);
					$( document.body ).trigger( 'sp_update_fragments', [
						data.data,
					] );

					form.unblock();
				}
			);
		} );

		$( document ).on(
			'change',
			'.sp-cart-item-quantity .quantity .qty',
			function ( e ) {
				e.preventDefault();
				var form = $( this );
				form.block( {
					message: null,
					overlayCSS: { background: '#FFF', opacity: 0.6 },
				} );
				$.post(
					shoppress_frontend.ajax.url,
					{
						action: 'sp_update_item_quantity',
						cart_item_key: $( this )
							.closest( '.sp-cart-item-pr' )
							.data( 'cart_item_key' ),
						quantity: $( this ).val(),
					},
					function ( data ) {
						if ( ! data ) {
							return;
						}

						show_notice( data.message_html );

						$( document.body ).trigger( 'wc_fragment_refresh' );
						$( document.body ).on(
							'wc_fragments_refreshed',
							function () {
								$( '#sp-mini-cart-drawer' ).addClass(
									'sp-open-drawer'
								);
							}
						);
						form.unblock();
					}
				);
			}
		);

		function copyInputValToClipboard( element ) {
			var $temp = $( '<input>' );
			$( 'body' ).append( $temp );
			$temp.val( $( element ).val() ).select();
			document.execCommand( 'copy' );
			$temp.remove();
		}

		$( document ).on(
			'click',
			'.sp-share-link-wrapper .sp-copy-to-clipboard',
			function ( e ) {
				copyInputValToClipboard(
					$( this )
						.closest( '.sp-share-link-wrapper' )
						.find( '.sp-share-link-input' )
				);
			}
		);

		$( document ).trigger( 'sp-frontend-init' );
	} );
} )( jQuery );
