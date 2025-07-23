(function ($) {

    /**
     * @param $scope The Widget wrapper element as a jQuery element
     * @param $ The jQuery alias
     */
    var WidgetCourselHandler = function ($scope, $) {
        $scope.find(".lightgallery").lightGallery({
            selector: '.kata-image-carousel-img',
        });
    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/kata-plus-image-carousel.default', WidgetCourselHandler);
    });

})(jQuery);