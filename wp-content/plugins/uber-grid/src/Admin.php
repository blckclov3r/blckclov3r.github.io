<?php


namespace PfhubPortfolio;


class Admin
{
    public $pages = array();

    public function __construct()
    {
        add_action('admin_menu', array($this, 'admin_menu'));
        add_action('wp_loaded', array($this, 'wp_loaded'));
        add_action('admin_enqueue_scripts', array($this, 'admin_styles'));
        add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
    }

    public function admin_styles($hook)
    {
        if (in_array($hook, $GLOBALS['pfhub_portfolio']->admin->pages)) {
            wp_enqueue_style("admin_css", PFHUB_PORTFOLIO_PLUGIN_URL . "/assets/style/admin.style.css", false);
            wp_enqueue_style("jquery_ui", PFHUB_PORTFOLIO_PLUGIN_URL . "/assets/style/jquery-ui.css", false);
            wp_enqueue_style("simple_slider_css", PFHUB_PORTFOLIO_PLUGIN_URL . "/assets/style/simple-slider.css", false);
        }
    }

    public function admin_scripts($hook)
    {
        $ajax_url = admin_url("admin-ajax.php");
        if (in_array($hook, $GLOBALS['pfhub_portfolio']->admin->pages)) {
            wp_enqueue_media();
            wp_enqueue_script("pfhub_portfolio_admin_js", PFHUB_PORTFOLIO_PLUGIN_URL . "/assets/js/admin.js", false);
            wp_enqueue_script("jquery-ui-core");
            wp_enqueue_script("simple_slider_js", PFHUB_PORTFOLIO_PLUGIN_URL . '/assets/js/simple-slider.js', false);
            wp_enqueue_script('param_block2', PFHUB_PORTFOLIO_PLUGIN_URL . "/assets/js/jscolor.js");
            wp_localize_script('pfhub_portfolio_admin_js', 'ajaxUrl', $ajax_url);
        }
        $edit_pages = array('post.php', 'post-new.php');
        if (in_array($hook, $edit_pages)) {
            wp_enqueue_script("pfhub_portfolio_add_shortcode", PFHUB_PORTFOLIO_PLUGIN_URL . "/assets/js/shortcode.js", false);
            wp_localize_script('pfhub_portfolio_add_shortcode', 'ajax_object_shortecode', $ajax_url);
            wp_localize_script('pfhub_portfolio_add_shortcode', 'pfhub_portfolio_add_shortecode_nonce', wp_create_nonce('pfhub_portfolio_add_shortcode'));
        }
    }

    /**
     * Prints Portfolio Menu
     */
    public function admin_menu()
    {
        $this->pages[] = add_menu_page(__('NavyPlugins Portfolio Gallery', 'pfhub_portfolio'), __('Portfolio Gallery', 'pfhub_portfolio'), 'delete_pages', 'portfolios_pfhub_portfolio', array(
            AdminGrids::class,
            'load_portfolio_page'
        ), 'dashicons-layout');
        $this->pages[] = add_submenu_page('portfolios_pfhub_portfolio', __('Portfolios', 'pfhub_portfolio'), __('Portfolios', 'pfhub_portfolio'), 'delete_pages', 'portfolios_pfhub_portfolio', array(
            AdminGrids::class,
            'load_portfolio_page'
        ));

        $this->pages[] = add_submenu_page('portfolios_pfhub_portfolio', __('Settings', 'pfhub_portfolio'), __('Settings', 'pfhub_portfolio'), 'delete_pages', 'Options_portfolio_styles', array(
            $this,
            'settings'
        ));
        $this->pages[] = add_submenu_page('portfolios_pfhub_portfolio', __('Lightbox Settings', 'pfhub_portfolio'), __('Lightbox Settings', 'pfhub_portfolio'), 'delete_pages', 'Options_portfolio_lightbox_styles', array(
            AdminLightboxSettings::class,
            'load_page'
        ));
    }

    public function settings()
    {
        require(PFHUB_PORTFOLIO_TEMPLATES_PATH . DIRECTORY_SEPARATOR  . 'admin-settings.php');
    }

    public function wp_loaded()
    {
        if (isset($_GET['page']) && $_GET['page'] == 'portfolios_pfhub_portfolio') {
            if (isset($_GET['task'])) {
                $task = sanitize_text_field($_GET['task']);
                switch ($task) {
                    case 'add_portfolio':
                        $this->add_portfolio();
                        break;
                    case 'portfolio_video':
                        $this->add_video();
                        break;
                    case 'portfolio_add_thumb_video':
                        $this->add_thumb_video();
                        break;
                    case 'portfolio_video_edit':
                        $this->edit_video();
                        break;
                    case 'remove_portfolio':
                        $this->remove_portfolio();
                        break;
                    case 'duplicate_portfolio_gallery':
                        $this->duplicate_portfolio();
                        break;
                }
            }

        }
    }

