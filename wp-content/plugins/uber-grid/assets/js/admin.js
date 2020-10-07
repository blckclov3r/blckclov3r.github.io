"use strict";
var sidebarNameChange = function (e) {
    document.getElementById("name").value = e.value;
}
var portfoliosListNameChange = function (e) {
    document.getElementById("pfhub_portfolio_name").value = e.value;
};
jQuery(document).ready(function () {

	jQuery('#lightbox_type input').change(function () {
		jQuery('#lightbox_type input').parent().removeClass('active');
		jQuery(this).parent().addClass('active');
		if(jQuery(this).val() == 'classic'){
			jQuery('#lightbox-options-list').addClass('active');
			jQuery('#new-lightbox-options-list').removeClass('active');
		}
		else{
			jQuery('#lightbox-options-list').removeClass('active');
			jQuery('#new-lightbox-options-list').addClass('active');
		}
		jQuery('#lightbox_type input').prop('checked',false);
		if(!jQuery(this).prop('checked')){
			jQuery(this).prop('checked',true);
		}
	});
	var setTimeoutConst;
	jQuery('body').on('mouseenter','ul#images-list > li img',function () {
		var onHoverPreview = jQuery('#img_hover_preview').prop('checked');
		if(onHoverPreview == true) {
			var imgSrc = jQuery(this).attr('data-img-src');
			jQuery('#gallery-image-zoom img').attr('src', imgSrc);
			setTimeoutConst = setTimeout(function () {
				jQuery('#gallery-image-zoom').fadeIn('3000');
			}, 700);
		}
	});
	jQuery('body').on('mouseout','ul#images-list > li img',function () {
		clearTimeout(setTimeoutConst);
		jQuery('#gallery-image-zoom').fadeOut('3000');
	});
	jQuery('a.set-new-video').click(function (e) {
		e.preventDefault();
		var videoUrl = jQuery('#pfhub_portfolio_edit_video_input').val();
		if (videoUrl == "") {
			alert("Please copy and past url from Youtube or Vimeo to insert into slider.");
			return false;
		}
		if (pfhubPortfolioYoutubeParser(videoUrl) == false) {
			alert("Url is incorrect");
			return false;
		}
		var videoEditNonce = jQuery(this).parents('#portfolio-gallery-edit-video-wrapper').attr('data-see-video-nonce');
		var videoUrl = jQuery('input#pfhub_portfolio_edit_video_input').val();
		var data = {
			videoEditNonce: videoEditNonce,
			videoUrl: videoUrl,
			task: 'see_new_video',
			action: 'pfhub_portfolio_action'
		};
		jQuery.post(ajaxUrl, data, function (response) {
			response = JSON.parse(response);
			jQuery('#portfolio-gallery-edit-video-wrapper .iframe-area').attr('src',response);
			jQuery('#portfolio-gallery-edit-video-wrapper .text-area').html(videoUrl);
		});
	});
	jQuery('.pfhub_portfolio-insert-edited-video-button').click(function (e) {
		e.preventDefault();
		var videoUrl = jQuery('#pfhub_portfolio_edit_video_input').val();
		if (videoUrl == "") {
			alert("Please copy and past url from Youtube or Vimeo to insert into slider.");
			return false;
		}
		if (pfhubPortfolioYoutubeParser(videoUrl) == false) {
			alert("Url is incorrect");
			return false;
		}
		var portfolioId = jQuery(this).parents('#portfolio-gallery-edit-video-wrapper').attr('data-portfolio-id');
		var portfolioItemId = jQuery(this).parents('#portfolio-gallery-edit-video-wrapper').attr('data-portfolio-item-id');
		var videoIndexInArray = jQuery(this).parents('#portfolio-gallery-edit-video-wrapper').attr('data-video-index');
		var videoEditNonce = jQuery(this).parents('#portfolio-gallery-edit-video-wrapper').attr('data-edit-video-nonce');
		var videoEditSafeLink = 'admin.php?page=portfolios_pfhub_portfolio&task=portfolio_video_edit&grid_id='+portfolioId+'&id='+portfolioItemId+'&thumb='+videoIndexInArray+'&portfolio_video_edit_nonce='+videoEditNonce+'&TB_iframe=1&closepop=1';
		jQuery(this).parents('form').attr('action',videoEditSafeLink).submit();
	});
	jQuery('a.edit-video-button').on('click',function () {
		var portfolioItemId = jQuery(this).parents('li.portfolio-item').attr('data-portfolio-item-id');
		var portfolioId = jQuery(this).parents('ul#images-list').attr('data-portfolio-gallery-id');
		var iframeSrc = jQuery(this).parents('li.editthisvideo').attr('data-iframe-src');
		var videoIndexInArray = jQuery(this).parents('li.editthisvideo ').attr('data-video-index');
		var videoSrc = jQuery(this).parent().find('img.editthisvideo').attr('data-video-src');
		var editThumbVideoNonce = jQuery(this).attr('data-edit-thumb-video');
		jQuery('#portfolio-gallery-edit-video-wrapper .iframe-area').attr('src',iframeSrc);
		jQuery('#portfolio-gallery-edit-video-wrapper textarea.text-area').text(videoSrc);
		jQuery('#portfolio-gallery-edit-video-wrapper').attr('data-video-index',videoIndexInArray);
		jQuery('#portfolio-gallery-edit-video-wrapper').attr('data-portfolio-id',portfolioId);
		jQuery('#portfolio-gallery-edit-video-wrapper').attr('data-portfolio-item-id',portfolioItemId);
		jQuery('#portfolio-gallery-edit-video-wrapper').attr('data-edit-video-nonce',editThumbVideoNonce);
	});
	jQuery('.pfhub_portfolio-insert-thumb-video-button').click(function (e) {
		e.preventDefault();
		var videoUrl = jQuery('#pfhub_portfolio_add_video_input_thumb').val();
		if (videoUrl == "") {
			alert("Please copy and past url from Youtube or Vimeo to insert into slider.");
			return false;
		}
		if (pfhubPortfolioYoutubeParser(videoUrl) == false) {
			alert("Url is incorrect");
			return false;
		}
		var portfolioItemId = jQuery(this).parent().attr('data-portfolio-item-id');
		var addThumbVideoNonce = jQuery('#pfhub_portfolio_add_videos_wrap').attr('data-add-thumb-video-nonce');
		var data = {
			addThumbVideoNonce: addThumbVideoNonce,
			videoUrl: videoUrl,
			task: 'add_thumb_video',
			action: 'pfhub_portfolio_action',
			portfolioItemId: portfolioItemId
		};
		jQuery.post(ajaxUrl, data, function (response) {
			response = JSON.parse(response);
			var projectIndex = jQuery('li[data-portfolio-item-id="'+portfolioItemId+'"]').index();
			var videoIndex = jQuery('li[data-portfolio-item-id="'+portfolioItemId+'"]').find('.image-container li').length;
			var allUrls = jQuery('li[data-portfolio-item-id="'+portfolioItemId+'"]').find('input.all-urls').val();
			var iframeVideoSrc = response.iframe_video_src;
			allUrls += videoUrl+';';
			var imageUrl = response.image_url;
			var videoType = response.video_type;
			jQuery('li[data-portfolio-item-id="'+portfolioItemId+'"]').find('input.all-urls').val(allUrls);
			var videoContainer = '<li class="editthisvideo editthisimage'+projectIndex+' ui-sortable-handle" data-video-index="'+videoIndex+'" data-iframe-src="'+iframeVideoSrc+'"> \
                <img class="editthisvideo" src="'+imageUrl+'" data-video-src="'+iframeVideoSrc+'" alt="'+iframeVideoSrc+'"> \
                <div class="play-icon  '+videoType+'-icon"></div> \
                <a class="thickbox edit-video-button" data-edit-thumb-video="" href="#TB_inline?width=700&amp;inlineId=portfolio-gallery-edit-video&amp;width=753&amp;height=396"> \
                <input type="button" class="edit-video" id="edit-video_11_0" value="Edit"> \
                </a> \
                <a href="#remove" title="2" class="remove-image">remove</a> \
                </li>';
			jQuery('.tb-close-icon').click();
			jQuery('li[data-portfolio-item-id="'+portfolioItemId+'"]').find('li.add-image-box').before(videoContainer);
		});
	});
	jQuery('.pfhub_portfolio-insert-video-button').click(function (e) {
		e.preventDefault();
		var videoUrl = jQuery('#pfhub_portfolio_add_video_input').val();
		if (videoUrl == "") {
			alert("Please copy and past url from Youtube or Vimeo to insert into slider.");
			return false;
		}
		if (pfhubPortfolioYoutubeParser(videoUrl) == false) {
			alert("Url is incorrect");
			return false;
		}
		var portfolioId = jQuery(this).parents('#pfhub_portfolio_add_videos_wrap').attr('data-portfolio-gallery-id');
		var portfolioVideoAddNonce = jQuery(this).parents('#pfhub_portfolio_add_videos_wrap').attr('data-add-video-nonce');
		jQuery(this).parent().attr('action', 'admin.php?page=portfolios_pfhub_portfolio&task=portfolio_video&id=' + portfolioId + '&portfolio_add_video_nonce=' + portfolioVideoAddNonce + '&closepop=1').submit();
	});
	jQuery('#pfhub_portfolio_add_video_input').change(function () {
		if (jQuery(this).val().indexOf("youtube") >= 0) {
			jQuery('#add-video-popup-options > div').removeClass('active');
			jQuery('#add-video-popup-options  .youtube').addClass('active');
		} else if (jQuery(this).val().indexOf("vimeo") >= 0) {
			jQuery('#add-video-popup-options > div').removeClass('active');
			jQuery('#add-video-popup-options  .vimeo').addClass('active');
		} else {
			jQuery('#add-video-popup-options > div').removeClass('active');
			jQuery('#add-video-popup-options  .error-message').addClass('active');
		}
	});
	jQuery('.add-image-video .add-video-slide').on('click', function () {
		jQuery('form.add-main-video').hide();
		jQuery('form.add-thumb-video').show();
		var portfolioItemId = jQuery(this).parents('li.portfolio-item').attr('data-portfolio-item-id');
		var portfolioId = jQuery(this).parents('ul#images-list').attr('data-portfolio-gallery-id');
		var portfolioVideoAddNonce = jQuery(this).attr('data-add-thumb-video-nonce');
		jQuery('form.add-thumb-video').attr('data-portfolio-item-id', portfolioItemId);
		jQuery('#pfhub_portfolio_add_videos_wrap').attr('data-add-thumb-video-nonce',portfolioVideoAddNonce);
		jQuery('#pfhub_portfolio_add_videos_wrap').attr('data-portfolio-gallery-id', portfolioId);
	});
	jQuery('.pfhub_portfolio_button.add-video-slide').on('click', function () {
		jQuery('form.add-main-video').show();
		jQuery('form.add-thumb-video').hide();
		var portfolioId = jQuery(this).attr('data-portfolio-gallery-id');
        var addVideoNonce = jQuery(this).attr('data-add-video-nonce');
		jQuery('#pfhub_portfolio_add_videos_wrap').attr('data-portfolio-gallery-id', portfolioId);
		jQuery('#pfhub_portfolio_add_videos_wrap').attr('data-add-video-nonce', addVideoNonce);
	});
	jQuery(document).on('click', '#portfolios-list .active', function () {
		jQuery(this).find('input').focus();
	});
	jQuery('#arrows-type input[name="params[portfolio_navigation_type]"]').change(function(){
		jQuery(this).parents('ul').find('li.active').removeClass('active');
		jQuery(this).parents('li').addClass('active');
	});
	jQuery('#portfolio-view-tabs > li > a').click(function(){
		jQuery('#portfolio-view-tabs > li').removeClass('active');
		jQuery(this).parent().addClass('active');
		jQuery('#portfolio-view-tabs-contents > li').removeClass('active');
		var liID=jQuery(this).attr('href');
		jQuery(liID).addClass('active');
		jQuery('#adminForm').attr('action',"admin.php?page=Options_portfolio_styles&task=save"+liID);
		return false;
	});
	jQuery(".close_free_banner").on("click",function(){
		jQuery(".free_version_banner").css("display","none");
		pfhubPortfolioSetCookie( 'portfolioGalleryBannerShow', 'no', {expires:86400} );
	});
	jQuery(".christmas-close").on("click",function(e){
		e.preventDefault();
		jQuery(".backend-christmas-banner").css("display","none");
		pfhubPortfolioSetCookie( 'portfolioGalleryChristmasBannerShow', 'no', {expires:3456000} );
	});
	jQuery(window).load(function(){
        if(pfhubPortfolioGetCookie('deleted')){
            pfhubPortfolioSetCookie( 'deleted', 'success', {expires:-1} );
        }
	});
    jQuery('#portfolio-loading-icon li').click(function(){
		jQuery(this).parents('ul').find('li.act').removeClass('act');
		jQuery(this).addClass('act');
	});	
        
        jQuery('input#show_loading').change(function(){
            if(jQuery(this).prop('checked') == false){
                jQuery('li.loading_opton').hide();
            }
            else{
                jQuery('li.loading_opton').show();
            }
        });
        jQuery('input#show_loading').change();

	jQuery('table.wp-list-table a[href*="remove_portfolio"]').click(function(){
		if(!confirm("Are you sure you want to delete this item?"))
			return false;
	});
	jQuery("ul.widget-images-list").on('click', '.remove-image', function () {
		jQuery(this).parents("#images-list > li").addClass('submit-post');
		jQuery(this).parent().find('img').remove();
		var allUrls = "";
		var $src;
		jQuery(this).parents('ul.widget-images-list').find('img').not('.plus').each(function () {
			if (jQuery(this).hasClass('editthisvideo')) {
				$src = jQuery(this).attr('data-video-src');
			}
			else $src = jQuery(this).attr('data-img-src');
			allUrls = allUrls + $src + ';';
			jQuery(this).parent().parent().parent().find('input.all-urls').val(allUrls);
		});
		if(!jQuery(this).parents('ul.widget-images-list').find('img').not('.plus').length){
			jQuery(this).parent().parent().parent().find('input.all-urls').val('');
		}
		jQuery(this).parent().remove();
		return false;
	});
	jQuery('.add-image-slide .add-image').click(function (e) {
		jQuery(this).parents("#images-list > li").addClass('submit-post');
		var button = jQuery(this);
		var id = button.attr('id').replace('_button', '');
		var _custom_media = true;
		wp.media.editor.send.attachment = function (props, attachment) {
			if (_custom_media) {
				jQuery("#" + id).parent().parent().before('<li class="editthisimage1 "><img src="' + attachment.url + '" data-img-src="' + attachment.url + '" alt="" /><input type="button" class="edit-image"  id="" value="Edit" /><a href="#remove" class="remove-image">remove</a></li>');
				jQuery("#" + id).val(jQuery("#" + id).val() + attachment.url + ';');
			} else {
				return _orig_send_attachment.apply(this, [props, attachment]);
			}
		}
		wp.media.editor.open(button);
		return false;
	});
	jQuery('.widget-images-list').on('click', '.edit-image', function (e) {
		jQuery(this).parents("#images-list > li").addClass('submit-post');
		var send_attachment_bkp = wp.media.editor.send.attachment;
		var $src;
		var button = jQuery(this);
		var id = button.parents('.widget-images-list').find('.all-urls').attr('id');
		var img = button.prev('img');
		var _custom_media = true;
		jQuery(".media-menu .media-menu-item").css("display", "none");
		jQuery(".media-menu-item:first").css("display", "block");
		jQuery(".separator").next().css("display", "none");
		jQuery('.attachment-filters').val('image').trigger('change');
		jQuery(".attachment-filters").css("display", "none");
		wp.media.editor.send.attachment = function (props, attachment) {
			if (_custom_media) {
				img.attr('data-img-src', attachment.url);
				img.attr('src', attachment.url);
				var allurls = '';
				img.parents('.widget-images-list').find('img').not('.plus').each(function () {
					if (jQuery(this).hasClass('editthisvideo')) {
						$src = jQuery(this).attr('data-video-src');
					}
					else $src = jQuery(this).attr('data-img-src');
					allurls = allurls + $src + ';';
				});
				jQuery("#" + id).val(allurls);
			} else {
				return _orig_send_attachment.apply(this, [props, attachment]);
			}
		}
		wp.media.editor.open(button);
		return false;
	});
    var custom_uploader;
    jQuery('.pfhub_portfolio-newuploader .pfhub_portfolio_button').click(function(e) {
        e.preventDefault();
        var button = jQuery(this);
        var id = button.attr('id').replace('_button', '');
        //If the uploader object has already been created, reopen the dialog
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }
        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose file',
            button: {
                text: 'Choose file'
            },
            multiple: true
        });
        var attachments;
        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function() {
            attachments = custom_uploader.state().get('selection').toJSON();
            for(var key in attachments){
                jQuery("#"+id).val(attachments[key].url+';;;'+jQuery("#"+id).val());
            }
            jQuery("#save-buttom").click();
        });
        custom_uploader.open();
    });

	jQuery(".wp-media-buttons-icon").click(function () {
		jQuery(".media-menu .media-menu-item").css("display", "none");
		jQuery(".media-menu-item:first").css("display", "block");
		jQuery(".separator").next().css("display", "none");
		jQuery('.attachment-filters').val('image').trigger('change');
        jQuery(".attachment-filters").css("display", "block");
        jQuery("select#media-attachment-date-filters").val("all");
	});
	jQuery('.widget-images-list .add-image-box').hover(function () {
		jQuery(this).find('.add-thumb-project').css('display', 'none');
		jQuery(this).find('.add-image-video').css('display', 'block');
	}, function () {
		jQuery(this).find('.add-image-video').css('display', 'none');
		jQuery(this).find('.add-thumb-project').css('display', '');
	});
	jQuery('#portfolio_effects_list').on('change', function () {
		var sel = jQuery(this).val();
		if (sel == 5) {
			jQuery('.for-content-slider').css('display', 'block');
			jQuery('.no-content-slider').css('display', 'none');
			jQuery('ul.for_loading').parent().css('display', 'none');
			jQuery('.inside2').parents('.postbox').css('display', 'none');
		}
		else if (sel == 3) {
			jQuery('.no-content-slider').css('display', 'none');
		}
		else if(sel == 7){
			jQuery('.postbox .allowIsotope:first').css('display', 'none');
		}
		else {
			jQuery('.inside2').parents('.postbox').css('display', 'block');
			jQuery('.postbox .allowIsotope:first').css('display', 'block');
			jQuery('.for-content-slider').css('display', 'none');
			jQuery('.no-content-slider').css('display', 'block');
			jQuery('ul.for_loading').parent().css('display', 'block');
		}
	});
	jQuery('#portfolio_effects_list').change();
	jQuery("#images-list > li input").on('keyup', function () {
		jQuery(this).parents("#images-list > li").addClass('submit-post');
	});
	jQuery("#images-list > li textarea").on('keyup', function () {
		jQuery(this).parents("#images-list > li").addClass('submit-post');
	});
	jQuery("#images-list > li input").on('change', function () {
		jQuery(this).parents("#images-list > li").addClass('submit-post');
	});
	jQuery("#images-list > li select").on('change', function () {
		jQuery(this).parents("#images-list > li").addClass('submit-post');
	});
	jQuery('.add-thumb-project').on('hover', function () {
		jQuery(this).parent().parents("li").addClass('submit-post');
	})

	jQuery("#images-list").sortable({
			start: function(event, ui) {
				ui.item.data('start_pos', ui.item.index());
			},
		stop: function (event, ui) {
			jQuery("#images-list > li").removeClass('has-background');
			var count = jQuery("#images-list > li").length;
			for (var i = 0; i <= count; i += 2) {
				jQuery("#images-list > li").eq(i).addClass("has-background");
			}
			jQuery("#images-list > li").each(function () {
				jQuery(this).find('.order_by').val(jQuery(this).index());
			});

			var start = Math.min(ui.item.data('start_pos'),ui.item.index());
			var end = Math.max(ui.item.data('start_pos'),ui.item.index());
			for(var i1=start; i1<=end; i1++){
				jQuery(document.querySelectorAll("#images-list > li")[i1]).addClass('highlights');
			}
		},
		change: function (event, ui) {
			var start_pos = ui.item.data('start_pos');
			var index = ui.placeholder.index();
			if (start_pos < index + 2) {
				jQuery('#images-list > li:nth-child(' + index + ')').addClass('highlights');
			} else {
				jQuery('#images-list > li:eq(' + (index + 1) + ')').addClass('highlights');
			}
		},
		update: function (event, ui) {
			jQuery('#sortable li').removeClass('highlights');
		},
		revert: true
	});
	jQuery(".widget-images-list").sortable({
		stop: function () {
			jQuery(".widget-images-list > li").each(function () {
				jQuery(this).removeClass('first');
				jQuery(".widget-images-list > li").first().addClass('first');
			});
			pfhubPortfolioReplaceAddImageBox();
		},
		change: function (event, ui) {
			jQuery(this).parents('li').addClass('submit-post');
			var start_pos = ui.item.data('start_pos');
			var index = ui.placeholder.index();
			if (start_pos < index) {
				jQuery('.widget-images-list > li:nth-child(' + index + ')').addClass('highlights');

			} else {
				jQuery('widget-images-list > li:eq(' + (index + 1) + ')').addClass('highlights');
			}
		},
		update: function (event, ui) {
			jQuery('#sortable li').removeClass('highlights');
		},
		revert: true
	});
});

