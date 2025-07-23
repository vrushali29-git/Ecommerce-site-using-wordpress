(function ($) {
    /**
     * @param $scope The Widget wrapper element as a jQuery element
     * @param $ The jQuery alias
     */
    var WidgetKataPlusFoodMenuHandler = function ($scope, $) {
        var plus_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="22" height="28" viewBox="0 0 22 28"><title>plus2</title><path fill="#222" d="M22 11.5v3c0 0.828-0.672 1.5-1.5 1.5h-6.5v6.5c0 0.828-0.672 1.5-1.5 1.5h-3c-0.828 0-1.5-0.672-1.5-1.5v-6.5h-6.5c-0.828 0-1.5-0.672-1.5-1.5v-3c0-0.828 0.672-1.5 1.5-1.5h6.5v-6.5c0-0.828 0.672-1.5 1.5-1.5h3c0.828 0 1.5 0.672 1.5 1.5v6.5h6.5c0.828 0 1.5 0.672 1.5 1.5z"></path></svg>';
        var minus_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="22" height="28" viewBox="0 0 22 28"><title>minus3</title><path fill="#222" d="M22 11.5v3c0 0.828-0.672 1.5-1.5 1.5h-19c-0.828 0-1.5-0.672-1.5-1.5v-3c0-0.828 0.672-1.5 1.5-1.5h19c0.828 0 1.5 0.672 1.5 1.5z"></path></svg>';
        $scope.find('.kata-food-menu').on('click', function () {
            if ($(this).data('accordion') == true) {
                $(this).find('.kata-food-menu-content').slideDown().closest('.kata-food-menu').siblings().find('.kata-food-menu-content').slideUp();
                $btn = $(this).find('.kata-food-menu-btn');
                $scope.find('.kata-food-menu-content').not($(this).find('.kata-food-menu-content')).each(function () {
                    btn = $(this).parent().find(".kata-food-menu-btn");
                    $(this).parent().find(".kata-food-menu-btn i").removeClass();
                    $(this).parent().find(".kata-food-menu-btn i").addClass("kata-icon open");
                    $(this).parent().find(".kata-food-menu-btn i").html(plus_svg);
                });
                $btn.find('i').removeClass();
                $btn.find('i').addClass("kata-icon close");
                $btn.find('i').html(minus_svg);
            }
        });
    };
    // Make sure you run this code under Elementor.
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/kata-plus-food-menu.default', WidgetKataPlusFoodMenuHandler);
    });

})(jQuery);