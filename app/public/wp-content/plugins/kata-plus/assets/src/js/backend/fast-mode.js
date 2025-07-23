/**
 * Fast Mode.
 *
 * @author  ClimaxThemes
 * @package	Kata Plus
 * @since	1.0.0
 */

'use strict';
(function ($) {
	var timer;
	var plugin_size_compare = 0;
	var plugins = [];

	/**
	 * Site Title
	 */
	$('.kt-fst-get-info')
		.find('#site-title')
		.on('input', function (el) {
			clearTimeout(timer);
			timer = setTimeout(function () {
				var $this = $(this),
					val = jQuery(el.target).val(),
					url = '',
					next_page_url = $('.kt-fst-mod-2')
						.find('.next-step')
						.attr('href');

				var value = next_page_url.substring(
					next_page_url.lastIndexOf('blogname='),
					next_page_url.lastIndexOf('&blogdescription')
				);
				url = next_page_url.replace(value, 'blogname=' + val);
				$('.kt-fst-mod-2').find('.next-step').attr('href', url);
			}, 1000);
		});

	/**
	 * Site Tagline
	 */
	$('.kt-fst-get-info')
		.find('#site-tagline')
		.on('input', function (el) {
			clearTimeout(timer);
			timer = setTimeout(function () {
				var $this = $(this),
					val = jQuery(el.target).val(),
					url = '',
					next_page_url = $('.kt-fst-mod-2')
						.find('.next-step')
						.attr('href');

				var value = next_page_url.substring(
					next_page_url.lastIndexOf('blogdescription='),
					next_page_url.lastIndexOf('&siteurl')
				);
				url = next_page_url.replace(value, 'blogdescription=' + val);
				$('.kt-fst-mod-2').find('.next-step').attr('href', url);
			}, 1000);
		});

	/**
	 * Admin Email
	 */
	$('.kt-fst-get-info')
		.find('#admin-email')
		.on('input', function (el) {
			clearTimeout(timer);
			timer = setTimeout(function () {
				var $this = $('.kt-fst-get-info').find('#admin-email'),
					val = jQuery(el.target).val(),
					url = '',
					next_page_url = $('.kt-fst-mod-2')
						.find('.next-step')
						.attr('href');

				var value = next_page_url.substring(
					next_page_url.lastIndexOf('admin-email='),
					next_page_url.lastIndexOf('&WPLANG')
				);
				url = next_page_url.replace(value, 'admin-email=' + val);
				$('.kt-fst-mod-2').find('.next-step').attr('href', url);
				var emailreg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
				if (!emailreg.test(jQuery(el.target).val())) {
					$this.attr('style', 'border-color: red;');
					$this.after(
						'<span class="email-validation-error" style="position: absolute; bottom: 13px; color: red;">' +
							$this.data('valid') +
							'</span>'
					);
				} else {
					$this.removeAttr('style');
					$('.email-validation-error').remove();
				}
			}, 1000);
		});

	/**
	 * language
	 */
	$('.kt-fst-get-info')
		.find('#WPLANG')
		.on('change', function (el) {
			var $this = $(this),
				url = '',
				next_page_url = $('.kt-fst-mod-2')
					.find('.next-step')
					.attr('href');
			var value = next_page_url.substring(
				next_page_url.lastIndexOf('WPLANG='),
				next_page_url.lastIndexOf('&timezone_string')
			);
			url = next_page_url.replace(value, 'WPLANG=' + this.value);
			$('.kt-fst-mod-2').find('.next-step').attr('href', url);
		});

	/**
	 * Timezone
	 */
	$('.kt-fst-get-info')
		.find('#timezone_string')
		.on('change', function (el) {
			var $this = $(this),
				url = '',
				next_page_url = $('.kt-fst-mod-2')
					.find('.next-step')
					.attr('href');
			var value = next_page_url.substring(
				next_page_url.lastIndexOf('timezone_string='),
				next_page_url.lastIndexOf('/')
			);
			url = next_page_url.replace(value, 'timezone_string=' + this.value);
			$('.kt-fst-mod-2').find('.next-step').attr('href', url);
		});

	/**
	 * Icon & Logo
	 */
	var wp_media_site_icon;
	$('.site-icon')
		.find('.change-image')
		.on('click', function (e) {
			e.preventDefault();
			var $this = $(this),
				$wrap = $this.parent('.site-icon'),
				$holder = $wrap.find('.image-place-holder'),
				url = '',
				next_page_url = $('.kt-fst-mod-3')
					.find('.next-step')
					.attr('href');

			if (typeof wp === 'undefined' || !wp.media || !wp.media.gallery) {
				return;
			}

			if (wp_media_site_icon) {
				wp_media_site_icon.open();
				return;
			}
			wp_media_site_icon = wp.media({
				title: $this.data('frame-title'),
				button: {
					text: $this.data('insert-title'),
				},
				multiple: false,
			});

			wp_media_site_icon.on('select', function () {
				var attachment = wp_media_site_icon
					.state()
					.get('selection')
					.first();
				$holder.css(
					'background-image',
					'url(' + attachment.attributes.url + ')'
				);
				$holder.attr('data-id', attachment.attributes.id);
				var value = next_page_url.substring(
					next_page_url.lastIndexOf('site-icon='),
					next_page_url.lastIndexOf('&site-logo')
				);
				url = next_page_url.replace(
					value,
					'site-icon=' + attachment.attributes.id
				);
				$('.kt-fst-mod-3').find('.next-step').attr('href', url);
			});

			wp_media_site_icon.open();
		});
	var wp_media_site_logo;
	$('.site-logo')
		.find('.change-image')
		.on('click', function (e) {
			e.preventDefault();
			var $this = $(this),
				$wrap = $this.parent('.site-logo'),
				$holder = $wrap.find('.image-place-holder'),
				url = '',
				next_page_url = $('.kt-fst-mod-3')
					.find('.next-step')
					.attr('href');

			if (typeof wp === 'undefined' || !wp.media || !wp.media.gallery) {
				return;
			}

			if (wp_media_site_logo) {
				wp_media_site_logo.open();
				return;
			}
			wp_media_site_logo = wp.media({
				title: $this.data('frame-title'),
				button: {
					text: $this.data('insert-title'),
				},
				multiple: false,
			});

			wp_media_site_logo.on('select', function () {
				var attachment = wp_media_site_logo
					.state()
					.get('selection')
					.first();
				$holder.css(
					'background-image',
					'url(' + attachment.attributes.url + ')'
				);
				$holder.attr('data-id', attachment.attributes.id);
				var value = next_page_url.substring(
					next_page_url.lastIndexOf('site-logo='),
					next_page_url.lastIndexOf('/')
				);
				url = next_page_url.replace(
					value,
					'site-logo=' + attachment.attributes.id
				);
				$('.kt-fst-mod-3').find('.next-step').attr('href', url);
			});

			wp_media_site_logo.open();
		});

	/**
	 * Site Phone
	 */
	$('.kt-fst-get-info')
		.find('#site-phone')
		.on('input', function (el) {
			clearTimeout(timer);
			timer = setTimeout(function () {
				var $this = $(this),
					val = jQuery(el.target).val(),
					url = '',
					next_page_url = $('.kt-fst-mod-4')
						.find('.next-step')
						.attr('href');

				var value = next_page_url.substring(
					next_page_url.lastIndexOf('site-phone='),
					next_page_url.lastIndexOf('&site-address')
				);

				url = next_page_url.replace(value, 'site-phone=' + val);
				$('.kt-fst-mod-4').find('.next-step').attr('href', url);
			}, 1000);
		});

	/**
	 * Site Address
	 */
	$('.kt-fst-get-info')
		.find('#site-address')
		.on('input', function (el) {
			clearTimeout(timer);
			timer = setTimeout(function () {
				var $this = $(this),
					val = jQuery(el.target).val(),
					url = '',
					next_page_url = $('.kt-fst-mod-4')
						.find('.next-step')
						.attr('href');

				var value = next_page_url.substring(
					next_page_url.lastIndexOf('site-address='),
					next_page_url.lastIndexOf('&site-facebook')
				);
				url = next_page_url.replace(value, 'site-address=' + val);
				$('.kt-fst-mod-4').find('.next-step').attr('href', url);
			}, 1000);
		});

	/**
	 * Site Socials facebook
	 */
	$('.kt-fst-get-info')
		.find('#site-facebook')
		.on('input', function (el) {
			clearTimeout(timer);
			timer = setTimeout(function () {
				var $this = $(this),
					val = jQuery(el.target).val(),
					url = '',
					next_page_url = $('.kt-fst-mod-4')
						.find('.next-step')
						.attr('href');

				var value = next_page_url.substring(
					next_page_url.lastIndexOf('site-facebook='),
					next_page_url.lastIndexOf('&site-twitter')
				);
				url = next_page_url.replace(value, 'site-facebook=' + val);
				$('.kt-fst-mod-4').find('.next-step').attr('href', url);
			}, 1000);
		});

	/**
	 * Site Socials twitter
	 */
	$('.kt-fst-get-info')
		.find('#site-twitter')
		.on('input', function (el) {
			clearTimeout(timer);
			timer = setTimeout(function () {
				var $this = $(this),
					val = jQuery(el.target).val(),
					url = '',
					next_page_url = $('.kt-fst-mod-4')
						.find('.next-step')
						.attr('href');

				var value = next_page_url.substring(
					next_page_url.lastIndexOf('site-twitter='),
					next_page_url.lastIndexOf('&site-linkedin')
				);
				url = next_page_url.replace(value, 'site-twitter=' + val);
				$('.kt-fst-mod-4').find('.next-step').attr('href', url);
			}, 1000);
		});

	/**
	 * Site Socials linkedin
	 */
	$('.kt-fst-get-info')
		.find('#site-linkedin')
		.on('input', function (el) {
			clearTimeout(timer);
			timer = setTimeout(function () {
				var $this = $(this),
					val = jQuery(el.target).val(),
					url = '',
					next_page_url = $('.kt-fst-mod-4')
						.find('.next-step')
						.attr('href');

				var value = next_page_url.substring(
					next_page_url.lastIndexOf('site-linkedin='),
					next_page_url.lastIndexOf('&site-instagram')
				);
				url = next_page_url.replace(value, 'site-linkedin=' + val);
				$('.kt-fst-mod-4').find('.next-step').attr('href', url);
			}, 1000);
		});

	/**
	 * Site Socials instagram
	 */
	$('.kt-fst-get-info')
		.find('#site-instagram')
		.on('input', function (el) {
			clearTimeout(timer);
			timer = setTimeout(function () {
				var $this = $(this),
					val = jQuery(el.target).val(),
					url = '',
					next_page_url = $('.kt-fst-mod-4')
						.find('.next-step')
						.attr('href');

				var value = next_page_url.substring(
					next_page_url.lastIndexOf('site-instagram='),
					next_page_url.lastIndexOf('/')
				);
				url = next_page_url.replace(value, 'site-instagram=' + val);
				$('.kt-fst-mod-4').find('.next-step').attr('href', url);
			}, 1000);
		});

	/**
	 * Importer
	 */
	$('.kt-fast-mode-load-more-demo').on('click', function () {
		var $this = $(this),
			$wrap = $this.parent('.kt-fst-mod-wrapper'),
			height = parseInt(
				$wrap
					.find('.kata-importer-wrapper')
					.css('height')
					.replace('px', '')
			);
		$wrap.find('.kata-importer-wrapper').css('height', height + 350 + 'px');
	});

	/**
	 * Customzie Site
	 */
	$('.customize-site').on('click', function (e) {
		var $this = $(this),
			$wrap = $this.closest('.kt-fst-mod-inner-wrapper'),
			prev_url = $('.prev-step').attr('href');
		if (
			'pages-list' == e.currentTarget.dataset.type ||
			'posts-list' == e.currentTarget.dataset.type ||
			'headers-list' == e.currentTarget.dataset.type ||
			'footers-list' == e.currentTarget.dataset.type ||
			'typography-list' == e.currentTarget.dataset.type ||
			'list-plugins' == e.currentTarget.dataset.type
		) {
			e.preventDefault();
			$('#page-title').text(e.currentTarget.dataset.title);
		}
		if (
			'headers-list' == e.currentTarget.dataset.type ||
			'footers-list' == e.currentTarget.dataset.type
		) {
			$('.kt-fst-mod-wrapper').css('max-width', '960px');
		}
		$('.' + e.currentTarget.dataset.type)
			.show()
			.siblings()
			.hide();
		$('.prev-step').attr('href', prev_url.replace('step=6', 'step=7'));
	});

	/**
	 * Header
	 */
	$(document).on('click', '.header-item', function () {
		var $this = $(this),
			template_id = $this.data('id');
		$this.addClass('selected').siblings().removeClass('selected');
		$('#wpwrap').append(
			'<div class="kt-ajax-notice-wrap"><p class="kt-ajax-notice">' +
				fast_mode_localize.ajax.notice.importing +
				'</p></div>'
		);
		$.ajax({
			type: 'POST',
			url: fast_mode_localize.ajax.url,
			data: {
				action: 'header_importer',
				template_id: template_id,
				nonce: fast_mode_localize.ajax.nonce,
			},
			success: function (data) {
				$('.kt-ajax-notice')
					.text(fast_mode_localize.ajax.notice.success)
					.css('background', '#a7dca1');
				setTimeout(() => {
					$('.kt-ajax-notice-wrap').remove();
				}, 1500);
			},
			error: function (data) {
				$('.kt-ajax-notice')
					.text(fast_mode_localize.ajax.notice.error)
					.css('background', '#dca1a1');
				setTimeout(() => {
					$('.kt-ajax-notice-wrap').remove();
				}, 1500);
			},
		});
	});

	/**
	 * Footer
	 */
	$(document).on('click', '.footer-item', function () {
		var $this = $(this),
			template_id = $this.data('id');
		$this.addClass('selected').siblings().removeClass('selected');
		$('#wpwrap').append(
			'<div class="kt-ajax-notice-wrap"><p class="kt-ajax-notice">' +
				fast_mode_localize.ajax.notice.importing +
				'</p></div>'
		);
		$.ajax({
			type: 'POST',
			url: fast_mode_localize.ajax.url,
			data: {
				action: 'footer_importer',
				template_id: template_id,
				nonce: fast_mode_localize.ajax.nonce,
			},
			success: function (data) {
				$('.kt-ajax-notice')
					.text(fast_mode_localize.ajax.notice.success)
					.css('background', '#a7dca1');
				setTimeout(() => {
					$('.kt-ajax-notice-wrap').remove();
				}, 1500);
			},
			error: function (data) {
				$('.kt-ajax-notice')
					.text(fast_mode_localize.ajax.notice.error)
					.css('background', '#dca1a1');
				setTimeout(() => {
					$('.kt-ajax-notice-wrap').remove();
				}, 1500);
			},
		});
	});

	/**
	 * typography
	 */
	$(document).on('click', '.typography-item', function () {
		var $this = $(this),
			template_id = $this.data('id');
		$this.addClass('selected').siblings().removeClass('selected');
		$('#wpwrap').append(
			'<div class="kt-ajax-notice-wrap"><p class="kt-ajax-notice">' +
				fast_mode_localize.ajax.notice.importing +
				'</p></div>'
		);
		$.ajax({
			type: 'POST',
			url: fast_mode_localize.ajax.url,
			data: {
				action: 'typography_importer',
				template_id: template_id,
				nonce: fast_mode_localize.ajax.nonce,
			},
			success: function (data) {
				$('.kt-ajax-notice')
					.text(fast_mode_localize.ajax.notice.success)
					.css('background', '#a7dca1');
				setTimeout(() => {
					$('.kt-ajax-notice-wrap').remove();
				}, 1500);
			},
			error: function (data) {
				$('.kt-ajax-notice')
					.text(fast_mode_localize.ajax.notice.error)
					.css('background', '#dca1a1');
				setTimeout(() => {
					$('.kt-ajax-notice-wrap').remove();
				}, 1500);
			},
		});
	});

	/**
	 * Install plugins function.
	 *
	 * @since	1.0.0
	 */
	function KataPlusInstallPlugin($install_plugin_btn, install_multiple) {
		if ($install_plugin_btn.hasClass('installing')) {
			return;
		}

		var plugin_action = $install_plugin_btn.attr('data-plugin-action');
		var href = $install_plugin_btn.attr('href').split('&');
		var plugin_name = href[1];
		var nonce = href[3];
		var data = {};
		var active_status = '';
		if (plugin_action == 'install') {
			active_status = 'install';
		} else if (plugin_action == 'activate') {
			active_status = 'activate';
		} else if (plugin_action == 'deactivate') {
			active_status = 'deactivate';
		}
		var $other_plugins = $install_plugin_btn
			.closest('.kata-install-plugin')
			.siblings('.kata-install-plugin');

		data['action'] = 'kata_plus_plugin_actions';
		data['plugin-action'] = plugin_action;
		data['plugin'] = plugin_name.substr(
			plugin_name.lastIndexOf('=') + 1,
			plugin_name.length
		);
		data['tgmpa-' + plugin_action] = plugin_action + '-plugin';
		data['tgmpa-nonce'] = nonce.substr(
			nonce.lastIndexOf('=') + 1,
			nonce.length
		);

		if (plugin_action == 'install' || plugin_action == 'update') {
			data['page'] = 'install-required-plugins';
		}

		$install_plugin_btn.addClass('installing').css('cursor', 'default');
		$other_plugins
			.css('opacity', '0.5')
			.find('.row-actions')
			.find('a')
			.css('cursor', 'default');

		$.ajax({
			type: 'GET',
			url: ajaxurl,
			data: data,
			success: function (url) {
				if (
					active_status == 'install' ||
					active_status == 'deactivate'
				) {
					console.log(kata_install_plugins);
					$install_plugin_btn
						.attr('data-plugin-action', 'activate')
						.text(kata_install_plugins['translation']['activate'])
						.closest('.kata-install-plugin')
						.addClass('active');
					$install_plugin_btn
						.closest('.kata-required-plugin')
						.removeClass('active');
					if (
						$install_plugin_btn.attr('data-plugin-action') ==
						'activate'
					) {
						url = url.substr(
							url.lastIndexOf('activate_href:') + 14,
							url.length
						);
						$install_plugin_btn.attr('href', url);
					}
				}
				if (active_status == 'activate') {
					$install_plugin_btn
						.attr('data-plugin-action', 'deactivate')
						.text(kata_install_plugins['translation']['deactivate'])
						.closest('.kata-install-plugin')
						.removeClass('active');
					$install_plugin_btn
						.closest('.kata-required-plugin')
						.addClass('active');
					if (
						$install_plugin_btn.attr('data-plugin-action') ==
						'deactivate'
					) {
						url = url.substr(
							url.lastIndexOf('deactivate_href:') + 14,
							url.length
						);
						$install_plugin_btn.attr('href', url);
					}
				}

				$other_plugins
					.css('opacity', '1')
					.find('.row-actions')
					.find('a')
					.css('cursor', 'pointer');
				$install_plugin_btn
					.removeClass('installing')
					.css('cursor', 'pointer');
				if (install_multiple) {
					plugins.shift();
					if (plugins.length > 0) {
						KataPlusInstallPlugin($(plugins[0]['item']), true);
						$('.kata-btn-install-plugins').removeAttr('style');
					}
				}
			},
			fail: function () {
				alert(
					kata_install_plugins['translation']['fail-plugin-action']
				);
			},
		}); // end ajax
	}

	$('.kata-required-plugin').on('click', function () {
		$(this).toggleClass('selected');
	});

	/**
	 * Install plugins
	 */
	$('.kata-btn-plugin-action').on('click', function (event) {
		event.preventDefault();
		KataPlusInstallPlugin($(this), false);
	});

	/**
	 * Install plugins bulk action
	 */
	$(document).on('click', '.install-plugin-bulk-action', function (e) {
		e.preventDefault();
		var $this = $(this),
			$required_plugins = $this.closest('.list-plugins');

		$required_plugins
			.find('.kata-required-plugin.selected')
			.each(function () {
				var $this = $(this);
				var $plugin_action_btn = $this.find('.kata-btn-plugin-action');
				var plugin_href = $plugin_action_btn.attr('href');
				var plugin_action = $plugin_action_btn.data('plugin-action');

				if (
					plugin_href != undefined &&
					plugin_href != '#' &&
					plugin_action != 'deactivate'
				) {
					plugins.push({
						item: $plugin_action_btn[0],
						href: plugin_href,
						plugin_action: plugin_action,
					});
				}
			});

		console.log(plugins);
		if (!plugins.length) {
			$this.css({
				background: '#e6e7e8',
				'border-color': '#e6e7e8',
				color: '#a1a2a3',
				'box-shadow': 'none',
				cursor: 'default',
			});
		} else {
			KataPlusInstallPlugin($(plugins[0]['item']), true);
		}
	});

	/**
	 * Help Module
	 */
	$(document).on('click', '.kt-fst-help img', function () {
		$('.kt-fst-hlp-wrapper').niceScroll({
			cursorcolor: '#aaa',
			cursoropacitymin: 0,
			cursoropacitymax: 1,
			cursorwidth: '7px',
			cursorborder: 'none',
			cursorborderradius: '5px',
			scrollspeed: 60,
			mousescrollstep: 40,
			hwacceleration: !0,
			gesturezoom: !0,
			grabcursorenabled: !0,
			autohidemode: !0,
			spacebarenabled: !0,
			railpadding: {
				top: 0,
				right: 1,
				left: 0,
				bottom: 1,
			},
			disableoutline: !0,
			horizrailenabled: !1,
			railalign: 'right',
			railvalign: 'bottom',
			enablemousewheel: !0,
			enablekeyboard: !0,
			smoothscroll: !0,
			cursordragspeed: 0.3,
		});
		if ('none' == $('.kt-fst-hlp-overlay').css('display')) {
			$('.kt-fst-hlp-overlay').css('display', 'inline-block');
		} else {
			$('.kt-fst-hlp-overlay').css('display', 'none');
		}
	});
	$(document).on('click', '.kt-fst-hlp-wrapper .close-help', function () {
		$('.kt-fst-hlp-overlay').css('display', 'none');
	});

	/**
	 * Select Demo
	 */
	$(document).on(
		'click',
		'.kata-demp-wrapper, .kata-importer-name, .kata-importer-screenshot',
		function (e) {
			var $this = $(this),
				$item = '',
				$wrap = $this.closest('.fast-mode-preview');

			if (e.target.className == 'kata-demp-wrapper') {
				$item = $this;
			} else {
				$item = $this.closest('.kata-demp-wrapper');
			}

			if (e.target.className !== 'kata-btn kata-btn-importer requested') {
				$('.kata-demp-wrapper').removeClass('selected');
			}
			$item.addClass('selected');

			var demo_key = $item.parent('.kata-importer').attr('data-key');
			$('.next-step').addClass('ready').attr('data-key', demo_key);
		}
	);

	$(document).on('click', '.next-step.ready', function (e) {
		e.preventDefault();

		if ($(this).hasClass('ready')) {
			$(
				'.kata-importer-wrapper:not(.fast-mode-preview) [data-key="' +
					$(this).attr('data-key') +
					'"]'
			)
				.find('.kata-btn-importer')
				.trigger('click');
		}

		$(this).removeClass('ready');
		$(this).addClass('run-importer');

		$(document).ajaxSuccess(function (event, xhr, settings) {
			if ('ImporterBuildSteps' == settings.name) {
				$('.kata-importer-wrapper:not(.fast-mode-preview)').show();
				$('.next-step').removeClass('run-importer');
			}
		});
	});

	$(document).ajaxSuccess(function (event, xhr, settings) {
		if (settings.data) {
			if (settings.data.indexOf('ImportDone') >= 0) {
				$('.kata-importer-wrapper:not(.fast-mode-preview)').hide();
				$('.next-step').removeClass('ready');
				window.location.replace('https://google.com');

				var search = window.location.search;
				var href = window.location.href;

				href = href.replace(search, '');
				window.location.replace(
					href + '?page=kata-plus-fast-mode&step=6'
				);
			}
		}
	});

	$(document).on('click', '.ti-close', function () {
		console.log('clicked');
		$('.kata-importer-wrapper:not(.fast-mode-preview)').hide();
		$('.next-step').removeClass('ready');
	});

	$(document).on('click', '.fast-mode-loadmore', function (e) {
		e.preventDefault();
		var height = $('.fast-mode-preview').css('height').replace('px', '');
		height = parseInt(height);
		height = height + height;
		if (height > 2100) {
			$('.fast-mode-preview').css('height', '100%');
			$('.fast-mode-loadmore').hide();
			$('#fast-mode-filet').remove();
		} else {
			$('.fast-mode-preview').css('height', height + 'px');
		}
	});

	/**
	 * Automate Importer
	 */
	$(document).ajaxSuccess(function (event, xhr, settings) {
		if ('ImporterBuildSteps' == settings.name) {
			setTimeout(function () {
				$('.kata-checkbox-input').each(function (index, element) {
					var $this = $(this),
						data = $this.attr('data-type');

					if (typeof data !== 'undefined') {
						var text =
							data == 'builders' ? 'Header, Footer...' : data;
						$('.kata-col-import-demo')
							.find('.tasks')
							.append(
								`<li class="kata-importer-task-menus kata-import-pending" data-action="${data}" data-status="pending">Import ${text}</li>`
							);
					}
				});

				if (
					$('.kata-btn-install-plugins').css('pointer-events') !==
					'none'
				) {
					$('.kata-btn-install-plugins').trigger('click');
					jQuery('.kata-col-import-demo')
						.find('.tasks')
						.append(
							`<li class="kata-importer-task-menus kata-import-active" data-action="plugins" data-status="pending">Installing Plugins</li>`
						);
				} else {
					setTimeout(function () {
						$('.kata-importer-task-menus');

						$('.kata-checkbox-wrap.all')
							.find('#all')
							.trigger('click');
						$('.kata-import-demo-btn').trigger('click');
					}, 3000);
				}
			}, 500);
		}
	});

	$(document).ajaxSuccess(function (event, xhr, settings) {
		if (settings.url) {
			if (settings.url.indexOf('kata_plus_plugin_actions') >= 0) {
				plugin_size_compare++;
				var plugins_size = $('.kata-required-plugin').length;

				if (plugins_size == plugin_size_compare) {
					setTimeout(function () {
						$('.kata-btn-install-plugins').trigger('click');
						$(
							'.kata-importer-task-menus[data-action="plugins"]'
						).removeClass('kata-import-active');
					}, 500);
					setTimeout(function () {
						$(
							'.kata-importer-task-menus[data-action="plugins"]'
						).attr(
							'class',
							'kata-importer-task-menus kata-import-done'
						);
					}, 1000);
				}

				setTimeout(function () {
					$('.kata-importer-task-menus');

					$('.kata-checkbox-wrap.all').find('#all').trigger('click');
					$('.kata-import-demo-btn').trigger('click');
				}, 3000);
			}
		}
	});

	/**
	 * Search.
	 *
	 * @since	1.0.0
	 */
	$('.fast-mode-search-demo')
		.find('.site-search')
		.on('input', function (el) {
			clearTimeout(timer); //clear any running timeout on key up
			timer = setTimeout(function () {
				var searchValue = jQuery(el.target).val();
				$('#kata-importer-search-styles').remove();
				if (searchValue) {
					$(
						'<style id="kata-importer-search-styles">.kata-importer[demo-name] {display:none;}.kata-importer[demo-name*="' +
							searchValue +
							'"]{display:inline-block;}</style>'
					).appendTo('head');
				}
			}, 250);
		});
})(jQuery);
