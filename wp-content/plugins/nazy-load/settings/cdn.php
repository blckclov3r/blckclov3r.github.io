<?php
function flying_pages_settings_cdn() {

    if (isset($_POST['submit'])) {
        update_option('flying_images_enable_cdn', sanitize_text_field($_POST['enable_cdn']));
        $keywords = trim($_POST['cdn_exclude_keywords']) ? array_map('trim', explode("\n", str_replace("\r", "", sanitize_textarea_field($_POST['cdn_exclude_keywords'])))) : [];
        update_option('flying_images_cdn_exclude_keywords', $keywords);
    }

    $enable_cdn = get_option('flying_images_enable_cdn');

    $cdn_exclude_keywords = get_option('flying_images_cdn_exclude_keywords');
    $cdn_exclude_keywords = implode("\n", $cdn_exclude_keywords);
    $cdn_exclude_keywords = esc_textarea($cdn_exclude_keywords);

    ?>
    
    <form method="POST">
        <?php wp_nonce_field('flying-images', 'flying-images-settings-form'); ?>
        <table class="form-table" role="presentation">
        <tbody>
            <tr>
                <th scope="row"><label>Enable CDN</label></th>
                <td>
                    <input name="enable_cdn" type="checkbox" value="1" <?php if ($enable_cdn) {echo "checked";} ?>>
                    <p class="description">Use <a href="https://statically.io" target="_blank">Statically</a> CDN to deliver images</p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label>Exclude Keywords</label></th>
                <td>
                    <textarea name="cdn_exclude_keywords" rows="4" cols="50"><?php echo $cdn_exclude_keywords; ?></textarea>
                    <p class="description">The list of keywords that should be excluded from adding CDN. Add keywords in new lines</p>
                </td>
            </tr>
        </tbody>
        </table>
        <p class="submit">
            <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
            <a href="https://statically.io/purge" target="_blank" class="button">Purge files from CDN</a>
        </p>
    </form>
<?php
}