/* Cookies */

function pfhubPortfolioSetCookie(name, value, options) {
	options = options || {};

	var expires = options.expires;

	if (typeof expires == "number" && expires) {
		var d = new Date();
		d.setTime(d.getTime() + expires * 1000);
		expires = options.expires = d;
	}
	if (expires && expires.toUTCString) {
		options.expires = expires.toUTCString();
	}


	if(typeof value == "object"){
		value = JSON.stringify(value);
	}
	value = encodeURIComponent(value);
	var updatedCookie = name + "=" + value;

	for (var propName in options) {
		updatedCookie += "; " + propName;
		var propValue = options[propName];
		if (propValue !== true) {
			updatedCookie += "=" + propValue;
		}
	}

	document.cookie = updatedCookie;
}

function pfhubPortfolioGetCookie (name) {
    var cookie, allcookie = document.cookie.split(';');
    for (var i = 0; i < allcookie.length; i++) {
        cookie = allcookie[i].split('=');
        cookie[0] = cookie[0].replace(/ +/g,'');
        if (cookie[0] == name) {
            return decodeURIComponent(cookie[1]);
        }
    }
    return false;
}

function pfhubPortfolioSubmitButton(pressbutton) {
	if (!document.getElementById('name').value) {
		alert("Name is required.");
		return;

	}
	pfhubPortfolioFilterInputs();
	document.getElementById("adminForm").action = document.getElementById("adminForm").action + "&task=" + pressbutton;
	document.getElementById("adminForm").submit();
}
function pfhubPortfolioReplaceAddImageBox() {
	jQuery(".widget-images-list").each(function () {
		var src = "";

		if (!jQuery(this).find('li').last().hasClass('add-image-box')) {
			var html = jQuery(this).find('.add-image-box').html();
			var li = jQuery('<li>');

			jQuery(this).find('.add-image-box').remove();
			li.addClass('add-image-box').append(html);
			jQuery(this).append(li);
			li.find('.add-thumb-project').css('display', '');
			li.find('.add-image-video').next().css('display', 'none');
			li.hover(function () {
				jQuery(this).find('.add-thumb-project').css('display', 'none');
				jQuery(this).find('.add-image-video').css('display', 'block');

			}, function () {
				jQuery(this).find('.add-image-video').css('display', 'none');
				jQuery(this).find('.add-thumb-project').css('display', '');

			});

		}
		jQuery(this).find("li").not(".add-image-box").each(function () {
			src += (jQuery(this).hasClass('editthisvideo') == true) ? jQuery(this).find('img').attr('data-video-src') : jQuery(this).find('img').attr('data-img-src');
			src += ";";
		});
		jQuery(this).find('.all-urls').val(src);
	});
}
function pfhubPortfolioFilterInputs() {
	var mainInputs = "";
	jQuery("#images-list > li.highlights").each(function () {
		jQuery(this).next().addClass('submit-post');
		jQuery(this).prev().addClass('submit-post');
		jQuery(this).prev().prev().addClass('submit-post');
		jQuery(this).addClass('submit-post');
		jQuery(this).removeClass('highlights');
	});
	if (jQuery("#images-list > li.submit-post").length) {
		jQuery("#images-list > li.submit-post").each(function () {
			var inputs = jQuery(this).find('.order_by').attr("name");
			var n = inputs.lastIndexOf('_');
			var res = inputs.substring(n + 1, inputs.length);
			res += ',';
			mainInputs += res;
		});
		mainInputs = mainInputs.substring(0, mainInputs.length - 1);
		jQuery(".changedvalues").val(mainInputs);
		jQuery("#images-list > li").not('.submit-post').each(function () {
			jQuery(this).find('input').removeAttr('name');
			jQuery(this).find('textarea').removeAttr('name');
			jQuery(this).find('select').removeAttr('name');
		});
		return mainInputs;
	}
	jQuery("#images-list > li").each(function () {
		jQuery(this).find('input').removeAttr('name');
		jQuery(this).find('textarea').removeAttr('name');
		jQuery(this).find('select').removeAttr('name');
	});
}
function pfhubPortfolioYoutubeParser(url) {
	var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
	var match = url.match(regExp);
	var match_vimeo = /vimeo.*\/(\d+)/i.exec(url);
	if (match && match[7].length == 11) {
		return match[7];
	} else if (match_vimeo) {
		return match_vimeo[1];
	} else {
		return false;
	}
}
