jQuery(function ($) {
	$('#wp-admin-bar-purge_minify_cache > a').on('click', function(e) {
		e.preventDefault();
		$(e.currentTarget).addClass('loading');
		wp_optimize.send_command('purge_minify_cache', null, function(response) {
			if (response.hasOwnProperty('error')) {
				alert(response.error);
			}
			if (response.hasOwnProperty('message')) {
				var m = JSON.parse(response.message);
				if ('object' === typeof m) {
					message = m.join('\n');
				} else {
					message = m;
				}
				alert(message);
				if (response.hasOwnProperty('files') && response.files.cachesize) {
					$('#wp-admin-bar-wpo_minify_cache_stats .stats').text(response.files.cachesize);
				}
			}
		}).always(function() {
			$(e.currentTarget).removeClass('loading');
			$.unblockUI();
		});
	})
});
