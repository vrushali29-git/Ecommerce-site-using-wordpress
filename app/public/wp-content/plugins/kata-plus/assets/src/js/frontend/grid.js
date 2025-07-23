(function ($) {

    /**
     * @param $scope The Widget wrapper element as a jQuery element
     * @param $ The jQuery alias
     */
    var WidgetGridHandler = function ($scope, $) {
        $scope.find(".grid-lightgallery").lightGallery({
            selector: '.grid-image',
        });
        $('.open-modal').find('.grid-title a').on('click', function (e) {
            e.preventDefault();
        });
        $(document).on('click', '[data-filter]', function (e) {
            var $this = $(this),
                data = $this.data('filter');
            
            if( $('#kata-grid-filter-style').length ) {
                $('#kata-grid-filter-style').remove();
            }
            $('.kata-grid-item:not(' + data + ')').fadeOut(400);
            $('.kata-grid-item' + data).fadeIn(400);
            // $('<style id="kata-grid-filter-style">.kata-grid-item{display:none;}.kata-grid-item' + data +'{display: block;}</style>').appendTo('body');
        });
        $('.kata-plus-grid').each(function (index, element) {
            if ($(this).find('.open-modal').length > 0) {
                $(this).find('.open-modal').on('click', function (e) {
                    $(this).closest('.kata-grid-item').find('.grid-image img').trigger('click');
                });
            }
        });
    }
    // Make sure you run this code under Elementor.
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/kata-plus-grid.default', WidgetGridHandler);
    });

})(jQuery);