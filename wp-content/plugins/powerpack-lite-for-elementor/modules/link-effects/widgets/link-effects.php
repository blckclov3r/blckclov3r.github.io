<?php
namespace PowerpackElementsLite\Modules\LinkEffects\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Link Effects Widget
 */
class Link_Effects extends Powerpack_Widget {

	/**
	 * Retrieve link effects widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return parent::get_widget_name( 'Link_Effects' );
	}

	/**
	 * Retrieve link effects widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return parent::get_widget_title( 'Link_Effects' );
	}

	/**
	 * Retrieve link effects widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return parent::get_widget_icon( 'Link_Effects' );
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 1.4.13.1
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return parent::get_widget_keywords( 'Link_Effects' );
	}

	/**
	 * Register link effects widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function _register_controls() {

		/*
		-----------------------------------------------------------------------------------*/
		/*
		  CONTENT TAB
		/*-----------------------------------------------------------------------------------*/

		/**
		 * Content Tab: Link Effects
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_link_effects',
			array(
				'label' => __( 'Link Effects', 'powerpack' ),
			)
		);

		$this->add_control(
			'text',
			array(
				'label'       => __( 'Text', 'powerpack' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => __( 'Click Here', 'powerpack' ),
			)
		);

		$this->add_control(
			'secondary_text',
			array(
				'label'     => __( 'Secondary Text', 'powerpack' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => array(
					'active' => true,
				),
				'default'   => __( 'Click Here', 'powerpack' ),
				'condition' => array(
					'effect' => 'effect-9',
				),
			)
		);

		$this->add_control(
			'link',
			array(
				'label'       => __( 'Link', 'powerpack' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => array(
					'active' => true,
				),
				'placeholder' => 'https://www.your-link.com',
				'default'     => array(
					'url' => '#',
				),
			)
		);

		$this->add_control(
			'effect',
			array(
				'label'   => __( 'Effect', 'powerpack' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'effect-1'  => __( 'Bottom Border Slides In', 'powerpack' ),
					'effect-2'  => __( 'Bottom Border Slides Out', 'powerpack' ),
					'effect-3'  => __( 'Brackets', 'powerpack' ),
					'effect-4'  => __( '3D Rolling Cube', 'powerpack' ),
					'effect-5'  => __( 'Same Word Slide In', 'powerpack' ),
					'effect-6'  => __( 'Right Angle Slides Down over Title', 'powerpack' ),
					'effect-7'  => __( 'Second Border Slides Up', 'powerpack' ),
					'effect-8'  => __( 'Border Slight Translate', 'powerpack' ),
					'effect-9'  => __( 'Second Text and Borders', 'powerpack' ),
					'effect-10' => __( 'Push Out', 'powerpack' ),
					'effect-11' => __( 'Text Fill', 'powerpack' ),
					'effect-12' => __( 'Circle', 'powerpack' ),
					'effect-13' => __( 'Three Circles', 'powerpack' ),
					'effect-14' => __( 'Border Switch', 'powerpack' ),
					'effect-15' => __( 'Scale Down', 'powerpack' ),
					'effect-16' => __( 'Fall Down', 'powerpack' ),
					'effect-17' => __( 'Move Up and Push Border', 'powerpack' ),
					'effect-18' => __( 'Cross', 'powerpack' ),
					'effect-19' => __( '3D Side', 'powerpack' ),
					'effect-20' => __( 'Unfold', 'powerpack' ),
					'effect-21' => __( 'Borders Slight Yranslate', 'powerpack' ),
				),
				'default' => 'effect-1',
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'     => __( 'Alignment', 'powerpack' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'    => array(
						'title' => __( 'Left', 'powerpack' ),
						'icon'  => 'fa fa-align-left',
					),
					'center'  => array(
						'title' => __( 'Center', 'powerpack' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'   => array(
						'title' => __( 'Right', 'powerpack' ),
						'icon'  => 'fa fa-align-right',
					),
					'justify' => array(
						'title' => __( 'Justified', 'powerpack' ),
						'icon'  => 'fa fa-align-justify',
					),
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		if ( ! is_pp_elements_active() ) {
			/**
			 * Content Tab: Upgrade PowerPack
			 *
			 * @since 1.2.9.4
			 */
			$this->start_controls_section(
				'section_upgrade_powerpack',
				array(
					'label' => apply_filters( 'upgrade_powerpack_title', __( 'Get PowerPack Pro', 'powerpack' ) ),
					'tab'   => Controls_Manager::TAB_CONTENT,
				)
			);

			$this->add_control(
				'upgrade_powerpack_notice',
				array(
					'label'           => '',
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => apply_filters( 'upgrade_powerpack_message', sprintf( __( 'Upgrade to %1$s Pro Version %2$s for 70+ widgets, exciting extensions and advanced features.', 'powerpack' ), '<a href="#" target="_blank" rel="noopener">', '</a>' ) ),
					'content_classes' => 'upgrade-powerpack-notice elementor-panel-alert elementor-panel-alert-info',
				)
			);

