(function ($) {
    /**
     * @param $scope The Widget wrapper element as a jQuery element
     * @param $ The jQuery alias
     */
    var WidgetStickyBoxHandler = function ($scope, $) {
        $scope.each(function (index, element) {

            var $this = $(this),
                sticky_id = $this.find('.kata-sticky-box').attr('id'),
                $sticky = $this.find('.kata-sticky-box#' + sticky_id),
                $stickyNavParent = $sticky.parent(),
                offset = $sticky.offset(),
                stickyNavTop = offset.top,
                stickyNavParentheight = $stickyNavParent.height(),
                stickyNavParenttop = $stickyNavParent.offset().top,
                stickySec = $sticky.attr('data-sec');

            var stickyNav = function () {
                var scrollTop = $(window).scrollTop() - 250;

                if (scrollTop > stickyNavTop) {
                    $sticky.css({
                        'position': ''
                    })
                    $sticky.addClass('sticky');
                } else {
                    if (scrollTop > 1) {
                        $sticky.css({
                            'position': ''
                        })
                        $sticky.addClass('sticky');
                    } else {
                        $sticky.removeClass('sticky');
                    }
                }

                var secSticky = stickyNavParentheight + stickyNavParenttop;


                if (stickySec == 'yes') {
                    if (scrollTop >= secSticky) {
                        $sticky.css({
                            'top': stickyNavParentheight,
                            'position': 'relative'
                        })
                        $sticky.removeClass('sticky');
                    }
                }
            };

            stickyNav();

            $(window).scroll(function () {
                stickyNav();
            });

            var stickyBox = $sticky;
            var postDes = stickyBox.attr('data-pos-des');
            var posMobile = stickyBox.attr('data-pos-mobile');
            var posTablet = stickyBox.attr('data-pos-tablet');

            // desktop
            switch (postDes) {
                case 'left':
                    stickyBox.css("left", "0");
                    break;

                case 'right':
                    stickyBox.css({
                        "right": "0",
                        "left": "auto"
                    });
                    break;

                case 'top':
                    stickyBox.css({
                        "top": "-250px",
                        "width": "100%"
                    });
                    break;

                case 'bottom':
                    stickyBox.css({
                        "bottom": "0",
                        "top": "auto",
                        "width": "100%"
                    });
                    break;
            }

            // tablet
            if (window.matchMedia('(max-width: 767px)').matches) {
                switch (posTablet) {
                    case 'left':
                        stickyBox.css({
                            "left": "0",
                            "width": "auto"
                        });
                        break;

                    case 'right':
                        stickyBox.css({
                            "right": "0",
                            "left": "auto",
                            "width": "auto"
                        });
                        break;

                    case 'top':
                        stickyBox.css({
                            "top": "0",
                            "width": "100%"
                        });
                        break;

                    case 'bottom':
                        stickyBox.css({
                            "bottom": "0",
                            "top": "auto",
                            "width": "100%"
                        });
                        break;
                }
            }

            // mobile
            if (window.matchMedia('(max-width: 480px)').matches) {
                switch (posMobile) {
                    case 'left':
                        stickyBox.css({
                            "left": "0",
                            "width": "auto"
                        });
                        break;

                    case 'right':
                        stickyBox.css({
                            "right": "0",
                            "left": "auto",
                            "width": "auto"
                        });
                        break;

                    case 'top':
                        stickyBox.css({
                            "top": "0",
                            "width": "100%"
                        });
                        break;

                    case 'bottom':
                        stickyBox.css({
                            "bottom": "0",
                            "top": "auto",
                            "width": "100%"
                        });
                        break;
                }
            }
        });
    };

    if ($('.kata-sticky-header-wrap').length > 0) {
        new WidgetStickyBoxHandler(jQuery('.kata-sticky-header-wrap'), jQuery);
    }

    // Make sure you run this code under Elementor.
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/kata-plus-sticky-box.default', WidgetStickyBoxHandler);
    });
})(jQuery);