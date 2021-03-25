<?php
function flying_pages_settings_compression() {
    if (isset($_POST['submit'])) {
        update_option('flying_images_enable_compression', sanitize_text_field($_POST['enable_compression']));
        update_option('flying_images_quality', sanitize_text_field($_POST['quality']));
    }

    $enable_cdn = get_option('flying_images_enable_cdn');
    $enable_compression = get_option('flying_images_enable_compression');
    $quality = esc_attr(get_option('flying_images_quality'));

    if(!$enable_cdn) echo '<br/><div class="notice notice-error is-dismissible"><p>CDN must be enabled for Compression</p></div>';
    
    ?>
    <form method="POST">
        <?php wp_nonce_field('flying-images', 'flying-images-settings-form'); ?>
        <table class="form-table" role="presentation">
        <tbody>
            <tr>
                <th scope="row"><label>Enable Compression</label></th>
                <td>
                    <input name="enable_compression" type="checkbox" value="1" <?php if ($enable_compression) {echo "checked";} ?>>
                    <p class="description">Compress images on the fly. If your images are already compressed using other plugins you can turn this off</p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label>Compression Quality</label></th>
                <td>
                    <select name="quality" value="<?php echo $quality; ?>">
                        <option value="100" <?php if ($quality == 100) {echo 'selected';} ?>>100%</option>
                        <option value="90" <?php if ($quality == 90) {echo 'selected';} ?>>90%</option>
                        <option value="80" <?php if ($quality == 80) {echo 'selected';} ?>>80%</option>
                        <option value="70" <?php if ($quality == 70) {echo 'selected';} ?>>70%</option>
                        <option value="60" <?php if ($quality == 60) {echo 'selected';} ?>>60%</option>
                        <option value="50" <?php if ($quality == 50) {echo 'selected';} ?>>50%</option>
                        <option value="40" <?php if ($quality == 40) {echo 'selected';} ?>>40%</option>
                        <option value="30" <?php if ($quality == 30) {echo 'selected';} ?>>30%</option>
                        <option value="20" <?php if ($quality == 20) {echo 'selected';} ?>>20%</option>
                        <option value="10" <?php if ($quality == 10) {echo 'selected';} ?>>10%</option>
                    </select>
                <td>
            </tr>
        </tbody>
        </table>
        <p class="submit">
            <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
        </p>
    </form>
<?php
}