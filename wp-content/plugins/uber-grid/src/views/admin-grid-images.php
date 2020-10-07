<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
global $wpdb;
$portfolio_wp_nonce = wp_create_nonce( 'pfhub_portfolio_nonce' );
$id                 = intval( $_GET['id'] );
?>
<div id="gallery-image-zoom">
    <img src=""/>
</div>
<div class="wrap" id="pfhub_portfolio_admin_wrap">
    <?php require( PFHUB_PORTFOLIO_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'admin-licensing-banner.php'); ?>
    <?php
    $path_site2  = PFHUB_PORTFOLIO_IMAGES_URL;
    $form_action = wp_nonce_url( 'admin.php?page=portfolios_pfhub_portfolio&id=' . $id, 'apply_portfolio_' . $row->id, 'pfhub_portfolio_apply_portfolio_nonce' );
    ?>
    <form action="<?php echo $form_action; ?>" method="post" name="adminForm"
          id="adminForm">
        <input type="hidden" class="changedvalues" value="" name="changedvalues" size="80">
        <div id="poststuff">
            <div id="portfolio-header">
                <ul id="portfolios-list">
                    <?php
                    foreach ( $rowsld as $rowsldires ) {
                        if ( $rowsldires->id != $row->id ) {
                            $edit_portfolio_safe_link = wp_nonce_url(
                                'admin.php?page=portfolios_pfhub_portfolio&task=edit_cat&id=' . $rowsldires->id,
                                'edit_portfolio_' . $rowsldires->id,
                                'pfhub_portfolio_edit_portfolio_nonce'
                            );
                            ?>
                            <li>
                                <a href="#"
                                   title="Swith to <?php echo esc_html($rowsldires->name); ?>"
                                   onclick="window.location.href='<?php echo esc_url( 'admin.php?page=portfolios_pfhub_portfolio&task=edit_cat&id=' . $rowsldires->id ) ?>'"><?php echo esc_html($rowsldires->name); ?></a>
                            </li>
                            <?php
                        } else { ?>
                            <li class="active">
                                <input class="text_area" onkeyup="portfoliosListNameChange(this)"
                                       type="text"
                                       name="name" id="name" maxlength="250"
                                       value="<?php echo esc_html( stripslashes( $row->name ) ); ?>"/>
                            </li>
                            <?php
                        }
                    }
                    $add_new_portfolio_safe_link = wp_nonce_url(
                        'admin.php?page=portfolios_pfhub_portfolio&task=add_portfolio',
                        'add_new_portfolio',
                        'pfhub_portfolio_add_portfolio_nonce'
                    );
                    $add_video_nonce             = wp_create_nonce( 'portfolio_add_video_nonce' . $row->id );
                    ?>
                    <li class="add-new">
                        <a onclick="window.location.href='<?php echo $add_new_portfolio_safe_link; ?>'" class="pfhub_portfolio_button" style="font-size:20px; line-height: 18px;" title="Add New Portfolio">+</a>
                    </li>
                </ul>
            </div>
            <div id="post-body" class="metabox-holder columns-2">
                <!-- Content -->
                <div id="post-body-content">
                    <?php add_thickbox(); ?>
                    <div id="post-body">
                        <div id="post-body-heading">
                            <div id="img_preview">
                                <h3><?php echo __( 'Projects / Images', 'pfhub_portfolio' ); ?></h3>
                                <input type="hidden" name="imagess" id="_unique_name"/>
                                <input type="hidden" name="pfhub_portfolio_admin_image_hover_preview"
                                       value="off"/>
                                <label
                                    for="img_hover_preview"><?php _e( 'Image preview on hover', 'pfhub_portfolio' ); ?>
                                    <input type="checkbox" id="img_hover_preview"
                                           name="pfhub_portfolio_admin_image_hover_preview"
                                           value="on" <?php if ( get_option( 'pfhub_portfolio_admin_image_hover_preview' ) == 'on' )
                                        echo 'checked' ?>>
                                </label>
                            </div>
                            <div class="pfhub_portfolio-newuploader uploader  add-new-image">
                                <input type="button" class="pfhub_portfolio_button wp-media-buttons-icon"
                                       name="_unique_name_button"
                                       id="_unique_name_button" value="Add Project / Image"/>
                            </div>

                            <a href="#TB_inline?width=700&height=500&inlineId=pfhub_portfolio_add_videos"
                               class="pfhub_portfolio_button add-video-slide thickbox" id="slideup3s"
                               value="iframepop" data-portfolio-gallery-id="<?php echo $row->id; ?>"
                               data-add-video-nonce="<?php echo $add_video_nonce; ?>">
                                <span class="wp-media-buttons-icon"></span>
                                <?php echo __( 'Add Video', 'pfhub_portfolio' ); ?>
                            </a>
                        </div>
                        <ul id="images-list" data-portfolio-gallery-id="<?php echo $row->id; ?>">
                            <?php
                            $j      = 2;
                            $myrows = explode( ",", $row->categories );

                            foreach ( $rowim as $key => $rowimages ) {
                                if ( $rowimages->media_type == '' ) {
                                    $rowimages->media_type = 'image';
                                }
                                $add_thumb_video_nonce = wp_create_nonce( 'add_thumb_video_nonce' . $rowimages->id );
                                switch ( $rowimages->media_type ) {
                                    case 'image': ?>
                                        <li class='portfolio-item <?php if ( $j % 2 == 0 ) {
                                            echo "has-background";
                                        }
                                        $j ++; ?>' data-portfolio-item-id="<?php echo $rowimages->id; ?>">
                                            <input class="order_by" type="hidden"
                                                   name="order_by_<?php echo $rowimages->id; ?>"
                                                   value="<?php echo $rowimages->ordering; ?>"/>
                                            <div class="image-container">
                                                <ul class="widget-images-list">
                                                    <?php $imgurl = explode( ";", $rowimages->image_url );
                                                    array_pop( $imgurl );
                                                    $i = 0;
                                                    foreach ( $imgurl as $key1 => $img ) {
                                                        $edit_thumb_video_nonce = wp_create_nonce( 'edit_thumb_video_nonce' . $rowimages->id . $i );
                                                        if ( \PfhubPortfolio\Helpers\GridHelper::getVideoType( $img ) != 'image' ) {
                                                            $video_id = \PfhubPortfolio\Helpers\GridHelper::getVideoId( $img );
                                                            if ( \PfhubPortfolio\Helpers\GridHelper::getVideoType( $img ) == 'youtube' ) {
                                                                $iframe_video_src = "//www.youtube.com/embed/" . $video_id[0] . "?modestbranding=1&showinfo=0&controls=0";
                                                            } else {
                                                                $iframe_video_src = "//player.vimeo.com/video/" . $video_id[0] . "?title=0&amp;byline=0&amp;portrait=0";
                                                            }
                                                            ?>
                                                            <li class="editthisvideo editthisimage<?php echo $key; ?><?php if ( $i == 0 ) {
                                                                echo 'first';
                                                            } ?>" data-video-index="<?php echo $i; ?>"
                                                                data-iframe-src="<?php echo $iframe_video_src; ?>">
                                                                <img class="editthisvideo"
                                                                     src="<?php echo \PfhubPortfolio\Helpers\GridHelper::getVideoImage( $img ); ?>"
                                                                     data-video-src="<?php echo esc_attr( $img ); ?>"
                                                                     alt="<?php echo esc_attr( $img ); ?>"/>
                                                                <div
                                                                    class="play-icon <?php echo \PfhubPortfolio\Helpers\GridHelper::getVideoType( $img ) == 'youtube' ? 'youtube-icon' : 'vimeo-icon'; ?>"></div>
                                                                <a class="thickbox edit-video-button"
                                                                   data-edit-thumb-video="<?php echo $edit_thumb_video_nonce; ?>"
                                                                   href="#TB_inline?width=700&height=500&inlineId=portfolio-gallery-edit-video">
                                                                    <input type="button" class="edit-video"
                                                                           id="edit-video_<?php echo $rowimages->id; ?>_<?php echo $key; ?>"
                                                                           value="Edit"/>
                                                                </a>
                                                                <a href="#remove" title="<?php echo $i; ?>"
                                                                   class="remove-image">remove</a>
                                                            </li>
                                                            <?php
                                                        } else { ?>
                                                            <li class="editthisimage editthisimage<?php echo $key; ?> <?php if ( $i == 0 ) {
                                                                echo 'first';
                                                                if ( strpos( $img, 'projects' ) != false ) {
                                                                    echo ' default-image';
                                                                }
                                                            } ?>">
                                                                <img
                                                                    src="<?php echo esc_attr( \PfhubPortfolio\Helpers\GridHelper::getImage( $img, array(), true ) ); ?>"
                                                                    data-img-src="<?php echo esc_attr( $img ); ?>"/>
                                                                <input type="button" class="edit-image" id=""
                                                                       value="Edit"/>
                                                                <a href="#remove" title="<?php echo $i; ?>"
                                                                   class="remove-image">remove</a>
                                                            </li>
                                                            <?php
                                                        }
                                                        $i ++;
                                                    } ?>
                                                    <li class="add-image-box">
                                                        <div class="add-thumb-project">
                                                            <img
                                                                src="<?php echo PFHUB_PORTFOLIO_PLUGIN_URL . '/assets/images/admin/plus.png'; ?>"
                                                                class="plus" alt=""/>
                                                        </div>
                                                        <div class="add-image-video">
                                                            <input type="hidden"
                                                                   name="imagess<?php echo $rowimages->id; ?>"
                                                                   id="unique_name<?php echo $rowimages->id; ?>"
                                                                   class="all-urls"
                                                                   value="<?php echo $rowimages->image_url; ?>"/>
                                                            <a title="Add video" class="add-video-slide thickbox"
                                                               data-add-thumb-video-nonce="<?php echo $add_thumb_video_nonce; ?>"
                                                               href="#TB_inline?width=700&height=500&inlineId=pfhub_portfolio_add_videos">
                                                                <img
                                                                    src="<?php echo PFHUB_PORTFOLIO_PLUGIN_URL . '/assets/images/admin/icon-video.png'; ?>"
                                                                    title="Add video" alt="" class="plus"/>
                                                                <input type="button"
                                                                       class="button<?php echo $rowimages->id; ?> wp-media-buttons-icon add-video"
                                                                       id="unique_name_button<?php echo $rowimages->id; ?>"
                                                                       value="+"/>
                                                            </a>
                                                            <div class="add-image-slide" title="Add image">
                                                                <img
                                                                    src="<?php echo PFHUB_PORTFOLIO_PLUGIN_URL . '/assets/images/admin/icon-img.png'; ?>"
                                                                    title="Add image" alt="" class="plus"/>
                                                                <input type="button"
                                                                       class="button<?php echo $rowimages->id; ?> wp-media-buttons-icon add-image"
                                                                       id="unique_name_button<?php echo $rowimages->id; ?>"
                                                                       value="+"/>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="image-options">
                                                <div class="options-container">
                                                    <div class="pfhub_portfolio_modern_field">
                                                        <input type="text"
                                                               id="titleimage<?php echo $rowimages->id; ?>"
                                                               name="titleimage<?php echo $rowimages->id; ?>"
                                                               value="<?php echo htmlspecialchars( $rowimages->name ); ?>">
                                                        <label for="titleimage<?php echo $rowimages->id; ?>"><?php echo __( 'Title', 'pfhub_portfolio' ); ?></label>
                                                    </div>
                                                    <div class="pfhub_portfolio_modern_field description-block">
                                                        <textarea id="im_description<?php echo $rowimages->id; ?>"
                                                                  name="im_description<?php echo $rowimages->id; ?>"><?php echo esc_html( stripslashes( $rowimages->description ) ); ?></textarea>
                                                        <label for="im_description<?php echo $rowimages->id; ?>"><?php echo __( 'Caption', 'pfhub_portfolio' ); ?></label>
                                                    </div>
                                                    <div class="link-block">
                                                        <div class="pfhub_portfolio_modern_field url-input">
                                                            <input type="text"
                                                                   id="media_url<?php echo $rowimages->id; ?>"
                                                                   name="media_url<?php echo $rowimages->id; ?>"
                                                                   value="<?php echo esc_attr( $rowimages->media_url ); ?>">
                                                            <label for="media_url<?php echo $rowimages->id; ?>"><?php echo __( 'URL', 'pfhub_portfolio' ); ?></label>
                                                        </div>

                                                        <label class="long"
                                                               for="sl_link_target<?php echo $rowimages->id; ?>">
                                                            <span><?php echo __( 'Open in new tab', 'pfhub_portfolio' ); ?></span>
                                                            <input type="hidden"
                                                                   name="sl_link_target<?php echo $rowimages->id; ?>"
                                                                   value=""/>
                                                            <input <?php if ( $rowimages->link_target == 'on' ) {
                                                                echo 'checked="checked"';
                                                            } ?>
                                                                class="link_target" type="checkbox"
                                                                id="sl_link_target<?php echo $rowimages->id; ?>"
                                                                name="sl_link_target<?php echo $rowimages->id; ?>"/>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="category-container">
                                                    <strong><?php echo __( 'Select Categories', 'pfhub_portfolio' ); ?></strong>
                                                    <em>(<?php echo __( 'Press Ctrl And Select multiply', 'pfhub_portfolio' ); ?>
                                                        )</em>
                                                    <select class="pfhub_portfolio_input" id="multipleSelect" multiple="multiple" disabled>
                                                        <?php
                                                        $pfhub_portfolio_cat = explode( ",", $rowimages->category );
                                                        foreach ( $myrows as $value ) {
                                                            if ( ! empty( $value ) ) {
                                                                ?>
                                                                <option <?php if ( in_array( str_replace( ' ', '_', $value ), str_replace( ' ', '_', $pfhub_portfolio_cat ) ) ) {
                                                                    echo "selected='selected' ";
                                                                } ?>
                                                                    value="<?php echo esc_attr( str_replace( ' ', '_', $value ) ); ?>">
                                                                    <?php echo str_replace( '_', ' ', $value ); ?>
                                                                </option>
                                                                <?php
                                                            }
                                                        }
                                                        $remove_project_safe_link = wp_nonce_url(
                                                            'admin.php?page=portfolios_pfhub_portfolio&id=' . $row->id . '&task=apply&removeslide=' . $rowimages->id,
                                                            'remove_project_' . $rowimages->id,
                                                            'pfhub_portfolio_apply_portfolio_nonce'
                                                        );
                                                        ?>
                                                    </select>
                                                    <input type="hidden" id="category<?php echo $rowimages->id; ?>"
                                                           name="category<?php echo $rowimages->id; ?>"
                                                           value="<?php echo esc_attr( str_replace( ' ', '_', $rowimages->category ) ); ?>"/>
                                                </div>
                                                <div class="remove-image-container">
                                                    <a class="button button-secondary remove-image"
                                                       href="<?php echo $remove_project_safe_link; ?>"><?php echo __( 'Remove Project', 'pfhub_portfolio' ); ?></a>
                                                </div>
                                            </div>
                                            <div class="clear"></div>
                                        </li>
                                        <?php
                                        break;
                                    case 'video':
                                        ?>
                                        <li class='portfolio-item <?php if ( $j % 2 == 0 ) {
                                            echo "has-background";
                                        }
                                        $j ++; ?>' data-portfolio-item-id="<?php echo $rowimages->id; ?>">
                                            <input class="order_by" type="hidden"
                                                   name="order_by_<?php echo $rowimages->id; ?>"
                                                   value="<?php echo esc_attr( $rowimages->ordering ); ?>"/>
                                            <div class="video-container">
                                                <ul class="widget-images-list">
                                                    <?php $imgurl = explode( ";", $rowimages->image_url );
                                                    array_pop( $imgurl );
                                                    $i = 0;
                                                    foreach ( $imgurl as $key1 => $img ) {
                                                        $edit_thumb_video_nonce = wp_create_nonce( 'edit_thumb_video_nonce' . $rowimages->id . $i );
                                                        if ( \PfhubPortfolio\Helpers\GridHelper::getVideoType( $img ) != 'image' ) :
                                                            $video_id = \PfhubPortfolio\Helpers\GridHelper::getVideoId( $img );
                                                            if ( \PfhubPortfolio\Helpers\GridHelper::getVideoType( $img ) == 'youtube' ) {
                                                                $iframe_video_src = "//www.youtube.com/embed/" . $video_id[0] . "?modestbranding=1&showinfo=0&controls=0";
                                                            } else {
                                                                $iframe_video_src = "//player.vimeo.com/video/" . $video_id[0] . "?title=0&amp;byline=0&amp;portrait=0";
                                                            } ?>
                                                            <li class="editthisvideo editthisimage<?php echo $key; ?><?php if ( $i == 0 ) {
                                                                echo 'first';
                                                            } ?>" data-video-index="<?php echo $i; ?>"
                                                                data-iframe-src="<?php echo $iframe_video_src; ?>">
                                                                <img class="editthisvideo"
                                                                     src="<?php echo esc_attr( \PfhubPortfolio\Helpers\GridHelper::getVideoImage( $img ) ); ?>"
                                                                     data-video-src="<?php echo esc_attr( $img ); ?>"
                                                                     alt="<?php echo esc_attr( $img ); ?>"/>
                                                                <div
                                                                    class="play-icon <?php echo \PfhubPortfolio\Helpers\GridHelper::getVideoType( $img ) == 'youtube' ? 'youtube-icon' : 'vimeo-icon'; ?>"></div>
                                                                <a class="thickbox edit-video-button"
                                                                   data-edit-thumb-video="<?php echo $edit_thumb_video_nonce; ?>"
                                                                   href="#TB_inline?width=700&height=500&inlineId=portfolio-gallery-edit-video">
                                                                    <input type="button" class="edit-video"
                                                                           id="edit-video_<?php echo $rowimages->id; ?>_<?php echo $key; ?>"
                                                                           value="Edit"/>
                                                                </a>
                                                                <a href="#remove" title="<?php echo $i; ?>"
                                                                   class="remove-image">remove</a>
                                                            </li>
                                                        <?php
                                                        else : ?>
                                                            <li class="editthisimage editthisimage<?php echo $key;
                                                            if ( $i == 0 ) {
                                                                echo 'first';
                                                            } ?>">
                                                                <img
                                                                    src="<?php echo esc_attr( \PfhubPortfolio\Helpers\GridHelper::getImage( $img, array(), true ) ); ?>"
                                                                    data-img-src="<?php echo esc_attr( $img ); ?>"/>
                                                                <input type="button" class="edit-image" id=""
                                                                       value="Edit"/>
                                                                <a href="#remove" title="<?php echo $i; ?>"
                                                                   class="remove-image">remove</a>
                                                            </li>
                                                        <?php
                                                        endif;
                                                        $i ++;
                                                    } ?>

                                                    <li class="add-image-box">
                                                        <div class="add-thumb-project">
                                                            <img
                                                                src="<?php echo PFHUB_PORTFOLIO_PLUGIN_URL . '/assets/images/admin/plus.png'; ?>"
                                                                class="plus" alt=""/>
                                                        </div>
                                                        <div class="add-image-video">
                                                            <input type="hidden"
                                                                   name="imagess<?php echo esc_attr( $rowimages->id ); ?>"
                                                                   id="unique_name<?php echo esc_attr( $rowimages->id ); ?>"
                                                                   class="all-urls"
                                                                   value="<?php echo esc_attr( $rowimages->image_url ); ?>"/>
                                                            <a title="Add video" class="add-video-slide thickbox"
                                                               data-add-thumb-video-nonce="<?php echo $add_thumb_video_nonce; ?>"
                                                               href="#TB_inline?width=700&height=500&inlineId=pfhub_portfolio_add_videos">
                                                                <img
                                                                    src="<?php echo esc_attr( PFHUB_PORTFOLIO_PLUGIN_URL . '/assets/images/admin/icon-video.png' ); ?>"
                                                                    title="Add video" alt="" class="plus"/>
                                                                <input type="button"
                                                                       class="button<?php echo esc_attr( $rowimages->id ); ?> wp-media-buttons-icon add-video"
                                                                       id="unique_name_button<?php echo esc_attr( $rowimages->id ); ?>"
                                                                       value="+"/>
                                                            </a>
                                                            <div class="add-image-slide" title="Add image">
                                                                <img
                                                                    src="<?php echo esc_attr( PFHUB_PORTFOLIO_PLUGIN_URL . '/assets/images/admin/icon-img.png' ); ?>"
                                                                    title="Add image" alt="" class="plus"/>
                                                                <input type="button"
                                                                       class="button<?php echo $rowimages->id; ?> wp-media-buttons-icon add-image"
                                                                       id="unique_name_button<?php echo $rowimages->id; ?>"
                                                                       value="+"/>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="image-options">
                                                <div class="options-container">
                                                    <div class="pfhub_portfolio_modern_field">

                                                        <input type="text"
                                                               id="titleimage<?php echo $rowimages->id; ?>"
                                                               name="titleimage<?php echo $rowimages->id; ?>"
                                                               value="<?php echo esc_html( stripslashes( $rowimages->name ) ); ?>">
                                                        <label for="titleimage<?php echo $rowimages->id; ?>"><?php echo __( 'Label', 'pfhub_portfolio' ); ?></label>
                                                    </div>
                                                    <div class="description-block pfhub_portfolio_modern_field">
                                                        <textarea
                                                                  id="im_description<?php echo $rowimages->id; ?>"
                                                                  name="im_description<?php echo $rowimages->id; ?>"><?php echo esc_html( stripslashes( $rowimages->description ) ); ?></textarea>
                                                        <label for="im_description<?php echo $rowimages->id; ?>"><?php echo __( 'Caption', 'pfhub_portfolio' ); ?></label>
                                                    </div>
                                                    <div class="link-block">
                                                        <div class="pfhub_portfolio_modern_field url-input">
                                                            <input type="text"
                                                                   id="media_url<?php echo $rowimages->id; ?>"
                                                                   name="media_url<?php echo $rowimages->id; ?>"
                                                                   value="<?php echo esc_attr( $rowimages->media_url ); ?>">
                                                            <label for="media_url<?php echo $rowimages->id; ?>"><?php echo __( 'URL', 'pfhub_portfolio' ); ?></label>
                                                        </div>

                                                        <label class="long"
                                                               for="sl_link_target<?php echo $rowimages->id; ?>">
                                                            <span>Open in new tab</span>
                                                            <input type="hidden"
                                                                   name="sl_link_target<?php echo $rowimages->id; ?>"
                                                                   value=""/>
                                                            <input <?php if ( $rowimages->link_target == 'on' ) {
                                                                echo 'checked="checked"';
                                                            } ?> class="link_target" type="checkbox"
                                                                 id="sl_link_target<?php echo $rowimages->id; ?>"
                                                                 name="sl_link_target<?php echo $rowimages->id; ?>"/>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="category-container">
                                                    <strong><?php echo __( 'Select Categories', 'pfhub_portfolio' ); ?></strong>
                                                    <em>(<?php echo __( 'Press Ctrl And Select multiply', 'pfhub_portfolio' ); ?>
                                                        )</em>
                                                    <select class="pfhub_portfolio_input" id="multipleSelect" multiple="multiple" disabled>
                                                        <?php
                                                        $pfhub_portfolio_cat = explode( ",", $rowimages->category );
                                                        foreach ( $myrows as $value ) {
                                                            if ( ! empty( $value ) ) { ?>
                                                                <option <?php if ( in_array( str_replace( ' ', '_', $value ), str_replace( ' ', '_', $pfhub_portfolio_cat ) ) ) {
                                                                    echo "selected='selected' ";
                                                                } ?>
                                                                    value="<?php echo esc_attr( str_replace( ' ', '_', $value ) ); ?>">
                                                                    <?php echo str_replace( '_', ' ', $value ); ?>
                                                                </option>
                                                                <?php
                                                            }
                                                        }
                                                        $remove_project_safe_link = wp_nonce_url(
                                                            'admin.php?page=portfolios_pfhub_portfolio&id=' . $row->id . '&task=apply&removeslide=' . $rowimages->id,
                                                            'remove_project_' . $rowimages->id,
                                                            'pfhub_portfolio_apply_portfolio_nonce'
                                                        );
                                                        ?>
                                                    </select>
                                                    <input type="hidden" id="category<?php echo $rowimages->id; ?>"
                                                           name="category<?php echo $rowimages->id; ?>"
                                                           value="<?php echo esc_attr( str_replace( ' ', '_', $rowimages->category ) ); ?>"/>
                                                </div>
                                                <div class="remove-image-container">
                                                    <a class="button button-secondary remove-image"
                                                       href="<?php echo $remove_project_safe_link; ?>"><?php echo __( 'Remove Project', 'pfhub_portfolio' ); ?></a>
                                                </div>
                                            </div>
                                            <div class="clear"></div>
                                        </li>
                                        <?php break;
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </div>
                <div id="postbox-container-1" class="postbox-container">
                    <div id="side-sortables" class="meta-box-sortables ui-sortable">
                        <div id="portfolio-unique-options" class="postbox">
                            <h3 class="hndle">
                                <span><?php echo __( 'Select The Portfolio/Gallery View', 'pfhub_portfolio' ); ?></span>
                            </h3>
                            <ul id="portfolio-unique-options-list">
                                <li>
                                    <label
                                        for="pfhub_portfolio_name"><?php echo __( 'Portfolio Name', 'pfhub_portfolio' ); ?></label>
                                    <input type="text" name="name" class="pfhub_portfolio_input" id="pfhub_portfolio_name"
                                           value="<?php echo esc_html( stripslashes( $row->name ) ); ?>"
                                           onkeyup="sidebarNameChange(this)">
                                </li>
                                <li style="display:none;">
                                    <label
                                        for="sl_width"><?php echo __( 'The requested action is not valid.', 'pfhub_portfolio' ); ?></label>
                                    <input type="text" name="sl_width" id="sl_width" value="1111"
                                           class="text_area"/>
                                </li>
                                <li style="display:none;">
                                    <label
                                        for="sl_height"><?php echo __( 'Height', 'pfhub_portfolio' ); ?></label>
                                    <input type="text" name="sl_height" id="sl_height"
                                           value="<?php echo esc_html( stripslashes( $row->sl_height ) ); ?>"
                                           class="text_area"/>
                                </li>
                                <li style="display:none;">
                                    <label
                                        for="pause_on_hover"><?php echo __( 'Pause on hover', 'pfhub_portfolio' ); ?></label>
                                    <input type="hidden" value="off" name="pause_on_hover"/>
                                    <input type="checkbox" name="pause_on_hover" value="on"
                                           id="pause_on_hover" <?php if ( $row->pause_on_hover == 'on' ) {
                                        echo 'checked="checked"';
                                    } ?> />
                                </li>
                                <li>
                                    <label
                                        for="portfolio_effects_list"><?php echo __( 'Select The View', 'pfhub_portfolio' ); ?></label>
                                    <select name="portfolio_effects_list" id="portfolio_effects_list">
                                        <option <?php if ( $row->grid_view_type == '0' ) {
                                            echo 'selected';
                                        } ?>
                                            value="0"><?php echo __( 'Toggle Grid', 'pfhub_portfolio' ); ?></option>
                                        <option <?php if ( $row->grid_view_type == '1' ) {
                                            echo 'selected';
                                        } ?>
                                            value="1"><?php echo __( 'Content Blocks', 'pfhub_portfolio' ); ?></option>
                                        <option <?php if ( $row->grid_view_type == '2' ) {
                                            echo 'selected';
                                        } ?>
                                            value="2"><?php echo __( 'Content Popup', 'pfhub_portfolio' ); ?></option>
                                        <option <?php if ( $row->grid_view_type == '3' ) {
                                            echo 'selected';
                                        } ?>
                                            value="3"><?php echo __( 'Projects List', 'pfhub_portfolio' ); ?></option>
                                        <option <?php if ( $row->grid_view_type == '5' ) {
                                            echo 'selected';
                                        } ?>
                                            value="5"><?php echo __( 'Content Slider', 'pfhub_portfolio' ); ?></option>
                                        <option <?php if ( $row->grid_view_type == '6' ) {
                                            echo 'selected';
                                        } ?>
                                            value="6"><?php echo __( 'Masonry Grid', 'pfhub_portfolio' ); ?></option>
                                        <option <?php if ( $row->grid_view_type == '7' ) {
                                            echo 'selected';
                                        } ?>
                                            value="7"><?php echo __( 'Elastic Grid', 'pfhub_portfolio' ); ?></option>
                                    </select>
                                </li>

                                <li style="display:none;" class="for-content-slider">
                                    <label
                                        for="sl_pausetime"><?php echo __( 'Pause time', 'pfhub_portfolio' ); ?></label>
                                    <input type="text" name="sl_pausetime" id="sl_pausetime"
                                           value="<?php echo esc_html( stripslashes( $row->description ) ); ?>"
                                           class="text_area"/>
                                </li>
                                <li style="display:none;" class="for-content-slider">
                                    <label
                                        for="sl_changespeed"><?php echo __( 'Change speed', 'pfhub_portfolio' ); ?></label>
                                    <input type="text" name="sl_changespeed" id="sl_changespeed"
                                           value="<?php echo esc_html( stripslashes( $row->param ) ); ?>"
                                           class="text_area"/>
                                </li>
                                <li class="no-content-slider no-full-width">
                                    <label
                                        for="slider_effect"><?php echo __( 'Show In Center', 'pfhub_portfolio' ); ?></label>
                                    <select id="slider_effect" disabled style="width:103px">
                                        <option <?php if ( $row->sl_position == 'off' ) {
                                            echo 'selected';
                                        } ?> value="off">Off
                                        </option>
                                        <option <?php if ( $row->sl_position == 'on' ) {
                                            echo 'selected';
                                        } ?> value="on">On
                                        </option>
                                    </select>
                                    <a class="portfolio-pro-link probuttonlink"
                                       href="https://portfoliohub.io/"
                                       target="_blank"><span class="portfolio-pro-icon"></span></a>
                                </li>
                                <li style="display:none;margin-top:10px" class="for-content-slider">
                                    <label
                                        for="pause_on_hover"><?php echo __( 'Pause On Hover ', 'pfhub_portfolio' ); ?></label>
                                    <input type="hidden" value="off" name="pause_on_hover"/>
                                    <input type="checkbox" name="pause_on_hover" value="on"
                                           id="pause_on_hover" <?php if ( $row->pause_on_hover == 'on' ) {
                                        echo 'checked="checked"';
                                    } ?> />
                                </li>
                                <li style="display:none;margin-top:10px" class="for-content-slider">
                                    <label
                                        for="autoslide"><?php echo __( 'Autoslide ', 'pfhub_portfolio' ); ?></label>
                                    <input type="hidden" value="off" name="autoslide"/>
                                    <input type="checkbox" name="autoslide" value="on"
                                           id="autoslide" <?php if ( $row->autoslide == 'on' ) {
                                        echo 'checked="checked"';
                                    } ?> />
                                </li>
                                <li>
                                    <label
                                        for="disable_right_click"><?php echo __( 'Disable Image Right Click', 'pfhub_portfolio' ); ?></label>
                                    <select name="disable_right_click" id="disable_right_click">
                                        <option <?php if ( get_option( 'pfhub_portfolio_disable_right_click' ) == 'off' ) {
                                            echo 'selected';
                                        } ?> value="off">Off
                                        </option>
                                        <option <?php if ( get_option( 'pfhub_portfolio_disable_right_click' ) == 'on' ) {
                                            echo 'selected';
                                        } ?> value="on">On
                                        </option>

                                    </select>
                                </li>
                            </ul>
                            <div id="major-publishing-actions" class="postbox-footer">
                                <div id="publishing-action">
                                    <input type="button" onclick="pfhubPortfolioSubmitButton('apply')"
                                           value="Save Portfolio"
                                           id="save-buttom" class="button button-primary">
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>

                        <div class="postbox">
                            <div class="inside2">
                                <ul>
                                    <li class="allowIsotope">
                                        <?php echo __( ' Show Sorting Buttons', 'pfhub_portfolio' ); ?> :
                                        <input type="hidden" value="off" name="pfhub_portfolio_show_sorting"/>
                                        <input type="checkbox"
                                               id="pfhub_portfolio_show_sorting" <?php if ( $row->pfhub_portfolio_show_sorting == 'on' ) {
                                            echo 'checked="checked"';
                                        } ?> name="pfhub_portfolio_show_sorting" value="on"/>
                                    </li>
                                    <li class="allowIsotope">
                                        <?php echo __( ' Show Category Buttons', 'pfhub_portfolio' ); ?> :
                                        <input type="hidden" value="off" name="pfhub_portfolio_show_filtering"/>
                                        <input type="checkbox"
                                               id="pfhub_portfolio_show_filtering" <?php if ( $row->pfhub_portfolio_show_filtering == 'on' ) {
                                            echo 'checked="checked"';
                                        } ?> value="on" disabled/>
                                        <a class="portfolio-pro-link probuttonlink"
                                           href="https://portfoliohub.io/"
                                           target="_blank"><span class="portfolio-pro-icon"></span></a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="postbox">
                            <h3 class="hndle"><span><?php echo __( 'Categories', 'pfhub_portfolio' ); ?>:</span>&nbsp;&nbsp;
                                <a class="portfolio-pro-link probuttonlink"
                                   href="https://portfoliohub.io/"
                                   target="_blank"><span class="portfolio-pro-icon"></span>
                                </a>
                            </h3>
                            <div class="inside">
                                <ul>
                                    <?php
                                    $ifforempty = $row->categories;
                                    $ifforempty = stripslashes( $ifforempty );
                                    $ifforempty = esc_html( $ifforempty );
                                    $ifforempty = empty( $ifforempty );
                                    if ( ! ( $ifforempty ) ) :
                                        foreach ( $myrows as $value ) :
                                            if ( ! empty( $value ) ) : ?>
                                                <li class="hndle">
                                                    <input class="del_val"
                                                           value="<?php echo str_replace( "_", " ", $value ); ?>"
                                                           disabled>
                                                    <span id="delete_cat" style="" value="a"><img
                                                            src="<?php echo PFHUB_PORTFOLIO_IMAGES_URL . "/admin/delete1.png"; ?>"
                                                            width="9" height="9" value="a"></span>
                                                    <span id="edit_cat" style=""><img
                                                            src='<?php echo PFHUB_PORTFOLIO_IMAGES_URL . "/admin/edit3.png"; ?>'
                                                            width="10" height="10"></span>
                                                </li>
                                            <?php
                                            endif;
                                        endforeach;
                                    endif;
                                    ?>
                                    <input type="hidden"
                                           value="<?php if ( strpos( $row->categories, ',,' ) !== false ) {
                                               $row->categories = str_replace( ",,", ",", $row->categories );
                                           }
                                           echo esc_attr( $row->categories ); ?>" id="allCategories"
                                           name="allCategories">
                                    <li id="add_cat_input" style="">
                                        <input type="text" size="12" disabled>
                                        <a style=""
                                           id="add_new_cat_buddon">+<?php echo __( 'Add New Category', 'pfhub_portfolio' ); ?></a>
                                    </li>
                                </ul>
                                <input type="hidden" value="" id="changing_val">
                            </div>
                        </div>

                        <div class="postbox">
                            <h3 class="hndle"><span><?php echo __( 'Loading Icons', 'pfhub_portfolio' ); ?></span>
                            </h3>
                            <div class="inside">
                                <ul id="portfolio-unique-options-list" class="for_loading">
                                    <li>
                                        <label><?php echo __( ' Show Loading Icon', 'pfhub_portfolio' ); ?>
                                            :</label>
                                        <input type="hidden" value="off" name="show_loading"/>
                                        <input type="checkbox"
                                               id="show_loading" <?php if ( $row->show_loading == 'on' ) {
                                            echo 'checked="checked"';
                                        } ?> name="show_loading" value="on"/>
                                    </li>
                                    <li class="loading_opton">
                                        <label for="portfolio_load_icon"
                                               style="width: 100%"><?php echo __( 'Image while portfolio loads:', 'pfhub_portfolio' ); ?></label>
                                        <ul id="portfolio-loading-icon">
                                            <li <?php if ( $row->loading_icon_type == 1 ) {
                                                echo 'class="act"';
                                            } ?>>
                                                <label for="loading_icon_type_1">
                                                    <div class="image-block-icon">
                                                        <img src="<?php echo $path_site2; ?>/loading/loading-1.svg"
                                                             alt=""/>
                                                    </div>
                                                    <input type="radio" id="loading_icon_type_1"
                                                           name="loading_icon_type"
                                                           value="1" <?php if ( $row->loading_icon_type == 1 ) {
                                                        echo 'checked="checked"';
                                                    } ?>>
                                                </label>
                                            </li>
                                            <li <?php if ( $row->loading_icon_type == 2 ) {
                                                echo 'class="act"';
                                            } ?>>
                                                <label for="loading_icon_type_2">
                                                    <div class="image-block-icon">
                                                        <img src="<?php echo $path_site2; ?>/loading/loading-2.svg"
                                                             alt=""/>
                                                    </div>
                                                    <input type="radio" id="loading_icon_type_2"
                                                           name="loading_icon_type"
                                                           value="2" <?php if ( $row->loading_icon_type == 2 ) {
                                                        echo 'checked="checked"';
                                                    } ?>>
                                                </label>
                                            </li>
                                            <li <?php if ( $row->loading_icon_type == 3 ) {
                                                echo 'class="act"';
                                            } ?>>
                                                <label for="loading_icon_type_3">
                                                    <div class="image-block-icon">
                                                        <img src="<?php echo $path_site2; ?>/loading/loading-3.svg"
                                                             alt=""/>
                                                    </div>
                                                    <input type="radio" id="loading_icon_type_3"
                                                           name="loading_icon_type"
                                                           value="3" <?php if ( $row->loading_icon_type == 3 ) {
                                                        echo 'checked="checked"';
                                                    } ?>>
                                                </label>
                                            </li>
                                            <li <?php if ( $row->loading_icon_type == 4 ) {
                                                echo 'class="act"';
                                            } ?>>
                                                <label for="loading_icon_type_4">
                                                    <div class="image-block-icon">
                                                        <img src="<?php echo $path_site2; ?>/loading/loading-4.svg"
                                                             alt=""/>
                                                    </div>
                                                    <input type="radio" id="loading_icon_type_4"
                                                           name="loading_icon_type"
                                                           value="4" <?php if ( $row->loading_icon_type == 4 ) {
                                                        echo 'checked="checked"';
                                                    } ?>>
                                                </label>
                                            </li>
                                            <li <?php if ( $row->loading_icon_type == 5 ) {
                                                echo 'class="act"';
                                            } ?>>
                                                <label for="loading_icon_type_5">
                                                    <div class="image-block-icon">
                                                        <img src="<?php echo $path_site2; ?>/loading/loading-5.svg"
                                                             alt=""/>
                                                    </div>
                                                    <input type="radio" id="loading_icon_type_5"
                                                           name="loading_icon_type"
                                                           value="5" <?php if ( $row->loading_icon_type == 5 ) {
                                                        echo 'checked="checked"';
                                                    } ?>>
                                                </label>
                                            </li>
                                            <li <?php if ( $row->loading_icon_type == 6 ) {
                                                echo 'class="act"';
                                            } ?>>
                                                <label for="loading_icon_type_6">
                                                    <div class="image-block-icon">
                                                        <img src="<?php echo $path_site2; ?>/loading/loading-6.svg"
                                                             alt=""/>
                                                    </div>
                                                    <input type="radio" id="loading_icon_type_6"
                                                           name="loading_icon_type"
                                                           value="6" <?php if ( $row->loading_icon_type == 6 ) {
                                                        echo 'checked="checked"';
                                                    } ?>>
                                                </label>
                                            </li>
                                            <li <?php if ( $row->loading_icon_type == 7 ) {
                                                echo 'class="act"';
                                            } ?>>
                                                <label for="loading_icon_type_7">
                                                    <div class="image-block-icon">
                                                        <img src="<?php echo $path_site2; ?>/loading/loading-7.svg"
                                                             alt=""/>
                                                    </div>
                                                    <input type="radio" id="loading_icon_type_7"
                                                           name="loading_icon_type"
                                                           value="7" <?php if ( $row->loading_icon_type == 7 ) {
                                                        echo 'checked="checked"';
                                                    } ?>>
                                                </label>
                                            </li>
                                            <li <?php if ( $row->loading_icon_type == 8 ) {
                                                echo 'class="act"';
                                            } ?>>
                                                <label for="loading_icon_type_8">
                                                    <div class="image-block-icon">
                                                        <img src="<?php echo $path_site2; ?>/loading/loading-8.svg"
                                                             alt=""/>
                                                    </div>
                                                    <input type="radio" id="loading_icon_type_8"
                                                           name="loading_icon_type"
                                                           value="8" <?php if ( $row->loading_icon_type == 8 ) {
                                                        echo 'checked="checked"';
                                                    } ?>>
                                                </label>
                                            </li>
                                            <li <?php if ( $row->loading_icon_type == 9 ) {
                                                echo 'class="act"';
                                            } ?>>
                                                <label for="loading_icon_type_9">
                                                    <div class="image-block-icon">
                                                        <img src="<?php echo $path_site2; ?>/loading/loading-9.svg"
                                                             alt=""/>
                                                    </div>
                                                    <input type="radio" id="loading_icon_type_9"
                                                           name="loading_icon_type"
                                                           value="9" <?php if ( $row->loading_icon_type == 9 ) {
                                                        echo 'checked="checked"';
                                                    } ?>>
                                                </label>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div id="portfolio-shortcode-box" class="postbox shortcode ms-toggle">
                            <h3 class="hndle"><span><?php echo __( 'Usage', 'pfhub_portfolio' ); ?></span></h3>
                            <div class="inside">
                                <ul>
                                    <li rel="tab-1" class="selected">
                                        <h4><?php echo __( 'Shortcode', 'pfhub_portfolio' ); ?></h4>
                                        <p><?php echo __( 'Copy &amp; paste the shortcode directly into any WordPress post or page', 'pfhub_portfolio' ); ?>
                                            .</p>
                                        <textarea class="full"
                                                  readonly="readonly">[pfhub_portfolio id="<?php echo $row->id; ?>"]</textarea>
                                    </li>
                                    <li rel="tab-2">
                                        <h4><?php echo __( 'Template Include', 'pfhub_portfolio' ); ?></h4>
                                        <p><?php echo __( 'Copy &amp; paste this code into a template file to include the slideshow within your theme', 'pfhub_portfolio' ); ?>
                                            .</p>
                                        <textarea class="full" readonly="readonly">&lt;?php echo do_shortcode("[pfhub_portfolio id='<?php echo $row->id; ?>']"); ?&gt;</textarea>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<?php
require_once( PFHUB_PORTFOLIO_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'admin-add-video.php' );
require_once( PFHUB_PORTFOLIO_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'admin-edit-video.php' );
?>
