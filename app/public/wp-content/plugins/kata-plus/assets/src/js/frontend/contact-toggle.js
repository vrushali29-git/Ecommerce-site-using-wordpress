(function ($) {
    /**
     * @param $scope The Widget wrapper element as a jQuery element
     * @param $ The jQuery alias
     */
    var WidgetContentToggleHandler = function ($scope, $) {
        $('.kata-contact-toggle').find('.linktopen').on('click', function (e) {
            var $this = $(this),
                $wrap = $this.closest('.kata-contact-toggle'),
                $view = $wrap.attr('data-view'),
                $content = $wrap.find('.contact-content');

            if ($view == 'modal') {
                e.preventDefault();
                if ($content.css('display') == 'none') {
                    setTimeout(function () {
                        $content.fadeIn();
                    }, 100);
                } else {
                    setTimeout(function () {
                        $content.fadeOut();
                    }, 100);
                }
            } else if ($view == 'drop') {
                e.preventDefault();
                if ($content.css('display') == 'none') {
                    setTimeout(function () {
                        $content.slideDown();
                    }, 100);
                } else {
                    setTimeout(function () {
                        $content.slideUp();
                    }, 100);
                }
            }
        });
    };
    // Make sure you run this code under Elementor.
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/kata-plus-contact-toggle.default', WidgetContentToggleHandler);
    });
})(jQuery);