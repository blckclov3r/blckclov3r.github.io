<?php


namespace PfhubPortfolio;


use PfhubPortfolio\Helpers\GridHelper;

class Install
{
    public static function init()
    {
        add_action('init', array(__CLASS__, 'checkVersion'), 5);
    }

    public static function checkVersion()
    {
        if (get_option('pfhub_portfolio_version') !== PFHUB_PORTFOLIO_VERSION) {
            self::install();
            update_option('pfhub_portfolio_version', PFHUB_PORTFOLIO_VERSION);
        }
    }

    public static function installOptions()
    {
        update_option('pfhub_portfolio_lightbox_type', 'modern');


        if (!get_option('pfhub_portfolio_admin_image_hover_preview')) {
            update_option('pfhub_portfolio_admin_image_hover_preview', 'on');
            update_option('pfhub_portfolio_version', '2.2.0');
        }

        $portfolio_new_columns = array(
            array('categories', 'varchar(200)', 'Category 1,Category 2,Category 3,'),
            array('pfhub_portfolio_show_sorting', 'varchar(3)', 'off'),
            array('pfhub_portfolio_show_filtering', 'varchar(3)', 'off'),
            array('autoslide', 'varchar(3)', 'on'),
            array('show_loading', 'varchar(3)', 'on'),
            array('loading_icon_type', 'int(2)', '1')
        );
        global $wpdb;
        $table_name = $wpdb->prefix . "pfhub_portfolio_grids";
        foreach ($portfolio_new_columns as $portfolio_new_column) {
            if (!\PfhubPortfolio\Helpers\GridHelper::hasTableColumn($table_name, $portfolio_new_column[0])) {
                $query = "ALTER TABLE " . $table_name . " ADD " . $portfolio_new_column[0] . " " . $portfolio_new_column[1] . " DEFAULT '" . $portfolio_new_column[2] . "'";
                $wpdb->query($query);
            }
        }
        global $wpdb;
        $query = "SELECT pfhub_portfolio_show_filtering FROM " . $wpdb->prefix . "pfhub_portfolio_grids WHERE id=1";
        $pfhub_portfolio_show_filtering = $wpdb->get_var($query);
        if ($pfhub_portfolio_show_filtering != 'on') {
            $wpdb->update(
                $wpdb->prefix . "pfhub_portfolio_grids",
                array('pfhub_portfolio_show_filtering' => 'off'),
                array('id' => 1)
            );
        }

        if (!get_option('pfhub_portfolio_disable_right_click')) {
            update_option('pfhub_portfolio_disable_right_click', 'off');
        }
    }

    public static function install()
    {
        if (!defined('PFHUB_PORTFOLIO_INSTALLING')) {
            define('PFHUB_PORTFOLIO_INSTALLING', true);
        }

        self::createTables();

        if (!get_option('pfhub_portfolio_version')) {
            update_option('pfhub_portfolio_lightbox_type', 'modern');
        }

        self::installOptions();

        do_action('pfhub_portfolio_installed');
    }

    private static function createTables()
    {
        global $wpdb;
        $charset = $wpdb->get_charset_collate();

        $sql_pfhub_portfolio_images = "
CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "pfhub_portfolio_images` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `grid_id` varchar(200) DEFAULT NULL,
  `description` text,
  `image_url` text,
  `media_url` text DEFAULT NULL,
  `media_type` text NOT NULL,
  `link_target` text NOT NULL,
  `ordering` int(11) NOT NULL,
  `published` tinyint(4) unsigned DEFAULT NULL,
  `published_in_sl_width` tinyint(4) unsigned DEFAULT NULL,
  `category` text NOT NULL,
  PRIMARY KEY (`id`)
  ) " . $charset . " AUTO_INCREMENT=5";

        $sql_pfhub_portfolio_grids = "
CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "pfhub_portfolio_grids` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `sl_height` int(11) unsigned DEFAULT NULL,
  `sl_width` int(11) unsigned DEFAULT NULL,
  `pause_on_hover` text,
  `grid_view_type` text,
  `description` text,
  `param` text,
  `sl_position` text NOT NULL,
  `ordering` int(11) NOT NULL,
  `published` text,
  `categories` text NOT NULL,
  `pfhub_portfolio_show_sorting` varchar(3) NOT NULL DEFAULT 'off',
  `pfhub_portfolio_show_filtering` varchar(3) NOT NULL DEFAULT 'off',
  `autoslide` varchar(5) NOT NULL DEFAULT 'on',
  `show_loading` varchar(3) NOT NULL DEFAULT 'on',
  `loading_icon_type` int(2) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) " . $charset . " AUTO_INCREMENT=2";

        $table_name = $wpdb->prefix . "pfhub_portfolio_images";
        $sql_2 = "
