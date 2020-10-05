<?php
namespace PowerpackElementsLite\Modules\Twitter\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Twitter Timeline Widget
 */
class Twitter_Timeline extends Powerpack_Widget {

	public function get_name() {
		return parent::get_widget_name( 'Twitter_Timeline' );
	}

	public function get_title() {
		return parent::get_widget_title( 'Twitter_Timeline' );
	}

	public function get_icon() {
		return parent::get_widget_icon( 'Twitter_Timeline' );
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return parent::get_widget_keywords( 'Twitter_Timeline' );
	}

	/**
	 * Retrieve the list of scripts the twitter timeline widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return array(
			'pp-jquery-plugin',
			'jquery-cookie',
			'twitter-widgets',
			'powerpack-frontend',
		);
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_timeline',
			array(
				'label' => __( 'Timeline', 'powerpack' ),
			)
		);

		$this->add_control(
			'username',
			array(
				'label'   => __( 'User Name', 'powerpack' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
			)
		);

		$this->add_control(
			'theme',
			array(
				'label'   => __( 'Theme', 'powerpack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'light',
				'options' => array(
					'light' => __( 'Light', 'powerpack' ),
					'dark'  => __( 'Dark', 'powerpack' ),
				),
			)
		);

		$this->add_control(
			'show_replies',
			array(
				'label'        => __( 'Show Replies', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'powerpack' ),
				'label_off'    => __( 'No', 'powerpack' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'    => __( 'Layout', 'powerpack' ),
				'type'     => Controls_Manager::SELECT2,
				'default'  => '',
				'options'  => array(
					'noheader'    => __( 'No Header', 'powerpack' ),
					'nofooter'    => __( 'No Footer', 'powerpack' ),
					'noborders'   => __( 'No Borders', 'powerpack' ),
					'transparent' => __( 'Transparent', 'powerpack' ),
					'noscrollbar' => __( 'No Scroll Bar', 'powerpack' ),
				),
				'multiple' => true,
			)
		);

		$this->add_control(
			'width',
			array(
				'label'      => __( 'Width', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'unit' => 'px',
					'size' => '',
				),
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 100,
						'max' => 1000,
					),
				),
			)
		);
		$this->add_control(
			'height',
			array(
				'label'      => __( 'Height', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'unit' => 'px',
					'size' => '',
				),
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 100,
						'max' => 1000,
					),
				),
			)
		);

		$this->add_control(
			'tweet_limit',
			array(
				'label'   => __( 'Tweet Limit', 'powerpack' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
			)
		);

		$this->add_control(
			'link_color',
			array(
				'label'   => __( 'Link Color', 'powerpack' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '',
			)
		);

		$this->add_control(
			'border_color',
			array(
				'label'   => __( 'Border Color', 'powerpack' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '',
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

	}

	protected function render() {
		$settings = $this->get_settings();

		$attrs = array();
		$attr  = ' ';

		$user = $settings['username'];

		$attrs['data-theme']        = $settings['theme'];
		$attrs['data-show-replies'] = ( 'yes' == $settings['show_replies'] ) ? 'true' : 'false';

		if ( ! empty( $settings['width'] ) ) {
			$attrs['data-width'] = $settings['width']['size'];
		}
		if ( ! empty( $settings['height'] ) ) {
			$attrs['data-height'] = $settings['height']['size'];
		}
		if ( isset( $settings['layout'] ) && ! empty( $settings['layout'] ) ) {
			$attrs['data-chrome'] = implode( ' ', $settings['layout'] );
		}
		if ( ! empty( $settings['tweet_limit'] ) && absint( $settings['tweet_limit'] ) ) {
			$attrs['data-tweet-limit'] = absint( $settings['tweet_limit'] );
		}
		if ( ! empty( $settings['link_color'] ) ) {
			$attrs['data-link-color'] = $settings['link_color'];
		}
		if ( ! empty( $settings['border_color'] ) ) {
			$attrs['data-border-color'] = $settings['border_color'];
		}

		foreach ( $attrs as $key => $value ) {
			$attr .= $key;
			if ( ! empty( $value ) ) {
				$attr .= '="' . $value . '"';
			}

			$attr .= ' ';
		}

		?>
		<div class="pp-twitter-timeline" <?php echo $attr; ?>>
			<a class="twitter-timeline" href="https://twitter.com/<?php echo $user; ?>" <?php echo $attr; ?>>Tweets by <?php echo $user; ?></a>
		</div>
		<?php
	}

	/**
	 * Render Twitter Timeline widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
	protected function _content_template() {
		?>
		<#
			view.addRenderAttribute( 'atts', {
				'data-theme': settings.theme,
				'data-show-replies': ( 'yes' == settings.show_replies ) ? 'true' : 'false',
				'link_color': settings.link_color,
				'data-width': settings.width.size,
				'data-height': settings.height.size,
				'data-chrome': settings.layout,
				'data-tweet-limit': settings.tweet_limit,
				'data-link-color': settings.link_color,
				'data-border-color': settings.border_color,
			});
		#>
		<div class="pp-twitter-timeline" {{{ view.getRenderAttributeString( 'atts' ) }}}>
			<a class="twitter-timeline" href="https://twitter.com/{{ settings.username }}" {{{ view.getRenderAttributeString( 'atts' ) }}}>Tweets by <# {{ settings.username }} #></a>
		</div>
		<?php
	}
}
