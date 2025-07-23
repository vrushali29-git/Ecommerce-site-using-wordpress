(function ($) {
    /**
     * @param $scope The Widget wrapper element as a jQuery element
     * @param $ The jQuery alias
     */
    var WidgetContactFormHandler = function ($scope, $) {

        // wrapper for textarea
        $scope.find('.wpcf7-form-control-wrap').find('textarea').parent('.wpcf7-form-control-wrap').addClass('textarea-wrapper');
        $scope.find('.wpcf7-form-control-wrap').children('select.wpcf7-select:not([multiple="multiple"])').niceSelect();
        $('.kata-plus-contact-field').find('label').on('click', function () {
            $(this).closest('.kata-plus-contact-field').find('input , textarea , select').focus();
        });

        $('.kata-plus-contact-field').find('input , textarea , select').on('focusout', function () {
            var $this = $(this),
                value = $this.val();
            if (value == '') {
                $(this).closest('.kata-plus-contact-field').removeClass('active');
            }
        });

        $('.kata-plus-contact-field').find('input , textarea , select').on('focusin', function () {
            var $this = $(this),
                value = $this.val();
            $this.closest('.kata-plus-contact-field').addClass('active');
        });
    };

    // Make sure you run this code under Elementor.
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/kata-plus-contact-form.default', WidgetContactFormHandler);
    });
})(jQuery);