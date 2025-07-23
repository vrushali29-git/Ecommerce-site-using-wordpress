( function ( $ ) {
	'use strict';
	$( document ).ready( function () {
		$.each( $( '.sp-notification-item-content' ), function ( i, el ) {
			var $wrap = $( el ).closest( '.sp-notification-item' );
			if ( el.offsetWidth < el.scrollWidth ) {
				$wrap
					.find( '.sp-notification-item-content-show-more svg' )
					.css( 'display', 'flex' );
			}
		} );

		$( document ).on(
			'click',
			'.sp-notification-item-content-show-more svg',
			function ( e ) {
				e.preventDefault();

				jQuery( this )
					.closest( '.sp-notification-item-content-show-more' )
					.toggleClass( 'show-more' )
					.closest( '.sp-notification-item' )
					.find( '.sp-notification-item-content' )
					.toggleClass( 'show-more' );
			}
		);

		$( document ).on(
			'click',
			'.sp-notification-view-add-review-open-popup',
			function ( e ) {
				e.preventDefault();
				var $this = $( this ),
					$id = $this.attr( 'href' );

				$( $id ).addClass( 'open' );
				$( $id ).fadeIn();
			}
		);

		$( document ).on(
			'click',
			'.sp-notification-view-add-review-close-popup',
			function () {
				$( this )
					.closest( '.sp-notification-view-add-review-popup' )
					.removeClass( 'open' )
					.fadeOut();
			}
		);
	} );
} )( jQuery );
