(function ($) {
    /**
     * @param $scope The Widget wrapper element as a jQuery element
     * @param $ The jQuery alias
     */
    var WidgetTeamMemberHandler = function ($scope, $) {

        // people number
        $('.kata-plus-book-table-people').find('.num-plus').on('click', function () {
            var inval = $(this).closest('.kata-plus-book-table-people').find('input').val();
            $(this).closest('.kata-plus-book-table-people').find('input').val(++inval);
            $(this).closest('.kata-plus-book-table-people').find('input').attr('value', inval);
            if ($(this).closest('.kata-plus-book-table-people').find('input').val() >= 10 && $(this).closest('.kata-plus-book-table-people').find('input').val() <= 99) {
                $(this).closest('.kata-plus-book-table-people').find('.people-title').css('left', '70px');
            } else if ($(this).closest('.kata-plus-book-table-people').find('input').val() >= 100) {
                $(this).closest('.kata-plus-book-table-people').find('.people-title').css('left', '80px');
            } else {
                $(this).closest('.kata-plus-book-table-people').find('.people-title').css('left', '60px');
            }
        });

        $('.kata-plus-book-table-people').find('.num-sub').on('click', function () {
            var inval = $(this).closest('.kata-plus-book-table-people').find('input').val();
            if (inval >= 1) {
                $(this).closest('.kata-plus-book-table-people').find('input').val(--inval);
                $(this).closest('.kata-plus-book-table-people').find('input').attr('value', inval);
            }
            if ($(this).closest('.kata-plus-book-table-people').find('input').val() >= 10 && $(this).closest('.kata-plus-book-table-people').find('input').val() <= 99) {
                $(this).closest('.kata-plus-book-table-people').find('.people-title').css('left', '70px');
            } else if ($(this).closest('.kata-plus-book-table-people').find('input').val() >= 100) {
                $(this).closest('.kata-plus-book-table-people').find('.people-title').css('left', '80px');
            } else {
                $(this).closest('.kata-plus-book-table-people').find('.people-title').css('left', '60px');
            }
        });

        // niceselect
        $('.kata-plus-book-table-time select').niceSelect();
        $('.kata-plus-book-table-time').find('.arrow').find('.kata-icon').on('click', function () {
            setTimeout(function () {
                var $this = $(this),
                    $wrap = $this.closest('.kata-plus-book-table-time'),
                    $select = $wrap.find('.nice-select');
                $select.trigger('click');
            }, 1);
        });
    };

    // Make sure you run this code under Elementor.
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/kata-plus-book-table.default', WidgetTeamMemberHandler);
    });
})(jQuery);