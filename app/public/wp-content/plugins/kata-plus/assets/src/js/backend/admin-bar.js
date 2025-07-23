'use strict';
(function ($) {
	$(document).ready(function () {
		setTimeout(function () {
			// Draggable
			$('.kata-finder').draggable({
				handle: '.kata-dialog-header',
				stop: function () {
					if (jQuery(this).css('top') < '0') {
						jQuery(this).css('top', '0');
					}
				},
			});
		}, 1000);
		// Scrollbar
		$(
			'.kata-dialog-body,.wp-full-overlay-sidebar-content, .accordion-container, .available-menu-items-list, .fonts-manager-sticky'
		).niceScroll({
			cursorcolor: '#aaa', // change cursor color in hex
			cursoropacitymin: 0, // change opacity when cursor is inactive (scrollabar "hidden" state), range from 1 to 0
			cursoropacitymax: 1, // change opacity when cursor is active (scrollabar "visible" state), range from 1 to 0
			cursorwidth: '7px', // cursor width in pixel (you can also write "5px")
			cursorborder: 'none', // css definition for cursor border
			cursorborderradius: '5px', // border radius in pixel for cursor
			scrollspeed: 60, // scrolling speed
			mousescrollstep: 40, // scrolling speed with mouse wheel (pixel)
			hwacceleration: true, // use hardware accelerated scroll when supported
			gesturezoom: true, // (only when boxzoom=true and with touch devices) zoom activated when pinch out/in on box
			grabcursorenabled: true, // (only when touchbehavior=true) display "grab" icon
			autohidemode: true, // how hide the scrollbar works, possible values:
			spacebarenabled: true, // enable page down scrolling when space bar has pressed
			railpadding: {
				top: 0,
				right: 1,
				left: 0,
				bottom: 1,
			}, // set padding for rail bar
			disableoutline: true, // for chrome browser, disable outline (orange highlight) when selecting a div with nicescroll
			horizrailenabled: false, // nicescroll can manage horizontal scroll
			railalign: 'right', // alignment of vertical rail
			railvalign: 'bottom', // alignment of horizontal rail
			enablemousewheel: true, // nicescroll can manage mouse wheel events
			enablekeyboard: true, // nicescroll can manage keyboard events
			smoothscroll: true, // scroll with ease movement
			cursordragspeed: 0.3, // speed of selection when dragged with cursor
		});

		// Finder Hotkey
		document.onkeydown = function (e) {
			if (e.key == 'F' && e.shiftKey && e.ctrlKey) {
				jQuery('.kata-admin-bar-finder-btn').trigger('click');
			}
		};

		// Finder Seach Focus
		$('.kata-admin-bar-finder-btn, .kata-admin-bar-finder').on(
			'click',
			function () {
				// nicescroll resize
				setTimeout(function () {
					$(
						'.kata-dialog-body,.wp-full-overlay-sidebar-content, .accordion-container, .available-menu-items-list'
					)
						.getNiceScroll()
						.resize();
				}, 200);
				// display finder dialog
				$('#kata-finder').show();
				$(this).addClass('active');
				// focus on search fild
				$('#kata-finder-search-input').focus();
			}
		);
		$('.wp-customizer .accordion-section, .accordion-section-title').on(
			'click',
			function () {
				setTimeout(function () {
					$(
						'.wp-full-overlay-sidebar-content, .accordion-container, .available-menu-items-list'
					)
						.getNiceScroll()
						.resize();
				}, 200);
			}
		);
		$('#kata-finder')
			.find('.kata-dialog-close-btn i')
			.on('click', function () {
				$(
					'.kata-admin-bar-finder-btn, .kata-admin-bar-finder'
				).removeClass('active');
			});

		// Themeplate manager
		$('.kata-admin-bar-template-btn').on('click', function () {
			$(this).addClass('active');
			setTimeout(function () {
				$('.elementor-templates-modal__header__close--normal')
					.find('.eicon-close')
					.on('click', function () {
						$('.kata-admin-bar-template-btn').removeClass('active');
					});
			}, 200);
		});

		$('#kata-finder')
			.find('.kata-dialog-close-btn')
			.on('click', function () {
				$('#kata-finder').hide();
			});

		// Templates
		$('.kata-admin-bar-templates-btn').on('click', function (event) {
			event.preventDefault();
			$('#elementor-preview-iframe')
				.contents()
				.find('.kata-plus-elementor-add-template-button')
				.trigger('click');
		});

		// Search
		$('#kata-finder-search-input').on('keyup', function () {
			var value = $(this).val().toLowerCase();
			if ($(this).val() != '') {
				$('.kata-finder-category-item').filter(function () {
					$(this).toggle(
						$(this).text().toLowerCase().indexOf(value) > -1
					);
				});
			} else {
				$('.kata-finder-category-item').show();
			}
			$('.kata-finder-category-items').each(function (index, value) {
				if ($(this).height() == 0) {
					$(this).prev().hide();
				} else {
					$(this).prev().show();
				}
			});
			setTimeout(function () {
				$(
					'.kata-dialog-body,.wp-full-overlay-sidebar-content, .accordion-container, .available-menu-items-list'
				)
					.getNiceScroll()
					.resize();
			}, 1);
		});

		// Return to Wordpress
		$('.kata-admin-bar-nav-item#kata-admin-bar-return-to-wordpress').on(
			'click',
			function (e) {
				var $this = $(this),
					target = e,
					$menu = $('#adminmenumain');
				$menu.toggleClass('active');
				$this.toggleClass('active');
			}
		);
		$(window).on('click', function (e) {
			if ($('body[class*="kata"]').length) {
				return;
			}
			if (
				e.target.localName != 'kata-admin-bar-nav-item' &&
				e.target.localName != 'li' &&
				e.target.className != 'wp-menu-name' &&
				e.target.className != 'ti-wordpress'
			) {
				$('#adminmenumain').removeClass('active');
				$('#kata-admin-bar-return-to-wordpress').removeClass('active');
			}
		});

		/**
		 * Prefers Color Scheme.
		 *
		 * @since	1.0.0
		 */
		(function () {
			//  && (!$('.kata-admin').data('color_scheme') || $('.kata-admin').data('color_scheme') == 'auto')
			if ($('.kata-admin-bar').length) {
				var color_mode;
				if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
					color_mode = 'dark';
				} else {
					color_mode = 'light';
				}
				$.ajax({
					url: kata_plus_admin_localize.ajax.url,
					type: 'POST',
					data: {
						nonce: kata_plus_admin_localize.ajax.nonce,
						action: 'prefers_color_scheme',
						color_mode: color_mode,
					},
				});
			}
		})();

		$('.kata-admin-bar-nav-item.collapsmenu').on('click', function (e) {
			var $this = $(this),
				$wrap = $this.closest('.kata-admin-bar');
			$this.toggleClass('active');
			$wrap.toggleClass('collaps');
			$('body').toggleClass('panelcollaps');
		});
	});
})(jQuery);
