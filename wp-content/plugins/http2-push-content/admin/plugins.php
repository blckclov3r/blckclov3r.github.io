<?php

class pisol_corw_other_plugins{

    public $plugin_name;

    private $setting = array();

    private $active_tab;

    private $this_tab = 'other_plugins';

    private $tab_name = "Related Plugins";

    private $setting_key = 'pisol_corw_other_plugins';


    function __construct($plugin_name){
        $this->plugin_name = $plugin_name;

        
        $this->tab = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
        $this->active_tab = $this->tab != "" ? $this->tab : 'default';

        $this->settings = array(
            
            
        );

        if($this->this_tab == $this->active_tab){
            add_action($this->plugin_name.'_tab_content', array($this,'tab_content'));
        }

        add_action($this->plugin_name.'_tab', array($this,'tab'),20);

        $this->register_settings();
        
    }

    function register_settings(){   

        foreach($this->settings as $setting){
            register_setting( $this->setting_key, $setting['field']);
        }
    
    }

    function tab(){
        ?>
        <a class=" px-3 text-light d-flex align-items-center  border-left border-right  <?php echo ($this->active_tab == $this->this_tab ? 'bg-primary' : 'bg-secondary'); ?>" href="<?php echo admin_url( 'admin.php?page='.sanitize_text_field($_GET['page']).'&tab='.$this->this_tab ); ?>">
            <?php _e( $this->tab_name, 'http2-push-content' ); ?> 
        </a>
        <a class=" px-3 text-light d-flex align-items-center  border-left border-right  <?php echo ($this->active_tab == $this->this_tab ? 'bg-primary' : 'bg-secondary'); ?>" href="https://wordpress.org/support/plugin/http2-push-content/reviews/#bbp_topic_content" target="_blank">
            Send suggestions 
        </a>
        <?php
    }

    function tab_content(){

        require_once ABSPATH . 'wp-admin/includes/class-wp-plugin-install-list-table.php';
        
        add_filter('install_plugins_nonmenu_tabs', function ($tabs) {
            $tabs[] = 'rajeshsingh520';
            return $tabs;
        });
        add_filter('install_plugins_table_api_args_rajeshsingh520', function ($args) {
            global $paged;
            return [
                'page' => $paged,
                'per_page' => 20,
                'locale' => get_user_locale(),
                'author' => 'rajeshsingh520',
            ];
        });

        $_POST['tab'] = 'rajeshsingh520';
        $table = new WP_Plugin_Install_List_Table();
        $table->prepare_items();

        wp_enqueue_script('plugin-install');
        add_thickbox();
        wp_enqueue_script('updates');
        echo '<div id="plugin-filter">';
        echo $table->display();
        echo '</div>';
        
    }

}

new pisol_corw_other_plugins($this->plugin_name);
