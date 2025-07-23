var ShopPressWidgetProductsHandler = ( function ( $ ) {
	var $scope;

	function loadMore( e ) {
		e.preventDefault();

		var wrap = $( $scope );
		var next_page_url = $( 'a.next.page-numbers', wrap ).attr( 'href' );

		if ( ! next_page_url ) {
			$( e.currentTarget ).parents( '.sp-loadmore-wrapper' ).fadeOut();
			return;
		}

		$( wrap ).css( 'opacity', '0.6' );
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
				var pagination = $( '.woocommerce-pagination', data ).html();
				$( '.products', wrap ).append( products_html );
				$( '.woocommerce-pagination', wrap ).html( pagination );
				$( wrap ).css( 'opacity', 1 );
			},
			error: function ( XMLHttpRequest, textStatus, errorThrown, data ) {
				alert( textStatus + ': ' + errorThrown );
				$( wrap ).css( 'opacity', 1 );
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

				if ( ! next_page_url || $( element ).hasClass( 'loading' ) ) {
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
						$( '.woocommerce-pagination', wrap ).html( pagination );

						if (
							$(
								'.woocommerce-pagination a.next.page-numbers',
								data
							).length == 0
						) {
							$( '.sp-loadmore-wrapper', wrap ).css(
								'display',
								'none'
							);
						}
						$( wrap ).css( 'opacity', 1 );
						$( element ).removeClass( 'loading' );
					},
					error: function (
						XMLHttpRequest,
						textStatus,
						errorThrown,
						data
					) {
						alert( textStatus + ': ' + errorThrown );
						$( wrap ).css( 'opacity', 1 );
						$( element ).removeClass( 'loading' );
					},
				} );
			}
		} );
	}

	function filter( e ) {
		e.preventDefault();

		var wrap = $( e.currentTarget ).closest( '.sp-categories-filter' );
		var parent = wrap.closest( '.elementor-widget-sp-recent-products' );
		var category_id = $( e.currentTarget ).data( 'cat-id' );
		var post_id = wrap.closest( '.elementor' ).data( 'elementor-id' );
		var widget_id = wrap.data( 'widget_id' );
		var loop_section = $( wrap ).next();

		$( loop_section ).css( 'opacity', '0.5' );

		var data = {
			action: 'shoppress-woo-category-filter',
			nonce: shoppress_frontend.nonce,
			category_id: category_id,
			post_id: post_id,
			widget_id: widget_id,
		};

		$.ajax( {
			type: 'POST',
			url: shoppress_frontend.ajaxurl,
			data: data,
			success: function ( res ) {
				var products_html = $( '.products', res ).html();
				$( '.products', parent ).html( products_html );
				$( loop_section ).css( 'opacity', '1' );
			},
			error: function ( err ) {
				console.log( err );
			},
		} );
	}

	return {
		init: function ( $sc, $ ) {
			$scope = $sc;
			$( document ).on( 'click', '.sp-loadmore', loadMore );
			$( '.sp-infinite-scroll' ).each( infiniteScroll );
			$( document ).on( 'click', '.sp-woo-cat-filter', filter );
		},
	};
} )( jQuery );

jQuery( document ).ready( function ( $ ) {
	$( window ).on( 'elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/sp-recent-products.default',
			ShopPressWidgetProductsHandler.init
		);
	} );
} );
