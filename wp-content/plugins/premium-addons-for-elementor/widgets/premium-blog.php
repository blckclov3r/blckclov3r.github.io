<?php

/**
 * Premium Blog.
 */
namespace PremiumAddons\Widgets;

// Elementor Classes.
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Box_Shadow;

// PremiumAddons Classes.
use PremiumAddons\Helper_Functions;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

/**
 * Class Premium_Blog
 */
class Premium_Blog extends Widget_Base {
    
    public function get_name() {
        return 'premium-addon-blog';
    }

    public function get_title() {
		return sprintf( '%1$s %2$s', Helper_Functions::get_prefix(), __('Blog', 'premium-addons-for-elementor') );
	}

    public function is_reload_preview_required() {
        return true;
    }
    
    public function get_style_depends() {
        return [
            'font-awesome-5-all',
            'premium-addons'
        ];
    }
    
    public function get_script_depends() {
        return [
            'isotope-js',
            'jquery-slick',
            'premium-addons-js'
        ];
    }

    public function get_icon() {
        return 'pa-blog';
    }
    
    public function get_keywords() {
		return [ 'posts', 'grid', 'item', 'loop', 'query', 'portfolio' ];
	}

    public function get_categories() {
        return [ 'premium-elements' ];
    }
    
    
    public function get_custom_help_url() {
		return 'https://premiumaddons.com/support/';
	}

