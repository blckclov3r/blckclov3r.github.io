<?php


namespace PfhubPortfolio;


class ElementorWidget extends \Elementor\Widget_Base {
	public function get_name() {
		return 'pfhub_portfolio';
	}

	public function get_title() {
		return __( 'PortfolioHub', 'pfhub_portfolio' );
	}

	public function get_icon() {
		return 'fa fa-image';
	}

	public function get_categories() {
		return array('basic');
	}

	protected function _register_controls() {
		global $wpdb;
		$gridsTable = $wpdb->prefix.'pfhub_portfolio_grids';
		$grids = $wpdb->get_results("SELECT id, name FROM `".$gridsTable."` order by id desc ");

		$gridOptions = array(
			0  => __( 'Select', 'pfhub_portfolio' )
		);

		if(!empty($grids)) {
			foreach ($grids as $grid) {
				$gridOptions[$grid->id] = $grid->name;
			}
		}

		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'pfhub_portfolio' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'grid_id',
			[
				'label' => __( 'Select Portfolio Gallery', 'pfhub_portfolio' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 0,
				'options' => $gridOptions,
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		echo do_shortcode('[pfhub_portfolio id="'.$settings['grid_id'].'"]');
	}
}