<?php

/**
 * Premium Image Scroll.
 */
namespace PremiumAddons\Widgets;

// Elementor Classes.
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Control_Media;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;


// PremiumAddons Classes.
use PremiumAddons\Helper_Functions;
use PremiumAddons\Includes;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class Premium_Image_Scroll
 */
class Premium_Image_Scroll extends Widget_Base {
    
    public function getTemplateInstance() {
		return $this->templateInstance = Includes\premium_Template_Tags::getInstance();
    }
    
    public function get_name() {
        return 'premium-image-scroll';
    }

    public function get_title() {
		return sprintf( '%1$s %2$s', Helper_Functions::get_prefix(), __('Image Scroll', 'premium-addons-for-elementor') );
    }
    
    public function is_reload_preview_required() {
        return true;
    }

    public function get_icon() {
        return 'pa-image-scroll';
    }

    public function get_categories() {
        return [ 'premium-elements' ];
    }
    
    public function get_style_depends() {
        return [
            'premium-addons'
        ];
    }

    public function get_script_depends() {
        return [
            'imagesloaded',
            'premium-addons-js'
        ];
    }
    
    public function get_custom_help_url() {
		return 'https://premiumaddons.com/support/';
	}

    protected function _register_controls() {

		$this->start_controls_section('general_settings',
            [
                'label'         => __('Image Settings', 'premium-addons-for-elementor')
            ]
        );

		$this->add_control('image',
			[
                'label'         => __('Image', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::MEDIA,
                'dynamic'       => [ 'active' => true ],
                'default'       => [
                    'url'	=> Utils::get_placeholder_image_src(),
                ],
                'description'   => __('Choose the scroll image', 'premium-addons-for-elementor' ),
                'label_block'   => true
			]
        );
        
        $this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail',
				'default' => 'full',
				'separator' => 'none',
			]
		);
        