    /**
	 * Register Blog controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
    protected function _register_controls() {
        
        $this->start_controls_section('general_settings_section',
            [
                'label'         => __('General', 'premium-addons-for-elementor'),
            ]
        );
        
        $this->add_control('premium_blog_skin',
            [
                'label'         => __('Skin', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'classic'       => __('Classic', 'premium-addons-for-elementor'),
                    'modern'        => __('Modern', 'premium-addons-for-elementor'),
                    'cards'         => __('Cards', 'premium-addons-for-elementor'),
                ],
                'default'       => 'modern',
                'label_block'   => true
            ]
        );

        $this->add_responsive_control('content_offset',
            [
                'label'         => __('Content Offset', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'range'         => [
                    'px'    => [
                        'min'   => -100, 
                        'max'   => 100,
                    ],
                ],
                'condition'     => [
                    'premium_blog_skin' =>  'modern',
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-skin-modern .premium-blog-content-wrapper' => 'top: {{SIZE}}{{UNIT}}'
                ]
            ]
        );
        
        $this->add_control('premium_blog_grid',
            [
                'label'         => __('Grid', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'default'       => 'yes'
            ]
        );
        
        $this->add_control('premium_blog_layout',
            [
                'label'             => __('Layout', 'premium-addons-for-elementor'),
                'type'              => Controls_Manager::SELECT,
                'options'           => [
                    'even'      => __('Even', 'premium-addons-for-elementor'),
                    'masonry'   => __('Masonry', 'premium-addons-for-elementor'),
                ],
                'default'           => 'masonry',
                'condition'         => [
                    'premium_blog_grid' => 'yes'
                ]
            ]
        );
        
        $this->add_control('force_height',
            [
                'label'         => __('Force Equal Height for Content Boxes', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'true',
                'condition'         => [
                    'premium_blog_grid' => 'yes',
                    'premium_blog_layout' => 'even'
                ]
            ]
        );

        $this->add_control('force_height_notice', 
            [
                'raw'               => __('Force equal height option uses JS to force all content boxes to take the equal height, so you will need to make sure all featured images are the same height. You can set that from Featured Image tab.', 'premium-addons-for-elemeentor'),
                'type'              => Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'condition'         => [
                    'premium_blog_grid' => 'yes',
                    'premium_blog_layout' => 'even',
                    'force_height'      => 'true'
                ]
            ] 
        );

        $this->add_responsive_control('premium_blog_columns_number',
            [
                'label'         => __('Number of Columns', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    '100%'  => __('1 Column', 'premium-addons-for-elementor'),
                    '50%'   => __('2 Columns', 'premium-addons-for-elementor'),
                    '33.33%'=> __('3 Columns', 'premium-addons-for-elementor'),
                    '25%'   => __('4 Columns', 'premium-addons-for-elementor'),
                    '20%'       => __( '5 Columns', 'premium-addons-for-elementor' ),
					'16.66%'    => __( '6 Columns', 'premium-addons-for-elementor' ),
                ],
                'default'       => '33.33%',
                'tablet_default'=> '50%',
                'mobile_default'=> '100%',
                'render_type'   => 'template',
                'label_block'   => true,
                'condition'     => [
                    'premium_blog_grid' =>  'yes',
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-post-outer-container'  => 'width: {{VALUE}};'
                ],
            ]
        );
        
        $this->add_control('premium_blog_number_of_posts',
            [
                'label'         => __('Posts Per Page', 'premium-addons-for-elementor'),
                'description'   => __('Set the number of per page','premium-addons-for-elementor'),
                'type'          => Controls_Manager::NUMBER,
                'min'			=> 1,
                'default'		=> 3,
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('section_query_options',
            [
                'label'         => __('Query', 'premium-addons-for-elementor'),
            ]
        );
        
        $this->add_control('category_filter_rule',
            [
                'label'       => __( 'Filter By Category Rule', 'premium-addons-for-elementor' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'category__in',
                'separator'     => 'before',
                'label_block' => true,
                'options'     => [
                    'category__in'     => __( 'Match Categories', 'premium-addons-for-elementor' ),
                    'category__not_in' => __( 'Exclude Categories', 'premium-addons-for-elementor' ),
                ]
            ]
        );
        
        $this->add_control('premium_blog_categories',
            [
                'label'         => __( 'Categories', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::SELECT2,
                'description'   => __('Get posts for specific category(s)','premium-addons-for-elementor'),
                'label_block'   => true,
                'multiple'      => true,
                'options'       => premium_blog_post_type_categories(),
            ]
        );
        
        $this->add_control('tags_filter_rule',
            [
                'label'       => __( 'Filter By Tag Rule', 'premium-addons-for-elementor' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'tag__in',
                'separator'     => 'before',
                'label_block' => true,
                'options'     => [
                    'tag__in'     => __( 'Match Tags', 'premium-addons-for-elementor' ),
                    'tag__not_in' => __( 'Exclude Tags', 'premium-addons-for-elementor' ),
                ]
            ]
        );
        
        $this->add_control('premium_blog_tags',
            [
                'label'         => __( 'Tags', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::SELECT2,
                'description'   => __('Get posts for specific tag(s)','premium-addons-for-elementor'),
                'label_block'   => true,
                'multiple'      => true,
                'options'       => premium_blog_post_type_tags(),        
            ]
        );
        
        $this->add_control('author_filter_rule',
            [
                'label'       => __( 'Filter By Author Rule', 'premium-addons-for-elementor' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'author__in',
                'separator'     => 'before',
                'label_block' => true,
                'options'     => [
                    'author__in'     => __( 'Match Authors', 'premium-addons-for-elementor' ),
                    'author__not_in' => __( 'Exclude Authors', 'premium-addons-for-elementor' ),
                ]
            ]
        );
        
        $this->add_control('premium_blog_users',
            [
                'label'         => __( 'Authors', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::SELECT2,
                'label_block'   => true,
                'multiple'      => true,
                'options'       => premium_blog_post_type_users(),        
            ]
        );
        
        $this->add_control('posts_filter_rule',
            [
                'label'       => __( 'Filter By Post Rule', 'premium-addons-for-elementor' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'post__not_in',
                'separator'     => 'before',
                'label_block' => true,
                'options'     => [
                    'post__in'     => __( 'Match Post', 'premium-addons-for-elementor' ),
                    'post__not_in' => __( 'Exclude Post', 'premium-addons-for-elementor' ),
                ]
            ]
        );
        
        $this->add_control('premium_blog_posts_exclude',
            [
                'label'         => __( 'Posts', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::SELECT2,
                'label_block'   => true,
                'multiple'      => true,
                'options'       => premium_blog_posts_list(),        
            ]
        );
        
        $this->add_control('premium_blog_offset',
			[
				'label'         => __( 'Offset Count', 'premium-addons-for-elementor' ),
                'description'   => __('This option is used to exclude number of initial posts from being display.','premium-addons-for-elementor'),
				'type' 			=> Controls_Manager::NUMBER,
                'default' 		=> '0',
				'min' 			=> '0',
			]
		);
        
        $this->add_control('premium_blog_order_by',
            [
                'label'         => __( 'Order By', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::SELECT,
                'separator'     => 'before',
                'label_block'   => true,
                'options'       => [
                    'none'  => __('None', 'premium-addons-for-elementor'),
                    'ID'    => __('ID', 'premium-addons-for-elementor'),
                    'author'=> __('Author', 'premium-addons-for-elementor'),
                    'title' => __('Title', 'premium-addons-for-elementor'),
                    'name'  => __('Name', 'premium-addons-for-elementor'),
                    'date'  => __('Date', 'premium-addons-for-elementor'),
                    'modified'=> __('Last Modified', 'premium-addons-for-elementor'),
                    'rand'  => __('Random', 'premium-addons-for-elementor'),
                    'comment_count'=> __('Number of Comments', 'premium-addons-for-elementor'),
                ],
                'default'       => 'date'
            ]
        );
        
        $this->add_control('premium_blog_order',
            [
                'label'         => __( 'Order', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::SELECT,
                'label_block'   => true,
                'options'       => [
                    'DESC'  => __('Descending', 'premium-addons-for-elementor'),
                    'ASC'   => __('Ascending', 'premium-addons-for-elementor'),
                ],
                'default'       => 'DESC'
            ]
        );
            
        $this->end_controls_section();

        $this->start_controls_section('premium_blog_general_settings',
            [
                'label'         => __('Featured Image', 'premium-addons-for-elementor'),
            ]
        );

        $this->add_control('show_featured_image',
            [
                'label'         => __('Show Featured Image', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'default'       => 'yes',
            ]
        );

        $featured_image_conditions = array(
            'show_featured_image'   => 'yes'
        );
        
        $this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'featured_image',
                'default' => 'full',
                'condition' => $featured_image_conditions
			]
		);
        
        $this->add_control('premium_blog_hover_color_effect',
            [
                'label'         => __('Overlay Effect', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'description'   => __('Choose an overlay color effect','premium-addons-for-elementor'),
                'options'       => [
                    'none'     => __('None', 'premium-addons-for-elementor'),
                    'framed'   => __('Framed', 'premium-addons-for-elementor'),
                    'diagonal' => __('Diagonal', 'premium-addons-for-elementor'),
                    'bordered' => __('Bordered', 'premium-addons-for-elementor'),
                    'squares'  => __('Squares', 'premium-addons-for-elementor'),
                ],
                'default'       => 'framed',
                'label_block'   => true,
                'condition'     => array_merge( $featured_image_conditions, [
                    'premium_blog_skin!' => 'classic'
                ])
            ]
        );
        
        $this->add_control('premium_blog_hover_image_effect',
            [
                'label'         => __('Hover Effect', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'description'   => __('Choose a hover effect for the image','premium-addons-for-elementor'),
                'options'       => [
                    'none'   => __('None', 'premium-addons-for-elementor'),
                    'zoomin' => __('Zoom In', 'premium-addons-for-elementor'),
                    'zoomout'=> __('Zoom Out', 'premium-addons-for-elementor'),
                    'scale'  => __('Scale', 'premium-addons-for-elementor'),
                    'gray'   => __('Grayscale', 'premium-addons-for-elementor'),
                    'blur'   => __('Blur', 'premium-addons-for-elementor'),
                    'bright' => __('Bright', 'premium-addons-for-elementor'),
                    'sepia'  => __('Sepia', 'premium-addons-for-elementor'),
                    'trans'  => __('Translate', 'premium-addons-for-elementor'),
                ],
                'default'       => 'zoomin',
                'label_block'   => true,
                'condition' => $featured_image_conditions
            ]
        );
        
        $this->add_responsive_control('premium_blog_thumb_min_height',
            [
                'label'         => __('Height', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', 'em'],
                'range'         => [
                    'px'    => [
                        'min'   => 0, 
                        'max'   => 300,
                    ],
                    'em'    => [
                        'min'   => 1, 
                        'max'   => 30,
                    ],
                ],
                'condition'     => array_merge( $featured_image_conditions, [
                    'premium_blog_grid' =>  'yes',
                ] ),
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-thumbnail-container img' => 'height: {{SIZE}}{{UNIT}}'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_blog_thumbnail_fit',
            [
                'label'         => __('Thumbnail Fit', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'cover'  => __('Cover', 'premium-addons-for-elementor'),
                    'fill'   => __('Fill', 'premium-addons-for-elementor'),
                    'contain'=> __('Contain', 'premium-addons-for-elementor'),
                ],
                'default'       => 'cover',
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-thumbnail-container img' => 'object-fit: {{VALUE}}'
                ],
                'condition'     => array_merge( $featured_image_conditions, [
                    'premium_blog_grid' =>  'yes'
                ])
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_blog_content_settings',
            [
                'label'         => __('Display Options', 'premium-addons-for-elementor'),
            ]
        );
        
        $this->add_control('premium_blog_title_tag',
			[
				'label'			=> __( 'Title HTML Tag', 'premium-addons-for-elementor' ),
				'description'	=> __( 'Select a heading tag for the post title.', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SELECT,
				'default'		=> 'h2',
				'options'       => [
                    'h1'    => 'H1',
                    'h2'    => 'H2',
                    'h3'    => 'H3',
                    'h4'    => 'H4',
                    'h5'    => 'H5',
                    'h6'    => 'H6',
                    'div'   => 'div',
                    'span'  => 'span',
                    'p'     => 'p',
                ],
				'label_block'	=> true,
			]
		);
        
        $this->add_responsive_control('premium_blog_posts_columns_spacing',
            [
                'label'         => __('Rows Spacing', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%', "em"],
                'range'         => [
                    'px'    => [
                        'min'   => 1, 
                        'max'   => 200,
                    ],
                ],
                'render_type'   => 'template',
                'condition'     => [
                    'premium_blog_grid'   => 'yes'
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-post-outer-container' => 'margin-bottom: {{SIZE}}{{UNIT}}'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_blog_posts_spacing',
            [
                'label'         => __('Columns Spacing', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'default'       => [
					'size' => 5,
				],
                'range'         => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
                'selectors'     => [
					'{{WRAPPER}} .premium-blog-post-outer-container' => 'padding-right: calc( {{SIZE}}{{UNIT}}/2 ); padding-left: calc( {{SIZE}}{{UNIT}}/2 )',
					'{{WRAPPER}} .premium-blog-wrap' => 'margin-left: calc( -{{SIZE}}{{UNIT}}/2 ); margin-right: calc( -{{SIZE}}{{UNIT}}/2 );',
				],
                'condition'     => [
                    'premium_blog_grid'   => 'yes'
                ],
            ]
        );
        
        $this->add_responsive_control('premium_flip_text_align',
            [
                'label'         => __( 'Alignment', 'premium-addons-for-elementor' ),
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
                'default'       => 'left',
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-content-wrapper' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_blog_posts_options',
            [
                'label'         => __('Post Options', 'premium-addons-for-elementor'),
            ]
        );
        
        $this->add_control('premium_blog_excerpt',
            [
                'label'         => __('Show Post Content', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'default'       => 'yes',
            ]
        );
        
        $this->add_control('content_source',
            [
                'label'         => __('Get Content From', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'excerpt'       => __('Post Excerpt', 'premium-addons-for-elementor'),
                    'full'          => __('Post Full Content', 'premium-addons-for-elementor'),
                ],
                'default'       => 'excerpt',
                'label_block'   => true,
                'condition'     => [
                    'premium_blog_excerpt'  => 'yes',
                ]
            ]
        );

        $this->add_control('premium_blog_excerpt_length',
            [
                'label'         => __('Excerpt Length', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::NUMBER,
                'description'   => __('Excerpt is used for article summary with a link to the whole entry. The default except length is 55','premium-addons-for-elementor'),
                'default'       => 55,
                'condition'     => [
                    'premium_blog_excerpt'  => 'yes',
                    'content_source'        => 'excerpt'
                ]
            ]
        );
        
        $this->add_control('premium_blog_excerpt_type',
            [
                'label'         => __('Excerpt Type', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'dots'   => __('Dots', 'premium-addons-for-elementor'),
                    'link'   => __('Link', 'premium-addons-for-elementor'),
                ],
                'default'       => 'dots',
                'label_block'   => true,
                'condition'     => [
                    'premium_blog_excerpt'  => 'yes',
                ]
            ]
        );
        
        $this->add_control('read_more_full_width',
            [
                'label'         => __('Full Width', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'prefix_class'  => 'premium-blog-cta-full-',
                'condition'     => [
                    'premium_blog_excerpt'      => 'yes',
                    'premium_blog_excerpt_type' => 'link'
                ]
            ]
        );

        $this->add_control('premium_blog_excerpt_text',
			[
				'label'			=> __( 'Read More Text', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::TEXT,
                'default'   => __( 'Read More →', 'premium-addons-for-elementor' ),
                'condition'     => [
                    'premium_blog_excerpt'      => 'yes',
                    'premium_blog_excerpt_type' => 'link'
                ]
			]
		);
        
        $this->add_control('premium_blog_author_meta',
            [
                'label'         => __('Author Meta', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'default'       => 'yes',
            ]
        );
        
        $this->add_control('premium_blog_date_meta',
            [
                'label'         => __('Date Meta', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'default'       => 'yes',
            ]
        );
        
        $this->add_control('premium_blog_categories_meta',
            [
                'label'         => __('Categories Meta', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'description'   => __('Display or hide categories meta','premium-addons-for-elementor'),
                'default'       => 'yes',
            ]
        );

        $this->add_control('premium_blog_comments_meta',
            [
                'label'         => __('Comments Meta', 'premium-addons-for-elementor'),
                'description'   => __('Display or hide comments meta','premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'default'       => 'yes',
            ]
        );
        
        $this->add_control('premium_blog_tags_meta',
            [
                'label'         => __('Tags Meta', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'description'   => __('Display or hide post tags','premium-addons-for-elementor'),
                'default'       => 'yes',
            ]
        );
        
        $this->add_control('premium_blog_post_format_icon',
            [
                'label'         => __( 'Post Format Icon', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::SWITCHER,
                'description'   => __( 'Please note that post format icon is hidden for 3 and 4 columns', 'premium-addons-for-elementor' ),
                'default'       => 'yes',
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_blog_advanced_settings',
            [
                'label'         => __('Advanced Settings', 'premium-addons-for-elementor'),
            ]
        );
        
        $this->add_control('premium_blog_cat_tabs',
            [
                'label'         => __('Filter Tabs', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'condition'     => [
                    'premium_blog_carousel!'  => 'yes'
                ]
            ]
        );
        
        $this->add_control('filter_tabs_type',
            [
                'label'       => __( 'Get Tabs From', 'premium-addons-for-elementor' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'categories',
                'label_block' => true,
                'options'     => [
                    'categories'    => __( 'Categories', 'premium-addons-for-elementor' ),
                    'tags'          => __( 'Tags', 'premium-addons-for-elementor' ),
                ],
                'condition'     => [
                    'premium_blog_cat_tabs'     => 'yes',
                    'premium_blog_carousel!'    => 'yes'
                ]
            ]
        );
        
        $this->add_control('filter_tabs_notice', 
            [
                'raw'               => __('Please make sure to select the categories/tags you need to show from Query tab.', 'premium-addons-for-elemeentor'),
                'type'              => Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'condition'     => [
                    'premium_blog_cat_tabs'     => 'yes',
                    'premium_blog_carousel!'    => 'yes'
                ]
            ] 
        );
        
        $this->add_control('premium_blog_tab_label',
			[
				'label'			=> __( 'First Tab Label', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::TEXT,
                'default'       => __('All', 'premium-addons-for-elementor'),
                'condition'     => [
                    'premium_blog_cat_tabs'     => 'yes',
                    'premium_blog_carousel!'    => 'yes'
                ]
			]
		);
        
        $this->add_responsive_control('premium_blog_filter_align',
            [
                'label'         => __( 'Alignment', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::CHOOSE,
                'options'       => [
                    'flex-start'    => [
                        'title' => __( 'Left', 'premium-addons-for-elementor' ),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'center'        => [
                        'title' => __( 'Center', 'premium-addons-for-elementor' ),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'flex-end'      => [
                        'title' => __( 'Right', 'premium-addons-for-elementor' ),
                        'icon'  => 'fa fa-align-right',
                    ],
                ],
                'default'       => 'center',
                'toggle'        => false,
                'condition'     => [
                    'premium_blog_cat_tabs'     => 'yes',
                    'premium_blog_carousel!'    => 'yes'
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-filter' => 'justify-content: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control('premium_blog_new_tab',
            [
                'label'         => __('Links in New Tab', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'description'   => __('Enable links to be opened in a new tab','premium-addons-for-elementor'),
                'default'       => 'yes',
            ]
        );
 
        $this->end_controls_section();
        
        $this->start_controls_section('premium_blog_carousel_settings',
            [
                'label'         => __('Carousel', 'premium-addons-for-elementor'),
                'condition'     => [
                    'premium_blog_grid' => 'yes'
                ]
            ]
        );
        
        $this->add_control('premium_blog_carousel',
            [
                'label'         => __('Enable Carousel', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER
            ]
        );
        
        $this->add_control('premium_blog_carousel_fade',
            [
                'label'         => __('Fade', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'condition'     => [
                    'premium_blog_columns_number' => '100%'
                ]
            ]
        );
        
        $this->add_control('premium_blog_carousel_play',
            [
                'label'         => __('Auto Play', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'condition'     => [
                    'premium_blog_carousel'  => 'yes'
                ]
            ]
        );
        
        $this->add_control('premium_blog_carousel_autoplay_speed',
			[
				'label'			=> __( 'Autoplay Speed', 'premium-addons-for-elementor' ),
				'description'	=> __( 'Autoplay Speed means at which time the next slide should come. Set a value in milliseconds (ms)', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::NUMBER,
				'default'		=> 5000,
				'condition'		=> [
					'premium_blog_carousel' => 'yes',
                    'premium_blog_carousel_play' => 'yes',
				],
			]
        );
        
        $this->add_control('premium_blog_carousel_center',
            [
                'label'         => __('Center Mode', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'condition'     => [
                    'premium_blog_carousel' => 'yes',
                ]
            ]
        );

        $this->add_control('premium_blog_carousel_spacing',
			[
				'label' 		=> __( 'Slides\' Spacing', 'premium-addons-for-elementor' ),
                'description'   => __('Set a spacing value in pixels (px)', 'premium-addons-for-elementor'),
				'type'			=> Controls_Manager::NUMBER,
                'default'		=> '15',
                'condition'     => [
                    'premium_blog_carousel' => 'yes',
                ]
			]
		);
        
        $this->add_control('premium_blog_carousel_dots',
            [
                'label'         => __('Navigation Dots', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'condition'     => [
                    'premium_blog_carousel'  => 'yes'
                ]
            ]
        );
        
        $this->add_control('premium_blog_carousel_arrows',
            [
                'label'         => __('Navigation Arrows', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'default'       => 'yes',
                'condition'     => [
                    'premium_blog_carousel'  => 'yes'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_blog_carousel_arrows_pos',
            [
                'label'         => __('Arrows Position', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', "em"],
                'range'         => [
                    'px'    => [
                        'min'       => -100, 
                        'max'       => 100,
                    ],
                    'em'    => [
                        'min'       => -10, 
                        'max'       => 10,
                    ],
                ],
                'condition'		=> [
					'premium_blog_carousel'         => 'yes',
                    'premium_blog_carousel_arrows'  => 'yes'
				],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-wrap a.carousel-arrow.carousel-next' => 'right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .premium-blog-wrap a.carousel-arrow.carousel-prev' => 'left: {{SIZE}}{{UNIT}};',
                ]
            ]
        );
        
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_blog_pagination_section',
            [
                'label'         => __('Pagination', 'premium-addons-for-elementor')
            ]
        );
        
        $this->add_control('premium_blog_paging',
            [
                'label'         => __('Enable Pagination', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'description'   => __('Pagination is the process of dividing the posts into discrete pages','premium-addons-for-elementor'),
            ]
        );
        
        $this->add_control('premium_blog_total_posts_number',
            [
                'label'         => __('Total Number of Posts', 'premium-addons-for-elementor'),
                'description'   => __('Set the number of posts in all pages','premium-addons-for-elementor'),
                'type'          => Controls_Manager::NUMBER,
                'default'       => wp_count_posts()->publish,
                'min'			=> 1,
                'condition'     => [
                    'premium_blog_paging'      => 'yes',
                ]
            ]
        );
        
        $this->add_control('pagination_strings',
            [
                'label'         => __('Enable Pagination Next/Prev Strings', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'default'       => 'yes',
                'condition'     => [
                    'premium_blog_paging'   => 'yes'
                ]
            ]
        );
        
        $this->add_control('premium_blog_prev_text',
			[
				'label'			=> __( 'Previous Page String', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::TEXT,
                'default'       => __('Previous','premium-addons-for-elementor'),
                'condition'     => [
                    'premium_blog_paging'   => 'yes',
                    'pagination_strings'    => 'yes'
                ]
			]
		);

        $this->add_control('premium_blog_next_text',
			[
				'label'			=> __( 'Next Page String', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::TEXT,
                'default'       => __('Next','premium-addons-for-elementor'),
                'condition'     => [
                    'premium_blog_paging'   => 'yes',
                    'pagination_strings'    => 'yes'
                ]
			]
		);
        
        $this->add_responsive_control('premium_blog_pagination_align',
            [
                'label'         => __( 'Alignment', 'premium-addons-for-elementor' ),
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
                'selectors_dictionary'  => [
                    'left'      => 'flex-start',
                    'center'    => 'center',
                    'right'     => 'flex-end',
                ],
                'default'       => 'right',
                'condition'     => [
                    'premium_blog_paging'      => 'yes',
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-pagination-container .page-numbers' => 'justify-content: {{VALUE}};',
                ]
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_blog_image_style_section',
            [
                'label'         => __('Image', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
                'condition'     => $featured_image_conditions
            ]
        );
        
        $this->add_control('premium_blog_plus_color',
            [
                'label'         => __('Plus Sign Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-thumbnail-container:before, {{WRAPPER}} .premium-blog-thumbnail-container:after' => 'background-color: {{VALUE}};',
                ],
                'condition'     => [
                    'premium_blog_skin!' => 'classic'
                ]
            ]
        );
        
        $this->add_control('premium_blog_overlay_color',
            [
                'label'         => __('Overlay Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-framed-effect, {{WRAPPER}} .premium-blog-bordered-effect, {{WRAPPER}} .premium-blog-squares-effect:before,{{WRAPPER}} .premium-blog-squares-effect:after,{{WRAPPER}} .premium-blog-squares-square-container:before,{{WRAPPER}} .premium-blog-squares-square-container:after, {{WRAPPER}} .premium-blog-format-container:hover, {{WRAPPER}} .premium-blog-thumbnail-overlay' => 'background-color: {{VALUE}};',
                ]
            ]
        );
        
        $this->add_control('premium_blog_border_effect_color',
            [
                'label'         => __('Border Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'condition'     => [
                    'premium_blog_hover_color_effect'  => 'bordered',
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-post-link:before, {{WRAPPER}} .premium-blog-post-link:after' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'css_filters',
                'selector' => '{{WRAPPER}} .premium-blog-thumbnail-container img',
            ]
        );
        
        $this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'      => 'hover_css_filters',
                'label'     => __('Hover CSS Filters', 'premium-addons-for-elementor'),
				'selector'  => '{{WRAPPER}} .premium-blog-post-container:hover .premium-blog-thumbnail-container img'
			]
		);
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_blog_format_style_section',
            [
                'label'         => __('Post Format Icon', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'premium_blog_post_format_icon' => 'yes'
                ]
            ]
        );
        
        $this->add_control('premium_blog_format_icon_size',
            [
                'label'         => __('Size', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'description'   => __('Choose icon size in (PX, EM)', 'premium-addons-for-elementor'),
                'range'         => [
                    'em'    => [
                        'min'       => 1,
                        'max'       => 10,
                    ],
                ],
                'size_units'    => ['px', "em"],
                'label_block'   => true,
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-format-icon, {{WRAPPER}} .premium-blog-thumbnail-overlay i' => 'font-size: {{SIZE}}{{UNIT}};',
                ]
            ]
        );
        
        $this->add_control('premium_blog_format_icon_color',
            [
                'label'         => __('Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-format-container i, {{WRAPPER}} .premium-blog-thumbnail-overlay i'  => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control('premium_blog_format_icon_hover_color',
            [
                'label'         => __('Hover Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-format-container:hover i, {{WRAPPER}} .premium-blog-thumbnail-overlay i:hover'  => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control('premium_blog_format_back_color',
            [
                'label'         => __('Background Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-format-container, {{WRAPPER}} .premium-blog-thumbnail-overlay i'  => 'background-color: {{VALUE}};',
                ]
            ]
        );
        
        $this->add_control('premium_blog_format_back_hover_color',
            [
                'label'         => __('Hover Background Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-format-container:hover, {{WRAPPER}} .premium-blog-thumbnail-overlay i:hover'  => 'background-color: {{VALUE}};',
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'          => 'premium_blog_format_border',
                'selector'      => '{{WRAPPER}} .premium-blog-thumbnail-overlay i',
            ]
        );
        
        $this->add_control('premium_blog_format_border_radius',
            [
                'label'         => __('Border Radius', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%' ,'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-thumbnail-overlay i' => 'border-radius: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_blog_format_padding',
            [
                'label'         => __('Padding', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-thumbnail-overlay i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                'condition'     => [
                    'premium_blog_skin' => 'classic'
                ]
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_blog_title_style_section',
            [
                'label'         => __('Title', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'          => 'premium_blog_title_typo',
                'selector'      => '{{WRAPPER}} .premium-blog-entry-title, {{WRAPPER}} .premium-blog-entry-title a',
            ]
        );
        
        $this->add_control('premium_blog_title_color',
            [
                'label'         => __('Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-entry-title a'  => 'color: {{VALUE}};',
                ]
            ]
        );
        
        $this->add_control('premium_blog_title_hover_color',
            [
                'label'         => __('Hover Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-entry-title:hover a'  => 'color: {{VALUE}};',
                ]
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_blog_meta_style_section',
            [
                'label'         => __('Meta', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'          => 'premium_blog_meta_typo',
                'selector'      => '{{WRAPPER}} .premium-blog-entry-meta a',
            ]
        );
        
        $this->add_control('premium_blog_meta_color',
            [
                'label'         => __('Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-entry-meta, {{WRAPPER}} .premium-blog-entry-meta a'  => 'color: {{VALUE}};',
                ]
            ]
        );
        
        $this->add_control('premium_blog_meta_hover_color',
            [
                'label'         => __('Hover Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-entry-meta a:hover, {{WRAPPER}} .premium-blog-entry-meta span:hover'  => 'color: {{VALUE}};',
                ]
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_blog_tags_style_section',
            [
                'label'         => __('Tags', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'          => 'premium_blog_tags_typo',
                'selector'      => '{{WRAPPER}} .premium-blog-post-tags-container a',
            ]
        );
        
        $this->add_control('premium_blog_tags_color',
            [
                'label'         => __('Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-post-tags-container, {{WRAPPER}} .premium-blog-post-tags-container a'  => 'color: {{VALUE}};',
                ]
            ]
        );
        
        $this->add_control('premium_blog_tags_hoer_color',
            [
                'label'         => __('Hover Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-post-tags-container a:hover'  => 'color: {{VALUE}};',
                ]
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_blog_content_style_section',
            [
                'label'         => __('Content Box', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'          => 'premium_blog_content_typo',
                'selector'      => '{{WRAPPER}} .premium-blog-post-content',
            ]
        );
        
        $this->add_control('premium_blog_post_content_color',
            [
                'label'         => __('Text Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_3,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-post-content'  => 'color: {{VALUE}};',
                ]
            ]
        );
        
        $this->add_control('premium_blog_content_background_color',
            [
                'label'         => __('Background Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'default'       => '#f5f5f5',
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-content-wrapper'  => 'background-color: {{VALUE}};',
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'          => 'premium_blog_box_shadow',
                'selector'      => '{{WRAPPER}} .premium-blog-content-wrapper',
            ]
        );
        
        $this->add_responsive_control('prmeium_blog_content_margin',
            [
                'label'         => __('Margin', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-content-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_responsive_control('prmeium_blog_content_padding',
            [
                'label'         => __('Padding', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-content-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_blog_box_style_section',
            [
                'label'         => __('Box', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control('premium_blog_box_background_color',
            [
                'label'         => __('Background Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-post-container'  => 'background-color: {{VALUE}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'          => 'box_border',
                'selector'      => '{{WRAPPER}} .premium-blog-post-container',
            ]
        );
        
        $this->add_control('box_border_radius',
            [
                'label'         => __('Border Radius', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%' ,'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-post-container' => 'border-radius: {{SIZE}}{{UNIT}};'
                ]
            ]
        );

        $this->add_responsive_control('prmeium_blog_box_padding',
            [
                'label'         => __('Spacing', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-post-outer-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_responsive_control('prmeium_blog_inner_box_padding',
            [
                'label'         => __('Padding', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-post-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_blog_pagination_Style',
            [
                'label'         => __('Pagination', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'premium_blog_paging'   => 'yes',
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'              => 'premium_blog_pagination_typo',
                'selector'          => '{{WRAPPER}} .premium-blog-pagination-container li > .page-numbers',
            ]
        );
        
        $this->start_controls_tabs('premium_blog_pagination_colors');
        
        $this->start_controls_tab('premium_blog_pagination_nomral',
            [
                'label'         => __('Normal', 'premium-addons-for-elementor'),
                
            ]
        );
        
        $this->add_control('prmeium_blog_pagination_color', 
            [
                'label'         => __('Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-pagination-container li .page-numbers' => 'color: {{VALUE}};'
                ]
            ]
        );
        
        $this->add_control('prmeium_blog_pagination_back_color', 
            [
                'label'         => __('Background Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-pagination-container li .page-numbers' => 'background-color: {{VALUE}};'
                ]
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab('premium_blog_pagination_hover',
            [
                'label'         => __('Hover', 'premium-addons-for-elementor'),
                
            ]
        );
        
        $this->add_control('prmeium_blog_pagination_hover_color', 
            [
                'label'         => __('Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-pagination-container li .page-numbers:hover' => 'color: {{VALUE}};'
                ]
            ]
        );
        
        $this->add_control('prmeium_blog_pagination_back_hover_color', 
            [
                'label'         => __('Background Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-pagination-container li .page-numbers:hover' => 'background-color: {{VALUE}};'
                ]
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab('premium_blog_pagination_active',
            [
                'label'         => __('Active', 'premium-addons-for-elementor'),
                
            ]
        );
        
        $this->add_control('prmeium_blog_pagination_active_color', 
            [
                'label'         => __('Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-pagination-container li span.current' => 'color: {{VALUE}};'
                ]
            ]
        );
        
        $this->add_control('prmeium_blog_pagination_back_active_color', 
            [
                'label'         => __('Background Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-pagination-container li span.current' => 'background-color: {{VALUE}};'
                ]
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'          => 'premium_blog_border',
                'separator'     => 'before',
                'selector'      => '{{WRAPPER}} .premium-blog-pagination-container li .page-numbers',
            ]
        );
        
        $this->add_control('premium_blog_border_radius',
                [
                    'label'         => __('Border Radius', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%' ,'em'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-blog-pagination-container li .page-numbers' => 'border-radius: {{SIZE}}{{UNIT}};'
                    ]
                ]
                );
        
        $this->add_responsive_control('prmeium_blog_pagination_margin',
            [
                'label'         => __('Margin', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-pagination-container li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_responsive_control('prmeium_blog_pagination_padding',
            [
                'label'         => __('Padding', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-pagination-container li .page-numbers' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('carousel_dots_style',
            [
                'label'         => __('Carousel Dots', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'premium_blog_carousel'         => 'yes',
                    'premium_blog_carousel_dots'  => 'yes'
                ]
            ]
        );
        
        $this->add_control('carousel_dot_navigation_color',
			[
				'label' 		=> __( 'Color', 'premium-addons-for-elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'scheme' 		=> [
				    'type' 	=> Scheme_Color::get_type(),
				    'value' => Scheme_Color::COLOR_2,
				],
				'selectors'		=> [
					'{{WRAPPER}} ul.slick-dots li' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_control('carousel_dot_navigation_active_color',
			[
				'label' 		=> __( 'Active Color', 'premium-addons-for-elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'scheme' 		=> [
				    'type' 	=> Scheme_Color::get_type(),
				    'value' => Scheme_Color::COLOR_1,
				],
				'selectors'		=> [
					'{{WRAPPER}} ul.slick-dots li.slick-active' => 'color: {{VALUE}}'
				]
			]
		);
        
        $this->end_controls_section();
        
        $this->start_controls_section('carousel_arrows_style',
            [
                'label'         => __('Carousel Arrows', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'premium_blog_carousel'         => 'yes',
                    'premium_blog_carousel_arrows'  => 'yes'
                ]
            ]
        );
        
        $this->add_control('arrow_color',
            [
                'label'         => __('Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-wrap .slick-arrow' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_responsive_control('premium_blog_carousel_arrow_size',
            [
                'label'         => __('Size', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%' ,'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-wrap .slick-arrow i' => 'font-size: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_control('premium_blog_carousel_arrow_background',
            [
                'label'         => __('Background Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-wrap .slick-arrow' => 'background-color: {{VALUE}};',
                ]
            ]
        );
        
        $this->add_control('premium_blog_carousel_border_radius',
            [
                'label'         => __('Border Radius', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%' ,'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-wrap .slick-arrow' => 'border-radius: {{SIZE}}{{UNIT}};'
                ]
            ]
        );

        $this->add_control('premium_blog_carousel_arrow_padding',
            [
                'label'         => __('Padding', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%' ,'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-wrap .slick-arrow' => 'padding: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_blog_read_more_style',
            [
                'label'         => __('Call to Action', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'premium_blog_excerpt'      => 'yes',
                    'premium_blog_excerpt_type' => 'link'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'          => 'premium_blog_read_more_typo',
                'selector'      => '{{WRAPPER}} .premium-blog-post-content .premium-blog-excerpt-link',
            ]
        );
        
        $this->add_responsive_control('read_more_spacing',
            [
                'label'             => __('Spacing', 'premium-addons-for-elementor'),
                'type'              => Controls_Manager::SLIDER,
                'selectors'         => [
                    '{{WRAPPER}} .premium-blog-post-content .premium-blog-excerpt-link'  => 'margin-top: {{SIZE}}px',
                ]
            ]
        );
        
        $this->start_controls_tabs('read_more_style_tabs');
        
        $this->start_controls_tab('read_more_tab_normal',
            [
                'label'         => __('Normal', 'premium-addons-for-elementor'),
                
            ]
        );
        
         $this->add_control('premium_blog_read_more_color',
            [
                'label'         => __('Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-post-content .premium-blog-excerpt-link'  => 'color: {{VALUE}};',
                ]
            ]
        );
         
        $this->add_control('read_more_background_color',
            [
                'label'         => __('Background Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-post-content .premium-blog-excerpt-link'  => 'background-color: {{VALUE}};',
                ]
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab('read_more_tab_hover',
            [
                'label'         => __('Hover', 'premium-addons-for-elementor'),
            ]
        );
        
        $this->add_control('premium_blog_read_more_hover_color',
            [
                'label'         => __('Hover Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-post-content .premium-blog-excerpt-link:hover'  => 'color: {{VALUE}};',
                ]
            ]
        );
        
        $this->add_control('read_more_hover_background_color',
            [
                'label'         => __('Hover Background Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-post-content .premium-blog-excerpt-link:hover'  => 'background-color: {{VALUE}};',
                ]
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'              => 'read_more_border',
                'separator'         => 'before',
                'selector'          => '{{WRAPPER}} .premium-blog-post-content .premium-blog-excerpt-link',
            ]
        );

        $this->add_control('read_more_border_radius',
            [
                'label'             => __('Border Radius', 'premium-addons-for-elementor'),
                'type'              => Controls_Manager::SLIDER,
                'size_units'        => ['px','em','%'],
                'selectors'         => [
                    '{{WRAPPER}} .premium-blog-post-content .premium-blog-excerpt-link'  => 'border-radius: {{SIZE}}{{UNIT}};',
                ]
            ]
        );
        
        $this->add_responsive_control('read_more_padding',
            [
                'label'             => __('Padding', 'premium-addons-for-elementor'),
                'type'              => Controls_Manager::DIMENSIONS,
                'size_units'        => ['px', 'em', '%'],
                'selectors'         => [
                    '{{WRAPPER}} .premium-blog-post-content .premium-blog-excerpt-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_blog_filter_style',
            [
                'label'     => __('Filter','premium-addons-for-elementor'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'premium_blog_cat_tabs'    => 'yes'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'          => 'premium_blog_filter_typo',
                'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                'selector'      => '{{WRAPPER}} .premium-blog-cats-container li a.category',
            ]
        );

        $this->start_controls_tabs('tabs_filter');

        $this->start_controls_tab('tab_filter_normal',
            [
                'label'         => __('Normal', 'premium-addons-for-elementor'),
            ]
        );

        $this->add_control('premium_blog_filter_color',
            [
                'label'         => __('Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-cats-container li a.category span' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control('premium_blog_background_color',
           [
               'label'         => __('Background Color', 'premium-addons-for-elementor'),
               'type'          => Controls_Manager::COLOR,
               'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
               'selectors'     => [
                   '{{WRAPPER}} .premium-blog-cats-container li a.category' => 'background-color: {{VALUE}};',
               ],
           ]
       );

       $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'              => 'premium_blog_filter_border',
                'selector'          => '{{WRAPPER}} .premium-blog-cats-container li a.category',
            ]
        );

        $this->add_control('premium_blog_filter_border_radius',
            [
                'label'             => __('Border Radius', 'premium-addons-for-elementor'),
                'type'              => Controls_Manager::SLIDER,
                'size_units'        => ['px','em','%'],
                'selectors'         => [
                    '{{WRAPPER}} .premium-blog-cats-container li a.category'  => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
                'separator'         => 'after'
            ]
        );

       $this->end_controls_tab();

       $this->start_controls_tab('tab_filter_active',
            [
                'label'         => __('Active', 'premium-addons-for-elementor'),
            ]
        );
        
        $this->add_control('premium_blog_filter_active_color',
            [
                'label'         => __('Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-blog-cats-container li a.active span' => 'color: {{VALUE}};',
                ]
            ]
        );
        
        $this->add_control('premium_blog_background_active_color',
            [
               'label'         => __('Background Color', 'premium-addons-for-elementor'),
               'type'          => Controls_Manager::COLOR,
               'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                   '{{WRAPPER}} .premium-blog-cats-container li a.active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'              => 'filter_active_border',
                'selector'          => '{{WRAPPER}} .premium-blog-cats-container li a.active',
            ]
        );

        $this->add_control('filter_active_border_radius',
            [
                'label'             => __('Border Radius', 'premium-addons-for-elementor'),
                'type'              => Controls_Manager::SLIDER,
                'size_units'        => ['px','em','%'],
                'selectors'         => [
                    '{{WRAPPER}} .premium-blog-cats-container li a.active'  => 'border-radius: {{SIZE}}{{UNIT}}',
                ],
                'separator'         => 'after'
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'          => 'premium_blog_filter_shadow',
                'selector'      => '{{WRAPPER}} .premium-blog-cats-container li a.category'
            ]
        );
        
        $this->add_responsive_control('premium_blog_filter_margin',
                [
                    'label'             => __('Margin', 'premium-addons-for-elementor'),
                    'type'              => Controls_Manager::DIMENSIONS,
                    'size_units'        => ['px', 'em', '%'],
                    'selectors'             => [
                        '{{WRAPPER}} .premium-blog-cats-container li a.category' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control('premium_blog_filter_padding',
                [
                    'label'             => __('Padding', 'premium-addons-for-elementor'),
                    'type'              => Controls_Manager::DIMENSIONS,
                    'size_units'        => ['px', 'em', '%'],
                'selectors'             => [
                    '{{WRAPPER}} .premium-blog-cats-container li a.category' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
       
    }
    
    /*
     * Renders post content
     * 
     * @since 3.0.5
     * @access protected
     */
    protected function get_post_content() {
        
        $settings = $this->get_settings();
        
        if ( 'yes' !== $settings['premium_blog_excerpt'] ) {
            return;
        }
        
        $src = $settings['content_source'];
        
        $excerpt_type = $settings['premium_blog_excerpt_type'];
        $excerpt_text = $settings['premium_blog_excerpt_text'];
        
        $length = ! empty( $settings['premium_blog_excerpt_length'] ) ? $settings['premium_blog_excerpt_length'] : 55;
        
    ?>
        <div class="premium-blog-post-content" style="<?php if ( $settings['premium_blog_post_format_icon'] !== 'yes' ) : echo 'margin-left:0px;'; endif; ?>">
            <?php
                echo premium_blog_get_excerpt_by_id( $src, $length, $excerpt_type, $excerpt_text );
            ?>
        </div>
    <?php
    }

