/**
 * Builders JS
 *
 * @author  ClimaxThemes
 * @package	Kata Plus
 * @since	1.2.0
 */
'use strict';

var Kata_Plus_Builders_Manager = (function ($) {
	return {
		/**
		 * Init.
		 *
		 * @since	1.2.0
		 */
		init: function () {
			this.ModifyAddNewBuilderURL();
			this.ModifyAddNewTranslationURL();
			this.SetPrimaryBuilder();
			this.SetDefaultPrimaryBuilder();
		},

		/**
		 * Modify add new builder url.
		 *
		 * @since	1.2.0
		 */
		ModifyAddNewBuilderURL: function () {
			var add_new_href =
				$('.page-title-action').attr('href') +
				'&builder=' +
				kata_builders_localize.builder_branch;
			$('.page-title-action').attr('href', add_new_href);

			$('tr.type-kata_plus_builder').each(function (index, element) {
				var target = $(this).find('.row-title');
				var edit_href =
					target.attr('href') +
					'&builder=' +
					kata_builders_localize.builder_branch;
				console.log(edit_href);
				$(target).attr('href', edit_href);
			});
		},

		/**
		 * Modify add new builder url.
		 *
		 * @since	1.2.0
		 */
		ModifyAddNewTranslationURL: function () {
			if ($('.pll_icon_add').length) {
				var href =
					$('.pll_icon_add').attr('href') +
					'&builder=' +
					kata_builders_localize.builder_branch;
				$('.pll_icon_add').attr('href', href);
			}
		},

		/**
		 * Set Primary Builder.
		 *
		 * @since	1.2.0
		 */
		SetPrimaryBuilder: function () {
			$(document).on('change', '.primary-kata-builder', function (e) {
				var $this = $(this),
					builder_id = $this.attr('value'),
					builder_type = $this.attr('name');

				if (e.target.checked) {
					$.ajax({
						url: kata_builders_localize.ajax.url,
						type: 'POST',
						dataType: 'json',
						data: {
							action: 'set_primary_builder',
							builder_type: builder_type,
							builder_id: builder_id,
							nonce: kata_builders_localize.ajax.nonce,
						},
						success: function (response, data) {
							alert(response);
						},
						error: function (response) {
							alert(response);
						},
					});
				}
			});
		},

		/**
		 * Set Primary Builder.
		 *
		 * @since	1.2.0
		 */
		SetDefaultPrimaryBuilder: function () {
			$('.update-kata-builders .button ').on('click', function (e) {
				var $this = $(this);
				e.preventDefault();
				$this
					.closest('.update-kata-builders')
					.addClass('notice-alt updating-message');

				$this.text('Updating database...');

				$.ajax({
					url: kata_builders_localize.ajax.url,
					type: 'POST',
					dataType: 'json',
					data: {
						action: 'update_builders',
						update_builders: true,
						nonce: kata_builders_localize.ajax.nonce,
					},
					success: function (response, data) {
						console.log($this);
						$this.html(response.message);

						setTimeout(function () {
							$this
								.closest('.update-kata-builders')
								.find('.notice-dismiss')
								.trigger('click');
							location.reload();
						}, 2000);
					},
					error: function (response) {
						console.log('error');
					},
				});
			});
		},
	};
})(jQuery);

jQuery(document).on('ready', function () {
	Kata_Plus_Builders_Manager.init();
});
