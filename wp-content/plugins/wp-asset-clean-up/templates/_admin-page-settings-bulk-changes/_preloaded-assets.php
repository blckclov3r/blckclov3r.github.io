<?php
/*
 * No direct access to this file
 */
if (! isset($data)) {
	exit;
}

if (! defined('WPACU_USE_MODAL_BOX')) {
	define('WPACU_USE_MODAL_BOX', true);
}

use WpAssetCleanUp\Preloads;

$assetsPreloaded = Preloads::instance()->getPreloads();

$hasCssPreloads = isset($assetsPreloaded['styles'])  && ! empty($assetsPreloaded['styles']);
$hasJsPreloads  = isset($assetsPreloaded['scripts']) && ! empty($assetsPreloaded['scripts']);

$isUpdateable = $hasCssPreloads || $hasJsPreloads;

do_action('wpacu_admin_notices');
?>
<p>This is the list of all the CSS/JS that were preloaded. &nbsp;&nbsp;<a id="wpacu-preloaded-assets-info-target" href="#wpacu-preloaded-assets-info" style="text-decoration: none;"><span class="dashicons dashicons-info"></span> How the list below gets filled?</a></p>

<form action="" method="post">
    <h2>Styles (.css)</h2>
	<?php if ($hasCssPreloads) { ?>
        <table style="width: 96%;" class="wp-list-table widefat fixed striped">
            <tr>
                <td style="min-width: 400px;"><strong>Handle</strong></td>
                <td><strong>Actions</strong></td>
            </tr>

			<?php
			ksort($assetsPreloaded['styles']);

			foreach ($assetsPreloaded['styles'] as $styleHandle => $preloadedStatus) {
				?>
                <tr class="wpacu_bulk_change_row">
                    <td>
                        <?php
	                    $data['assets_info'][ 'styles' ][ $styleHandle ] ['preloaded_status'] = $preloadedStatus;
                        wpacuRenderHandleTd($styleHandle, 'styles', $data);
                        ?>
                    </td>
                    <td>
                        <label><input type="checkbox"
                                      class="wpacu_remove_preload"
                                      name="wpacu_styles_remove_preloads[<?php echo $styleHandle; ?>]"
                                      value="remove" /> Remove preload for this CSS file</label>
                    </td>
                </tr>
				<?php
			}
			?>
        </table>
	<?php } else { ?>
        <p>There are no preloaded stylesheets.</p>
	<?php } ?>

    <div style="margin: 20px 0; width: 96%;">
        <hr/>
    </div>

    <h2>Scripts (.js)</h2>
	<?php if ($hasJsPreloads) { ?>
        <table style="width: 96%;" class="wp-list-table widefat fixed striped">
            <tr>
                <td style="min-width: 400px;"><strong>Handle</strong></td>
                <td><strong>Actions</strong></td>
            </tr>

			<?php
			ksort($assetsPreloaded['scripts']);

			foreach ($assetsPreloaded['scripts'] as $scriptHandle => $preloadedStatus) {
				?>
                <tr class="wpacu_bulk_change_row">
                    <td><?php wpacuRenderHandleTd($scriptHandle, 'scripts', $data); ?></td>
                    <td>
                        <label><input type="checkbox"
                                      class="wpacu_remove_preload"
                                      name="wpacu_scripts_remove_preloads[<?php echo $scriptHandle; ?>]"
                                      value="remove" /> Remove preload for this JS file</label>
                    </td>
                </tr>
				<?php
			}
			?>
        </table>
	<?php } else { ?>
        <p>There are no preloaded scripts.</p>
	<?php } ?>

	<?php
	if ($isUpdateable) {
		wp_nonce_field('wpacu_remove_preloaded_assets', 'wpacu_remove_preloaded_assets_nonce');
	}
	?>
    <div id="wpacu-update-button-area" class="no-left-margin">
        <p style="margin: 20px 0 0 0;">
            <input type="submit"
                   name="submit"
                   <?php if (! $isUpdateable) { ?>disabled="disabled"<?php } ?>
                   class="wpacu-remove-preloads-btn button button-primary"
                   value="Remove preload for chosen CSS/JS" />

            <?php
            if (! $isUpdateable) {
                ?>
                &nbsp;&nbsp; <small>Note: As there are no preloaded CSS/JS, the update button is not enabled.</small>
                <?php
            }
            ?>
        </p>
        <div id="wpacu-updating-settings" style="margin-left: 285px; top: 10px;">
            <img src="<?php echo admin_url('images/spinner.gif'); ?>" align="top" width="20" height="20" alt="" />
        </div>
    </div>
</form>

<!-- Start Site-Wide Modal -->
<div id="wpacu-preloaded-assets-info" class="wpacu-modal">
    <div class="wpacu-modal-content">
        <span class="wpacu-close">&times;</span>
        <h2><?php _e('Preloading CSS/JS site-wide', 'wp-asset-clean-up'); ?></h2>
        <p>This is an overview of all the assets (Stylesheets &amp; Scripts) that have were selected for preloading. Anything you see on this page is filled the moment you go to edit a page via the "CSS/JS Load Manager" (e.g. homepage or a post) and use the "Preload" option (drop-down) on any of the assets.</p>

        <p>The preload for a CSS/JS can also be removed by editing a page that loads that particular file and just select "No (default)" option.</p>

        <p>This is considered a bulk change because the preloading for the chosen file is applied site-wide (not just on the page where you activated the preloading).</p>
    </div>
</div>
<!-- End Site-Wide Modal -->