    /*
     * Renders post format icon
     * 
     * @since 3.0.5
     * @access protected
     */
    protected function get_post_format_icon() {
        
        $post_format = get_post_format();
        
        switch( $post_format ) {
            case 'aside':
                $post_format = 'file-text-o';
                break;
            case 'audio':
                $post_format = 'music';
                break;
            case 'gallery':
                $post_format = 'file-image-o';
                break;
            case 'image':
                $post_format = 'picture-o';
                break;
            case 'link':
                $post_format = 'link';
                break;
            case 'quote':
                $post_format = 'quote-left';
                break;
            case 'video':
                $post_format = 'video-camera';
                break;
            default: 
                $post_format = 'thumb-tack';
        }
    ?>
        <i class="premium-blog-format-icon fa fa-<?php echo $post_format; ?>"></i>
    <?php 
    }
    
    /*
     * Get Post Thumbnail
     * 
     * 
     * Renders HTML markup for post thumbnail
     * 
     * @since 3.0.5
     * @access protected
     * 
     * @param $target string link target
     */
    protected function get_post_thumbnail( $target ) {
        
        $settings = $this->get_settings_for_display();
        
        $skin = $settings['premium_blog_skin'];
        
        $settings['featured_image']      = [
			'id' => get_post_thumbnail_id(),
		];
        
        $thumbnail_html = Group_Control_Image_Size::get_attachment_image_html( $settings, 'featured_image' );
        
        if( empty( $thumbnail_html ) )
            return;
        
        if( 'classic' !== $skin ): ?>
            <a href="<?php the_permalink(); ?>" target="<?php echo esc_attr( $target ); ?>">
        <?php endif;
            echo $thumbnail_html;
        if( 'classic' !== $skin ): ?>
            </a>
        <?php endif;
    }

