<?php

/**
 * Premium Image Separator.
 */
namespace PremiumAddons\Widgets;

// Elementor Classes.
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Control_Media;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Text_Shadow;

// PremiumAddons Classes.
use PremiumAddons\Helper_Functions;
use PremiumAddons\Includes;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

/**
 * Class Premium_Image_Separator
 */
class Premium_Image_Separator extends Widget_Base {

    protected $templateInstance;

    public function getTemplateInstance() {
        return $this->templateInstance = Includes\premium_Template_Tags::getInstance();
    }

    public function get_name() {
        return 'premium-addon-image-separator';
    }

    public function get_title() {
		return sprintf( '%1$s %2$s', Helper_Functions::get_prefix(), __('Image Separator', 'premium-addons-for-elementor') );
	}

    public function get_style_depends() {
        return [
            'premium-addons'
        ];
    }

    public function get_script_depends() {
        return [
            'lottie-js'
        ];
    }
    
    public function get_icon() {
        return 'pa-image-separator';
    }

    public function get_categories() {
        return [ 'premium-elements' ];
    }

    public function get_keywords() {
		return [ 'divider', 'section', 'shape' ];
	}
    
    public function get_custom_help_url() {
		return 'https://premiumaddons.com/support/';
	}

    /**
	 * Register Image Controls controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
    protected function _register_controls() {

        $this->start_controls_section('premium_image_separator_general_settings',
            [
                'label'         => __('Image Settings', 'premium-addons-for-elementor')
            ]
        );
        
        $this->add_control('separator_type',
            [
                'label'			=> __( 'Separator Type', 'premium-addons-for-elementor' ),
                'type' 			=> Controls_Manager::SELECT,
                'options'		=> [
                    'icon'          => __('Icon', 'premium-addons-for-elementor'),
                    'image'         => __( 'Image', 'premium-addons-for-elementor'),
                    'animation'     => __('Lottie Animation', 'premium-addons-for-elementor'),
                ],
                'default'		=> 'image'
            ]
        );

        $this->add_control('separator_icon',
		  	[
		     	'label'			=> __( 'Select an Icon', 'premium-addons-for-elementor' ),
		     	'type'              => Controls_Manager::ICONS,
                'default' => [
                    'value'     => 'fas fa-grip-lines',
                    'library'   => 'fa-solid',
                ],
			  	'condition'		=> [
			  		'separator_type' => 'icon'
			  	]
		  	]
        );

        $this->add_control('premium_image_separator_image',
            [
                'label'         => __('Image', 'premium-addons-for-elementor'),
                'description'   => __('Choose the separator image', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::MEDIA,
                'dynamic'       => [ 'active' => true ],
                'default'       => [
                    'url'	=> Utils::get_placeholder_image_src(),
                ],
                'label_block'   => true,
                'condition'     => [
                    'separator_type'    => 'image'
                ]
            ]
        );

        $this->add_control('lottie_url', 
            [
                'label'             => __( 'Animation JSON URL', 'premium-addons-for-elementor' ),
                'type'              => Controls_Manager::TEXT,
                'dynamic'           => [ 'active' => true ],
                'description'       => 'Get JSON code URL from <a href="https://lottiefiles.com/" target="_blank">here</a>',
                'label_block'       => true,
                'condition'         => [
                    'separator_type'    => 'animation'
                ],
            ]
        );

        $this->add_control('lottie_loop',
            [
                'label'         => __('Loop','premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'true',
                'default'       => 'true',
                'condition'         => [
                    'separator_type'    => 'animation'
                ],
            ]
        );

        $this->add_control('lottie_reverse',
            [
                'label'         => __('Reverse','premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'true',
                'condition'         => [
                    'separator_type'    => 'animation'
                ],
            ]
        );

        $this->add_control('lottie_hover',
            [
                'label'         => __('Only Play on Hover','premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'true',
                'condition'         => [
                    'separator_type'    => 'animation'
                ],
            ]
        );

        $this->add_responsive_control('premium_image_separator_image_size',
            [
                'label'         => __('Size', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', 'em', '%'],
                'default'       => [
                    'unit'  => 'px',
                    'size'  => 200,
                ],
                'range'         => [
                    'px'    => [
                        'min'   => 1, 
                        'max'   => 800, 
                    ],
                    'em'    => [
                        'min'   => 1, 
                        'max'   => 30,
                    ],
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-image-separator-container img'    => 'width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .premium-image-separator-container i'      => 'font-size: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .premium-image-separator-container svg'    => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_image_separator_image_gutter',
            [
                'label'         => __('Gutter (%)', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::NUMBER,
                'default'       => -50,
                'description'   => __('-50% is default. Increase to push the image outside or decrease to pull the image inside.','premium-addons-for-elementor'),
                'selectors'     => [
                    '{{WRAPPER}} .premium-image-separator-container' => 'transform: translateY( {{VALUE}}% );'
                ]
            ]
        );
        
        $this->add_control('premium_image_separator_image_align', 
            [
                'label'         => __('Alignment', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::CHOOSE,
                'options'       => [
                    'left'  => [
                        'title'     => __('Left', 'premium-addons-for-elementor'),
                        'icon'      => 'fa fa-align-left'
                    ],
                    'center'  => [
                        'title'     => __('Center', 'premium-addons-for-elementor'),
                        'icon'      => 'fa fa-align-center'
                    ],
                    'right'  => [
                        'title'     => __('Right', 'premium-addons-for-elementor'),
                        'icon'      => 'fa fa-align-right'
                    ],
                ],
                'default'       => 'center',
                'selectors'     => [
                    '{{WRAPPER}} .premium-image-separator-container'   => 'text-align: {{VALUE}};',
                ]
            ]
        );
        
        $this->add_control('premium_image_separator_link_switcher', 
            [
                'label'         => __('Link', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'description'   => __('Add a custom link or select an existing page link','premium-addons-for-elementor'),
            ]
        );
        
        $this->add_control('premium_image_separator_link_type', 
            [
                'label'         => __('Link/URL', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'url'   => __('URL', 'premium-addons-for-elementor'),
                    'link'  => __('Existing Page', 'premium-addons-for-elementor'),
                ],
                'default'       => 'url',
                'label_block'   => true,
                'condition'     => [
                    'premium_image_separator_link_switcher'  => 'yes',
                ],
            ]
        );
        
        $this->add_control('premium_image_separator_existing_page', 
            [
                'label'         => __('Existing Page', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT2,
                'options'       => $this->getTemplateInstance()->get_all_post(),
                'multiple'      => false,
                'label_block'   => true,
                'condition'     => [
                    'premium_image_separator_link_switcher'  => 'yes',
                    'premium_image_separator_link_type'     => 'link',
                ],
            ]
        );
        
        $this->add_control('premium_image_separator_image_link',
            [
                'label'         => __('URL', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::TEXT,
                'dynamic'       => [
                    'active'        => true,
                    'categories'    => [
                        TagsModule::POST_META_CATEGORY,
                        TagsModule::URL_CATEGORY
                    ]
                ],
                'label_block'   => true,
                'condition'     => [
                    'premium_image_separator_link_switcher' => 'yes',
                    'premium_image_separator_link_type'     => 'url',
                ],
            ]
        );
        
        $this->add_control('premium_image_separator_image_link_text',
            [
                'label'         => __('Image Title', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::TEXT,
                'label_block'   => true,
                'condition'     => [
                    'premium_image_separator_link_switcher' => 'yes',
                ],
            ]
        );
        
        $this->add_control('link_new_tab',
            [
                'label'         => __('Open Link in New Tab', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'default'       => 'yes',
                'condition'     => [
                    'premium_image_separator_link_switcher' => 'yes',
                ],
            ]
        );
       
        $this->end_controls_section();
        
        $this->start_controls_section('premium_image_separator_style',
            [
                'label'         => __('Separator', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'      => 'css_filters',
                'selector'  => '{{WRAPPER}} .premium-image-separator-container',
                'condition' => [
                    'separator_type!'    => 'icon'
                ]
			]
        );

        $this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'      => 'hover_css_filters',
                'label'     => __('Hover CSS Filters', 'premium-addons-for-elementor'),
                'selector'  => '{{WRAPPER}} .premium-image-separator-container:hover',
                'condition' => [
                    'separator_type!'    => 'icon'
                ]
			]
		);
        
        $this->add_control('icon_color',
		  	[
				'label'         => __( 'Color', 'premium-addons-for-elementor' ),
                'type' 			=> Controls_Manager::COLOR,
                'scheme' 		=> [
				    'type' 	=> Scheme_Color::get_type(),
				    'value' => Scheme_Color::COLOR_1,
				],
				'selectors'     => [
					'{{WRAPPER}} .premium-image-separator-container i' => 'color: {{VALUE}}'
				],
			  	'condition'     => [
			  		'separator_type' => 'icon'
			  	]
			]
        );

        $this->add_control('icon_hover_color',
            [
                'label'         => __( 'Hover Color', 'premium-addons-for-elementor' ),
                'type' 			=> Controls_Manager::COLOR,
                'scheme' 		=> [
                    'type' 	=> Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-image-separator-container i:hover' => 'color: {{VALUE}}'
                ],
                'condition'     => [
                    'separator_type' => 'icon'
                ]
            ]
        );
        
        $this->add_control('icon_background_color',
		  	[
				'label'         => __( 'Background Color', 'premium-addons-for-elementor' ),
                'type' 			=> Controls_Manager::COLOR,
                'scheme' 		=> [
				    'type' 	=> Scheme_Color::get_type(),
				    'value' => Scheme_Color::COLOR_2,
				],
				'selectors'     => [
					'{{WRAPPER}} .premium-image-separator-container i' => 'background-color: {{VALUE}}'
				],
			  	'condition'     => [
			  		'separator_type' => 'icon'
			  	]
			]
        );
 
        $this->add_control('icon_hover_background_color',
		  	[
				'label'         => __( 'Hover Background Color', 'premium-addons-for-elementor' ),
                'type' 			=> Controls_Manager::COLOR,
                'scheme' 		=> [
				    'type' 	=> Scheme_Color::get_type(),
				    'value' => Scheme_Color::COLOR_2,
				],
				'selectors'     => [
					'{{WRAPPER}} .premium-image-separator-container i:hover' => 'background-color: {{VALUE}}'
				],
			  	'condition'     => [
			  		'separator_type' => 'icon'
			  	]
			]
        );

        $this->add_responsive_control('separator_border_radius',
            [
                'label'         => __('Border Radius', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-image-separator-container i, {{WRAPPER}} .premium-image-separator-container img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'          => 'separator_shadow',
                'selector'      => '{{WRAPPER}} .premium-image-separator-container i',
                'condition'     => [
                    'separator_type' => 'icon'
                ]
            ]
        );
        
        $this->add_responsive_control('icon_padding',
            [
                'label'         => __('Padding', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-image-separator-container i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}'
                ],
                'condition'     => [
                    'separator_type' => 'icon'
                ]
            ]
        );
        
        $this->end_controls_section();
       
    }

    /**
	 * Render Image Separator widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
    protected function render() {
        
        $settings = $this->get_settings_for_display();
        
        $type   = $settings['separator_type'];

        if( 'yes' === $settings['premium_image_separator_link_switcher'] ) {
            $link_type = $settings['premium_image_separator_link_type'];

            if( 'url' === $link_type ) {
                $url = $settings['premium_image_separator_image_link'];
            } else {
                $url = get_permalink( $settings['premium_image_separator_existing_page'] );
            }

            $this->add_render_attribute( 'link', [
                'class' => 'premium-image-separator-link',
                'href'  => $url
            ]);

            if( 'yes' === $settings['link_new_tab'] ) {
                $this->add_render_attribute( 'link', 'target', '_blank');
            }

            if( ! empty ( $settings['premium_image_separator_image_link_text']) ) {
                $this->add_render_attribute( 'link', 'title', $settings['premium_image_separator_image_link_text'] );
            }
            
        }
        
        if( 'image' === $type ) {
            $alt = esc_attr( Control_Media::get_image_alt( $settings['premium_image_separator_image'] ) );
        } elseif ( 'animation' === $type ) {
            $this->add_render_attribute( 'separator_lottie', [
                'class' => 'premium-lottie-animation',
                'data-lottie-url' => $settings['lottie_url'],
                'data-lottie-loop' => $settings['lottie_loop'],
                'data-lottie-reverse' => $settings['lottie_reverse'],
                'data-lottie-hover' => $settings['lottie_hover']
            ]);
        }
    ?>

    <div class="premium-image-separator-container">
        <?php if( 'image' === $type ) : ?>
            <img src="<?php echo $settings['premium_image_separator_image']['url']; ?>" alt="<?php echo $alt; ?>">
        <?php elseif( 'icon' === $type ) :
            Icons_Manager::render_icon( $settings['separator_icon'], [ 'aria-hidden' => 'true' ] );
        else: ?>
            <div <?php echo $this->get_render_attribute_string( 'separator_lottie' ); ?>></div>
        <?php endif; ?>
        <?php if (  $settings['premium_image_separator_link_switcher'] === 'yes' && ! empty( $url ) ) : ?>
            <a <?php echo $this->get_render_attribute_string( 'link' ); ?>></a>
        <?php endif; ?>
    </div>
    <?php
    }
    
    /**
	 * Render Image Separtor widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
    protected function content_template() {
        ?>
        <#
            var type        = settings.separator_type,
                linkSwitch  = settings.premium_image_separator_link_switcher;
                
            if( 'image' === type ) {
                var imgUrl = settings.premium_image_separator_image.url;
            } else if ( 'icon' === type ) {
                var iconHTML = elementor.helpers.renderIcon( view, settings.separator_icon, { 'aria-hidden': true }, 'i' , 'object' );    
            } else {

                view.addRenderAttribute( 'separator_lottie', {
                    'class': 'premium-lottie-animation',
                    'data-lottie-url': settings.lottie_url,
                    'data-lottie-loop': settings.lottie_loop,
                    'data-lottie-reverse': settings.lottie_reverse,
                    'data-lottie-hover': settings.lottie_hover
                });
                
            }

            if( 'yes' === linkSwitch ) {
                var linkType = settings.premium_image_separator_link_type,
                    linkTitle = settings.premium_image_separator_image_link_text,
                    linkUrl = ( 'url' == linkType ) ? settings.premium_image_separator_image_link : settings.premium_image_separator_existing_page;

                view.addRenderAttribute( 'link', 'class', 'premium-image-separator-link' );
                view.addRenderAttribute( 'link', 'href', linkUrl );

                if( '' !== linkTitle ) {
                    view.addRenderAttribute( 'link', 'title', linkTitle );
                }
                
            }

        #>

        <div class="premium-image-separator-container">
            <# if( 'image' === type ) { #>
                <img alt="image separator" src="{{ imgUrl }}">
            <# } else if( 'icon' === type ) { #>
                {{{ iconHTML.value }}} 
            <# } else { #>
                <div {{{ view.getRenderAttributeString('separator_lottie') }}}></div>
            <# } #>
            <# if( 'yes' === linkSwitch ) { #>
                <a {{{ view.getRenderAttributeString( 'link' ) }}}></a>
            <# } #>
        </div>
        
        <?php  
    }
}