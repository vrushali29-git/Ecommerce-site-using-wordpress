(function ($) {
    /**
     * @param $scope The Widget wrapper element as a jQuery element
     * @param $ The jQuery alias
     */
    var WidgetPostCommentsHandler = function ($scope, $) {

        $('.comment-form').find('input:not([type="checkbox"]), textarea').each(function (index, element) {
            var $this = $(this),
                $parent = $this.parent('p');
            if ($this.val()) {
                $parent.find('label').fadeOut();
                $parent.find('.required').fadeOut();
            }
        });

        $('.comment-form').find('input:not([type="checkbox"]), textarea').on('focusin', function () {
            var $this = $(this),
                $parent = $this.parent('p'),
                $form = $this.parents('.comment-form');
            $parent.find('label').fadeOut();
            $parent.find('.required').fadeOut();
        });
        $('.comment-form').find('input:not([type="checkbox"]), textarea').on('focusout', function () {
            var $this = $(this),
                $parent = $this.parent('p'),
                $form = $this.parents('.comment-form');
            if (!$this.val()) {
                $parent.find('label').fadeIn();
                $parent.find('.required').fadeIn();
            }
        });
        $('.comment-form').on('submit', function (e) {
            var $form = $(this),
                $author = $form.find('[id="author"]'),
                $email = $form.find('[id="email"]'),
                $textarea = $form.find('textarea'),
                regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;

            $author.removeAttr('style');
            $email.removeAttr('style');
            $textarea.removeAttr('style');

            if ($author.length) {
                if ($author.val() == '') {
                    $author.css('border-color', 'red');
                    e.preventDefault();
                }
            }
            if ($textarea.length) {
                if ($textarea.val() == '') {
                    $textarea.css('border-color', 'red');
                    e.preventDefault();
                }
            }
            if ($email.length) {
                if ($email.val() == '') {
                    $email.css('border-color', 'red');
                    e.preventDefault();
                }
                if (regex.test($email.val()) == false) {
                    e.preventDefault();
                    $email.css('border-color', 'red');
                }
            }
        });
    };

    // Make sure you run this code under Elementor.
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/kata-plus-post-comments.default', WidgetPostCommentsHandler);
    });
})(jQuery);