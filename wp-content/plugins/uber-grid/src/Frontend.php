<?php


namespace PfhubPortfolio;


use PfhubPortfolio\Helpers\GridHelper;

class Frontend
{
    public static function init()
    {
        add_shortcode('pfhub_portfolio', array(__CLASS__, 'run_shortcode'));
        add_shortcode('pfhub_portfolio_portfolio', array(__CLASS__, 'run_shortcode'));
        add_action('admin_footer', array(__CLASS__, 'inline_popup_content'));
        add_action('media_buttons_context', array(__CLASS__, 'add_editor_media_button'));

        add_action('pfhub_portfolio_shortcode_scripts', array(__CLASS__, 'frontend_scripts'), 10, 2);
        add_action('pfhub_portfolio_shortcode_scripts', array(__CLASS__, 'frontend_styles'), 10, 2);
        add_action('pfhub_portfolio_localize_scripts', array(__CLASS__, 'localize_scripts'), 10, 1);
    }

    public static function widgets()
    {
        register_widget(PortfolioWidget::class);
    }

    public static function run_shortcode($attrs)
    {
        $attrs = shortcode_atts(array(
            'id' => 'no NavyPlugins portfolio',
        ), $attrs);

        global $wpdb;
        $query = $wpdb->prepare("SELECT grid_view_type FROM " . $wpdb->prefix . "pfhub_portfolio_grids WHERE id=%d", $attrs['id']);
        $portfolio_view = $wpdb->get_var($query);

        do_action('pfhub_portfolio_shortcode_scripts', $attrs['id'], $portfolio_view);
        do_action('pfhub_portfolio_localize_scripts', $attrs['id']);

        return self::init_frontend($attrs['id']);
    }

    protected static function init_frontend($id)
    {
        global $wpdb;

        $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "pfhub_portfolio_images WHERE grid_id = '%d' ORDER BY ordering ASC", $id);

        $images = $wpdb->get_results($query);

