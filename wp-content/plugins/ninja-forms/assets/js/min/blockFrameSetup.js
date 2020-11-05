/**
 * This file is used by form block iFrame
 *
 * It was created with original block, it is still used by refactored block.
 */
(function() {
    jQuery(document).on( 'nfFormReady', function(){
        var frameEl = window.frameElement;

        // get the form element
        var $form = jQuery("#nf-form-" + ninjaFormsBlockSetup.form_id + "-cont");
        // get the height of the form
        var height = $form.find( '.ninja-forms-form-wrap' ).outerHeight(true);

        if (frameEl) {
            // add 125 to height b/c the submit button was missing
            frameEl.height = height + 125;
        }
    });
 
 })();