( function ( $ ) {
    
    var redirectionLink = " https://premiumaddons.com/pro/?utm_source=wp-menu&utm_medium=wp-dash&utm_campaign=get-pro&utm_term=";
    "use strict";
    
    $(".pa-checkbox").on("click", function() {
       if($(this).prop("checked") == true) {
           $(".pa-elements-table input").prop("checked", 1);
       }else if($(this).prop("checked") == false){
           $(".pa-elements-table input").prop("checked", 0);
       }
    });
   
   $(".pro-slider").on('click', function(){

        swal({
            title: '<span class="pa-swal-head">Get PRO Widgets & Addons<span>',
            html: 'Supercharge your Elementor with PRO widgets and addons that you won’t find anywhere else.',
            type: 'warning',
            showCloseButton: true,
	  		showCancelButton: true,
            cancelButtonText: "More Info",
	  		focusConfirm: true
        }).then(function(json_data) {}, function(dismiss) {
            if (dismiss === 'cancel') { 
                window.open( redirectionLink + settings.theme, '_blank' );
            } 
        });
    });

    $( 'form#pa-settings' ).on( 'submit', function(e) {
		e.preventDefault();
		$.ajax( {
			url: settings.ajaxurl,
			type: 'post',
			data: {
                action: 'pa_save_admin_addons_settings',
                security: settings.nonce,
				fields: $( 'form#pa-settings' ).serialize(),
			},
            success: function( response ) {
				swal(
				  'Settings Saved!',
				  'Click OK to continue',
				  'success'
				);
			},
			error: function() {
				swal(
				  'Oops...',
				  'Something Wrong!',
				);
			}
		} );

	} );
        
    $('form#pa-maps').on('submit',function(e){
       e.preventDefault();
       $.ajax( {
            url: settings.ajaxurl,
            type: 'post',
            data: {
                action: 'pa_maps_save_settings',
                security: settings.nonce,
                fields: $('form#pa-maps').serialize(),
            },
            success: function (response){
                swal(
				  'Settings Saved!',
				  'Click OK to continue',
				  'success'
                );
            },
            error: function(){
                swal(
                    'Oops...',
                    'Something Wrong!',
                );
            }
        });
    });
    
    
     $('form#pa-beta-form').on('submit',function(e){
       e.preventDefault();
       $.ajax( {
            url: settings.ajaxurl,
            type: 'post',
            data: {
                action: 'pa_beta_save_settings',
                security: settings.nonce,
                fields: $('form#pa-beta-form').serialize(),
            },
            success: function (response){
                swal(
				  'Settings Saved!',
				  'Click OK to continue',
				  'success'
                );
            },
            error: function(){
                swal(
                    'Oops...',
                    'Something Wrong!',
                );
            }
        });
    });



    $( '.pa-rollback-button' ).on( 'click', function( event ) {
				event.preventDefault();

				var $this = $( this ),
					dialogsManager = new DialogsManager.Instance();

				dialogsManager.createWidget( 'confirm', {
					headerMessage: premiumRollBackConfirm.i18n.rollback_to_previous_version,
					message: premiumRollBackConfirm.i18n.rollback_confirm,
					strings: {
						cancel: premiumRollBackConfirm.i18n.cancel,
                        confirm: premiumRollBackConfirm.i18n.yes,
					},
					onConfirm: function() {
						$this.addClass( 'loading' );

						location.href = $this.attr( 'href' );
					}
				} ).show();
			} );
    
} )(jQuery);