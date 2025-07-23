/**
 * Dialog.
 *
 * @author  ClimaxThemes
 * @package	Kata Plus
 * @since	1.0.0
 */
'use strict';

var Kata_Plus_Dialog = (function ($) {
    /**
     * Global variables.
     *
     * @since	1.0.0
     */
    var $window = $(window);
    var runOnceTime = false;

    return {
        /**
         * Init.
         *
         * @since	1.0.0
         */
        init: function () {
            if (runOnceTime == false) {
                runOnceTime = true;
                this.dialog();
            }
        },

        /**
         * Dialog.
         *
         * @since	1.0.0
         */
        dialog: function () {
            var $dialog = $('.kata-dialog');
            // Open dialog
            $('.kata-dialog-open-btn').off().on('click', function () {
                $dialog.show();
            });
            // Close dialog
            $dialog.find('.kata-dialog-close-btn').off().on('click', function () {
                $dialog.hide();
            });
        },

        /**
         * Get Query String Value.
         *
         * @since	1.0.0
         */
        getQueryStringValue: function (key) {
            return decodeURIComponent(window.location.search.replace(new RegExp("^(?:.*[&\\?]" + encodeURIComponent(key).replace(/[\.\+\*]/g, "\\$&") + "(?:\\=([^&]*))?)?.*$", "i"), "$1"));
        },
    };
})(jQuery);

// Check Backend or Frontend
if (Kata_Plus_Dialog.getQueryStringValue('elementor-preview')) {
    // Elementor preview
    jQuery(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/global', function ($scope) {
            Kata_Plus_Dialog.init(true);
        });
    });
} else {
    // Frontend
    jQuery(document).ready(function () {
        Kata_Plus_Dialog.init();
    });
}