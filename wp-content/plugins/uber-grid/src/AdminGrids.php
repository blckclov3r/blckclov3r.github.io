<?php


namespace PfhubPortfolio;


class AdminGrids
{
    public static function load_portfolio_page()
    {
        $task = isset($_GET["task"]) ? sanitize_text_field($_GET["task"]) : '';
        $id = isset($_GET["id"]) ? intval($_GET['id']) : 0;
        switch ($task) {
            case 'edit_cat':
                if ($id) {
                    self::edit_portfolio($id);
                }
                break;
            case 'save':
                if ($id) {
                    self::save_portfolio_data($id);
                }
                break;
            case 'apply':
                $a = isset($_REQUEST['pfhub_portfolio_apply_portfolio_nonce']);
                $b = wp_verify_nonce($_REQUEST['pfhub_portfolio_apply_portfolio_nonce'], 'apply_portfolio_' . $id);
                $c = wp_verify_nonce($_REQUEST['pfhub_portfolio_apply_portfolio_nonce'], 'remove_project_' . (isset($_GET['removeslide']) ? absint($_GET['removeslide']) : ''));

                if (!(($b || $c) && $a)) {
                    wp_die('Security check failure');
                }
                if ($id) {
                    self::save_portfolio_data($id);
                    self::edit_portfolio($id);
                }
                break;
            default:
                self::show_portfolios_page();
                break;
        }
    }

