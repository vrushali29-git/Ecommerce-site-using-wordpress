(function ($) {

    /**
     * @param $scope The Widget wrapper element as a jQuery element
     * @param $ The jQuery alias
     */
    var WidgetMasonryHandler = function ($scope, $) {
        $(".kata-plus-masonry-grid-wrap").each(function () {
            var $this = $(this);
            $this.find(".ms-lightgallery").lightGallery({
                selector: '.masonry-image',
            });
            if ($this.find(".ms-lightgallery").find('.open-modal').length > 0) {
                $this.find(".ms-lightgallery").find('.open-modal').on('click', function (e) {
                    $this.closest('.ms-grid-item').find('.masonry-image').trigger('click');
                });
            }
            $this.find('.masonry-category-filters').children('span').on('click', function () {
                var $this = $(this),
                    $wrap = $this.closest('.kata-plus-masonry-grid-wrap'),
                    filter = $this.data('filter');
                $wrap.find('.kata-filter-view').html('');
                $this.addClass('active').siblings().removeClass('active');
                $wrap.find('.ms-grid-item' + filter).clone().prependTo($wrap.find('.kata-filter-view'));
                if ($('.kata-filter-view').hasClass('ms-lightgallery')) {
                    $wrap.find('.kata-filter-view').find('.ms-grid-item').find('a').on('click', function (e) {
                        e.preventDefault();
                        var $this = $(this),
                            $item = $this.parent('.ms-grid-item'),
                            $parent = $item.parent('.ms-lightgallery'),
                            $wrap = $parent.parent('.kata-plus-masonry-grid'),
                            $light = $parent.find('.masonry-image'),
                            itemid = $item.attr('id');
                        $wrap.find('div[class*="kata-masonry-grid-row"]').find('#' + itemid).find('.masonry-image').trigger('click');
                    });
                }
                $wrap.find('.ms-grid-item').removeClass('animate').animate({
                    top: "50px",
                    opacity: 0
                }, {
                    duration: 500,
                    queue: false
                });
                $wrap.find('.ms-grid-item' + filter).addClass('animate').animate({
                    top: "0",
                    opacity: 1
                }, {
                    duration: 500,
                    queue: false
                });
                if (filter == '*') {
                    $wrap.find('.kata-filter-view').html('');
                    $wrap.find('.kata-plus-masonry-grid div[class*="kata-masonry-grid-row"]').show();
                } else {
                    $wrap.find('.kata-plus-masonry-grid div[class*="kata-masonry-grid-row"]').hide();
                }
            });

        });
        $('.ms-grid-item').each(function (index, element) {
            var $this = $(this),
                dataclass = $this.find('.masonry-image').find('img').data('class');
            $this.addClass(dataclass);
        });

        $('.ms-grid-item').each(function (index, element) {
            if ($(this).find('.open-modal').length > 0) {
                $(this).find('.open-modal').on('click', function (e) {
                    $(this).closest('.ms-grid-item').find('.masonry-image img').trigger('click');
                });
            }
        });
    }
    // Make sure you run this code under Elementor.
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/kata-plus-masonry-grid.default', WidgetMasonryHandler);
    });

})(jQuery);