			$this->end_controls_section();
		}

		/*
		-----------------------------------------------------------------------------------*/
		/*
		  STYLE TAB
		/*-----------------------------------------------------------------------------------*/

		/**
		 * Style Tab: Link Effects
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_style',
			array(
				'label' => __( 'Link Effects', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography',
				'label'    => __( 'Typography', 'powerpack' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} a.pp-link',
			)
		);

		$this->add_responsive_control(
			'divider_title_width',
			array(
				'label'      => __( 'Width', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => 200,
				),
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 1000,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-link-effect-19'      => 'width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .pp-link-effect-19 span' => 'transform-origin: 50% 50% calc(-{{SIZE}}{{UNIT}}/2)',
				),
				'condition'  => array(
					'effect' => 'effect-19',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_link_style' );

		$this->start_controls_tab(
			'tab_link_normal',
			array(
				'label' => __( 'Normal', 'powerpack' ),
			)
		);

		$this->add_control(
			'link_color_normal',
			array(
				'label'     => __( 'Link Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} a.pp-link, {{WRAPPER}} .pp-link-effect-10 span, {{WRAPPER}} .pp-link-effect-15:before, {{WRAPPER}} .pp-link-effect-16, {{WRAPPER}} .pp-link-effect-17:before' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'background_color_normal',
			array(
				'label'     => __( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-link-effect-4 span, {{WRAPPER}} .pp-link-effect-10 span, {{WRAPPER}} .pp-link-effect-19 span, {{WRAPPER}} .pp-link-effect-20 span' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'link_border_color',
			array(
				'label'     => __( 'Border Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-link-effect-8:before' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .pp-link-effect-11'       => 'border-top-color: {{VALUE}};',
					'{{WRAPPER}} .pp-link-effect-1:after, {{WRAPPER}} .pp-link-effect-2:after, {{WRAPPER}} .pp-link-effect-6:before, {{WRAPPER}} .pp-link-effect-6:after, {{WRAPPER}} .pp-link-effect-7:before, {{WRAPPER}} .pp-link-effect-7:after, {{WRAPPER}} .pp-link-effect-14:before, {{WRAPPER}} .pp-link-effect-14:after, {{WRAPPER}} .pp-link-effect-18:before, {{WRAPPER}} .pp-link-effect-18:after' => 'background: {{VALUE}};',
					'{{WRAPPER}} .pp-link-effect-3:before, {{WRAPPER}} .pp-link-effect-3:after' => 'color: {{VALUE}};',
					'{{WRAPPER}} .pp-link-effect-20 span'  => 'box-shadow: inset 0 3px {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_link_hover',
			array(
				'label' => __( 'Hover', 'powerpack' ),
			)
		);

		$this->add_control(
			'link_color_hover',
			array(
				'label'     => __( 'Link Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} a.pp-link:hover, {{WRAPPER}} .pp-link-effect-10:before, {{WRAPPER}} .pp-link-effect-11:before, {{WRAPPER}} .pp-link-effect-15, {{WRAPPER}} .pp-link-effect-16:before, {{WRAPPER}} .pp-link-effect-20 span:before' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'background_color_hover',
			array(
				'label'     => __( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-link-effect-4 span:before, {{WRAPPER}} .pp-link-effect-10:before, {{WRAPPER}} .pp-link-effect-19 span:before, {{WRAPPER}} .pp-link-effect-20 span:before' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'link_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-link-effect-8:after' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .pp-link-effect-11:before' => 'border-bottom-color: {{VALUE}};',
					'{{WRAPPER}} .pp-link-effect-9:before, {{WRAPPER}} .pp-link-effect-9:after, {{WRAPPER}} .pp-link-effect-14:hover:before, {{WRAPPER}} .pp-link-effect-14:focus:before, {{WRAPPER}} .pp-link-effect-14:hover:after, {{WRAPPER}} .pp-link-effect-14:focus:after, {{WRAPPER}} .pp-link-effect-17:after, {{WRAPPER}} .pp-link-effect-18:hover:before, {{WRAPPER}} .pp-link-effect-18:focus:before, {{WRAPPER}} .pp-link-effect-18:hover:after, {{WRAPPER}} .pp-link-effect-18:focus:after, {{WRAPPER}} .pp-link-effect-21:before, {{WRAPPER}} .pp-link-effect-21:after' => 'background: {{VALUE}};',
					'{{WRAPPER}} .pp-link-effect-17'      => 'color: {{VALUE}};',
					'{{WRAPPER}} .pp-link-effect-13:hover:before, {{WRAPPER}} .pp-link-effect-13:focus:before' => 'color: {{VALUE}}; text-shadow: 10px 0 {{VALUE}}, -10px 0 {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_section();

	}

	/**
	 * Render link effects widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		// get our input from the widget settings.

		$pa_link_text           = ! empty( $settings['text'] ) ? $settings['text'] : '';
		$pa_link_secondary_text = ! empty( $settings['secondary_text'] ) ? $settings['secondary_text'] : '';

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_link_attributes( 'link', $settings['link'] );
		}

		$this->add_render_attribute( 'link', 'class', 'pp-link' );

		if ( $settings['effect'] ) {
			$this->add_render_attribute( 'link', 'class', 'pp-link-' . $settings['effect'] );
		}

		if ( $settings['effect'] == 'effect-4' || $settings['effect'] == 'effect-5' || $settings['effect'] == 'effect-19' || $settings['effect'] == 'effect-20' ) {
			$this->add_render_attribute( 'pp-link-text', 'data-hover', $pa_link_text );
		}

		if ( $settings['effect'] == 'effect-10' || $settings['effect'] == 'effect-11' || $settings['effect'] == 'effect-15' || $settings['effect'] == 'effect-16' || $settings['effect'] == 'effect-17' || $settings['effect'] == 'effect-18' ) {
			$this->add_render_attribute( 'pp-link-text-2', 'data-hover', $pa_link_text );
		}
		?>

		<a <?php echo $this->get_render_attribute_string( 'link' ); ?><?php echo $this->get_render_attribute_string( 'pp-link-text-2' ); ?>>
			<span <?php echo $this->get_render_attribute_string( 'pp-link-text' ); ?>>
				<?php echo $pa_link_text; ?>
			</span>
			<?php if ( $settings['effect'] == 'effect-9' ) { ?>
				<span><?php echo esc_attr( $pa_link_secondary_text ); ?></span>
			<?php } ?>
		</a>

		<?php
	}

	protected function _content_template() {}

}
