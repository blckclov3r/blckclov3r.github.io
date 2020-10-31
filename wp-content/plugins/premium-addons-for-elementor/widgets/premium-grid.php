<?php

/**
 * Premium Media Grid.
 */
namespace PremiumAddons\Widgets;

// Elementor Classes.
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Embed;
use Elementor\Control_Media;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;

// PremiumAddons Classes.
use PremiumAddons\Helper_Functions;
use PremiumAddons\Includes;

if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class Premium_Grid
 */
class Premium_Grid extends Widget_Base {
    
    public function get_name() {
        return 'premium-img-gallery';
    }
    
    public function getTemplateInstance() {
		return $this->templateInstance = Includes\premium_Template_Tags::getInstance();
	}
    
    public function get_title() {
		return sprintf( '%1$s %2$s', Helper_Functions::get_prefix(), __('Media Grid', 'premium-addons-for-elementor') );
	}
    
    public function get_icon() {
        return 'pa-grid-icon';
    }
    
    public function get_style_depends() {
        return [
            'pa-prettyphoto',
            'premium-addons'
        ];
    }
    
    public function get_script_depends() {
        return [
            'prettyPhoto-js',
            'isotope-js',
            'premium-addons-js'
        ];
    }
    
    public function is_reload_preview_required() {
        return true;
    }
    
    public function get_categories() {
        return ['premium-elements'];
    }

    public function get_keywords() {
        return ['layout', 'gallery', 'images', 'videos', 'portfolio', 'visual', 'masonry'];
    }
    
    public function get_custom_help_url() {
		return 'https://premiumaddons.com/support/';
	}
    
