

	function wpsm_media_upload(el){
	   
		showImg = jQuery(el).prev('img');
		uploadID = jQuery(el).next('input');
		teamID = jQuery(uploadID).next('input');
			
	   media_uploader = wp.media({
			frame:    "post", 
			state:    "insert", 
			library: { 
			  type: 'image' // limits the frame to show only images
		   },
			multiple: false
		});

		media_uploader.on("insert", function(){
			var json = media_uploader.state().get("selection").first().toJSON();
		
			
			var image_url = json.url;
			var id = json.id;
				
			
			//alert(image_url);
			 jQuery(uploadID).val(image_url);
			 jQuery(teamID).val(id);
			 showImg.attr('src',image_url);
			var image_caption = json.caption;
			var image_title = json.title;
		});

		media_uploader.open();
	}