    /**
     * Add New Portfolio
     */
    public static function add_portfolio()
    {
        if (!isset($_REQUEST['pfhub_portfolio_add_portfolio_nonce']) || !wp_verify_nonce($_REQUEST['pfhub_portfolio_add_portfolio_nonce'], 'add_new_portfolio')) {
            wp_die('Security check failure.');
        }
        global $wpdb;
        $table_name = $wpdb->prefix . "pfhub_portfolio_grids";
        $wpdb->insert(
            $table_name,
            array(
                'name' => 'New portfolio',
                'sl_height' => '375',
                'sl_width' => '600',
                'pause_on_hover' => 'on',
                'grid_view_type' => '2',
                'description' => '4000',
                'param' => '1000',
                'sl_position' => 'off',
                'ordering' => '1',
                'published' => '300',
                'categories' => 'My First Category,My Second Category,My Third Category,',
                'pfhub_portfolio_show_sorting' => 'off',
                'pfhub_portfolio_show_filtering' => 'off',
            )
        );

        $apply_portfolio_safe_link = wp_nonce_url(
            'admin.php?page=portfolios_pfhub_portfolio&id=' . $wpdb->insert_id . '&task=apply',
            'apply_portfolio_' . $wpdb->insert_id,
            'pfhub_portfolio_apply_portfolio_nonce'
        );

        $apply_portfolio_safe_link = htmlspecialchars_decode($apply_portfolio_safe_link);

        header('Location: ' . $apply_portfolio_safe_link);
    }

    /**
     * Insert portfolio video
     */
    public static function add_video()
    {
        if (!isset($_GET["id"]) || !absint($_GET['id']) || absint($_GET['id']) != $_GET['id']) {
            wp_die('"id" parameter is required to be not negative integer');
        }
        $id = absint($_GET["id"]);
        if (!isset($_REQUEST['portfolio_add_video_nonce']) || !wp_verify_nonce($_REQUEST['portfolio_add_video_nonce'], 'portfolio_add_video_nonce')) {
            wp_die('brr');
        }
        global $wpdb;
        if (isset($_POST["pfhub_portfolio_add_video_input"]) && $_POST["pfhub_portfolio_add_video_input"] != '') {
            $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "pfhub_portfolio_grids WHERE id= %d", $id);
            $row = $wpdb->get_row($query);
            $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "pfhub_portfolio_images where grid_id = %d ", $row->id);
            $rowplusorder = $wpdb->get_results($query);

