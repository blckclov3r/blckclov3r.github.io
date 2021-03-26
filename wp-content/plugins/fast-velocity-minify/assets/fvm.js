// get logs via ajax
function fvm_get_logs() {
		
	// ajax request
	jQuery( document ).ready(function() {
	var data = { 'action': 'fvm_get_logs' };
	jQuery.post(ajaxurl, data, function(resp) {
		if(resp.success == 'OK') { 
		
			// cache stats
			jQuery('.fvm-cache-stats').html("There are "+resp.stats_css.count+" CSS files and "+resp.stats_js.count+" JS files using a total of "+resp.stats_total.size+" on your cache directory");
			
			// css log
			jQuery('textarea.log-css').val(resp.css_log);
			jQuery('textarea.log-css').scrollTop(0);
						
			// js log
			jQuery('textarea.log-js').val(resp.js_log);
			jQuery('textarea.log-js').scrollTop(0);
				
		} else {
			// error log
			console.error(resp.success);	
		}
	});
	});
}


jQuery( document ).ready(function() {

	// help section
	jQuery( ".accordion" ).accordion({ active: false, collapsible: true, heightStyle: "content" });

});