    /*
     * Renders post skin
     * 
     * @since 3.0.5
     * @access protected
     */
    protected function get_post_layout() {
        
        $settings = $this->get_settings();
        
        $image_effect = $settings['premium_blog_hover_image_effect'];
        
        $post_effect = $settings['premium_blog_hover_color_effect'];
        
        if( $settings['premium_blog_new_tab'] == 'yes' ) {
            $target = '_blank';
        } else {
            $target = '_self';
        }
        
        $skin = $settings['premium_blog_skin'];
        
        $post_id = get_the_ID();
        
        $key = 'post_' . $post_id;
        
        $tax_key = sprintf( '%s_tax', $key );
        
        $wrap_key = sprintf( '%s_wrap', $key );
        
        $content_key = sprintf( '%s_content', $key );
        
        $this->add_render_attribute( $tax_key, 'class', 'premium-blog-post-outer-container' );
        
        $this->add_render_attribute( $wrap_key, 'class', [ 
            'premium-blog-post-container',
            'premium-blog-skin-' . $skin,
        ] );
        
        $thumb = ( ! has_post_thumbnail() || 'yes' !== $settings['show_featured_image'] ) ? 'empty-thumb' : '';
        
        if ( 'yes' === $settings['premium_blog_cat_tabs'] && 'yes' !== $settings['premium_blog_carousel'] ) {
            
            $filter_rule = $settings['filter_tabs_type'];
            
            $taxonomies = 'categories' === $filter_rule ? get_the_category( $post_id ) : get_the_tags( $post_id );
            
            if( ! empty( $taxonomies ) ) {
                foreach( $taxonomies as $index => $taxonomy ) {
                
                    $taxonomy_key = 'categories' === $filter_rule ? $taxonomy->cat_name : $taxonomy->name;

                    $attr_key = str_replace( ' ', '-', $taxonomy_key );

                    $this->add_render_attribute( $tax_key, 'class', strtolower( $attr_key ) );
                }
            }
            
            
        }
        
        $this->add_render_attribute( $content_key, 'class', [ 
            'premium-blog-content-wrapper',
            $thumb,
        ] );
        
    ?>
        <div <?php echo $this->get_render_attribute_string( $tax_key ); ?>>
            <div <?php echo $this->get_render_attribute_string( $wrap_key ); ?>>
                <?php if( 'yes' === $settings['show_featured_image'] ) : ?>
                <div class="premium-blog-thumb-effect-wrapper">
                    <div class="premium-blog-thumbnail-container <?php echo 'premium-blog-' . $image_effect . '-effect';?>">
                        <?php $this->get_post_thumbnail( $target ); ?>
                    </div>
                    <?php if( 'classic' !== $skin ) : ?>
                        <div class="premium-blog-effect-container <?php echo 'premium-blog-'. $post_effect . '-effect'; ?>">
                            <a class="premium-blog-post-link" href="<?php the_permalink(); ?>" target="<?php echo esc_attr( $target ); ?>"></a>
                            <?php if( $settings['premium_blog_hover_color_effect'] === 'squares' ) : ?>
                                <div class="premium-blog-squares-square-container"></div>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="premium-blog-thumbnail-overlay">    
                            <a class="elementor-icon" href="<?php the_permalink(); ?>" target="<?php echo esc_attr( $target ); ?>">
                                <?php if( $settings['premium_blog_post_format_icon'] === 'yes' ) : ?>
                                    <?php echo $this->get_post_format_icon(); ?>
                                <?php endif; ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                <?php if( 'cards' === $skin ) : ?>
                    <div class="premium-blog-author-thumbnail">
                        <?php echo get_avatar( get_the_author_meta( 'ID' ), 128, '', get_the_author_meta( 'display_name' ) ); ?>
                    </div>
                <?php endif; ?>
                <div <?php echo $this->get_render_attribute_string( $content_key ); ?>>
                    <div class="premium-blog-inner-container">
                        <?php if( $settings['premium_blog_post_format_icon'] === 'yes' ) : ?>
                        <div class="premium-blog-format-container">
                            <a class="premium-blog-format-link" href="<?php the_permalink(); ?>" title="<?php if( get_post_format() === ' ') : echo 'standard' ; else : echo get_post_format();  endif; ?>" target="<?php echo esc_attr( $target ); ?>"><?php $this->get_post_format_icon(); ?></a>
                        </div>
                        <?php endif; ?>
                        <div class="premium-blog-entry-container">
                            <?php
                                $this->get_post_title( $target );
                                if ( 'cards' !== $skin ) {
                                    $this->get_post_meta( $target );
                                }

                            ?>

                        </div>
                    </div>

                    <?php
                        $this->get_post_content();
                        if ( 'cards' === $skin ) {
                            $this->get_post_meta( $target );
                        }
                        
                    ?>
                    
                    <?php if( $settings['premium_blog_tags_meta'] === 'yes' && has_tag() ) : ?>
                        <div class="premium-blog-post-tags-container" style="<?php if( $settings['premium_blog_post_format_icon'] !== 'yes' ) : echo 'margin-left:0px;'; endif; ?>">
                            <span class="premium-blog-post-tags">
                                <i class="fa fa-tags fa-fw"></i>
                                    <?php the_tags(' ', ', '); ?>
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    <?php }
    
