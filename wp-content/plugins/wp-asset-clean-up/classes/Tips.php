<?php
namespace WpAssetCleanUp;

/**
 * Class Tips
 * @package WpAssetCleanUp
 */
class Tips
{
	/**
	 * @var array
	 */
	public $list = array('css' => array(), 'js' => array());

	/**
	 * Tips constructor.
	 */
	public function __construct()
	{
		// CSS list
		$this->list['css']['wp-block-library'] = <<<HTML
This asset is related to the Gutenberg block editor. If you do not use it (e.g. you have an alternative option such as Divi, Elementor etc.), then it is safe to unload this file.
HTML;

		if ($extraWpBlockLibraryTip = self::ceGutenbergCssLibraryBlockTip()) {
			$this->list['css']['wp-block-library'] .= ' '.$extraWpBlockLibraryTip;
		}

		$this->list['css']['astra-contact-form-7'] = <<<HTML
This asset is related to the "Contact Form 7" plugin. If you do not use it on this page (e.g. only needed on a page such as "Contact"), then you can safely unload it.
HTML;
		$this->list['css']['contact-form-7'] = <<<HTML
This CSS file is related to "Contact Form 7" and if you don't load any form on this page (e.g. you use it only on pages such as Contact, Make a booking etc.), then you can safely unload it (e.g. side-wide and make exceptions on the few pages you use it).
HTML;

		$this->list['css']['duplicate-post'] = <<<HTML
This CSS file is meant to style the "Duplicate Post" plugin's menu within the top admin bar and it's loading when the user (with the right privileges) is logged-in. It's NOT meant to load for the guests (non logged-in visitors). You can leave it loaded.
HTML;

		$this->list['css']['dashicons'] = <<<HTML
To avoid breaking admin bar's styling which relies on the Wordpress Dashicons, any unload rule set for this handle will be ignored IF the user is logged-in and the admin bar is showing up.
HTML;



		// JavaScript list
		$this->list['js']['wp-embed'] = <<<HTML
To completely disable oEmbeds, you can use "Disable oEmbed (Embeds) Site-Wide" from plugin's "Settings" -&gt; "Site-Wide Common Unloads". It will also prevent this file from loading in the first place and hide it from this location.
HTML;
		$this->list['js']['wc-cart-fragments'] = <<<HTML
This is used to make an AJAX call to retrieve the latest WooCommerce cart information. If there is no cart area (e.g. in a sidebar or menu), you can safely unload this file.
HTML;

		$this->list['js']['contact-form-7'] = <<<HTML
This JavaScript file is related to "Contact Form 7" and if you don't load any form on this page (e.g. you use it only on pages such as Contact, Make a booking etc.), then you can safely unload it (e.g. side-wide and make exceptions on the few pages you use it).
HTML;
	}

	/**
	 * Tip related to "Classic Editor" plugin and some of its active settings
	 *
	 * @return string
	 */
	public static function ceGutenbergCssLibraryBlockTip()
	{
		if (Misc::isClassicEditorUsed()) {
			return <<<HTML
You are using "Classic Editor" plugin and the option "Default editor for all users" is set to "Classic Editor" and "Allow users to switch editors" option is set to "No". It is very likely you do not need the Gutenberg CSS Library Block in any page.
HTML;
		}

		return false;
	}
}
