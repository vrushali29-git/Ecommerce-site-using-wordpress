/**
 * Icon Control.
 *
 * @author  ClimaxThemes
 * @package	Kata Plus
 * @since	1.0.0
 */
jQuery(window).on('elementor:init', function () {
	lozad('.lozad', {
		load: function (el) {
			el.src = el.dataset.src;
			el.onload = function () {
				el.classList.add('kata-loaded')
			}
		}
	}).observe();

	var $dialog = jQuery('#kata-icons-dialog');
	var $icons = jQuery('.kata-icons');

	// Close dialog
	$dialog.find('.kata-dialog-close-btn').on('click', function (event) {
		$dialog.hide();
	});

	// Search icons
	var timer;

	function clear() {
		clearTimeout(timer); //clear any running timeout on key up
		timer = setTimeout(function () { //then give it a second to see if the user is finished
			jQuery(this).val('');
			jQuery('.search-result').html('');
			jQuery('.icons-wrap.search-result').removeClass('activated');
			jQuery('.icons-wrap:not(.search-result)').removeClass('deactivated');
			jQuery('.kata-search-icons').find('input[type="text"]').val('');
			jQuery('#kata-icon-box-search-styles').remove();
		}, 600);
	}

	$dialog.find('.management-btn').on('click', function () {
		if (jQuery('#kata-icons-dialog .icons-management-area').hasClass('hidden')) {
			var text = jQuery(this).data('back-text');
			jQuery(this).html(text);
			jQuery('ul.kata-icons .icons-wrap > .icon-pack-wrap').css('display', 'none');
			setTimeout(function () {
				jQuery('.kata-dialog-body').getNiceScroll().resize();
			}, 250);

			jQuery('#kata-icons-dialog .icons-management-area').removeClass('hidden');
			jQuery('.ul.kata-icons .icons-wrap > .icon-pack-wrap').css('height', '100%');
			jQuery(this).addClass('active');
		} else {
			var text = jQuery(this).data('text');
			jQuery(this).html(text);
			jQuery(this).removeClass('active');
			jQuery('#kata-icons-dialog .icons-management-area').addClass('hidden');
			jQuery('.ul.kata-icons .icons-wrap > .icon-pack-wrap').css('display', 'block');
			jQuery('.ul.kata-icons .icons-wrap > .icon-pack-wrap').css('height', '0px');
			jQuery('.kata-icons-dialog .section.icon-pack-section .icon-pack-wrap.active').removeClass('active');
			jQuery('.kata-icons-dialog .section.add-new-icon-pack-section').css('display', 'none');
			$dialog.find('ul.kata-filter-icons li').eq(1).find('a').trigger('click');
			setTimeout(function () {
				jQuery('.kata-dialog-body').getNiceScroll().resize();
			}, 250);
		}
		$dialog.find('.add-new-icon-pack-modal-section').css('display', 'none');
		setTimeout(function () {
			jQuery('#kata-icons-dialog .icons-management-area').getNiceScroll().resize();
		}, 250);
	});

	$dialog.find('.kata-back-wrap a').on('click', function () {
		$dialog.find('.management-btn').trigger('click');
		return false;
	});

	$dialog.find('.kata-new-pack-wrap a').on('click', function () {
		jQuery('.kata-icons-dialog .add-new-icon-pack-modal-section').removeClass('hidden');
		jQuery('.kata-icons-dialog .add-new-icon-pack-modal-section').css('display', 'block')
		return false;
	});

	jQuery('.kata-new-pack-form-wrap .kata-new-pack-back-step').on('click', function () {
		jQuery('.kata-icons-dialog .add-new-icon-pack-modal-section').addClass('hidden');
		jQuery('.kata-icons-dialog .add-new-icon-pack-modal-section').css('display', 'none')
		return false;
	});

	$dialog.find('.kata-new-pack-form-wrap .kata-new-pack-next-step').on('click', function () {
		var $name = jQuery('.kata-icons-dialog .kata-new-pack-form-wrap input.kata-new-pack-name').val();

		if ($name == '' && jQuery('.kata-new-pack-form-wrap').find('.kata-plus-elementor-error').length < 1) {
			jQuery('.kata-icons-dialog .kata-new-pack-form-wrap input.kata-new-pack-name').after('<p class="kata-plus-elementor-error">' + jQuery(".kata-icons-dialog .kata-new-pack-form-wrap input.kata-new-pack-name").data("required") + '</p>');
			setTimeout(function () {
				jQuery('.kata-new-pack-form-wrap').find('.kata-plus-elementor-error').remove();
			}, 2000);
			return false;
		}
		$dialog.find('.kata-new-pack-message').html('');
		jQuery.ajax({
			type: "post",
			url: kata_plus_admin_localize.ajax.url,
			data: {
				'action': 'kata_plus_pro_new_icon_pack',
				'name': $name
			},
			dataType: "json",
			success: function (response) {
				if (response.status == 'success') {
					jQuery('.kata-icons-dialog ul.kata-icon-packs-list').append(response.html);
					jQuery('.kata-icons-dialog ul.kata-icon-packs-menu').append(response.menuHtml);
					jQuery('.kata-icons-dialog ul.kata-icon-packs-menu').parent().removeClass('hidden');
					jQuery('.kata-icons-dialog .section.icon-pack-section').append(response.iconWrapHtml);
					jQuery('.' + response.hash).trigger('click');
					jQuery('.kata-new-pack-form-wrap .kata-new-pack-back-step').trigger('click')
					jQuery('.kata-icons-dialog .kata-new-pack-form-wrap input.kata-new-pack-name').val('');
				} else if (response.message != 'undefined') {
					$dialog.find('.kata-new-pack-message').html(response.message);
				}

			}
		});
		return false;
	});

	jQuery(document).on('click', '.kata-icon-packs-list .edit-icon-pack, .kata-icon-packs-list li>a', function (e) {
		e.preventDefault();
		var $this = jQuery(this),
			$wrap = $this.closest('.icon-pack-wrapper'),
			name = $this.parents('li[data-name]').data('name');
		$wrap.addClass('active').siblings().removeClass('active');
		jQuery('.icon-pack-section .icon-pack-wrap').each(function () {
			jQuery(this).removeClass('active');
		});
		jQuery('#plupload-upload-ui #uploaded-icons-pack-id').val($this.parents('li[data-name]').data('id'))
		jQuery('#plupload-upload-ui #uploaded-icons-pack-family').val($this.parents('li[data-name]').data('name'))
		jQuery('.section.icon-pack-section .icon-pack-wrap[data-pack="' + name + '"]').addClass('active');
		jQuery('.kata-icons-dialog .section.add-new-icon-pack-section').css('display', 'block');
		jQuery('#kata-icons-dialog .icons-management-area').getNiceScroll().resize();
	})


	jQuery(document).on('click', '.section.icon-pack-section li span.remove-icon', function () {
		var delete_confirm = confirm(jQuery(this).parents('.icon-pack-wrap').data('delete-message'));
		if (delete_confirm) {
			var $this = jQuery(this),
				ID = $this.data('id'),
				Key = $this.data('key');
			jQuery.ajax({
				type: "post",
				url: kata_plus_admin_localize.ajax.url,
				data: {
					action: 'kata_plus_pro_delete_icon_from_pack',
					id: ID,
					key: Key,
				},
				dataType: "json",
				success: function (response) {
					$this.parent().remove();
					jQuery('#kata-icons-dialog .icons-management-area').getNiceScroll().resize();
				},
				error: function (response) {
					if (typeof (response.message) != 'undefined') {
						alert(response.message)
					}
				}
			});
		}
		return false;
	})

	jQuery(document).on('click', '.section.icon-pack-section .icon-pack-wrap.active .save-and-close-edit-area', function () {

		jQuery('#kata-icons-dialog .icons-management-area').addClass('loading');
		var $this = jQuery(this),
			ID = $this.data('id'),
			Name = jQuery('.section.icon-pack-section .icon-pack-wrap.active .icon-pack-name').val();
		jQuery.ajax({
			type: "post",
			url: kata_plus_admin_localize.ajax.url,
			data: {
				action: 'kata_plus_pro_update_icon_pack_name',
				id: ID,
				name: Name,
			},
			dataType: "json",
			success: function (response) {
				jQuery('.kata-icons-dialog .section.icon-pack-section .icon-pack-wrap.active').removeClass('active');
				jQuery('.kata-icons-dialog .section.add-new-icon-pack-section').css('display', 'none');
				jQuery('.kata-icons-dialog .kata-icon-packs-list li[data-id="' + ID + '"] a').html(Name);
				jQuery('.kata-icons-dialog .kata-icon-packs-menu li[data-id="' + ID + '"] a').html(Name);
				jQuery('#kata-icons-dialog .icons-management-area').removeClass('loading');
				jQuery('#kata-icons-dialog .icons-management-area').getNiceScroll().resize();
			},
			error: function (response) {
				if (typeof (response.message) != 'undefined') {
					alert(response.message)
				}
			}
		});

		return false;
	})

	jQuery(document).on('click', '.kata-icon-packs-list .delete-icon-pack', function () {
		var delete_confirm = confirm(jQuery(this).parents('li[data-name]').data('delete-message'));
		if (delete_confirm) {
			var parent = jQuery(this).parents('li[data-name]');
			var name = parent.data('name');
			var ID = parent.data('id');
			jQuery.ajax({
				type: "post",
				url: kata_plus_admin_localize.ajax.url,
				data: {
					action: 'kata_plus_pro_delete_icon_pack',
					id: ID
				},
				dataType: "json",
				success: function (response) {
					jQuery('.section.icon-pack-section .icon-pack-wrap[data-pack="' + name + '"]').remove();
					parent.remove();
					jQuery('ul.kata-filter-icons li[data-name="uploaded-icons"] .kata-icon-packs-menu li[data-id="' + ID + '"]').remove();
					if (jQuery('ul.kata-filter-icons li[data-name="uploaded-icons"] .kata-icon-packs-menu li').length < 1) {
						jQuery('ul.kata-filter-icons li[data-name="uploaded-icons"]').addClass('hidden');
					}
					jQuery('#kata-icons-dialog .icons-management-area').getNiceScroll().resize();
				},
				error: function (response) {
					if (typeof (response.message) != 'undefined') {
						alert(response.message)
					}
				}
			});
		}
		return false;
	})

	jQuery(document).on('click', 'ul.kata-filter-icons li[data-name="uploaded-icons"]', function () {
		var $this = jQuery(this),
			$navwrap = $this.closest('.kata-filter-icons'),
			$menu = $this.find('.kata-icon-packs-menu');
		$navwrap.children('li').removeClass('active');
		if ($menu.hasClass('active')) {
			$menu.removeClass('active')
		} else {
			$menu.addClass('active')
		}
	})

	jQuery(document).mouseup(function (e) {
		if (!jQuery(e.target).hasClass('more-menu-btn') && !jQuery(e.target).parent().hasClass('more-menu-btn')) {
			var UploadedIcon = jQuery('ul.kata-filter-icons li[data-name="uploaded-icons"]');
			if (!UploadedIcon.is(e.target) && UploadedIcon.has(e.target).length === 0) {
				jQuery('.kata-icon-packs-menu').removeClass('active');
			}
		}
	});

	jQuery(document).ready(function () {
		jQuery('.icons-management-area').niceScroll({
			cursorcolor: "#aaa", // change cursor color in hex
			cursoropacitymin: 0, // change opacity when cursor is inactive (scrollabar "hidden" state), range from 1 to 0
			cursoropacitymax: 1, // change opacity when cursor is active (scrollabar "visible" state), range from 1 to 0
			cursorwidth: "7px", // cursor width in pixel (you can also write "5px")
			cursorborder: "none", // css definition for cursor border
			cursorborderradius: "5px", // border radius in pixel for cursor
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
				bottom: 1
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
	});

	$dialog.find('.kata-search-icons').children('input').on('input', function (el) {
		clearTimeout(timer); //clear any running timeout on key up
		timer = setTimeout(function () { //then give it a second to see if the user is finished
			var searchValue = jQuery(el.target).val();
			searchValue = searchValue.replace(' ', '-');
			if (searchValue != '') {
				if (jQuery('.search-result').find('').length == 0) {
					// jQuery('.search-result').html('');
					// jQuery('.icons-wrap').find('li[data-font-family][data-name*=' + searchValue + ']').clone().appendTo('.search-result');
					jQuery('#kata-icon-box-search-styles').remove();
					jQuery('.icon-pack-wrap').css('display', 'block');
					jQuery('<style id="kata-icon-box-search-styles">.kata-dialog-body li[data-font-family][data-name] {display:none;}.kata-dialog-body li[data-font-family][data-name*="' + searchValue + '"]{display:inline-block;}</style>').appendTo("head")
					lozad('.lozad', {
						load: function (el) {
							el.src = el.dataset.src;
							el.onload = function () {
								el.classList.add('kata-loaded')
							}
						}
					}).observe();
				}
			} else {
				jQuery('#kata-icon-box-search-styles').remove();
				jQuery('.icon-pack-wrap').css('display', 'none');
				jQuery('.icon-pack-wrap[data-pack="themify"]').css('display', 'block');
			}
			setTimeout(function () {
				jQuery('.kata-dialog-body').getNiceScroll().resize();
			}, 1);
		}, 250);
	});

	// Filter icons
	jQuery(document).on('click', '.kata-filter-icons a', function (event) {
		setTimeout(function () {
			jQuery('.kata-dialog-body').getNiceScroll().resize();
		}, 1);
		event.preventDefault();
		var $parent = jQuery(this).parent();
		var filterValue = $parent.data('name');
		$parent.addClass('active').siblings().removeClass('active');
		if (filterValue == 'all') {
			$icons.find('.icon-pack-wrap').show();
		} else {
			$icons.find('.icon-pack-wrap').hide();
			$icons.find('.icon-pack-wrap[data-pack*=' + filterValue + ']').show();
		}
	});

	// Draggable and Resizable
	$dialog.draggable({
		handle: '.kata-dialog-header',
		stop: function () {
			if (jQuery(this).css('top') < '276px') {
				jQuery(this).css('top', '276px')
			}
		}
	});

	var ControlIconView = elementor.modules.controls.BaseData.extend({
		onReady: function () {
			var self = this;
			var cid = this.model.cid;

			setTimeout(function () {
				// Choose icon
				jQuery('.kata-open-icons-dialog-btn.elementor-control-default-' + cid).each(function () {
					if (typeof (self.ui.input) == 'undefined') {
						return;
					}
					var $this = jQuery(this);
					var $removeBtn = $this.next();
					var $currentIcon = $this.prev();
					var uiInputValue = self.ui.input[0].value;
					if (uiInputValue.indexOf('7-stroke') != -1) {
						$currentIcon.attr('src', $currentIcon.attr('src').replace('kata-plus', 'kata-plus-pro'));
					} else {
						$currentIcon.attr('src', $currentIcon.attr('src').replace('kata-plus-pro', 'kata-plus'));
					}
					// Open dialog
					$this.off().on('click', function (event) {
						event.preventDefault();
						clear();
						jQuery('.kata-search-icons').find('input[type="text"]').val('');
						setTimeout(function () {
							jQuery('.kata-dialog-body').getNiceScroll().resize();
						}, 1);
						// Show dialog
						$dialog.show();
						$dialog.find('input[type="radio"]').prop('checked', false);
						$dialog.find('input[type="radio"][data-name="' + uiInputValue + '"]').prop('checked', true);
						// Add icon
						jQuery('#kata-icons-dialog').attr('data-cid', cid);
						jQuery(document).on('click', '#kata-icons-dialog[data-cid="' + cid + '"] li[data-font-family]', function () {
							var iconName = jQuery(this).data('name');
							if (iconName.indexOf('7-stroke') != -1) {
								$currentIcon.show().attr('src', $currentIcon.attr('data-src').replace('kata-plus', 'kata-plus-pro') + iconName + ".svg");
							} else {
								$currentIcon.show().attr('src', $currentIcon.attr('data-src') + iconName + ".svg");
							}
							$removeBtn.show();
							$this.parent().find('input').val(iconName);
							$dialog.find('input[type="radio"]').prop('checked', false);
							$dialog.find('input[type="radio"][data-name="' + iconName + '"]').prop('checked', true);
							self.setValue(iconName);
						});
					});

					// Remove icon
					$removeBtn.off().on('click ', function (event) {
						event.preventDefault();
						jQuery(this).hide();
						$currentIcon.hide();
						$this.parent().find('input').val('');
						self.setValue('');
					});
				});
			}, 100); // setTimeout
		},
		onBeforeDestroy: function () {
			jQuery('.kata-dialog-close-btn').trigger('click');
			clear();
		}
	});
	// distroy search
	jQuery('#kata-icons-dialog').find('.kata-dialog-close-btn').on('click', 'i', function () {
		console.log(jQuery(this));
		clear();
	});
	elementor.addControlView('kata_plus_icons', ControlIconView);
});
