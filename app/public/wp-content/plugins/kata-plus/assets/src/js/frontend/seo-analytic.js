(function ($) {
    /**
     * @param $scope The Widget wrapper element as a jQuery element
     * @param $ The jQuery alias
     */
    var WidgetseoAnalyticHandler = function ($scope, $) {
                          
        var input  = $('.kata-plus-seo-analytic').find('input'),
            form   = $('.kata-seo-analytic');
            action = $('.kata-seo-analytic').attr('action'),

        $(form).on('submit', function (e) {
            e.preventDefault();
            result = action + input.val();
            $('.kata-seo-analytic').attr('action', result);
            location.replace(result);
        });
    
    };

    // Make sure you run this code under Elementor.
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/kata-plus-seo-analytic.default', WidgetseoAnalyticHandler);
    });
})(jQuery);