(function ($) {
    /**
     * @param $scope The Widget wrapper element as a jQuery element
     * @param $ The jQuery alias
     */
    var WidgetProgressBarHandler = function ($scope, $) {
        var scopeoffset = $scope.offset(),
            top = scopeoffset.top,
            half_window = ($(window).innerHeight() / 2) + $scope.innerHeight();
        if (half_window >= top) {
            if (!$scope.find('.bar-wrapper').hasClass('action')) {
                $scope.find('.bar-wrapper').addClass('action');
            }
        }

        $(window).on('scroll', function () {
            var $window = $(this);
            $scope.each(function (index, element) {
                var $this = $(this).find('.bar-wrapper'),
                    offset = $scope.offset(),
                    top = offset.top,
                    half_window = (($window.innerHeight() / 2) + $scope.innerHeight()) + $window.scrollTop();
                if (half_window >= top) {
                    if (!$this.hasClass('action')) {
                        $this.addClass('action');
                    }
                }
            });
        });

        $scope.find('.bar').each(function( index ) {
            $scope.find('.bar').css("width", function(){
                var $this = $(this);
                return $this.data('counter');
            })
        });

    };

    // Make sure you run this code under Elementor.
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/kata-plus-progress-bar.default', WidgetProgressBarHandler);
    });
})(jQuery);