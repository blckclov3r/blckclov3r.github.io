<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
$id                        = intval( $_GET['id'] );
$portfolio_add_video_nonce = wp_create_nonce( 'portfolio_add_video_nonce' );
$portfolio_add_thumb_nonce = wp_create_nonce( 'add_thumb_video_nonce' );
?>
<div id="pfhub_portfolio_add_videos" style="display: none">
    <div id="pfhub_portfolio_add_videos_wrap" data-portfolio-gallery-id="<?php echo $id; ?>"
         data-add-thumb-video-nonce="<?php echo $portfolio_add_thumb_nonce; ?>"
         data-add-video-nonce="<?php echo $portfolio_add_video_nonce; ?>">
        <h2><?php echo __( 'Add Video URL From Youtube or Vimeo', 'pfhub_portfolio' ); ?></h2>
        <div class="control-panel">
            <form method="post" action="" class="add-main-video" style="display:block !important;">
                <input type="text" id="pfhub_portfolio_add_video_input" name="pfhub_portfolio_add_video_input"/>
                <button class='save-slider-options button-primary pfhub_portfolio-insert-video-button'
                        id='pfhub_portfolio-insert-video-button'><?php echo __( 'Insert Video', 'pfhub_portfolio' ); ?></button>
                <div id="add-video-popup-options">
                    <div>
                        <div>
                            <label for="show_title"><?php echo __( 'Label', 'pfhub_portfolio' ); ?>:</label>
                            <div>
                                <input name="show_title" value="" type="text"/>
                            </div>
                        </div>
                        <div>
                            <label for="show_description"><?php echo __( 'Caption', 'pfhub_portfolio' ); ?>
                                :</label>
                            <textarea id="show_description" name="show_description"></textarea>
                        </div>
                        <div>
                            <label for="show_url"><?php echo __( 'Url', 'pfhub_portfolio' ); ?>:</label>
                            <input type="text" name="show_url" value=""/>
                        </div>
                    </div>
                </div>
            </form>
            <form method="post" action="" class="add-thumb-video" data-portfolio-item-id="">
                <input type="text" id="pfhub_portfolio_add_video_input_thumb" name="pfhub_portfolio_add_video_input_thumb"/>
                <button class='save-slider-options button-primary pfhub_portfolio-insert-thumb-video-button'
                        id='pfhub_portfolio-insert-video-button'><?php echo __( 'Insert Video', 'pfhub_portfolio' ); ?></button>
            </form>
        </div>
    </div>
</div>
