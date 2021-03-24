<?php

class Http2_Push_Content_General_Option{

    public $plugin_name;

    private $setting = array();

    private $active_tab;

    private $this_tab = 'default';

    private $tab_name = "General setting";

    private $setting_key = 'http2_push_content_general';

    public $as = array('script', 'style', "embed", "fetch", "font", "image", "object", "video");

    public $to = array("push-preload", "push", "preload");

    function __construct($plugin_name){
        $this->plugin_name = $plugin_name;
        
        $this->tab = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
        $this->active_tab = $this->tab != "" ? $this->tab : 'default';

        $this->settings = array(
                array('field'=>'http2_push_general_list'),
                array('field'=>'push_all_style', 'label'=>__('Push/Preload all style','http2-push-content'),'type'=>'select', 'value'=>array(false =>__('Do Nothing','http2-push-content'), 'push'=>__('Push','http2-push-content'), 'preload'=>__('Preload','http2-push-content'), 'push-preload'=>__('Push Preload','http2-push-content')), 'default'=>'push-preload', 'desc'=>__('This push and preload all the style sheet added using enque method','http2-push-content')),
                array('field'=>'push_all_script', 'label'=>__('Push/Preload all script','http2-push-content'),'type'=>'select', 'value'=>array(false =>__('Do Nothing','http2-push-content'),'push'=>__('Push','http2-push-content'), 'preload'=>__('Preload','http2-push-content'), 'push-preload'=>__('Push Preload','http2-push-content')), 'default'=>'push-preload', 'desc'=>__('This push and preload all the script added using enqueue method','http2-push-content')),
            );
        

        if($this->this_tab == $this->active_tab){
            add_action($this->plugin_name.'_tab_content', array($this,'tab_content'));
        }

        add_action($this->plugin_name.'_tab', array($this,'tab'),1);

        add_filter('pre_update_option_http2_push_general_list',array($this, 'remove_blank_values'));
        
        $this->register_settings();
    }

    function remove_blank_values($resources){
        if(is_array($resources)):
            foreach($resources as $key => $link){
                if($link['url'] == "" || !in_array($link['as'], $this->as) || !in_array($link['to'], $this->to)){
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
        <?php
    }

    function tab_content(){
        $general_list = get_option('http2_push_general_list',false);
       ?>
        <script type="text/javascript">
        var general_push_list = <?php echo json_encode(($general_list == false) ? array(): array_values($general_list)); ?>;
        </script>
        <script id="resource_tmpl" type="text/x-jsrender">
        <div class="flex">
        <input required type='text' class="form-control w-50 url" name="http2_push_general_list[{{: count}}][url]" value="{{: value.url}}" placeholder="Full url of resource">
        <select required  class="form-control w-25" name="http2_push_general_list[{{: count}}][as]">
                        <option disabled><?php _e('Select Resource Type', 'http2-push-content'); ?></option>
                        <option value="script" {{if value.as == 'script'}}selected="selected"{{/if}}>script</option>
                        <option value="style" {{if value.as == 'style'}}selected="selected"{{/if}}>style</option>
                        <option value="audio" {{if value.as == 'audio'}}selected="selected"{{/if}}>audio</option>
                        <option value="embed" {{if value.as == 'embed'}}selected="selected"{{/if}}>embed</option>
                        <option value="fetch" {{if value.as == 'fetch'}}selected="selected"{{/if}}>fetch</option>
                        <option value="font" {{if value.as == 'font'}}selected="selected"{{/if}}>font</option>
                        <option value="image" {{if value.as == 'image'}}selected="selected"{{/if}}>image</option>
                        <option value="object" {{if value.as == 'object'}}selected="selected"{{/if}}>object</option>
                        <option value="video" {{if value.as == 'video'}}selected="selected"{{/if}}>video</option>
        </select>
        <select required class="form-control w-25" name="http2_push_general_list[{{: count}}][to]">
                        <option disabled><?php _e('Select Push/Pull', 'http2-push-content'); ?></option>
                        <option value="push-preload" {{if value.to == 'push-preload'}}selected="selected"{{/if}}>Push and Preload</option>
                        <option value="push" {{if value.to == 'push'}}selected="selected"{{/if}}>Push</option>
                        <option value="preload" {{if value.to == 'preload'}}selected="selected"{{/if}}>Preload</option>
        </select>
        <select required class="general_push_list_rule form-control w-25"  name="http2_push_general_list[{{: count}}][apply_to]" data-count="{{: count}}" data-name="http2_push_general_list">
            <?php 
                $obj = new Http2_Push_Content_Apply_To(); 
                $obj->apply_to_options();
            ?>
        </select>
        {{if value.id != undefined && (value.apply_to == 'specific_pages' || value.apply_to == 'not_specific_pages' || value.apply_to == 'specific_posts' || value.apply_to == 'not_specific_posts') }}
        <input class="pisol-ids form-control" type="text" name="http2_push_general_list[{{: count}}][id]" value="{{: value.id}}" id="http2_push_general_list_{{: count}}_id"  placeholder="e.g: 12, 22, 33">
        {{/if}}
        <a class="remove_resource_to_push" href="javascript:void(0);"><span class="dashicons dashicons-trash pi-icon"></span></a>
        </div>
        </script>
        <form method="post" action="options.php"  class="pisol-setting-form">
        <?php settings_fields( $this->setting_key ); ?>
        <?php
            foreach($this->settings as $setting){
                new pisol_class_form($setting, $this->setting_key);
            }
        ?>
        <h2><?php echo __('Push or Preload or Do Both to any content: (put exact url of the resource from the page source)','http2-push-content'); ?><br><small>e.g: http://yoursite.com/wp-includes/js/jquery/jquery.js</small></h2>
        
        <div id="push-resource-list">

        </div>
        <br>
        <a class="btn btn-info btn-sm" href="javascript:void(0);" id="add_resource_to_push"><span class="dashicons dashicons-plus-alt pi-icon"></span> Add Resource to push</a>
        <br>
        <input type="submit" class="mt-3 btn btn-primary btn-lg" value="Save Option" />
        </form>
       <?php
    }
}

add_action($this->plugin_name.'_general_option', new Http2_Push_Content_General_Option($this->plugin_name));