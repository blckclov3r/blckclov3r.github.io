<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<section id="pfhub_portfolio_content_<?php echo esc_attr($portfolioID); ?>"
         class=" portfolio-gallery-content <?php if ($portfolioShowSorting == 'on') {
             echo 'sortingActive ';
         }
         if ($portfolioShowFiltering == 'on') {
             echo 'filteringActive';
         } ?>"
         data-image-behaviour="<?php echo esc_attr($pfhub_portfolio_get_options['pfhub_portfolio_port_natural_size_contentpopup']); ?>">
    <div id="pfhub_portfolio-container-loading-overlay_<?php echo esc_attr($portfolioID); ?>"></div>
    <?php if (($sortingFloatPopup == 'left' && $filteringFloatPopup == 'left') || ($sortingFloatPopup == 'right' && $filteringFloatPopup == 'right')) { ?>
    <div id="pfhub_portfolio_options_and_filters_<?php echo esc_attr($portfolioID); ?>">
        <?php } ?>
        <?php if ($portfolioShowSorting == "on") { ?>
            <div id="pfhub_portfolio_options_<?php echo esc_attr($portfolioID); ?>"
                 data-sorting-position="<?php echo esc_attr($pfhub_portfolio_get_options["pfhub_portfolio_view2_sorting_float"]); ?>">
                <ul class="sort-by-button-group clearfix">
                    <?php if ($pfhub_portfolio_get_options["pfhub_portfolio_view2_sorting_name_by_default"] != ''): ?>
                        <li><a href="#sortBy=original-order" data-option-value="original-order" class="selected"
                               data><?php echo esc_attr($pfhub_portfolio_get_options["pfhub_portfolio_view2_sorting_name_by_default"]); ?></a>
                        </li>
                    <?php endif; ?>
                    <?php if ($pfhub_portfolio_get_options["pfhub_portfolio_view2_sorting_name_by_id"] != ''): ?>
                        <li><a href="#sortBy=load_date"
                               data-option-value="load_date"><?php echo esc_attr($pfhub_portfolio_get_options["pfhub_portfolio_view2_sorting_name_by_id"]); ?></a>
                        </li>
                    <?php endif; ?>
                    <?php if ($pfhub_portfolio_get_options["pfhub_portfolio_view2_sorting_name_by_name"] != ''): ?>
                        <li><a href="#sortBy=name"
                               data-option-value="name"><?php echo esc_attr($pfhub_portfolio_get_options["pfhub_portfolio_view2_sorting_name_by_name"]); ?></a>
                        </li>
                    <?php endif; ?>
                    <?php if ($pfhub_portfolio_get_options["pfhub_portfolio_view2_sorting_name_by_random"] != ''): ?>
                        <li id="random"><a data-option-value="random"
                                           href='#random'><?php echo esc_attr($pfhub_portfolio_get_options["pfhub_portfolio_view2_sorting_name_by_random"]); ?></a>
                        </li>
                    <?php endif; ?>
                </ul>
                <ul id="port-sort-direction" class="option-set clearfix">
                    <?php if ($pfhub_portfolio_get_options["pfhub_portfolio_view2_sorting_name_by_asc"] != ''): ?>
                        <li><a href="#sortAscending=true" data-option-value="true" data-option-key="number"
                               class="selected"><?php echo esc_attr($pfhub_portfolio_get_options["pfhub_portfolio_view2_sorting_name_by_asc"]); ?></a>
                        </li>
                    <?php endif; ?>
                    <?php if ($pfhub_portfolio_get_options["pfhub_portfolio_view2_sorting_name_by_desc"] != ''): ?>
                        <li><a href="#sortAscending=false" data-option-key="number"
                               data-option-value="false"><?php echo esc_attr($pfhub_portfolio_get_options["pfhub_portfolio_view2_sorting_name_by_desc"]); ?></a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        <?php }
        if ($portfolioShowFiltering == "on") { ?>
            <div id="pfhub_portfolio_filters_<?php echo esc_attr($portfolioID); ?>"
                 data-filtering-position="<?php echo esc_attr($pfhub_portfolio_get_options["pfhub_portfolio_view2_filtering_float"]); ?>">
                <ul>
                    <li rel="*">
                        <a><?php echo esc_attr($pfhub_portfolio_get_options["pfhub_portfolio_view2_cat_all"]); ?></a>
                    </li>
                    <?php
                    $portfolioCats = explode(",", $portfolioCats);
                    foreach ($portfolioCats as $portfolioCatsValue) {
                        if (!empty($portfolioCatsValue)) {
                            ?>
                            <li rel=".<?php echo str_replace(" ", "_", $portfolioCatsValue); ?>">
                                <a><?php echo str_replace("_", " ", $portfolioCatsValue); ?></a></li>
                            <?php
                        }
                    }
                    ?>
                </ul>
            </div>
        <?php } ?>
        <?php if (($sortingFloatPopup == 'left' && $filteringFloatPopup == 'left') || ($sortingFloatPopup == 'right' && $filteringFloatPopup == 'right')) { ?>
    </div>
<?php } ?>
    <div id="pfhub_portfolio_container_<?php echo esc_attr($portfolioID); ?>"
         class="pfhub_portfolio_container super-list variable-sizes clearfix view-<?php echo esc_attr($view_slug); ?>" <?php if ($sortingFloatPopup == "top" && $filteringFloatPopup == "top") {
        echo "style='clear: both;'";
    } ?> data-show-loading="<?php echo esc_attr($portfolioShowLoading); ?>"
         data-show-center="<?php echo esc_attr($portfolioposition); ?>">
        <?php
        foreach ($images as $key => $row) {
            $link = $row->media_url;
            $descnohtml = strip_tags($row->description);
            $result = substr($descnohtml, 0, 50);
            $catForFilter = explode(",", $row->category);
            ?>
            <div id="pfhub_portfolio_pupup_element_<?php echo esc_attr($row->id); ?>_child"
                 class="portelement portelement_<?php echo esc_attr($portfolioID); ?>  <?php foreach ($catForFilter as $catForFilterValue) {
                     echo str_replace(" ", "_", $catForFilterValue) . " ";
                 } ?>" tabindex="0" data-symbol="<?php echo esc_attr($row->name); ?>"
                 data-category="alkaline-earth">
                <p style="display:none;" class="load_date"><?php echo esc_attr($row->publish_date); ?></p>
                <p style="display:none;" class="number"><?php echo esc_attr($row->id); ?></p>
                <p style="display:none;" class="random"><?php echo esc_attr($row->id); ?></p>
                <div class="image-block image-block_<?php echo esc_attr($portfolioID); ?>">
                    <?php $imgurl = explode(";", $row->image_url); ?>
                    <?php if ($row->image_url != ';') {
                        switch (\PfhubPortfolio\Helpers\GridHelper::getVideoType($imgurl[0])) {
                            case 'image': ?>
                                <img alt="<?php echo esc_attr($row->name); ?>"
                                     id="wd-cl-img<?php echo esc_attr($key); ?>"
                                     src="<?php if ($pfhub_portfolio_get_options['pfhub_portfolio_port_natural_size_contentpopup'] == 'resize') {
                                         echo esc_url(\PfhubPortfolio\Helpers\GridHelper::getImage($imgurl[0], array(
                                             $pfhub_portfolio_get_options['pfhub_portfolio_view2_element_width'],
                                             $pfhub_portfolio_get_options['pfhub_portfolio_view2_element_height']
                                         ), false));
                                     } else {
                                         echo esc_url($imgurl[0]);
                                     } ?>"/>
                                <?php
                                break;
                            case 'youtube':
                                $videourl = \PfhubPortfolio\Helpers\GridHelper::getVideoId($imgurl[0]); ?>
                                <img alt="<?php echo esc_attr($row->name); ?>"
                                     id="wd-cl-img<?php echo esc_attr($key); ?>"
                                     src="//img.youtube.com/vi/<?php echo esc_attr($videourl[0]); ?>/mqdefault.jpg"/>
                                <?php
                                break;
                            case 'vimeo':
                                $videourl = \PfhubPortfolio\Helpers\GridHelper::getVideoId($imgurl[0]);
                                $hash = unserialize(wp_remote_fopen("https://vimeo.com/api/v2/video/" . $videourl[0] . ".php"));
                                $imgsrc = $hash[0]['thumbnail_large'];
                                ?>
                                <img alt="<?php echo esc_attr($row->name); ?>"
                                     src="<?php echo esc_attr($imgsrc); ?>"/>
                                <?php break;

                        }
                    } else { ?>
                        <img alt="<?php echo esc_attr($row->name); ?>" id="wd-cl-img<?php echo esc_attr($key); ?>"
                             src="images/noimage.jpg"/>
                        <?php
                    } ?>
                    <div class="image-overlay"><a title="<?php echo esc_attr($row->name); ?>"
                                                  href="#<?php echo esc_attr($row->id); ?>"></a></div>
                </div>
                <?php if ($row->name != '' || $row->media_url != ''): ?>
                    <div class="title-block_<?php echo $portfolioID; ?>">
                        <h3 class="name" title="<?php echo esc_attr($row->name); ?>"><?php echo $row->name; ?></h3>
                        <?php if ($pfhub_portfolio_get_options["pfhub_portfolio_view2_element_show_linkbutton"] == 'on' && $link != '') { ?>
                            <div class="button-block"><a
                                        href="<?php echo esc_url($row->media_url); ?>" <?php if ($row->link_target == "on") {
                                    echo 'target="_blank"';
                                } ?> ><?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view2_element_linkbutton_text"]; ?></a>
                            </div>
                        <?php } ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php
        } ?>
        <div style="clear:both;"></div>
    </div>
    <div style="clear:both"></div>