    /*
     * Render post title
     * 
     * @since 3.4.4
     * @access protected
     */
    protected function get_post_title( $link_target ) {
        
        $settings = $this->get_settings_for_display();
        
        $this->add_render_attribute( 'title', 'class', 'premium-blog-entry-title' );
        
    ?>
        
        <<?php echo $settings['premium_blog_title_tag'] . ' ' . $this->get_render_attribute_string('title'); ?>>
            <a href="<?php the_permalink(); ?>" target="<?php echo esc_attr( $link_target ); ?>">
                <?php the_title(); ?>
            </a>
        </<?php echo $settings['premium_blog_title_tag']; ?>>
        
    <?php   
    }
    
    /*
     * Get Post Meta
     * 
     * @since 3.4.4
     * @access protected
     */
    protected function get_post_meta( $link_target ) {
        
        $settings = $this->get_settings();

        $author_meta = $settings['premium_blog_author_meta'];
        
        $data_meta = $settings['premium_blog_date_meta'];

        $categories_meta = $settings['premium_blog_categories_meta'];

        $comments_meta = $settings['premium_blog_comments_meta'];

        if( 'yes' === $data_meta ) {
            $date_format = get_option('date_format');
        }
        
        if( 'yes' === $comments_meta ) {

            $comments_strings = [
                'no-comments'           => __( 'No Comments', 'premium-addons-for-elementor' ),
				'one-comment'           => __( '1 Comment', 'premium-addons-for-elementor' ),
				'multiple-comments'     => __( '% Comments', 'premium-addons-for-elementor' ),
            ];

        }

        
        
    ?>
        
        <div class="premium-blog-entry-meta" style="<?php if( $settings['premium_blog_post_format_icon'] !== 'yes' ) : echo 'margin-left:0px'; endif; ?>">
        
            <?php if( $author_meta === 'yes' ) : ?>
                <span class="premium-blog-post-author premium-blog-meta-data">
                    <i class="fa fa-user fa-fw"></i><?php the_author_posts_link(); ?>
                </span>
            <?php endif; ?>

            <?php if( $data_meta === 'yes' ) : ?>
                <span class="premium-blog-meta-separator">|</span>
                <span class="premium-blog-post-time premium-blog-meta-data">
                    <i class="fa fa-calendar fa-fw"></i>
                    <span><?php the_time( $date_format ); ?></span>
                </span>
            <?php endif; ?>

            <?php if( $categories_meta === 'yes' ) : ?>
                <span class="premium-blog-meta-separator">|</span>
                <span class="premium-blog-post-categories premium-blog-meta-data">
                    <i class="fa fa-align-left fa-fw"></i>
                    <?php the_category(', '); ?>
                </span>
            <?php endif; ?>

            <?php if( $comments_meta === 'yes' ) : ?>
                <span class="premium-blog-meta-separator">|</span>
                <span class="premium-blog-post-comments premium-blog-meta-data">
                    <i class="fa fa-comments-o fa-fw"></i>
                    <span>
                        <?php comments_popup_link( $comments_strings['no-comments'], $comments_strings['one-comment'], $comments_strings['multiple-comments'] ); ?>
                    </span>
                </span>
            <?php endif; ?>
        </div>
        
    <?php
    }
    
