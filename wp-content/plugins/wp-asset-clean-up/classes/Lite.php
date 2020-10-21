<?php
namespace WpAssetCleanUp;

/**
 * The code is triggered only in Asset CleanUp Lite
 *
 * Class Lite
 * @package WpAssetCleanUp
 */
class Lite
{
	/**
	 * Lite constructor.
	 */
	public function __construct()
	{
		add_action('current_screen', array($this, 'currentScreen'));

		}

	/**
	 *
	 */
	public function currentScreen()
	{
		$current_screen = \get_current_screen();

		if ($current_screen->base === 'term' && isset($current_screen->taxonomy) && $current_screen->taxonomy != '') {
			add_action ($current_screen->taxonomy . '_edit_form_fields', static function ($tag) {
				?>
				<tr class="form-field">
					<th scope="row" valign="top"><label for="wpassetcleanup_list"><?php echo WPACU_PLUGIN_TITLE; ?>: <?php _e('CSS &amp; JavaScript Load Manager', 'wp-asset-clean-up'); ?></label></th>
					<td data-wpacu-taxonomy="<?php echo $tag->taxonomy; ?>">
						<img width="20" height="20" src="<?php echo WPACU_PLUGIN_URL; ?>/assets/icons/icon-lock.svg" valign="top" alt="" /> &nbsp;
						<?php
						echo sprintf(
							__('Managing the loading of the styles &amp; scripts files for this <strong>%s</strong> taxonomy is %savailable in the Pro version%s', 'wp-asset-clean-up'),
							$tag->taxonomy,
							'<a href="'.WPACU_PLUGIN_GO_PRO_URL.'?utm_source=taxonomy_edit_page&utm_medium=upgrade_link" target="_blank">',
							'</a>'
						);
						?>
					</td>
				</tr>
				<?php
			});
		}
	}

	}