<?php

class Http2_Push_Content_Js_Option{

    public $plugin_name;

    private $setting = array();

    private $active_tab;

    private $this_tab = 'general-js';

    private $tab_name = "Defer / Async / Remove JS";

    private $setting_key = 'http2_async_js';

   
    function __construct($plugin_name){
        $this->plugin_name = $plugin_name;
        
        $this->tab = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
        $this->active_tab = $this->tab != "" ? $this->tab : 'default';

        $this->settings = array(
                array('field'=>'http2_async_js_list')
            );
        

        if($this->this_tab == $this->active_tab){
            add_action($this->plugin_name.'_tab_content', array($this,'tab_content'));
        }

        add_action($this->plugin_name.'_tab', array($this,'tab'),1);

        add_filter('pre_update_option_http2_async_js_list',array($this, 'remove_blank_values'));

        $this->register_settings();
    }

    function remove_blank_values($resources){
        if(is_array($resources)):
            foreach($resources as $key => $link){
                if($link['js'] == "" ){
                    unset($resources[$key]);
                } 
            }
        endif;
        return $resources;
    }


    function register_settings(){   

        foreach($this->settings as $setting){
                register_setting( $this->setting_key, $setting['field']);
        }
    
    }

    function tab(){
        $page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );
        ?>
        <a class=" px-3 text-light d-flex align-items-center  border-left border-right  <?php echo ($this->active_tab == $this->this_tab ? 'bg-primary' : 'bg-secondary'); ?>" href="<?php echo admin_url( 'admin.php?page='.$page.'&tab='.$this->this_tab ); ?>">
            <?php _e( $this->tab_name, 'http2-push-content' ); ?> 
        </a>
        <a class=" px-3 text-light d-flex align-items-center  border-left border-right  bg-danger" href="https://www.youtube.com/watch?v=GHGclxgbSqI" target="_blank">
        Instructional Video 
        </a>
        <?php
    }

    function tab_content(){
        $general_list = get_option('http2_async_js_list',false);
       ?>
        <script type="text/javascript">
        var general_async_js_list = <?php echo json_encode(($general_list == false) ? array(): array_values($general_list)); ?>;
        </script>
        <script id="async_js_list_tmpl" type="text/x-jsrender">
        <div class="flex">
        <input required type='text' class="form-control w-50 css_identifier" name="http2_async_js_list[{{: count}}][js]" value="{{: value.js}}" placeholder="JS Identifier E.g: twentytwenty/jquery.js">
        <select required  class="form-control w-25" name="http2_async_js_list[{{: count}}][to]">
                        <option disabled><?php _e('Select', 'http2-push-content'); ?></option>
                        <option value="async" {{if value.to == 'async'}}selected="selected"{{/if}}>Asynchronous</option>
                        <option value="defer" {{if value.to == 'defer'}}selected="selected"{{/if}}>Defered</option>
                        <option value="remove" {{if value.to == 'remove'}}selected="selected"{{/if}}>Remove</option>
        </select>
        <select required  class="general_async_js_list_rule form-control w-25" name="http2_async_js_list[{{: count}}][apply_to]"  data-count="{{: count}}"  data-name="http2_async_js_list">
            <?php 
                $obj = new Http2_Push_Content_Apply_To(); 
                $obj->apply_to_options();
            ?>
        </select>
        {{if value.id != undefined && (value.apply_to == 'specific_pages' || value.apply_to == 'not_specific_pages' || value.apply_to == 'specific_posts' || value.apply_to == 'not_specific_posts') }}
        <input class="pisol-ids form-control" type="text" name="http2_async_js_list[{{: count}}][id]" value="{{: value.id}}" id="http2_async_js_list_{{: count}}_id"  placeholder="e.g: 12, 22, 33">
        {{/if}}
        <a class="remove_js_resource" href="javascript:void(0);"><span class="dashicons dashicons-trash pi-icon"></span></a>
        </div>
        </script>
        <form method="post" action="options.php"  class="pisol-setting-form">
        <?php settings_fields( $this->setting_key ); ?>
        <div class="pisol_grid">
        <?php
            foreach($this->settings as $setting){
                new pisol_class_form($setting, $this->setting_key);
            }
        ?>
        </div>
        <h2><?php echo __('Asynchronous or Defer or Remove JS file','http2-push-content'); ?></h2>
        <div id="js-resource-list">

        </div>
        <br>
        <a class="btn btn-info btn-sm" href="javascript:void(0);" id="add_js"><span class="dashicons dashicons-plus-alt pi-icon"></span> Add JS</a>
        <br>
        <input type="submit" class="mt-3 btn btn-primary btn-lg" value="Save Option" />
        </form>
       <?php
    }
}

add_action($this->plugin_name.'_general_option', new Http2_Push_Content_Js_Option($this->plugin_name));