<?php


namespace PfhubPortfolio;


use PfhubPortfolio\Helpers\GridHelper;

class Ajax
{
    public static function init() {
        add_action( 'wp_ajax_pfhub_portfolio_action', array( __CLASS__, 'callback' ) );
        add_action( 'wp_ajax_nopriv_pfhub_portfolio_action', array( __CLASS__, 'callback' ) );
    }

    public static function callback() {
        global $wpdb;
        if ( $_POST['task'] == 'optonschange' ) {
            if ( isset( $_POST['id'] ) ) {

                if( !isset( $_REQUEST['nonce'] ) || !wp_verify_nonce( $_REQUEST['nonce'], 'pfhub_portfolio_change_options' ) ){
                    die( __( 'Authentication failed', 'pfhub_portfolio' ) );
                }

                $id = intval($_POST['id']);

                if( !$id ){
                    wp_die( __( 'Not numeric id', 'pfhub_portfolio' ) );
                }

                $query    = $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "pfhub_portfolio_grids WHERE id = %d", $id );
                $row      = $wpdb->get_row( $query );
                $response = array(
                    'portfolio_effects_list' => $row->grid_view_type,
                    'pfhub_portfolio_show_sorting'        => $row->pfhub_portfolio_show_sorting,
                    'pfhub_portfolio_show_filtering'      => $row->pfhub_portfolio_show_filtering,
                    'sl_pausetime'           => $row->description,
                    'sl_changespeed'         => $row->param,
                    'pause_on_hover'         => $row->pause_on_hover
                );
                echo json_encode( $response );
                wp_die();
            }
        }
        if ( $_POST['post'] == 'optionssave' ) {
            if ( isset( $_POST["pfhub_portfolio_grid_id"] ) ) {
                if( !isset( $_REQUEST['nonce'] ) || !wp_verify_nonce( $_REQUEST['nonce'], 'pfhub_portfolio_add_shortecode_nonce' ) ){
                    die( __( 'Authentication failed', 'pfhub_portfolio' ) );
                }
                $id = absint($_POST["pfhub_portfolio_grid_id"]);
                $wpdb->update(
                    $wpdb->prefix . "pfhub_portfolio_grids",
                    array(
                        'pfhub_portfolio_show_sorting' => sanitize_text_field( $_POST["pfhub_portfolio_show_sorting"] ),
                        'pfhub_portfolio_show_filtering' => sanitize_text_field( $_POST["pfhub_portfolio_show_filtering"] ),
                        'description' => sanitize_text_field( $_POST["sl_pausetime"] ),
                        'param' => sanitize_text_field( $_POST["sl_changespeed"] ),
                        'pause_on_hover' => sanitize_text_field( $_POST["pause_on_hover"] ),
                        'grid_view_type' => sanitize_text_field( $_POST["portfolio_effects_list"] ),
                    ),
                    array('id' => $id),
                    array('%s', '%s', '%s', '%s', '%s', '%s')
                );
            }
        }
        if (  $_POST['task'] == 'see_new_video' ) {
            if ( ! isset( $_REQUEST['videoEditNonce'] ) || ! wp_verify_nonce( $_REQUEST['videoEditNonce'], 'see_new_video_nonce' ) ) {
                die( __( 'Authentication failed', 'pfhub_portfolio' ) );
            }
            $video_url = sanitize_text_field( $_POST['videoUrl'] );
            $video_id = GridHelper::getVideoId( $video_url );
            $video_id = $video_id[0];
            $video_type = GridHelper::getVideoType( $video_url );
            if ( $video_type == 'youtube' ) {
                $iframe_video_src = "//www.youtube.com/embed/" . $video_id . "?modestbranding=1&showinfo=0&controls=0";
            } else {
                $iframe_video_src = "//player.vimeo.com/video/" . $video_id . "?title=0&amp;byline=0&amp;portrait=0";
            }
            echo json_encode( $iframe_video_src );
            wp_die();
        }
        if (  $_POST['task'] == 'add_thumb_video' ) {
            if ( !isset( $_POST["portfolioItemId"] ) || !absint($_POST['portfolioItemId']) || absint( $_POST['portfolioItemId'] ) != $_POST['portfolioItemId'] ) {
                wp_die('"portfolioItemId" parameter is required to be not negative integer');
            }
            $portfolioItemId = absint( $_POST["portfolioItemId"] );
            if (!isset($_REQUEST['addThumbVideoNonce']) || !wp_verify_nonce($_REQUEST['addThumbVideoNonce'], 'add_thumb_video_nonce' . $portfolioItemId)) {
                die(__('Authentication failed', 'pfhub_portfolio'));
            }
            $video_url = sanitize_text_field( $_POST['videoUrl'] );
            $video_id = GridHelper::getVideoId( $video_url );
            $video_type = $video_id[1];
            $video_id = $video_id[0];
            $video_type = GridHelper::getVideoType( $video_url );
            if ( $video_type == 'youtube' ) {
                $iframe_video_src = "//www.youtube.com/embed/" . $video_id . "?modestbranding=1&showinfo=0&controls=0";
            } else {
                $iframe_video_src = "//player.vimeo.com/video/" . $video_id . "?title=0&amp;byline=0&amp;portrait=0";
            }
            $image_url = GridHelper::getVideoImage($video_url);
            echo json_encode( array ( 'iframe_video_src' => $iframe_video_src,'image_url' => $image_url, 'video_type' => $video_type ) );
            wp_die();
        }
    }
}