        $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "pfhub_portfolio_grids WHERE id = '%d' ORDER BY id ASC", $id);

        $portfolio = $wpdb->get_results($query);

        $pfhub_portfolio_get_options = \PfhubPortfolio\Helpers\GridHelper::getDefaultSettings();

        ob_start();

        if (!$portfolio) {
            _e("Portfolio with this ID doesn't exist.", "portfolio-gallery");
            return;
        }

        self::loadFrontEnd($images, $pfhub_portfolio_get_options, $portfolio);

        return ob_get_clean();

    }

    public static function loadFrontEnd($images, $pfhub_portfolio_get_options, $portfolio)
    {
        global $wpdb;

        $portfolioID = absint($portfolio[0]->id);
        if ($portfolioID === 0) {
            wp_die("Undefined Portfolio ID");
        }
        $portfolioeffect = $portfolio[0]->grid_view_type;
        $portfolioposition = $portfolio[0]->sl_position;
        $portfolioCats = $portfolio[0]->categories;
        $portfolioShowSorting = $portfolio[0]->pfhub_portfolio_show_sorting;
        $portfolioShowFiltering = $portfolio[0]->pfhub_portfolio_show_filtering;
        $portfolioShowLoading = $portfolio[0]->show_loading;
        $portfolioLoadingIconype = $portfolio[0]->loading_icon_type;

        $view = $portfolioeffect;
        switch ($view) {
            case 0:
                if ($portfolioShowSorting == 'on') {
                    $sortingFloatToggle = $pfhub_portfolio_get_options["pfhub_portfolio_view0_sorting_float"];
                } else {
                    $sortingFloatToggle = '';
                }
                if ($portfolioShowFiltering == 'on') {
                    $filteringFloatToggle = $pfhub_portfolio_get_options["pfhub_portfolio_view0_filtering_float"];
                } else {
                    $filteringFloatToggle = '';
                }
                $view_slug = \PfhubPortfolio\Helpers\GridHelper::getViewNameById($portfolioID);
                require PFHUB_PORTFOLIO_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'view-grid.php';
                require PFHUB_PORTFOLIO_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'view-grid-css.php';
                break;
            case 1:
                if ($portfolioShowSorting == 'on') {
                    $sortingFloatFullHeight = $pfhub_portfolio_get_options["pfhub_portfolio_view1_sorting_float"];
                } else {
                    $sortingFloatFullHeight = '';
                }
                if ($portfolioShowFiltering == 'on') {
                    $filteringFloatFullHeight = $pfhub_portfolio_get_options["pfhub_portfolio_view1_filtering_float"];
                } else {
                    $filteringFloatFullHeight = '';
                }
                $view_slug = \PfhubPortfolio\Helpers\GridHelper::getViewNameById($portfolioID);
                require PFHUB_PORTFOLIO_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'view-masonry.php';
                require PFHUB_PORTFOLIO_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'view-masonry-css.php';
                break;
            case 2:
                if ($portfolioShowSorting == 'on') {
                    $sortingFloatPopup = $pfhub_portfolio_get_options["pfhub_portfolio_view2_sorting_float"];
                } else {
                    $sortingFloatPopup = '';
                }
                if ($portfolioShowFiltering == 'on') {
                    $filteringFloatPopup = $pfhub_portfolio_get_options["pfhub_portfolio_view2_filtering_float"];
                } else {
                    $filteringFloatPopup = '';
                }
                $view_slug = \PfhubPortfolio\Helpers\GridHelper::getViewNameById($portfolioID);
                require PFHUB_PORTFOLIO_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'view-content-popup.php';
                require PFHUB_PORTFOLIO_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'view-content-popup-css.php';
                break;
            case 3:
                if ($portfolioShowSorting == 'on') {
                    $sortingFloatFullWidth = $pfhub_portfolio_get_options["pfhub_portfolio_view3_sorting_float"];
                } else {
                    $sortingFloatFullWidth = '';
                }
                if ($portfolioShowFiltering == 'on') {
                    $filteringFloatFullWidth = $pfhub_portfolio_get_options["pfhub_portfolio_view3_filtering_float"];
                } else {
                    $filteringFloatFullWidth = '';
                }
                $view_slug = \PfhubPortfolio\Helpers\GridHelper::getViewNameById($portfolioID);
                require PFHUB_PORTFOLIO_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'view-list.php';
                require PFHUB_PORTFOLIO_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'view-list-css.php';
                break;
            case 5:
                $view_slug = \PfhubPortfolio\Helpers\GridHelper::getViewNameById($portfolioID);
                require PFHUB_PORTFOLIO_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'view-content-slider.php';
                require PFHUB_PORTFOLIO_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'view-content-slider-css.php';
                break;
            case 6:
                if ($portfolioShowSorting == 'on') {
                    $sortingFloatLgal = $pfhub_portfolio_get_options["pfhub_portfolio_view6_sorting_float"];
                } else {
                    $sortingFloatLgal = '';
                }
                if ($portfolioShowFiltering == 'on') {
                    $filteringFloatLgal = $pfhub_portfolio_get_options["pfhub_portfolio_view6_filtering_float"];
                } else {
                    $filteringFloatLgal = '';
                }
                $view_slug = \PfhubPortfolio\Helpers\GridHelper::getViewNameById($portfolioID);
                require PFHUB_PORTFOLIO_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'view-image-grid.php';
                require PFHUB_PORTFOLIO_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'view-image-grid-css.php';
                break;
            case 7:
                $portfolioposition = 'on';
                $view_slug = \PfhubPortfolio\Helpers\GridHelper::getViewNameById($portfolioID);
                require PFHUB_PORTFOLIO_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'view-elastic-grid.php';
                require PFHUB_PORTFOLIO_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'view-elastic-grid-css.php';
                break;
        }
    }

    /**
     * Add editor media button
     *
     * @param $context
     *
     * @return string
     */
    public static function add_editor_media_button($context)
    {
        $img = untrailingslashit(PFHUB_PORTFOLIO_PLUGIN_URL) . "/assets/images/admin/smallicon.png";

        $container_id = 'pfhub_portfolio';

        $title = __('Select NavyPlugins Portfolio Gallery to insert into post', 'pfhub_portfolio');

        $button_text = __('Add Portfolio Gallery', 'pfhub_portfolio');

        $context .= '<a class="button thickbox" title="' . $title . '"    href="#TB_inline?width=400&inlineId=' . $container_id . '">
		<span class="wp-media-buttons-icon" style="background: url(' . $img . '); background-repeat: no-repeat; background-position: left bottom;"></span>' . $button_text . '</a>';

        return $context;
    }

    public static function inline_popup_content()
    {
        require PFHUB_PORTFOLIO_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'admin-popup.php';
    }

    public static function frontend_styles($id, $portfolio_view)
    {
        $general_options = GridHelper::getDefaultSettings();

        wp_register_style('portfolio-all-css', plugins_url('../assets/style/portfolio-all.css', __FILE__));
        wp_enqueue_style('portfolio-all-css');

        wp_register_style('style2-os-css', plugins_url('../assets/style/style2-os.css', __FILE__));
        wp_enqueue_style('style2-os-css');

        if (get_option('pfhub_portfolio_lightbox_type') == 'classic') {
            wp_register_style('lightbox-css', plugins_url('../assets/style/lightbox.css', __FILE__));
            wp_enqueue_style('lightbox-css');

            wp_register_style('pfhub_portfolio_colorbox_css', untrailingslashit(PFHUB_PORTFOLIO_PLUGIN_URL) . '/assets/style/colorbox-' . $general_options['pfhub_portfolio_light_box_style'] . '.css');
            wp_enqueue_style('pfhub_portfolio_colorbox_css');
        } elseif (get_option('pfhub_portfolio_lightbox_type') == 'modern') {
            wp_register_style('pfhub_portfolio_resp_lightbox_css', untrailingslashit(PFHUB_PORTFOLIO_PLUGIN_URL) . '/assets/style/responsive_lightbox.css');
            wp_enqueue_style('pfhub_portfolio_resp_lightbox_css');
        }

        wp_enqueue_style('pfhub_portfolio_colorbox_css', untrailingslashit(PFHUB_PORTFOLIO_PLUGIN_URL) . '/assets/style/colorbox-' . $general_options['pfhub_portfolio_light_box_style'] . '.css');

        if ($portfolio_view == '5') {
            wp_register_style('animate-css', plugins_url('../assets/style/animate.min.css', __FILE__));
            wp_enqueue_style('animate-css');
            wp_register_style('liquid-slider-css', plugins_url('../assets/style/liquid-slider.css', __FILE__));
            wp_enqueue_style('liquid-slider-css');
        }
        if ($portfolio_view == '7') {
            wp_register_style('elastic-grid-css', plugins_url('../assets/style/elastic_grid.css', __FILE__));
            wp_enqueue_style('elastic-grid-css');
        }

    }

    /**
     * Enqueue scripts
     * @param $id
     * @param $portfolio_view
     */
    public static function frontend_scripts($id, $portfolio_view)
    {
        $view_slug = GridHelper::getViewNameById($id);
        $general_options = GridHelper::getDefaultSettings();

        if (!wp_script_is('jquery')) {
            wp_enqueue_script('jquery');
        }

        wp_register_script('pfhubPortfolioNeon-min-js', plugins_url('../assets/js/jquery.pfhubPortfolioNeon.min.js', __FILE__), array('jquery'), '1.0.0', true);
        wp_enqueue_script('pfhubPortfolioNeon-min-js');

        if (get_option('pfhub_portfolio_lightbox_type') == 'classic') {
            wp_register_script('jquery.pcolorbox-js', plugins_url('../assets/js/jquery.colorbox.js', __FILE__), array('jquery'), '1.0.0', true);
            wp_enqueue_script('jquery.pcolorbox-js');
        } elseif (get_option('pfhub_portfolio_lightbox_type') == 'modern') {
            wp_register_script('portfolio-resp-lightbox-js', plugins_url('../assets/js/lightbox.js', __FILE__), array('jquery'), '1.0.0', true);
            wp_enqueue_script('portfolio-resp-lightbox-js');

            //wp_register_script('mousewheel-min-js', plugins_url('../assets/js/mousewheel.min.js', __FILE__), array('jquery'), '1.0.0', true);
            //wp_enqueue_script('mousewheel-min-js');

            wp_register_script('froogaloop2-min-js', plugins_url('../assets/js/froogaloop2.min.js', __FILE__), array('jquery'), '1.0.0', true);
            wp_enqueue_script('froogaloop2-min-js');
        }


        wp_register_script('front-end-js-' . $view_slug, plugins_url('../assets/js/view-' . $view_slug . '.js', __FILE__), array('jquery', 'pfhubPortfolioNeon-min-js'), '1.0.0', true);
        wp_enqueue_script('front-end-js-' . $view_slug);

        wp_register_script('portfolio-custom-js', plugins_url('../assets/js/custom.js', __FILE__), array('jquery'), '1.0.0', true);
        wp_enqueue_script('portfolio-custom-js');

        if ($portfolio_view == '5') {
            wp_register_script('easing-js', plugins_url('../assets/js/jquery.easing.min.js', __FILE__), array('jquery'), '1.3.0', true);
            wp_enqueue_script('easing-js');
            wp_register_script('touch_swipe-js', plugins_url('../assets/js/jquery.touchSwipe.min.js', __FILE__), array('jquery'), '1.0.0', true);
            wp_enqueue_script('touch_swipe-js');
            wp_register_script('liquid-slider-js', plugins_url('../assets/js/jquery.liquid-slider.min.js', __FILE__), array('jquery'), '1.0.0', true);
            wp_enqueue_script('liquid-slider-js');
        }

        if ($portfolio_view == '7') {
            wp_register_script('modernizr.custom-js', plugins_url('../assets/js/modernizr.custom.js', __FILE__), array('jquery'), '1.0.0', false);
            wp_enqueue_script('modernizr.custom-js');
            wp_register_script('classie-js', plugins_url('../assets/js/classie.js', __FILE__), array('jquery'), '1.3.0', false);
            wp_enqueue_script('classie-js');
            wp_register_script('jquery.elastislide-js', plugins_url('../assets/js/jquery.elastislide.js', __FILE__), array('jquery'), '1.0.0', false);
            wp_enqueue_script('jquery.elastislide-js');
            wp_register_script('hoverdir.js', plugins_url('../assets/js/jquery.hoverdir.js', __FILE__), array('jquery'), '1.0.0', false);
            wp_enqueue_script('hoverdir.js');
            wp_register_script('portfolio-gallery-elastic_grid-js', plugins_url('../assets/js/elastic_grid.js', __FILE__), array('jquery'), '1.3.0', false);
            wp_enqueue_script('portfolio-gallery-elastic_grid-js');
        }

    }

    public static function localize_scripts($id)
    {
        $portfolio_param = GridHelper::getDefaultSettings();
        $view_slug = GridHelper::getViewNameById($id);
        global $wpdb;
        $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "pfhub_portfolio_images  WHERE grid_id = '%d' ORDER BY ordering ASC", $id);
        $images[$id] = $wpdb->get_results($query);

        $lightbox = array(
            'lightbox_transition' => $portfolio_param['pfhub_portfolio_light_box_transition'],
            'lightbox_speed' => $portfolio_param['pfhub_portfolio_light_box_speed'],
            'lightbox_fadeOut' => $portfolio_param['pfhub_portfolio_light_box_fadeout'],
            'lightbox_title' => $portfolio_param['pfhub_portfolio_light_box_title'],
            'lightbox_scalePhotos' => $portfolio_param['pfhub_portfolio_light_box_scalephotos'],
            'lightbox_scrolling' => $portfolio_param['pfhub_portfolio_light_box_scrolling'],
            'lightbox_opacity' => ($portfolio_param['pfhub_portfolio_light_box_opacity'] / 100) + 0.001,
            'lightbox_open' => $portfolio_param['pfhub_portfolio_light_box_open'],
            'lightbox_returnFocus' => $portfolio_param['pfhub_portfolio_light_box_returnfocus'],
            'lightbox_trapFocus' => $portfolio_param['pfhub_portfolio_light_box_trapfocus'],
            'lightbox_fastIframe' => $portfolio_param['pfhub_portfolio_light_box_fastiframe'],
            'lightbox_preloading' => $portfolio_param['pfhub_portfolio_light_box_preloading'],
            'lightbox_overlayClose' => $portfolio_param['pfhub_portfolio_light_box_overlayclose'],
            'lightbox_escKey' => $portfolio_param['pfhub_portfolio_light_box_esckey'],
            'lightbox_arrowKey' => $portfolio_param['pfhub_portfolio_light_box_arrowkey'],
            'lightbox_loop' => $portfolio_param['pfhub_portfolio_light_box_loop'],
            'lightbox_closeButton' => $portfolio_param['pfhub_portfolio_light_box_closebutton'],
            'lightbox_previous' => $portfolio_param['pfhub_portfolio_light_box_previous'],
            'lightbox_next' => $portfolio_param['pfhub_portfolio_light_box_next'],
            'lightbox_close' => $portfolio_param['pfhub_portfolio_light_box_close'],
            'lightbox_html' => $portfolio_param['pfhub_portfolio_light_box_html'],
            'lightbox_photo' => $portfolio_param['pfhub_portfolio_light_box_photo'],
            'lightbox_innerWidth' => $portfolio_param['pfhub_portfolio_light_box_innerwidth'],
            'lightbox_innerHeight' => $portfolio_param['pfhub_portfolio_light_box_innerheight'],
            'lightbox_initialWidth' => $portfolio_param['pfhub_portfolio_light_box_initialwidth'],
            'lightbox_initialHeight' => $portfolio_param['pfhub_portfolio_light_box_initialheight'],
            'lightbox_slideshow' => $portfolio_param['pfhub_portfolio_light_box_slideshow'],
            'lightbox_slideshowSpeed' => $portfolio_param['pfhub_portfolio_light_box_slideshowspeed'],
            'lightbox_slideshowAuto' => $portfolio_param['pfhub_portfolio_light_box_slideshowauto'],
            'lightbox_slideshowStart' => $portfolio_param['pfhub_portfolio_light_box_slideshowstart'],
            'lightbox_slideshowStop' => $portfolio_param['pfhub_portfolio_light_box_slideshowstop'],
            'lightbox_fixed' => $portfolio_param['pfhub_portfolio_light_box_fixed'],
            'lightbox_reposition' => $portfolio_param['pfhub_portfolio_light_box_reposition'],
            'lightbox_retinaImage' => $portfolio_param['pfhub_portfolio_light_box_retinaimage'],
            'lightbox_retinaUrl' => $portfolio_param['pfhub_portfolio_light_box_retinaurl'],
            'lightbox_retinaSuffix' => $portfolio_param['pfhub_portfolio_light_box_retinasuffix'],
            'lightbox_maxWidth' => $portfolio_param['pfhub_portfolio_light_box_maxwidth'],
            'lightbox_maxHeight' => $portfolio_param['pfhub_portfolio_light_box_maxheight'],
            'lightbox_sizeFix' => $portfolio_param['pfhub_portfolio_light_box_size_fix']
        );

        if ($portfolio_param['pfhub_portfolio_light_box_size_fix'] == 'false') {
            $lightbox['lightbox_width'] = '';
        } else {
            $lightbox['lightbox_width'] = $portfolio_param['pfhub_portfolio_light_box_width'];
        }

        if ($portfolio_param['pfhub_portfolio_light_box_size_fix'] == 'false') {
            $lightbox['lightbox_height'] = '';
        } else {
            $lightbox['lightbox_height'] = $portfolio_param['pfhub_portfolio_light_box_height'];
        }

        $pos = $portfolio_param['pfhub_portfolio_slider_title_position'];
        switch ($pos) {
            case 1:
                $lightbox['lightbox_top'] = '10%';
                $lightbox['lightbox_bottom'] = 'false';
                $lightbox['lightbox_left'] = '10%';
                $lightbox['lightbox_right'] = 'false';
                break;
            case 2:
                $lightbox['lightbox_top'] = '10%';
                $lightbox['lightbox_bottom'] = 'false';
                $lightbox['lightbox_left'] = 'false';
                $lightbox['lightbox_right'] = 'false';
                break;
            case 3:
                $lightbox['lightbox_top'] = '10%';
                $lightbox['lightbox_bottom'] = 'false';
                $lightbox['lightbox_left'] = 'false';
                $lightbox['lightbox_right'] = '10%';
                break;
            case 4:
                $lightbox['lightbox_top'] = 'false';
                $lightbox['lightbox_bottom'] = 'false';
                $lightbox['lightbox_left'] = '10%';
                $lightbox['lightbox_right'] = 'false';
                break;
            case 5:
                $lightbox['lightbox_top'] = 'false';
                $lightbox['lightbox_bottom'] = 'false';
                $lightbox['lightbox_left'] = 'false';
                $lightbox['lightbox_right'] = 'false';
                break;
            case 6:
                $lightbox['lightbox_top'] = 'false';
                $lightbox['lightbox_bottom'] = 'false';
                $lightbox['lightbox_left'] = 'false';
                $lightbox['lightbox_right'] = '10%';
                break;
            case 7:
                $lightbox['lightbox_top'] = 'false';
                $lightbox['lightbox_bottom'] = '10%';
                $lightbox['lightbox_left'] = '10%';
                $lightbox['lightbox_right'] = 'false';
                break;
            case 8:
                $lightbox['lightbox_top'] = 'false';
                $lightbox['lightbox_bottom'] = '10%';
                $lightbox['lightbox_left'] = 'false';
                $lightbox['lightbox_right'] = 'false';
                break;
            case 9:
                $lightbox['lightbox_top'] = 'false';
                $lightbox['lightbox_bottom'] = '10%';
                $lightbox['lightbox_left'] = 'false';
                $lightbox['lightbox_right'] = '10%';
                break;
        }

        $images_obj = array();

        foreach ($images[$id] as $image) {
            $thumbnails = $image->image_url;
            $thumbnails = substr($thumbnails, 0, -1);
            $thumbnails = explode(';', $thumbnails);
            $thumbs = array();
            $larg_images = array();
            foreach ($thumbnails as $key => $thumbnail) {
                if (GridHelper::getVideoType($thumbnail) == 'image') {
                    if ($key == 0) {
                        $smal_img = esc_url(GridHelper::getImage($thumbnail, 'medium', false));
                    } else {
                        $smal_img = esc_url(GridHelper::getImage($thumbnail, array(), true));
                    }
                    $big_img = $thumbnail;
                } elseif (GridHelper::getVideoType($thumbnail) == 'youtube') {
                    $videourl = GridHelper::getVideoId($thumbnail);
                    $smal_img = esc_url("//img.youtube.com/vi/" . $videourl[0] . "/mqdefault.jpg");
                    $videourl = GridHelper::getVideoId($thumbnail);
                    $big_img = "https://www.youtube.com/embed/" . $videourl[0];
                } elseif (GridHelper::getVideoType($thumbnail) == 'vimeo') {
                    $videourl = GridHelper::getVideoId($thumbnail);
                    $hash = unserialize(wp_remote_fopen("https://vimeo.com/api/v2/video/" . $videourl[0] . ".php"));
                    $smal_img = esc_url($hash[0]['thumbnail_large']);
                    $videourl = GridHelper::getVideoId($thumbnail);
                    $big_img = "https://player.vimeo.com/video/" . $videourl[0];
                }
                array_push($thumbs, $smal_img);
                array_push($larg_images, $big_img);
            }
            $categories = str_replace(" ", "_", $image->category);
            $categories = explode(',', $categories);
            if ($image->link_target == 'on') {
                $target = '_blank';
            } else {
                $target = '';
            }
            $images_parent_obj = array(
                'title' => $image->name,
                'description' => $image->description,
                'thumbnail' => $thumbs,
                'large' => $larg_images,
                'button_list' => array(
                    array(
                        'title' => $portfolio_param['pfhub_portfolio_view7_expand_block_button_text'],
                        'url' => $image->media_url,
                        'new_window' => $target
                    ),
                ),
                'tags' => $categories
            );
            array_push($images_obj, $images_parent_obj);
        }

        $lightbox_options = array(
            'pfhub_portfolio_lightbox_slideAnimationType' => $portfolio_param['pfhub_portfolio_lightbox_slideAnimationType'],
            'pfhub_portfolio_lightbox_lightboxView' => get_option('pfhub_portfolio_lightbox_lightboxView'),
            'pfhub_portfolio_lightbox_speed_new' => get_option('pfhub_portfolio_lightbox_speed_new'),
            'pfhub_portfolio_lightbox_width_new' => $portfolio_param['pfhub_portfolio_lightbox_width_new'],
            'pfhub_portfolio_lightbox_height_new' => $portfolio_param['pfhub_portfolio_lightbox_height_new'],
            'pfhub_portfolio_lightbox_videoMaxWidth' => $portfolio_param['pfhub_portfolio_lightbox_videoMaxWidth'],
            'pfhub_portfolio_lightbox_overlayDuration' => $portfolio_param['pfhub_portfolio_lightbox_overlayDuration'],
            'pfhub_portfolio_lightbox_overlayClose_new' => get_option('pfhub_portfolio_lightbox_overlayClose_new'),
            'pfhub_portfolio_lightbox_loop_new' => get_option('pfhub_portfolio_lightbox_loop_new'),
            'pfhub_portfolio_lightbox_escKey_new' => $portfolio_param['pfhub_portfolio_lightbox_escKey_new'],
            'pfhub_portfolio_lightbox_keyPress_new' => $portfolio_param['pfhub_portfolio_lightbox_keyPress_new'],
            'pfhub_portfolio_lightbox_arrows' => $portfolio_param['pfhub_portfolio_lightbox_arrows'],
            'pfhub_portfolio_lightbox_mouseWheel' => $portfolio_param['pfhub_portfolio_lightbox_mouseWheel'],
            'pfhub_portfolio_lightbox_download' => $portfolio_param['pfhub_portfolio_lightbox_download'],
            'pfhub_portfolio_lightbox_showCounter' => $portfolio_param['pfhub_portfolio_lightbox_showCounter'],
            'pfhub_portfolio_lightbox_nextHtml' => $portfolio_param['pfhub_portfolio_lightbox_nextHtml'],
            'pfhub_portfolio_lightbox_prevHtml' => $portfolio_param['pfhub_portfolio_lightbox_prevHtml'],
            'pfhub_portfolio_lightbox_sequence_info' => $portfolio_param['pfhub_portfolio_lightbox_sequence_info'],
            'pfhub_portfolio_lightbox_sequenceInfo' => $portfolio_param['pfhub_portfolio_lightbox_sequenceInfo'],
            'pfhub_portfolio_lightbox_slideshow_new' => $portfolio_param['pfhub_portfolio_lightbox_slideshow_new'],
            'pfhub_portfolio_lightbox_slideshow_auto_new' => $portfolio_param['pfhub_portfolio_lightbox_slideshow_auto_new'],
            'pfhub_portfolio_lightbox_slideshow_speed_new' => $portfolio_param['pfhub_portfolio_lightbox_slideshow_speed_new'],
            'pfhub_portfolio_lightbox_slideshow_start_new' => $portfolio_param['pfhub_portfolio_lightbox_slideshow_start_new'],
            'pfhub_portfolio_lightbox_slideshow_stop_new' => $portfolio_param['pfhub_portfolio_lightbox_slideshow_stop_new'],
            'pfhub_portfolio_lightbox_watermark' => $portfolio_param['pfhub_portfolio_lightbox_watermark'],
            'pfhub_portfolio_lightbox_socialSharing' => $portfolio_param['pfhub_portfolio_lightbox_socialSharing'],
            'pfhub_portfolio_lightbox_facebookButton' => $portfolio_param['pfhub_portfolio_lightbox_facebookButton'],
            'pfhub_portfolio_lightbox_twitterButton' => $portfolio_param['pfhub_portfolio_lightbox_twitterButton'],
            'pfhub_portfolio_lightbox_googleplusButton' => $portfolio_param['pfhub_portfolio_lightbox_googleplusButton'],
            'pfhub_portfolio_lightbox_pinterestButton' => $portfolio_param['pfhub_portfolio_lightbox_pinterestButton'],
            'pfhub_portfolio_lightbox_linkedinButton' => $portfolio_param['pfhub_portfolio_lightbox_linkedinButton'],
            'pfhub_portfolio_lightbox_tumblrButton' => $portfolio_param['pfhub_portfolio_lightbox_tumblrButton'],
            'pfhub_portfolio_lightbox_redditButton' => $portfolio_param['pfhub_portfolio_lightbox_redditButton'],
            'pfhub_portfolio_lightbox_bufferButton' => $portfolio_param['pfhub_portfolio_lightbox_bufferButton'],
            'pfhub_portfolio_lightbox_diggButton' => $portfolio_param['pfhub_portfolio_lightbox_diggButton'],
            'pfhub_portfolio_lightbox_vkButton' => $portfolio_param['pfhub_portfolio_lightbox_vkButton'],
            'pfhub_portfolio_lightbox_yummlyButton' => $portfolio_param['pfhub_portfolio_lightbox_yummlyButton'],
            'pfhub_portfolio_lightbox_watermark_text' => $portfolio_param['pfhub_portfolio_lightbox_watermark_text'],
            'pfhub_portfolio_lightbox_watermark_textColor' => $portfolio_param['pfhub_portfolio_lightbox_watermark_textColor'],
            'pfhub_portfolio_lightbox_watermark_textFontSize' => $portfolio_param['pfhub_portfolio_lightbox_watermark_textFontSize'],
            'pfhub_portfolio_lightbox_watermark_containerBackground' => $portfolio_param['pfhub_portfolio_lightbox_watermark_containerBackground'],
            'pfhub_portfolio_lightbox_watermark_containerOpacity' => $portfolio_param['pfhub_portfolio_lightbox_watermark_containerOpacity'],
            'pfhub_portfolio_lightbox_watermark_containerWidth' => $portfolio_param['pfhub_portfolio_lightbox_watermark_containerWidth'],
            'pfhub_portfolio_lightbox_watermark_position_new' => $portfolio_param['pfhub_portfolio_lightbox_watermark_position_new'],
            'pfhub_portfolio_lightbox_watermark_opacity' => $portfolio_param['pfhub_portfolio_lightbox_watermark_opacity'],
            'pfhub_portfolio_lightbox_watermark_margin' => $portfolio_param['pfhub_portfolio_lightbox_watermark_margin'],
            'pfhub_portfolio_lightbox_watermark_img_src_new' => $portfolio_param['pfhub_portfolio_lightbox_watermark_img_src_new'],
        );

        if (get_option('pfhub_portfolio_lightbox_type') == 'classic') {
            wp_localize_script('jquery.pcolorbox-js', 'lightbox_obj', $lightbox);
        } elseif (get_option('pfhub_portfolio_lightbox_type') == 'modern') {
            list($r, $g, $b) = array_map('hexdec', str_split($portfolio_param['pfhub_portfolio_lightbox_watermark_containerBackground'], 2));
            $titleopacity = $portfolio_param["pfhub_portfolio_lightbox_watermark_containerOpacity"] / 100;
            $lightbox_options['pfhub_portfolio_watermark_container_bg_color'] = 'rgba(' . $r . ',' . $g . ',' . $b . ',' . $titleopacity . ')';
            wp_localize_script('portfolio-resp-lightbox-js', 'portfolio_resp_lightbox_obj', $lightbox_options);
            wp_localize_script('portfolio-custom-js', 'is_watermark', $portfolio_param['pfhub_portfolio_lightbox_watermark']);
            wp_localize_script('portfolio-resp-lightbox-js', 'portfolioGalleryDisableRightClickLightbox', get_option('pfhub_portfolio_disable_right_click'));
        }

        wp_localize_script('portfolio-custom-js', 'portfolio_lightbox_type', get_option('pfhub_portfolio_lightbox_type'));
        wp_localize_script('front-end-js-' . $view_slug, 'portfolio_param_obj', $portfolio_param);
        wp_localize_script('front-end-js-' . $view_slug, 'images_obj_' . $id, $images_obj);
        wp_localize_script('portfolio-gallery-elastic_grid-js', 'show_filter_all_text', $portfolio_param['pfhub_portfolio_view7_show_all_filter_button']);
        wp_localize_script('portfolio-gallery-elastic_grid-js', 'elements_margin', $portfolio_param['pfhub_portfolio_view7_element_margin']);
        wp_localize_script('portfolio-custom-js', 'portfolioGalleryDisableRightClick', get_option('pfhub_portfolio_disable_right_click'));
        wp_localize_script('portfolio-gallery-elastic_grid-js', 'portfolioGalleryDisableRightClickElastic', get_option('pfhub_portfolio_disable_right_click'));

    }
}
