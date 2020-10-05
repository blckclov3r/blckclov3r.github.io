jQuery(document).ready(function($) {

	var cstm_cds_currentPageTitle = $('title').html();
	var cstm_cds_notSavedPageTitle = 'UNSAVED-' + $('title').html();
	var cstm_cds_notSavedText = 'The changes you made will be lost if you navigate away from this page.';
	var cstm_cds_audioElement = document.createElement('audio');
	cstm_cds_audioElement.setAttribute('src', cstm_cds_vars.cstm_cds_plugin_dir_url + 'Glass.mp3');


	// Active Tabs
	var previous_left_tab = "";
	var active_left_tab = $('.css-tabs > .active').data('select-file');

	$('.css-tabs > a').click(function() {

		previous_left_tab = ( active_left_tab != $(this).data('select-file') ? active_left_tab : previous_left_tab );
		active_left_tab = $(this).data('select-file');

	});

	var previous_right_tab = "";
	var active_right_tab = $('.js-tabs > .active').data('select-file');

	$('.js-tabs > a').click(function() {

		previous_right_tab = ( active_right_tab != $(this).data('select-file') ? active_right_tab : previous_right_tab );;
		active_right_tab = $(this).data('select-file');

	});


	// SASS Checker
	var cstm_cds_sass_active = false;
	if ( $('.css-side textarea').hasClass('sass') ) cstm_cds_sass_active = true;


	// Admin Checker
	var cstm_cds_admin_active = false;
	if ( cstm_cds_vars.cstm_cds_admin == "Admin" ) cstm_cds_admin_active = true;
	if (cstm_cds_admin_active) $('#toplevel_page_custom-codes ul.wp-submenu li.wp-first-item').removeClass('current').next().addClass('current');


	// FIND CODE EDITORS
	var cstm_cds_editors = {};
    $('.code-editor').each(function(index) {

		// Language Detect
		var editor_lang;
	    if ( $(this).hasClass('css') ) {
		    editor_lang = "css";
	    } else if ( $(this).hasClass('sass') ) {
		    editor_lang = "text/x-scss";
	    } else if ( $(this).hasClass('js') ) {
		    editor_lang = "javascript";
	    } else if ( $(this).hasClass('php') ) {
		    editor_lang = "application/x-httpd-php";
	    } else { // No need for now
		    editor_lang = $(this).data('fileextension');
	    }


	    // Is read-only?
	    var read_only = false;
	    if ( $(this).hasClass('read-only') ) { //console.log('YEAH'); console.log($(this));
		    read_only = true;
	    }


	    // Theme
	    var theme = "monokai";
	    if ( $(this).hasClass('theme-light') ) {
		    theme = "default";
	    }


	    // Put a unique id
	    var the_editor_id = editor_lang + '-file-' + index ;
	    if (read_only) the_editor_id = "readonly";
	    $(this).attr('id', the_editor_id);



		// Run Codemirror
		cstm_cds_editors[the_editor_id] =
			emmetCodeMirror(
				CodeMirror.fromTextArea(
					document.getElementById(the_editor_id),
					{
						mode: editor_lang,
					    profile: 'xhtml', // define Emmet output profile
						theme: theme,
						lineNumbers: true,
						styleActiveLine: true,
						autoCloseBrackets: true,
						matchBrackets: true,
						foldGutter: true,
						indentUnit: 4,
						showTrailingSpace: false,
						readOnly: read_only,
						highlightSelectionMatches: {showToken: /\w/},
						gutters: ["CodeMirror-linenumbers", "breakpoints"],
						extraKeys: {
					        "Cmd-F": "findPersistent",
					        "Ctrl-F": "findPersistent",
					        "Cmd-7": function(cm) {
								cm.toggleComment({ fullLines: false });
				  			},
					        "Ctrl-7": function(cm) {
								cm.toggleComment({ fullLines: false });
				  			},
					        "Cmd-G": function(cm) { console.log('TEST');
					          cm.setOption("fullScreen", !cm.getOption("fullScreen"));
					          var wrapper = $('.wrap');
					          if ( wrapper.hasClass('fixedthis') ) {
						          wrapper.removeClass('fixedthis');
						      } else {
							      wrapper.addClass('fixedthis');
							  }

							  // hide admin menus
							  var admin_bars = $('#adminmenuback, #adminmenuwrap, #wpadminbar');
							  if ( admin_bars.hasClass('zerozindex') ) {
								  admin_bars.removeClass('zerozindex');
							  } else {
								  admin_bars.addClass('zerozindex');
							  }

					        },
					        "Ctrl-G": function(cm) {
					          cm.setOption("fullScreen", !cm.getOption("fullScreen"));
					          var wrapper = $('.wrap');
					          if ( wrapper.hasClass('fixedthis') ) {
						          wrapper.removeClass('fixedthis');
						      } else {
							      wrapper.addClass('fixedthis');
							  }

							  // hide admin menus
							  var admin_bars = $('#adminmenuback, #adminmenuwrap, #wpadminbar');
							  if ( admin_bars.hasClass('zerozindex') ) {
								  admin_bars.removeClass('zerozindex');
							  } else {
								  admin_bars.addClass('zerozindex');
							  }
					        },
					        "Esc": function(cm) {
					          if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
					          var wrapper = $('.wrap');
					          if ( !$('.wrap').hasClass('fixedthis') ) {
						          $('.wrap').addClass('fixedthis');
							  }

							  var admin_bars = $('#adminmenuback, #adminmenuwrap, #wpadminbar');
							  if ( admin_bars.hasClass('zerozindex') ) {
								  admin_bars.removeClass('zerozindex');
							  }

					        }
				      	}
					}
				)
			);

        cstm_cds_editors[the_editor_id].on("gutterClick", function(cm, n) {
			var info = cm.lineInfo(n);
			cm.setGutterMarker(n, "breakpoints", info.gutterMarkers ? null : makeMarker());
		});
		cstm_cds_editors[the_editor_id].setSize("100%", "auto");

        function makeMarker() {
			var marker = document.createElement("div");
			marker.style.color = "#822";
			marker.innerHTML = "●";
			return marker;
		}


		// CAPTURE CHANGES
		var this_editor = $(this);
		var this_filename = this_editor.data('filename');
		var this_tab = this_editor.parent().find('.custom-tabs a[data-select-file="' + this_filename + '"]');
		cstm_cds_editors[the_editor_id].on('change', function () {

			if ( the_editor_id != 'readonly' ) {
		        $('#loaded').hide();
		        $('#load-failed').hide();
		        $('title').html(cstm_cds_notSavedPageTitle);
		        this_tab.addClass('unsaved');
		        this_editor.removeClass('empty-file');

		        // Not Saved warning
		        window.onbeforeunload = function() {
		    		return cstm_cds_notSavedText;
				};
			}

	    });


    });






	// TABS
	$('.custom-tabs > a').click(function() {


		var current_side_element = $(this).parent().parent();


		// An Active Bottom Tab Click
		if ( $(this).parent().hasClass('bottom-tabs') && $(this).hasClass('active') ) {


			var previous_tab = current_side_element.hasClass('css-side') ? previous_left_tab : previous_right_tab;

			// Deactivate the current tab and editor
			current_side_element.find('.custom-tabs a').removeClass('active');
			current_side_element.find('textarea.code-editor').addClass('not-active').removeClass('active');


			// Activate the previous tab and editor
			current_side_element.find('.custom-tabs a[data-select-file="'+ previous_tab +'"]').addClass('active');
			current_side_element.find('textarea.code-editor[data-filename="' + previous_tab + '"]').addClass('active').removeClass('not-active');


		// Other Tab Clicks
		} else {


			var file_to_select = $(this).data('select-file');

			current_side_element.find('.custom-tabs a').removeClass('active');
			current_side_element.find('textarea.code-editor').addClass('not-active').removeClass('active');

			$(this).addClass('active');
			current_side_element.find('textarea.code-editor[data-filename="' + file_to_select + '"]').addClass('active').removeClass('not-active');


		}



		// Theme Functions Tab Title Change
		if ( $(this).hasClass('functions-php') && $(this).hasClass('active') ) {

			$('.css-side > h2 > span.dynamic-title').data( 'orig', $('.css-side > h2 > span.dynamic-title').text().trim() ).text('Custom Functions PHP');

		} else {

			$('.css-side > h2 > span.dynamic-title').text( $('.css-side > h2 > span.dynamic-title').data('orig') );

		}



		// Capture the unsaved tabs
		if ( !$('.custom-tabs a.active').hasClass('unsaved') ) {
	        $('title').html(cstm_cds_currentPageTitle);
		} else {
	        $('title').html(cstm_cds_notSavedPageTitle);
		}



		// EXISTING SASS WARNING TRIGERER
		if ( !cstm_cds_sass_active ) {

			if ( $('.css-side .code-editor.active').hasClass('existing-sass') ) {
				$('#cstm_cds_savethem').addClass('existing-sass');
			} else {
				$('#cstm_cds_savethem').removeClass('existing-sass');
			}

		}

		return false;

	});






	// EXISTING SASS WARNING TRIGERER ON LOAD
	if ( !cstm_cds_sass_active ) {

		if ( $('.css-side .code-editor.active').hasClass('existing-sass') ) {
			$('#cstm_cds_savethem').addClass('existing-sass');
		}

	}






	// SAVE SHORTCUT
	jQuery('div.CodeMirror textarea').addClass("mousetrap");
	Mousetrap.bind(['ctrl+s', 'command+s'], function(e) {
		if (e.preventDefault) {
	        e.preventDefault();
	    } else {
	        // internet explorer
	        e.returnValue = false;
	    }

		jQuery('#cstm_cds_savethem').click();

	});






	// HIDEABLE SIDES
	$('a.hider-css').click(function () {

		$('.js-side').show().removeClass('both-open').addClass('open');
		$('.css-side').hide().removeClass('both-open').removeClass('open');

		// Not to Save hidden data
		$('.js-side .code-editor').removeClass('hidden');
		$('.css-side .code-editor').addClass('hidden');

		/* Buttons */
		$('a.hider-js').html('Show only CSS');
		$('.show-both').show();

		return false;

	});

	$('a.hider-js').click(function () {

		$('.css-side').show().removeClass('both-open').addClass('open');
		$('.js-side').hide().removeClass('both-open').removeClass('open');

		// Not to Save hidden data
		$('.css-side .code-editor').removeClass('hidden');
		$('.js-side .code-editor').addClass('hidden');

		/* Buttons */
		$('a.hider-css').html('Show only JS');
		$('.show-both').show();

		return false;

	});

	$('span.show-both a').click(function () {

		$('.css-side').show().removeClass('open').addClass('both-open');
		$('.js-side').show().removeClass('open').addClass('both-open');

		// Not to Save hidden data
		$('.css-side .code-editor, .js-side .code-editor').removeClass('hidden');

		/* Buttons */
		$('a.hider-css, a.hider-js').html('Hide this');
		$('.show-both').hide();

		return false;

	});





	// FORM SUBMIT & AJAX SAVER
	$('#cc-custom-codes-form').submit(function() {

		var process_time = new Date().getTime();

		// Check existing Sass on CSS mode for warning
		if ( $('#cstm_cds_savethem').hasClass('existing-sass') ) {

			if (confirm("Your existing SASS file will be deleted?")) {
				$('#cstm_cds_savethem').removeClass('existing-sass');
				$('.css-side .code-editor.active').removeClass('existing-sass');
			} else {
				return false;
			}

		}



		// Disable Editors
		$('.code-editor').each(function(index) {
			$('#cc-saving-overlay, .sides').addClass('saving');
			cstm_cds_editors[$(this).attr('id')].setOption("readOnly", true);
		});



		// Loading Indicator & Button State
		$('#loaded').hide();
		$('#load-failed').hide();
		$('#loading').show();
		$('#cstm_cds_savethem').attr('disabled', true);



		// Ajax Settings
		var data = {};
		data['action'] = 'cstm_cds_ajax_action';
		data['cstm_cds_nonce'] = cstm_cds_vars.cstm_cds_nonce;
		data['is_admin'] = cstm_cds_admin_active ? true : false;

		// Prepare the cstm_cds_editors' data
		data['cstm_cds_editor_contents'] = [];
		var editor_data = {};



		// Output Update and Include the main file data?
		var update_output = true;


		// NOT FULL SCREEN
		if ( !$('.CodeMirror').hasClass('CodeMirror-fullscreen') ) {


			$('.code-editor.active:not(.hidden)').each(function(index) {

				var filename = (cstm_cds_admin_active ? 'admin_' : '') + $(this).data('filename') + "." + $(this).data('fileextension');
				editor_data[filename] = [];


				// 0. Language
				if ( $(this).data('fileextension') == "scss" ) {
					editor_data[filename].push('sass');
				} else {
					editor_data[filename].push( $(this).data('fileextension') );
				}
				editor_data[filename].push( (cstm_cds_admin_active ? 'admin_' : '') + $(this).data('filename') ); // 1. File Name
				editor_data[filename].push( cstm_cds_editors[$(this).attr('id')].getValue() ); // 2. Content

				if ( $(this).data('fileextension') == "php" )
					update_output = false;


			});

			if ( $('.js-side').hasClass('open') )
				update_output = false;


		// FULL SCREEN
		} else {


			var full_editor = $('.CodeMirror-fullscreen').prev();
			var filename = (cstm_cds_admin_active ? 'admin_' : '') + full_editor.data('filename') + "." + full_editor.data('fileextension');
			editor_data[filename] = [];

			// 0. Language
			if ( full_editor.data('fileextension') == "scss" ) {
				editor_data[filename].push('sass');
			} else {
				editor_data[filename].push( full_editor.data('fileextension') );
			}
			editor_data[filename].push( (cstm_cds_admin_active ? 'admin_' : '') + full_editor.data('filename') ); // 1. File Name
			editor_data[filename].push( cstm_cds_editors[full_editor.attr('id')].getValue() ); // 2. Content

			// JS Side
			if ( $('.CodeMirror-fullscreen').parent().hasClass('js-side') )
				update_output = false;

			// PHP Editor
			if ( full_editor.data('fileextension') == "php" )
				update_output = false;


		}

		// MIXIN FILE - Re-Save all styles to debug
		if ( $('.css-tabs .mixins').hasClass('active') || $('.css-tabs .css-output-tab').hasClass('active') ) {

			$('.css-side .code-editor:not(.php):not(.empty-file)').each(function(index) {

				var filename = (cstm_cds_admin_active ? 'admin_' : '') + $(this).data('filename') + "." + $(this).data('fileextension');
				editor_data[filename] = [];


				// 0. Language
				if ( $(this).data('fileextension') == "scss" ) {
					editor_data[filename].push('sass');
				} else {
					editor_data[filename].push( $(this).data('fileextension') );
				}
				editor_data[filename].push( (cstm_cds_admin_active ? 'admin_' : '') + $(this).data('filename') ); // 1. File Name
				editor_data[filename].push( cstm_cds_editors[$(this).attr('id')].getValue() ); // 2. Content


			});

		}


		// Include CSS Main File Data ?
		if ( update_output ) {

			editor_data[ cstm_cds_admin_active ? 'admin_panel.' : 'custom_public.' + 'css' ] = [];
			editor_data[ cstm_cds_admin_active ? 'admin_panel.' : 'custom_public.' + 'css' ].push( 'css' );
			editor_data[ cstm_cds_admin_active ? 'admin_panel.' : 'custom_public.' + 'css' ].push( cstm_cds_admin_active ? 'admin_panel' : 'custom_public' );
			editor_data[ cstm_cds_admin_active ? 'admin_panel.' : 'custom_public.' + 'css' ].push( '' );

		}


		// Push the files' data
		data['cstm_cds_editor_contents'] = editor_data;



		// SEND DATA TO PHP
		$.post(ajaxurl, data, function(response) {

			// Variable defines
			var responses;
			var has_error = false;
	        var has_fatal_error = false;
	        var has_success = false;
	        var successful = false;
	        var result_content = "";

			// CATCH RESULT
			try {
			    responses = jQuery.parseJSON( response );
			} catch (e) {
				has_fatal_error = true;
			}


			if ( !has_fatal_error ) {
				if (responses.error != null) has_error = true;
				if (responses.success != null) has_success = true;
				if (!has_error) successful = true;
			} else {
				has_error = true;
				has_success = false;
				successful = false;
			}



			// Create the result content
			String.prototype.stripSlashes = function(){
			    return this.replace(/\\(.)/mg, "$1");
			}
			if (has_error && !has_fatal_error) {
				$.each(responses.error, function(key, result_output){
		           	result_content += result_output.stripSlashes();
		        });
			}
			if (has_success) {
				$.each(responses.success, function(key, result_output){
		           	result_content += result_output.stripSlashes();
		        });
			}



			// PRINT THE RESULTS
			if ( has_fatal_error ) {
				$('.responser + .error-content').html(response);
			} else {
				$('.responser + .error-content').html(result_content);
			}



			// Correct the button state
			$('#loading').hide();
			$('#cstm_cds_savethem').attr('disabled', false);


			if ( successful ) {

				$('#loaded').show();
				$('#load-failed').removeClass('bounce');


				// UPDATE The CSS Output
				if ( update_output )
					cstm_cds_editors['readonly'].setValue(responses.css_output);


				// Remove the unsaved star from tabs
				$.each(responses.success, function(key, result_output){
					var only_filename = key.replace(/\.[^/.]+$/, "");
					var only_extension = key.substr( (key.lastIndexOf('.') +1) );
					if (cstm_cds_admin_active) only_filename = only_filename.substr(6);
					$('.custom-tabs a[data-select-file="'+ only_filename +'"]').removeClass('unsaved');
					$('.code-editor.empty-file[data-filename="'+ only_filename +'"][data-fileextension="'+only_extension+'"]').removeClass('empty-file');
		        });


				// Play the music
				cstm_cds_audioElement.play();

				// Allow closing if not any unsaved tab
				if ( !$('.custom-tabs a.unsaved').length ) window.onbeforeunload = null;


				// Get current title if not any unsaved page
				if ( !$('.custom-tabs a.unsaved').length ) $('title').html(cstm_cds_currentPageTitle);


				// Total Process Time
				$('.responser + .error-content').append("<p class='total-process'>Total Process: " + (new Date().getTime() - process_time + " ms") + "</p>");

			} else {

				$('#load-failed').show().addClass('bounce');

				// Not saved warning
				window.onbeforeunload = function() {
					return cstm_cds_notSavedText;
				};

				// Total Process Time
				$('.responser + .error-content').append("<p class='total-process'>Total Process: " + (new Date().getTime() - process_time + " ms") + "</p>");
			}



			// Enable Editors
			$('.code-editor:not(.read-only)').each(function(index) {
				$('#cc-saving-overlay, .sides').removeClass('saving');
				cstm_cds_editors[$(this).attr('id')].setOption("readOnly", false);
			});



		}); // Ajax Sender

		return false;

	});


	var button_es = $('#custom-codes-editor-settings-saver');

	$('.es-inputs').on('change', function() {

		if ( !button_es.prop('disabled') )
			button_es.val('Save');

	});

	$('#custom-codes-editor-settings-form').submit(function() {
		var form = $(this);
		var data =  form.serialize();

		button_es.prop("disabled", true).val('Saving...');

        $.post( 'options.php', data ).error(function() {
            alert('An error occured. Please try again.');
        }).success( function() {

			button_es.prop("disabled", false).val('Saved!');

			// Change Editor Theme
			$('.code-editor').each(function(index) {

				if ( $('input[name="cstm_cds_editor_theme"]:checked').val() == "light" ) {
					cstm_cds_editors[$(this).attr('id')].setOption("theme", "default");
					$('.sides, .code-editor').addClass('theme-light').removeClass('theme-dark');
				} else {
					cstm_cds_editors[$(this).attr('id')].setOption("theme", "monokai");
					$('.sides, .code-editor').addClass('theme-dark').removeClass('theme-light');
				}

			});

        });

        return false;

	});


});