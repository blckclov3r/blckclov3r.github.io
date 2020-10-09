<?php
if (! isset($showUnloadOnThisPageCheckUncheckAll, $showLoadItOnThisPageCheckUncheckAll, $locationChild)) {
	exit;
}
?>
<div class="wpacu-plugin-toggle-all-wrap">
    <?php
    // Only show if there is at least one "Unload on this page" checkbox available
    // It won't be if there are only bulk unloads
    if ( $showUnloadOnThisPageCheckUncheckAll ) {
        ?>
        <div class="wpacu-plugin-toggle-all">
            &#10230; "Unload on this page" -
            <a class="wpacu-plugin-check-all"
               data-wpacu-plugin="<?php echo $locationChild; ?>" href="#">Check All</a>
            |
            <a class="wpacu-plugin-uncheck-all"
               data-wpacu-plugin="<?php echo $locationChild; ?>" href="#">Uncheck
                All</a>
        </div>
    <?php }  ?>

    <?php
    // Only show if there is at least one bulk unloaded asset
    // Otherwise, there is no load exception to make
    if ( $showLoadItOnThisPageCheckUncheckAll ) {
        ?>
        <div class="wpacu-plugin-toggle-all">
            &#10230; Make an exception from bulk unload, "Load it on this page" -
            <a class="wpacu-plugin-check-load-all"
               data-wpacu-plugin="<?php echo $locationChild; ?>" href="#">Check All</a>
            |
            <a class="wpacu-plugin-uncheck-load-all"
               data-wpacu-plugin="<?php echo $locationChild; ?>" href="#">Uncheck
                All</a> * <small>relevant if bulk unload rules (e.g. site-wide) are already applied</small>
        </div>
        <?php
    }
    ?>
</div>
