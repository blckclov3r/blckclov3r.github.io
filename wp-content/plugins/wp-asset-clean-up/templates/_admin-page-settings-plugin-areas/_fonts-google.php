<?php
/*
 * No direct access to this file
 */
if (! isset($data)) {
	exit;
}

$tabIdArea = 'wpacu-setting-google-fonts';
$styleTabContent = ($selectedTabArea === $tabIdArea) ? 'style="display: table-cell;"' : '';

$ddOptions = array(
    'swap' => 'swap (most used)',
    'auto' => 'auto',
    'block' => 'block',
    'fallback' => 'fallback',
    'optional' => 'optional'
);
?>
<div id="<?php echo $tabIdArea; ?>" class="wpacu-settings-tab-content" <?php echo $styleTabContent; ?>>
	<h2 class="wpacu-settings-area-title"><?php _e('Google Fonts: Load Optimizer', 'wp-asset-clean-up'); ?></h2>

    <div class="wpacu-sub-tabs-wrap"> <!-- Sub-tabs wrap -->
        <!-- Sub-nav menu -->
        <input class="wpacu-nav-input"
               id="wpacu-google-fonts-optimize-tab-item"
               type="radio"
               name="wpacu_sub_tab_area"
               value="wpacu-google-fonts-optimize"
               <?php if (in_array($selectedSubTabArea, array('wpacu-google-fonts-optimize', ''))) { ?>checked="checked"<?php } ?> />
        <label class="wpacu-nav-label"
               for="wpacu-google-fonts-optimize-tab-item">Optimize Font Delivery</label>

        <input class="wpacu-nav-input"
               id="wpacu-google-fonts-remove-tab-item"
               type="radio"
               name="wpacu_sub_tab_area"
               value="wpacu-google-fonts-remove"
               <?php if ($selectedSubTabArea === 'wpacu-google-fonts-remove') { ?>checked="checked"<?php } ?> />
        <label class="wpacu-nav-label"
               for="wpacu-google-fonts-remove-tab-item">Remove All</label>
        <!-- /Sub-nav menu -->

        <section class="wpacu-sub-tabs-item" id="wpacu-google-fonts-optimize-tab-item-area">
            <?php include_once __DIR__.'/_fonts-google/_optimize-area.php'; ?>
        </section>
        <section class="wpacu-sub-tabs-item" id="wpacu-google-fonts-remove-tab-item-area">
	        <?php include_once __DIR__.'/_fonts-google/_remove-area.php'; ?>
        </section>
    </div> <!-- /Sub-tabs wrap -->
</div>

<div id="wpacu-google-fonts-display-info" class="wpacu-modal" style="padding-top: 70px;">
    <div class="wpacu-modal-content" style="max-width: 800px;">
        <span class="wpacu-close">&times;</span>
        <h3 style="margin-top: 2px; margin-bottom: 5px;">font-display: <span style="background: #f2faf2;">swap</span></h3>
        <p style="margin-top: 0; margin-bottom: 24px;">The text is shown immediately (without any block period, no invisible text) in the fallback font until the custom font loads, then it's swapped with the custom font. You get a <strong>FOUT</strong> (<em>flash of unstyled text</em>).</p>

        <h3 style="margin-bottom: 5px;">font-display: <span style="background: #f2faf2;">block</span></h3>
        <p style="margin-top: 0; margin-bottom: 24px;">The text blocks (is invisible) for a short period. Then, if the custom font hasn't been downloaded yet, the browser swaps (renders the text in the fallback font), for however long it takes the custom font to be downloaded, and then re-renders the text in the custom font. You get a <strong>FOIT</strong> (<em>flash of invisible text</em>).</p>

        <h3 style="margin-bottom: 5px;">font-display: <span style="background: #f2faf2;">fallback</span></h3>
        <p style="margin-top: 0; margin-bottom: 24px;">This is somewhere in between block and swap. The text is invisible for a short period of time (100ms). Then if the custom font hasn't downloaded, the text is shown in a fallback font (for about 3s), then swapped after the custom font loads.</p>

        <h3 style="margin-bottom: 5px;">font-display: <span style="background: #f2faf2;">optional</span></h3>
        <p style="margin-top: 0; margin-bottom: 24px;">This behaves just like fallback, only the browser can decide to not use the custom font at all, based on the user's connection speed (if you're on a slow 3G or less, it will take forever to download the custom font and then swapping to it will be too late and extremely annoying)</p>

        <h3 style="margin-bottom: 5px;">font-display: <span style="background: #f2faf2;">auto</span></h3>
        <p style="margin-top: 0; margin-bottom: 0;">The default. Typical browser font loading behavior will take place. This behavior may be FOIT, or FOIT with a relatively long invisibility period. This may change as browser vendors decide on better default behaviors.</p>
    </div>
</div>