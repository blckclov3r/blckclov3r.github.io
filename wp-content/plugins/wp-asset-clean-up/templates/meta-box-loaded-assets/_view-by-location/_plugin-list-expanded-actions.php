<?php
if (! isset($showUnloadOnThisPageCheckUncheckAll, $showLoadItOnThisPageCheckUncheckAll, $locationChild)) {
	exit;
}
?>
<div class="wpacu-plugin-toggle-all-wrap">
	<?php
	// Only show if there is at least one "Unload on this page" checkbox available
	// It won't be if there are only bulk unloads
	if ( $showUnloadOnThisPageCheckUncheckAll ) { ?>
        <div class="wpacu-plugin-toggle-all">
            <ul>
                <li>"Unload on this page"</li>
                <li>
                    <a class="wpacu-plugin-check-all"
                       data-wpacu-plugin="<?php echo $locationChild; ?>"
                       href="#">Check All</a>
                    |
                    <a class="wpacu-plugin-uncheck-all"
                       data-wpacu-plugin="<?php echo $locationChild; ?>"
                       href="#">Uncheck All</a>
                </li>
            </ul>
        </div>
	<?php } ?>
	<?php
	// Only show if there is at least one bulk unloaded asset
	// Otherwise, there is no load exception to make
	if ( $showLoadItOnThisPageCheckUncheckAll ) {
		?>
        <div class="wpacu-plugin-toggle-all" style="min-width: 390px;">
            <ul>
                <li>Make an exception from bulk unload, "Load it on this page"</li>
                <li>
                    <a class="wpacu-plugin-check-load-all"
                       data-wpacu-plugin="<?php echo $locationChild; ?>"
                       href="#">Check All</a>
                    |
                    <a class="wpacu-plugin-uncheck-load-all"
                       data-wpacu-plugin="<?php echo $locationChild; ?>"
                       href="#">Uncheck All</a>
                </li>
        </div>
		<?php
	}
	?>
</div>