    public static function show_portfolios_page()
    {

        global $wpdb;
        if (isset($_COOKIE['deleted'])) {
            if ($_COOKIE['deleted'] == 'success') {
                ?>
                <div class="updated"><p><strong><?php _e('Item Deleted.'); ?></strong></p></div>
                <?php
            } elseif ($_COOKIE["deleted"] == 'fail') {
                ?>
                <div id="message" class="error"><p>Portfolio Not Deleted</p></div>
            <?php }
        }

        if (isset($_POST['search_events_by_title'])) {
            $_POST['search_events_by_title'] = esc_html(stripslashes($_POST['search_events_by_title']));
        }
        if (isset($_POST['asc_or_desc'])) {
            $_POST['asc_or_desc'] = sanitize_text_field($_POST['asc_or_desc']);
        }
        if (isset($_POST['order_by'])) {
            $_POST['order_by'] = sanitize_text_field($_POST['order_by']);
        }
        $where = '';
        $sort["custom_style"] = "manage-column column-autor sortable desc";
        $sort["default_style"] = "manage-column column-autor sortable desc";
        $sort["sortid_by"] = 'id';
        $sort["1_or_2"] = 1;
        $order = '';

        if (isset($_POST['page_number'])) {

            if ($_POST['asc_or_desc']) {
                $sort["sortid_by"] = sanitize_text_field($_POST['order_by']);
                if ($_POST['asc_or_desc'] == 1) {
                    $sort["custom_style"] = "manage-column column-title sorted asc";
                    $sort["1_or_2"] = "2";
                    $order = "ORDER BY " . $sort["sortid_by"] . " ASC";
                } else {
                    $sort["custom_style"] = "manage-column column-title sorted desc";
                    $sort["1_or_2"] = "1";
                    $order = "ORDER BY " . $sort["sortid_by"] . " DESC";
                }
            }
            if ($_POST['page_number']) {
                $limit = (floatval($_POST['page_number']) - 1) * 20;
            } else {
                $limit = 0;
            }
        } else {
            $limit = 0;
        }
        if (isset($_POST['search_events_by_title'])) {
            $search_tag = esc_html(stripslashes($_POST['search_events_by_title']));
        } else {
            $search_tag = "";
        }

        if (isset($_GET["catid"])) {
            $cat_id = absint($_GET["catid"]);
        } else {
            if (isset($_POST['cat_search'])) {
                $cat_id = absint($_POST['cat_search']);
            } else {

                $cat_id = 0;
            }
        }

        if ($search_tag) {
            $where = " WHERE name LIKE '%" . sanitize_text_field($search_tag) . "%' ";
        }
        if ($where) {
            if ($cat_id) {
                $where .= " AND sl_width=" . $cat_id;
            }

        } else {
            if ($cat_id) {
                $where .= " WHERE sl_width=" . $cat_id;
            }

        }

        $cat_row_query = "SELECT id,name FROM " . $wpdb->prefix . "pfhub_portfolio_grids WHERE sl_width=0";

        // get the total number of records
        $query = "SELECT COUNT(*) FROM " . $wpdb->prefix . "pfhub_portfolio_grids" . $where;

        $total = $wpdb->get_var($query);
        $pageNav['total'] = $total;
        $pageNav['limit'] = $limit / 20 + 1;

        if ($cat_id) {
            $query = "SELECT  a.* ,  COUNT(b.id) AS count, g.par_name AS par_name FROM " . $wpdb->prefix . "pfhub_portfolio_grids  AS a LEFT JOIN " . $wpdb->prefix . "pfhub_portfolio_grids AS b ON a.id = b.sl_width LEFT JOIN (SELECT  " . $wpdb->prefix . "pfhub_portfolio_grids.ordering as ordering," . $wpdb->prefix . "pfhub_portfolio_grids.id AS id, COUNT( " . $wpdb->prefix . "pfhub_portfolio_images.grid_id ) AS prod_count
FROM " . $wpdb->prefix . "pfhub_portfolio_images, " . $wpdb->prefix . "pfhub_portfolio_grids
WHERE " . $wpdb->prefix . "pfhub_portfolio_images.grid_id = " . $wpdb->prefix . "pfhub_portfolio_grids.id
GROUP BY " . $wpdb->prefix . "pfhub_portfolio_images.grid_id) AS c ON c.id = a.id LEFT JOIN
(SELECT " . $wpdb->prefix . "pfhub_portfolio_grids.name AS par_name," . $wpdb->prefix . "pfhub_portfolio_grids.id FROM " . $wpdb->prefix . "pfhub_portfolio_grids) AS g
 ON a.sl_width=g.id WHERE  a.name LIKE '%" . $search_tag . "%' group by a.id " . $order . " LIMIT " . $limit . ",20";

        } else {
            $query = "SELECT  a.* ,  COUNT(b.id) AS count, g.par_name AS par_name FROM " . $wpdb->prefix . "pfhub_portfolio_grids  AS a LEFT JOIN " . $wpdb->prefix . "pfhub_portfolio_grids AS b ON a.id = b.sl_width LEFT JOIN (SELECT  " . $wpdb->prefix . "pfhub_portfolio_grids.ordering as ordering," . $wpdb->prefix . "pfhub_portfolio_grids.id AS id, COUNT( " . $wpdb->prefix . "pfhub_portfolio_images.grid_id ) AS prod_count
FROM " . $wpdb->prefix . "pfhub_portfolio_images, " . $wpdb->prefix . "pfhub_portfolio_grids
WHERE " . $wpdb->prefix . "pfhub_portfolio_images.grid_id = " . $wpdb->prefix . "pfhub_portfolio_grids.id
GROUP BY " . $wpdb->prefix . "pfhub_portfolio_images.grid_id) AS c ON c.id = a.id LEFT JOIN
(SELECT " . $wpdb->prefix . "pfhub_portfolio_grids.name AS par_name," . $wpdb->prefix . "pfhub_portfolio_grids.id FROM " . $wpdb->prefix . "pfhub_portfolio_grids) AS g
 ON a.sl_width=g.id WHERE a.name LIKE '%" . $search_tag . "%'  group by a.id " . $order . " LIMIT " . $limit . ",20";
        }

        $rows = $wpdb->get_results($query);
        global $glob_ordering_in_cat;
        if (isset($sort["sortid_by"])) {
            if ($sort["sortid_by"] == 'ordering') {
                if ($_POST['asc_or_desc'] == 1) {
                    $glob_ordering_in_cat = " ORDER BY ordering ASC";
                } else {
                    $glob_ordering_in_cat = " ORDER BY ordering DESC";
                }
            }
        }
        $rows = \PfhubPortfolio\Helpers\GridHelper::catTree($rows);
        $query = "SELECT  " . $wpdb->prefix . "pfhub_portfolio_grids.ordering," . $wpdb->prefix . "pfhub_portfolio_grids.id, COUNT( " . $wpdb->prefix . "pfhub_portfolio_images.grid_id ) AS prod_count
