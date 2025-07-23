(function ($) {
	const notice = $(".kata-notice");
	const closeBtn = notice.find(".notice-dismiss");
	const migrateBtn = notice.find(".kata-notice-btn");

	closeBtn.on("click", function () {
		notice.remove();

		var data = {
			action: "styler_migrate_rm_notice",
			nonce: kataConverterNotice.nonce,
		};

		$.ajax({
			type: "POST",
			url: kataConverterNotice.ajax_url,
			data: data,
			success: function (response) {
				console.log(response);
			},
			error: function (err) {
				console.log(err);
			},
		});
	});

	migrateBtn.on("click", function (e) {
		e.preventDefault();

		var data = {
			action: "styler_migrate_date",
			nonce: kataConverterNotice.nonce,
		};

		$.ajax({
			type: "POST",
			url: kataConverterNotice.ajax_url,
			data: data,
			success: function (response) {
				console.log(response);
			},
			error: function (err) {
				console.log(err);
			},
		});
	});
})(jQuery);
