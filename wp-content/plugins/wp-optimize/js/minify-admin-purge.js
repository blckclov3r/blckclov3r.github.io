jQuery(document).ready(function ($) {
	$('#wp-admin-bar-purge_minify_cache').click(function(e) {
		e.preventDefault();
		wp_optimize.send_command('purge_minify_cache', null, function(response) {
			console.log(response)
			if (response.hasOwnProperty('error')) {
				alert(response.error);
			}
			if (response.hasOwnProperty('message')) {
				alert(response.notice);
			}
		}).always(function() {
			$.unblockUI();
		});
	})
});
