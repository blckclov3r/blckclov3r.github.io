<?php
namespace WpAssetCleanUp;

use WpAssetCleanUp\OptimiseAssets\OptimizeCommon;

/**
 * Class Debug
 * @package WpAssetCleanUp
 */
class Debug
{
	/**
	 * Debug constructor.
	 */
	public function __construct()
	{
		if (array_key_exists('wpacu_debug', $_GET)) {
			add_action('wp_footer', array($this, 'showDebugOptions'), PHP_INT_MAX);
		}

		foreach(array('wp', 'admin_init') as $wpacuActionHook) {
			add_action( $wpacuActionHook, static function() {
				if (isset( $_GET['wpacu_get_cache_dir_size'] ) && Menu::userCanManageAssets()) {
					self::printCacheDirInfo();
				}

				// For debugging purposes
				if (isset($_GET['wpacu_get_already_minified']) && Menu::userCanManageAssets()) {
                    echo '<pre>'; print_r(OptimizeCommon::getAlreadyMarkedAsMinified()); echo '</pre>';
                    exit();
                }

				if (isset($_GET['wpacu_remove_already_minified']) && Menu::userCanManageAssets()) {
					echo '<pre>'; print_r(OptimizeCommon::removeAlreadyMarkedAsMinified()); echo '</pre>';
					exit();
				}

				if (isset($_GET['wpacu_limit_already_minified']) && Menu::userCanManageAssets()) {
					OptimizeCommon::limitAlreadyMarkedAsMinified();
					echo '<pre>'; print_r(OptimizeCommon::getAlreadyMarkedAsMinified()); echo '</pre>';
					exit();
				}
			} );
		}
	}

	/**
	 *
	 */
	public function showDebugOptions()
	{
	    if (! Menu::userCanManageAssets()) {
	        return;
        }

	    $markedCssListForUnload = array_unique(Main::instance()->allUnloadedAssets['css']);
		$markedJsListForUnload  = array_unique(Main::instance()->allUnloadedAssets['js']);

		$allDebugOptions = array(
			// CSS
			'wpacu_no_css_unload'    => 'Do not apply any CSS unload rules',
			'wpacu_no_css_minify'    => 'Do not minify any CSS',
			'wpacu_no_css_combine'   => 'Do not combine any CSS',

			// JS
			'wpacu_no_js_unload'     => 'Do not apply any JavaScript unload rules',
			'wpacu_no_js_minify'     => 'Do not minify any JavaScript',
			'wpacu_no_js_combine'    => 'Do not combine any JavaScript',

			// Others
			'wpacu_no_frontend_show' => 'Do not show the bottom CSS/JS managing list',
			'wpacu_no_admin_bar'     => 'Do not show the admin bar',
			'wpacu_no_html_changes'  => 'Do not alter the HTML DOM (this will also load all assets non-minified and non-combined)',
		);
		?>
		<style type="text/css">
			#wpacu-debug-options {
                background: white;
                width: 90%;
                margin: 10px;
				border: 1px solid #cdcdcd;
				border-radius: 5px;
				padding: 12px;
			}

