<?php
function flying_pages_settings() {
    
    // Validate nonce
    if(isset($_POST['submit']) && !wp_verify_nonce($_POST['flying_pages_settings_form'], 'flying_pages')) {
        echo '<div class="notice notice-error"><p>Nonce verification failed</p></div>';
        exit;
    }

    // Update config in database after form submission
    if (isset($_POST['submit'])) {
        
        $keywords = trim($_POST['ignore_keywords']) ? array_map('trim', explode("\n", str_replace("\r", "", sanitize_textarea_field($_POST['ignore_keywords'])))) : [];

        update_option('flying_pages_config_ignore_keywords', $keywords);
        update_option('flying_pages_config_delay', $_POST['mouse_hover_only'] ? sanitize_text_field($_POST['mouse_hover_only']) 
                                                                              : sanitize_text_field($_POST['delay']));
        update_option('flying_pages_config_max_rps', sanitize_text_field($_POST['max_rps']));
        update_option('flying_pages_config_hover_delay', sanitize_text_field($_POST['hover_delay']));
        update_option('flying_pages_config_disable_on_login', sanitize_text_field($_POST['disable_on_login']));

        echo '<div class="notice notice-success is-dismissible"><p>Settings have been saved! Please clear cache if you\'re using a cache plugin</p></div>';
    }

    $ignore_keywords = get_option('flying_pages_config_ignore_keywords');
    $ignore_keywords = implode("\n", $ignore_keywords);
    $ignore_keywords = esc_textarea($ignore_keywords);
    
    $delay = esc_attr(get_option('flying_pages_config_delay'));
    $max_rps =  esc_attr(get_option('flying_pages_config_max_rps'));
    $hover_delay =  esc_attr(get_option('flying_pages_config_hover_delay'));
    $disable_on_login =  esc_attr(get_option('flying_pages_config_disable_on_login'));
?>
<form method="POST">
    <?php wp_nonce_field( 'flying_pages', 'flying_pages_settings_form' ); ?>
    <table class="form-table" role="presentation">
    <tbody>
        <tr>
            <th scope="row"><label>Delay to start preloading</label></th>
            <td>
                <select name="delay" value="<?php echo $delay; ?>">
                    <option value="0" <?php if ($delay == 0) echo 'selected'; ?>>0 second (recommended)</option>
                    <option value="1" <?php if ($delay == 1) echo 'selected'; ?>>1 second</option>
                    <option value="2" <?php if ($delay == 2) echo 'selected'; ?>>2 seconds</option>
                    <option value="3" <?php if ($delay == 3) echo 'selected'; ?>>3 seconds</option>
                    <option value="5" <?php if ($delay == 5) echo 'selected'; ?>>5 seconds</option>
                    <option value="10" <?php if ($delay == 10) echo 'selected'; ?>>10 seconds</option>
                    <option value="15" <?php if ($delay == 15) echo 'selected'; ?>>15 seconds</option>
                    <option value="20" <?php if ($delay == 20) echo 'selected'; ?>>20 seconds</option>
                    <option value="30" <?php if ($delay == 30) echo 'selected'; ?>>30 seconds</option>
                </select>
                <p class="description">Delay to start preloading links in the viewport after browser becomes idle <br/>(hovering links doesn't have any delay)</p>
            <td>
        </tr>
        <tr> 
            <th scope="row"><label>Max requests per second</label></th>
            <td>
                <select name="max_rps" value="<?php echo $max_rps; ?>">
                    <option value="1" <?php if ($max_rps == 1) echo 'selected'; ?>>1 request</option>
                    <option value="2" <?php if ($max_rps == 2) echo 'selected'; ?>>2 requests</option>
                    <option value="3" <?php if ($max_rps == 3) echo 'selected'; ?>>3 requests</option>
                    <option value="5" <?php if ($max_rps == 5) echo 'selected'; ?>>5 requests</option>
                    <option value="10" <?php if ($max_rps == 10) echo 'selected'; ?>>10 requests</option>
                    <option value="0" <?php if ($max_rps == 0) echo 'selected'; ?>>Unlimited</option>
                </select>
                <p class="description">Maximum requests browser should send to the server per second</p>
            <td>
        </tr>
        <tr>
            <th scope="row"><label>Preload only on mouse hover</label></th>
            <td>
                <input name="mouse_hover_only" type="checkbox" value="3600" <?php if($delay == 3600) echo "checked"; ?>>
                <p class="description">Start preloading links only on mouse hover</p>
            </td>
        </tr>
        <tr>
            <th scope="row"><label>Mouse hover delay</label></th>
            <td>
                <select name="hover_delay" value="<?php echo $hover_delay; ?>">
                    <option value="0" <?php if ($hover_delay == 0) echo 'selected'; ?>>0 ms</option>
                    <option value="50" <?php if ($hover_delay == 50) echo 'selected'; ?>>50 ms</option>
                    <option value="100" <?php if ($hover_delay == 100) echo 'selected'; ?>>100 ms</option>
                    <option value="150" <?php if ($hover_delay == 150) echo 'selected'; ?>>150 ms</option>
                    <option value="200" <?php if ($hover_delay == 200) echo 'selected'; ?>>200 ms</option>
                    <option value="500" <?php if ($hover_delay == 500) echo 'selected'; ?>>500 ms</option>
                    <option value="1000" <?php if ($hover_delay == 1000) echo 'selected'; ?>>1 second</option>
                </select>
                <p class="description">Delay to start preloading links on mouse hover</p>
            <td>
        </tr>
        <tr>
            <th scope="row"><label>Ignore keywords</label></th>
            <td>
                <textarea name="ignore_keywords" rows="5"><?php echo $ignore_keywords; ?></textarea>
                <p class="description">The list of keywords that should be ignored from preloading</p>
            </td>
        </tr>
        <tr>
            <th scope="row"><label>Disable for logged in admins</label></th>
            <td>
                <input name="disable_on_login" type="checkbox" value="1" <?php if($disable_on_login) echo "checked"; ?>>
                <p class="description">Helps to reduce server load since most of the cache plugins do not serve cached pages when logged in</p>
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