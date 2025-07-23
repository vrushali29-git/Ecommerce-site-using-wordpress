(function ($) {
    /**
     * @param $scope The Widget wrapper element as a jQuery element
     * @param $ The jQuery alias
     */
    var WidgetComparisonSliderHandler = function ($scope, $) {
        var kataComparisonslider = $scope.find(".kata-comparison-slider"),
            img1 = kataComparisonslider.data('img1'),
            img2 = kataComparisonslider.data('img2'),
            title1 = kataComparisonslider.data('title1'),
            title2 = kataComparisonslider.data('title2'),
            orientation = kataComparisonslider.data('orientation'),
            pos = kataComparisonslider.data('pos'),
            selector = '#' + kataComparisonslider.attr('id');

        new juxtapose.JXSlider(selector,
            [{
                    src: img1,
                    label: title1,
                },
                {
                    src: img2,
                    label: title2,
                }
            ], {
                animate: true,
                showLabels: true,
                startingPosition: pos,
                makeResponsive: true,
                mode: orientation,
            });
    };
    // Make sure you run this code under Elementor.
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/kata-plus-comparison-slider.default', WidgetComparisonSliderHandler);
    });
})(jQuery);