    /**
	 * Register Media Grid controls.
	 *
	 * @since 2.1.0
	 * @access protected
	 */
    protected function _register_controls() {
        
        $this->start_controls_section('premium_gallery_general',
            [
                'label'             => __('Layout','premium-addons-for-elementor'),
                
            ]);
        
        $this->add_control('premium_gallery_img_size_select',
            [
                'label'             => __('Grid Layout', 'premium-addons-for-elementor'),
                'type'              => Controls_Manager::SELECT,
                'options'           => [
                    'fitRows'  => __('Even', 'premium-addons-for-elementor'),
                    'masonry'  => __('Masonry', 'premium-addons-for-elementor'),
                    'metro'    => __('Metro', 'premium-addons-for-elementor'), 
                ],
                'default'           => 'fitRows',
            ]
        );
        
        $this->add_responsive_control('pemium_gallery_even_img_height',
			[ 
 				'label'             => __( 'Height', 'premium-addons-for-elementor' ),
				'label_block'       => true,
                'size_units'        => ['px', 'em', 'vh'],
				'type'              => Controls_Manager::SLIDER,
                'range'             => [
                    'px'    => [
                        'min'   => 1, 
                        'max'   => 500,
                    ],
                    'em'    => [
                        'min'   => 1, 
                        'max'   => 50,
                    ],
                ],
                'render_type'       => 'template',
                'condition'         => [
                    'premium_gallery_img_size_select'   => 'fitRows'
                ],
                'selectors'         => [
                    '{{WRAPPER}} .premium-gallery-item .pa-gallery-image' => 'height: {{SIZE}}{{UNIT}}'
                ]
			]
		);

        $this->add_control('premium_gallery_images_fit',
            [
                'label'             => __('Images Fit', 'premium-addons-for-elementor'),
                'type'              => Controls_Manager::SELECT,
                'options'           => [
                    'fill'   => __('Fill', 'premium-addons-for-elementor'),
                    'cover'  => __('Cover', 'premium-addons-for-elementor'),
               ],
                'default'           => 'fill',
                'selectors'         => [
                    '{{WRAPPER}} .premium-gallery-item .pa-gallery-image'  => 'object-fit: {{VALUE}}'
                ],
                'condition'         => [
                    'premium_gallery_img_size_select'   =>  [ 'metro', 'fitRows' ]
                ]
            ]
        );
        
        $this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'             => 'thumbnail',
				'default'          => 'full',
			]
		);
        
        $this->add_responsive_control('premium_gallery_column_number',
			[
  				'label'            => __( 'Columns', 'premium-addons-for-elementor' ),
				'label_block'      => true,
				'type'             => Controls_Manager::SELECT,				
				'desktop_default'  => '50%',
				'tablet_default'   => '100%',
				'mobile_default'   => '100%',
				'options'          => [
					'100%'      => __( '1 Column', 'premium-addons-for-elementor' ),
					'50%'       => __( '2 Columns', 'premium-addons-for-elementor' ),
					'33.330%'   => __( '3 Columns', 'premium-addons-for-elementor' ),
					'25%'       => __( '4 Columns', 'premium-addons-for-elementor' ),
					'20%'       => __( '5 Columns', 'premium-addons-for-elementor' ),
					'16.66%'    => __( '6 Columns', 'premium-addons-for-elementor' ),
                    '8.33%'     => __( '12 Columns', 'premium-addons-for-elementor' ),
				],
                'condition'        => [
                    'premium_gallery_img_size_select!'  => 'metro'
                ],
				'selectors'         => [
					'{{WRAPPER}} .premium-img-gallery-masonry div.premium-gallery-item, {{WRAPPER}} .premium-img-gallery-fitRows div.premium-gallery-item' => 'width: {{VALUE}};',
				],
				'render_type'       => 'template'
			]
		);
        
        $this->add_control( 'premium_gallery_load_more', 
            [
                'label'             => __( 'Load More Button', 'premium-addons-for-elementor' ),
                'description'       => __('Requires number of images larger than 6', 'premium-addons-for-elementor'),
                'type'              => Controls_Manager::SWITCHER
            ]
        );
        
        $this->add_control( 'premium_gallery_load_more_text', 
            [
                'label'             => __( 'Button Text', 'premium-addons-for-elementor' ),
                'type'              => Controls_Manager::TEXT,
                'default'           => __('Load More', 'premium-addons-for-elementor'),
                'dynamic'           => [ 'active' => true ],
                'condition'         => [
                    'premium_gallery_load_more'    => 'yes'
                ]
            ]
        );
        
        $this->add_control( 'premium_gallery_load_minimum',
            [
                'label'             => __('Minimum Number of Images', 'premium-addons-for-elementor'),
                'type'              => Controls_Manager::NUMBER,
                'description'       => __('Set the minimum number of images before showing load more button', 'premium-addons-for-elementor'),
                'default'           => 6,
                'condition'         => [
                    'premium_gallery_load_more'    => 'yes'
                ]
            ]
        );
        
        $this->add_control( 'premium_gallery_load_click_number',
            [
                'label'             => __('Images to Show', 'premium-addons-for-elementor'),
                'type'              => Controls_Manager::NUMBER,
                'description'       => __('Set the minimum number of images to show with each click', 'premium-addons-for-elementor'),
                'default'           => 6,
                'condition' => [
                    'premium_gallery_load_more'    => 'yes'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_gallery_load_more_align',
            [
                'label'             => __( 'Button Alignment', 'premium-addons-for-elementor' ),
                'type'              => Controls_Manager::CHOOSE,
                'options'           => [
                    'left'      => [
                        'title'=> __( 'Left', 'premium-addons-for-elementor' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center'    => [
                        'title'=> __( 'Center', 'premium-addons-for-elementor' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right'     => [
                        'title'=> __( 'Right', 'premium-addons-for-elementor' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default'           => 'center',
                'selectors'         => [
                    '{{WRAPPER}} .premium-gallery-load-more' => 'text-align: {{VALUE}};',
                ],
                'condition'         => [
                    'premium_gallery_load_more'    => 'yes'
                ]
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_gallery_cats',
            [
                'label'             => __('Categories','premium-addons-for-elementor'),
            ]
        );
        
        $this->add_control('premium_gallery_filter',
            [
                'label'             => __( 'Filter Tabs', 'premium-addons-for-elementor' ),
                'type'              => Controls_Manager::SWITCHER,
                'default'           => 'yes'
            ]
        );
        
        $condition = array( 'premium_gallery_filter' => 'yes' );
        
        $this->add_control( 'premium_gallery_first_cat_switcher', 
            [
                'label'             => __( 'First Category', 'premium-addons-for-elementor' ),
                'type'              => Controls_Manager::SWITCHER,
                'default'           => 'yes',
                'condition'         => $condition
            ]
        );
        
        $this->add_control( 'premium_gallery_first_cat_label', 
            [
                'label'             => __( 'First Category Label', 'premium-addons-for-elementor' ),
                'type'              => Controls_Manager::TEXT,
                'default'           => __('All', 'premium-addons-for-elementor'),
                'dynamic'           => [ 'active' => true ],
                'condition' => array_merge( [
                    'premium_gallery_first_cat_switcher'    => 'yes'
                ], $condition )
            ]
        );
        
        $repeater = new REPEATER();
        
        $repeater->add_control( 'premium_gallery_img_cat', 
            [
                'label'             => __( 'Category', 'premium-addons-for-elementor' ),
                'type'              => Controls_Manager::TEXT,
                'dynamic'           => [ 'active' => true ],
            ]
        );
        
        $repeater->add_control( 'premium_gallery_img_cat_rotation',
            [
                'label'             => __('Rotation Degrees', 'premium-addons-for-elementor'),
                'type'              => Controls_Manager::NUMBER,
                'description'       => __('Set rotation value in degrees', 'premium-addons-for-elementor'),
                'min'               => -180,
                'max'               => 180,
                'selectors'         => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'transform: rotate({{VALUE}}deg);'
                ],
            ]
        );
        
        $this->add_control('premium_gallery_cats_content',
            [
                'label'              => __( 'Categories', 'premium-addons-for-elementor' ),
                'type'               => Controls_Manager::REPEATER,
                'default'            => [
                    [
                        'premium_gallery_img_cat'   => 'Category 1',
                    ],
                    [
                        'premium_gallery_img_cat'   => 'Category 2',
                    ],
                ],
                'fields'             => $repeater->get_controls(),
                'title_field'        => '{{{ premium_gallery_img_cat }}}',
                'condition'          => $condition
            ]
        );
        
        $this->add_control('premium_gallery_active_cat',
            [
                'label'             => __('Active Category Index', 'premium-addons-for-elementor'),
                'type'              => Controls_Manager::NUMBER,
                'default'           => 0,
                'min'               => 0,
                'condition'         => $condition
                
            ]
        );
            
        $this->add_control('active_cat_notice',
			[
				'raw'             => __( 'Please note categories are zero indexed, so if you need the first category to be active, you need to set the value to 0', 'premium-addons-for-elementor' ),
                'type'            => Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);
        
        $this->add_control('premium_gallery_shuffle',
            [
                'label'         => __( 'Shuffle Images on Filter Click', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::SWITCHER,
                'condition'     => array_merge( [
                    'premium_gallery_filter'    => 'yes'
                ], $condition )
            ]
        );
    
        $this->add_responsive_control('premium_gallery_filters_align',
            [
                'label'             => __( 'Alignment', 'premium-addons-for-elementor' ),
                'type'              => Controls_Manager::CHOOSE,
                'options'           => [
                    'flex-start'      => [
                        'title'=> __( 'Left', 'premium-addons-for-elementor' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center'    => [
                        'title'=> __( 'Center', 'premium-addons-for-elementor' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'flex-end'     => [
                        'title'=> __( 'Right', 'premium-addons-for-elementor' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default'           => 'center',
                'selectors'         => [
                    '{{WRAPPER}} .premium-img-gallery-filter' => 'justify-content: {{VALUE}}'
                ],
                'condition'         => $condition
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_gallery_content',
            [
                'label'     => __('Images/Videos','premium-addons-for-elementor'),
            ]);
        
        $img_repeater = new REPEATER();
        
        $img_repeater->add_control('premium_gallery_img', 
            [
                'label' => __( 'Upload Image', 'premium-addons-for-elementor' ),
                'type' => Controls_Manager::MEDIA,
                'dynamic'       => [ 'active' => true ],
                'default'       => [
                    'url'	=> Utils::get_placeholder_image_src(),
                ],
            ]);
        
        $img_repeater->add_responsive_control('premium_gallery_image_cell',
			[
  				'label'                 => __( 'Width', 'premium-addons-for-elementor' ),
                'description'           => __('Works only when layout set to Metro', 'premium-addons-for-elementor'),
				'label_block'           => true,
                'default'               => [
                    'unit'  => 'px',
                    'size'  => 4
                ],
				'type'                  => Controls_Manager::SLIDER,
                'range'         => [
                    'px'    => [
                        'min'   => 1, 
                        'max'   => 12,
                    ],
                ],
				'render_type' => 'template'
			]
		);
        
        $img_repeater->add_responsive_control('premium_gallery_image_vcell',
			[
  				'label'                 => __( 'Height', 'premium-addons-for-elementor' ),
                'description'           => __('Works only when layout set to \'Metro\'', 'premium-addons-for-elementor'),
				'label_block'           => true,
				'type'                  => Controls_Manager::SLIDER,
                'default'               => [
                    'unit'  => 'px',
                    'size'  => 4
                ],
                'range'         => [
                    'px'    => [
                        'min'   => 1, 
                        'max'   => 12,
                    ],
                ],
				'render_type' => 'template'
			]
		);
        
        $img_repeater->add_control('premium_gallery_video',
            [
                'label'         => __( 'Video', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'true'
            ]
        );
        
        $img_repeater->add_control('premium_gallery_video_type',
            [
                'label'         => __( 'Type', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'youtube'       => __('YouTube', 'premium-addons-for-elementor'),
                    'vimeo'         => __('Vimeo', 'premium-addons-for-elementor'),
                    'hosted'        => __('Self Hosted', 'premium-addons-for-elementor'),
                ],
                'label_block'   => true,
                'default'       => 'youtube',
                'condition'     => [
                    'premium_gallery_video' => 'true',
                ]
            ]
        );
        
        $img_repeater->add_control('premium_gallery_video_url',
            [
                'label'         => __( 'Video URL', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::TEXT,
                'label_block'   => true,
                'dynamic'       => [ 
                    'active' => true,
                    'categories' => [
                        TagsModule::POST_META_CATEGORY,
                        TagsModule::URL_CATEGORY
                    ]
                ],
                'condition'     => [
                    'premium_gallery_video'         => 'true',
                    'premium_gallery_video_type!'   => 'hosted'
                ]
            ]
        );
        
        $img_repeater->add_control('premium_gallery_video_self',
            [
                'label'         => __('Select Video', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::MEDIA,
                'dynamic'       => [
                    'active' => true,
                    'categories' => [
                        TagsModule::POST_META_CATEGORY,
                        TagsModule::URL_CATEGORY,
                    ],
                ],
                'media_type' => 'video',
                'condition'     => [
                    'premium_gallery_video'     => 'true',
                    'premium_gallery_video_type'=> 'hosted'
                ]
            ]
        );
        
        $img_repeater->add_control('premium_gallery_video_self_url',
            [
                'label'         => __('Remote Video URL', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::TEXT,
                'dynamic'       => [
                    'active' => true,
                ],
                'label_block'   => true,
                'condition'     => [
                    'premium_gallery_video'     => 'true',
                    'premium_gallery_video_type'=> 'hosted'
                ]
            ]
        );
        
        $img_repeater->add_control('premium_gallery_video_controls',
            [
                'label'         => __( 'Controls', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'true',
                'default'       => 'true',
                'condition'     => [
                    'premium_gallery_video'     => 'true'
                ]
            ]
        );
        
        $img_repeater->add_control('premium_gallery_video_mute',
            [
                'label'         => __( 'Mute', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'true',
                'condition'     => [
                    'premium_gallery_video'     => 'true'
                ]
            ]
        );
        
        $img_repeater->add_control('premium_gallery_img_name', 
            [
                'label' => __( 'Title', 'premium-addons-for-elementor' ),
                'type' => Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
                'label_block'   => true,
            ]);
        
        $img_repeater->add_control('premium_gallery_img_desc', 
            [
                'label' => __( 'Description', 'premium-addons-for-elementor' ),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic'       => [ 'active' => true ],
                'label_block' => true,
            ]);
        
        $img_repeater->add_control('premium_gallery_img_category', 
            [
                'label' => __( 'Category', 'premium-addons-for-elementor' ),
                'type' => Controls_Manager::TEXT,
                'description'=> __('To assign for multiple categories, separate by a comma \',\'','premium-addons-for-elementor'),
                'dynamic'       => [ 'active' => true ],
            ]);
        
        $img_repeater->add_control('premium_gallery_img_link_type', 
            [
                'label'         => __('Link Type', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'url'   => __('URL', 'premium-addons-for-elementor'),
                    'link'  => __('Existing Page', 'premium-addons-for-elementor'),
                ],
                'default'       => 'url',
                'label_block'   => true,
                'condition'     => [
                    'premium_gallery_video!'     => 'true',
                ]
            ]);
        
        $img_repeater->add_control('premium_gallery_img_link', 
            [
                'label'         => __('Link', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::URL,
                'dynamic'       => [ 'active' => true ],
                'placeholder'   => 'https://premiumaddons.com/',
                'label_block'   => true,
                'condition'     => [
                    'premium_gallery_img_link_type' => 'url',
                    'premium_gallery_video!'        => 'true',
                ]
            ]);
        
        $img_repeater->add_control('premium_gallery_img_existing', 
            [
                'label'         => __('Existing Page', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT2,
                'options'       => $this->getTemplateInstance()->get_all_post(),
                'condition'     => [
                    'premium_gallery_img_link_type'=> 'link',
                ],
                'multiple'      => false,
                'separator'     => 'after',
                'label_block'   => true,
                'condition'     => [
                    'premium_gallery_img_link_type' => 'link',
                    'premium_gallery_video!'        => 'true',
                ]
            ]);
        
        $img_repeater->add_control('premium_gallery_link_whole',
            [
                'label'         => __( 'Whole Image Link', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::SWITCHER,
                'condition'     => [
                    'premium_gallery_video!'         => 'true',
                ]
            ]
        );
        
        $img_repeater->add_control('premium_gallery_lightbox_whole',
            [
                'label'         => __( 'Whole Image Lightbox', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::SWITCHER,
                'condition'     => [
                    'premium_gallery_video!'         => 'true',
                ]
            ]
        );
        
        $this->add_control('premium_gallery_img_content',
           [
               'label' => __( 'Images', 'premium-addons-for-elementor' ),
               'type' => Controls_Manager::REPEATER,
               'default' => [
                   [
                       'premium_gallery_img_name'   => 'Image #1',
                       'premium_gallery_img_category'   => 'Category 1'
                   ],
                   [
                       'premium_gallery_img_name'   => 'Image #2',
                       'premium_gallery_img_category' => 'Category 2'
                   ],
               ],
               'fields' => $img_repeater->get_controls(),
               'title_field'   => '{{{ "" !== premium_gallery_img_name ? premium_gallery_img_name : "Image" }}}' . ' - {{{ "" !== premium_gallery_img_category ? premium_gallery_img_category : "No Categories" }}}',
           ]
       );
        
        $this->add_control('premium_gallery_shuffle_onload',
            [
                'label'         => __( 'Shuffle Images on Page Load', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::SWITCHER,
            ]
        );
        
        $this->add_control('premium_gallery_yt_thumbnail_size',
            [
                'label'     => __( 'Youtube Videos Thumbnail Size', 'premium-addons-for-elementor' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'maxresdefault' => __( 'Maximum Resolution', 'premium-addons-for-elementor' ),
                    'hqdefault'     => __( 'High Quality', 'premium-addons-for-elementor' ),
                    'mqdefault'     => __( 'Medium Quality', 'premium-addons-for-elementor' ),
                    'sddefault'     => __( 'Standard Quality', 'premium-addons-for-elementor' ),
                ],
                'default'   => 'maxresdefault',
                'label_block'=> true
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_gallery_grid_settings',
            [
                'label'     => __('Display Options','premium-addons-for-elementor'),
                
            ]);
        
        $this->add_responsive_control('premium_gallery_gap',
            [
                'label'         => __('Image Gap', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%', "em"],
                'range'         => [
                    'px'    => [
                        'min'   => 0, 
                        'max'   => 200,
                    ],
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-gallery-item' => 'padding: {{SIZE}}{{UNIT}}'
                ]
            ]
        );
        
        $this->add_control('premium_gallery_img_style',
            [
                'label'         => __('Skin', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'description'   => __('Choose a layout style for the gallery','premium-addons-for-elementor'),
                'options'       => [
                    'default'           => __('Style 1', 'premium-addons-for-elementor'),
                    'style1'            => __('Style 2', 'premium-addons-for-elementor'),
                    'style2'            => __('Style 3', 'premium-addons-for-elementor'),
                    'style3'            => __('Style 4', 'premium-addons-for-elementor'),
                ],
                'default'       => 'default',
                'separator'     => 'before',
                'label_block'   => true
            ]
        );
        
        $this->add_control('premium_grid_style_notice', 
            [
                'raw'               => __('Style 4 works only with Even / Masonry Layout', 'premium-addons-for-elementor'),
                'type'              => Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'condition'         => [
                    'premium_gallery_img_style'         => 'style3',
                    'premium_gallery_img_size_select'   => 'metro'
                ]
            ] 
        );
        
        $this->add_responsive_control('premium_gallery_style1_border_border',
            [
                'label'         => __('Height', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'range'         => [
                    'px'    => [
                        'min'   => 0,
                        'max'   => 700,
                    ]
                ],
                'label_block'   => true,
                'selectors'     => [
                    '{{WRAPPER}} .pa-gallery-img.style1 .premium-gallery-caption' => 'bottom: {{SIZE}}px;',
                ],
                'condition'     => [
                    'premium_gallery_img_style' => 'style1'
                ]
            ]
        );
        
        $this->add_control('premium_gallery_img_effect',
            [
                'label'         => __('Hover Effect', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'description'   => __('Choose a hover effect for the image','premium-addons-for-elementor'),
                'options'       => [
                    'none'          => __('None', 'premium-addons-for-elementor'),
                    'zoomin'        => __('Zoom In', 'premium-addons-for-elementor'),
                    'zoomout'       => __('Zoom Out', 'premium-addons-for-elementor'),
                    'scale'         => __('Scale', 'premium-addons-for-elementor'),
                    'gray'          => __('Grayscale', 'premium-addons-for-elementor'),
                    'blur'          => __('Blur', 'premium-addons-for-elementor'),
                    'bright'        => __('Bright', 'premium-addons-for-elementor'),
                    'sepia'         => __('Sepia', 'premium-addons-for-elementor'),
                    'trans'         => __('Translate', 'premium-addons-for-elementor'),
                ],
                'default'       => 'zoomin',
                'label_block'   => true,
                'separator'     => 'after'
            ]
        );
        
        $this->add_control('premium_gallery_links_icon',
		  	[
		     	'label'         => __( 'Links Icon', 'premium-addons-for-elementor' ),
		     	'type'          => Controls_Manager::ICONS,
                'default'       => [
                    'library'       => 'fa-solid',
                    'value'         => 'fas fa-link',
                ]
		  	]
		);

        $this->add_control('premium_gallery_videos_heading',
    		[
				'label'			=> __( 'Videos', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::HEADING,
                'separator'     => 'before'
			]
		);
        
        $this->add_control('premium_gallery_video_icon',
            [
                'label'         => __( 'Always Show Play Icon', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'true',
                'default'       => 'true',
                'condition'     => [
                    'premium_gallery_img_style!' => 'style2'
                ],
                
            ]
        );
        
        $this->add_control('premium_gallery_videos_icon',
		  	[
		     	'label'         => __( 'Videos Play Icon', 'premium-addons-for-elementor' ),
		     	'type'          => Controls_Manager::ICONS,
                'default'       => [
                    'library'       => 'fa-solid',
                    'value'         => 'fas fa-play',
                ]
		  	]
		);
        
        $this->add_control('premium_gallery_rtl_mode',
            [
                'label'         => __( 'RTL Mode', 'premium-addons-for-elementor' ),
                'description'   => __('This option moves the origin of the grid to the right side. Useful for RTL direction sites', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'separator'     => 'before'
            ]
        );
        
        $this->add_responsive_control('premium_gallery_content_align',
                [
                    'label'         => __( 'Content Alignment', 'premium-addons-for-elementor' ),
                    'type'          => Controls_Manager::CHOOSE,
                    'options'       => [
                        'left'      => [
                            'title'=> __( 'Left', 'premium-addons-for-elementor' ),
                            'icon' => 'fa fa-align-left',
                            ],
                        'center'    => [
                            'title'=> __( 'Center', 'premium-addons-for-elementor' ),
                            'icon' => 'fa fa-align-center',
                            ],
                        'right'     => [
                            'title'=> __( 'Right', 'premium-addons-for-elementor' ),
                            'icon' => 'fa fa-align-right',
                            ],
                        ],
                    'default'       => 'center',
                    'separator'     => 'before',
                    'selectors'     => [
                        '{{WRAPPER}} .premium-gallery-caption' => 'text-align: {{VALUE}};',
                        ],
                    ]
                );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_gallery_lightbox_section',
            [
                'label'         => __('Lightbox', 'premium-addons-for-elementor'),
            ]
        );
        
        $this->add_control('premium_gallery_light_box',
            [
                'label'         => __( 'Lightbox', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::SWITCHER,
                'default'       => 'yes',
                'separator'     => 'before'
            ]
        );
        
        $this->add_control('premium_gallery_lightbox_type',
			[
				'label'             => __( 'Lightbox Style', 'premium-addons-for-elementor' ),
				'type'              => Controls_Manager::SELECT,
				'default'           => 'default',
				'options'           => [
					'default'   => __( 'PrettyPhoto', 'premium-addons-for-elementor' ),
					'yes'       => __( 'Elementor', 'premium-addons-for-elementor' ),
					'no'        => __( 'Other Lightbox Plugin', 'premium-addons-for-elementor' ),
				],
				'condition'         => [
					'premium_gallery_light_box' => 'yes',
				],
			]
        );
        
        $this->add_control('lightbox_show_title',
            [
                'label'         => __( 'Show Image Title', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::SWITCHER,
                'default'       => 'yes',
                'condition'     => [
                    'premium_gallery_light_box'     => 'yes',
                    'premium_gallery_lightbox_type' => 'yes'
                ]
            ]
        );
        
        $this->add_control('premium_gallery_lightbox_doc',
			[
				'raw'             => __( 'Please note Elementor lightbox style is always applied on videos.', 'premium-addons-for-elementor' ),
                'type'            => Controls_Manager::RAW_HTML,
                'content_classes' => 'editor-pa-doc',
			]
		);
        
        $this->add_control('premium_gallery_lightbox_theme',
            [
                'label'             => __('Lightbox Theme', 'premium-addons-for-elementor'),
                'type'              => Controls_Manager::SELECT,
                'options'           => [
                    'pp_default'        => __('Default', 'premium-addons-for-elementor'),
                    'light_rounded'     => __('Light Rounded', 'premium-addons-for-elementor'),
                    'dark_rounded'      => __('Dark Rounded', 'premium-addons-for-elementor'),
                    'light_square'      => __('Light Square', 'premium-addons-for-elementor'),
                    'dark_square'       => __('Dark Square', 'premium-addons-for-elementor'),
                    'facebook'          => __('Facebook', 'premium-addons-for-elementor'),
                ],
                'default'           => 'pp_default',
                'condition'     => [
                    'premium_gallery_light_box'     => 'yes',
                    'premium_gallery_lightbox_type' => 'default'
                ]
            ]
        );
        
        $this->add_control('premium_gallery_overlay_gallery',
            [
                'label'         => __( 'Overlay Gallery Images', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::SWITCHER,
                'condition'     => [
                    'premium_gallery_light_box'     => 'yes',
                    'premium_gallery_lightbox_type' => 'default'
                ]
            ]
        );
        
        $this->add_control('premium_gallery_lightbox_icon',
		  	[
		     	'label'         => __( 'Lightbox Icon', 'premium-addons-for-elementor' ),
		     	'type'          => Controls_Manager::ICONS,
                'default'       => [
                    'library'       => 'fa-solid',
                    'value'         => 'fas fa-search',
                ],
                'condition'     => [
                    'premium_gallery_light_box'     => 'yes'
                ]
		  	]
		);
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_gallery_responsive_section',
            [
                'label'         => __('Responsive', 'premium-addons-for-elementor'),
            ]);
        
        $this->add_control('premium_gallery_responsive_switcher',
            [
                'label'         => __('Responsive Controls', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'description'   => __('If the content text is not suiting well on specific screen sizes, you may enable this option which will hide the description text.', 'premium-addons-for-elementor')
            ]);
        
        $this->add_control('premium_gallery_min_range', 
            [
                'label'     => __('Minimum Size', 'premium-addons-for-elementor'),
                'type'      => Controls_Manager::NUMBER,
                'description'=> __('Note: minimum size for extra small screens is 1px.','premium-addons-for-elementor'),
                'default'   => 1,
                'condition' => [
                    'premium_gallery_responsive_switcher'    => 'yes'
                ],
            ]);

        $this->add_control('premium_gallery_max_range', 
            [
                'label'     => __('Maximum Size', 'premium-addons-for-elementor'),
                'type'      => Controls_Manager::NUMBER,
                'description'=> __('Note: maximum size for extra small screens is 767px.','premium-addons-for-elementor'),
                'default'   => 767,
                'condition' => [
                    'premium_gallery_responsive_switcher'    => 'yes'
                ],
            ]);

		$this->end_controls_section();
        
        $this->start_controls_section('section_pa_docs',
            [
                'label'         => __('Helpful Documentations', 'premium-addons-for-elementor'),
            ]
        );
        
        $this->add_control('doc_1',
            [
                'raw'             => sprintf( __( '%1$s Getting started » %2$s', 'premium-addons-for-elementor' ), '<a href="https://premiumaddons.com/docs/grid-widget-tutorial/?utm_source=pa-dashboard&utm_medium=pa-editor&utm_campaign=pa-plugin" target="_blank" rel="noopener">', '</a>' ),
                'type'            => Controls_Manager::RAW_HTML,
                'content_classes' => 'editor-pa-doc',
            ]
        );

        $this->add_control('doc_2',
            [
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => sprintf( __( '%1$s How to assign a grid item to multiple categories » %2$s', 'premium-addons-for-elementor' ), '<a href="https://premiumaddons.com/docs/how-to-assign-an-image-to-multiple-categories/?utm_source=pa-dashboard&utm_medium=pa-editor&utm_campaign=pa-plugin" target="_blank" rel="noopener">', '</a>' ),
                'content_classes' => 'editor-pa-doc',
            ]
        );

        $this->add_control('doc_3',
            [
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => sprintf( __( '%1$s How to open an Elementor popup/lightbox using a grid item » %2$s', 'premium-addons-for-elementor' ), '<a href="https://premiumaddons.com/docs/how-to-open-a-popup-lightbox-through-a-grid-image/?utm_source=pa-dashboard&utm_medium=pa-editor&utm_campaign=pa-plugin" target="_blank" rel="noopener">', '</a>' ),
                'content_classes' => 'editor-pa-doc',
            ]
        );

        $this->end_controls_section();
        
        $this->start_controls_section('premium_gallery_general_style',
            [
                'label'     => __('General','premium-addons-for-elementor'),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]);
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
                [
                    'name'              => 'premium_gallery_general_background',
                    'types'             => [ 'classic', 'gradient' ],
                    'selector'          => '{{WRAPPER}} .premium-img-gallery',
                ]
                );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
                [
                    'name'              => 'premium_gallery_general_border',
                    'selector'          => '{{WRAPPER}} .premium-img-gallery',
                    ]
                );
        
        $this->add_control('premium_gallery_general_border_radius',
                [
                    'label'         => __('Border Radius', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%', 'em'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-img-gallery' => 'border-radius: {{SIZE}}{{UNIT}};'
                        ]
                    ]
                );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'              => 'premium_gallery_general_box_shadow',
                'selector'          => '{{WRAPPER}} .premium-img-gallery',
            ]
            );
        
        $this->add_responsive_control('premium_gallery_general_margin',
                [
                    'label'         => __('Margin', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => [ 'px', 'em', '%' ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-img-gallery' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        ]
                    ]
                );
        
        $this->add_responsive_control('premium_gallery_general_padding',
                [
                    'label'         => __('Padding', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => [ 'px', 'em', '%' ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-img-gallery' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        ]
                    ]
                );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_gallery_img_style_section',
            [
                'label'     => __('Image','premium-addons-for-elementor'),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]);
        
        $this->add_control('premium_gallery_icons_style_overlay',
            [
                'label'         => __('Hover Overlay Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .pa-gallery-img:not(.style2):hover .pa-gallery-icons-wrapper, {{WRAPPER}} .pa-gallery-img .pa-gallery-icons-caption-container, {{WRAPPER}} .pa-gallery-img:hover .pa-gallery-icons-caption-container' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'              => 'premium_gallery_img_border',
                'selector'          => '{{WRAPPER}} .pa-gallery-img-container',
            ]
        );
        
        $this->add_control('premium_gallery_img_border_radius',
                [
                    'label'         => __('Border Radius', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%', 'em'],
                    'selectors'     => [
                        '{{WRAPPER}} .pa-gallery-img-container' => 'border-radius: {{SIZE}}{{UNIT}};'
                        ]
                    ]
                );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'label'             => __('Shadow','premium-addons-for-elementor'),
                'name'              => 'premium_gallery_img_box_shadow',
                'selector'          => '{{WRAPPER}} .pa-gallery-img-container',
                'condition'         => [
                    'premium_gallery_img_style!' => 'style1'
                ]
            ]
            );
        
        $this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'css_filters',
				'selector' => '{{WRAPPER}} .pa-gallery-img-container img',
			]
		);
        
        $this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
                'label' => __('Hover CSS Filters', 'premium-addons-for-elementor'),
				'name' => 'hover_css_filters',
				'selector' => '{{WRAPPER}} .premium-gallery-item:hover img',
			]
		);
        
        $this->add_responsive_control('premium_gallery_img_margin',
                [
                    'label'         => __('Margin', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => [ 'px', 'em', '%' ],
                    'selectors'     => [
                        '{{WRAPPER}} .pa-gallery-img-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        ]
                    ]
                );
        
        $this->add_responsive_control('premium_gallery_img_padding',
                [
                    'label'         => __('Padding', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => [ 'px', 'em', '%' ],
                    'selectors'     => [
                        '{{WRAPPER}} .pa-gallery-img-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        ]
                    ]
                );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_gallery_content_style',
            [
                'label'     => __('Title / Description','premium-addons-for-elementor'),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]);
        
        $this->add_control('premium_gallery_title_heading',
                [
                    'label'         => __('Title', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::HEADING,
                ]
                );
        
        $this->add_control('premium_gallery_title_color',
                [
                    'label'         => __('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_2,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-gallery-img-name, {{WRAPPER}} .premium-gallery-img-name a' => 'color: {{VALUE}};',
                    ]
                ]
                );
        
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'          => 'premium_gallery_title_typo',
                    'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                    'selector'      => '{{WRAPPER}} .premium-gallery-img-name, {{WRAPPER}} .premium-gallery-img-name a',
                    ]
                );
        
        $this->add_control('premium_gallery_description_heading',
                [
                    'label'         => __('Description', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::HEADING,
                    'separator'     => 'before',
                ]
                );
        
        $this->add_control('premium_gallery_description_color',
                [
                    'label'         => __('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_3,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-gallery-img-desc, {{WRAPPER}} .premium-gallery-img-desc a' => 'color: {{VALUE}};',
                    ]
                ]
                );
        
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'          => 'premium_gallery_description_typo',
                    'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                    'selector'      => '{{WRAPPER}} .premium-gallery-img-desc, {{WRAPPER}} .premium-gallery-img-desc a',
                    ]
                );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
                [
                    'name'              => 'premium_gallery_content_background',
                    'types'             => [ 'classic', 'gradient' ],
                    'selector'          => '{{WRAPPER}} .premium-gallery-caption',
                    'separator'         => 'before',
                ]
                );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
                [
                    'name'              => 'premium_gallery_content_border',
                    'selector'          => '{{WRAPPER}} .premium-gallery-caption',
                    ]
                );
        
        $this->add_control('premium_gallery_content_border_radius',
                [
                    'label'         => __('Border Radius', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%', 'em'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-gallery-caption' => 'border-radius: {{SIZE}}{{UNIT}};'
                        ]
                    ]
                );
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'label'             => __('Shadow','premium-addons-for-elementor'),
                'name'              => 'premium_gallery_content_shadow',
                'selector'          => '{{WRAPPER}} .premium-gallery-caption',
            ]
            );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'              => 'premium_gallery_content_box_shadow',
                'selector'          => '{{WRAPPER}} .premium-gallery-caption',
            ]
            );
        
        $this->add_responsive_control('premium_gallery_content_margin',
                [
                    'label'         => __('Margin', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => [ 'px', 'em', '%' ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-gallery-caption' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        ]
                    ]
                );
        
        $this->add_responsive_control('premium_gallery_content_padding',
                [
                    'label'         => __('Padding', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => [ 'px', 'em', '%' ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-gallery-caption' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        ]
                    ]
                );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_gallery_icons_style',
            [
                'label'     => __('Icons','premium-addons-for-elementor'),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]);
        
        $this->add_responsive_control('premium_gallery_style1_icons_position',
            [
                'label'         => __('Position', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%', 'em'],
                'range'         => [
                    'px'    => [
                        'min'   => 0,
                        'max'   => 300,
                    ]
                ],
                'label_block'   => true,
                'selectors'     => [
                    '{{WRAPPER}} .pa-gallery-img.style1 .pa-gallery-icons-inner-container,{{WRAPPER}} .pa-gallery-img.default .pa-gallery-icons-inner-container' => 'top: {{SIZE}}{{UNIT}};',
                ],
                'condition'     => [
                    'premium_gallery_img_style!' => 'style2'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_gallery_icons_size',
            [
                'label'         => __('Size', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', 'em'],
                'range'         => [
                    'px'    => [
                        'min'   => 1,
                        'max'   => 50,
                    ]
                ],
                'label_block'   => true,
                'selectors'     => [
                    '{{WRAPPER}} .pa-gallery-icons-inner-container i, {{WRAPPER}} .pa-gallery-icons-caption-cell i' => 'font-size: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .pa-gallery-icons-inner-container svg, {{WRAPPER}} .pa-gallery-icons-caption-cell svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}'
                ],
            ]
        );
        
        $this->start_controls_tabs('premium_gallery_icons_style_tabs');
        
        $this->start_controls_tab('premium_gallery_icons_style_normal',
                [
                    'label'         => __('Normal', 'premium-addons-for-elementor'),
                ]
                );
        
        $this->add_control('premium_gallery_icons_style_color',
                [
                    'label'         => __('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .pa-gallery-magnific-image i, {{WRAPPER}} .pa-gallery-img-link i' => 'color: {{VALUE}};',
                    ]
                ]
                );
        
        $this->add_control('premium_gallery_icons_style_background',
                [
                    'label'         => __('Background Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_2,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .pa-gallery-magnific-image span, {{WRAPPER}} .pa-gallery-img-link span' => 'background-color: {{VALUE}};',
                    ]
                ]
                );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
                [
                    'name'          => 'premium_gallery_icons_style_border',
                    'selector'      => '{{WRAPPER}} .pa-gallery-magnific-image span, {{WRAPPER}} .pa-gallery-img-link span',
                ]
                );
        
        $this->add_control('premium_gallery_icons_style_border_radius',
                [
                    'label'         => __('Border Radius', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', 'em' , '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .pa-gallery-magnific-image span, {{WRAPPER}} .pa-gallery-img-link span' => 'border-radius: {{SIZE}}{{UNIT}};'
                    ]
                ]
                );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
                [
                    'label'         => __('Shadow','premium-addons-for-elementor'),
                    'name'          => 'premium_gallery_icons_style_shadow',
                    'selector'      => '{{WRAPPER}} .pa-gallery-magnific-image span, {{WRAPPER}} .pa-gallery-img-link span',
                ]
                );
        
        $this->add_responsive_control('premium_gallery_icons_style_margin',
                [
                    'label'         => __('Margin', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .pa-gallery-magnific-image span, {{WRAPPER}} .pa-gallery-img-link span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]);
        
        $this->add_responsive_control('premium_gallery_icons_style_padding',
                [
                    'label'         => __('Padding', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .pa-gallery-magnific-image span, {{WRAPPER}} .pa-gallery-img-link span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]);
        
        $this->end_controls_tab();

        $this->start_controls_tab('premium_gallery_icons_style_hover',
        [
            'label'         => __('Hover', 'premium-addons-for-elementor'),
        ]
        );
        
        $this->add_control('premium_gallery_icons_style_color_hover',
                [
                    'label'         => __('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .pa-gallery-magnific-image:hover i, {{WRAPPER}} .pa-gallery-img-link:hover i' => 'color: {{VALUE}};',
                    ]
                ]
                );
        
        $this->add_control('premium_gallery_icons_style_background_hover',
                [
                    'label'         => __('Background Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_2,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .pa-gallery-magnific-image:hover span, {{WRAPPER}} .pa-gallery-img-link:hover span' => 'background-color: {{VALUE}};',
                    ]
                ]
                );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
                [
                    'name'          => 'premium_gallery_icons_style_border_hover',
                    'selector'      => '{{WRAPPER}} .pa-gallery-magnific-image:hover span, {{WRAPPER}} .pa-gallery-img-link:hover span',
                ]
                );
        
        $this->add_control('premium_gallery_icons_style_border_radius_hover',
                [
                    'label'         => __('Border Radius', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', 'em' , '%' ],                    
                    'selectors'     => [
                        '{{WRAPPER}} .pa-gallery-magnific-image:hover span, {{WRAPPER}} .pa-gallery-img-link:hover span' => 'border-radius: {{SIZE}}{{UNIT}};'
                    ]
                ]
                );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
                [
                    'label'         => __('Shadow','premium-addons-for-elementor'),
                    'name'          => 'premium_gallery_icons_style_shadow_hover',
                    'selector'      => '{{WRAPPER}} {{WRAPPER}} .pa-gallery-magnific-image:hover span, {{WRAPPER}} .pa-gallery-img-link:hover span',
                ]
                );
        
        $this->add_responsive_control('premium_gallery_icons_style_margin_hover',
                [
                    'label'         => __('Margin', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .pa-gallery-magnific-image:hover span, {{WRAPPER}} .pa-gallery-img-link:hover span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]);
        
        $this->add_responsive_control('premium_gallery_icons_style_padding_hover',
                [
                    'label'         => __('Padding', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .pa-gallery-magnific-image:hover span, {{WRAPPER}} .pa-gallery-img-link:hover span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]);
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_gallery_filter_style',
            [
                'label'     => __('Filter','premium-addons-for-elementor'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'premium_gallery_filter'    => 'yes'
                ]
            ]);
        
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'          => 'premium_gallery_filter_typo',
                    'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                    'selector'      => '{{WRAPPER}} .premium-gallery-cats-container li a.category',
                    ]
                );
        
        $this->start_controls_tabs('premium_gallery_filters');

        $this->start_controls_tab('premium_gallery_filters_normal',
            [
                'label'         => __('Normal', 'premium-addons-for-elementor'),
            ]
        );
        
        $this->add_control('premium_gallery_filter_color',
            [
                'label'         => __('Text Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-gallery-cats-container li a.category span' => 'color: {{VALUE}};',
                ]
            ]
        );
        
        $this->add_control('premium_gallery_background_color',
           [
               'label'         => __('Background Color', 'premium-addons-for-elementor'),
               'type'          => Controls_Manager::COLOR,
               'selectors'     => [
                   '{{WRAPPER}} .premium-gallery-cats-container li a.category' => 'background-color: {{VALUE}};',
               ],
           ]
       );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
                [
                    'name'              => 'premium_gallery_filter_border',
                    'selector'          => '{{WRAPPER}} .premium-gallery-cats-container li a.category',
                ]
                );

        $this->add_control('premium_gallery_filter_border_radius',
                [
                    'label'             => __('Border Radius', 'premium-addons-for-elementor'),
                    'type'              => Controls_Manager::SLIDER,
                    'size_units'        => ['px','em','%'],
                    'selectors'         => [
                        '{{WRAPPER}} .premium-gallery-cats-container li a.category'  => 'border-radius: {{SIZE}}{{UNIT}};',
                        ]
                    ]
                );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab('premium_gallery_filters_hover',
            [
                'label'         => __('Hover', 'premium-addons-for-elementor'),
            ]
        );
        
        $this->add_control('premium_gallery_filter_hover_color',
            [
                'label'         => __('Text Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-gallery-cats-container li a:hover span' => 'color: {{VALUE}};',
                ]
            ]
        );
        
        $this->add_control('premium_gallery_background_hover_color',
           [
               'label'         => __('Background Color', 'premium-addons-for-elementor'),
               'type'          => Controls_Manager::COLOR,
               'selectors'     => [
                   '{{WRAPPER}} .premium-gallery-cats-container li a:hover' => 'background-color: {{VALUE}};',
               ],
           ]
       );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
                [
                    'name'              => 'premium_gallery_filter_border_hover',
                    'selector'          => '{{WRAPPER}} .premium-gallery-cats-container li a.category:hover',
                ]
                );

        $this->add_control('premium_gallery_filter_border_radius_hover',
                [
                    'label'             => __('Border Radius', 'premium-addons-for-elementor'),
                    'type'              => Controls_Manager::SLIDER,
                    'size_units'        => ['px','em','%'],
                    'selectors'         => [
                        '{{WRAPPER}} .premium-gallery-cats-container li a.category:hover'  => 'border-radius: {{SIZE}}{{UNIT}};',
                        ]
                    ]
                );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab('premium_gallery_filters_active',
            [
                'label'         => __('Active', 'premium-addons-for-elementor'),
            ]
        );
        
        $this->add_control('premium_gallery_filter_active_color',
            [
                'label'         => __('Text Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-gallery-cats-container li a.active span' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control('premium_gallery_background_active_color',
           [
               'label'         => __('Background Color', 'premium-addons-for-elementor'),
               'type'          => Controls_Manager::COLOR,
               'selectors'     => [
                   '{{WRAPPER}} .premium-gallery-cats-container li a.active' => 'background-color: {{VALUE}};',
               ],
           ]
       );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
                [
                    'name'              => 'premium_gallery_filter_border_active',
                    'selector'          => '{{WRAPPER}} .premium-gallery-cats-container li a.active',
                ]
                );

        $this->add_control('premium_gallery_filter_border_radius_active',
                [
                    'label'             => __('Border Radius', 'premium-addons-for-elementor'),
                    'type'              => Controls_Manager::SLIDER,
                    'size_units'        => ['px','em','%'],
                    'selectors'         => [
                        '{{WRAPPER}} .premium-gallery-cats-container li a.active'  => 'border-radius: {{SIZE}}{{UNIT}};',
                        ]
                    ]
                );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
                [
                    'name'          => 'premium_gallery_filter_shadow',
                    'selector'      => '{{WRAPPER}} .premium-gallery-cats-container li a.category',
                ]
                );
        
        $this->add_responsive_control('premium_gallery_filter_margin',
                [
                    'label'             => __('Margin', 'premium-addons-for-elementor'),
                    'type'              => Controls_Manager::DIMENSIONS,
                    'size_units'        => ['px', 'em', '%'],
                    'selectors'             => [
                        '{{WRAPPER}} .premium-gallery-cats-container li a.category' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control('premium_gallery_filter_padding',
                [
                    'label'             => __('Padding', 'premium-addons-for-elementor'),
                    'type'              => Controls_Manager::DIMENSIONS,
                    'size_units'        => ['px', 'em', '%'],
                'selectors'             => [
                    '{{WRAPPER}} .premium-gallery-cats-container li a.category' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_gallery_button_style_settings',
            [
                'label'         => __('Load More Button', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'premium_gallery_load_more'  => 'yes',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
                [
                    'name'          => 'premium_gallery_button_typo',
                    'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                    'selector'      => '{{WRAPPER}} .premium-gallery-load-more-btn',
            ]
        );

        $this->start_controls_tabs('premium_gallery_button_style_tabs');

        $this->start_controls_tab('premium_gallery_button_style_normal',
            [
                'label'         => __('Normal', 'premium-addons-for-elementor'),
            ]
        );

        $this->add_control('premium_gallery_button_color',
            [
                'label'         => __('Text Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-gallery-load-more-btn'  => 'color: {{VALUE}};',
                    '{{WRAPPER}} .premium-gallery-load-more-btn .premium-loader'  => 'border-color: {{VALUE}};',
                    ]
                ]
            );
        
        $this->add_control('premium_gallery_button_spin_color',
            [
                'label'         => __('Spinner Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-gallery-load-more-btn .premium-loader'  => 'border-top-color: {{VALUE}};'
                    ]
                ]
            );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'          => 'premium_gallery_button_text_shadow',
                'selector'      => '{{WRAPPER}} .premium-gallery-load-more-btn',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'              => 'premium_gallery_button_background',
                'types'             => [ 'classic' , 'gradient' ],
                'selector'          => '{{WRAPPER}} .premium-gallery-load-more-btn',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'          => 'premium_gallery_button_border',
                'selector'      => '{{WRAPPER}} .premium-gallery-load-more-btn',
            ]
        );

        $this->add_control('premium_gallery_button_border_radius',
            [
                'label'         => __('Border Radius', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', 'em' , '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-gallery-load-more-btn' => 'border-radius: {{SIZE}}{{UNIT}};'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'          => 'premium_gallery_button_box_shadow',
                'selector'      => '{{WRAPPER}} .premium-gallery-load-more-btn',
            ]
        );

        $this->add_responsive_control('premium_gallery_button_margin',
            [
                'label'         => __('Margin', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-gallery-load-more-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );

        $this->add_responsive_control('premium_gallery_button_padding',
            [
                'label'         => __('Padding', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-gallery-load-more-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('premium_gallery_button_style_hover',
            [
                'label'         => __('Hover', 'premium-addons-for-elementor'),
            ]
        );

        $this->add_control('premium_gallery_button_hover_color',
            [
                'label'         => __('Text Hover Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-gallery-load-more-btn:hover'  => 'color: {{VALUE}};'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'          => 'premium_gallery_button_text_shadow_hover',
                'selector'      => '{{WRAPPER}} .premium-gallery-load-more-btn:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'          => 'premium_gallery_button_background_hover',
                'types'         => [ 'classic' , 'gradient' ],
                'selector'      => '{{WRAPPER}} .premium-gallery-load-more-btn:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'          => 'premium_gallery_button_border_hover',
                'selector'      => '{{WRAPPER}} .premium-gallery-load-more-btn:hover',
            ]
        );

        $this->add_control('button_border_radius_hover',
            [
                'label'         => __('Border Radius', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', 'em' , '%' ],                    
                'selectors'     => [
                    '{{WRAPPER}} .premium-gallery-load-more-btn:hover' => 'border-radius: {{SIZE}}{{UNIT}};'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'          => 'premium_gallery_button_shadow_hover',
                'selector'      => '{{WRAPPER}} .premium-gallery-load-more-btn:hover',
            ]
        );

        $this->add_responsive_control('button_margin_hover',
            [
                'label'         => __('Margin', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-gallery-load-more-btn:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );

        $this->add_responsive_control('premium_gallery_button_padding_hover',
            [
                'label'         => __('Padding', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-gallery-load-more-btn:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section('section_lightbox_style',
			[
				'label' => __( 'Lightbox', 'premium-addons-for-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'premium_gallery_lightbox_type' => 'yes'
                ]
			]
		);

		$this->add_control('lightbox_color',
			[
				'label' => __( 'Background Color', 'premium-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'#elementor-lightbox-slideshow-{{ID}}' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control('lightbox_ui_color',
			[
				'label' => __( 'UI Color', 'premium-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'#elementor-lightbox-slideshow-{{ID}} .dialog-lightbox-close-button, #elementor-lightbox-slideshow-{{ID}} .elementor-swiper-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control('lightbox_ui_hover_color',
			[
				'label' => __( 'UI Hover Color', 'premium-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'#elementor-lightbox-slideshow-{{ID}} .dialog-lightbox-close-button:hover, #elementor-lightbox-slideshow-{{ID}} .elementor-swiper-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
        
        $this->update_controls();
        
    }
    
    /*
     * Filter Cats
     * 
     * Formats Category to be inserted in class attribute.
     * 
     * @since 2.1.0
     * @access public
     * 
     * @return string category slug
     */
    public function filter_cats( $string ) {
        
		$cat_filtered = mb_strtolower( $string );
        
        if( strpos( $cat_filtered, 'class' ) || strpos( $cat_filtered, 'src' ) ) {
            $cat_filtered = substr( $cat_filtered, strpos( $cat_filtered, '"' ) + 1 );
            $cat_filtered = strtok( $cat_filtered, '"' );
            $cat_filtered = preg_replace( '/[http:.]/', '', $cat_filtered );
            $cat_filtered = str_replace( '/', '', $cat_filtered );
        }

        $cat_filtered = str_replace( ', ', ',', $cat_filtered );
		$cat_filtered = preg_replace( "/[\s_&@!#%]/", "-", $cat_filtered );
        $cat_filtered = str_replace( ',', ' ', $cat_filtered );

		return $cat_filtered;
	}
    
    /*
     * Render Filter Tabs on the frontend
     *
     * @since 2.1.0
     * @access protected
     * 
     * @param string $first Class for the first category
     * @param number $active_index active category index
     */
    protected function render_filter_tabs( $first, $active_index ) {
        
        $settings = $this->get_settings_for_display();
        
        ?>

        <div class="premium-img-gallery-filter">
            <ul class="premium-gallery-cats-container">
                <?php if( 'yes' == $settings['premium_gallery_first_cat_switcher'] ) : ?>
                    <li>
                        <a href="javascript:;" class="category <?php echo $first; ?>" data-filter="*">
                            <span><?php echo $settings['premium_gallery_first_cat_label']; ?></span>
                        </a>
                    </li>
                <?php endif; 
                foreach( $settings['premium_gallery_cats_content'] as $index => $category ) {
                    if( ! empty( $category['premium_gallery_img_cat'] ) ) {
                        $cat_filtered = $this->filter_cats( $category['premium_gallery_img_cat'] );

                        $key = 'premium_grid_category_' . $index;

                        if( $active_index === $index ) {
                            $this->add_render_attribute( $key, 'class', 'active' );
                        }

                        $this->add_render_attribute( $key,
                            'class', [
                                'category',
                                'elementor-repeater-item-' . $category['_id']
                            ]
                        );
                        
                        $slug = sprintf( '.%s', $cat_filtered );
                        
                        $this->add_render_attribute( $key, 'data-filter', $slug );
                    ?>
                        <li>
                            <a href="javascript:;" <?php echo $this->get_render_attribute_string( $key ); ?>>
                                <span><?php echo $category['premium_gallery_img_cat']; ?></span>
                            </a>
                        </li>
                    <?php }
                } ?>
            </ul>
        </div>

        <?php
    }
    
    /**
	 * Render Grid output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 2.1.0
	 * @access protected
	 */
    protected function render() {
        
        $settings       = $this->get_settings_for_display();
        
        $filter         = $settings['premium_gallery_filter'];
        
        $skin           = $settings['premium_gallery_img_style'];
        
        $layout         = $settings['premium_gallery_img_size_select'];
        
        $lightbox       = $settings['premium_gallery_light_box'];
        
        $lightbox_type  = $settings['premium_gallery_lightbox_type'];
        
        $show_play      = $settings['premium_gallery_video_icon'];
        
        if ( 'yes' === $settings['premium_gallery_responsive_switcher'] ) {
            $min_size   = $settings['premium_gallery_min_range'] . 'px';
            $max_size   = $settings['premium_gallery_max_range'] . 'px';
        }
        
        $category = "*";
        
        if ( 'yes' === $filter ) {
            
            if ( ! empty( $settings['premium_gallery_active_cat'] ) || 0 === $settings['premium_gallery_active_cat'] ) {
                
                if( 'yes' !== $settings['premium_gallery_first_cat_switcher'] ) {
                    $active_index           = $settings['premium_gallery_active_cat'];
                    $active_category        = $settings['premium_gallery_cats_content'][$active_index]['premium_gallery_img_cat'];
                    $category               = "." . $this->filter_cats( $active_category );
                    $active_category_index  = $settings['premium_gallery_active_cat'];
                    
                } else {
                    $active_category_index  = $settings['premium_gallery_active_cat'] - 1;
                }
                
            } else {
                $active_category_index = 'yes' === $settings['premium_gallery_first_cat_switcher'] ? -1 : 0;
            }
        
            $is_all_active = ( 0 > $active_category_index ) ? "active" : "";
            
        }
        
        if ( 'original' === $layout ) {
            $layout = 'masonry';
        } else if ( 'one_size' === $layout ) {
            $layout = 'fitRows';
        }
        
        $ltr_mode           = 'yes' === $settings['premium_gallery_rtl_mode'] ? false : true;
        
        $shuffle            = 'yes' === $settings['premium_gallery_shuffle'] ? true : false;
        
        $shuffle_onload     = 'yes' === $settings['premium_gallery_shuffle_onload'] ? 'random' : 'original-order';
        
        $grid_settings  = [
            'img_size'      => $layout,
            'filter'        => $filter,
            'theme'         => $settings['premium_gallery_lightbox_theme'],
            'active_cat'    => $category,
            'ltr_mode'      => $ltr_mode,
            'shuffle'       => $shuffle,
            'sort_by'       => $shuffle_onload,
            'skin'          => $skin
        ];
        
        $load_more          = 'yes' === $settings['premium_gallery_load_more'] ? true : false;
        
        if( $load_more ) {
            $minimum        = ! empty ( $settings['premium_gallery_load_minimum'] ) ? $settings['premium_gallery_load_minimum'] : 6;
            $click_number   = ! empty ( $settings['premium_gallery_load_click_number'] ) ? $settings['premium_gallery_load_click_number'] : 6;
            
            $grid_settings = array_merge( $grid_settings, [
                'load_more'     => $load_more,
                'minimum'       => $minimum,
                'click_images'  => $click_number,
            ]);
        }
        
        if ( 'yes' === $lightbox ) {
            $grid_settings = array_merge( $grid_settings, [
                'light_box'         => $lightbox,
                'lightbox_type'     => $lightbox_type,
                'overlay'           => 'yes' === $settings['premium_gallery_overlay_gallery'] ? true : false,
            ]);
        } else {
            $this->add_render_attribute( 'grid', [
                'class'         => [
                        'premium-img-gallery-no-lightbox'
                    ]
                ]
            );
        }
        
        $this->add_render_attribute( 'grid', [
                'id'            => 'premium-img-gallery-' . esc_attr( $this->get_id() ),
                'class'         => [
                    'premium-img-gallery',
                    'premium-img-gallery-' . $layout,
                    $settings['premium_gallery_img_effect']
                ]
            ]
        );
        
        if ( $show_play ) {
            $this->add_render_attribute( 'grid', [
                    'class'         => [
                        'premium-gallery-icon-show'
                    ]
                ]
            );
        }
        
        $this->add_render_attribute( 'container', 'class', [
            'pa-gallery-img-container'
        ]);
        
    ?>

    <div <?php echo $this->get_render_attribute_string( 'grid' ); ?>>
        <?php if( $filter == 'yes' ) :
            $this->render_filter_tabs( $is_all_active, $active_category_index );
        endif; ?>
        
        <div class="premium-gallery-container" data-settings='<?php echo wp_json_encode( $grid_settings ); ?>'>
            
            <?php if ( 'metro' === $layout ) : ?>
                <div class="grid-sizer"></div>
            <?php endif;
            
            foreach( $settings['premium_gallery_img_content'] as $index => $image  ) :
                
                $key = 'gallery_item_' . $index;
                
                $this->add_render_attribute( $key, [
                        'class' => [
                            'premium-gallery-item',
                            'elementor-repeater-item-' . $image['_id'],
                            $this->filter_cats( $image['premium_gallery_img_category'] )
                        ]
                    ]
                );
                
                if ( 'metro' === $layout ) {
                    
                    $cells = [
                        'cells'         => $image['premium_gallery_image_cell']['size'],
                        'vcells'        => $image['premium_gallery_image_vcell']['size'],
                        'cells_tablet'  => $image['premium_gallery_image_cell_tablet']['size'],
                        'vcells_tablet' => $image['premium_gallery_image_vcell_tablet']['size'],
                        'cells_mobile'  => $image['premium_gallery_image_cell_mobile']['size'],
                        'vcells_mobile' => $image['premium_gallery_image_vcell_mobile']['size'],
                    ];
                    
                    $this->add_render_attribute( $key, 'data-metro', wp_json_encode( $cells )  );
                }
                
                if( $image['premium_gallery_video'] ) {
                    $this->add_render_attribute( $key, 'class', 'premium-gallery-video-item'  );
                }
                
            ?>
            <div <?php echo $this->get_render_attribute_string( $key ); ?>>
                <div class="pa-gallery-img <?php echo esc_attr( $skin ); ?>" onclick="">
                    <div <?php echo $this->get_render_attribute_string( 'container' ); ?>>
                        <?php 
                            $video_link = $this->render_grid_item ( $image, $index );
                            
                            $image['video_link'] = $video_link;
                        if( 'style3' === $skin ) : ?>
                            <div class="pa-gallery-icons-wrapper">
                                <div class="pa-gallery-icons-inner-container">
                                    <?php $this->render_icons( $image, $index ); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php if( 'style2' !== $skin ) :
                        if( 'default' === $skin || 'style1' === $skin ) : ?>
                            <div class="pa-gallery-icons-wrapper">
                                <div class="pa-gallery-icons-inner-container">
                                    <?php $this->render_icons( $image, $index ); ?>
                                </div>
                            </div>
                        <?php 
                        endif;
                        $this->render_image_caption( $image );
                    else: ?>
                        <div class="pa-gallery-icons-caption-container">
                            <div class="pa-gallery-icons-caption-cell">
                                <?php 
                                        $this->render_icons( $image, $index );
                                        $this->render_image_caption( $image );
                                ?>
                            </div>
                        </div>
                    <?php endif;
                    if( $image['premium_gallery_video'] ) : ?>
                            </div>
                        </div>
                        <?php continue;
                    endif;
                        if( 'yes' === $image['premium_gallery_link_whole'] ) {

                            if( 'url' === $image['premium_gallery_img_link_type'] && ! empty( $image['premium_gallery_img_link']['url'] ) ) {

                                $icon_link  = $image['premium_gallery_img_link']['url'];
                                $external   = $image['premium_gallery_img_link']['is_external'] ? 'target="_blank"' : '';
                                $no_follow  = $image['premium_gallery_img_link']['nofollow'] ? 'rel="nofollow"' : '';

                            ?>
                                <a class="pa-gallery-whole-link" href="<?php echo esc_attr( $icon_link ); ?>" <?php echo $external; ?><?php echo $no_follow; ?>></a>

                            <?php } elseif( 'link' === $image['premium_gallery_img_link_type'] ) {
                                $icon_link = get_permalink( $image['premium_gallery_img_existing'] );
                            ?>
                                <a class="pa-gallery-whole-link" href="<?php echo esc_attr( $icon_link ); ?>"></a>
                            <?php }

                        } elseif ( 'yes' === $lightbox ) {

                            if( 'yes' === $image['premium_gallery_lightbox_whole'] ) {

                                $lightbox_key   = 'image_lightbox_' . $index;

                                $this->add_render_attribute( $lightbox_key, [
                                    'class'     => 'pa-gallery-whole-link',
                                    'href'      => $image['premium_gallery_img']['url'],
                                ]);

                                if( 'default' !== $lightbox_type ) {

                                    $this->add_render_attribute( $lightbox_key, [
                                        'data-elementor-open-lightbox'      => $lightbox_type,
                                        'data-elementor-lightbox-slideshow' => $this->get_id()
                                    ]);

                                    if( 'yes' === $settings['lightbox_show_title'] ) {

                                        $alt    = Control_Media::get_image_alt( $image['premium_gallery_img'] );
                                       
                                        $this->add_render_attribute( $lightbox_key, 'data-elementor-lightbox-title', $alt );
                                        
                                    }

                                } else {

                                    $rel            = sprintf( 'prettyPhoto[premium-grid-%s]', $this->get_id() );

                                    $this->add_render_attribute( $lightbox_key, [
                                        'data-rel'  => $rel
                                    ]);
                                }

                                ?>

                                <a <?php echo $this->get_render_attribute_string( $lightbox_key ); ?>></a>

                                <?php
                            }
                        }
                    ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <?php if ( 'yes' === $settings['premium_gallery_load_more'] ) : ?>
            <div class="premium-gallery-load-more premium-gallery-btn-hidden">
                <button class="premium-gallery-load-more-btn">
                    <span><?php echo $settings['premium_gallery_load_more_text']; ?></span>
                    <div class="premium-loader"></div>
                </button>
            </div>
        <?php endif; ?>
        
    </div>
    
    <?php if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) {

        if ( 'metro' !== $settings['premium_gallery_img_size_select'] ) {
            $this->render_editor_script();
        }
    } ?>

    <?php if( 'yes' === $settings['premium_gallery_responsive_switcher'] ) : ?>
        <style>
            @media( min-width: <?php echo $min_size; ?> ) and ( max-width:<?php echo $max_size; ?> ) {
                #premium-img-gallery-<?php echo esc_attr( $this->get_id() ); ?> .premium-gallery-caption {
                    display: none;
                }  
            }
        </style>
    <?php endif; ?>
        
    <?php }
    
    /*
     * Render Grid Image
     * 
     * Written in PHP and used to generate the final HTML for image.
     * 
     * @since 3.6.4
     * @access protected
     * 
     * @param array $item grid image repeater item
     * @param number $index item index
     */
    protected function render_grid_item( $item, $index ) {
        
        $settings   = $this->get_settings();
        
        $is_video   = $item['premium_gallery_video'];
        
        $alt        = Control_Media::get_image_alt( $item['premium_gallery_img'] );
        
        $key        = 'image_' . $index;

        $image_src = $item['premium_gallery_img'];
        $image_src_size = Group_Control_Image_Size::get_attachment_image_src($image_src['id'], 'thumbnail', $settings);

        $image_src = empty( $image_src_size ) ? $image_src['url'] : $image_src_size;
        
        if( $is_video ) {
            
            $type       = $item['premium_gallery_video_type'];
        
            if( 'hosted' !==  $type ) {
                $embed_params   = $this->get_embed_params( $item );
                $link           = Embed::get_embed_url( $item['premium_gallery_video_url'], $embed_params );
                
                if( empty( $image_src ) ) {
                    $video_props    = Embed::get_video_properties( $link );
                    $id             = $video_props['video_id'];
                    $type           = $video_props['provider'];
                    $size           = '';
                    if( 'youtube' === $type ) {
                        $size = $settings['premium_gallery_yt_thumbnail_size'];
                    }
                    $image_src = Helper_Functions::get_video_thumbnail( $id, $type, $size );
                }
                
            } else {
                $video_params = $this->get_hosted_params( $item );
            }

        }
        
        $this->add_render_attribute( $key, [
            'class' => 'pa-gallery-image',
            'src'   => $image_src,
            'alt'   => $alt
        ]);
        
        if ( $is_video ) {
        ?>
            <div class="premium-gallery-video-wrap" data-type="<?php echo $item['premium_gallery_video_type']; ?>">
                <?php if( 'hosted' !== $item['premium_gallery_video_type'] ) : ?>
                    <div class="premium-gallery-iframe-wrap" data-src="<?php echo $link; ?>"></div>
                <?php else: 
                    $link = empty( $item['premium_gallery_video_self_url'] ) ? $item['premium_gallery_video_self']['url'] : $item['premium_gallery_video_self_url'];
                ?>
                    <video src="<?php echo esc_url( $link ); ?>" <?php echo Utils::render_html_attributes( $video_params ); ?>></video>
                <?php endif; ?>
            </div>
        <?php } ?>
                
        <img <?php echo $this->get_render_attribute_string( $key ); ?>>    
        <?php
         
        return ( isset( $link ) && ! empty ( $link ) ) ? $link : false;
    }
    
    /*
     * Render Icons
     * 
     * Render Lightbox and URL Icons HTML
     * 
     * @since 3.6.4
     * @access protected
     * 
     * @param array $item grid image repeater item
     * @param number $index item index
     */
    protected function render_icons( $item, $index ) {
        
        $settings       = $this->get_settings_for_display();
        
        $lightbox_key   = 'image_lightbox_' . $index;
        
        $link_key       = 'image_link_' . $index;
        
        $href           = $item['premium_gallery_img']['url'];
        
        $lightbox       = $settings['premium_gallery_light_box'];
        
        $lightbox_type  = $settings['premium_gallery_lightbox_type'];
        
        $is_video       = $item['premium_gallery_video'];
        
        $id             = $this->get_id();
        
        if ( $is_video ) {
            
            $type = $item['premium_gallery_video_type'];
            
            $this->add_render_attribute( $lightbox_key, [
                'class'     => [
                    'pa-gallery-lightbox-wrap',
                    'pa-gallery-video-icon'
                ]
            ]);
            
            if( 'yes' === $lightbox ) {
                
                $lightbox_options = [
                    'type' => 'video',
                    'videoType' => $item['premium_gallery_video_type'],
                    'url' => $item['video_link'],
                    'modalOptions' => [
                        'id' => 'elementor-lightbox-' . $this->get_id(),
                        'videoAspectRatio' => '169',
                    ],
                ];
                
                if( 'hosted' === $type ) {
                    $lightbox_options['videoParams'] = $this->get_hosted_params( $item );
                }
                
                $this->add_render_attribute( $lightbox_key, [
                    'data-elementor-open-lightbox' => 'yes',
                    'data-elementor-lightbox' => wp_json_encode( $lightbox_options ),
                ] );
                
                
            }
            
        ?>
            <div <?php echo $this->get_render_attribute_string( $lightbox_key ); ?>>
                <a class="pa-gallery-magnific-image pa-gallery-video-icon">
                    <span>
                        <?php Icons_Manager::render_icon( $settings['premium_gallery_videos_icon'], [ 'aria-hidden' => 'true' ] );
                            ?>
                    </span>
                </a>
            </div>
        
        <?php
            return; 
        }
        
        if( 'yes' === $lightbox ) {
            
            if( 'yes' !== $item['premium_gallery_lightbox_whole'] ) {
                
                $this->add_render_attribute( $lightbox_key, [
                    'class'     => 'pa-gallery-magnific-image',
                    'href'      => $href,
                ]);

                if( 'default' !== $lightbox_type ) {

                    $this->add_render_attribute( $lightbox_key, [
                        'data-elementor-open-lightbox'      => $lightbox_type,
                        'data-elementor-lightbox-slideshow' => $id
                    ]);
                    
                    if( 'yes' === $settings['lightbox_show_title'] ) {

                        $alt    = Control_Media::get_image_alt( $item['premium_gallery_img'] );
                       
                        $this->add_render_attribute( $lightbox_key, 'data-elementor-lightbox-title', $alt );
                        
                    }

                } else {

                    $rel = sprintf( 'prettyPhoto[premium-grid-%s]', $this->get_id() );

                    $this->add_render_attribute( $lightbox_key, [
                        'data-rel'  => $rel
                    ]);
                    
                }

                ?> 
                    <a <?php echo $this->get_render_attribute_string( $lightbox_key ); ?>>
                        <span>
                            <?php Icons_Manager::render_icon( $settings['premium_gallery_lightbox_icon'], [ 'aria-hidden' => 'true' ] );
                            ?>
                        </span>
                    </a>
            <?php
            }
        }
        

        if( ! empty( $item['premium_gallery_img_link']['url'] ) || ! empty ( $item['premium_gallery_img_existing'] ) ) {
            
            if( 'yes' !== $item['premium_gallery_link_whole'] ) {
                
                $icon_link = '';
                
                $this->add_render_attribute( $link_key, [
                    'class'     => 'pa-gallery-img-link',
                ]);

                if( 'url' === $item['premium_gallery_img_link_type'] && ! empty( $item['premium_gallery_img_link']['url'] ) ) {

                    $icon_link  = $item['premium_gallery_img_link']['url'];

                    $external   = $item['premium_gallery_img_link']['is_external'] ? '_blank' : '';

                    $no_follow  = $item['premium_gallery_img_link']['nofollow'] ? 'nofollow' : '';

                    $this->add_render_attribute( $link_key, [
                        'href'      => $icon_link,
                        'target'    => $external,
                        'rel'       => $no_follow
                    ]);

                } elseif( 'link' === $item['premium_gallery_img_link_type'] && ! empty( $item['premium_gallery_img_existing'] ) ) {

                    $icon_link = get_permalink( $item['premium_gallery_img_existing'] );

                    $this->add_render_attribute( $link_key, [
                        'href'      => $icon_link
                    ]);

                } 

                if ( ! empty ( $icon_link ) ) {
                ?>
                    <a <?php echo $this->get_render_attribute_string( $link_key ); ?>>
                        <span>
                            <?php Icons_Manager::render_icon( $settings['premium_gallery_links_icon'], [ 'aria-hidden' => 'true' ] );
                            ?>
                        </span>
                    </a>
                <?php
                }
            }
        }
    }
    
    /*
     * Render Image Caption
     * 
     * Written in PHP to render the final HTML for image title and description
     * 
     * @since 3.6.4
     * @access proteced
     * 
     * @param array $item grid image repeater item
     */
    protected function render_image_caption( $item ) {
        
        $title          = $item['premium_gallery_img_name'];
        
        $description    = $item['premium_gallery_img_desc'];
            
        if( ! empty( $title ) || ! empty( $description ) ) : ?>
            <div class="premium-gallery-caption">
                
                <?php if( ! empty( $title ) ) : ?>
                    <span class="premium-gallery-img-name"><?php echo $title; ?></span>
                <?php endif;
                    
                if( ! empty( $description ) ) : ?>
                    <p class="premium-gallery-img-desc"><?php echo $description; ?></p>
                <?php endif; ?>
                    
            </div>
        <?php endif; 
    }
    
    /*
     * Get Hosted Videos Parameters
     * 
     * @since 3.7.0
     * @access private
     * 
     * @param array $item grid image repeater item
     */
    private function get_hosted_params( $item ) {
        
		$video_params = [];
        
        if ( $item[ 'premium_gallery_video_controls' ] ) {
            $video_params[ 'controls' ] = '';
        }

        if ( $item['premium_gallery_video_mute'] ) {
			$video_params['muted'] = 'muted';
		}
        
		return $video_params;
    }
    
    /*
     * Get embeded videos parameters
     * 
     * @since 3.7.0
     * @access private
     * 
     * @param array $item grid image repeater item
     */
    private function get_embed_params( $item ) {
        
		$video_params               = [];
        
        $video_params[ 'controls' ] = $item[ 'premium_gallery_video_controls' ] ? '1' : '0';
        
        $key                        = 'youtube' === $item[ 'premium_gallery_video_type' ] ? 'mute' : 'muted';
        
        $video_params[ $key ]       = $item['premium_gallery_video_mute'] ? '1' : '0';

        if( 'vimeo' === $item[ 'premium_gallery_video_type' ] ) {
            $video_params[ 'autopause' ] = '0';
        }
		
		return $video_params;
    }
    
    /*
     * Update Controls
     * 
     * @since 3.8.8
     * @access private
     * 
     * @param array $item grid image repeater item
     */
    private function update_controls() {
		
		$this->update_responsive_control( 'premium_gallery_img_border_radius',
			[
                'type'          => Controls_Manager::DIMENSIONS,
                'selectors'     => [
                    '{{WRAPPER}} .pa-gallery-img-container, {{WRAPPER}} .pa-gallery-img:not(.style2) .pa-gallery-icons-wrapper, {{WRAPPER}} .pa-gallery-img.style2 .pa-gallery-icons-caption-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
				
			]
		);
        
        $this->update_responsive_control( 'premium_gallery_content_border_radius',
			[
                'type'          => Controls_Manager::DIMENSIONS,
                'selectors'     => [
                    '{{WRAPPER}} .premium-gallery-caption' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
				
			]
		);

    }
    
    /**
	 * Render Editor Masonry Script.
	 *
	 * @since 3.12.3
	 * @access protected
	 */
	protected function render_editor_script() {

		?><script type="text/javascript">
			jQuery( document ).ready( function( $ ) {

				$( '.premium-gallery-container' ).each( function() {

                    var $node_id 	= '<?php echo $this->get_id(); ?>',
                        scope 		= $( '[data-id="' + $node_id + '"]' ),
                        settings    = $(this).data("settings"),
                        selector 	= $(this);
                    
					if ( selector.closest( scope ).length < 1 ) {
						return;
					}
					
                    var masonryArgs = {
                        // set itemSelector so .grid-sizer is not used in layout
                        filter 			: settings.active_cat,
                        itemSelector	: '.premium-gallery-item',
                        percentPosition : true,
                        layoutMode		: settings.img_size,
                    };

                    var $isotopeObj = {};

                    selector.imagesLoaded( function() {

                        $isotopeObj = selector.isotope( masonryArgs );

                        selector.find('.premium-gallery-item').resize( function() {
                            $isotopeObj.isotope( 'layout' );
                        });
                    });

				});
			});
		</script>
		<?php
	}
}