    /*
     * Get Filter Tabs Markup
     * 
     * @since 3.11.2
     * @access protected
     */
    protected function get_filter_tabs_markup() {
        
        $settings = $this->get_settings();
        
        $filter_rule = $settings['filter_tabs_type'];
        
        $filters = 'categories' === $filter_rule ? $settings['premium_blog_categories'] : $settings['premium_blog_tags'];
        
        if( empty( $filters ) )
            return;
        
        ?>
        <div class="premium-blog-filter">
            <ul class="premium-blog-cats-container">
                <?php if( ! empty( $settings['premium_blog_tab_label'] ) ) : ?>
                    <li>
                        <a href="javascript:;" class="category active" data-filter="*">
                            <span><?php echo esc_html( $settings['premium_blog_tab_label'] ); ?></span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php foreach( $filters as $index => $filter ) {
                        $key = 'blog_category_' . $index;

                        if( 'categories' === $filter_rule ) {
                            $name = get_cat_name( $filter );
                        } else {
                            $tag = get_tag( $filter );
                            
                            $name = ucfirst( $tag->name );
                        }
                        
                        $name_filter = str_replace(' ', '-', $name );
                        $name_lower = strtolower( $name_filter );

                        $this->add_render_attribute( $key,
                            'class', [
                                'category'
                            ]
                        );

                        if( empty( $settings['premium_blog_tab_label'] ) && 0 === $index ) {
                            $this->add_render_attribute( $key,
                                'class', [
                                    'active'
                                ]
                            );
                        }
                    ?>
                        <li>
                            <a href="javascript:;" <?php echo $this->get_render_attribute_string( $key ); ?> data-filter=".<?php echo esc_attr( $name_lower ); ?>">
                                <span><?php echo $name; ?></span>
                            </a>
                        </li>
                <?php } ?>
            </ul>
        </div>
        <?php
    }

