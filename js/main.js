(function($) {
	$.fn.outputLeadTimes = function(productCode, options) {
		var defaultOptions = {
			"success": function( data, status ) {
				// ↓↓↓↓↓ここからHTML要素出力ロジック↓↓↓↓↓
				var table = $("<table />")
						.append(
							$("<caption />").text('出荷予定表('+data["データ出力日時"]+' 現在)')
						)
						.append(
							$("<colgroup />")
								.append($("<col />"))
								.append($("<col />"))
								.append($("<col />"))
						)
						.append(
							$("<thead />").append(
								$("<tr />")
									.append($("<th />").text('在庫状況'))
									.append($("<th />").text('在庫数'))
									.append($("<th />").text('発送日目安'))
							)
						);

				$(data["在庫状況別発送日目安"]).each(function( k, v ) {
					table
						.append(
							$("<tbody />").append(
								$("<tr />")
									.append($("<td />").text(v["在庫状況"]))
									.append($("<td />").text(v["在庫数"] == -1 ? '-' : (v["在庫数"] + data["単位"] + 'まで')))
									.append($("<td />").text(v["発送日目安"]))
							)
						);
				});

				$("#lead-time-table")
					.append(table);
				// ↑↑↑↑↑ここまでHTML要素出力ロジック↑↑↑↑↑

			},
			"error": function() {
				alert('欠品だ！');
			}
		};
		var setting = $.extend(defaultOptions, options);

		$(this).find('*').remove().end()

		$.ajax({
			url: "//friends-map.net/test/js/" + productCode + ".json?_=" + (new Date()),
			type: "GET",
			dataType: "json",
			"success":setting.success,
			"error":setting.error
		});

		return this;
	};
})(jQuery);
