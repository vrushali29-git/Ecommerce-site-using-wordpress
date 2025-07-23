(function ($) {
    /**
     * @param $scope The Widget wrapper element as a jQuery element
     * @param $ The jQuery alias
     */
    var WidgetGiftCardHandler = function ($scope, $) {
        console.log(1);
        $('.kata-plus-gift-cards').children('style').remove();
        

    };

    // Make sure you run this code under Elementor.
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/kata-plus-gift-cards.default', WidgetGiftCardHandler);
    });
})(jQuery);