(function ($) {
	class KataElementorSticky extends elementorModules.frontend.handlers.Base {
		/**
		 * Returns the default settings for the Kata Elementor Sticky handler.
		 *
		 * @return {Object} An object containing the default settings.
		 */
		getDefaultSettings() {
			if (typeof this.getElementSettings('kata_sticky') == 'undefined') {
				return;
			}

			return {
				id: this.getID(),
				kata_sticky: this.getElementSettings('kata_sticky'),
				kata_sticky_in_column:
					this.getElementSettings('kata_sticky_in_column') == 'yes'
						? true
						: false,
				kata_sticky_on: this.getElementSettings('kata_sticky_on'),
				kata_sticky_offset:
					this.getElementSettings('kata_sticky_offset'),
			};
		}

		/**
		 * On Init
		 *
		 * Runs when the widget is loaded and initialized in the frontend.
		 */
		onInit() {
			this.settings = this.getDefaultSettings();

			if (typeof this.settings == 'undefined') {
				return;
			}

			if (typeof this.settings != 'undefined') {
				if (this.settings.kata_sticky == '') {
					return;
				}
			}

			this.onElementChange();
		}

		/**
		 * Checks if the current environment is the Elementor editor.
		 *
		 * @return {boolean} True if the environment is the Elementor editor, false otherwise.
		 */
		isEditor() {
			if (typeof elementor == 'undefined') {
				return false;
			}

			return true;
		}

		/**
		 * Checks if the current environment has a top bar.
		 *
		 * @return {boolean} True if the environment has a top bar, false otherwise.
		 */
		hasTopBar() {
			if (this.isEditor() || document.getElementById('wpadminbar'))
				return true;

			return false;
		}

		/**
		 * Calculates the top bar spacing based on the current environment.
		 *
		 * @return {number} The top bar spacing in pixels.
		 */
		calculateTopbarSpacing() {
			if (!this.hasTopBar()) {
				return 0;
			}

			if (document.getElementById('wpadminbar')) {
				return 32;
			}

			if (document.getElementById('elementor-editor-wrapper-v2')) {
				return 48;
			}

			if (elementor.$previewResponsiveWrapper.hasClass('ui-resizable')) {
				return 40;
			}
		}

		/**
		 * Initializes the sticky element.
		 *
		 * @return {void}
		 */
		prepareSticky() {
			setTimeout(() => {
				const topBarSpacing = this.calculateTopbarSpacing();
				const target = $(this.$element[0]).find('.kata-sticky-wrap')[0];
				const offset = this.settings.kata_sticky_offset + topBarSpacing;
				var options = {};

				if (this.settings.kata_sticky == 'top') {
					options['topSpacing'] = offset;
				}

				if (this.settings.kata_sticky == 'bottom') {
					options['bottomSpacing'] = offset;
				}

				const stickySidebar = new StickySidebar(target, options);

				if (this.settings.kata_sticky == 'bottom') {
					target.addEventListener(
						'affixed.top.stickySidebar',
						function (event) {
							event.target.querySelector(
								'.inner-wrapper-sticky'
							).style.top = 'auto';
							event.target.querySelector(
								'.inner-wrapper-sticky'
							).style.bottom = offset + 'px';
						}
					);
				}

				if (!this.settings.kata_sticky_in_column) {
					if (this.settings.kata_sticky == 'top') {
						target.addEventListener(
							'affixed.container-bottom.stickySidebar',
							function (event) {
								const width = event.target.offsetWidth;

								event.target.querySelector(
									'.inner-wrapper-sticky'
								).style.width = width + 'px';
								event.target.querySelector(
									'.inner-wrapper-sticky'
								).style.position = 'fixed';
								event.target.querySelector(
									'.inner-wrapper-sticky'
								).style.top = offset + 'px';
								event.target.querySelector(
									'.inner-wrapper-sticky'
								).style.transform = 'translate(0)';
							}
						);
					}

					if (this.settings.kata_sticky == 'bottom') {
						target.addEventListener(
							'affixed.container-bottom.stickySidebar',
							function (event) {
								const width = event.target.offsetWidth;

								event.target.querySelector(
									'.inner-wrapper-sticky'
								).style.width = width + 'px';
								event.target.querySelector(
									'.inner-wrapper-sticky'
								).style.position = 'fixed';
								event.target.querySelector(
									'.inner-wrapper-sticky'
								).style.bottom = offset + 'px';
								event.target.querySelector(
									'.inner-wrapper-sticky'
								).style.transform = 'translate(0)';
							}
						);
					}
				}

				// Update sticky sidebar when device mode changes
				var currentDeviceMode =
					elementorFrontend.getCurrentDeviceMode();
				var activeDevices = this.settings.kata_sticky_on;

				if (-1 !== activeDevices.indexOf(currentDeviceMode)) {
					stickySidebar.updateSticky();
				} else {
					stickySidebar.destroy();
				}

				if (this.settings.kata_sticky_in_column) {
					target.addEventListener(
						'affix.container-bottom.stickySidebar',
						function (event) {
							stickySidebar.destroy();
							const stickySidebar = new StickySidebar(
								target,
								options
							);
						}
					);
				}
			}, 500);
		}

		/**
		 * Initializes the sticky element.
		 *
		 * @return {void}
		 */
		initialConfig() {
			if ($(this.$element[0]).children('.kata-sticky-wrap').length == 0) {
				$(this.$element[0])
					.children('.elementor-element')
					.wrapAll('<div class="kata-sticky-wrap"></div>');

				$(this.$element[0])
					.children('.elementor-element-overlay')
					.appendTo(this.$element[0]);
				$(this.$element[0])
					.children('.elementor-shape.elementor-shape-top')
					.appendTo(this.$element[0]);
				$(this.$element[0])
					.children('.elementor-shape.elementor-shape-bottom')
					.appendTo(this.$element[0]);
				$(this.$element[0])
					.children('.ui-resizable-handle.ui-resizable-e')
					.appendTo(this.$element[0]);
			}
		}

		/**
		 * Initializes the sticky element.
		 *
		 * @return {void}
		 */
		resetConfig() {
			if ($(this.$element[0]).children('.kata-sticky-wrap').length > 0) {
				$(this.$element[0])
					.find('.elementor-element')
					.unwrap('.inner-wrapper-sticky');
				$(this.$element[0])
					.find('.elementor-element')
					.unwrap('.kata-sticky-wrap');
			}
		}

		/**
		 * On Element Change
		 *
		 * Runs every time a control value is changed by the user in the editor.
		 *
		 * @param {string} propertyName - The ID of the control that was changed.
		 */
		onElementChange(propertyName) {
			this.settings = this.getDefaultSettings();

			if (typeof this.settings == 'undefined') {
				return;
			}

			if (typeof this.settings != 'undefined') {
				if (this.settings.kata_sticky == '') {
					this.resetConfig();
					return;
				}
			}

			this.initialConfig();
			this.prepareSticky();
		}
	}

	$(window).on('elementor/frontend/init', function () {
		const addHandler = function ($element) {
			elementorFrontend.elementsHandler.addHandler(KataElementorSticky, {
				$element,
			});
		};

		elementorFrontend.hooks.addAction(
			'frontend/element_ready/container',
			addHandler
		);
	});
})(jQuery);
