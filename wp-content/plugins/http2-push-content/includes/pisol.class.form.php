<?php
/**
* version 2.2
* work with bootstrap
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if(!class_exists('pisol_class_form')):
class pisol_class_form{

    private $setting;
    private $saved_value; 
    private $pro;
    function __construct($setting){

        $this->setting = $setting;

        if(isset( $this->setting['default'] )){
            $this->saved_value = get_option($this->setting['field'], $this->setting['default']);
        }else{
            $this->saved_value = get_option($this->setting['field']);
        }

        if(isset( $this->setting['pro'] )){
            if($this->setting['pro']){
                $this->pro = ' free-version ';
                $this->setting['desc'] = '<span style="color:#f00; font-weight:bold;">Workes in Pro version only / Without PRO version this setting will have no effect</span>';
            }else{
                $this->pro = ' paid-version ';
            }
        }else{
            $this->pro = "";
        }
        
        
        $this->check_field_type();
    }

    

    
    function check_field_type(){
        if(isset($this->setting['type'])):
            switch ($this->setting['type']){
                case 'select':
                    $this->select_box();
                break;

                case 'number':
                    $this->number_box();
                break;

                case 'text':
                    $this->text_box();
                break;
                    
                case 'textarea':
                    $this->textarea_box();
                break;

                case 'multiselect':
                    $this->multiselect_box();
                break;

                case 'color':
                    $this->color_box();
                break;

                case 'hidden':
                    $this->hidden_box();
                break;

                case 'switch':
                    $this->switch_display();
                break;
            }
        endif;
    }

    function bootstrap($label, $field, $desc = ""){
        ?>
        <div class="row py-4 border-bottom align-items-center">
            <div class="col-12 col-md-5">
            <?php echo $label; ?>
            <?php echo $desc != "" ? $desc: ""; ?>
            </div>
            <div class="col-12 col-md-7">
            <?php echo $field; ?>
            </div>
        </div>
        <?php
    }

    /*
        Field type: select box
    */
    function select_box(){

        $label = '<label class="h6 mb-0" class="mb-0" for="'.$this->setting['field'].'">'.$this->setting['label'].'</label>';
        $desc = (isset($this->setting['desc'])) ? '<br><small>'.$this->setting['desc'].'</small>' : "";
        
        $field = '<select class="form-control '.$this->pro.'" name="'.$this->setting['field'].'" id="'.$this->setting['field'].'"'
         .(isset($this->setting['multiple']) ? ' multiple="'.$this->setting['multiple'].'"': '')
        .'>';
            foreach($this->setting['value'] as $key => $val){
               $field .= '<option value="'.$key.'" '.( ( $this->saved_value == $key) ? " selected=\"selected\" " : "" ).'>'.$val.'</option>';
            }
        $field .= '</select>';

        $this->bootstrap($label, $field, $desc);

    }

    /*
        Field type: select box
    */
    function multiselect_box(){
        $label = '<label class="h6 mb-0" for="'.$this->setting['field'].'">'.$this->setting['label'].'</label>';
        $desc = ((isset($this->setting['desc'])) ? '<br><small>'.$this->setting['desc'].'</small>' : "");
        $field = '<select style="min-height:100px;" class="form-control multiselect '.$this->pro.'" name="'.$this->setting['field'].'[]" id="'.$this->setting['field'].'" multiple'. '>';
            foreach($this->setting['value'] as $key => $val){
                if(isset($this->saved_value) && $this->saved_value != false){
                    $field .='<option value="'.$key.'" '.( ( in_array($key, $this->saved_value) ) ? " selected=\"selected\" " : "" ).'>'.$val.'</option>';
                }else{
                    $field .= '<option value="'.$key.'">'.$val.'</option>';
                }
            }
            $field .= '</select>';

            $this->bootstrap($label, $field, $desc);

    }

    /*
        Field type: Number box
    */
    function number_box(){

        $label = '<label class="h6 mb-0" for="'.$this->setting['field'].'">'.$this->setting['label'].'</label>';
        $desc =  (isset($this->setting['desc'])) ? '<br><small>'.$this->setting['desc'].'</small>' : "";
        $field = '<input type="number" class="form-control '.$this->pro.'" name="'.$this->setting['field'].'" id="'.$this->setting['field'].'" value="'.$this->saved_value.'"'
        .(isset($this->setting['min']) ? ' min="'.$this->setting['min'].'"': '')
        .(isset($this->setting['max']) ? ' max="'.$this->setting['max'].'"': '')
        .(isset($this->setting['required']) ? ' required="'.$this->setting['required'].'"': '')
        .(isset($this->setting['readonly']) ? ' readonly="'.$this->setting['readonly'].'"': '')
        .'>';
        $this->bootstrap($label, $field, $desc);
    }

    /*
        Field type: Number box
    */
    function text_box(){

        $label = '<label class="h6 mb-0" for="'.$this->setting['field'].'">'.$this->setting['label'].'</label>';
        $desc =  (isset($this->setting['desc'])) ? '<br><small>'.$this->setting['desc'].'</small>' : "";
        $field = '<input type="text" class="form-control '.$this->pro.'" name="'.$this->setting['field'].'" id="'.$this->setting['field'].'" value="'.$this->saved_value.'"'
        .(isset($this->setting['required']) ? ' required="'.$this->setting['required'].'"': '')
        .(isset($this->setting['readonly']) ? ' readonly="'.$this->setting['readonly'].'"': '')
        .'>';
        $this->bootstrap($label, $field, $desc);
    }
    
    /*
    Textarea field
    */
    function textarea_box(){
        $label = '<label class="h6 mb-0" for="'.$this->setting['field'].'">'.$this->setting['label'].'</label>';
        $desc =  (isset($this->setting['desc'])) ? '<br><small>'.$this->setting['desc'].'</small>' : "";
        $field = '<textarea type="text" class="form-control '.$this->pro.'" name="'.$this->setting['field'].'" id="'.$this->setting['field'].'"'
        .(isset($this->setting['required']) ? ' required="'.$this->setting['required'].'"': '')
        .(isset($this->setting['readonly']) ? ' readonly="'.$this->setting['readonly'].'"': '')
        .'>';
        $field .= $this->saved_value; 
        $field .= '</textarea>';
        $this->bootstrap($label, $field, $desc);
    }

     /*
        Field type: color
    */
    function color_box(){
        wp_enqueue_style( 'wp-color-picker');
        wp_enqueue_script( 'wp-color-picker');
        wp_add_inline_script('wp-color-picker','
        jQuery(document).ready(function($) {
            $(".color-picker").wpColorPicker();
          });
        ');
        $label = '<label class="h6 mb-0" for="'.$this->setting['field'].'">'.$this->setting['label'].'</label>';
        $desc =  (isset($this->setting['desc'])) ? '<br><small>'.$this->setting['desc'].'</small>' : "";
        $field = '<input type="text" class="color-picker pisol_select '.$this->pro.'" name="'.$this->setting['field'].'" id="'.$this->setting['field'].'" value="'.$this->saved_value.'"'
        .(isset($this->setting['required']) ? ' required="'.$this->setting['required'].'"': '')
        .(isset($this->setting['readonly']) ? ' readonly="'.$this->setting['readonly'].'"': '')
        .'>';
        $this->bootstrap($label, $field, $desc);
    }

    function hidden_box(){
        $label =  '<label class="h6 mb-0" for="'.$this->setting['field'].'">'.$this->setting['label'].'</label>';
        $desc =   (isset($this->setting['desc'])) ? '<br><small>'.$this->setting['desc'].'</small>' : "";
        $field ='<input type="hidden" class="pisol_select '.$this->pro.'" name="'.$this->setting['field'].'" id="'.$this->setting['field'].'" value="'.$this->saved_value.'"'
        .(isset($this->setting['required']) ? ' required="'.$this->setting['required'].'"': '')
        .(isset($this->setting['readonly']) ? ' readonly="'.$this->setting['readonly'].'"': '')
        .'>';
        $this->bootstrap($label, $field, $desc);
    }

    /*
        Field type: switch
    */
    function switch_display(){

        $label = '<label class="h6 mb-0" for="'.$this->setting['field'].'">'.$this->setting['label'].'</label>';
        $desc = (isset($this->setting['desc'])) ? '<br><small>'.$this->setting['desc'].'</small>' : "";
        
        $field = '<div class="custom-control custom-switch">
        <input type="checkbox" class="custom-control-input" name="'.$this->setting['field'].'" id="'.$this->setting['field'].'"'.(($this->saved_value == 1 || $this->saved_value) ? "checked=\'checked\'": "").' >
        <label class="custom-control-label" ></label>
        </div>';

        $this->bootstrap($label, $field, $desc);
    }
}
endif;
