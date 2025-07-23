(function ($) {
    /**
     * @param $scope The Widget wrapper element as a jQuery element
     * @param $ The jQuery alias
     */
    var WidgetIMGHZHandler = function ($scope, $) {
        $('.kata-image-hover-zoom').zoom();
    };

    // Make sure you run this code under Elementor.
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/kata-plus-image-hover-zoom.default', WidgetIMGHZHandler);
    });
})(jQuery);