</section>
<ul id="pfhub_portfolio_popup_list_<?php echo esc_attr($portfolioID); ?>" class="pfhub_portfolio_popup_list">
    <?php
    $changePopup = 1;
    foreach ($images as $key => $row) {
        $imgurl = explode(";", $row->image_url);
        array_pop($imgurl);
        $link = $row->media_url;
        $descnohtml = strip_tags($row->description);
        $result = substr($descnohtml, 0, 50);
        $catForFilter = explode(",", $row->category);
        ?>
        <li class="pupup-element <?php foreach ($catForFilter as $catForFilterValue) {
            echo str_replace(" ", "_", $catForFilterValue) . " ";
        } ?>" id="pfhub_portfolio_pupup_element_<?php echo esc_attr($row->id); ?>">
            <div class="heading-navigation heading-navigation_<?php echo esc_attr($portfolioID); ?>">
                <div style="display: inline-block; float: left;">
                    <div class="left-change"><a href="#<?php echo $changePopup - 1; ?>"
                                                data-popupid="#<?php echo $row->id; ?>"><</a></div>
                    <div class="right-change"><a href="#<?php echo $changePopup + 1; ?>"
                                                 data-popupid="#<?php echo $row->id; ?>">></a></div>
                </div>
                <?php $changePopup = $changePopup + 1; ?>
                <a href="#close" class="close"></a>
                <div style="clear:both;"></div>
            </div>
            <div class="pfhub-portfolio-popup-wrapper pfhub-portfolio-popup-wrapper_<?php echo esc_attr($portfolioID); ?>">
                <div class="image-block_<?php echo esc_attr($portfolioID); ?> image-block">
                    <?php

                    if ($row->image_url != ';') {
                        switch (\PfhubPortfolio\Helpers\GridHelper::getVideoType($imgurl[0])) {
                            case 'image':
                                ?>
                                <img alt="<?php echo esc_attr($row->name); ?>"
                                     id="wd-cl-img<?php echo esc_attr($key); ?>"
                                     src="<?php echo esc_attr($imgurl[0]); ?>"/>
                                <?php
                                break;
                            case 'youtube':
                                $videourl = \PfhubPortfolio\Helpers\GridHelper::getVideoId($imgurl[0]);
                                ?>
                                <iframe
                                        src="//www.youtube.com/embed/<?php echo esc_attr($videourl[0]); ?>?modestbranding=1&showinfo=0"
                                        frameborder="0" allowfullscreen></iframe>
                                <?php
                                break;
                            case 'vimeo':
                                $videourl = \PfhubPortfolio\Helpers\GridHelper::getVideoId($imgurl[0]);
                                ?>
                                <iframe
                                        src="//player.vimeo.com/video/<?php echo esc_attr($videourl[0]); ?>?title=0&amp;byline=0&amp;portrait=0"
                                        frameborder="0" webkitallowfullscreen mozallowfullscreen
                                        allowfullscreen></iframe>
                                <?php break;
                        }
                    } else { ?>
                        <img alt="<?php echo esc_atr($row->name); ?>" id="wd-cl-img<?php echo esc_attr($key); ?>"
                             src="images/noimage.jpg"/>
                        <?php
                    } ?>
                </div>
                <div class="right-block">
                    <?php if ($pfhub_portfolio_get_options["pfhub_portfolio_view2_show_popup_title"] == 'on') { ?>
                        <h3
                                class="title"><?php echo $row->name; ?></h3><?php } ?>

                    <?php if ($pfhub_portfolio_get_options["pfhub_portfolio_view2_thumbs_position"] == 'before' and $pfhub_portfolio_get_options["pfhub_portfolio_view2_show_thumbs"] == 'on' && count($imgurl) != 1) { ?>
                        <div>
                            <ul class="thumbs-list thumbs-list_<?php echo esc_attr($portfolioID); ?>">
                                <?php
                                foreach ($imgurl as $key => $img) {
                                    ?>
                                    <li>
                                        <?php
                                        switch (\PfhubPortfolio\Helpers\GridHelper::getVideoType($img)) {
                                            case 'image':
                                                ?>

                                                <a href="<?php echo esc_url($img); ?>"
                                                   class="img-thumb"
                                                   title="<?php echo esc_attr($row->name); ?>"><img
                                                            src="<?php echo esc_url(\PfhubPortfolio\Helpers\GridHelper::getImage($img, array(
                                                                $pfhub_portfolio_get_options['pfhub_portfolio_view2_thumbs_width'],
                                                                $pfhub_portfolio_get_options['pfhub_portfolio_view2_thumbs_height']
                                                            ), true)); ?>"></a>

                                                <?php
                                                break;
                                            case 'youtube':
                                                $videourl = \PfhubPortfolio\Helpers\GridHelper::getVideoId($img); ?>
                                                <a href="https://www.youtube.com/embed/<?php echo esc_attr($videourl[0]); ?>"
                                                   class="video-thumb"
                                                   title="<?php echo esc_attr($row->name); ?>"
                                                   style="position:relative">
                                                    <img
                                                            src="//img.youtube.com/vi/<?php echo esc_attr($videourl[0]); ?>/mqdefault.jpg">
                                                    <div class="play-icon youtube-icon"
                                                         title="<?php echo esc_attr($videourl[0]); ?>"></div>
                                                </a>
                                                <?php break;
                                            case 'vimeo':
                                                $videourl = \PfhubPortfolio\Helpers\GridHelper::getVideoId($img);
                                                $hash = unserialize(wp_remote_fopen("https://vimeo.com/api/v2/video/" . $videourl[0] . ".php"));
                                                $imgsrc = $hash[0]['thumbnail_large'];
                                                ?>
                                                <a class=" video-thumb"
                                                   href="http://player.vimeo.com/video/<?php echo esc_attr($videourl[0]); ?>"
                                                   title="<?php echo esc_attr($row->name); ?>"
                                                   style="position:relative">
                                                    <img src="<?php echo esc_attr($imgsrc); ?>"
                                                         alt="<?php echo esc_attr($row->name); ?>"/>
                                                    <div class="play-icon vimeo-icon"
                                                         title="<?php echo esc_attr($videourl[0]); ?>"></div>
                                                </a>
                                                <?php
                                                break;

                                        }
                                        ?>
                                    </li>
                                    <?php
                                } ?>
                            </ul>
                        </div>
                    <?php } ?>

                    <?php if ($pfhub_portfolio_get_options["pfhub_portfolio_view2_show_description"] == 'on') { ?>
                        <div class="description"><?php echo $row->description; ?></div><?php } ?>

                    <?php if ($pfhub_portfolio_get_options["pfhub_portfolio_view2_thumbs_position"] == 'after' and $pfhub_portfolio_get_options["pfhub_portfolio_view2_show_thumbs"] == 'on' && count($imgurl) != 1) { ?>
                        <div>
                            <ul class="thumbs-list_<?php echo esc_attr($portfolioID); ?>">
                                <?php
                                foreach ($imgurl as $key => $img) {
                                    ?>
                                    <li>
                                        <?php
                                        switch (\PfhubPortfolio\Helpers\GridHelper::getVideoType($img)) {
                                            case 'image':
                                                ?>

                                                <a href="<?php echo esc_attr($row->media_url); ?>"
                                                   class="img-thumb"
                                                   title="<?php echo esc_attr($row->name); ?>"><img
                                                            src="<?php echo esc_attr($img); ?>"></a>

                                                <?php
                                                break;
                                            case 'youtube':
                                                $videourl = \PfhubPortfolio\Helpers\GridHelper::getVideoId($img); ?>
                                                <a href="https://www.youtube.com/embed/<?php echo $videourl[0]; ?>"
                                                   class=" video-thumb"
                                                   title="<?php echo esc_attr($row->name); ?>"
                                                   style="position:relative">
                                                    <img
                                                            src="//img.youtube.com/vi/<?php echo esc_attr($videourl[0]); ?>/mqdefault.jpg">
                                                    <div class="play-icon youtube-icon"
                                                         title="<?php echo esc_attr($videourl[0]); ?>"></div>
                                                </a>
                                                <?php break;
                                            case 'vimeo':
                                                $videourl = \PfhubPortfolio\Helpers\GridHelper::getVideoId($img);
                                                $hash = unserialize(wp_remote_fopen("https://vimeo.com/api/v2/video/" . $videourl[0] . ".php"));
                                                $imgsrc = $hash[0]['thumbnail_large'];
                                                ?>
                                                <a class="video-thumb"
                                                   href="http://player.vimeo.com/video/<?php echo esc_attr($videourl[0]); ?>"
                                                   title="<?php echo esc_attr($row->name); ?>"
                                                   style="position:relative">
                                                    <img src="<?php echo esc_attr($imgsrc); ?>"
                                                         alt="<?php echo esc_attr($row->name); ?>"/>
                                                    <div class="play-icon vimeo-icon"
                                                         title="<?php echo esc_attr($videourl[0]); ?>"></div>
                                                </a>
                                                <?php
                                                break;

                                        }
                                        ?>
                                    </li>
                                    <?php
                                } ?>
                            </ul>
                        </div>
                    <?php } ?>

                    <?php if ($pfhub_portfolio_get_options["pfhub_portfolio_view2_show_popup_linkbutton"] == 'on' && $link != '') { ?>
                        <div class="button-block">
                            <a href="<?php echo esc_url($link); ?>" <?php if ($row->link_target == "on") {
                                echo 'target="_blank"';
                            } ?>><?php echo esc_attr($pfhub_portfolio_get_options["pfhub_portfolio_view2_popup_linkbutton_text"]); ?></a>
                        </div>
                    <?php } ?>
                    <div style="clear:both;"></div>
                </div>
                <div style="clear:both;"></div>
            </div>
        </li>
        <?php
    } ?>
</ul>
