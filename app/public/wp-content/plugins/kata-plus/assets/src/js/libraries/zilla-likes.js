jQuery(function ($) {
    $(".zilla-likes").on("click", function (e) {
        e.preventDefault();
        var link = $(this);

        var id = $(this).attr("id");

        $.ajax({
            type: "POST",
            url: kata_plus_localize.ajax.url,
            data: {
                action: "zilla-likes",
                likes_id: id,
            },
            xhrFields: {
                withCredentials: true,
            },
            success: function (data) {
                link.html(data).toggleClass("active").attr("title", "You already like this");
            },
        });

        return false;
    });
});
