(function ($) {
    /**
     * @param $scope The Widget wrapper element as a jQuery element
     * @param $ The jQuery alias
     */
    var WidgetTemplateLoaderHandler = function ($scope, $) {
        if ( $('.elementor-editor-active').length ) {
            $scope.find('.elementor[data-elementor-type][data-elementor-settings]').each(function (index, element) {
                var $template = $(this);
                $template.append('<div class="kata-full-site-edit"><i class="eicon-edit"></i><span>Edit</span></div>');
            });
        }
    };

    // Make sure you run this code under Elementor.
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/kata-plus-template-loader.default', WidgetTemplateLoaderHandler);
    });
})(jQuery);