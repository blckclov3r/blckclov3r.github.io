<?php


namespace PfhubPortfolio;


use WP_Widget;

class PortfolioWidget extends WP_Widget
{
    public function __construct() {
        parent::__construct(
            'PfhubPortfolio',
            'NavyPlugins Portfolio',
            array( 'description' => __( 'NavyPlugins Portfolio', 'pfhub_portfolio' ), )
        );
    }

    public function widget( $args, $instance ) {
        if ( isset( $instance['grid_id'] ) ) {
            $grid_id = $instance['grid_id'];

            $title = apply_filters( 'widget_title', $instance['title'] );

            echo $args['before_widget'];
            if ( ! empty( $title ) ) {
                echo $args['before_title'] . $title .$args['after_title'];
            }

            echo do_shortcode( "[pfhub_portfolio id={$grid_id}]" );
            echo $args['after_widget'];
        }
    }

    /**
     * Update options
     * @param array $new_instance
     * @param array $old_instance
     *
     * @return array
     */
    public function update( $new_instance, $old_instance ) {
        $instance                 = array();
        $instance['grid_id'] = strip_tags( $new_instance['grid_id'] );
        $instance['title']        = strip_tags( $new_instance['title'] );

        return $instance;
    }

    /**
     * Print out the widget's form HTML
     *
     * @param array $instance
     *
     * @return string|void
     */
    public function form( $instance ) {
        $selected_portfolio = 0;
        $title              = "";
        if ( isset( $instance['title'] ) ) {
            $title = $instance['title'];
        }
        if (isset($instance['grid_id'])) {
            $selected_portfolio = $instance['grid_id'];
        }

        ?>
        <p>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Label:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
                   name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
                   value="<?php echo esc_attr( $title ); ?>"/>
        </p>
        <label
            for="<?php echo $this->get_field_id( 'grid_id' ); ?>"><?php _e( 'Select portfolio:', 'pfhub_portfolio' ); ?></label>
        <select id="<?php echo $this->get_field_id( 'grid_id' ); ?>"
                name="<?php echo $this->get_field_name( 'grid_id' ); ?>">
            <?php
            global $wpdb;
            $query     = "SELECT * FROM " . $wpdb->prefix . "pfhub_portfolio_grids ";
            $rowwidget = $wpdb->get_results( $query );
            foreach ( $rowwidget as $rowwidgetecho ) { ?>
                <option <?php selected( $selected_portfolio, $rowwidgetecho->id, true); ?> value="<?php echo $rowwidgetecho->id; ?>"><?php echo $rowwidgetecho->name; ?></option>
            <?php } ?>
        </select>
        </p>
        <?php
    }
}
