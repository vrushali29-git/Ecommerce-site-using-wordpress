( function ( $ ) {
	/**
	 * @param $scope The Widget wrapper element as a jQuery element
	 * @param $ The jQuery alias
	 */
	var ShopPressWidgetProductsHandler = ( function ( $scope, $ ) {
		function setup_slider() {
			if (
				typeof sp_slider_init === 'function' ||
				typeof sp_thumbnail_loop_slider === 'function' ||
				typeof sp_product_slider_init === 'function'
			) {
				sp_slider_init();
				sp_thumbnail_loop_slider();
				sp_product_slider_init();
			}
		}
		if ( 'undefined' === typeof ShopPressWidgetProductFiltersHandler ) {
			jQuery( document ).on( 'click', '.sp-loadmore', function ( e ) {
				e.preventDefault();

				var parent = jQuery( '.sp-products-loop-wrapper' );

				if (
					! jQuery( 'a.next.page-numbers', parent ).attr( 'href' )
				) {
					jQuery( e.currentTarget )
						.parents( '.sp-loadmore-wrapper' )
						.fadeOut();
					return;
				}

				jQuery( parent ).css( 'opacity', '0.6' );

				jQuery.ajax( {
					type: 'GET',
					url: jQuery( 'a.next.page-numbers', parent ).attr( 'href' ),
					dataType: 'html',
					data: {
						url: jQuery( 'a.next.page-numbers', parent ).attr(
							'href'
						),
					},
					error: function ( textStatus, errorThrown ) {
						alert( textStatus + ': ' + errorThrown );
						jQuery( parent ).css( 'opacity', 1 );
					},
					success: function ( data ) {
						jQuery( '.products', parent ).append(
							jQuery( '.products', data ).html()
						);
						jQuery( '.woocommerce-pagination', parent ).html(
							jQuery( '.woocommerce-pagination', data ).html()
						);

						if (
							jQuery(
								'.woocommerce-pagination a.next.page-numbers',
								data
							).length == 0
						) {
							jQuery( '.sp-loadmore-wrapper' ).css(
								'display',
								'none'
							);
						}

						jQuery( parent ).css( 'opacity', 1 );
						setup_slider();
					},
				} );
			} );

			jQuery( '.sp-infinite-scroll' ).each( function ( index, element ) {
				var parent = $scope;
				var offset = jQuery( element ).offset();
				var top = offset.top;
				var height = jQuery( element )
					.closest( '.sp-products-loop-wrapper' )
					.innerHeight();
				var max = Math.round(
					document
						.querySelector( '.sp-infinite-scroll' )
						.getBoundingClientRect().top
				);
				jQuery( window ).scroll( function () {
					height = jQuery( element )
						.closest( '.sp-products-loop-wrapper' )
						.innerHeight();
					if (
						jQuery( window ).scrollTop() >= top - height &&
						jQuery( window ).scrollTop() <= max
					) {
						if (
							! jQuery( 'a.next.page-numbers', parent ).attr(
								'href'
							) ||
							jQuery( element ).hasClass( 'loading' )
						) {
							return;
						}
						if ( ! jQuery( element ).hasClass( 'loading' ) ) {
							jQuery( element ).addClass( 'loading' );
						}
						jQuery( parent ).css( 'opacity', '0.5' );
						jQuery.ajax( {
							type: 'GET',
							url: jQuery( 'a.next.page-numbers', parent ).attr(
								'href'
							),
							data: {
								url: jQuery(
									'a.next.page-numbers',
									parent
								).attr( 'href' ),
							},
							dataType: 'html',
							error: function ( textStatus, errorThrown ) {
								alert( textStatus + ': ' + errorThrown );
								jQuery( parent ).css( 'opacity', 1 );
								jQuery( element ).removeClass( 'loading' );
							},
							success: function ( data ) {
								jQuery( '.products', parent ).append(
									jQuery( '.products', data ).html()
								);
								jQuery(
									'.woocommerce-pagination',
									parent
								).html(
									jQuery(
										'.woocommerce-pagination',
										data
									).html()
								);
								jQuery( parent ).css( 'opacity', 1 );
								jQuery( element ).removeClass( 'loading' );
								setup_slider();
							},
						} );
					}
				} );
			} );
		}

		if ( typeof sp_product_slider_init === 'function' ) {
			sp_product_slider_init();
		}
	} )( jQuery );

	jQuery( window ).on( 'elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/sp-products-loop.default',
			'frontend/element_ready/sp-products.default',
			ShopPressWidgetProductsHandler
		);
	} );
} )( jQuery );