            foreach ($rowplusorder as $key => $rowplusorders) {

                if ($rowplusorders->ordering == 0) {
                    $rowplusorderspl = 1;
                    $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "pfhub_portfolio_images SET ordering = %d WHERE id = %d ", $rowplusorderspl, $rowplusorders->id));
                } else {
                    $rowplusorderspl = $rowplusorders->ordering + 1;
                    $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "pfhub_portfolio_images SET ordering = %d WHERE id = %d ", $rowplusorderspl, $rowplusorders->id));
                }

            }
            $_POST["pfhub_portfolio_add_video_input"] .= ";";
            $_POST["show_title"] = sanitize_text_field($_POST["show_title"]);
            $_POST["show_description"] = wp_kses_post($_POST["show_description"]);
            $_POST["pfhub_portfolio_add_video_input"] = explode(';', sanitize_text_field($_POST["pfhub_portfolio_add_video_input"]));
            $_POST["pfhub_portfolio_add_video_input"] = array_map('esc_url', $_POST["pfhub_portfolio_add_video_input"]);
            $_POST["pfhub_portfolio_add_video_input"] = array_map('htmlspecialchars_decode', $_POST["pfhub_portfolio_add_video_input"]);
            $_POST["pfhub_portfolio_add_video_input"] = implode(';', $_POST["pfhub_portfolio_add_video_input"]);

            $_POST["show_url"] = esc_url($_POST["show_url"]);
            $table_name = $wpdb->prefix . "pfhub_portfolio_images";
            $wpdb->insert(
                $table_name,
                array(
                    'name' => sanitize_text_field($_POST["show_title"]),
                    'grid_id' => $id,
                    'description' => wp_kses_post($_POST["show_description"]),
                    'image_url' => sanitize_text_field($_POST["pfhub_portfolio_add_video_input"]),
                    'media_url' => esc_url_raw($_POST["show_url"]),
                    'media_type' => 'video',
                    'link_target' => 'on',
                    'ordering' => '0',
                    'published' => '1',
                    'published_in_sl_width' => '1',
                    'category' => '',
                )
            );
        }
        $apply_portfolio_safe_link = wp_nonce_url(
            'admin.php?page=portfolios_pfhub_portfolio&id=' . $id . '&task=apply',
            'apply_portfolio_' . $id,
            'pfhub_portfolio_apply_portfolio_nonce'
        );
        $apply_portfolio_safe_link = htmlspecialchars_decode($apply_portfolio_safe_link);
        header('Location: ' . $apply_portfolio_safe_link);
    }

    /**
     * Add project thumbnail video
     */
    public static function add_thumb_video()
    {
        global $wpdb;
        if (!isset($_GET["id"]) || !absint($_GET['id']) || absint($_GET['id']) != $_GET['id']) {
            wp_die('"id" parameter is required to be not negative integer');
        }
        $id = absint($_GET["id"]);
        if (isset($_POST["pfhub_portfolio_add_video_input_thumb"]) && $_POST["pfhub_portfolio_add_video_input_thumb"] != '') {
            if (!isset($_REQUEST['add_thumb_video_nonce']) || !wp_verify_nonce($_REQUEST['add_thumb_video_nonce'], 'add_thumb_video_nonce')) {
                wp_die('Security check failure');
            }
            if (isset($_GET['thumb_parent']) || $_GET['thumb_parent'] != null) {
                $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "pfhub_portfolio_grids WHERE id= %d", $id);
                $row = $wpdb->get_row($query);
                $project_id = absint($_GET['thumb_parent']);
                $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "pfhub_portfolio_images where grid_id = %d and id = %d", $row->id, $project_id);
                $get_proj_image = $wpdb->get_row($query);
                $project_id = absint($_GET['thumb_parent']);
                $get_proj_image->image_url .= sanitize_text_field($_POST["pfhub_portfolio_add_video_input_thumb"]) . ";";
                $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "pfhub_portfolio_images SET image_url = '%s' where grid_id = %s and id = %d", $get_proj_image->image_url, $row->id, $project_id));
                $apply_portfolio_safe_link = wp_nonce_url(
                    'admin.php?page=portfolios_pfhub_portfolio&id=' . $id . '&task=apply',
                    'apply_portfolio_' . $id,
                    'pfhub_portfolio_apply_portfolio_nonce'
                );
                $apply_portfolio_safe_link = htmlspecialchars_decode($apply_portfolio_safe_link);
                header('Location: ' . $apply_portfolio_safe_link);
            }
        }
    }

    /**
     * Edit video
     */
    public static function edit_video()
    {
        $thumb = sanitize_text_field($_GET["thumb"]);
        if (!isset($_GET["id"]) || !absint($_GET['id']) || absint($_GET['id']) != $_GET['id']) {
            wp_die('"id" parameter is required to be not negative integer');
        }
        $id = absint($_GET["id"]);
        if (!isset($_REQUEST['portfolio_video_edit_nonce']) || !wp_verify_nonce($_REQUEST['portfolio_video_edit_nonce'], 'edit_thumb_video_nonce' . $id . $thumb)) {
            wp_die('Security check failure');
        }
        global $wpdb;
        $grid_id = absint($_GET["grid_id"]);
        $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "pfhub_portfolio_images where grid_id = %s and id = %d", $grid_id, $id);
        $get_proj_image = $wpdb->get_row($query);
        $input_edit_video = explode(";", $get_proj_image->image_url);
        if (isset($_POST["pfhub_portfolio_edit_video_input"]) && $_POST["pfhub_portfolio_edit_video_input"] != '') {
            $input_edit_video[$thumb] = sanitize_text_field($_POST["pfhub_portfolio_edit_video_input"]);
            array_map('esc_url', $input_edit_video);
            $new_url = implode(";", $input_edit_video);
            $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "pfhub_portfolio_images SET image_url = '%s' where grid_id = %s and id = %d", $new_url, $grid_id, $id));
        }
        $apply_portfolio_safe_link = wp_nonce_url(
            'admin.php?page=portfolios_pfhub_portfolio&id=' . $grid_id . '&task=apply',
            'apply_portfolio_' . $grid_id,
            'pfhub_portfolio_apply_portfolio_nonce'
        );
        $apply_portfolio_safe_link = htmlspecialchars_decode($apply_portfolio_safe_link);
        header('Location: ' . $apply_portfolio_safe_link);
    }

    /**
     * Remove video
     */
    public static function remove_portfolio()
    {
        if (!isset($_GET["id"]) || !absint($_GET['id']) || absint($_GET['id']) != $_GET['id']) {
            wp_die('"id" parameter is required to be not negative integer');
        }
        $id = absint($_GET["id"]);
        if (!isset($_REQUEST['pfhub_portfolio_remove_portfolio_nonce']) || !wp_verify_nonce($_REQUEST['pfhub_portfolio_remove_portfolio_nonce'], 'remove_portfolio_' . $id)) {
            wp_die('Security check failure');
        }
        global $wpdb;
        $sql_remov_tag = $wpdb->prepare("DELETE FROM " . $wpdb->prefix . "pfhub_portfolio_grids WHERE id = %d", $id);
        $sql_remov_image = $wpdb->prepare("DELETE FROM " . $wpdb->prefix . "pfhub_portfolio_images WHERE grid_id = %d", $id);
        if (!$wpdb->query($sql_remov_tag)) {
            setcookie('deleted', 'fail', time() + 2);
        } else {
            $wpdb->query($sql_remov_image);
            setcookie('deleted', 'success', time() + 2);
        }
        header('Location: admin.php?page=portfolios_pfhub_portfolio');
    }

    /**
     * Duplicate Portfolio
     */
    public static function duplicate_portfolio()
    {
        if (isset($_GET['page']) && $_GET['page'] == 'portfolios_pfhub_portfolio') {
            $task = isset($_GET["task"]) ? sanitize_text_field($_GET["task"]) : '';
            if ($task && $task == 'duplicate_portfolio_gallery') {
                if (!isset($_GET["id"]) || !absint($_GET['id']) || absint($_GET['id']) != $_GET['id']) {
                    wp_die('"id" parameter is required to be not negative integer');
                }
                $id = absint($_GET["id"]);
                if (!isset($_REQUEST['pfhub_portfolio_duplicate_nonce']) || !wp_verify_nonce($_REQUEST['pfhub_portfolio_duplicate_nonce'], 'pfhub_portfolio_duplicate_nonce' . $id)) {
                    wp_die('Security check fail');
                }
                global $wpdb;
                $table_name = $wpdb->prefix . "pfhub_portfolio_grids";
                $query = $wpdb->prepare("SELECT * FROM " . $table_name . " WHERE id=%d", $id);
                $portfolio_gallery = $wpdb->get_results($query);
                $wpdb->insert(
                    $table_name,
                    array(
                        'name' => $portfolio_gallery[0]->name . ' Copy',
                        'sl_height' => $portfolio_gallery[0]->sl_height,
                        'sl_width' => $portfolio_gallery[0]->sl_width,
                        'pause_on_hover' => $portfolio_gallery[0]->pause_on_hover,
                        'grid_view_type' => $portfolio_gallery[0]->grid_view_type,
                        'description' => $portfolio_gallery[0]->description,
                        'param' => $portfolio_gallery[0]->param,
                        'sl_position' => $portfolio_gallery[0]->sl_position,
                        'ordering' => $portfolio_gallery[0]->ordering,
                        'published' => $portfolio_gallery[0]->published,
                        'categories' => $portfolio_gallery[0]->categories,
                        'pfhub_portfolio_show_sorting' => $portfolio_gallery[0]->pfhub_portfolio_show_sorting,
                        'pfhub_portfolio_show_filtering' => $portfolio_gallery[0]->pfhub_portfolio_show_filtering,
                        'autoslide' => $portfolio_gallery[0]->autoslide,
                        'show_loading' => $portfolio_gallery[0]->show_loading,
                        'loading_icon_type' => $portfolio_gallery[0]->loading_icon_type
                    )
                );
                $last_key = $wpdb->insert_id;
                $table_name = $wpdb->prefix . "pfhub_portfolio_images";
                $query = $wpdb->prepare("SELECT * FROM " . $table_name . " WHERE grid_id=%d", $id);
                $portfolios = $wpdb->get_results($query);
                $portfolios_list = "";
                foreach ($portfolios as $key => $portfolio) {
                    $new_portfolio = "('";
                    $new_portfolio .= esc_sql($portfolio->name) . "','" . $last_key . "','" . esc_sql($portfolio->description) . "','" . $portfolio->image_url . "','" .
                        $portfolio->media_url . "','" . $portfolio->media_type . "','" . $portfolio->link_target . "','" . $portfolio->ordering . "','" .
                        $portfolio->published . "','" . $portfolio->published_in_sl_width . "','" . $portfolio->category . "')";
                    $portfolios_list .= $new_portfolio . ",";
                }
                $portfolios_list = substr($portfolios_list, 0, strlen($portfolios_list) - 1);
                $query = "INSERT into " . $table_name . " (name,grid_id,description,image_url,media_url,media_type,link_target,ordering,published,published_in_sl_width,category)
					VALUES " . $portfolios_list;
                $wpdb->query($query);
                wp_redirect('admin.php?page=portfolios_pfhub_portfolio');
            }
        }
    }

}