		$this->add_responsive_control('image_height',
            [
                'label'         => __('Image Height', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', 'em', 'vh'],
                'default'       => [
                    'unit'  => 'px',
                    'size'  => 300,
                ],
                'range'         => [
                    'px'    => [
                        'min'   => 200,
                        'max'   => 800,
                    ],
                    'em'    => [
                        'min'   => 1,
                        'max'   => 50,
                    ],
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-image-scroll-container' => 'height: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control('link_switcher', 
            [
                'label'         => __('Link', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'description'   => __('Add a custom link or select an existing page link','premium-addons-for-elementor'),
            ]
        );

        $this->add_control('link_type', 
            [
                'label'         => __('Link/URL', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'url'   => __('URL', 'premium-addons-for-elementor'),
                    'link'  => __('Existing Page', 'premium-addons-for-elementor'),
                ],
                'default'       => 'url',
                'condition'     => [
                    'link_switcher'  => 'yes',
                ],
                'label_block'   => true,
            ]
        );

        $this->add_control('link',
            [
                'label'         => __('URL', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::URL,
                'dynamic'       => [ 'active' => true ],
                'placeholder'   => 'https://premiumaddons.com/',
                'label_block'   => true,
                'condition'     => [
                	'link_switcher'	=> 'yes',
                    'link_type' => 'url'
                ]
            ]
        );

        $this->add_control('existing_page', 
            [
                'label'         => __('Existing Page', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT2,
                'options'       => $this->getTemplateInstance()->get_all_post(),
                'condition'     => [
                    'link_switcher'  => 'yes',
                    'link_type'     => 'link',
                ],
                'label_block'   => true,
            ]
        );

        $this->add_control('link_text',
            [
                'label'         => __('Link Title', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::TEXT,
                'condition'     => [
                    'link_switcher' => 'yes',
                ],
                'label_block'   => true
            ]
        );

        $this->add_control(
			'mask_image_scroll_switcher',
			[
				'label' => esc_html__( 'Mask Image Shape', 'premium-addons-for-elementor' ),
				'type' => Controls_Manager::HIDDEN,
				'separator' => 'before',
			]
        );
        
		$this->add_control(
			'mask_shape_image_scroll',
			[
				'label' => esc_html__( 'Mask Image', 'premium-addons-for-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => '',
				],
				'description' => esc_html__( 'Use PNG image with the shape you want to mask around feature image.', 'premium-addons-for-elementor' ),
				'selectors' => [
					'{{WRAPPER}} .premium-image-scroll-mask-media ' => 'mask-image: url({{URL}});-webkit-mask-image: url({{URL}});',
				],
				'condition' => [
					'mask_image_scroll_switcher' => 'yes',
				],
			]
        );

        $this->add_control(
			'mask_shape_size_image_scroll',
			[
				'label' => esc_html__( 'Mask Size', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .premium-image-scroll-mask-media ' => '-webkit-mask-size:{{SIZE}}{{UNIT}};mask-size:{{SIZE}}{{UNIT}};',
                ],
                'size_units'    => ['px','em','%'],
                'default'       => [
                    'size'	=> 100 ,
                    'unit' => '%'
                ],
				'condition' => [
					'mask_image_scroll_switcher' => 'yes',
				],
			]
        );

        $this->add_control('image_scroll_shadow',
            [
                'label'         => __('Image Shadow', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::POPOVER_TOGGLE,
                'condition' => [
					'mask_image_scroll_switcher' => 'yes',
                ],
            ]
        );
                $this->add_control('image_scroll_shadow',
            [
                'label'         => __('Image Shadow', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::POPOVER_TOGGLE,
                'condition' => [
					'mask_image_scroll_switcher' => 'yes',
                ],
                'return_value' => 'yes',
				'default' => 'no',
            ]
        );
        
        $this->start_popover();

        $this->add_control('image_scroll_shadow_color',
            [
                'label'         => __('Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'default'       => 'rgba(0,0,0,0.75)'
            ]
        );
        
        $this->add_control('image_scroll_shadow_blur',
			[
				'label'			=> __( 'Blur', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SLIDER,
                'range'         => [
                    'px'    => [
                        'min'   => 0,
                        'max'   => 100,
                        'step'  => 1
                    ]
                ],
                'default'       => [
                    'size'	=> 1 ,
                    'unit' => 'px'
                ],
			]
        );
        
        $this->add_control('image_scroll_shadow_h',
            [
                'label'			=> __( 'Horizontal', 'premium-addons-for-elementor' ),
                'type'			=> Controls_Manager::SLIDER,
                'range'         => [
                    'px'    => [
                        'min'   => 0,
                        'max'   => 100,
                        'step'  => 1
                    ]
                ],
                'default'       => [
                    'size'	=> 4 ,
                    'unit' => 'px'
                ],
            ]
        );

        $this->add_control('image_scroll_shadow_v',
            [
                'label'			=> __( 'Vertical', 'premium-addons-for-elementor' ),
                'type'			=> Controls_Manager::SLIDER,
                'range'         => [
                    'px'    => [
                        'min'   => 0,
                        'max'   => 100,
                        'step'  => 1
                    ]
                ],
                'default'       => [
                    'size'	=> 4 ,
                    'unit' => 'px'
                ],
            ]
        );

        $this->end_popover();
        

        

        $this->end_controls_section();

        $this->start_controls_section('advanced_settings',
			[
				'label' => __( 'Advanced Settings' , 'premium-addons-for-elementor' )
			]
        );

        $this->add_control('direction_type',
            [
                'label'			 => __( 'Direction', 'premium-addons-for-elementor' ),
                'description'	 => __( 'Select Scroll Direction', 'premium-addons-for-elementor' ),
                'type'			 => Controls_Manager::SELECT,
                'options'		 => [
                    'horizontal' => __( 'Horizontal', 'premium-addons-for-elementor' ),
                    'vertical'   => __( 'Vertical', 'premium-addons-for-elementor' )
                ],
                'default'		=> 'vertical'
            ]
        );
        
        $this->add_control('reverse',
            [
                'label'			=> __( 'Reverse Direction', 'premium-addons-for-elementor' ),
                'type'			=> Controls_Manager::SWITCHER,
                'condition'     => [
                    'trigger_type' => 'hover',
                ]
            ]
        );

        $this->add_control('trigger_type', 
            [
                'label'         => __('Trigger', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'hover'   => __('Hover', 'premium-addons-for-elementor'),
                    'scroll'  => __('Mouse Scroll', 'premium-addons-for-elementor'),
                ],
                'default'       => 'hover',
            ]
        );

        $this->add_control('duration_speed',
            [
                'label'			=> __( 'Speed', 'premium-addons-for-elementor' ),
                'description'	=> __( 'Set the scroll speed value. The value will be counted in seconds (s)', 'premium-addons-for-elementor' ),
                'type'			=> Controls_Manager::NUMBER,
                'default'		=> 3,
                'condition'     => [
                    'trigger_type' => 'hover',
                ],
                'selectors' => [
                    '{{WRAPPER}} .premium-image-scroll-container img'   => 'transition-duration: {{Value}}s',
                ]
            ]
        );
        
        $this->add_control('icon_switcher',
            [
                'label'         => __('Icon', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control('icon_size',
            [
                'label'         => __('Icon Size', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px','em'],
                'default'       => [
                    'size'  => 30,
                ],
                'range'         => [
                    'px'    => [
                        'min' => 5,
                        'max' => 100
                    ],
                ],
                'selectors'         => [
                    '{{WRAPPER}} .premium-image-scroll-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition'      => [
                    'icon_switcher' => 'yes'
                ]
            ]
        );

        $this->add_control('overlay',
            [
                'label'         => __('Overlay','premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __('Show','premium-addons-for-elementor'),
                'label_off'     => __('Hide','premium-addons-for-elementor'),
                
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section('image_style',
            [
                'label'         => __('Image', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control('icon_color',
            [
                'label'         => __('Icon Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-image-scroll-icon'  => 'color: {{VALUE}};'
                ],
                'condition'     => [
                    'icon_switcher'  => 'yes'
                ]
            ]
        );
        
        $this->add_control('overlay_background',
            [
                'label'         => __('Overlay Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-image-scroll-overlay'  => 'background: {{VALUE}};'
                ],
                'condition'     => [
                    'overlay'  => 'yes'
                ]
            ]
        );
        
        $this->start_controls_tabs('image_style_tabs');
        
        $this->start_controls_tab('image_style_tab_normal',
            [
                'label'         => __('Normal', 'premium-addons-for-elementor'),
            ]
        );
        
        $this->add_control('opacity',
			[
				'label'     => __( 'Opacity', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max'   => 1,
						'min'   => 0.10,
						'step'  => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} img' => 'opacity: {{SIZE}};',
				],
			]
		);

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'css_filters',
                'selector' => '{{WRAPPER}} .premium-image-scroll-container img',
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab('image_style_tab_hover',
            [
                'label'         => __('Hover', 'premium-addons-for-elementor'),
            ]
        );
        
        $this->add_control('hover_opacity',
			[
				'label'     => __( 'Opacity', 'premium-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max'   => 1,
						'min'   => 0.10,
						'step'  => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .premium-image-scroll-section:hover img' => 'opacity: {{SIZE}};',
				],
			]
		); 
       
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'css_filters_hover',
                'selector' => '{{WRAPPER}} .premium-image-scroll-container img:hover',
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->add_control('blend_mode',
			[
				'label'     => __( 'Blend Mode', 'elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					''              => __( 'Normal', 'elementor' ),
					'multiply'      => 'Multiply',
					'screen'        => 'Screen',
					'overlay'       => 'Overlay',
					'darken'        => 'Darken',
					'lighten'       => 'Lighten',
					'color-dodge'   => 'Color Dodge',
					'saturation'    => 'Saturation',
					'color'         => 'Color',
					'luminosity'    => 'Luminosity',
				],
                'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .premium-image-scroll-container img' => 'mix-blend-mode: {{VALUE}}',
				],
			]
        );
        
        $this->end_controls_section();

        $this->start_controls_section('container_style',
            [
                'label'     => __('Container','premium-addons-for-elementor'),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->start_controls_tabs('container_style_tabs');

        $this->start_controls_tab('container_style_normal',
            [
                'label'         => __('Normal', 'premium-addons-for-elementor'),
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'              => 'container_border',
                'selector'          => '{{WRAPPER}} .premium-image-scroll-section',
            ]
        );

        $this->add_control('container_border_radius',
            [
                'label'         => __('Border Radius', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%', 'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-image-scroll-section, {{WRAPPER}} .premium-container-scroll' => 'border-radius: {{SIZE}}{{UNIT}}'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'              => 'container_box_shadow',
                'selector'          => '{{WRAPPER}} .premium-image-scroll-section',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('container_style_hover',
            [
                'label'         => __('Hover', 'premium-addons-for-elementor'),
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'              => 'container_border_hover',
                'selector'          => '{{WRAPPER}} .premium-image-scroll-section:hover',
            ]
        );

        $this->add_control('container_border_radius_hover',
            [
                'label'         => __('Border Radius', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%', 'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-image-scroll-section:hover, {{WRAPPER}} .premium-container-scroll:hover' => 'border-radius: {{SIZE}}{{UNIT}}'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'              => 'container_box_shadow_hover',
                'selector'          => '{{WRAPPER}} .premium-image-scroll-section:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

    }
    
    /**
	 * Render Image Scroll widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
    protected function render() {
        
        $settings = $this->get_settings_for_display();

        $link_type = $settings['link_type'];

        $link_url = ( 'url' == $link_type ) ? $settings['link']['url'] : get_permalink( $settings['existing_page'] );
       
        if ( $settings['link_switcher'] == 'yes' ) {
            $this->add_render_attribute( 'link', 'class', 'premium-image-scroll-link' );

            if( ! empty( $settings['link']['is_external'] ) ) {
                $this->add_render_attribute( 'link', 'target', "_blank" );
            }

            if( ! empty( $settings['link']['nofollow'] ) ) {
                $this->add_render_attribute( 'link', 'rel',  "nofollow" );
            }

            if( ! empty( $settings['link_text'] ) ) {
                $this->add_render_attribute( 'link', 'title', $settings['link_text'] );
            }

            if( ! empty( $settings['link']['url'] ) || ! empty( $settings['existing_page'] ) ) {
                $this->add_render_attribute( 'link', 'href',  $link_url );
            }
        }
       
        if ( $settings['icon_switcher'] ) {
            $icon_type = sprintf('pa-%s-mouse-scroll', $settings['direction_type'] );
        }
        

        $image_scroll = [
            'trigger'     => $settings['trigger_type'] ,
            'direction'   => $settings['direction_type'],
            'reverse'     => $settings['reverse'],
        ];

        $this->add_render_attribute( 'container', 'class', 'premium-image-scroll-container' );
  
        $this->add_render_attribute( 'container', 'data-settings', wp_json_encode( $image_scroll ) );
        
        $this->add_render_attribute( 'direction_type', 'class', 'premium-image-scroll-' . $settings['direction_type'] );
        
        if(!empty($settings["mask_image_scroll_switcher"]) && $settings["mask_image_scroll_switcher"]==='yes'){
            $this->add_render_attribute( 'direction_type', 'class', 'premium-image-scroll-mask-media' );
        }

        $image_html = '';
        if ( ! empty( $settings['image']['url'] ) ) {
            
			$this->add_render_attribute( 'image', 'src', $settings['image']['url'] );
            
			$this->add_render_attribute( 'image', 'alt', Control_Media::get_image_alt( $settings['image'] ) );
            
			$this->add_render_attribute( 'image', 'title', Control_Media::get_image_title( $settings['image'] ) );

			$image_html = Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail', 'image' );
            
        }
        if($settings['mask_image_scroll_switcher'] === 'yes' && $settings['mask_shape_image_scroll']['url'] !== ''){
            $this->add_render_attribute( 'shadow', 'style', 'filter: drop-shadow('.$settings['image_scroll_shadow_color'] .' '. $settings['image_scroll_shadow_h']['size'] .'px '. $settings['image_scroll_shadow_v']['size'] .'px '. $settings['image_scroll_shadow_blur']['size'].'px '.')' );
        }

        ?>
            <div class="premium-image-scroll-section" <?php echo $this->get_render_attribute_string('shadow'); ?>>
                <div <?php echo $this->get_render_attribute_string('container'); ?>>
                    <?php if( 'yes' == $settings['icon_switcher'] ) : ?>
                        <div class="premium-image-scroll-content">
                            <i class="premium-image-scroll-icon <?php echo $icon_type ?>"></i>
                        </div>
                    <?php endif; ?>
                    <div <?php echo $this->get_render_attribute_string('direction_type'); ?>>
                        <?php  if($settings['overlay'] == 'yes') : ?>
                            <div class="premium-image-scroll-overlay">
                        <?php endif;
                            if ( $settings['link_switcher'] == 'yes' && ! empty( $link_url ) ) : ?>
                                <a <?php echo $this->get_render_attribute_string('link'); ?>></a>
                        <?php endif;
                            if( $settings['overlay'] == 'yes' ) : ?>
                            </div> 
                        <?php endif;
                            echo $image_html;
                        ?>
                    </div>
                </div>
            </div>
        <?php
      
    }
    
    /**
	 * Render Image Scroll widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
    protected function content_template() {
    ?>
        <#
        
            var linkType = settings.link_type,
                trigger = settings.trigger_type,
                direction = settings.direction_type,
                reverse = settings.reverse,
                url;
            
            var scrollSettings = {};
            
            scrollSettings.trigger = trigger;
            scrollSettings.direction = direction,
            scrollSettings.reverse  = reverse;
            
            if ( 'yes' == settings.icon_switcher ) {
            
                var iconClass = 'pa-' + direction + '-mouse-scroll';
            
            }
            
            
            if ( 'yes' == settings.link_switcher ) {
                view.addRenderAttribute( 'link', 'class', 'premium-image-scroll-link' );
                url = 'url' == linkType ? settings.link.url : settings.existing_page;
                view.addRenderAttribute( 'link', 'href',  url );
                
                if ( 'yes' == settings.link_switcher ) {
                    view.addRenderAttribute( 'link', 'title', settings.link_text );
                }
            }
            
            view.addRenderAttribute( 'container', 'class', 'premium-image-scroll-container' );

            view.addRenderAttribute( 'container', 'data-settings', JSON.stringify(scrollSettings) );
            
            view.addRenderAttribute( 'direction_type', 'class', 'premium-image-scroll-' + direction );

            view.addRenderAttribute( 'image', 'src', settings.image.url );

            if(settings.mask_image_scroll_switcher && settings.mask_image_scroll_switcher ==='yes'){
                view.addRenderAttribute( 'direction_type', 'class', 'premium-image-scroll-mask-media');
            }
         
            var imageHtml = '';
            if ( settings.image.url ) {
			var image = {
				id: settings.image.id,
				url: settings.image.url,
				size: settings.thumbnail_size,
				dimension: settings.thumbnail_custom_dimension,
				model: view.getEditModel()
			};

			var image_url = elementor.imagesManager.getImageUrl( image );
            
			imageHtml = '<img src="' + image_url + '"/>';
            
		}
        if(settings.mask_image_scroll_switcher === 'yes' && settings.mask_shape_image_scroll.url !== ''){
            view.addRenderAttribute( 'shadow', 'style', 'filter: drop-shadow('+settings.image_scroll_shadow_color +' '+ settings.image_scroll_shadow_h.size +'px '+ settings.image_scroll_shadow_v.size +'px '+ settings.image_scroll_shadow_blur.size+'px '+')' );
        }

        #>
        <div class="premium-image-scroll-section" {{{ view.getRenderAttributeString('shadow') }}}>
            <div {{{ view.getRenderAttributeString('container') }}}>
                <# if (  'yes' == settings.icon_switcher ) { #>
                    <div class="premium-image-scroll-content">   
                        <i class="premium-image-scroll-icon {{ iconClass }}"></i>
                    </div>
                <# } #>
                <div {{{ view.getRenderAttributeString('direction_type') }}}>
                    <# if( 'yes' == settings.overlay ) { #>
                        <div class="premium-image-scroll-overlay">
                    <# }
                    if ( 'yes' == settings.link_switcher && '' !=  url ) { #>
                        <a {{{ view.getRenderAttributeString('link') }}}></a>
                    <# }
                    if( 'yes' == settings.overlay ) { #>
                        </div> 
                    <# } #>
                    
                    {{{ imageHtml }}}
                    
                </div>
            </div>
        </div>
    <?php 
    }
    
}
