/**
 * Footer Builder.
 *
 * @author  ClimaxThemes
 * @package	Kata Plus
 * @since	1.0.0
 */
'use strict';
(function ($) {
	/**
	 * Global variables.
	 *
	 * @since	1.0.0
	 */
	var plugins = [];
	var timer;
	var wishlist = {};

	lozad('.lozad', {
		load: function (el) {
			el.src = el.dataset.src;
			el.onload = function () {
				el.classList.add('kata-loaded');
			};
		},
	}).observe();

	/**
	 * constructor
	 *
	 * @since	1.0.0
	 */
	(function () {
		/**
		 * Create Wishlist
		 */
		if (!localStorage.getItem('importer-wishlist')) {
			localStorage.setItem('importer-wishlist', JSON.stringify(wishlist));
		}
	})();

	/**
	 * Categories.
	 *
	 * @since	1.0.0
	 */
	$('.demo-categories').find('select[name="demo-categories"]').niceSelect();

	$('.demo-categories')
		.find('select[name="demo-categories"]')
		.on('change', function () {
			$('#kata-importer-search-styles').remove();
			$(
				'<style id="kata-importer-search-styles">.kata-importer[website-type] {display:none;}.kata-importer[website-type*="' +
					this.value +
					'"]{display:inline-block;}</style>'
			).appendTo('head');
		});

	/**
	 * Tags.
	 *
	 * @since	1.0.0
	 */
	$('.kata-importer').each(function (index, element) {
		var $this = $(this),
			$wrap = $this.closest('.kata-importer-wrapper'),
			$tag = $this.attr('website-type'),
			all = $wrap.find('.kata-importer[website-type*="all"]').length,
			fast = $wrap.find('.kata-importer[website-type*="fast"]').length,
			free = $wrap.find('.kata-importer[website-type*="free"]').length,
			pro = $wrap.find(
				'.kata-importer[website-type*="pro"]:not([website-type*="fast"])'
			).length;
		if ($tag.indexOf('all') != -1) {
			$wrap.find('.demotypeitem[value="all"]').find('span').text(all);
		}
		if ($tag.indexOf('fast') != -1) {
			$wrap.find('.demotypeitem[value="fast"]').find('span').text(fast);
		}
		if ($tag.indexOf('free') != -1) {
			$wrap.find('.demotypeitem[value="free"]').find('span').text(free);
		}
		if ($tag.indexOf('pro') != -1) {
			$wrap.find('.demotypeitem[value="pro"]').find('span').text(pro);
		}
	});
	$('.kata-demotypes')
		.find('.demotypeitem')
		.on('click', function () {
			var $this = $(this),
				value = $this.attr('value');
			$this.addClass('active').siblings().removeClass('active');
			$('#kata-importer-search-styles').remove();
			$(
				'<style id="kata-importer-search-styles">.kata-importer[website-type] {display:none;}.kata-importer[website-type*="' +
					value +
					'"]{display:inline-block;}</style>'
			).appendTo('head');
		});

	/**
	 * Search.
	 *
	 * @since	1.0.0
	 */
	$('.kata-demo-importer-search-box')
		.find('input[type="text"]')
		.on('input', function (el) {
			clearTimeout(timer); //clear any running timeout on key up
			timer = setTimeout(function () {
				var searchValue = jQuery(el.target).val().toLowerCase();
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

	/**
	 * Wishlist.
	 *
	 * @since	1.0.0
	 */
	$.each(
		JSON.parse(localStorage.getItem('importer-wishlist')),
		function (key, val) {
			if (val) {
				$('.kata-importer[demo-name="' + key + '"]').attr(
					'data-wishlist',
					true
				);
			}
		}
	);
	$('.kata-demo-importer-wish-list')
		.find('.kata-icon')
		.on('click', function (el) {
			var $this = $(this),
				$wrap = $this.closest('.kata-demo-importer-wish-list');
			if ($this.attr('data-show') == 'false') {
				$this.attr('data-show', true);
				$wrap.attr('data-show', true);
				$('#kata-importer-search-styles').remove();
				$(
					'<style id="kata-importer-search-styles">.kata-importer[data-wishlist="false"] {display:none;}.kata-importer[data-wishlist="true"]{display:inline-block;}</style>'
				).appendTo('head');
			} else {
				$this.attr('data-show', false);
				$wrap.attr('data-show', false);
				$('#kata-importer-search-styles').remove();
			}
		});

	$('.kata-demo-addto-wishlist')
		.find('.kata-icon')
		.on('click', function (el) {
			var $this = $(this),
				$wrap = $this.closest('.kata-importer'),
				item = $wrap.attr('demo-name');

			wishlist = JSON.parse(localStorage.getItem('importer-wishlist'));

			if (!wishlist[item]) {
				wishlist[item] = true;
				$wrap.attr('data-wishlist', true);
			} else {
				wishlist[item] = false;
				$wrap.attr('data-wishlist', false);
			}
			localStorage.setItem('importer-wishlist', JSON.stringify(wishlist));
		});

	/**
	 * Modal nicescroll.
	 *
	 * @since	1.0.0
	 */
	function ModalNice() {
		$('.kata-lightbox-content').niceScroll({
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
	}

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
		} else if (plugin_action == 'update') {
			active_status = 'update';
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
					active_status == 'deactivate' ||
					active_status == 'update'
				) {
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
				StepManager();
				if (install_multiple) {
					plugins.shift();
					if (plugins.length > 0) {
						KataPlusInstallPlugin($(plugins[0]['item']), true);
						$('.kata-btn-install-plugins').removeAttr('style');
					} else {
						StepManager();
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

	/**
	 * Install plugin bulk.
	 *
	 * @since	1.0.0
	 * @event	click
	 */
	function KataPlusInstallPluginBulk() {
		$('.kata-btn-install-plugins').on('click', function (e) {
			e.preventDefault();
			var $this = $(this),
				$required_plugins = $this
					.parent()
					.next('.kata-required-plugins');

			$required_plugins
				.find('.kata-required-plugin:not(:hidden)')
				.each(function () {
					var $this = $(this);
					var $plugin_action_btn = $this.find(
						'.kata-btn-plugin-action'
					);
					var plugin_href = $plugin_action_btn.attr('href');
					var plugin_action =
						$plugin_action_btn.data('plugin-action');

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
	}

	/**
	 * Step Manager.
	 *
	 * @since	1.0.0
	 * @event	click
	 */
	function StepManager() {
		setTimeout(function () {
			if (!$('.kata-required-plugin:not(.active)').length) {
				$('.kata-btn.kata-btn-install-plugins').css({
					'pointer-events': 'none',
					background: '#e6e7e8',
					'border-color': '#e6e7e8',
					color: '#a1a2a3',
					'box-shadow': 'none',
					cursor: 'default',
				});
				$('.kt-importer-step[data-step="1"]').trigger('click');
				var currnet_location =
					window.location.search.indexOf('kata-plus-fast-mode') >= 0
						? 'fastmode'
						: 'importer';
				if (currnet_location != 'fastmode') {
					$('.kata-lightbox-content').attr('tabindex', 1);
					$('.kata-lightbox-content').attr(
						'style',
						'transform: translateX(-740px)'
					);
				}
			} else {
				$('.kata-btn-install-plugins').removeAttr('style');
			}
		}, 500);
		$('.resume-import-progress').on('click', function (e) {
			e.preventDefault();
			var $wrap = $(this).closest('.initial-notice');
			var eContainer = $(this).hasClass('activation-e-con');
			if (
				$wrap
					.find('.importer-wraning')
					.find('input[type="checkbox"]')
					.is(':checked')
			) {
				$wrap.fadeOut();

				if (eContainer) {
					$.ajax({
						url: importer_localize.ajax.url,
						type: 'POST',
						data: {
							action: 'activate_elementor_container',
							nonce: importer_localize.ajax.nonce,
						},
						success: function (data) {},
						error: function (data) {},
					});
				}
			}
		});
		$(document).on(
			'click',
			'.importer-wraning [for="warning-1"]',
			function () {
				var $this = $(this),
					$wrap = $this.closest('.kata-checkbox-wrap');
				$wrap.find('input[type="checkbox"]').trigger('click');
			}
		);
	}

	var importedContents = {};
	/**
	 * Select Data.
	 *
	 * @since	1.0.0
	 * @event	click
	 */
	$(document).ajaxComplete(function (event, xhr, options) {
		if (options.name == 'ImporterBuildSteps') {
			$('.kata-checkbox-wrap input[type="checkbox"]').on(
				'click',
				function () {
					var $this = $(this),
						$wrap = $this.closest('.kata-col-import-demo'),
						checks = $wrap
							.find('.kata-checkbox-wrap')
							.siblings()
							.find('input[type="checkbox"]')
							.prop('checked');

					if ($this.prop('checked') || checks) {
						$wrap
							.find('.kata-import-demo-btn')
							.removeClass('disabled');
					} else {
						$wrap
							.find('.kata-import-demo-btn')
							.addClass('disabled');
					}
					if ($this.attr('id') == 'all') {
						if ($this.prop('checked') == true) {
							$(
								'.kata-checkbox-wrap input[type="checkbox"]:not(#all)'
							).prop('checked', true);
						} else if ($this.prop('checked') == false) {
							$(
								'.kata-checkbox-wrap input[type="checkbox"]:not(#all)'
							).prop('checked', false);
						}
					}
				}
			);
			// Import Request
			$('.kata-import-demo-btn').on('click', function (e) {
				var $this = $(this),
					$wrap = $this.closest('.kata-col-import-demo'),
					$parent = $wrap.closest('.kata-lightbox-content'),
					$tasks = $parent.find('.kata-importer-tasks'),
					$key = $this.data('key'),
					$name = $this.data('name'),
					$screenshot = $this.data('screenshot'),
					demo_data = [];

				e.preventDefault();
				$parent
					.find('.kata-import-content-wrap')
					.find('.kata-checkbox-input')
					.attr('disabled', 'disabled');
				$wrap.find('.kata-import-demo-btn').addClass('disabled');
				$wrap.find('.kata-import-demo-btn').text('Downloading Content');
				var current_i = $wrap
					.find('input[type="checkbox"][data-type]:checked')
					.first()
					.data('type');
				$.each(
					$wrap.find('input[type="checkbox"][data-type]:checked'),
					function () {
						demo_data.push($(this).data('type'));
					}
				);

				var currnet_location =
					window.location.search.indexOf('kata-plus-fast-mode') >= 0
						? 'fastmode'
						: 'importer';

				$.ajax({
					url: importer_localize.ajax.url,
					type: 'POST',
					data: {
						action: 'BuildImporter',
						demo_data: demo_data,
						key: $key,
						name: $name,
						screenshot: $screenshot,
						nonce: importer_localize.ajax.nonce,
						currnet_location: currnet_location,
					},
					success: function (data) {
						$('li.kt-importer-step.kt-last-step').trigger('click');
						$('li.kt-importer-step[data-step="1"]').addClass(
							'inactive'
						);
						$('li.kt-importer-step[data-step="0"]').addClass(
							'inactive'
						);

						$('.kata-checkbox-wrap').remove();
						$('.kata-required-plugin').remove();
						$(data).appendTo($tasks.find('.tasks'));
						importedContents['key'] = $key;
						start_import($key, demo_data, current_i);
					},
					error: function (data) {
						if (typeof data.responseText !== 'undefined') {
							jQuery('.steps').html(data.responseText);
						}
					},
				});
			});
		}
	});

	var mediaTry = 1;
	var before_action = '';

	function start_import(key, import_items, current) {
		if (!import_items || typeof current == 'undefined') {
			console.log('finished import action');
			ImportDone();
			return;
		}
		console.log(import_items);
		$('.kata-importer-task-menus').removeClass('kata-import-active');
		$('.kata-importer-task-menus[data-action="' + current + '"]').addClass(
			'kata-import-active'
		);
		var demo = $('.kata-import-demo-title').text();
		// console.log(key, import_items, current);
		$.ajax({
			url: importer_localize.ajax.url,
			type: 'POST',
			dataType: 'json',
			data: {
				action: 'Importer',
				key: key,
				import_item: current,
				demo: demo.toLowerCase(),
				mediaTry: mediaTry,
				nonce: importer_localize.ajax.nonce,
			},
			success: function (response, data) {
				console.log(response);
				console.log(data);
				if (typeof response.status == 'undefined') {
					response.status = '';
				}

				if (response.status == 'newAJAX') {
					$('.kata-importer-tasks')
						.find('li[data-action="' + current + '"]')
						.html(
							'<span class="message info please-wait">Please Wait</span>' +
								$('.kata-importer-tasks')
									.find('li[data-action="' + current + '"]')
									.html()
						);
					mediaTry++;
					jQuery('.kata-col-import-demo .meter span')
						.css('width', 100 / (import_items.length + 1) + '%')
						.trigger('size-change');
					start_import(key, import_items, import_items[0]);
				} else {
					import_items.shift();
					jQuery('.kata-col-import-demo .meter span')
						.css('width', 100 / (import_items.length + 1) + '%')
						.trigger('size-change');
					if (response.status == 'success') {
						importedContents[current] = true;
						$('.kata-importer-tasks')
							.find('li[data-action="' + current + '"]')
							.addClass('kata-import-done');
					} else {
						importedContents[current] = response.message;
						$('.kata-importer-tasks')
							.find('li[data-action="' + current + '"]')
							.addClass('kata-import-error');
						if (typeof response.message != 'undefined') {
							$('.kata-importer-tasks')
								.find('li[data-action="' + current + '"]')
								.html(
									'<span class="message">' +
										response.message +
										'</span>' +
										$('.kata-importer-tasks')
											.find(
												'li[data-action="' +
													current +
													'"]'
											)
											.html()
								);
						}
					}
					if (import_items) {
						start_import(key, import_items, import_items[0]);
					}
				}
			},
			error: function (response) {
				if (response.status == 'newAJAX') {
					setTimeout(function () {
						start_import(key, import_items, current);
					}, 25);
				}
			},
		});
	}

	function ImportDone() {
		var demo = $('.kata-import-demo-title').text(),
			demo_url = $('.demo_url').val();
		$.ajax({
			url: importer_localize.ajax.url,
			type: 'POST',
			data: {
				action: 'ImportDone',
				key: importedContents['key'],
				demo_url: demo_url,
				reports: importedContents,
				demo: demo.toLowerCase(),
				nonce: importer_localize.ajax.nonce,
			},
			success: function (response) {
				$('#kata-importer-search-styles').remove();
				jQuery('.kata-lightbox-wrapper .ti-close')
					.first()
					.trigger('click');
				var $modal = jQuery(
					'.kata-importer .kata-lightbox-wrapper'
				).first();
				var screenshot = $('.kata-col-import-demo-image')
					.closest('.kata-importer')
					.attr('demo-screenshot');
				$('.kata-col-import-demo-image')
					.find('img')
					.attr('src', screenshot);
				$modal.fadeIn().addClass('active-modal').html(response);
				if ($('.kata-lightbox-wrapper').hasClass('active-modal')) {
					$('.kata-lightbox-wrapper').addClass('done');
				}
				$('.kata-lightbox-wrapper')
					.find('.ti-close')
					.on('click', function () {
						var $this = $(this),
							$wrap = $this.closest('.kata-lightbox-wrapper');
						$wrap.fadeOut().removeClass('active-modal').html('');
						$('.kata-lightbox-wrapper').removeClass('done');
					});
				RedirectAfterImportDone();
			},
		});
	}

	/**
	 * Importer Steps.
	 *
	 * @since	1.0.0
	 * @event	click
	 */
	function ImporterSteps() {
		StepManager();
		$('.kata-lightbox')
			.find('.kt-importer-step')
			.on('click', function () {
				var $this = $(this),
					$wrap = $this.closest('.kata-lightbox'),
					step = $this.data('step'),
					width = $wrap.innerWidth();
				$this
					.addClass('kt-active-step')
					.siblings()
					.removeClass('kt-active-step');

				var currnet_location =
					window.location.search.indexOf('kata-plus-fast-mode') >= 0
						? 'fastmode'
						: 'importer';
				if (currnet_location != 'fastmode') {
					$('.kata-lightbox-content').attr('tabindex', step);
					$wrap
						.find('.kata-lightbox-content')
						.attr(
							'style',
							'transform: translateX(-' + width * step + 'px)'
						);
				}
			});
	}

	/**
	 * Redirect After Import Done.
	 *
	 * @since	1.0.0
	 */
	function RedirectAfterImportDone() {
		$('.kata-lightbox-wrapper')
			.find('.ti-close')
			.on('click', function () {
				window.location.replace(window.location.href);
				console.log(window.location.href);
			});
	}

	/**
	 * Importer modal.
	 *
	 * @since	1.0.0
	 */
	$('.kata-importer')
		.find('.kata-btn-importer')
		.on('click', function () {
			var $this = $(this),
				$wrap = $this.closest('.kata-importer'),
				$demo_url = $wrap.find('.kata-importer-preview').attr('href'),
				$modal = $wrap.find('.kata-lightbox-wrapper'),
				$screenshot = $wrap.attr('demo-screenshot'),
				$name = $wrap.attr('demo-name'),
				$e_con = $wrap.attr('data-econ'),
				currnet_location =
					window.location.search.indexOf('kata-plus-fast-mode') >= 0
						? 'fastmode'
						: 'importer',
				$key = $wrap.attr('data-key');

			$this.addClass('requested');
			$.ajax({
				url: importer_localize.ajax.url,
				name: 'ImporterBuildSteps',
				type: 'POST',
				data: {
					action: 'ImporterBuildSteps',
					key: $key,
					demo_url: $demo_url,
					screenshot: $screenshot,
					name: $name,
					e_con: $e_con,
					nonce: importer_localize.ajax.nonce,
					currnet_location: currnet_location,
				},
				success: function (data) {
					$this.removeClass('requested');
					$modal.fadeIn().addClass('active-modal').html(data);
					ImporterSteps();
					KataPlusInstallPluginBulk();
					ModalNice();
					$('.kata-lightbox-wrapper')
						.find('.ti-close')
						.on('click', function () {
							var $this = $(this),
								$wrap = $this.closest('.kata-lightbox-wrapper');
							$wrap
								.fadeOut()
								.removeClass('active-modal')
								.html('');
						});
					$('.kata-btn-plugin-action').on('click', function (event) {
						event.preventDefault();
						KataPlusInstallPlugin($(this), false);
					});

					/**
					 * Reset Demo
					 */
					$('.kata-import-demo-reset').on('click', function () {
						if (confirm(importer_localize.ajax.reset_message)) {
							$('.kata-import-demo-reset')
								.find('.dashicons-update-alt')
								.css('opacity', '1');
							$.ajax({
								url: importer_localize.ajax.url,
								name: 'reset_site',
								type: 'POST',
								data: {
									action: 'reset_site',
									reset: 'yes',
									nonce: importer_localize.ajax.nonce,
								},
								success: function (data, response) {
									$('.kata-import-demo-reset')
										.find('.dashicons-update-alt')
										.css('opacity', '0');
									if (data.status == 'not_allowed') {
										alert(data.message);
									} else {
										alert(data.message);
									}
								},
							});
						}
					});
				},
				error: function (response) {
					console.log(response);
				},
			});
		});
})(jQuery);
