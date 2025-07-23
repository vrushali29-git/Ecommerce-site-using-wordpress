(function ($) {
	/**
	 * @param $scope The Widget wrapper element as a jQuery element
	 * @param $ The jQuery alias
	 */
	var WidgetVideoPlayerHandler = function ($scope, $) {
		// open
		$('.kata-plus-video-player:not(.kata-lightbox)')
			.find('a')
			.on('click', function (e) {
				e.preventDefault();
				var $this = $(this),
					$wrap = $this.closest('.kata-plus-video-player'),
					type = $wrap.attr('data-video-type'),
					src = $this.attr('href');

				if (!$wrap.hasClass('kata-lightbox')) {
					if (type == 'youtube') {
						var iframe =
							'<iframe src="https://www.youtube-nocookie.com/embed/' +
							src +
							'?autoplay=1&amp;rel=0&amp;showinfo=0" allowfullscreen></iframe><i class="close_inline_video">✖</i>';
					} else if (type == 'vimeo') {
						var iframe =
							'<iframe src="https://player.vimeo.com/video/' +
							src +
							'?autoplay=1" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe><i class="close_inline_video"></i>';
					} else if (type == 'local') {
						var iframe =
							'<video width="800" controls> <source src="' +
							src +
							'" type="video/mp4"> </video><i class="close_inline_video">✖</i>';
					}
					$wrap.find('img, .kata-vp-conent').css({
						opacity: '0',
						transition: 'opacity .3s ease',
						'-webkit-transition': 'opacity .3s ease',
					});
					$wrap.append(iframe);
					// close
					$('.kata-plus-video-player')
						.find('i.close_inline_video')
						.on('click', function () {
							$wrap.find('iframe').remove();
							$wrap.find('video').remove();
							$(this).remove();
							$wrap
								.find('img, .kata-vp-conent')
								.css({ opacity: '1' });
						});
				}
			});
		$('.kata-lightbox').lightGallery({
			zoom: false,
			actualSize: false,
			videoMaxWidth: '70%',
			enableZoomAfter: 50,
			counter: false,
		});
	};

	// Make sure you run this code under Elementor.
	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/kata-plus-video-player.default',
			WidgetVideoPlayerHandler
		);
	});
})(jQuery);
