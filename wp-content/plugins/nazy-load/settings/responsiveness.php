<?php
function flying_pages_settings_responsiveness()
{
    // Responsiveness
    if (isset($_POST['submit'])) {
        update_option('flying_images_enable_responsive_images', sanitize_text_field($_POST['enable_responsive_images']));
    }

    $enable_cdn = get_option('flying_images_enable_cdn');
    $enable_responsive_images = get_option('flying_images_enable_responsive_images');

    if(!$enable_cdn) echo '<br/><div class="notice notice-error is-dismissible"><p>CDN must be enabled for Responsiveness</p></div>';
    
    ?>
    <form method="POST">
        <?php wp_nonce_field('flying-images', 'flying-images-settings-form'); ?>
        <table class="form-table" role="presentation">
        <tbody>
            <tr>
                <th scope="row"><label>Enable responsive images</label></th>
                <td>
                    <input name="enable_responsive_images" type="checkbox" value="1" <?php if ($enable_responsive_images) {echo "checked";} ?>>
                    <p class="description">Use srcset to deliver responsive (adaptive) images</p>
                </td>
            </tr>
        </tbody>
        </table>
        <p class="submit">
            <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
        </p>
    </form>
<?php
}