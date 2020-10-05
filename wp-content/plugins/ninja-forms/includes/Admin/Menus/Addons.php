<?php if ( ! defined( 'ABSPATH' ) ) exit;

final class NF_Admin_Menus_Addons extends NF_Abstracts_Submenu
{
    public $parent_slug = 'ninja-forms';

    public $menu_slug = 'ninja-forms#apps';

    public $position = 7;

    public function __construct()
    {
        $disable_marketing = false;
        if ( ! apply_filters( 'ninja_forms_disable_marketing', $disable_marketing ) ) {
            parent::__construct();
        }

        add_action( 'admin_init', array( $this, 'nf_upgrade_redirect' ) );
    }

    /**
     * If we have required updates, unregister the menu item
     */
    public function nf_upgrade_redirect() {
        global $pagenow;
            
        if( "1" == get_option( 'ninja_forms_needs_updates' ) ) {
            remove_submenu_page( $this->parent_slug, $this->menu_slug );
        }
    }

    public function get_page_title()
    {
        $title = '<span style="color:#84cc1e">' . esc_html__( 'Add-Ons', 'ninja-forms' ) . '</span>'; 

        return $title;
    }

    public function get_capability()
    {
        return apply_filters( 'ninja_forms_admin_extend_capabilities', $this->capability );
    }

    public function display()
    {
        // Fetch our marketing feed.
        $saved = get_option( 'ninja_forms_addons_feed', false );
        // If we got back nothing...
        if ( ! $saved ) {
            // Default to the in-app file.
            $items = file_get_contents( Ninja_Forms::$dir . '/deprecated/addons-feed.json' );
            $items = json_decode( $items, true );
        } // Otherwise... (We did get something from the db.)
        else {
            // Use the data we fetched.
            $items = json_decode( $saved, true );
        }
        //shuffle( $items );

        $notices = array();

        foreach ($items as &$item) {
            $plugin_data = array();
            if( !empty( $item['plugin'] ) && file_exists( WP_PLUGIN_DIR.'/'.$item['plugin'] ) ){
                $plugin_data = get_plugin_data( WP_PLUGIN_DIR.'/'.$item['plugin'], false, true );
            }
            
            if ( ! file_exists( Ninja_Forms::$dir . '/' . $item[ 'image' ] ) ) {
                $item[ 'image' ] = 'assets/img/add-ons/placeholder.png';
            }

            $version = isset ( $plugin_data['Version'] ) ? $plugin_data['Version'] : '';

            if ( ! empty ( $version ) && $version < $item['version'] ) {

                $notices[] = array(
                    'title' => $item[ 'title' ],
                    'old_version' => $version,
                    'new_version' => $item[ 'version' ]
                );
            }
        }

        $groups = [
            'popular' => [
                'title' => __( 'You Can Build Smart, Beautiful WordPress Forms!', 'ninja-forms' ),
                'items' => self::filterItemsByCategroy( $items, 'form-function-design' ),
            ],
            'documents' => [
                'title' => __( 'Better Document Sharing will Take Your Business Further', 'ninja-forms' ),
                'items' => self::filterItemsByCategroy( $items, 'file-management' ),
            ],
            'payments' => [
                'title' => __( 'Accept Payments & Donations Without Breaking the Bank', 'ninja-forms' ),
                'items' => self::filterItemsByCategroy( $items, 'payment-gateways' ),
            ],
            'marketing' => [
                'title' => __( 'Want to Attract More Subscribers to Your Mailing Lists?', 'ninja-forms' ),
                'items' => self::filterItemsByCategroy( $items, 'email-marketing' ),
            ],
            'website' => [
                'title' => __( 'Let Your Users Do More, and Do More for Your Users', 'ninja-forms' ),
                'items' => self::filterItemsByCategroy( $items, 'user-management' ),
            ],
            'crm' => [
                'title' => __( 'Generate More Leads Than You Ever Thought Possible', 'ninja-forms' ),
                'items' => self::filterItemsByCategroy( $items, 'crm-integrations' ),
            ],
            'notifications' => [
                'title' => __( 'Never Miss an Important Submission or Lead Again!', 'ninja-forms' ),
                'items' => self::filterItemsByCategroy( $items, 'notification-workflow' ),
            ],
            'misc' => [
                'title' => __( 'Don’t See Your Favorite Service Above? We Can Likely Still Help.', 'ninja-forms' ),
                'items' => self::filterItemsByCategroy( $items, 'custom-integrations' ),
            ],
        ];
        

        Ninja_Forms::template( 'admin-menu-addons.html.php', compact( 'items', 'notices', 'groups' ) );
    }

    public static function filterItemsByCategroy( $items, $category ) {
        return array_filter( $items, function( $item ) use ($category) {
            return array_filter( $item['categories'], function( $itemCategory ) use ($category){
                return $category === $itemCategory['slug'];
            });
        });
    }

} // End Class NF_Admin_Addons
