(function ($) {
	$(document).ready(function () {
		$("#rollback-version").select2();
	});

	const warpper = $(".kata-select-version");
	const icon = warpper.find("div");
	const btn = warpper.find("button");
	const version = $("#rollback-version");

	btn.on("click", function () {
		icon.addClass("kt-btn-loading");

		var data = {
			action: "kata_rollback",
			nonce: kataRollback.nonce,
			version: version.val(),
			slug: version.find('option:selected').attr('data-slug'),
		};

		$.ajax({
			type: "POST",
			url: kataRollback.ajax_url,
			data: data,
			success: function (response) {
				console.log(response);
				icon.removeClass("kt-btn-loading");
			},
			error: function (err) {
				alert( err.responseJSON );
				icon.removeClass("kt-btn-loading");
			},
		});
	});
})(jQuery);
