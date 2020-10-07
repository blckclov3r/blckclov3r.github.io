<?php


namespace PfhubPortfolio\Helpers;


class GridHelper
{

    public static function catTree($cat, $tree_problem = '', $hihiih = 1)
    {
        global $wpdb;
        global $glob_ordering_in_cat;
        static $trr_cat = array();
        if (!isset($search_tag)) {
            $search_tag = '';
        }
        if ($hihiih) {
            $trr_cat = array();
        }
        foreach ($cat as $local_cat) {
            $local_cat->name = $tree_problem . $local_cat->name;
            array_push($trr_cat, $local_cat);
            $new_cat_query = "SELECT  a.* ,  COUNT(b.id) AS count, g.par_name AS par_name FROM " . $wpdb->prefix . "pfhub_portfolio_grids  AS a LEFT JOIN " . $wpdb->prefix . "pfhub_portfolio_grids AS b ON a.id = b.sl_width LEFT JOIN (SELECT  " . $wpdb->prefix . "pfhub_portfolio_grids.ordering as ordering," . $wpdb->prefix . "pfhub_portfolio_grids.id AS id, COUNT( " . $wpdb->prefix . "pfhub_portfolio_images.grid_id ) AS prod_count
FROM " . $wpdb->prefix . "pfhub_portfolio_images, " . $wpdb->prefix . "pfhub_portfolio_grids
WHERE " . $wpdb->prefix . "pfhub_portfolio_images.grid_id = " . $wpdb->prefix . "pfhub_portfolio_grids.id
GROUP BY " . $wpdb->prefix . "pfhub_portfolio_images.grid_id) AS c ON c.id = a.id LEFT JOIN
(SELECT " . $wpdb->prefix . "pfhub_portfolio_grids.name AS par_name," . $wpdb->prefix . "pfhub_portfolio_grids.id FROM " . $wpdb->prefix . "pfhub_portfolio_grids) AS g
 ON a.sl_width=g.id WHERE a.name LIKE '%" . $search_tag . "%' AND a.sl_width=" . $local_cat->id . " group by a.id  " . $glob_ordering_in_cat;
            $new_cat = $wpdb->get_results($new_cat_query);
            \PfhubPortfolio\Helpers\GridHelper::catTree($new_cat, $tree_problem . "— ", 0);
        }

