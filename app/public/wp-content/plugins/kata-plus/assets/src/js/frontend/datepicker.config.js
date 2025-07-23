(function ($) {
    /**
     * @param $scope The Widget wrapper element as a jQuery element
     * @param $ The jQuery alias
     */
    var WidgetDatePickerHandler = function ($scope, $) {
        $(".datepicker").each(function (index, element) {
            $(this).flatpickr({
                disableMobile: true,
            });
            setTimeout(function () {
                if ($(this).attr('type') == 'hidden') {
                    $(this).attr('type', 'text');
                    $(this).next().css('display', 'none');
                }
            }, 4000);
            $(".kata-plus-book-table-date .arrow").click(function () {
                $(this).datepicker("show");
            });
        });
    };

    // Make sure you run this code under Elementor.
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/kata-plus-book-table.default', WidgetDatePickerHandler);
    });
})(jQuery);