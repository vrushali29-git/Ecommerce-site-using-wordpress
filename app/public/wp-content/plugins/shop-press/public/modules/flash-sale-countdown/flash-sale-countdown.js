function sp_flash_sale_countdown( el, timeend ) {
	if ( timeend == '' ) {
		return;
	}

	var countDownDate = new Date( timeend + ' 23:59:59' ).getTime();

	var now = new Date().getTime();

	var distance = countDownDate - now;

	var days = Math.floor( distance / ( 1000 * 60 * 60 * 24 ) );

	var hours = Math.floor(
		( distance % ( 1000 * 60 * 60 * 24 ) ) / ( 1000 * 60 * 60 )
	);
	var minutes = Math.floor(
		( distance % ( 1000 * 60 * 60 ) ) / ( 1000 * 60 )
	);
	var seconds = Math.floor( ( distance % ( 1000 * 60 ) ) / 1000 );

	var daysHtml =
		'<li class="fc-countdown-days"><div>' +
		days +
		'</div><span>' +
		shoppress_frontend.i18n.days +
		'</span></li>';
	var hoursHtml =
		'<li class="fc-countdown-hours"><div>' +
		hours +
		'</div><span>' +
		shoppress_frontend.i18n.hours +
		'</span></li>';
	var minutesHtml =
		'<li class="fc-countdown-minutes"><div>' +
		minutes +
		'</div><span>' +
		shoppress_frontend.i18n.minutes +
		'</span></li>';
	var secondsHtml =
		'<li class="fc-countdown-seconds"><div>' +
		seconds +
		'</div><span>' +
		shoppress_frontend.i18n.seconds +
		'</span></li>';

	jQuery( el ).html(
		'<ul>' + daysHtml + hoursHtml + minutesHtml + secondsHtml + '</ul>'
	);

	if ( distance < 0 ) {
		clearInterval( x );
		jQuery( el ).html( 'EXPIRED' );
	}
}

function sp_flash_sale_countdown_init() {
	var flash_sale_countdown = setInterval( function () {
		jQuery.each( jQuery( '.fs-countdown' ), function ( i, el ) {
			var timeend = jQuery( el ).data( 'timeend' );
			sp_flash_sale_countdown( el, timeend );
		} );
	}, 1000 );
}
jQuery( window ).ready( function ( $ ) {
	sp_flash_sale_countdown_init();

	$( window ).on( 'elementor/frontend/init', function ( $scope ) {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/sp-item-flash-sale-countdown.default',
			function () {
				sp_flash_sale_countdown_init();
			}
		);
	} );
} );
