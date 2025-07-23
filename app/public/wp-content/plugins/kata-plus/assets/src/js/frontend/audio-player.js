(function ($) {
    /**
     * @param $scope The Widget wrapper element as a jQuery element
     * @param $ The jQuery alias
     */
    var WidgetAudioPlayerHandler = function ($scope, $) {
        $scope.find(".kata-plus-track-wrapper").each(function (index, element) {
            var $this = $(this),
                $controls = $this.find(".kata-plus-track-controls"),
                $player = $this.find(".mejs-mediaelement").find(".wp-audio-shortcode");
            $this.find("audio").mediaelementplayer();
            setTimeout( function() {
                $('.mejs-playpause-button').on('click', function () {
                    var $this = $(this),
                        $wrap = $this.closest('.kata-plus-track-wrapper'),
                        status = $this.find('button').attr('title');
                    if( status === 'Play' ) {
                        $wrap.addClass('playing-audio').siblings().removeClass('playing-audio');
                    } else {
                        $wrap.removeClass('playing-audio');
                    }
                });
            }, 1000);
        });
    };

    // Make sure you run this code under Elementor.
    $(window).on("elementor/frontend/init", function () {
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-audio-player.default",
            WidgetAudioPlayerHandler
        );
    });
})(jQuery);
