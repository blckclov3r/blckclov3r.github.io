<?php
// no direct access
if (! isset($data)) {
	exit;
}
?>
<script type="text/javascript" data-wpacu-own-inline-script="true">
    var wpacuContentLinks           = document.getElementsByClassName('wpacu-assets-collapsible'),
        wpacuInlineCodeContentLinks = document.getElementsByClassName('wpacu-assets-inline-code-collapsible'),
        wpacuPluginToggleWrapLinks  = document.getElementsByClassName('wpacu-plugin-contracted-wrap-link'),
        wpacuI, wpacuITwo, wpacuIThree;

    // "Styles" & "Scripts" main areas
    for (wpacuI = 0; wpacuI < wpacuContentLinks.length; wpacuI++) {
        wpacuContentLinks[wpacuI].addEventListener('click', function (e) {
            e.preventDefault();

            this.classList.toggle('wpacu-assets-collapsible-active');

            var assetsListContent = this.nextElementSibling;

            if (assetsListContent.style.maxHeight) {
                assetsListContent.style.maxHeight = null;
            } else {
                //assetsListContent.style.maxHeight = assetsListContent.scrollHeight + "px";
                assetsListContent.style.maxHeight = 'inherit';
            }
        });
    }

    // Inline code associated with the handle (expand)
    for (wpacuITwo = 0; wpacuITwo < wpacuInlineCodeContentLinks.length; wpacuITwo++) {
        wpacuInlineCodeContentLinks[wpacuITwo].addEventListener('click', function (e) {
            e.preventDefault();

            this.classList.toggle('wpacu-assets-inline-code-collapsible-active');

            var assetInlineCodeContent = this.nextElementSibling;

            if (assetInlineCodeContent.style.maxHeight) {
                assetInlineCodeContent.style.maxHeight = null;
            } else {
                assetInlineCodeContent.style.maxHeight = assetInlineCodeContent.scrollHeight + 'px';
            }
        });
    }

    // Check if the contract / expand buttons exist (e.g. in view-default.php)
    var $wpacuContractAllBtn = document.getElementById('wpacu-assets-contract-all'),
        $wpacuExpandAllBtn = document.getElementById('wpacu-assets-expand-all');

    if (typeof($wpacuContractAllBtn) != 'undefined' && $wpacuContractAllBtn != null) {
        $wpacuContractAllBtn.addEventListener('click', function (e) {
            e.preventDefault();
            wpacuContractAllMainAreas();
        });
    }

    if (typeof($wpacuExpandAllBtn) != 'undefined' && $wpacuExpandAllBtn != null) {
        $wpacuExpandAllBtn.addEventListener('click', function (e) {
            e.preventDefault();
            wpacuExpandAllMainAreas();
        });
    }

    function wpacuExpandAllMainAreas() {
        var wpacuI, assetsListContent, wpacuContentLinks = document.getElementsByClassName('wpacu-assets-collapsible');

        for (wpacuI = 0; wpacuI < wpacuContentLinks.length; wpacuI++) {
            wpacuContentLinks[wpacuI].classList.add('wpacu-assets-collapsible-active');
            assetsListContent = wpacuContentLinks[wpacuI].nextElementSibling;
            //assetsListContent.style.maxHeight = assetsListContent.scrollHeight + 'px';
            assetsListContent.style.maxHeight = 'inherit';
            assetsListContent.classList.remove('wpacu-open');
        }
    }

    function wpacuContractAllMainAreas() {
        var wpacuI, assetsListContent, wpacuContentLinks = document.getElementsByClassName('wpacu-assets-collapsible');

        for (wpacuI = 0; wpacuI < wpacuContentLinks.length; wpacuI++) {
            wpacuContentLinks[wpacuI].classList.remove('wpacu-assets-collapsible-active');
            assetsListContent = wpacuContentLinks[wpacuI].nextElementSibling;
            assetsListContent.style.maxHeight = null;
        }
    }

    function wpacuExpandAllInlineCodeAreas()
    {
        var wpacuIE,
            assetInlineCodeContent,
            wpacuInlineCodeContentLinks = document.getElementsByClassName('wpacu-assets-inline-code-collapsible');

        for (wpacuIE = 0; wpacuIE < wpacuInlineCodeContentLinks.length; wpacuIE++) {
            wpacuInlineCodeContentLinks[wpacuIE].classList.add('wpacu-assets-inline-code-collapsible-active');
            assetInlineCodeContent = wpacuInlineCodeContentLinks[wpacuIE].nextElementSibling;
            assetInlineCodeContent.style.maxHeight = assetInlineCodeContent.scrollHeight + 'px';
            assetInlineCodeContent.classList.remove('wpacu-open');
        }
    }

    <?php
    // "Styles" and "Scripts"
    if ($data['plugin_settings']['assets_list_layout_areas_status'] === 'contracted') {
    ?>
        wpacuContractAllMainAreas();
    <?php
    } else {
    ?>
        // Remove 'wpacu-open' and set the right max-height to ensure the click action below will work smoothly
        wpacuExpandAllMainAreas();
    <?php
    }

    // "Inline code associated with the handle" - Expand all
    if ($data['plugin_settings']['assets_list_inline_code_status'] !== 'contracted') {
        ?>
            wpacuExpandAllInlineCodeAreas();
        <?php
    }
    ?>

    for (wpacuIThree = 0; wpacuIThree < wpacuPluginToggleWrapLinks.length; wpacuIThree++) {
        wpacuPluginToggleWrapLinks[wpacuIThree].addEventListener('click', function (e) {
            e.preventDefault();

            var wpacuNext = this.nextElementSibling;

            if (this.classList.contains('wpacu-link-closed')) {
                // Change Link Class
                this.classList.remove('wpacu-link-closed');
                this.classList.add('wpacu-link-open');

                // Change Target Content  Class
                wpacuNext.classList.remove('wpacu-area-closed');
                wpacuNext.classList.add('wpacu-area-open');
            } else {
                // Change Link Class
                this.classList.remove('wpacu-link-open');
                this.classList.add('wpacu-link-closed');

                // Change Target Content  Class
                wpacuNext.classList.remove('wpacu-area-open');
                wpacuNext.classList.add('wpacu-area-closed');
            }
        });
    }

    /* Source: http://bdadam.com/blog/automatically-adapting-the-height-textarea.html */
    (function() {
        function wpacuAdjustTextareaHeight(el, minHeight) {
            // compute the height difference which is caused by border and outline
            var outerHeight = parseInt(window.getComputedStyle(el).height, 10);
            var diff = outerHeight - el.clientHeight;

            // set the height to 0 in case of it has to be shrinked
            el.style.height = 0;

            // set the correct height
            // el.scrollHeight is the full height of the content, not just the visible part
            el.style.height = Math.max(minHeight, el.scrollHeight + diff) + 'px';
        }


        // We use the "data-wpacu-adapt-height" attribute as a marker
        var wpacuTextAreas = [].slice.call(document.querySelectorAll('textarea[data-wpacu-adapt-height="1"]'));

        // Iterate through all the textareas on the page
        wpacuTextAreas.forEach(function(el) {
            // we need box-sizing: border-box, if the textarea has padding
            el.style.boxSizing = el.style.mozBoxSizing = 'border-box';

            // we don't need any scrollbars, do we? :)
            el.style.overflowY = 'hidden';

            // the minimum height initiated through the "rows" attribute
            var minHeight = el.scrollHeight;

            el.addEventListener('input', function() {
                wpacuAdjustTextareaHeight(el, minHeight);
            });

            // we have to readjust when window size changes (e.g. orientation change)
            window.addEventListener('resize', function() {
                wpacuAdjustTextareaHeight(el, minHeight);
            });

            // we adjust height to the initial content
            wpacuAdjustTextareaHeight(el, minHeight);
        });
    }());

    /* For Hardcoded Assets List */
    jQuery(document).ready(function($) {
        var $wpacuEl, $wpacuP, $wpacuUp, $wpacuPs, wpacuTotalHeight;

        $(document).on('click', '.wpacu-has-view-more > p.wpacu-view-more-link-area > a', function (e) {
            e.preventDefault();
            //console.log('View more click...');

            wpacuTotalHeight = 0;

            $wpacuEl = $(this);
            $wpacuP = $wpacuEl.parent();
            $wpacuUp = $wpacuP.parent();
            $wpacuPs = $wpacuUp.find('div');

            // Measure how tall inside should be by adding together heights of all inside elements (except read-more)
            $wpacuPs.each(function () {
                wpacuTotalHeight += jQuery(this).outerHeight();
            });

            $wpacuUp.css({
                // Set height to prevent instant jumpdown when max height is removed
                'height': $wpacuUp.height(),
                'max-height': 9999
            }).animate({
                'height': wpacuTotalHeight
            }, function () {
                $wpacuUp.css({'height': 'auto'});
            });

            // Fade out read-more
            $wpacuP.fadeOut();

            // Prevent jump-down
            return false;
        });
    });

    <?php
    // [wpacu_lite]
    if (! is_admin()) {
        // Admin manages the list in the front-end view
        $upgradeToProLink = WPACU_PLUGIN_GO_PRO_URL.'?utm_source=manage_hardcoded_assets&utm_medium=go_pro_frontend';
    ?>
        var wpacuElGoPro = document.getElementsByClassName('wpacu-manage-hardcoded-assets-requires-pro-popup');

        for (var wpacuII = 0; wpacuII < wpacuElGoPro.length; wpacuII++) {
            // Here we have the same onclick
            wpacuElGoPro.item(wpacuII).onclick = function() {
                window.location.replace('<?php echo $upgradeToProLink; ?>');
            };
        }
    <?php
    }
    // [/wpacu_lite]
    ?>
</script>