        return $trr_cat;
    }

    public static function navigationHtml($count_items, $page_number, $serch_fields = "")
    {
        ?>
        <script type="text/javascript">
            function clear_search_texts() {
                document.getElementById("serch_or_not").value = '';
            }

            function submit_href(x, y) {
                var items_county =<?php if ($count_items) {
                    if ($count_items % 20) {
                        echo ($count_items - $count_items % 20) / 20 + 1;
                    } else {
                        echo ($count_items - $count_items % 20) / 20;
                    }
                } else {
                    echo 1;
                }?>;
                if (document.getElementById("serch_or_not").value != "search") {
                    clear_search_texts();
                }
                switch (y) {
                    case 1:
                        if (x >= items_county) document.getElementById('page_number').value = items_county;

                        else
                            document.getElementById('page_number').value = x + 1
                        break;
                    case 2:
                        document.getElementById('page_number').value = items_county;
                        break;
                    case -1:
                        if (x == 1) document.getElementById('page_number').value = 1;

                        else
                            document.getElementById('page_number').value = x - 1;
                        break;
                    case -2:
                        document.getElementById('page_number').value = 1;
                        break;
                    default:
                        document.getElementById('page_number').value = 1;
                }
                document.getElementById('admin_form').submit();

            }

        </script>
        <div class="tablenav top" style="width:95%">
            <?php if ($serch_fields != "") {
                echo $serch_fields;
            }
            ?>
            <div class="tablenav-pages">
                <?php if ($count_items > 20) {

                if ($page_number == 1) {
                    $first_page = "first-page disabled";
                    $prev_page = "prev-page disabled";
                    $next_page = "next-page";
                    $last_page = "last-page";
                }
                if ($page_number >= (1 + ($count_items - $count_items % 20) / 20)) {
                    $first_page = "first-page ";
                    $prev_page = "prev-page";
                    $next_page = "next-page disabled";
                    $last_page = "last-page disabled";
                }

                ?>
                <span class="pagination-links">
	<a class="<?php echo $first_page; ?>" title="Go to the first page"
       href="javascript:submit_href(<?php echo $page_number; ?>,-2);">«</a>
	<a class="<?php echo $prev_page; ?>" title="Go to the previous page"
       href="javascript:submit_href(<?php echo $page_number; ?>,-1);">‹</a>
	<span class="paging-input">
	<span class="total-pages"><?php echo $page_number; ?></span>
	of <span class="total-pages">
	<?php echo ($count_items - $count_items % 20) / 20 + 1; ?>
	</span>
	</span>
	<a class="<?php echo $next_page ?>" title="Go to the next page"
       href="javascript:submit_href(<?php echo $page_number; ?>,1);">›</a>
	<a class="<?php echo $last_page ?>" title="Go to the last page"
       href="javascript:submit_href(<?php echo $page_number; ?>,2);">»</a>
				<?php }
                ?>
	</span>
            </div>
        </div>
        <input type="hidden" id="page_number" name="page_number" value="<?php if (isset($_POST['page_number'])) {
            echo esc_attr($_POST['page_number']);
        } else {
            echo '1';
        } ?>"/>

        <input type="hidden" id="serch_or_not" name="serch_or_not" value="<?php if (isset($_POST["serch_or_not"])) {
            echo esc_attr($_POST["serch_or_not"]);
        } ?>"/>
        <?php
    }

    public static function hasTableColumn($tableName, $columnName)
    {
        global $wpdb;
        $columns = $wpdb->get_results("SHOW COLUMNS FROM  " . $tableName, ARRAY_A);
        foreach ($columns as $column) {
            if ($column['Field'] == $columnName) {
                return true;
            }
        }

        return false;
    }

    public static function getVideoImage($url)
    {
        if (strpos($url, 'youtube') !== false || strpos($url, 'youtu') !== false) {
            $video_thumb = \PfhubPortfolio\Helpers\GridHelper::getVideoId($url);
            $video_thumb_url = $video_thumb[0];
            $thumburl = 'http://img.youtube.com/vi/' . $video_thumb_url . '/mqdefault.jpg';
        } else if (strpos($url, 'vimeo') !== false) {
            $vimeo = $url;
            $vimeo_explode = explode("/", $vimeo);
            $imgid = end($vimeo_explode);
            $hash = unserialize(wp_remote_fopen("https://vimeo.com/api/v2/video/" . $imgid . ".php"));
            $imgsrc = $hash[0]['thumbnail_large'];
            $thumburl = $imgsrc;
        }

        return $thumburl;
    }

    public static function getVideoType($url)
    {
        if (strpos($url, 'youtube') !== false || strpos($url, 'youtu') !== false) {
            if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
                return 'youtube';
            }
        } elseif (strpos($url, 'vimeo') !== false) {
            $explode = explode("/", $url);
            $end = end($explode);
            if (strlen($end) == 7 || strlen($end) == 8 || strlen($end) == 9) {
                return 'vimeo';
            }
        }

        return 'image';
    }

    public static function getVideoId($url)
    {
        if (strpos($url, 'youtube') !== false || strpos($url, 'youtu') !== false) {
            if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
                return array($match[1], 'youtube');
            }
        } else {
            $url = untrailingslashit($url);

            $vimeoid = explode("/", $url);
            $vimeoid = end($vimeoid);

            return array($vimeoid, 'vimeo');
        }
    }

    public static function adjustBrightness($hex, $steps)
    {
        $steps = max(-255, min(255, $steps));
        $hex = str_replace('#', '', $hex);
        if (strlen($hex) == 3) {
            $hex = str_repeat(substr($hex, 0, 1), 2) . str_repeat(substr($hex, 1, 1), 2) . str_repeat(substr($hex, 2, 1), 2);
        }
        $color_parts = str_split($hex, 2);
        $new_color = '';
        foreach ($color_parts as $color) {
            $color = hexdec($color); // Convert to decimal
            $color = max(0, min(255, $color + $steps)); // Adjust color
            $new_color .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
        }
        return $new_color;
    }

    public static function getImage($image_src, $image_sizes, $is_thumbnail)
    {
        $is_attachment = \PfhubPortfolio\Helpers\GridHelper::getImageId($image_src);

        $is_readable = is_readable($image_src);
        if ($is_readable) {
            $img_sizes = getimagesize($image_src);
            $img_height = $img_sizes[1];
        } else {
            $img_height = null;
        }
        if (is_object($image_sizes)) {
            // Closures are currently implemented as objects
            $image_sizes = array($image_sizes, '');
        }
        if (!$is_attachment) {
            $image_url = $image_src;
        } elseif (is_string($image_sizes)) {
            $attachment_id = \PfhubPortfolio\Helpers\GridHelper::getImageId($image_src);
            $natural_img_width = explode(',', wp_get_attachment_image_sizes($attachment_id, 'full'));
            $natural_img_width = $natural_img_width[1];
            $natural_img_width = str_replace(' ', '', $natural_img_width);
            $natural_img_width = intval(str_replace('px', '', $natural_img_width));
            if ($img_height == null || $img_height >= $natural_img_width) {
                $image_url = wp_get_attachment_image_url($attachment_id, 'large');
            } else {
                $image_url = wp_get_attachment_image_url($attachment_id, 'medium');
            }
        } else {
            $attachment_id = \PfhubPortfolio\Helpers\GridHelper::getImageId($image_src);
            $natural_img_width = explode(',', wp_get_attachment_image_sizes($attachment_id, 'full'));
            $natural_img_width = $natural_img_width[1];
            $natural_img_width = str_replace(' ', '', $natural_img_width);
            $natural_img_width = intval(str_replace('px', '', $natural_img_width));
            if ($is_thumbnail) {
                $image_url = wp_get_attachment_image_url($attachment_id, 'thumbnail');
            } elseif ($image_sizes[0] <= 300 || $image_sizes[0] == '') {
                if ($img_height == null || $img_height >= $natural_img_width) {
                    $image_url = wp_get_attachment_image_url($attachment_id, 'large');
                } else {
                    $image_url = wp_get_attachment_image_url($attachment_id, 'medium');
                }
            } elseif ($image_sizes[0] <= 700) {
                $image_url = wp_get_attachment_image_url($attachment_id, 'large');
            } elseif ($image_sizes[0] >= $natural_img_width) {
                $image_url = wp_get_attachment_image_url($attachment_id, 'full');
            } else {
                $image_url = wp_get_attachment_image_url($attachment_id, $image_sizes);
            }
        }

        return $image_url;
    }

    public static function getImageId($url)
    {
        global $wpdb;

        return $wpdb->get_var($wpdb->prepare("SELECT ID FROM " . $wpdb->prefix . "posts WHERE guid=%s", $url));
    }

    public static function getImageTitle($url)
    {
        global $wpdb;
        $attachmentTitle = $wpdb->get_var($wpdb->prepare("SELECT post_title FROM " . $wpdb->prefix . "posts WHERE guid=%s", $url));
        return $attachmentTitle;
    }

    public static function getViewNameById($id)
    {
        global $wpdb;
        $query = $wpdb->prepare("SELECT grid_view_type from " . $wpdb->prefix . "pfhub_portfolio_grids WHERE id=%d", $id);
        $view = $wpdb->get_var($query);
        switch ($view) {
            case 0:
                $slug = 'grid';
                break;
            case 1:
                $slug = 'masonry';
                break;
            case 2:
                $slug = 'content-popup';
                break;
            case 3:
                $slug = 'list';
                break;
            case 5:
                $slug = 'content-slider';
                break;
            case 6:
                $slug = 'image-grid';
                break;
            case 7:
                $slug = 'elastic-grid';
                break;
        }

        return $slug;
    }

    public static function getDefaultSettings()
    {
        return array(
            'pfhub_portfolio_view0_togglebutton_style' => 'dark',
            'pfhub_portfolio_view0_show_separator_lines' => 'on',
            'pfhub_portfolio_view0_linkbutton_text' => 'View More',
            'pfhub_portfolio_view0_show_linkbutton' => 'on',
            'pfhub_portfolio_view0_linkbutton_background_hover_color' => 'df2e1b',
            'pfhub_portfolio_view0_linkbutton_background_color' => 'e74c3c',
            'pfhub_portfolio_view0_linkbutton_font_hover_color' => 'ffffff',
            'pfhub_portfolio_view0_linkbutton_color' => 'ffffff',
            'pfhub_portfolio_view0_linkbutton_font_size' => '14',
            'pfhub_portfolio_view0_description_color' => '5b5b5b',
            'pfhub_portfolio_view0_description_font_size' => '14',
            'pfhub_portfolio_view0_show_description' => 'on',
            'pfhub_portfolio_view0_thumbs_width' => '75',
            'pfhub_portfolio_view0_thumbs_position' => 'before',
            'pfhub_portfolio_view0_show_thumbs' => 'on',
            'pfhub_portfolio_view0_title_font_size' => '15',
            'pfhub_portfolio_view0_title_font_color' => '555555',
            'pfhub_portfolio_view0_element_border_width' => '1',
            'pfhub_portfolio_view0_element_border_color' => 'D0D0D0',
            'pfhub_portfolio_view0_element_background_color' => 'f7f7f7',
            'pfhub_portfolio_view0_block_width' => '275',
            'pfhub_portfolio_view0_block_height' => '160',
            'pfhub_portfolio_view1_show_separator_lines' => 'on',
            'pfhub_portfolio_view1_linkbutton_text' => 'View More',
            'pfhub_portfolio_view1_show_linkbutton' => 'on',
            'pfhub_portfolio_view1_linkbutton_background_hover_color' => 'df2e1b',
            'pfhub_portfolio_view1_linkbutton_background_color' => 'e74c3c',
            'pfhub_portfolio_view1_linkbutton_font_hover_color' => 'ffffff',
            'pfhub_portfolio_view1_linkbutton_color' => 'ffffff',
            'pfhub_portfolio_view1_linkbutton_font_size' => '14',
            'pfhub_portfolio_view1_description_color' => '5b5b5b',
            'pfhub_portfolio_view1_description_font_size' => '14',
            'pfhub_portfolio_view1_show_description' => 'on',
            'pfhub_portfolio_view1_thumbs_width' => '75',
            'pfhub_portfolio_view1_thumbs_position' => 'before',
            'pfhub_portfolio_view1_show_thumbs' => 'on',
            'pfhub_portfolio_view1_title_font_size' => '15',
            'pfhub_portfolio_view1_title_font_color' => '555555',
            'pfhub_portfolio_view1_element_border_width' => '1',
            'pfhub_portfolio_view1_element_border_color' => 'D0D0D0',
            'pfhub_portfolio_view1_element_background_color' => 'f7f7f7',
            'pfhub_portfolio_view1_block_width' => '275',
            'pfhub_portfolio_view2_element_linkbutton_text' => 'View More',
            'pfhub_portfolio_view2_element_show_linkbutton' => 'on',
            'pfhub_portfolio_view2_element_linkbutton_color' => 'ffffff',
            'pfhub_portfolio_view2_element_linkbutton_font_size' => '14',
            'pfhub_portfolio_view2_element_linkbutton_background_color' => '2ea2cd',
            'pfhub_portfolio_view2_show_popup_linkbutton' => 'on',
            'pfhub_portfolio_view2_popup_linkbutton_text' => 'View More',
            'pfhub_portfolio_view2_popup_linkbutton_background_hover_color' => '0074a2',
            'pfhub_portfolio_view2_popup_linkbutton_background_color' => '2ea2cd',
            'pfhub_portfolio_view2_popup_linkbutton_font_hover_color' => 'ffffff',
            'pfhub_portfolio_view2_popup_linkbutton_color' => 'ffffff',
            'pfhub_portfolio_view2_popup_linkbutton_font_size' => '14',
            'pfhub_portfolio_view2_description_color' => '222222',
            'pfhub_portfolio_view2_description_font_size' => '14',
            'pfhub_portfolio_view2_show_description' => 'on',
            'pfhub_portfolio_view2_thumbs_width' => '75',
            'pfhub_portfolio_view2_thumbs_height' => '75',
            'pfhub_portfolio_view2_thumbs_position' => 'before',
            'pfhub_portfolio_view2_show_thumbs' => 'on',
            'pfhub_portfolio_view2_popup_background_color' => 'FFFFFF',
            'pfhub_portfolio_view2_popup_overlay_color' => '000000',
            'pfhub_portfolio_view2_popup_overlay_transparency_color' => '70',
            'pfhub_portfolio_view2_popup_closebutton_style' => 'dark',
            'pfhub_portfolio_view2_show_separator_lines' => 'on',
            'pfhub_portfolio_view2_show_popup_title' => 'on',
            'pfhub_portfolio_view2_element_title_font_size' => '18',
            'pfhub_portfolio_view2_element_title_font_color' => '222222',
            'pfhub_portfolio_view2_popup_title_font_size' => '18',
            'pfhub_portfolio_view2_popup_title_font_color' => '222222',
            'pfhub_portfolio_view2_element_overlay_color' => 'FFFFFF',
            'pfhub_portfolio_view2_element_overlay_transparency' => '70',
            'pfhub_portfolio_view2_zoombutton_style' => 'light',
            'pfhub_portfolio_view2_element_border_width' => '1',
            'pfhub_portfolio_view2_element_border_color' => 'dedede',
            'pfhub_portfolio_view2_element_background_color' => 'f9f9f9',
            'pfhub_portfolio_view2_element_width' => '275',
            'pfhub_portfolio_view2_element_height' => '160',
            'pfhub_portfolio_view3_show_separator_lines' => 'on',
            'pfhub_portfolio_view3_linkbutton_text' => 'View More',
            'pfhub_portfolio_view3_show_linkbutton' => 'on',
            'pfhub_portfolio_view3_linkbutton_background_hover_color' => '0074a2',
            'pfhub_portfolio_view3_linkbutton_background_color' => '2ea2cd',
            'pfhub_portfolio_view3_linkbutton_font_hover_color' => 'ffffff',
            'pfhub_portfolio_view3_linkbutton_color' => 'ffffff',
            'pfhub_portfolio_view3_linkbutton_font_size' => '14',
            'pfhub_portfolio_view3_description_color' => '555555',
            'pfhub_portfolio_view3_description_font_size' => '14',
            'pfhub_portfolio_view3_show_description' => 'on',
            'pfhub_portfolio_view3_thumbs_width' => '75',
            'pfhub_portfolio_view3_thumbs_height' => '75',
            'pfhub_portfolio_view3_show_thumbs' => 'on',
            'pfhub_portfolio_view3_title_font_size' => '18',
            'pfhub_portfolio_view3_title_font_color' => '0074a2',
            'pfhub_portfolio_view3_mainimage_width' => '240',
            'pfhub_portfolio_view3_element_border_width' => '1',
            'pfhub_portfolio_view3_element_border_color' => 'dedede',
            'pfhub_portfolio_view3_element_background_color' => 'f9f9f9',
            'pfhub_portfolio_view4_togglebutton_style' => 'dark',
            'pfhub_portfolio_view4_show_separator_lines' => 'on',
            'pfhub_portfolio_view4_linkbutton_text' => 'View More',
            'pfhub_portfolio_view4_show_linkbutton' => 'on',
            'pfhub_portfolio_view4_linkbutton_background_hover_color' => 'df2e1b',
            'pfhub_portfolio_view4_linkbutton_background_color' => 'e74c3c',
            'pfhub_portfolio_view4_linkbutton_font_hover_color' => 'ffffff',
            'pfhub_portfolio_view4_linkbutton_color' => 'ffffff',
            'pfhub_portfolio_view4_linkbutton_font_size' => '14',
            'pfhub_portfolio_view4_description_color' => '555555',
            'pfhub_portfolio_view4_description_font_size' => '14',
            'pfhub_portfolio_view4_show_description' => 'on',
            'pfhub_portfolio_view4_title_font_size' => '18',
            'pfhub_portfolio_view4_title_font_color' => 'E74C3C',
            'pfhub_portfolio_view4_element_border_width' => '1',
            'pfhub_portfolio_view4_element_border_color' => 'dedede',
            'pfhub_portfolio_view4_element_background_color' => 'f9f9f9',
            'pfhub_portfolio_view4_block_width' => '275',
            'pfhub_portfolio_view5_icons_style' => 'dark',
            'pfhub_portfolio_view5_show_separator_lines' => 'on',
            'pfhub_portfolio_view5_linkbutton_text' => 'View More',
            'pfhub_portfolio_view5_show_linkbutton' => 'on',
            'pfhub_portfolio_view5_linkbutton_background_hover_color' => '0074a2',
            'pfhub_portfolio_view5_linkbutton_background_color' => '2ea2cd',
            'pfhub_portfolio_view5_linkbutton_font_hover_color' => 'ffffff',
            'pfhub_portfolio_view5_linkbutton_color' => 'ffffff',
            'pfhub_portfolio_view5_linkbutton_font_size' => '14',
            'pfhub_portfolio_view5_description_color' => '555555',
            'pfhub_portfolio_view5_description_font_size' => '14',
            'pfhub_portfolio_view5_show_description' => 'on',
            'pfhub_portfolio_view5_thumbs_width' => '75',
            'pfhub_portfolio_view5_thumbs_height' => '75',
            'pfhub_portfolio_view5_show_thumbs' => 'on',
            'pfhub_portfolio_view5_title_font_size' => '16',
            'pfhub_portfolio_view5_title_font_color' => '0074a2',
            'pfhub_portfolio_view5_main_image_width' => '275',
            'pfhub_portfolio_view5_slider_tabs_font_color' => 'd9d99',
            'pfhub_portfolio_view5_slider_tabs_background_color' => '555555',
            'pfhub_portfolio_view5_slider_background_color' => 'f9f9f9',
            'pfhub_portfolio_view6_title_font_size' => '16',
            'pfhub_portfolio_view6_title_font_color' => '0074A2',
            'pfhub_portfolio_view6_title_font_hover_color' => '2EA2CD',
            'pfhub_portfolio_view6_title_background_color' => '000000',
            'pfhub_portfolio_view6_title_background_transparency' => '80',
            'pfhub_portfolio_view6_border_radius' => '3',
            'pfhub_portfolio_view6_border_width' => '0',
            'pfhub_portfolio_view6_border_color' => 'eeeeee',
            'pfhub_portfolio_view6_width' => '275',
            'pfhub_portfolio_light_box_size' => '17',
            'pfhub_portfolio_light_box_width' => '500',
            'pfhub_portfolio_light_box_transition' => 'elastic',
            'pfhub_portfolio_light_box_speed' => '800',
            'pfhub_portfolio_light_box_href' => 'False',
            'pfhub_portfolio_light_box_title' => 'false',
            'pfhub_portfolio_light_box_scalephotos' => 'true',
            'pfhub_portfolio_light_box_rel' => 'false',
            'pfhub_portfolio_light_box_scrolling' => 'false',
            'pfhub_portfolio_light_box_opacity' => '20',
            'pfhub_portfolio_light_box_open' => 'false',
            'pfhub_portfolio_light_box_overlayclose' => 'true',
            'pfhub_portfolio_light_box_esckey' => 'false',
            'pfhub_portfolio_light_box_arrowkey' => 'false',
            'pfhub_portfolio_light_box_loop' => 'true',
            'pfhub_portfolio_light_box_data' => 'false',
            'pfhub_portfolio_light_box_classname' => 'false',
            'pfhub_portfolio_light_box_fadeout' => '300',
            'pfhub_portfolio_light_box_closebutton' => 'false',
            'pfhub_portfolio_light_box_current' => 'image',
            'pfhub_portfolio_light_box_previous' => 'previous',
            'pfhub_portfolio_light_box_next' => 'next',
            'pfhub_portfolio_light_box_close' => 'close',
            'pfhub_portfolio_light_box_iframe' => 'false',
            'pfhub_portfolio_light_box_inline' => 'false',
            'pfhub_portfolio_light_box_html' => 'false',
            'pfhub_portfolio_light_box_photo' => 'false',
            'pfhub_portfolio_light_box_height' => '500',
            'pfhub_portfolio_light_box_innerwidth' => 'false',
            'pfhub_portfolio_light_box_innerheight' => 'false',
            'pfhub_portfolio_light_box_initialwidth' => '300',
            'pfhub_portfolio_light_box_initialheight' => '100',
            'pfhub_portfolio_light_box_maxwidth' => '768',
            'pfhub_portfolio_light_box_maxheight' => '500',
            'pfhub_portfolio_light_box_slideshow' => 'false',
            'pfhub_portfolio_light_box_slideshowspeed' => '2500',
            'pfhub_portfolio_light_box_slideshowauto' => 'true',
            'pfhub_portfolio_light_box_slideshowstart' => 'start slideshow',
            'pfhub_portfolio_light_box_slideshowstop' => 'stop slideshow',
            'pfhub_portfolio_light_box_fixed' => 'true',
            'pfhub_portfolio_light_box_top' => 'false',
            'pfhub_portfolio_light_box_bottom' => 'false',
            'pfhub_portfolio_light_box_left' => 'false',
            'pfhub_portfolio_light_box_right' => 'false',
            'pfhub_portfolio_light_box_reposition' => 'false',
            'pfhub_portfolio_light_box_retinaimage' => 'true',
            'pfhub_portfolio_light_box_retinaurl' => 'false',
            'pfhub_portfolio_light_box_retinasuffix' => '@2x.$1',
            'pfhub_portfolio_light_box_returnfocus' => 'true',
            'pfhub_portfolio_light_box_trapfocus' => 'true',
            'pfhub_portfolio_light_box_fastiframe' => 'true',
            'pfhub_portfolio_light_box_preloading' => 'true',
            'pfhub_portfolio_slider_title_position' => '5',
            'pfhub_portfolio_light_box_style' => '1',
            'pfhub_portfolio_light_box_size_fix' => 'false',
            'pfhub_portfolio_view0_show_sorting' => 'on',
            'pfhub_portfolio_view0_sortbutton_font_size' => '14',
            'pfhub_portfolio_view0_sortbutton_font_color' => '555555',
            'pfhub_portfolio_view0_sortbutton_hover_font_color' => 'ffffff',
            'pfhub_portfolio_view0_sortbutton_background_color' => 'F7F7F7',
            'pfhub_portfolio_view0_sortbutton_hover_background_color' => 'FF3845',
            'pfhub_portfolio_view0_sortbutton_border_radius' => '0',
            'pfhub_portfolio_view0_sortbutton_border_padding' => '3',
            'pfhub_portfolio_view0_sorting_float' => 'top',
            'pfhub_portfolio_view0_show_filtering' => 'on',
            'pfhub_portfolio_view0_filterbutton_font_size' => '14',
            'pfhub_portfolio_view0_filterbutton_font_color' => '555555',
            'pfhub_portfolio_view0_filterbutton_background_color' => 'F7F7F7',
            'pfhub_portfolio_view0_filterbutton_hover_font_color' => 'ffffff',
            'pfhub_portfolio_view0_filterbutton_hover_background_color' => 'FF3845',
            'pfhub_portfolio_view0_filterbutton_border_radius' => '0',
            'pfhub_portfolio_view0_filterbutton_border_padding' => '3',
            'pfhub_portfolio_view0_filtering_float' => 'left',
            'pfhub_portfolio_view1_show_sorting' => 'on',
            'pfhub_portfolio_view1_sortbutton_font_size' => '14',
            'pfhub_portfolio_view1_sortbutton_font_color' => '555555',
            'pfhub_portfolio_view1_sortbutton_hover_font_color' => 'ffffff',
            'pfhub_portfolio_view1_sortbutton_background_color' => 'F7F7F7',
            'pfhub_portfolio_view1_sortbutton_hover_background_color' => 'FF3845',
            'pfhub_portfolio_view1_sortbutton_border_radius' => '0',
            'pfhub_portfolio_view1_sortbutton_border_padding' => '3',
            'pfhub_portfolio_view1_sorting_float' => 'top',
            'pfhub_portfolio_view1_show_filtering' => 'on',
            'pfhub_portfolio_view1_filterbutton_font_size' => '14',
            'pfhub_portfolio_view1_filterbutton_font_color' => '555555',
            'pfhub_portfolio_view1_filterbutton_background_color' => 'F7F7F7',
            'pfhub_portfolio_view1_filterbutton_hover_font_color' => 'ffffff',
            'pfhub_portfolio_view1_filterbutton_hover_background_color' => 'FF3845',
            'pfhub_portfolio_view1_filterbutton_border_radius' => '0',
            'pfhub_portfolio_view1_filterbutton_border_padding' => '3',
            'pfhub_portfolio_view1_filtering_float' => 'left',
            'pfhub_portfolio_view2_show_sorting' => 'on',
            'pfhub_portfolio_view2_sortbutton_font_size' => '14',
            'pfhub_portfolio_view2_sortbutton_font_color' => '555555',
            'pfhub_portfolio_view2_sortbutton_hover_font_color' => 'ffffff',
            'pfhub_portfolio_view2_sortbutton_background_color' => 'F7F7F7',
            'pfhub_portfolio_view2_sortbutton_hover_background_color' => 'FF3845',
            'pfhub_portfolio_view2_sortbutton_border_radius' => '0',
            'pfhub_portfolio_view2_sortbutton_border_padding' => '3',
            'pfhub_portfolio_view2_sorting_float' => 'top',
            'pfhub_portfolio_view2_show_filtering' => 'on',
            'pfhub_portfolio_view2_filterbutton_font_size' => '14',
            'pfhub_portfolio_view2_filterbutton_font_color' => '555555',
            'pfhub_portfolio_view2_filterbutton_background_color' => 'F7F7F7',
            'pfhub_portfolio_view2_filterbutton_hover_font_color' => 'ffffff',
            'pfhub_portfolio_view2_filterbutton_hover_background_color' => 'FF3845',
            'pfhub_portfolio_view2_filterbutton_border_radius' => '0',
            'pfhub_portfolio_view2_filterbutton_border_padding' => '3',
            'pfhub_portfolio_view2_filtering_float' => 'left',
            'pfhub_portfolio_view3_show_sorting' => 'on',
            'pfhub_portfolio_view3_sortbutton_font_size' => '14',
            'pfhub_portfolio_view3_sortbutton_font_color' => '555555',
            'pfhub_portfolio_view3_sortbutton_hover_font_color' => 'ffffff',
            'pfhub_portfolio_view3_sortbutton_background_color' => 'F7F7F7',
            'pfhub_portfolio_view3_sortbutton_hover_background_color' => 'FF3845',
            'pfhub_portfolio_view3_sortbutton_border_radius' => '0',
            'pfhub_portfolio_view3_sortbutton_border_padding' => '3',
            'pfhub_portfolio_view3_sorting_float' => 'top',
            'pfhub_portfolio_view3_show_filtering' => 'on',
            'pfhub_portfolio_view3_filterbutton_font_size' => '14',
            'pfhub_portfolio_view3_filterbutton_font_color' => '555555',
            'pfhub_portfolio_view3_filterbutton_background_color' => 'F7F7F7',
            'pfhub_portfolio_view3_filterbutton_hover_font_color' => 'ffffff',
            'pfhub_portfolio_view3_filterbutton_hover_background_color' => 'FF3845',
            'pfhub_portfolio_view3_filterbutton_border_radius' => '0',
            'pfhub_portfolio_view3_filterbutton_border_padding' => '3',
            'pfhub_portfolio_view3_filtering_float' => 'left',
            'pfhub_portfolio_view4_show_sorting' => 'on',
            'pfhub_portfolio_view4_sortbutton_font_size' => '14',
            'pfhub_portfolio_view4_sortbutton_font_color' => '555555',
            'pfhub_portfolio_view4_sortbutton_hover_font_color' => 'ffffff',
            'pfhub_portfolio_view4_sortbutton_background_color' => 'F7F7F7',
            'pfhub_portfolio_view4_sortbutton_hover_background_color' => 'FF3845',
            'pfhub_portfolio_view4_sortbutton_border_radius' => '0',
            'pfhub_portfolio_view4_sortbutton_border_padding' => '3',
            'pfhub_portfolio_view4_sorting_float' => 'top',
            'pfhub_portfolio_view4_show_filtering' => 'on',
            'pfhub_portfolio_view4_filterbutton_font_size' => '14',
            'pfhub_portfolio_view4_filterbutton_font_color' => '555555',
            'pfhub_portfolio_view4_filterbutton_background_color' => 'F7F7F7',
            'pfhub_portfolio_view4_filterbutton_hover_font_color' => 'ffffff',
            'pfhub_portfolio_view4_filterbutton_hover_background_color' => 'FF3845',
            'pfhub_portfolio_view4_filterbutton_border_radius' => '0',
            'pfhub_portfolio_view4_filterbutton_border_padding' => '3',
            'pfhub_portfolio_view4_filtering_float' => 'left',
            'pfhub_portfolio_view6_show_sorting' => 'on',
            'pfhub_portfolio_view6_sortbutton_font_size' => '14',
            'pfhub_portfolio_view6_sortbutton_font_color' => '555555',
            'pfhub_portfolio_view6_sortbutton_hover_font_color' => 'ffffff',
            'pfhub_portfolio_view6_sortbutton_background_color' => 'F7F7F7',
            'pfhub_portfolio_view6_sortbutton_hover_background_color' => 'FF3845',
            'pfhub_portfolio_view6_sortbutton_border_radius' => '0',
            'pfhub_portfolio_view6_sortbutton_border_padding' => '3',
            'pfhub_portfolio_view6_sorting_float' => 'top',
            'pfhub_portfolio_view6_show_filtering' => 'on',
            'pfhub_portfolio_view6_filterbutton_font_size' => '14',
            'pfhub_portfolio_view6_filterbutton_font_color' => '555555',
            'pfhub_portfolio_view6_filterbutton_background_color' => 'F7F7F7',
            'pfhub_portfolio_view6_filterbutton_hover_font_color' => 'ffffff',
            'pfhub_portfolio_view6_filterbutton_hover_background_color' => 'FF3845',
            'pfhub_portfolio_view6_filterbutton_border_radius' => '0',
            'pfhub_portfolio_view6_filterbutton_border_padding' => '3',
            'pfhub_portfolio_view6_filtering_float' => 'left',
            'pfhub_portfolio_view0_sorting_name_by_default' => 'Default',
            'pfhub_portfolio_view0_sorting_name_by_id' => 'Date',
            'pfhub_portfolio_view0_sorting_name_by_name' => 'Title',
            'pfhub_portfolio_view0_sorting_name_by_random' => 'Random',
            'pfhub_portfolio_view0_sorting_name_by_asc' => 'Ascending',
            'pfhub_portfolio_view0_sorting_name_by_desc' => 'Descending',
            'pfhub_portfolio_view1_sorting_name_by_default' => 'Default',
            'pfhub_portfolio_view1_sorting_name_by_id' => 'Date',
            'pfhub_portfolio_view1_sorting_name_by_name' => 'Title',
            'pfhub_portfolio_view1_sorting_name_by_random' => 'Random',
            'pfhub_portfolio_view1_sorting_name_by_asc' => 'Ascending',
            'pfhub_portfolio_view1_sorting_name_by_desc' => 'Descending',
            'pfhub_portfolio_view2_popup_full_width' => 'on',
            'pfhub_portfolio_view2_sorting_name_by_default' => 'Default',
            'pfhub_portfolio_view2_sorting_name_by_id' => 'Date',
            'pfhub_portfolio_view2_sorting_name_by_name' => 'Title',
            'pfhub_portfolio_view2_sorting_name_by_random' => 'Random',
            'pfhub_portfolio_view2_sorting_name_by_asc' => 'Ascending',
            'pfhub_portfolio_view2_sorting_name_by_desc' => 'Descending',
            'pfhub_portfolio_view3_sorting_name_by_default' => 'Default',
            'pfhub_portfolio_view3_sorting_name_by_id' => 'Date',
            'pfhub_portfolio_view3_sorting_name_by_name' => 'Title',
            'pfhub_portfolio_view3_sorting_name_by_random' => 'Random',
            'pfhub_portfolio_view3_sorting_name_by_asc' => 'Ascending',
            'pfhub_portfolio_view3_sorting_name_by_desc' => 'Descending',
            'pfhub_portfolio_view4_sorting_name_by_default' => 'Default',
            'pfhub_portfolio_view4_sorting_name_by_id' => 'Date',
            'pfhub_portfolio_view4_sorting_name_by_name' => 'Title',
            'pfhub_portfolio_view4_sorting_name_by_random' => 'Random',
            'pfhub_portfolio_view4_sorting_name_by_asc' => 'Ascending',
            'pfhub_portfolio_view4_sorting_name_by_desc' => 'Descending',
            'pfhub_portfolio_view5_sorting_name_by_default' => 'Default',
            'pfhub_portfolio_view5_sorting_name_by_id' => 'Date',
            'pfhub_portfolio_view5_sorting_name_by_name' => 'Title',
            'pfhub_portfolio_view5_sorting_name_by_random' => 'Random',
            'pfhub_portfolio_view5_sorting_name_by_asc' => 'Ascending',
            'pfhub_portfolio_view5_sorting_name_by_desc' => 'Descending',
            'pfhub_portfolio_view6_sorting_name_by_default' => 'Default',
            'pfhub_portfolio_view6_sorting_name_by_id' => 'Date',
            'pfhub_portfolio_view6_sorting_name_by_name' => 'Title',
            'pfhub_portfolio_view6_sorting_name_by_random' => 'Random',
            'pfhub_portfolio_view6_sorting_name_by_asc' => 'Ascending',
            'pfhub_portfolio_view6_sorting_name_by_desc' => 'Descending',
            'pfhub_portfolio_view0_cat_all' => 'all',
            'pfhub_portfolio_view1_cat_all' => 'all',
            'pfhub_portfolio_view2_cat_all' => 'all',
            'pfhub_portfolio_view3_cat_all' => 'all',
            'pfhub_portfolio_view4_cat_all' => 'all',
            'pfhub_portfolio_view5_cat_all' => 'all',
            'pfhub_portfolio_view6_cat_all' => 'all',
            'pfhub_portfolio_port_natural_size_thumbnail' => 'resize',
            'pfhub_portfolio_port_natural_size_contentpopup' => 'resize',
            'pfhub_portfolio_view0_elements_in_center' => 'off',
            'pfhub_portfolio_view0_filterbutton_width' => '180',
            'pfhub_portfolio_view1_filterbutton_width' => '180',
            'pfhub_portfolio_view2_filterbutton_width' => '180',
            'pfhub_portfolio_view3_filterbutton_width' => '180',
            'pfhub_portfolio_view4_filterbutton_width' => '180',
            'pfhub_portfolio_view6_filterbutton_width' => '180',
            'pfhub_portfolio_port_natural_size_toggle' => 'resize',
            'pfhub_portfolio_admin_image_hover_preview' => 'on',
            'pfhub_portfolio_view7_image_behaviour' => 'crop',
            'pfhub_portfolio_view7_element_width' => '250',
            'pfhub_portfolio_view7_element_height' => '150',
            'pfhub_portfolio_view7_element_margin' => '10',
            'pfhub_portfolio_view7_element_border_width' => '0',
            'pfhub_portfolio_view7_element_border_color' => 'DEDEDE',
            'pfhub_portfolio_view7_element_overlay_background_color_' => '484848',
            'pfhub_portfolio_view7_element_overlay_opacity' => '70',
            'pfhub_portfolio_view7_element_hover_effect' => 'true',
            'pfhub_portfolio_view7_filter_all_text' => 'All',
            'pfhub_portfolio_view7_filter_effect' => 'popup',
            'pfhub_portfolio_view7_hover_effect_delay' => '0',
            'pfhub_portfolio_view7_hover_effect_inverse' => 'false',
            'pfhub_portfolio_view7_expanding_speed' => '500',
            'pfhub_portfolio_view7_expand_block_height' => '500',
            'pfhub_portfolio_view7_element_title_font_size' => '16',
            'pfhub_portfolio_view7_element_title_font_color' => 'FFFFFF',
            'pfhub_portfolio_view7_element_title_align' => 'center',
            'pfhub_portfolio_view7_element_title_border_width' => '1',
            'pfhub_portfolio_view7_element_title_border_color' => 'FFFFFF',
            'pfhub_portfolio_view7_element_title_margin_top' => '40',
            'pfhub_portfolio_view7_element_title_padding_top_bottom' => '10',
            'pfhub_portfolio_view7_expand_block_background_color' => '222222',
            'pfhub_portfolio_view7_expand_block_opacity' => '100',
            'pfhub_portfolio_view7_expand_block_title_color' => 'd6d6d6',
            'pfhub_portfolio_view7_expand_block_title_font_size' => '35',
            'pfhub_portfolio_view7_expand_block_description_font_size' => '13',
            'pfhub_portfolio_view7_expand_block_description_font_color' => '999',
            'pfhub_portfolio_view7_expand_block_description_font_hover_color' => '999',
            'pfhub_portfolio_view7_expand_block_description_text_align' => 'left',
            'pfhub_portfolio_view7_expand_block_button_background_color' => '454545',
            'pfhub_portfolio_view7_expand_block_button_background_hover_color' => '454545',
            'pfhub_portfolio_view7_expand_block_button_text_color' => '9f9f9f',
            'pfhub_portfolio_view7_expand_block_button_font_size' => '11',
            'pfhub_portfolio_view7_expand_block_button_text' => 'View More',
            'pfhub_portfolio_view7_filter_button_font_hover_color' => 'fff',
            'pfhub_portfolio_view7_filter_button_background_color' => 'F7F7F7',
            'pfhub_portfolio_view7_filter_button_background_hover_color' => 'FF3845',
            'pfhub_portfolio_view7_filter_button_border_radius' => '0',
            'pfhub_portfolio_view7_expand_width' => '100',
            'pfhub_portfolio_view7_thumbnail_width' => '100',
            'pfhub_portfolio_view7_thumbnail_height' => '100',
            'pfhub_portfolio_view7_thumbnail_bg_color' => '313131',
            'pfhub_portfolio_view7_thumbnail_block_box_shadow' => 'on',
            'pfhub_portfolio_view7_filter_button_text' => 'All',
            'pfhub_portfolio_view7_filter_button_font_size' => '16',
            'pfhub_portfolio_view7_filter_button_font_color' => '444444',
            'pfhub_portfolio_view7_filter_button_bg_color_active' => '666',
            'pfhub_portfolio_view7_filter_button_padding' => '8',
            'pfhub_portfolio_view7_filter_button_radius' => '4',
            'pfhub_portfolio_view7_filter_button_font_active_color' => 'fff',
            'pfhub_portfolio_view7_show_all_filter_button' => 'on',
            'pfhub_portfolio_lightbox_slideAnimationType' => 'effect_1',
            'pfhub_portfolio_lightbox_lightboxView' => 'view1',
            'pfhub_portfolio_lightbox_speed_new' => '600',
            'pfhub_portfolio_lightbox_width_new' => '100',
            'pfhub_portfolio_lightbox_height_new' => '100',
            'pfhub_portfolio_lightbox_videoMaxWidth' => '790',
            'pfhub_portfolio_lightbox_overlayDuration' => '150',
            'pfhub_portfolio_lightbox_overlayClose_new' => 'true',
            'pfhub_portfolio_lightbox_loop_new' => 'true',
            'pfhub_portfolio_lightbox_escKey_new' => 'false',
            'pfhub_portfolio_lightbox_keyPress_new' => 'false',
            'pfhub_portfolio_lightbox_arrows' => 'true',
            'pfhub_portfolio_lightbox_mouseWheel' => 'false',
            'pfhub_portfolio_lightbox_download' => 'false',
            'pfhub_portfolio_lightbox_showCounter' => 'true',
            'pfhub_portfolio_lightbox_nextHtml' => '',     //not used
            'pfhub_portfolio_lightbox_prevHtml' => '',     //not used
            'pfhub_portfolio_lightbox_sequence_info' => 'image',
            'pfhub_portfolio_lightbox_sequenceInfo' => 'of',
            'pfhub_portfolio_lightbox_slideshow_new' => 'false',
            'pfhub_portfolio_lightbox_slideshow_auto_new' => 'false',
            'pfhub_portfolio_lightbox_slideshow_speed_new' => '2500',
            'pfhub_portfolio_lightbox_slideshow_start_new' => '',     //not used
            'pfhub_portfolio_lightbox_slideshow_stop_new' => '',     //not used
            'pfhub_portfolio_lightbox_watermark' => 'false',
            'pfhub_portfolio_lightbox_socialSharing' => 'false',
            'pfhub_portfolio_lightbox_facebookButton' => 'true',
            'pfhub_portfolio_lightbox_twitterButton' => 'true',
            'pfhub_portfolio_lightbox_googleplusButton' => 'true',
            'pfhub_portfolio_lightbox_pinterestButton' => 'false',
            'pfhub_portfolio_lightbox_linkedinButton' => 'false',
            'pfhub_portfolio_lightbox_tumblrButton' => 'false',
            'pfhub_portfolio_lightbox_redditButton' => 'false',
            'pfhub_portfolio_lightbox_bufferButton' => 'false',
            'pfhub_portfolio_lightbox_diggButton' => 'false',
            'pfhub_portfolio_lightbox_vkButton' => 'false',
            'pfhub_portfolio_lightbox_yummlyButton' => 'false',
            'pfhub_portfolio_lightbox_watermark_text' => 'WaterMark',
            'pfhub_portfolio_lightbox_watermark_textColor' => 'ffffff',
            'pfhub_portfolio_lightbox_watermark_textFontSize' => '30',
            'pfhub_portfolio_lightbox_watermark_containerBackground' => '000000',
            'pfhub_portfolio_lightbox_watermark_containerOpacity' => '90',
            'pfhub_portfolio_lightbox_watermark_containerWidth' => '300',
            'pfhub_portfolio_lightbox_watermark_position_new' => '9',
            'pfhub_portfolio_lightbox_watermark_opacity' => '70',
            'pfhub_portfolio_lightbox_watermark_margin' => '10',
            'pfhub_portfolio_lightbox_watermark_img_src_new' => PFHUB_PORTFOLIO_IMAGES_URL . '/admin/No-image-found.jpg',
            'pfhub_portfolio_lightbox_type' => 'modern',
            'pfhub_portfolio_view8_title_font_size' => '16',
            'pfhub_portfolio_view8_title_font_color' => '0074A2',
            'pfhub_portfolio_view8_title_font_hover_color' => '2EA2CD',
            'pfhub_portfolio_view8_title_background_color' => '000000',
            'pfhub_portfolio_view8_hide_title' => 'off',
            'pfhub_portfolio_view8_title_background_transparency' => '80',
            'pfhub_portfolio_view8_border_width' => '0',
            'pfhub_portfolio_view8_element_background_color' => 'f9f9f9',
            'pfhub_portfolio_view8_border_color' => 'eeeeee',
            'pfhub_portfolio_view8_border_radius' => '0',
            'pfhub_portfolio_view8_width' => '275',
            'pfhub_portfolio_view8_image_title_font_size' => '18',
            'pfhub_portfolio_view8_image_title_font_color' => '0074A2',
            'pfhub_portfolio_view8_desc_font_size' => '14',
            'pfhub_portfolio_view8_desc_font_color' => '0074A2',
            'pfhub_portfolio_view8_show_sorting' => 'on',
            'pfhub_portfolio_view8_sortbutton_font_size' => '14',
            'pfhub_portfolio_view8_sortbutton_font_color' => '555555',
            'pfhub_portfolio_view8_sortbutton_hover_font_color' => 'ffffff',
            'pfhub_portfolio_view8_sortbutton_background_color' => 'F7F7F7',
            'pfhub_portfolio_view8_sortbutton_hover_background_color' => 'FF3845',
            'pfhub_portfolio_view8_sortbutton_border_width' => '0',
            'pfhub_portfolio_view8_sortbutton_border_padding' => '3',
            'pfhub_portfolio_view8_sorting_float' => 'top',
            'pfhub_portfolio_view8_sorting_name_by_default' => 'Default',
            'pfhub_portfolio_view8_sorting_name_by_id' => 'Date',
            'pfhub_portfolio_view8_sorting_name_by_name' => 'Title',
            'pfhub_portfolio_view8_sorting_name_by_random' => 'Random',
            'pfhub_portfolio_view8_sorting_name_by_asc' => 'Ascending',
            'pfhub_portfolio_view8_sorting_name_by_desc' => 'Descending',
            'pfhub_portfolio_view8_cat_all' => 'all',
            'pfhub_portfolio_view8_show_filtering' => 'on',
            'pfhub_portfolio_view8_filterbutton_font_size' => '14',
            'pfhub_portfolio_view8_filterbutton_font_color' => '555555',
            'pfhub_portfolio_view8_filterbutton_hover_font_color' => 'ffffff',
            'pfhub_portfolio_view8_filterbutton_background_color' => 'F7F7F7',
            'pfhub_portfolio_view8_filterbutton_hover_background_color' => 'FF3845',
            'pfhub_portfolio_view8_filterbutton_width' => '180',
            'pfhub_portfolio_view8_filterbutton_border_radius' => '0',
            'pfhub_portfolio_view8_filterbutton_border_padding' => '3',
            'pfhub_portfolio_view8_filterbutton_margin' => '',
            'pfhub_portfolio_view8_filtering_float' => 'left',
        );
    }
}
