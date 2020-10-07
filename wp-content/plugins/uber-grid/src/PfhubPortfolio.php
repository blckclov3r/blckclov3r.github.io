<?php


namespace PfhubPortfolio;


class PfhubPortfolio
{
    public $admin = null;

    public function __construct()
    {
        register_activation_hook(__FILE__, array(Install::class, 'install'));
        add_action('init', array($this, 'init'), 0);
        add_action('plugins_loaded', array($this, 'loadPluginTextdomain'));
        add_action('widgets_init', array(Frontend::class, 'widgets'));
	    add_action('elementor/widgets/widgets_registered', array($this, 'elementorWidgets'));
    }

	public function elementorWidgets()
	{
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new ElementorWidget());
	}

    /**
     * Load plugin text domain
     */
    public function loadPluginTextdomain()
    {
        load_plugin_textdomain('pfhub_portfolio', false, PFHUB_PORTFOLIO_PLUGIN_PATH . '/languages/');
    }

    public function init()
    {
        Install::init();

        if (is_admin()) {
            $this->admin = new Admin();
        }

        Frontend::init();
        Ajax::init();

    }

}