            #wpacu-debug-options p {
                margin-bottom: 15px;
            }

            #wpacu-debug-options ul.wpacu-options {
                list-style: none;
                padding-left: 0;
                margin-top: 0;
                margin-left: 8px;
            }

            #wpacu-debug-options ul.wpacu-options li {
                line-height: normal;
                font-size: inherit;
            }

			#wpacu-debug-options ul.wpacu-options li label {
				cursor: pointer;
                font-size: inherit;
			}

            #wpacu-debug-options table td {
                padding: 20px;
            }

            ul#wpacu-debug-timing {
                margin-left: 0;
                padding-left: 0;
            }

			ul#wpacu-debug-timing > li {
				list-style: none;
				padding-left: 20px;
			}

			ul#wpacu-debug-timing li > ul > li {
				list-style: disc;
				padding-left: 0;
			}
		</style>

		<div id="wpacu-debug-options">
            <table>
                <tr>
                    <td valign="top">
                        <p>View the page with the following options <strong>disabled</strong> (for debugging purposes):</p>
                        <form>
                            <ul class="wpacu-options">
                            <?php
                            foreach ($allDebugOptions as $debugKey => $debugText) {
                            ?>
                                <li>
                                    <label><input type="checkbox"
                                                  name="<?php echo $debugKey; ?>"
                                                  <?php if (array_key_exists($debugKey, $_GET)) { echo 'checked="checked"'; } ?> /> &nbsp;<?php echo $debugText; ?></label>
                                </li>
                            <?php
                            }
                            ?>
                            </ul>
                            <div>
                                <input type="submit"
                                       value="View page with the chosen options turned off" />
                            </div>
                            <input type="hidden" name="wpacu_debug" value="on" />
                        </form>
                    </td>
                    <td>
	                    <div style="margin: 0 0 10px; padding: 10px 0;">
	                        <strong>CSS handles marked for unload:</strong>&nbsp;
	                        <?php
	                        if (! empty($markedCssListForUnload)) {
	                            sort($markedCssListForUnload);
		                        $markedCssListForUnloadFiltered = array_map(static function($handle) {
		                        	return '<span style="color: darkred;">'.$handle.'</span>';
		                        }, $markedCssListForUnload);
	                            echo implode(' &nbsp;/&nbsp; ', $markedCssListForUnloadFiltered);
	                        } else {
	                            echo 'None';
	                        }
	                        ?>
	                    </div>

	                    <div style="margin: 0 0 10px; padding: 10px 0;">
	                        <strong>JS handles marked for unload:</strong>&nbsp;
	                        <?php
	                        if (! empty($markedJsListForUnload)) {
	                            sort($markedJsListForUnload);
		                        $markedJsListForUnloadFiltered = array_map(static function($handle) {
			                        return '<span style="color: darkred;">'.$handle.'</span>';
		                        }, $markedJsListForUnload);

	                            echo implode(' &nbsp;/&nbsp; ', $markedJsListForUnloadFiltered);
	                        } else {
	                            echo 'None';
	                        }
	                        ?>
	                    </div>

	                    <hr />

                        <div style="margin: 0 0 10px; padding: 10px 0;">
							<ul style="list-style: none; padding-left: 0;">
                                <li style="margin-bottom: 10px;">Dequeue any chosen styles (.css): <?php echo Misc::printTimingFor('filter_dequeue_styles',  '{wpacu_filter_dequeue_styles_exec_time} ({wpacu_filter_dequeue_styles_exec_time_sec})'); ?></li>
                                <li style="margin-bottom: 20px;">Dequeue any chosen scripts (.js): <?php echo Misc::printTimingFor('filter_dequeue_scripts', '{wpacu_filter_dequeue_scripts_exec_time} ({wpacu_filter_dequeue_scripts_exec_time_sec})'); ?></li>

                                <li style="margin-bottom: 10px;">OptimizeCommon - HTML alteration via <em>wp_loaded</em>: {wpacu_alter_html_source_exec_time} ({wpacu_alter_html_source_exec_time_sec})
                                    <ul id="wpacu-debug-timing">
                                        <li style="margin-top: 10px; margin-bottom: 10px;">&nbsp;OptimizeCSS: {wpacu_alter_html_source_for_optimize_css_exec_time} ({wpacu_alter_html_source_for_optimize_css_exec_time_sec})
                                            <ul>
                                                <li>Google Fonts Optimization/Removal: {wpacu_alter_html_source_for_google_fonts_optimization_removal_exec_time}</li>
                                                <li>From CSS file to Inline: {wpacu_alter_html_source_for_inline_css_exec_time}</li>
                                                <li>Update Original to Optimized: {wpacu_alter_html_source_original_to_optimized_css_exec_time}</li>
                                                <li>Preloads: {wpacu_alter_html_source_for_preload_css_exec_time}</li>
                                                <!-- -->
                                                <li>Combine: {wpacu_alter_html_source_for_combine_css_exec_time}</li>
                                                <li>Minify Inline Tags: {wpacu_alter_html_source_for_minify_inline_style_tags_exec_time}</li>
                                                <li>Unload (ignore dependencies): {wpacu_alter_html_source_unload_ignore_deps_css_exec_time}</li>
                                            </ul>
                                        </li>

                                        <li>OptimizeJs: {wpacu_alter_html_source_for_optimize_js_exec_time} ({wpacu_alter_html_source_for_optimize_js_exec_time_sec})
                                            <ul>
                                                <li>Update Original to Optimized: {wpacu_alter_html_source_original_to_optimized_js_exec_time}</li>
                                                <li>Preloads: {wpacu_alter_html_source_for_preload_js_exec_time}</li>
                                                <!-- -->
                                                <li>Combine: {wpacu_alter_html_source_for_combine_js_exec_time}</li>
                                                <li>Unload (ignore dependencies): {wpacu_alter_html_source_unload_ignore_deps_js_exec_time}</li>
                                                <li>Move any inline wih jQuery code after jQuery library: {wpacu_alter_html_source_move_inline_jquery_after_src_tag_exec_time}</li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
							</ul>
	                    </div>
                    </td>
                </tr>
            </table>
		</div>
		<?php
	}

	/**
	 *
	 */
	public static function printCacheDirInfo()
    {
    	$assetCleanUpCacheDirRel = OptimizeCommon::getRelPathPluginCacheDir();
	    $assetCleanUpCacheDir  = WP_CONTENT_DIR . $assetCleanUpCacheDirRel;

	    echo '<h3>'.WPACU_PLUGIN_TITLE.': Caching Directory Stats</h3>';

	    if (is_dir($assetCleanUpCacheDir)) {
	    	$printCacheDirOutput = str_replace($assetCleanUpCacheDirRel, '<strong>'.$assetCleanUpCacheDirRel.'</strong>', $assetCleanUpCacheDir).'</em>';
		    if (! is_writable($assetCleanUpCacheDir)) {
			    echo '<span style="color: red;">'.
			            'The '.$printCacheDirOutput.' directory is <em>not writable</em>.</span>'.
			         '<br /><br />';
		    } else {
			    echo '<span style="color: green;">The '.$printCacheDirOutput.' directory is <em>writable</em>.</span>' . '<br /><br />';
		    }

		    $dirItems = new \RecursiveDirectoryIterator( $assetCleanUpCacheDir,
			    \RecursiveDirectoryIterator::SKIP_DOTS );

		    $totalFiles = 0;
		    $totalSize  = 0;

		    foreach (
			    new \RecursiveIteratorIterator( $dirItems, \RecursiveIteratorIterator::SELF_FIRST,
				    \RecursiveIteratorIterator::CATCH_GET_CHILD ) as $item
		    ) {
			    if ($item->isDir()) {
			    	echo '<br />';

				    $appendAfter = ' - ';

			    	if (is_writable($item)) {
					    $appendAfter .= ' <em><strong>writable</strong> directory</em>';
				    } else {
					    $appendAfter .= ' <em><strong style="color: red;">not writable</strong> directory</em>';
				    }
			    } elseif ($item->isFile()) {
			    	$appendAfter = '(<em>'.Misc::formatBytes($item->getSize()).'</em>)';

			    	echo '&nbsp;-&nbsp;';
			    }
			    echo $item.' '.$appendAfter.'<br />';
			    if ( $item->isFile() ) {
				    $totalSize += $item->getSize();
				    $totalFiles ++;
			    }
		    }

		    echo '<br />'.'Total Files: <strong>'.$totalFiles.'</strong> / Total Size: <strong>'.Misc::formatBytes($totalSize).'</strong>';
	    } else {
		    echo 'The directory does not exists.';
	    }

	    exit();
    }
}
