(function ($) {
    /**
     * @param $scope The Widget wrapper element as a jQuery element
     * @param $ The jQuery alias
     */
    var WidgetSocialShareHandler = function ($scope, $) {
        $(".kata-social-share")
            .find("a")
            .on("click", function () {
                var $this = $(this),
                    $wrap = $this.parent(".kata-social-share"),
                    $post = $wrap.data("id");
                $.ajax({
                    url: kata_plus_localize.ajax.url,
                    type: "POST",
                    data: {
                        nonce: kata_plus_localize.ajax.nonce,
                        action: "post_share_count",
                        post_id: $post,
                    },
                    success(data) {
                        $wrap.find(".kata-post-share-count").find("span").text(data);
                    },
                });
            });
        $(".kata-social-share.kt-social-sticky").each(function (index, element) {
            var $this = $(this),
                offset = $this.offset(),
                top = offset.top,
                max = Math.round(document.querySelector(".kata-post-content").getBoundingClientRect().bottom);
            $(window).scroll(function () {
                if ($(window).scrollTop() >= top - 100 && $(window).scrollTop() <= max) {
                    if (!$this.hasClass("kt-social-sticky-active")) {
                        $this.addClass("kt-social-sticky-active");
                    }
                } else {
                    if ($this.hasClass("kt-social-sticky-active")) {
                        $this.removeClass("kt-social-sticky-active");
                    }
                }
                if ($(window).scrollTop() >= top && $(window).scrollTop() >= max) {
                    if ($this.hasClass("kt-social-sticky-active")) {
                        $this.removeClass("kt-social-sticky-active");
                    }
                }
            });
        });
    };

    // Make sure you run this code under Elementor.
    $(window).on("elementor/frontend/init", function () {
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-social-share.default",
            WidgetSocialShareHandler
        );
    });
})(jQuery);
