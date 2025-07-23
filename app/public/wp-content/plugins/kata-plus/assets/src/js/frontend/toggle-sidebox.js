(function ($) {

    /**
     * @param $scope The Widget wrapper element as a jQuery element
     * @param $ The jQuery alias
     */
    var WidgetToggleSideBoxHandler = function ($scope, $) {
        $scope.find('.toggle-sidebox-trigger').on('click', function () {
            if ($(this).hasClass('open-toggle')) {
                $(this).removeClass('open-toggle');
                $scope.find(".toggle-sidebox-content").removeClass('open-toggle');
            } else {
                $(this).addClass('open-toggle');
                $scope.find(".toggle-sidebox-content").addClass('open-toggle');
            } 
        });
    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/kata-plus-toggle-sidebox.default', WidgetToggleSideBoxHandler);
    });

})(jQuery);