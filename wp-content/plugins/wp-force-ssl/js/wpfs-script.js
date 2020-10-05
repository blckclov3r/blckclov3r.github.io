/*
 * WP Force SSL
 * (c) WebFactory Ltd 2019 - 2020
 */

(function($) {
  // display a loading message while an action is performed
  function block_ui(message) {
    tmp = swal({
      text: message,
      type: false,
      imageUrl: wpfs.loading_icon_url,
      onOpen: () => {
        $(swal.getImage()).addClass('rotating');
      },
      heightAuto: false,
      imageWidth: 100,
      imageHeight: 100,
      imageAlt: message,
      allowOutsideClick: false,
      allowEscapeKey: false,
      allowEnterKey: false,
      showConfirmButton: false,
      width: 600
    });

    return tmp;
  } // block_ui

  // test SSL certificate
  $('.wpfs_test_ssl').on('click', function(e) {
    e.preventDefault();

    var _ajax_nonce = wpfs.nonce_test_ssl;
    var action = 'test_ssl_nonce_action';
    var form_data = '_ajax_nonce=' + _ajax_nonce + '&action=' + action;

    block_ui(wpfs.testing);

    $.post({
      url: wpfs.ajaxurl,
      data: form_data
    })

      .always(function(data) {
        swal.close();
      })

      .done(function(result) {
        if (typeof result.success != 'undefined' && result.success) {
          jQuery.get(wpfs.home_url).always(function(data, text, xhr) {
            status = xhr.status;
            wphe_changed = false;

            if (status.substr(0, 1) != '2') {
              swal({ type: 'error', heightAuto: false, title: wpfs.undocumented_error });
            } else {
              swal({
                type: 'success',
                heightAuto: false,
                title: wpfs.test_success,
                html: result.data
              });
            }
          });
        } else if (typeof result.success != 'undefined' && !result.success) {
          swal({ heightAuto: false, type: 'error', title: wpfs.test_failed, html: result.data });
        } else {
          swal({ heightAuto: false, type: 'error', title: wpfs.undocumented_error });
        }
      })

      .fail(function(data) {
        if (data.data) {
          swal({
            type: 'error',
            heightAuto: false,
            title: wpfs.documented_error + ' ' + data.data
          });
        } else {
          swal({ heightAuto: false, type: 'error', title: wpfs.undocumented_error });
        }
      });

    return false;
  }); // test SSL certificate

  // save settings
  $('#wpfs_save_settings').on('click', function(e) {
    e.preventDefault();

    var _ajax_nonce = wpfs.nonce_save_settings;
    var action = 'save_settting_nonce_action';
    var form_data = $('#wpfs_form').serialize() + '&_ajax_nonce=' + _ajax_nonce + '&action=' + action;

    block_ui(wpfs.saving);

    $.post({
      url: wpfs.ajaxurl,
      data: form_data
    })
      .always(function(data) {
        swal.close();
      })
      .done(function(result) {
        if (typeof result.success != 'undefined' && result.success) {
          jQuery.get(window.location.href).always(function(data, text, xhr) {
            status = xhr.status;
            if (status.substr(0, 1) != '2') {
              swal({ heightAuto: false, type: 'error', title: wpfs.undocumented_error });
            } else {
              $('#review-notification').show();
              swal({
                type: 'success',
                heightAuto: false,
                title: wpfs.save_success,
                showConfirmButton: false,
                timer: 1400
              });
            }
          });
        } else if (typeof result.success != 'undefined' && !result.success) {
          swal({ heightAuto: false, type: 'error', title: result.data });
        } else {
          swal({ heightAuto: false, type: 'error', title: wpfs.undocumented_error });
        }
      })
      .fail(function(data) {
        if (data.data) {
          swal({
            type: 'error',
            heightAuto: false,
            title: wpfs.documented_error + ' ' + data.data
          });
        } else {
          swal({ heightAuto: false, type: 'error', title: wpfs.undocumented_error });
        }
      });

    return false;
  });
})(jQuery);