FROM " . $wpdb->prefix . "pfhub_portfolio_images, " . $wpdb->prefix . "pfhub_portfolio_grids
WHERE " . $wpdb->prefix . "pfhub_portfolio_images.grid_id = " . $wpdb->prefix . "pfhub_portfolio_grids.id
GROUP BY " . $wpdb->prefix . "pfhub_portfolio_images.grid_id ";
        $prod_rows = $wpdb->get_results($query);

        foreach ($rows as $row) {
            foreach ($prod_rows as $row_1) {
                if ($row->id == $row_1->id) {
                    $row->ordering = $row_1->ordering;
                    $row->prod_count = $row_1->prod_count;
                }
            }
        }

        require_once(PFHUB_PORTFOLIO_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'admin-grids.php');
    }

    public static function edit_portfolio($id)
    {
        global $wpdb;

        if (isset($_GET["removeslide"])) {
            if (absint($_GET['removeslide']) != $_GET['removeslide'] || !absint($_GET['removeslide'])) {
                wp_die(__('"removeslide" parameter is required to be not negative integer', 'pfhub_portfolio'));
            } else {
                $project_id = absint($_GET["removeslide"]);
                $wpdb->query("DELETE FROM " . $wpdb->prefix . "pfhub_portfolio_images  WHERE id = " . $project_id);
            }
        }

        $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "pfhub_portfolio_grids WHERE id= %d", $id);
        $row = $wpdb->get_row($query);
        if (!isset($row->grid_view_type)) {
            return 'id not found';
        }
        $images = explode(";;;", $row->grid_view_type);
        $par = explode('	', $row->param);
        $count_ord = count($images);
        $cat_row = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "pfhub_portfolio_grids WHERE id!=" . $id . " and sl_width=0");
        $cat_row = \PfhubPortfolio\Helpers\GridHelper::catTree($cat_row);
        $query = $wpdb->prepare("SELECT name,ordering FROM " . $wpdb->prefix . "pfhub_portfolio_grids WHERE sl_width=%d  ORDER BY `ordering` ", $row->sl_width);
        $ord_elem = $wpdb->get_results($query);

        $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "pfhub_portfolio_images where grid_id = %d order by ordering ASC  ", $row->id);
        $rowim = $wpdb->get_results($query);
        $query = "SELECT * FROM " . $wpdb->prefix . "pfhub_portfolio_grids order by id ASC";
        $rowsld = $wpdb->get_results($query);

        require_once(PFHUB_PORTFOLIO_TEMPLATES_PATH . DIRECTORY_SEPARATOR  . 'admin-grid-images.php');
    }

    public static function save_portfolio_data($id)
    {
        global $wpdb;
        if (!(isset($_POST['sl_width']) && isset($_POST["name"]))) {
            return;
        }
        $id = absint($id);
        $name = sanitize_text_field(wp_unslash($_POST["name"]));
        $sl_width = absint($_POST["sl_width"]);
        $sl_height = absint($_POST["sl_height"]);
        $pause_on_hover = in_array($_POST["pause_on_hover"], array(
            'on',
            'off'
        )) ? $_POST["pause_on_hover"] : 'on';
        $autoslide = in_array($_POST["autoslide"], array('on', 'off')) ? $_POST["autoslide"] : 'on';
        $portfolio_effects_list = absint($_POST["portfolio_effects_list"]);
        $description = absint($_POST["sl_pausetime"]);
        $sl_changespeed = absint($_POST["sl_changespeed"]);
        $all_categories = wp_unslash(str_replace(' ', '_', sanitize_text_field($_POST["allCategories"])));
        $pfhub_portfolio_show_sorting = in_array($_POST["pfhub_portfolio_show_sorting"], array(
            'on',
            'off'
        )) ? $_POST["pfhub_portfolio_show_sorting"] : 'off';
        $pfhub_portfolio_show_filtering = in_array($_POST["pfhub_portfolio_show_filtering"], array(
            'on',
            'off'
        )) ? $_POST["pfhub_portfolio_show_filtering"] : 'off';
        $show_loading = in_array($_POST["show_loading"], array(
            'on',
            'off'
        )) ? $_POST["show_loading"] : 'on';
        $loading_icon_type = absint($_POST["loading_icon_type"]);

        $wpdb->update(
            $wpdb->prefix . "pfhub_portfolio_grids",
            array(
                'name' => $name,
                'sl_width' => $sl_width,
                'sl_height' => $sl_height,
                'pause_on_hover' => $pause_on_hover,
                'autoslide' => $autoslide,
                'grid_view_type' => $portfolio_effects_list,
                'description' => $description,
                'param' => $sl_changespeed,
                'ordering' => 1,
                'categories' => $all_categories,
                'pfhub_portfolio_show_sorting' => $pfhub_portfolio_show_sorting,
                'pfhub_portfolio_show_filtering' => $pfhub_portfolio_show_filtering,
                'show_loading' => $show_loading,
                'loading_icon_type' => $loading_icon_type,
            ),
            array('id' => $id)
        );

        update_option('pfhub_portfolio_disable_right_click', sanitize_text_field($_POST['disable_right_click']));

        $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "pfhub_portfolio_grids WHERE id = %d", $id);
        $row = $wpdb->get_row($query);

        if (isset($_POST['pfhub_portfolio_admin_image_hover_preview'])) {
            $img_preview = sanitize_text_field($_POST['pfhub_portfolio_admin_image_hover_preview']);
            update_option('pfhub_portfolio_admin_image_hover_preview', $img_preview);
        } else {
            update_option('pfhub_portfolio_admin_image_hover_preview', 'off');
        }

        if (isset($_POST['changedvalues']) && $_POST['changedvalues'] != '') {
            $changedValues = preg_replace('#[^0-9,]+#', '', $_POST['changedvalues']);
            $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "pfhub_portfolio_images where grid_id = %d AND id in (" . $changedValues . ") order by id ASC", $row->id);
            $rowim = $wpdb->get_results($query);
            foreach ($rowim as $key => $rowimages) {
                $imgDescription = wp_kses_post(wp_unslash($_POST["im_description" . $rowimages->id . ""]));
                $imgTitle = wp_kses_post(wp_unslash($_POST["titleimage" . $rowimages->id . ""]));
                $orderBy = absint($_POST["order_by_" . $rowimages->id]);
                $link_target = sanitize_text_field($_POST["sl_link_target" . $rowimages->id]);
                $url = sanitize_text_field($_POST["media_url" . $rowimages->id]);
                $image_url = sanitize_text_field($_POST["imagess" . $rowimages->id]);

                $wpdb->update(
                    $wpdb->prefix . "pfhub_portfolio_images",
                    array(
                        'ordering' => $orderBy,
                        'link_target' => $link_target,
                        'media_url' => $url,
                        'name' => $imgTitle,
                        'description' => $imgDescription,
                        'image_url' => $image_url,
                    ),
                    array('id' => absint($rowimages->id))
                );
            }
        }

        if ($_POST["imagess"] != '') {
            $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "pfhub_portfolio_images where grid_id = %d order by id ASC", $row->id);
            $rowim = $wpdb->get_results($query);
            foreach ($rowim as $key => $rowimages) {
                $orderingplus = $rowimages->ordering + 1;
                $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "pfhub_portfolio_images SET  ordering = %d  WHERE ID = %d ", $orderingplus, $rowimages->id));
            }

            $table_name = $wpdb->prefix . "pfhub_portfolio_images";
            $imagesnewuploader = explode(";;;", sanitize_text_field($_POST["imagess"]));

            array_pop($imagesnewuploader);

            foreach ($imagesnewuploader as $imagesnewupload) {
                $wpdb->insert(
                    $table_name,
                    array(
                        'name' => '',
                        'grid_id' => $row->id,
                        'description' => '',
                        'image_url' => $imagesnewupload . ';',
                        'media_url' => '',
                        'ordering' => '0',
                        'published' => 2,
                        'published_in_sl_width' => '1',
                    ),
                    array('%s', '%d', '%s', '%s', '%s', '%s', '%d', '%s')
                );
            }

            $query = $wpdb->prepare("SELECT * FROM " . $table_name . " WHERE grid_id=%d ORDER BY ordering", $id);
            $portfolio_images = $wpdb->get_results($query);
            $i = 0;
            foreach ($portfolio_images as $portfolio_image) {
                $wpdb->update(
                    $table_name,
                    array('ordering' => $i),
                    array('id' => $portfolio_image->id)
                );
                $i++;
            }
        }

        ?>
        <div class="updated"><p><strong><?php _e('Item Saved', 'pfhub_portfolio'); ?></strong></p></div>
        <?php

        return true;
    }
}