    /**
	 * Render Blog output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
    protected function render() {
        
        $paged = max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) );
        
        $settings = $this->get_settings();

        $offset = ! empty( $settings['premium_blog_offset'] ) ? $settings['premium_blog_offset'] : 0;
        
        $post_per_page = ! empty( $settings['premium_blog_number_of_posts'] ) ? $settings['premium_blog_number_of_posts'] : 3;
        
        $new_offset = $offset + ( ( $paged - 1 ) * $post_per_page );
        
        $post_args = premium_blog_get_post_settings( $settings );

        $posts = premium_blog_get_post_data( $post_args, $paged , $new_offset );

        if( ! count( $posts ) ) {

            $this->get_empty_query_message();
            return;

        }
        
        switch( $settings['premium_blog_columns_number'] ) {
            case '100%' :
                $col_number = 'col-1';
                break;
            case '50%' :
                $col_number = 'col-2';
                break;
            case '33.33%' :
                $col_number = 'col-3';
                break;
            case '25%' :
                $col_number = 'col-4';
                break;
        }
        
        $carousel = 'yes' == $settings['premium_blog_carousel'] ? true : false; 
        
        $this->add_render_attribute('blog', [
            'class'=> [
                'premium-blog-wrap',
                'premium-blog-' . $settings['premium_blog_layout'],
                'premium-blog-' . $col_number
            ],
            'data-layout'   => $settings['premium_blog_layout'],
            'data-equal'    => $settings['force_height']
        ]);
        
        if ( $carousel ) {
            
            $play   = 'yes' === $settings['premium_blog_carousel_play'] ? true : false;
            $fade   = 'yes' === $settings['premium_blog_carousel_fade'] ? 'true' : 'false';
            $arrows = 'yes' === $settings['premium_blog_carousel_arrows'] ? 'true' : 'false';
            $grid   = 'yes' === $settings['premium_blog_grid'] ? 'true' : 'false';
            $center_mode   = 'yes' === $settings['premium_blog_carousel_center'] ? 'true' : 'false';
            $spacing   = intval( $settings['premium_blog_carousel_spacing'] );
            
            $speed  = ! empty( $settings['premium_blog_carousel_autoplay_speed'] ) ? $settings['premium_blog_carousel_autoplay_speed'] : 5000;
            $dots   = 'yes' === $settings['premium_blog_carousel_dots'] ? 'true' : 'false';

            $columns = intval ( 100 / substr( $settings['premium_blog_columns_number'], 0, strpos( $settings['premium_blog_columns_number'], '%') ) );
            
            $columns_tablet = intval ( 100 / substr( $settings['premium_blog_columns_number_tablet'], 0, strpos( $settings['premium_blog_columns_number_tablet'], '%') ) );

            $columns_mobile = intval ( 100 / substr( $settings['premium_blog_columns_number_mobile'], 0, strpos( $settings['premium_blog_columns_number_mobile'], '%') ) );

            $carousel_settings = [
                'data-carousel' => $carousel,
                'data-grid' => $grid,
                'data-fade' => $fade,
                'data-play' => $play,
                'data-center' => $center_mode,
                'data-slides-spacing' => $spacing,
                'data-speed' => $speed,
                'data-col' => $columns,
                'data-col-tablet' => $columns_tablet,
                'data-col-mobile' => $columns_mobile,
                'data-arrows' => $arrows,
                'data-dots' => $dots
            ];

            $this->add_render_attribute('blog', $carousel_settings );
        
            
        }
        
    ?>
    <div class="premium-blog">
        <?php if ( 'yes' === $settings['premium_blog_cat_tabs'] && 'yes' !== $settings['premium_blog_carousel'] ) : ?>
            <?php $this->get_filter_tabs_markup(); ?>
        <?php endif; ?>
        <div <?php echo $this->get_render_attribute_string('blog'); ?>>

            <?php

            if( count( $posts ) ) {
                global $post;
                foreach( $posts as $post ) {
                    setup_postdata( $post );
                    $this->get_post_layout();
                }
            ?>
        </div>
    </div>
    <?php if ( $settings['premium_blog_paging'] === 'yes' ) : ?>
        <div class="premium-blog-pagination-container">
            <?php 
            $count_posts = wp_count_posts();
            $published_posts = $count_posts->publish;
            
            $total_posts = ! empty ( $settings['premium_blog_total_posts_number'] ) ? $settings['premium_blog_total_posts_number'] : $published_posts;
            
            if( $total_posts > $published_posts )
                $total_posts = $published_posts;
            
            $page_tot = ceil( ( $total_posts - $offset ) / $settings['premium_blog_number_of_posts'] );
            
            if ( $page_tot > 1 ) {
                $big        = 999999999;
                echo paginate_links( 
                    array(
                        'base'      => str_replace( $big, '%#%',get_pagenum_link( 999999999, false ) ),
                        'format'    => '?paged=%#%',
                        'current'   => $paged,
                        'total'     => $page_tot,
                        'prev_next' => 'yes' === $settings['pagination_strings'] ? true : false,
                        'prev_text' => sprintf( "&lsaquo; %s", $settings['premium_blog_prev_text'] ),
                        'next_text' => sprintf( "%s &rsaquo;", $settings['premium_blog_next_text'] ),
                        'end_size'  => 1,
                        'mid_size'  => 2,
                        'type'      => 'list'
                    ));
                }
            ?>
        </div>
    <?php endif;
            wp_reset_postdata();
            
            if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) {

                if ( 'masonry' === $settings['premium_blog_layout'] && 'yes' !== $settings['premium_blog_carousel'] ) {
                    $this->render_editor_script();
                }
            }

        }
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

				$( '.premium-blog-wrap' ).each( function() {

                    var $node_id 	= '<?php echo $this->get_id(); ?>',
                        scope 		= $( '[data-id="' + $node_id + '"]' ),
                        selector 	= $(this);
                    
					if ( selector.closest( scope ).length < 1 ) {
						return;
					}
					
                    var masonryArgs = {
                        itemSelector	: '.premium-blog-post-outer-container',
                        percentPosition : true,
                        layoutMode		: 'masonry',
                    };

                    var $isotopeObj = {};

                    selector.imagesLoaded( function() {

                        $isotopeObj = selector.isotope( masonryArgs );

                        selector.find('.premium-blog-post-outer-container').resize( function() {
                            $isotopeObj.isotope( 'layout' );
                        });
                    });

				});
			});
		</script>
		<?php
    }

    /*
     * Get Empty Query Message
     * 
     * Written in PHP and used to generate the final HTML when the query is empty
     * 
     * @since 3.20.3
     * @access protected
     * 
     */
    protected function get_empty_query_message() {
        ?>
        <div class="premium-error-notice">
            <?php echo __('The current query has no posts. Please make sure you have published items matching your query.','premium-addons-for-elementor'); ?>
        </div>
        <?php
    }
    
}
