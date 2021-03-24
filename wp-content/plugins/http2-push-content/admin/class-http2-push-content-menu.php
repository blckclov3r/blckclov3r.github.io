<?php

class Http2_Push_Content_Menu{

    public $plugin_name;
    public $menu;
    
    function __construct($plugin_name , $version){
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        add_action( 'admin_menu', array($this,'plugin_menu') );
    }

    function plugin_menu(){
        
        $this->menu = add_menu_page(
            __( 'HTTP2 Push Content List', 'http2-push-content' ),
            'HTTP2 Push Content',
            'manage_options',
            'http2-push-content',
            array($this, 'menu_option_page'),
            'dashicons-randomize',
            6
        );

        add_action("load-".$this->menu, array($this,"bootstrap_style"));
 
    }

    public function bootstrap_style() {

		wp_enqueue_style( $this->plugin_name."_bootstrap", plugin_dir_url( __FILE__ ) . 'css/bootstrap.css', array(), $this->version, 'all' );

	}

    function menu_option_page(){
        ?>
        <div class="container mt-2">
            <div class="row">
                    <div class="col-12">
                        <div class='bg-dark'>
                        <div class="row">
                            <div class="col-12 col-sm-2 py-2">
                                    <a href="https://www.piwebsolution.com/" target="_blank"><img class="img-fluid ml-2" src="<?php echo plugin_dir_url( __FILE__ ); ?>img/pi-web-solution.svg"></a>
                            </div>
                            <div class="col-12 col-sm-10 d-flex">
                                <?php do_action($this->plugin_name.'_tab'); ?>
                            </div>
                        </div>
                        </div>
                    </div>
            </div>
            <div class="row">
                <div class="col-12">
                <div class="bg-light border pl-3 pr-3 pb-3 pt-0">
                    <div class="row">
                        <div class="col">
                        <?php do_action($this->plugin_name.'_tab_content'); ?>
                        </div>
                        <?php do_action($this->plugin_name.'_promotion'); ?>
                    </div>
                </div>
                </div>
            </div>
        </div>
        <?php
    }

}