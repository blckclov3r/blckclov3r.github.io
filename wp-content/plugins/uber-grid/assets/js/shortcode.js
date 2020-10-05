jQuery(document).ready(function(){
            var pfhub_portfolio_show_sorting;
            var pfhub_portfolio_show_filtering;
            var auto_slide_on;

            jQuery('#pfhub_portfolio_show_sorting').change(function () {
                if (jQuery('#pfhub_portfolio_show_sorting').prop('checked') == false) {
                    jQuery('#pfhub_portfolio_show_sorting').val('off');
                }
                else if (jQuery('#pfhub_portfolio_show_sorting').prop('checked') == true) {
                    jQuery('#pfhub_portfolio_show_sorting').val('on');
                }
            });

            jQuery('#pfhub_portfolio_show_filtering').change(function () {
                if (jQuery('#pfhub_portfolio_show_filtering').prop('checked') == false) {
                    jQuery('#pfhub_portfolio_show_filtering').val('off');
                }
                else if (jQuery('#pfhub_portfolio_show_filtering').prop('checked') == true) {
                    jQuery('#pfhub_portfolio_show_filtering').val('on');
                }
            });

            jQuery('#auto_slide_on').change(function () {
                if (jQuery('#auto_slide_on').prop('checked') == false) {
                    jQuery('#auto_slide_on').val('off');
                }
                else if (jQuery('#auto_slide_on').prop('checked') == true) {
                    jQuery('#auto_slide_on').val('on');
                }
            });

            jQuery('#pfhub_portfolioinsert').on('click', function () {
                pfhub_portfolio_show_sorting = jQuery('#pfhub_portfolio_show_sorting').val();
                pfhub_portfolio_show_filtering = jQuery('#pfhub_portfolio_show_filtering').val();
                auto_slide_on = jQuery('#auto_slide_on').val();
                var id = jQuery('#pfhub_portfolio-select option:selected').val();
                var portfolio_effects_list = jQuery('#portfolio_effects_list').val();
                var sl_pausetime = jQuery('#sl_pausetime').val();
                var sl_changespeed = jQuery('#sl_changespeed').val();
                var err = 0;
                var data = {
                    action: 'pfhub_portfolio_action',
                    task: 'optionssave',
                    pfhub_portfolio_grid_id: id,
                    portfolio_effects_list: portfolio_effects_list,
                    pfhub_portfolio_show_sorting: pfhub_portfolio_show_sorting,
                    pfhub_portfolio_show_filtering: pfhub_portfolio_show_filtering,
                    sl_pausetime: sl_pausetime,
                    sl_changespeed: sl_changespeed,
                    pause_on_hover: auto_slide_on,
                    nonce: pfhub_portfolio_add_shortecode_nonce
                };

                if (!jQuery.isNumeric(sl_pausetime) || sl_pausetime < 0) {
                    err = err + 1;
                } else {
                    sl_pausetime = Math.round(sl_pausetime);
                }

                if (!jQuery.isNumeric(sl_changespeed) || sl_changespeed < 0) {
                    err = err + 1;
                } else {
                    sl_changespeed = Math.round(sl_changespeed);
                }

                if (err > 0) {
                    alert('Fill the fields correctly.');
                    return false;
                }


                jQuery.post(ajax_object_shortecode, data, function (response) {

                });
                window.send_to_editor('[pfhub_portfolio id="' + id + '"]');
                tb_remove();

            });
            jQuery('#portfolio_effects_list').on('change', function () {
                var sel = jQuery(this).val();

                if (sel == 5) {
                    jQuery('.for-content-slider').css('display', 'block')
                }
                else {
                    jQuery('.for-content-slider').css('display', 'none')
                }
            });
            jQuery('#portfolio_effects_list').change();

            //////////////////portfolio change options/////////////////////
            jQuery('#pfhub_portfolio-select').change(function () {
                var shoertecodeChangeViewNonce = jQuery(this).attr('change-view-nonce');
                var sel = jQuery(this).val(),
                    data = {
                        action: 'pfhub_portfolio_action',
                        task: 'optonschange',
                        id: sel,
                        nonce: shoertecodeChangeViewNonce
                    };

                jQuery.post(ajax_object_shortecode, data, function (response) {
                    response = JSON.parse(response);

                    var list_effect = response.grid_view_type;
                    jQuery('#portfolio_effects_list').val(response.portfolio_effects_list);
                    jQuery('#portfolio_effects_list option[value=list_effect]').attr('selected');
                    jQuery('#pfhub_portfolio_show_sorting').val(response.pfhub_portfolio_show_sorting);

                    if (jQuery('#pfhub_portfolio_show_sorting').val() == 'on') {
                        jQuery('#pfhub_portfolio_show_sorting').attr('checked', 'checked');
                    } else {
                        jQuery('#pfhub_portfolio_show_sorting').removeAttr('checked');
                    }

                    jQuery('#pfhub_portfolio_show_filtering').val(response.pfhub_portfolio_show_filtering);

                    if (jQuery('#pfhub_portfolio_show_filtering').val() == 'on') {
                        jQuery('#pfhub_portfolio_show_filtering').attr('checked', 'checked');
                    } else {
                        jQuery('#pfhub_portfolio_show_filtering').removeAttr('checked');
                    }

                    jQuery('#sl_pausetime').val(response.sl_pausetime);
                    jQuery('#sl_changespeed').val(response.sl_changespeed);
                    jQuery('#auto_slide_on').val(response.pause_on_hover);

                    if (jQuery('#auto_slide_on').val() == 'on') {
                        jQuery('#auto_slide_on').attr('checked', 'checked');
                    } else {
                        jQuery('#auto_slide_on').removeAttr('checked');
                    }
                    if (response) {
                        var sel1 = jQuery('#portfolio_effects_list').val();
                        if (sel1 == 5) {
                            jQuery('.for-content-slider').css('display', 'block')
                        } else {
                            jQuery('.for-content-slider').css('display', 'none')
                        }
                    }
                });
            });
});
