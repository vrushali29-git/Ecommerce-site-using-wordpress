(function ($) {
    /**
     * @param $scope The Widget wrapper element as a jQuery element
     * @param $ The jQuery alias
     */
    var WidgetFoodMenuToggleHandler = function ($scope, $) {
        var item = $('.kata-plus-food-menu-toggle').find('.image-content');
        item.first().addClass('active');

        $scope.find('.kata-food-menu').on('click', function () {
            var $this = $(this),
                $wrap = $this.closest('.kata-plus-food-menu-toggle'),
                dataItem = $this.data('item');
            $this.addClass('active').siblings().removeClass('active');
            $wrap.find('.right-side-food .image-content[data-item="' + dataItem + '"]').addClass('active').siblings().removeClass('active');
        });
    }
    // Make sure you run this code under Elementor.
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/kata-plus-food-menu-toggle.default', WidgetFoodMenuToggleHandler);
    });

})(jQuery);