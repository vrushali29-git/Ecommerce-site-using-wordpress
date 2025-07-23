(function ($) {
    /**
     * @param $scope The Widget wrapper element as a jQuery element
     * @param $ The jQuery alias
     */
    var WidgetTeamMemberHandler = function ($scope, $) {
        // titl options
        $('.kata-plus-tilt').each(function (index, element) {
            var $this = $(this),
                datas = $this.data();

            $(this).tilt({
                glare: true,
                maxGlare: datas['glare'],
                scale: datas['scale'],
            });
        });
    };

    // Make sure you run this code under Elementor.
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/kata-plus-team-member.default', WidgetTeamMemberHandler);
    });
})(jQuery);