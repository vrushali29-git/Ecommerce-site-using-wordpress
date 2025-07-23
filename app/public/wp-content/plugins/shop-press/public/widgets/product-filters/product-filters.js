( function ( $ ) {
	/**
	 * @param $scope The Widget wrapper element as a jQuery element
	 * @param $ The jQuery alias
	 */
	var ShopPressWidgetProductFiltersHandler = function ( $scope, $ ) {
		const shopUrl = removeAfterShopAndParams( window.location.href );

		function removeAfterShopAndParams( url ) {
			const parsedUrl = new URL( url );
			const segments = parsedUrl.pathname.split( '/' ).filter( Boolean );
			const shopIndex = segments.indexOf( 'shop' );

			if ( shopIndex !== -1 ) {
				const newSegments = segments.slice( 0, shopIndex + 1 );

				parsedUrl.pathname = '/' + newSegments.join( '/' ) + '/';
			}

			parsedUrl.search = '';
			return parsedUrl.toString();
		}

		function updateUrl( urlPath ) {
			history.pushState( {}, null, urlPath );
		}

		function init_price_filter() {
			if ( typeof woocommerce_price_slider_params === 'undefined' ) {
				return false;
			}

			$( 'input#min_price, input#max_price' ).hide();
			$( '.price_slider, .price_label' ).show();

			var min_price = $( '.price_slider_amount #min_price' ).data(
					'min'
				),
				max_price = $( '.price_slider_amount #max_price' ).data(
					'max'
				),
				step = $( '.price_slider_amount' ).data( 'step' ) || 1,
				current_min_price = $(
					'.price_slider_amount #min_price'
				).val(),
				current_max_price = $(
					'.price_slider_amount #max_price'
				).val();

			$( '.price_slider:not(.ui-slider)' ).slider( {
				range: true,
				animate: true,
				min: min_price,
				max: max_price,
				step: step,
				values: [ current_min_price, current_max_price ],
				create: function () {
					$( '.price_slider_amount #min_price' ).val(
						current_min_price
					);
					$( '.price_slider_amount #max_price' ).val(
						current_max_price
					);

					$( document.body ).trigger( 'price_slider_create', [
						current_min_price,
						current_max_price,
					] );
				},
				slide: function ( event, ui ) {
					$( 'input#min_price' ).val( ui.values[ 0 ] );
					$( 'input#max_price' ).val( ui.values[ 1 ] );

					$( document.body ).trigger( 'price_slider_slide', [
						ui.values[ 0 ],
						ui.values[ 1 ],
					] );
				},
				change: function ( event, ui ) {
					$( document.body ).trigger( 'price_slider_change', [
						ui.values[ 0 ],
						ui.values[ 1 ],
					] );
				},
			} );
		}

		$( document ).on(
			'click change',
			'.woocommerce-widget-layered-nav-list a, .woocommerce-widget-layered-nav-list input[type="checkbox"], .widget_rating_filter, .widget_layered_nav_filters',
			function ( e ) {
				e.preventDefault();

				const { target } = e;
				const href = $( target ).closest( 'a' ).attr( 'href' );

				filterReq( href );
			}
		);

		$( document ).on(
			'submit',
			'.widget_price_filter form, .woocommerce-widget-layered-nav-dropdown',
			function ( e ) {
				e.preventDefault();

				const item = $( this );
				const form = item.closest( 'form' );
				const data = form.serialize();
				const filterUrl = `${ shopUrl }?${ data }`;

				filterReq( filterUrl );
			}
		);

		$( document ).on( 'click', '.sp-reset-filters', function () {
			filterReq( shopUrl );
		} );

		function filterReq( url ) {
			const wrap = $( '.sp-products-loop-wrapper' );
			const filtersWrap = $( '.sp-product-filters' );

			$.ajax( {
				type: 'GET',
				url: url,
				dataType: 'html',
				cache: true,
				beforeSend: function () {
					wrap.find( 'ul.products' ).css( 'opacity', '0.5' );
				},
				success: function ( response ) {
					updateUrl( url );

					wrap.html(
						$( response ).find( '.sp-products-loop-wrapper' ).html()
					);
					filtersWrap.html(
						$( response ).find( '.sp-product-filters' ).html()
					);

					$( document ).ready( function ( $ ) {
						// Run slick
						if (
							typeof sp_slider_init === 'function' &&
							typeof sp_thumbnail_loop_slider === 'function'
						) {
							sp_slider_init();
							sp_thumbnail_loop_slider();
						}

						// Run slider for price
						if ( typeof init_price_filter === 'function' ) {
							init_price_filter();
							$( document.body ).on(
								'init_price_filter',
								init_price_filter
							);
						}

						wrap.find( 'ul.products' ).css( 'opacity', '' );
					} );
				},
				error: function ( error ) {
					console.log( error );
				},
			} );
		}

		function infiniteScroll( index, element ) {
			var offset = $( element ).offset();
			var top = offset.top;
			var wrap = $scope;
			var max = Math.round(
				document
					.querySelector( '.sp-infinite-scroll' )
					.getBoundingClientRect().top
			);
			$( window ).scroll( function () {
				if (
					$( window ).scrollTop() >= top - 500 &&
					$( window ).scrollTop() <= max
				) {
					var next_page_url = $( 'a.next.page-numbers', wrap ).attr(
						'href'
					);

					if (
						! next_page_url ||
						$( element ).hasClass( 'loading' )
					) {
						return;
					}

					if ( ! $( element ).hasClass( 'loading' ) ) {
						$( element ).addClass( 'loading' );
					}

					$( wrap ).css( 'opacity', '0.5' );
					$.ajax( {
						type: 'GET',
						url: next_page_url,
						dataType: 'html',
						cache: true,
						headers: {
							'cache-control': 'no-cache',
						},
						data: {
							url: next_page_url,
						},
						success: function ( data ) {
							var products_html = $( '.products', data ).html();
							var pagination = $(
								'.woocommerce-pagination',
								data
							).html();
							$( '.products', wrap ).append( products_html );
							$( '.woocommerce-pagination', wrap ).html(
								pagination
							);
							$( wrap ).css( 'opacity', 1 );
							$( element ).removeClass( 'loading' );
						},
						error: function ( error ) {
							$( wrap ).css( 'opacity', 1 );
							$( element ).removeClass( 'loading' );
							console.log( error );
						},
					} );
				}
			} );
		}

		$( '.sp-infinite-scroll' ).each( infiniteScroll );
	};

	$( window ).on( 'elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/sp-product-filters.default',
			ShopPressWidgetProductFiltersHandler
		);
	} );
} )( jQuery );
