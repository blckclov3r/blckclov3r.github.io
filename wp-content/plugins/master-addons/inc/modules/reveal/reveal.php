<?php
namespace MasterAddons\Inc\Extensions;

use \Elementor\Controls_Manager;
use \MasterAddons\Inc\Classes\JLTMA_Extension_Prototype;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; };

/**
 * Reveals - Opening effect
*/

class JLTMA_Extension_Reveal extends JLTMA_Extension_Prototype {
    
    private static $instance = null;
    public $name = 'Reveal';
    public $has_controls = true;

    public function get_script_depends() {
	    return [ 
            'ma-el-anime-lib', 
            'ma-el-reveal-lib', 
            'master-addons-scripts' 
        ];
    }


    protected function add_actions() {

        // Activate controls for widgets
        add_action('elementor/element/common/jltma_section_reveal_advanced/before_section_end', function( $element, $args ) {
            $this->add_controls($element, $args);
        }, 10, 2);

        add_filter('elementor/widget/print_template', array($this, 'reveal_print_template'), 9, 2);

        add_action('elementor/widget/render_content', array($this, 'reveal_render_template'), 9, 2);

    }

    private function add_controls($element, $args) {

        $element_type = $element->get_type();

        $element->add_control(
            'enabled_reveal', [
                'label' => __('Enabled Reveal', MELA_TD),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => __('Yes', MELA_TD),
                'label_off' => __('No', MELA_TD),
                'return_value' => 'yes',
                'frontend_available' => true
            ]
        );
        $element->add_control(
            'reveal_direction',
            [
                'label' => __( 'Direction', MELA_TD ),
                'type' => Controls_Manager::SELECT,
                'default' => 'c',
                'options' => [
                    'c' => __( 'Center', MELA_TD ),
                    'lr' => __( 'Left to Right', MELA_TD ),
                    'rl' => __( 'Right to Left', MELA_TD ),
                    'tb' => __( 'Top to Bottom', MELA_TD ),
                    'bt' => __( 'Bottom to top', MELA_TD ),
                ],
                'frontend_available' => true,
                'condition' => [
                    'enabled_reveal' => 'yes'
                ]
            ]
        );
        $element->add_control(
            'reveal_speed', [
                'label' => __('Speed', MELA_TD),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 10,
                        'step' => 0.1
                    ],
                ],
                'default' => [
                    'size' => 5,
                    
                ],
                'frontend_available' => true,
                'condition' => [
                    'enabled_reveal' => 'yes'
                ]
            ]
        );
        $element->add_control(
            'reveal_delay', [
                'label' => __('Delay', MELA_TD),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 20,
                        'step' => 0.1
                    ],
                ],
                'default' => [
                    'size' => 0,
                    
                ],
                'frontend_available' => true,
                'condition' => [
                    'enabled_reveal' => 'yes'
                ]
            ]
        );
        $element->add_control(
            'reveal_bgcolor', [
                'label' => __('Color', MELA_TD),
                'type' => Controls_Manager::COLOR,
                'frontend_available' => true,
                'condition' => [
                    'enabled_reveal' => 'yes'
                ]
            ]
        );
        
        
    }


    public function reveal_print_template($content, $widget) {
        if (!$content)
            return '';

        $id_item = $widget->get_id();

        $content = "<# if ( '' !== settings.enabled_reveal ) { #><div id=\"reveal-{{id}}\" class=\"reveal\">" . $content . "</div><# } else { #>" . $content . "<# } #>";
        return $content;
    }

    public function reveal_render_template($content, $widget) {
        $settings = $widget->get_settings_for_display();

        if ($settings['enabled_reveal']) {
            
            $this->_enqueue_alles();

            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {}

            $id_item = $widget->get_id();
            $content = '<div id="reveal-' . $id_item . '" class="revealFx">' . $content . '</div>';
        }
        return $content;
    }
    

    public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
    }
    
}

JLTMA_Extension_Reveal::get_instance();