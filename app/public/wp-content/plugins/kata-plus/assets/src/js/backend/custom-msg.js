"use strict";
(function ($) {
    $(document).ready(function () {
        // close notification
        $(".kata-notification-close").on("click", function (e) {
            e.preventDefault();
            $.ajax({
                url: kata_plus_admin_localize.ajax.url,
                type: "POST",
                data: {
                    action: "close_cmsg_notification",
                    nonce: kata_plus_admin_localize.ajax.nonce
                },
                success: function (response) {
                    $(".kata-admin-notification").fadeOut(
                        100,
                        function () {
                            $(this).remove();
                        }
                    );
                }
            });
        });
    });
})(jQuery);