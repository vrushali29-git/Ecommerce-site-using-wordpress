(function ($) {

    /**
     * @param $scope The Widget wrapper element as a jQuery element
     * @param $ The jQuery alias
     */
    var WidgetCourselHandler = function ($scope, $) {

        $(".lightgallery").lightGallery({
            selector: '.carousel-image',
            getCaptionFromTitleOrAlt: true
        });

        $(".kata-plus-carousel-grid").each(function () {

            var cloned_div = "#" + $(this).data('id') + "_cloned";
            var self = $(this);
            var datas = $(this).data();
            var owl = $(this);

            // Numbers
            if (datas['number'] == true) {
                owl.on('changed.owl.carousel', function (event) {
                    var total_container = self;
                    var pos = event.relatedTarget.normalize(event.item.index, true);
                    var totalItems = event.item.count;

                    if (totalItems > 0 && totalItems < 10) {
                        totalItems = '0' + totalItems;
                    }

                    pos = pos + 1

                    if (pos > 0 && pos < 10) {
                        pos = '0' + pos;
                    }
                    $(total_container).siblings('.content-slider-num').html('<span class="content-slider-num-current">' + pos + '</span><span class="content-slider-num-total">/' + totalItems + '</span>');
                });
            }
            $('.kata-owl').each(function (index, element) {
                var $this = $(this);
                var data = $this.data();
                $this.owlCarousel({
                    smartSpeed: data['inc_owl_smspd'],
                    dots: data['inc_owl_pag'],
                    loop: data['inc_owl_loop'],
                    rtl: data['inc_owl_rtl'],
                    autoplay: data['inc_owl_autoplay'],
                    center: data['inc_owl_center'],
                    animateOut: data['animateout'],
                    animateIn: data['animatein'],
                    autoplayTimeout: data['inc_owl_spd'],
                    autoplayHoverPause: data['inc_owl_autoplayhoverpause'],
                    navText: [window.atob(data['inc_owl_prev']), window.atob(data['inc_owl_nxt'])],
                    responsive: {
                        320: {
                            items: data['inc_owl_itemmob'],
                            stagePadding: data['inc_owl_stgpadmob'],
                            margin: data['inc_owl_marginmob'],
                            dots: data['inc_owl_pag_mob'],
                        },
                        768: {
                            items: data['inc_owl_itemtab'],
                            stagePadding: data['inc_owl_stgpadtab'],
                            margin: data['inc_owl_margintab'],
                            dots: data['inc_owl_pag_tab'],
                        },
                        1024: {
                            items: data['inc_owl_item'],
                            stagePadding: data['inc_owl_stgpad'],
                            margin: data['inc_owl_margin'],
                            nav: data['inc_owl_arrow'],
                        }
                    }
                });
                // Progress bar
                if ($scope.find(this).hasClass('dots-and-num')) {
                    var $progress = $('.dots-and-num').find('.kata-owl-progress-bar'),
                        items = $scope.find(this).find('.owl-item').not('.cloned').length,
                        percentage = 100 / items;
                    $('<span class="maxitems">' + items + '</span>').appendTo($scope.find(this).next('.kata-owl-progress-bar'));
                    $('<span class="minitems">1</span>').prependTo($scope.find(this).next('.kata-owl-progress-bar'));
                    $('.kata-progress-bar-inner').css('width', percentage);
                    $scope.find(this).on('translate.owl.carousel', function (event) {
                        var $this = $scope.find(this),
                            $items = $this.find('.owl-item.active'),
                            progresswidth = $this.next('.kata-owl-progress-bar').children('.kata-progress-bar-inner').css('width'),
                            progresswidth = parseInt(progresswidth.replace('px', '')),
                            max = progresswidth + percentage;
                        if (max >= 101) {
                            $this.next('.kata-owl-progress-bar').children('.kata-progress-bar-inner').css('width', Math.round(percentage));
                        } else {
                            $this.next('.kata-owl-progress-bar').children('.kata-progress-bar-inner').css('width', Math.round(max));
                        }
                    })
                }
            });
            $('.owl-filter-bar').on('click', '.item', function () {
                var $item = $(this);
                var filter = $item.data('owl-filter')
                owl.owlcarousel2_filter(filter);
                $('.kata-owl').each(function (index, element) {
                    var $this = $(this);
                    var data = $this.data();
                    $this.owlCarousel({
                        smartSpeed: data['inc_owl_smspd'],
                        dots: data['inc_owl_pag'],
                        loop: data['inc_owl_loop'],
                        rtl: data['inc_owl_rtl'],
                        autoplay: data['inc_owl_autoplay'],
                        center: data['inc_owl_center'],
                        animateOut: data['animateout'],
                        animateIn: data['animatein'],
                        autoplayTimeout: data['inc_owl_spd'],
                        autoplayHoverPause: data['inc_owl_autoplayhoverpause'],
                        navText: [window.atob(data['inc_owl_prev']), window.atob(data['inc_owl_nxt'])],
                        responsive: {
                            320: {
                                items: data['inc_owl_itemmob'],
                                stagePadding: data['inc_owl_stgpadmob'],
                                margin: data['inc_owl_marginmob'],
                                dots: data['inc_owl_pag_mob'],
                            },
                            768: {
                                items: data['inc_owl_itemtab'],
                                stagePadding: data['inc_owl_stgpadtab'],
                                margin: data['inc_owl_margintab'],
                                dots: data['inc_owl_pag_tab'],
                            },
                            1024: {
                                items: data['inc_owl_item'],
                                stagePadding: data['inc_owl_stgpad'],
                                margin: data['inc_owl_margin'],
                                nav: data['inc_owl_arrow'],
                            }
                        }
                    });
                });
                // Progress bar
                if ($scope.find(this).hasClass('dots-and-num')) {
                    var $progress = $('.dots-and-num').find('.kata-owl-progress-bar'),
                        items = $scope.find(this).find('.owl-item').not('.cloned').length,
                        percentage = 100 / items;
                    $('<span class="maxitems">' + items + '</span>').appendTo($scope.find(this).next('.kata-owl-progress-bar'));
                    $('<span class="minitems">1</span>').prependTo($scope.find(this).next('.kata-owl-progress-bar'));
                    $('.kata-progress-bar-inner').css('width', percentage);
                    $scope.find(this).on('translate.owl.carousel', function (event) {
                        var $this = $scope.find(this),
                            $items = $this.find('.owl-item.active'),
                            progresswidth = $this.next('.kata-owl-progress-bar').children('.kata-progress-bar-inner').css('width'),
                            progresswidth = parseInt(progresswidth.replace('px', '')),
                            max = progresswidth + percentage;
                        if (max >= 101) {
                            $this.next('.kata-owl-progress-bar').children('.kata-progress-bar-inner').css('width', Math.round(percentage));
                        } else {
                            $this.next('.kata-owl-progress-bar').children('.kata-progress-bar-inner').css('width', Math.round(max));
                        }
                    })
                }
            })

            if ($(this).find(".grid-item").find('.open-modal').length > 0) {
                $(this).find(".grid-item").find('.open-modal').on('click', function (e) {
                    $(this).closest('.grid-item').find('.carousel-image').trigger('click');
                });
            }
        });
    }
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/kata-plus-carousel-grid.default', WidgetCourselHandler);
    });

})(jQuery);