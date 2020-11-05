<?php
/**
 * The shortcode functionality of the plugin
 *
 * @link       https://www.feataholic.com
 * @since      1.1.8 Free
 *
 * @package    MZLDR
 * @subpackage MZLDR/includes
 */
class MZLDR_Shortcode
{
	/**
	 * The Loader Model
	 *
	 * @var class $loader_model
	 */
    private $loader_model;

	/**
	 * The Impression Model
	 *
	 * @var class $impression_model
	 */
	private $impression_model;
    
    public function __construct(){
		$this->loader_model = new MZLDR_Loader_Model();
		$this->impression_model = new MZLDR_Impression_Model();
    }

	/**
	 * Handle the shortcode
	 * 
	 * @return  void
	 */
    public function mzldr_handle($atts = array())
    {
        $loader_id = isset($atts['loader_id']) ? $atts['loader_id'] : '';
        if (empty($loader_id)) {
            return;
        }

        // grab loader
        $data = array(
            'where' => 'WHERE id = ' . $loader_id . ' AND published=1'
        );
        $loader = $this->loader_model->getLoaders($data);

        // if no loader exists, return
        if (empty($loader) && !isset($loader[0])) {
            return;
        }
        
        $loader = $loader[0];
        
        // track impressions
		$this->impression_model->checkBeforeTrack($loader);
        
        // add an action to wp_footer so we can add the Loader
        add_action( 'wp_footer', function() use ($loader) {
            $loaders[] = $loader;

            require MZLDR_PUBLIC_PATH . 'partials/loader/tmpl.php';
        } );
    }

}
