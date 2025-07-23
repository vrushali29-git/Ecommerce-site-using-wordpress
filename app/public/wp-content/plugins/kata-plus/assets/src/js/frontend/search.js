(function ($) {
    /**
     * @param $scope The Widget wrapper element as a jQuery element
     * @param $ The jQuery alias
     */
    var WidgetSearchHandler = function ($scope, $) {
        $scope.find(".kata-plus-search-ajax-result-wrap").hide();
        var timer;
        $('.kt-search-term').on('change', function() {
            $(this).find('option[value="'+this.value+'"]').attr('selected', 'selected').siblings().removeAttr('selected');
            $(this).closest('.kata-plus-live-search').find('.search-field').trigger('input');
        });
        $(".kata-plus-live-search").find(".search-field").on("input", function (e) {
            clearTimeout(timer);
            timer = setTimeout(() => {
                var $this = $(this),
                    $keyword = $(this).val(),
                    posttype = $(this).attr("data-posttype"),
                    taxonomy = $(this).attr("data-taxonomy"),
                    term_id = $('.kt-search-term').find('option[selected="selected"]').attr('value');
                $.ajax({
                    url: kata_plus_localize.ajax.url,
                    type: "POST",
                    data: {
                        action: "live_search",
                        nonce: kata_plus_localize.ajax.nonce,
                        keyword: $keyword,
                        posttype: posttype,
                        taxonomy: taxonomy,
                        term_id: term_id,
                    },
                    success: function (data) {
                        if ($keyword == "") {
                            $(".kata-plus-search-ajax-result-wrap").empty().slideUp();
                        } else {
                            $(".kata-plus-search-ajax-result-wrap").html(data).slideDown();
                        }
                    },
                });
            }, 300);
        });
        // Search Modal
        $scope.find(".kt-toggle-wrapper").on("click", function (e) {
            e.preventDefault();
            var $this = $(this),
                $wrap = $this.closest(".kt-search-open-as"),
                $input = $wrap.find(".search-field"),
                $element_1 = $wrap.find(".kt-search-wrapper"),
                $element_2 = $wrap.find(".kt-search-overlay"),
                $element_3 = $wrap.find(".kt-close-overlay"),
                $close = $wrap.find(".kt-close-overlay");

            if ($wrap.hasClass("active")) {
                $wrap.removeClass("active");
                $element_1.fadeOut();
                $element_2.fadeOut();
                $element_3.hide();
            } else {
                $wrap.addClass("active");
                $element_1.fadeIn();
                $element_2.fadeIn();
                $element_3.show();
            }
            $input.focus();
        });

        $(document).on("click", ".kt-search-open-as .kt-close-search-modal", function (e) {
            e.preventDefault();
            var $this = $(this),
                $wrap = $this.closest(".kt-search-open-as"),
                $element_1 = $wrap.find(".kt-search-wrapper"),
                $element_2 = $wrap.find(".kt-search-overlay"),
                $element_3 = $wrap.find(".kt-close-overlay");
            if ($wrap.hasClass("active")) {
                $wrap.removeClass("active");
                $element_1.fadeOut();
                $element_2.fadeOut();
                $element_3.hide();
            }
        });
        // button icon clickabel
        $(".kata-search-icon").find('.kata-icon').on("click", function (e) {
            $(this).closest('.kata-search-icon').find('input[type="submit"]').trigger('click');
        });

        $(document).keydown(function (e) {
            var code = e.keyCode || e.which;
            if (code == 27) $(".kt-close-search-modal").trigger("click");
        });
    };
    // Make sure you run this code under Elementor.
    $(window).on("elementor/frontend/init", function () {
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/kata-plus-search.default",
            WidgetSearchHandler
        );
    });
})(jQuery);
