(function ($) {
    /**
     * @param $scope The Widget wrapper element as a jQuery element
     * @param $ The jQuery alias
     */
    var WidgetTestimonialsVerticalHandler = function ($scope, $) {
        $('.kata-testimonials-vertical').each(function (index, element) {
            var $wrap = $(this),
                $items = $wrap.find('.kata-testimonials-vertical-item'),
                $parent = $wrap.closest('.kata-testimonials-vertical-wrapper'),
                $itemlength = $items.length,
                $height = $items.outerHeight(),
                current = 1,
                slidespd = $wrap.data('speed'),
                t = 0;

            $parent.css('height', $height * 3);
            if (window.matchMedia('(max-width: 767px)').matches) {
                $parent.css('height', $height * 2);

            }
            if (window.matchMedia('(max-width: 480px)').matches) {
                $parent.css('height', $height * 2);
            }
            $items.first().clone().appendTo($wrap);
            $items.last().clone().prependTo($wrap);
            $wrap.find('.kata-testimonials-vertical-item:nth-of-type(' + ++current + ')').addClass('active');

            setInterval(function () {
                if (current <= $itemlength) {
                    t = $height + t;
                    $wrap.css('transform', 'translateY(-' + t + 'px)');
                    $wrap.find('.kata-testimonials-vertical-item:nth-of-type(' + ++current + ')').addClass('active').siblings().removeClass('active');
                } else {
                    current = 1;
                    t = 0;
                    $wrap.css('transform', 'translateY(-' + t + 'px)');
                    $wrap.find('.kata-testimonials-vertical-item:nth-of-type(' + ++current + ')').addClass('active');
                    $items.first().addClass('active').siblings().removeClass('active');
                }
            }, slidespd);
        });
    };

    // Make sure you run this code under Elementor.
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/kata-plus-testimonials-vertical.default', WidgetTestimonialsVerticalHandler);
    });
})(jQuery);