INSERT INTO 
`" . $table_name . "` (`id`, `name`, `grid_id`, `description`, `image_url`, `media_url`, `media_type`, `link_target`, `ordering`, `published`, `published_in_sl_width`) VALUES
(1, 'Retro', '1', '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. </p><p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>', '" . PFHUB_PORTFOLIO_PLUGIN_URL . "assets/images/defaultImages/projects/volkswagen-1.jpg" . ";" . PFHUB_PORTFOLIO_PLUGIN_URL . "assets/images/defaultImages/projects/volkswagen-2.jpg" . ";" . PFHUB_PORTFOLIO_PLUGIN_URL . "assets/images/defaultImages/projects/volkswagen-3.jpg" . ";', 'https://portfoliohub.io/', 'image', 'on', 0, 1, NULL),
(2, 'iPhone', '1', '<h6>Lorem Ipsum </h6><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrudexercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. </p><p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p><ul><li>lorem ipsum</li><li>dolor sit amet</li><li>lorem ipsum</li><li>dolor sit amet</li></ul>', '" . PFHUB_PORTFOLIO_PLUGIN_URL . "assets/images/defaultImages/projects/huawei-1.jpg" . ";" . PFHUB_PORTFOLIO_PLUGIN_URL . "assets/images/defaultImages/projects/huawei-2.jpg" . ";" . PFHUB_PORTFOLIO_PLUGIN_URL . "assets/images/defaultImages/projects/huawei-3.jpg" . ":" . "https://www.youtube.com/watch?v=SHZVn2Ul5nU" . ";', 'https://portfoliohub.io/', 'image', 'on', 2, 1, NULL),
(3, 'Desk', '1', '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. </p><h7>Dolor sit amet</h7><p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>', '" . PFHUB_PORTFOLIO_PLUGIN_URL . "assets/images/defaultImages/projects/designer-1.jpg" . ";" . PFHUB_PORTFOLIO_PLUGIN_URL . "assets/images/defaultImages/projects/designer-2.jpg" . ";" . PFHUB_PORTFOLIO_PLUGIN_URL . "assets/images/defaultImages/projects/designer-3.jpg" . ";', 'https://portfoliohub.io/', 'image', 'on', 3, 1, NULL),
(4, 'Autumn / Winter Collection', '1', '<h6>Lorem Ipsum</h6><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>', '" . PFHUB_PORTFOLIO_PLUGIN_URL . "assets/images/defaultImages/projects/autumn-2.jpg" . ";', 'https://portfoliohub.io/', 'image', 'on', 4, 1, NULL),
(5, 'Headphone', '1', '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. </p><p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>', '" . PFHUB_PORTFOLIO_PLUGIN_URL . "assets/images/defaultImages/projects/headphone-1.jpg" . ";" . "https://vimeo.com/81260807" . ";" . PFHUB_PORTFOLIO_PLUGIN_URL . "assets/images/defaultImages/projects/headphone-2.jpg" . ";" . PFHUB_PORTFOLIO_PLUGIN_URL . "assets/images/defaultImages/projects/headphone-3.jpg" . ";', 'https://portfoliohub.io/', 'image', 'on', 5, 1, NULL),
(6, 'Take Flight', '1', '<h6>Lorem Ipsum</h6><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. </p><p>Excepteur sint occaecat cupidatat non proident , sunt in culpa qui officia deserunt mollit anim id est laborum.</p>', '" . PFHUB_PORTFOLIO_PLUGIN_URL . "assets/images/defaultImages/projects/drone-1.jpg" . ";" . PFHUB_PORTFOLIO_PLUGIN_URL . "assets/images/defaultImages/projects/drone-2.jpg" . ";" . PFHUB_PORTFOLIO_PLUGIN_URL . "assets/images/defaultImages/projects/drone-3.jpg" . ";', 'https://portfoliohub.io/', 'image', 'on', 6, 1, NULL),
(7, 'Workstation', '1', '<h6>Lorem Ipsum </h6><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. </p><p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p><ul><li>lorem ipsum</li><li>dolor sit amet</li><li>lorem ipsum</li><li>dolor sit amet</li></ul>', '" . PFHUB_PORTFOLIO_PLUGIN_URL . "assets/images/defaultImages/projects/portfolio-1.jpg" . ";" . PFHUB_PORTFOLIO_PLUGIN_URL . "assets/images/defaultImages/projects/portfolio-2.jpg" . ";" . PFHUB_PORTFOLIO_PLUGIN_URL . "assets/images/defaultImages/projects/portfolio-3.jpg" . ";', 'https://portfoliohub.io/', 'image', 'on', 7, 1, NULL),
(8, 'Studio', '1', '<ul><li>lorem ipsumdolor sit amet</li><li>lorem ipsum dolor sit amet</li></ul><p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>', '" . PFHUB_PORTFOLIO_PLUGIN_URL . "assets/images/defaultImages/projects/music-1.jpg" . ";" . PFHUB_PORTFOLIO_PLUGIN_URL . "assets/images/defaultImages/projects/music-2.jpg" . ";" . PFHUB_PORTFOLIO_PLUGIN_URL . "assets/images/defaultImages/projects/music-3.jpg" . ";', 'https://portfoliohub.io/', 'image', 'on', 8, 1, NULL)";

        $table_name = $wpdb->prefix . "pfhub_portfolio_grids";
        $wpdb->query($sql_pfhub_portfolio_images);
        $wpdb->query($sql_pfhub_portfolio_grids);

        if (!$wpdb->get_var("select count(*) from " . $wpdb->prefix . "pfhub_portfolio_images")) {
            $wpdb->query($sql_2);
        }
        if (!$wpdb->get_var("select count(*) from " . $wpdb->prefix . "pfhub_portfolio_grids")) {
            $wpdb->insert(
                $table_name,
                array(
                    'id' => 1,
                    'name' => 'My First Grid',
                    'sl_height' => 375,
                    'sl_width' => 600,
                    'pause_on_hover' => 'on',
                    'grid_view_type' => '2',
                    'description' => '4000',
                    'param' => '1000',
                    'sl_position' => 'off',
                    'ordering' => 1,
                    'published' => '300',
                    'categories' => 'Category 1,Category 2,Category 3,',
                )
            );
        }
        $table_name = $wpdb->prefix . "pfhub_portfolio_images";


        if (!GridHelper::hasTableColumn($table_name, "publish_date")) {
            $wpdb->query("ALTER TABLE `" . $table_name . "` ADD `publish_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ");
        }

    }
}
