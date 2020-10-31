<?php
namespace WpAssetCleanUp;

/**
 *
 * Class Overview
 * @package WpAssetCleanUp
 */
class Overview
{
	/**
	 * @var array
	 */
	public $data = array();

	/**
	 * Overview constructor.
	 */
	public function __construct()
    {
        // [START] Clear load exceptions for a handle
	    $transientName = 'wpacu_load_exceptions_cleared';
	    if ( isset( $_POST['wpacu_action'], $_POST['wpacu_handle'], $_POST['wpacu_asset_type'] )
	         && ( $wpacuAction = $_POST['wpacu_action'] )
	         && ( $wpacuHandle = $_POST['wpacu_handle'] )
	         && ( $wpacuAssetType = $_POST['wpacu_asset_type'] ) && $wpacuAction === 'clear_load_exceptions'
        ) {
	        check_admin_referer('wpacu_clear_load_exceptions', 'wpacu_clear_load_exceptions_nonce');
            Maintenance::removeAllLoadExceptionsFor($wpacuHandle, $wpacuAssetType);
            set_transient($transientName, array('handle' => $wpacuHandle, 'type' => $wpacuAssetType));
            wp_redirect(admin_url('admin.php?page=wpassetcleanup_overview'));
            exit();
        }

	    if ($transientData = get_transient($transientName)) {
	        add_action('admin_notices', static function() use ($transientData, $transientName) {
		        $wpacuAssetTypeToPrint = ($transientData['type'] === 'styles') ? 'CSS' : 'JavaScript';
	            ?>
                <div class="notice wpacu-notice-info is-dismissible">
                    <p><span style="color: #008f9c; font-size: 26px; margin-right: 4px; vertical-align: text-bottom; margin-bottom: 0;" class="dashicons dashicons-yes"></span> <?php echo sprintf(__('The load exception rules for the `<strong>%s</strong>` %s handle have been removed.', 'wp-asset-clean-up'), $transientData['handle'], $wpacuAssetTypeToPrint); ?></p>
                </div>
                <?php
                delete_transient($transientName);
            }, PHP_INT_MAX);
        }
	    // [END] Clear load exceptions for a handle

	    // [START] Clear all rules for the chosen "orphaned" handle
	    $transientName = 'wpacu_all_rules_cleared';
	    if ( isset( $_POST['wpacu_action'], $_POST['wpacu_handle'], $_POST['wpacu_asset_type'] )
	         && ( $wpacuAction = $_POST['wpacu_action'] )
	         && ( $wpacuHandle = $_POST['wpacu_handle'] )
	         && ( $wpacuAssetType = $_POST['wpacu_asset_type'] ) && $wpacuAction === 'clear_all_rules'
	    ) {
		    check_admin_referer('wpacu_clear_all_rules', 'wpacu_clear_all_rules_nonce');
		    Maintenance::removeAllRulesFor($wpacuHandle, $wpacuAssetType);
		    set_transient('wpacu_all_rules_cleared', array('handle' => $wpacuHandle, 'type' => $wpacuAssetType));
		    wp_redirect(admin_url('admin.php?page=wpassetcleanup_overview'));
		    exit();
	    }

	    if ($transientData = get_transient($transientName)) {
		    add_action('admin_notices', static function() use ($transientData, $transientName) {
			    $wpacuAssetTypeToPrint = ($transientData['type'] === 'styles') ? 'CSS' : 'JavaScript';
			    ?>
                <div class="notice wpacu-notice-info is-dismissible">
                    <p><span style="color: #008f9c; font-size: 26px; margin-right: 4px; vertical-align: text-bottom; margin-bottom: 0;" class="dashicons dashicons-yes"></span> <?php echo sprintf(__('All the rules were cleared for the orphaned `<strong>%s</strong>` %s handle.', 'wp-asset-clean-up'), $transientData['handle'], $wpacuAssetTypeToPrint); ?></p>
                </div>
			    <?php
			    delete_transient($transientName);
		    }, PHP_INT_MAX);
	    }
	    // [END] Clear all rules for the chosen "orphaned" handle

	    // [START] Clear all redundant unload rules (if the site-wide one is already enabled)
	    $transientName = 'wpacu_all_redundant_unload_rules_cleared';
	    if ( isset( $_POST['wpacu_action'], $_POST['wpacu_handle'], $_POST['wpacu_asset_type'] )
	         && ( $wpacuAction = $_POST['wpacu_action'] )
	         && ( $wpacuHandle = $_POST['wpacu_handle'] )
	         && ( $wpacuAssetType = $_POST['wpacu_asset_type'] ) && $wpacuAction === 'clear_all_redundant_unload_rules'
	    ) {
		    check_admin_referer('wpacu_clear_all_redundant_rules', 'wpacu_clear_all_redundant_rules_nonce');
		    Maintenance::removeAllRedundantUnloadRulesFor($wpacuHandle, $wpacuAssetType);
		    set_transient($transientName, array('handle' => $wpacuHandle, 'type' => $wpacuAssetType));
		    wp_redirect(admin_url('admin.php?page=wpassetcleanup_overview'));
		    exit();
	    }

	    if ($transientData = get_transient($transientName)) {
		    add_action('admin_notices', static function() use ($transientData, $transientName) {
			    $wpacuAssetTypeToPrint = ($transientData['type'] === 'styles') ? 'CSS' : 'JavaScript';
			    ?>
                <div class="notice wpacu-notice-info is-dismissible">
                    <p><span style="color: #008f9c; font-size: 26px; margin-right: 4px; vertical-align: text-bottom; margin-bottom: 0;" class="dashicons dashicons-yes"></span> <?php echo sprintf(__('All the redundant unload rules were cleared for the `<strong>%s</strong>` %s handle, leaving the only one relevant: `site-wide (everywhere)`.', 'wp-asset-clean-up'), $transientData['handle'], $wpacuAssetTypeToPrint); ?></p>
                </div>
			    <?php
			    delete_transient($transientName);
		    }, PHP_INT_MAX);
	    }
	    // [END] Clear all redundant unload rules (if the site-wide one is already enabled)
    }

	/**
	 * @return array
	 */
	public static function handlesWithAtLeastOneRule()
    {
        global $wpdb;

	    $wpacuPluginId = WPACU_PLUGIN_ID;

	    $allHandles = array();

	    /*
		 * Per page rules (unload, load exceptions if a bulk rule is enabled, async & defer for SCRIPT tags)
		 */
	    // Homepage (Unloads)
	    $wpacuFrontPageUnloads = get_option(WPACU_PLUGIN_ID . '_front_page_no_load');

	    if ($wpacuFrontPageUnloads) {
		    $wpacuFrontPageUnloadsArray = @json_decode( $wpacuFrontPageUnloads, ARRAY_A );

		    foreach (array('styles', 'scripts') as $assetType) {
			    if ( isset( $wpacuFrontPageUnloadsArray[$assetType] ) && ! empty( $wpacuFrontPageUnloadsArray[$assetType] ) ) {
				    foreach ( $wpacuFrontPageUnloadsArray[$assetType] as $assetHandle ) {
					    $allHandles[$assetType][ $assetHandle ]['unload_on_home_page'] = 1;
				    }
			    }
		    }
	    }

	    // Homepage (Load Exceptions)
	    $wpacuFrontPageLoadExceptions = get_option(WPACU_PLUGIN_ID . '_front_page_load_exceptions');

	    if ($wpacuFrontPageLoadExceptions) {
		    $wpacuFrontPageLoadExceptionsArray = @json_decode( $wpacuFrontPageLoadExceptions, ARRAY_A );

		    foreach ( array('styles', 'scripts') as $assetType ) {
			    if ( isset( $wpacuFrontPageLoadExceptionsArray[$assetType] ) && ! empty( $wpacuFrontPageLoadExceptionsArray[$assetType] ) ) {
				    foreach ( $wpacuFrontPageLoadExceptionsArray[$assetType] as $assetHandle ) {
					    $allHandles[$assetType][ $assetHandle ]['load_exception_on_home_page'] = 1;
				    }
			    }
		    }
	    }

	    // Homepage (async, defer)
	    $wpacuFrontPageData = get_option(WPACU_PLUGIN_ID . '_front_page_data');

	    if ($wpacuFrontPageData) {
		    $wpacuFrontPageDataArray = @json_decode( $wpacuFrontPageData, ARRAY_A );
		    if ( isset($wpacuFrontPageDataArray['scripts']) && ! empty($wpacuFrontPageDataArray['scripts']) ) {
			    foreach ($wpacuFrontPageDataArray['scripts'] as $assetHandle => $assetData) {
				    if (isset($assetData['attributes']) && ! empty($assetData['attributes'])) {
					    // async, defer attributes
					    $allHandles['scripts'][ $assetHandle ]['script_attrs']['home_page'] = $assetData['attributes'];
				    }
			    }
		    }

		    // Do not apply "async", "defer" exceptions (e.g. "defer" is applied site-wide, except the home page)
		    if (isset($wpacuFrontPageDataArray['scripts_attributes_no_load']) && ! empty($wpacuFrontPageDataArray['scripts_attributes_no_load'])) {
			    foreach ($wpacuFrontPageDataArray['scripts_attributes_no_load'] as $assetHandle => $assetAttrsNoLoad) {
				    $allHandles['scripts'][$assetHandle]['attrs_no_load']['home_page'] = $assetAttrsNoLoad;
			    }
		    }
	    }

	    // Get all Asset CleanUp (Pro) meta keys from all WordPress meta tables where it can be possibly used
	    foreach (array($wpdb->postmeta, $wpdb->termmeta, $wpdb->usermeta) as $tableName) {
		    $wpacuGetValuesQuery = <<<SQL
SELECT * FROM `{$tableName}`
WHERE meta_key IN('_{$wpacuPluginId}_no_load', '_{$wpacuPluginId}_data', '_{$wpacuPluginId}_load_exceptions')
SQL;
		    $wpacuMetaData = $wpdb->get_results( $wpacuGetValuesQuery, ARRAY_A );

		    foreach ( $wpacuMetaData as $wpacuValues ) {
			    $decodedValues = @json_decode( $wpacuValues['meta_value'], ARRAY_A );

			    if ( empty( $decodedValues ) ) {
				    continue;
			    }

			    // $refId is the ID for the targeted element from the meta table which could be: post, taxonomy ID or user ID
			    if ($tableName === $wpdb->postmeta) {
				    $refId = $wpacuValues['post_id'];
				    $refKey = 'post';
			    } elseif ($tableName === $wpdb->termmeta) {
				    $refId = $wpacuValues['term_id'];
				    $refKey = 'term';
			    } else {
				    $refId = $wpacuValues['user_id'];
				    $refKey = 'user';
			    }

			    if ( $wpacuValues['meta_key'] === '_' . $wpacuPluginId . '_no_load' ) {
				    foreach ( $decodedValues as $assetType => $assetHandles ) {
					    foreach ( $assetHandles as $assetHandle ) {
						    // Unload it on this page
						    $allHandles[ $assetType ][ $assetHandle ]['unload_on_this_page'][$refKey][] = $refId;
					    }
				    }
			    } elseif ( $wpacuValues['meta_key'] === '_' . $wpacuPluginId . '_load_exceptions' ) {
				    foreach ( $decodedValues as $assetType => $assetHandles ) {
					    foreach ( $assetHandles as $assetHandle ) {
						    // If bulk unloaded, 'Load it on this page'
						    $allHandles[ $assetType ][ $assetHandle ]['load_exception_on_this_page'][$refKey][] = $refId;
					    }
				    }
			    } elseif ( $wpacuValues['meta_key'] === '_' . $wpacuPluginId . '_data' ) {
				    if ( isset( $decodedValues['scripts'] ) && ! empty( $decodedValues['scripts'] ) ) {
					    foreach ( $decodedValues['scripts'] as $assetHandle => $scriptData ) {
						    if ( isset( $scriptData['attributes'] ) && ! empty( $scriptData['attributes'] ) ) {
							    // async, defer attributes
							    $allHandles['scripts'][ $assetHandle ]['script_attrs'][$refKey][$refId] = $scriptData['attributes'];
						    }
					    }
				    }

				    if ( isset( $decodedValues['scripts_attributes_no_load'] ) && ! empty( $decodedValues['scripts_attributes_no_load'] ) ) {
					    foreach ( $decodedValues['scripts_attributes_no_load'] as $assetHandle => $scriptNoLoadAttrs ) {
						    $allHandles['scripts'][$assetHandle]['attrs_no_load'][$refKey][$refId] = $scriptNoLoadAttrs;
					    }
				    }
			    }
		    }
	    }

	    /*
		 * Global (Site-wide) Rules: Preloading, Position changing, Unload via RegEx, etc.
		 */
	    $wpacuGlobalData = get_option(WPACU_PLUGIN_ID . '_global_data');
	    $wpacuGlobalDataArray = @json_decode($wpacuGlobalData, ARRAY_A);

	    $allPossibleDataTypes = array('load_it_logged_in', 'preloads', 'positions', 'notes', 'ignore_child', 'everywhere', 'date', '404', 'search');

	    foreach (array('styles', 'scripts') as $assetType) {
		    if ($assetType === 'scripts' && isset( $wpacuGlobalDataArray[ $assetType ])) {
                foreach (array_keys($wpacuGlobalDataArray[ $assetType ]) as $dataType) {
                    if ( strpos( $dataType, 'custom_post_type_archive_' ) !== false ) {
                        $allPossibleDataTypes[] = $dataType;
                    }
                }

			    }

		    foreach ($allPossibleDataTypes as $dataType) {
			    if ( isset( $wpacuGlobalDataArray[ $assetType ][$dataType] ) && ! empty( $wpacuGlobalDataArray[ $assetType ][$dataType] ) ) {
				    foreach ( $wpacuGlobalDataArray[ $assetType ][$dataType] as $assetHandle => $dataValue ) {
					    if ($dataType === 'everywhere' && $assetType === 'scripts' && isset($dataValue['attributes'])) {
						    if (count($dataValue['attributes']) === 0) {
							    continue;
						    }
						    // async/defer applied site-wide
						    $allHandles[ $assetType ][ $assetHandle ]['script_site_wide_attrs'] = $dataValue['attributes'];
					    } elseif ($dataType !== 'everywhere' && $assetType === 'scripts' && isset($dataValue['attributes'])) {
						    // For date, 404, search pages
						    $allHandles[ $assetType ][ $assetHandle ]['script_attrs'][$dataType] = $dataValue['attributes'];
					    } else {
						    $allHandles[ $assetType ][ $assetHandle ][ $dataType ] = $dataValue;
					    }
				    }
			    }
		    }

		    foreach (array('unload_regex', 'load_regex') as $unloadType) {
			    if (isset($wpacuGlobalDataArray[$assetType][$unloadType]) && ! empty($wpacuGlobalDataArray[$assetType][$unloadType])) {
				    foreach ($wpacuGlobalDataArray[$assetType][$unloadType] as $assetHandle => $unloadData) {
					    if (isset($unloadData['enable'], $unloadData['value']) && $unloadData['enable'] && $unloadData['value']) {
						    $allHandles[ $assetType ][ $assetHandle ][$unloadType] = $unloadData['value'];
					    }
				    }
			    }
		    }
	    }

	    // Do not apply "async", "defer" exceptions (e.g. "defer" is applied site-wide, except the 404, search, date)
	    if (isset($wpacuGlobalDataArray['scripts_attributes_no_load']) && ! empty($wpacuGlobalDataArray['scripts_attributes_no_load'])) {
		    foreach ($wpacuGlobalDataArray['scripts_attributes_no_load'] as $unloadedIn => $unloadedInValues) {
			    foreach ($unloadedInValues as $assetHandle => $assetAttrsNoLoad) {
				    $allHandles['scripts'][$assetHandle]['attrs_no_load'][$unloadedIn] = $assetAttrsNoLoad;
			    }
		    }
	    }

	    /*
		 * Unload Site-Wide (Everywhere) Rules: Preloading, Position changing, Unload via RegEx, etc.
		 */
	    $wpacuGlobalUnloadData = get_option(WPACU_PLUGIN_ID . '_global_unload');
	    $wpacuGlobalUnloadDataArray = @json_decode($wpacuGlobalUnloadData, ARRAY_A);

	    foreach (array('styles', 'scripts') as $assetType) {
		    if (isset($wpacuGlobalUnloadDataArray[$assetType]) && ! empty($wpacuGlobalUnloadDataArray[$assetType])) {
			    foreach ($wpacuGlobalUnloadDataArray[$assetType] as $assetHandle) {
				    $allHandles[ $assetType ][ $assetHandle ]['unload_site_wide'] = 1;
			    }
		    }
	    }

	    /*
		* Bulk Unload Rules - post, page, custom post type (e.g. product, download), taxonomy (e.g. category), 404, date, archive (for custom post type) with pagination etc.
		*/
	    $wpacuBulkUnloadData = get_option(WPACU_PLUGIN_ID . '_bulk_unload');
	    $wpacuBulkUnloadDataArray = @json_decode($wpacuBulkUnloadData, ARRAY_A);

	    foreach (array('styles', 'scripts') as $assetType) {
		    if (isset($wpacuBulkUnloadDataArray[$assetType]) && ! empty($wpacuBulkUnloadDataArray[$assetType])) {
			    foreach ($wpacuBulkUnloadDataArray[$assetType] as $unloadBulkType => $unloadBulkValues) {
				    if (empty($unloadBulkValues)) {
					    continue;
				    }

				    // $unloadBulkType could be 'post_type', 'date', '404', 'taxonomy', 'search', 'custom_post_type_archive_[post_type_name_here]', etc.
				    if ($unloadBulkType === 'post_type') {
					    foreach ($unloadBulkValues as $postType => $assetHandles) {
						    foreach ($assetHandles as $assetHandle) {
							    $allHandles[ $assetType ][ $assetHandle ]['unload_bulk']['post_type'][] = $postType;
						    }
					    }
				    } elseif (in_array($unloadBulkType, array('date', '404', 'search')) || (strpos($unloadBulkType, 'custom_post_type_archive_') !== false)) {
					    foreach ($unloadBulkValues as $assetHandle) {
						    $allHandles[ $assetType ][ $assetHandle ]['unload_bulk'][$unloadBulkType] = 1;
					    }
				    } elseif ($unloadBulkType === 'taxonomy') {
					    foreach ($unloadBulkValues as $taxonomyType => $assetHandles) {
						    foreach ($assetHandles as $assetHandle) {
							    $allHandles[ $assetType ][ $assetHandle ]['unload_bulk']['taxonomy'][] = $taxonomyType;
						    }
					    }
				    } elseif ($unloadBulkType === 'author' && isset($unloadBulkValues['all']) && ! empty($unloadBulkValues['all'])) {
					    foreach ($unloadBulkValues['all'] as $assetHandle) {
						    $allHandles[ $assetType ][ $assetHandle ]['unload_bulk']['author'] = 1;
					    }
				    }
			    }
		    }
	    }

	    if (isset($allHandles['styles'])) {
		    ksort($allHandles['styles']);
	    }

	    if (isset($allHandles['scripts'])) {
		    ksort($allHandles['scripts']);
	    }

	    return $allHandles;
    }

	/**
	 *
	 */
	public function pageOverview()
	{
		$allHandles = self::handlesWithAtLeastOneRule();
		$this->data['handles'] = $allHandles;

		if (isset($this->data['handles']['styles']) || isset($this->data['handles']['scripts'])) {
			// Only fetch the assets information if there is something to be shown
			// to avoid useless queries to the database
			$this->data['assets_info'] = Main::getHandlesInfo();
			}

		Main::instance()->parseTemplate('admin-page-overview', $this->data, true);
	}

	/**
	 * @param $handle
	 * @param $assetType
	 * @param $data
	 * @param string $for ('default': bulk unloads, regex unloads)
	 */
	public static function renderHandleTd($handle, $assetType, $data, $for = 'default')
	{
		global $wp_version;

		$handleData = '';
		$isCoreFile = false; // default

        if (isset($data['handles'][$assetType][$handle]) && $data['handles'][$assetType][$handle]) {
            $handleData = $data['handles'][$assetType][$handle];
        }

        if ( $for === 'default' ) {
            // Show the original "src" and "ver, not the altered one
            // (in case filters such as "wpacu_{$handle}_(css|js)_handle_obj" were used to load alternative versions of the file, depending on the situation)
            $srcKey = isset($data['assets_info'][ $assetType ][ $handle ]['src_origin']) ? 'src_origin' : 'src';
	        $verKey = isset($data['assets_info'][ $assetType ][ $handle ]['ver_origin']) ? 'ver_origin' : 'ver';

            $src = (isset( $data['assets_info'][ $assetType ][ $handle ][$srcKey] ) && $data['assets_info'][ $assetType ][ $handle ][$srcKey]) ? $data['assets_info'][ $assetType ][ $handle ][$srcKey] : false;

            $isExternalSrc = true;

            if (Misc::getLocalSrc($src)
                || strpos($src, '/?') !== false // Dynamic Local URL
                || strpos(str_replace(site_url(), '', $src), '?') === 0 // Starts with ? right after the site url (it's a local URL)
            ) {
                $isExternalSrc = false;
                $isCoreFile = Misc::isCoreFile($data['assets_info'][$assetType][$handle]);
            }

            if (strpos($src, '/') === 0 && strpos($src, '//') !== 0) {
                $src = site_url() . $src;
            }

	        $ver = $wp_version; // default
	        if (isset($data['assets_info'][ $assetType ][ $handle ][$verKey] ) && $data['assets_info'][ $assetType ][ $handle ][$verKey] ) {
		        $ver = is_array($data['assets_info'][ $assetType ][ $handle ][$verKey] )
			        ? implode(',', $data['assets_info'][ $assetType ][ $handle ][$verKey] )
			        : $data['assets_info'][ $assetType ][ $handle ][$verKey] ;
	        }
	        ?>
            <strong><span style="color: green;"><?php echo $handle; ?></span></strong>
            <small><em>v<?php echo $ver; ?></em></small>
            <?php
            if ($isCoreFile) {
                ?>
                <span title="WordPress Core File" style="font-size: 15px; vertical-align: middle;" class="dashicons dashicons-wordpress-alt wpacu-tooltip"></span>
                <?php
            }
            ?>
            <?php
            // [wpacu_pro]
            // If called from "Bulk Changes" -> "Preloads"
            $preloadedStatus = isset($data['assets_info'][ $assetType ][ $handle ]['preloaded_status']) ? $data['assets_info'][ $assetType ][ $handle ]['preloaded_status'] : false;
            if ($preloadedStatus === 'async') { echo '&nbsp;(<strong><em>'.$preloadedStatus.'</em></strong>)'; }
            // [/wpacu_pro]

            $handleExtras = array();

            // If called from "Overview"
	        if (isset($handleData['preloads']) && $handleData['preloads']) {
		        $handleExtras[0] = '<span style="font-weight: 600;">Preloaded</span>';

	            if ($handleData['preloads'] === 'async') {
		            $handleExtras[0] .= ' (async)';
                }
	        }

	        if (isset($handleData['positions']) && $handleData['positions']) {
                $handleExtras[1] = '<span style="color: #004567; font-weight: 600;">Moved to <code>&lt;'.$handleData['positions'].'&gt;</code></span>';
            }

	        /*
	         * 1) Per page (homepage, a post, a category, etc.)
	         * Async, Defer attributes
	         */
            // Per home page
	        if (isset($handleData['script_attrs']['home_page']) && ! empty($handleData['script_attrs']['home_page'])) {
		        ksort($handleData['script_attrs']['home_page']);
		        $handleExtras[2] = 'Homepage attributes: <strong>'.implode(', ', $handleData['script_attrs']['home_page']).'</strong>';
	        }

	        // Date archive pages
	        if (isset($handleData['script_attrs']['date']) && ! empty($handleData['script_attrs']['date'])) {
		        ksort($handleData['script_attrs']['date']);
		        $handleExtras[22] = 'Date archive attributes: <strong>'.implode(', ', $handleData['script_attrs']['date']).'</strong>';
	        }

	        // 404 page
	        if (isset($handleData['script_attrs']['404']) && ! empty($handleData['script_attrs']['404'])) {
		        ksort($handleData['script_attrs']['404']);
		        $handleExtras[23] = '404 Not Found attributes: <strong>'.implode(', ', $handleData['script_attrs']['404']).'</strong>';
	        }

	        // Search results page
	        if (isset($handleData['script_attrs']['search']) && ! empty($handleData['script_attrs']['search'])) {
		        ksort($handleData['script_attrs']['search']);
		        $handleExtras[24] = '404 Not Found attributes: <strong>'.implode(', ', $handleData['script_attrs']['search']).'</strong>';
	        }

            // Archive page for Custom Post Type (those created via theme editing or via plugins such as "Custom Post Type UI")
            $scriptAttrsStr = (isset($handleData['script_attrs']) && is_array($handleData['script_attrs'])) ? implode('', array_keys($handleData['script_attrs'])) : '';

	        if (strpos($scriptAttrsStr, 'custom_post_type_archive_') !== false) {
	            $keyNo = 225;
	            foreach ($handleData['script_attrs'] as $scriptAttrsKey => $scriptAttrsValue) {
	                $customPostTypeName = str_replace('custom_post_type_archive_', '', $scriptAttrsKey);
		            $handleExtras[$keyNo] = 'Archive custom post type page "'.$customPostTypeName.'" attributes: <strong>'.implode(', ', $handleData['script_attrs'][$scriptAttrsKey]).'</strong>';
		            $keyNo++;
	            }
	        }

	        // Per post page
            if (isset($handleData['script_attrs']['post']) && ! empty($handleData['script_attrs']['post'])) {
	            $handleExtras[3] = 'Per post attributes: ';

		        $postsList = '';

		        ksort($handleData['script_attrs']['post']);

		        foreach ($handleData['script_attrs']['post'] as $postId => $attrList) {
			        $postData   = get_post($postId);
			        $postTitle  = $postData->post_title;
			        $postType   = $postData->post_type;
			        $postsList .= '<a title="Post Title: '.$postTitle.', Post Type: '.$postType.'" class="wpacu-tooltip" target="_blank" href="'.admin_url('post.php?post='.$postId.'&action=edit').'">'.$postId.'</a> - <strong>'.implode(', ', $attrList).'</strong> / ';
		        }

	            $handleExtras[3] .= rtrim($postsList, ' / ');
	        }

            // User archive page (specific author)
	        if (isset($handleData['script_attrs']['user']) && ! empty($handleData['script_attrs']['user'])) {
		        $handleExtras[31] = 'Per author page attributes: ';

		        $authorPagesList = '';

		        ksort($handleData['script_attrs']['user']);

		        foreach ($handleData['script_attrs']['user'] as $userId => $attrList) {
			        $authorLink = get_author_posts_url(get_the_author_meta('ID', $userId));
			        $authorRelLink = str_replace(site_url(), '', $authorLink);

			        $authorPagesList .= '<a target="_blank" href="'.$authorLink.'">'.$authorRelLink.'</a> - <strong>'.implode(', ', $attrList).'</strong> | ';
		        }

		        $authorPagesList = trim($authorPagesList, ' | ');

		        $handleExtras[31] .= rtrim($authorPagesList, ' / ');
	        }

            // Per category page
            if (isset($handleData['script_attrs']['term']) && ! empty($handleData['script_attrs']['term'])) {
	            $handleExtras[33] = 'Per taxonomy attributes: ';

                $taxPagesList = '';

	            foreach ($handleData['script_attrs']['term'] as $termId => $attrList) {
		            $taxData     = get_term( $termId );
		            $taxonomy    = $taxData->taxonomy;
		            $termLink    = get_term_link( $taxData, $taxonomy );
		            $termRelLink = str_replace( site_url(), '', $termLink );

		            $taxPagesList .= '<a href="' . $termRelLink . '">' . $termRelLink . '</a> - <strong>'.implode(', ', $attrList).'</strong> | ';
	            }

	            $taxPagesList = trim($taxPagesList, ' | ');

	            $handleExtras[33] .= rtrim($taxPagesList, ' / ');
            }

            /*
             * 2) Site-wide type
             * Any async, defer site-wide attributes? Exceptions will be also shown
             */
	        if (isset($handleData['script_site_wide_attrs'])) {
		        $handleExtras[4] = 'Site-wide attributes: ';
		        foreach ( $handleData['script_site_wide_attrs'] as $attrValue ) {
			        $handleExtras[4] .= '<strong>' . $attrValue . '</strong>';

			        // Are there any exceptions? e.g. async, defer unloaded site-wide, but loaded on the homepage
			        if ( isset( $handleData['attrs_no_load'] ) && ! empty( $handleData['attrs_no_load'] ) ) {
				        // $attrSetIn could be 'home_page', 'term', 'user', 'date', '404', 'search'
				        $handleExtras[4] .= ' <em>(with exceptions from applying added for these pages: ';

				        $handleAttrsExceptionsList = '';

				        foreach ( $handleData['attrs_no_load'] as $attrSetIn => $attrSetValues ) {
					        if ( $attrSetIn === 'home_page' && in_array($attrValue, $attrSetValues) ) {
						        $handleAttrsExceptionsList .= ' Homepage, ';
					        }

					        if ( $attrSetIn === 'date' && in_array($attrValue, $attrSetValues) ) {
						        $handleAttrsExceptionsList .= ' Date Archive, ';
					        }

					        if ( (int)$attrSetIn === 404 && in_array($attrValue, $attrSetValues) ) {
						        $handleAttrsExceptionsList .= ' 404 Not Found, ';
					        }

					        if ( $attrSetIn === 'search' && in_array($attrValue, $attrSetValues) ) {
						        $handleAttrsExceptionsList .= ' Search Results, ';
					        }

					        if (strpos($attrSetIn, 'custom_post_type_archive_') !== false) {
						        $customPostTypeName = str_replace('custom_post_type_archive_', '', $attrSetIn);
					            $handleAttrsExceptionsList .= ' Archive "'.$customPostTypeName.'" custom post type, ';
					        }

					        // Post pages such as posts, pages, product (WooCommerce), download (Easy Digital Downloads), etc.
					        if ( $attrSetIn === 'post' ) {
						        $postPagesList = '';

						        foreach ( $attrSetValues as $postId => $attrSetValuesTwo ) {
							        if (! in_array($attrValue, $attrSetValuesTwo)) {
								        continue;
							        }

							        $postData   = get_post($postId);
							        $postTitle  = $postData->post_title;
							        $postType   = $postData->post_type;

							        $postPagesList .= '<a title="Post Title: '.$postTitle.', Post Type: '.$postType.'" class="wpacu-tooltip" target="_blank" href="'.admin_url('post.php?post='.$postId.'&action=edit').'">'.$postId.'</a> | ';
						        }

						        if ($postPagesList) {
						            $postPagesList = trim( $postPagesList, ' | ' ).', ';
						            $handleAttrsExceptionsList .= $postPagesList;
						        }
					        }

					        // Taxonomy pages such as category archive, product category in WooCommerce
					        if ( $attrSetIn === 'term' ) {
						        $taxPagesList = '';

						        foreach ( $attrSetValues as $termId => $attrSetValuesTwo ) {
						            if (! in_array($attrValue, $attrSetValuesTwo)) {
						                continue;
                                    }

							        $taxData     = get_term( $termId );
							        $taxonomy    = $taxData->taxonomy;
							        $termLink    = get_term_link( $taxData, $taxonomy );
							        $termRelLink = str_replace( site_url(), '', $termLink );

							        $taxPagesList .= '<a href="' . $termRelLink . '">' . $termRelLink . '</a> | ';
						        }

						        if ($taxPagesList) {
							        $taxPagesList = trim( $taxPagesList, ' | ' ) . ', ';
							        $handleAttrsExceptionsList .= $taxPagesList;
						        }
					        }

					        // Author archive pages (e.g. /author/john/page/2/)
					        if ($attrSetIn === 'user') {
						        $authorPagesList = '';

						        foreach ( $attrSetValues as $userId => $attrSetValuesTwo ) {
							        if (! in_array($attrValue, $attrSetValuesTwo)) {
								        continue;
							        }

							        $authorLink = get_author_posts_url(get_the_author_meta('ID', $userId));
							        $authorRelLink = str_replace(site_url(), '', $authorLink);

							        $authorPagesList .= '<a target="_blank" href="'.$authorLink.'">'.$authorRelLink.'</a> | ';
						        }

						        if ($authorPagesList) {
						            $authorPagesList = trim( $authorPagesList, ' | ' ).', ';
						            $handleAttrsExceptionsList .= $authorPagesList;
						        }
                            }
				        }

				        $handleAttrsExceptionsList = trim($handleAttrsExceptionsList, ', ');

				        $handleExtras[4] .= $handleAttrsExceptionsList;
				        $handleExtras[4] .= '</em>), ';
			        }

			        $handleExtras[4] .= ', ';
		        }

		        $handleExtras[4] = trim($handleExtras[4], ', ');
	        }

	        if (! empty($handleExtras)) {
		        echo '<small>' . implode( ' <span style="font-weight: 300; color: grey;">/</span> ', $handleExtras ) . '</small>';
	        }

            if ( $src ) {
                $verDb = (isset($data['assets_info'][ $assetType ][ $handle ][$verKey]) && $data['assets_info'][ $assetType ][ $handle ][$verKey]) ? $data['assets_info'][ $assetType ][ $handle ][$verKey] : false;

		        $appendAfterSrc = (strpos($src, '?') === false) ? '?' : '&';

		        if ( $verDb ) {
		            if (is_array($verDb)) {
			            $appendAfterSrc .= http_build_query(array('ver' => $data['assets_info'][ $assetType ][ $handle ][$verKey]));
                    } else {
			            $appendAfterSrc .= 'ver='.$ver;
                    }
		        } else {
			        $appendAfterSrc .= 'ver='.$wp_version; // default
		        }
		        ?>
                <div><a <?php if ($isExternalSrc) { ?> data-wpacu-external-source="<?php echo $src . $appendAfterSrc; ?>" <?php } ?> href="<?php echo $src . $appendAfterSrc; ?>" target="_blank"><small><?php echo str_replace( site_url(), '', $src ); ?></small></a> <?php if ($isExternalSrc) { ?><span data-wpacu-external-source-status></span><?php } ?></div>
                <?php
	            if ($maybeInactiveAsset = \WpAssetCleanUp\Misc::maybeIsInactiveAsset($src)) {
		            $clearAllRulesConfirmMsg = sprintf(esc_attr(__('This will clear all rules (unloads, load exceptions &amp; other settings) for the `%s` CSS handle', 'wp-asset-clean-up')), $handle)
                        . esc_js("\n\n") . esc_attr(__('Click \'OK\' to confirm the action', 'wp-asset-clean-up'));
		            $wpacuNonceField = wp_nonce_field('wpacu_clear_all_rules', 'wpacu_clear_all_rules_nonce');
	                ?>
                    <div>
                        <small><strong>Note:</strong> <span style="color: darkred;">The plugin `<strong><?php echo $maybeInactiveAsset; ?></strong>` seems to be inactive, thus any rules set are also inactive &amp; irrelevant, unless you re-activate the plugin.</span></small>
                        <form method="post" action="" style="display: inline-block;">
                            <input type="hidden" name="wpacu_action" value="clear_all_rules" />
                            <input type="hidden" name="wpacu_handle" value="<?php echo $handle; ?>" />
                            <input type="hidden" name="wpacu_asset_type" value="<?php echo $assetType; ?>" />
                            <?php echo $wpacuNonceField; ?>
                            <script type="text/javascript">
                                var wpacuClearAllRulesConfirmMsg = '<?php echo $clearAllRulesConfirmMsg; ?>';
                            </script>
                            <button onclick="return confirm(wpacuClearAllRulesConfirmMsg);" type="submit" class="button button-secondary"><span class="dashicons dashicons-trash" style="vertical-align: text-bottom;"></span> Clear all rules for this "orphaned" handle</button>
                        </form>
                    </div>
		            <?php
	            }
            }

            // Any note?
            if (isset($handleData['notes']) && $handleData['notes']) {
                ?>
                <div><small><span class="dashicons dashicons-welcome-write-blog"></span> Note: <em><?php echo ucfirst(htmlspecialchars($data['handles'][$assetType][$handle]['notes'])); ?></em></small></div>
                <?php
            }
            ?>
        <?php
        }
	}

	/**
	 * @param $handleData
	 *
	 * @return mixed
	 */
	public static function renderHandleChangesOutput($handleData)
	{
		$handleChangesOutput = array();
		$anyGroupPostUnloadRule = false; // default (turns to true if any unload rule that applies on multiple pages for posts is set)
		$anyLoadExceptionRule = false; // default (turns to true if any load exception rule is set)

		// It could turn to "true" IF the site-wide rule is turned ON and there are other unload rules on top of it (useless ones in this case)
		$hasRedundantUnloadRules = false;

		// Site-wide
		if (isset($handleData['unload_site_wide'])) {
			$handleChangesOutput['site_wide'] = '<span style="color: #cc0000;">Unloaded site-wide (everywhere)</span>';
			$anyGroupPostUnloadRule = true;
		}

		// Bulk unload (on all posts, categories, etc.)
		if (isset($handleData['unload_bulk'])) {
			$handleChangesOutput['bulk'] = '';

			if (isset($handleData['unload_bulk']['post_type'])) {
				foreach ($handleData['unload_bulk']['post_type'] as $postType) {
					$handleChangesOutput['bulk'] .= ' Unloaded on all pages of <strong>' . $postType . '</strong> post type, ';
					$anyGroupPostUnloadRule = true;
				}
			}

			if (isset($handleData['unload_bulk']['taxonomy']) && ! empty($handleData['unload_bulk']['taxonomy'])) {
				$handleChangesOutput['bulk'] .= ' Unloaded for all pages belonging to the following taxonomies: <strong>'.implode(', ', $handleData['unload_bulk']['taxonomy']).'</strong>, ';
				$anyGroupPostUnloadRule = true;
			}

			$unloadBulkKeys = isset($handleData['unload_bulk']) ? array_keys($handleData['unload_bulk']) : array();
			$unloadBulkKeysStr = implode('', $unloadBulkKeys);

			if (isset($handleData['unload_bulk']['date'])
                || isset($handleData['unload_bulk']['404'])
                || isset($handleData['unload_bulk']['search'])
                || (strpos($unloadBulkKeysStr, 'custom_post_type_archive_') !== false)
            ) {
				foreach ($handleData['unload_bulk'] as $bulkType => $bulkValue) {
					if ($bulkType === 'date' && $bulkValue === 1) {
						$handleChangesOutput['bulk'] .= ' Unloaded on all archive `Date` pages (any date), ';
					}
					if ($bulkType === 'search' && $bulkValue === 1) {
						$handleChangesOutput['bulk'] .= ' Unloaded on `Search` page (any keyword), ';
					}
					if ($bulkType === 404 && $bulkValue === 1) {
						$handleChangesOutput['bulk'] .= ' Unloaded on `404 Not Found` page (any URL), ';
					}
					if (strpos($bulkType, 'custom_post_type_archive_') !== false) {
					    $customPostType = str_replace('custom_post_type_archive_', '', $bulkType);
						$handleChangesOutput['bulk'] .= ' Unloaded on the archive (list of posts) page of `'.$customPostType.'` custom post type';
					}
				}
			}

			if (isset($handleData['unload_bulk']['author']) && $handleData['unload_bulk']['author']) {
				$handleChangesOutput['bulk'] .= ' Unloaded on all author pages, ';
			}

			$handleChangesOutput['bulk'] = rtrim($handleChangesOutput['bulk'], ', ');

			if (isset($handleChangesOutput['site_wide'])) {
				$handleChangesOutput['bulk'] .= ' * <em>unnecessary, as it\'s already unloaded site-wide</em>';
				$hasRedundantUnloadRules = true;
			}
		}

		if (isset($handleData['unload_on_home_page']) && $handleData['unload_on_home_page']) {
			$handleChangesOutput['on_home_page'] = '<span style="color: #cc0000;">Unloaded</span> on the <a target="_blank" href="'.Misc::getPageUrl(0).'">homepage</a>';

			if (isset($handleChangesOutput['site_wide'])) {
				$handleChangesOutput['on_home_page'] .= ' * <em>unnecessary, as it\'s already unloaded site-wide</em>';
				$hasRedundantUnloadRules = true;
			}
        }

		if (isset($handleData['load_exception_on_home_page']) && $handleData['load_exception_on_home_page']) {
			$handleChangesOutput['load_exception_on_home_page'] = '<span style="color: green;">Loaded (as an exception)</span> on the <a target="_blank" href="'.Misc::getPageUrl(0).'">homepage</a>';
			$anyLoadExceptionRule = true;
		}

		// On this page: post, page, custom post type
		if (isset($handleData['unload_on_this_page']['post'])) {
			$handleChangesOutput['on_this_post'] = 'Unloaded in the following posts: ';

			$postsList = '';

			sort($handleData['unload_on_this_page']['post']);

			foreach ($handleData['unload_on_this_page']['post'] as $postId) {
				$postData   = get_post($postId);
				$postTitle  = $postData->post_title;
				$postType   = $postData->post_type;
				$postsList .= '<a title="Post Title: '.$postTitle.', Post Type: '.$postType.'" class="wpacu-tooltip" target="_blank" href="'.admin_url('post.php?post='.$postId.'&action=edit').'">'.$postId.'</a>, ';
			}

			$handleChangesOutput['on_this_post'] .= rtrim($postsList, ', ');

			if (isset($handleChangesOutput['site_wide'])) {
				$handleChangesOutput['on_this_post'] .= ' * <em>unnecessary, as it\'s already unloaded site-wide</em>';
				$hasRedundantUnloadRules = true;
            }
		}

		// Unload on this page: taxonomy such as 'category', 'product_cat' (specific one, not all categories)
		if (isset($handleData['unload_on_this_page']['term'])) {
			$handleChangesOutput['on_this_tax'] = '<span style="color: #cc0000;">Unloaded</span> in the following pages: ';

			$taxList = '';

			sort($handleData['unload_on_this_page']['term']);

			foreach ($handleData['unload_on_this_page']['term'] as $termId) {
				$taxData   = get_term($termId);
				$taxonomy = $taxData->taxonomy;
				$termLink = get_term_link($taxData, $taxonomy);
				$termRelLink = str_replace(site_url(), '', $termLink);

				$taxList .= '<a target="_blank" href="'.$termLink.'">'.$termRelLink.'</a>, ';
			}

			$handleChangesOutput['on_this_tax'] .= rtrim($taxList, ', ');

			if (isset($handleChangesOutput['site_wide'])) {
				$handleChangesOutput['on_this_tax'] .= ' * <em>unnecessary, as it\'s already unloaded site-wide</em>';
				$hasRedundantUnloadRules = true;
			}
		}

		if (isset($handleData['unload_on_this_page']['user'])) {
			$handleChangesOutput['on_this_tax'] = '<span style="color: #cc0000;">Unloaded</span> in the following author pages: ';

			$taxList = '';

			sort($handleData['unload_on_this_page']['user']);

			foreach ($handleData['unload_on_this_page']['user'] as $userId) {
				$authorLink = get_author_posts_url(get_the_author_meta('ID', $userId));
				$authorRelLink = str_replace(site_url(), '', $authorLink);

				$taxList .= '<a target="_blank" href="'.$authorLink.'">'.$authorRelLink.'</a>, ';
			}

			$handleChangesOutput['on_this_tax'] .= rtrim($taxList, ', ');

			if (isset($handleChangesOutput['site_wide'])) {
				$handleChangesOutput['on_this_tax'] .= ' * <em>unnecessary, as it\'s already unloaded site-wide</em>';
				$hasRedundantUnloadRules = true;
			}
		}

		// Unload via RegEx
		if (isset($handleData['unload_regex']) && $handleData['unload_regex']) {
			$handleChangesOutput['unloaded_via_regex'] = '<span style="color: #cc0000;">Unloads if</span> the request URI (from the URL) matches this RegEx(es): <code>'.nl2br($handleData['unload_regex']).'</code>';

			if (isset($handleChangesOutput['site_wide'])) {
				$handleChangesOutput['unloaded_via_regex'] .= ' * <em>unnecessary, as it\'s already unloaded site-wide</em>';
				$hasRedundantUnloadRules = true;
			}

			$anyGroupPostUnloadRule = true;
		}

		// Maybe it has other unload rules on top of the site-wide one (which covers everything)
		if ($hasRedundantUnloadRules) {
			$clearRedundantUnloadRulesConfirmMsg = sprintf(esc_js(__('This will clear all redundant (useless) unload rules for the `%s` CSS handle as there\'s already a site-wide rule applied.', 'wp-asset-clean-up')), $handleData['handle'])
			                                       . esc_js("\n\n") . esc_js(__('Click \'OK\' to confirm the action', 'wp-asset-clean-up'));
			$wpacuNonceField = wp_nonce_field('wpacu_clear_all_redundant_rules', 'wpacu_clear_all_redundant_rules_nonce');
			$clearRedundantUnloadRulesArea = <<<HTML
<form method="post" action="" style="display: inline-block;">
<input type="hidden" name="wpacu_action" value="clear_all_redundant_unload_rules" />
<input type="hidden" name="wpacu_handle" value="{$handleData['handle']}" />
<input type="hidden" name="wpacu_asset_type" value="{$handleData['asset_type']}" />
{$wpacuNonceField}
<script type="text/javascript">
var wpacuClearRedundantUnloadRulesConfirmMsg = '{$clearRedundantUnloadRulesConfirmMsg}';
</script>
<button onclick="return confirm(wpacuClearRedundantUnloadRulesConfirmMsg);" type="submit" class="button button-secondary"><span class="dashicons dashicons-trash" style="vertical-align: text-bottom;"></span> Clear all redundant unload rules</button>
</form>
HTML;
			$handleChangesOutput['has_redundant_unload_rules'] = $clearRedundantUnloadRulesArea;
		}

		if (isset($handleData['ignore_child']) && $handleData['ignore_child']) {
            $handleChangesOutput['ignore_child'] = 'If unloaded by any rule, ignore dependencies and keep its "children" loaded';
		}

		// Load exceptions? Per page, via RegEx, if user is logged-in
		if (isset($handleData['load_exception_on_this_page']['post'])) {
			$handleChangesOutput['load_exception_on_this_post'] = '<span style="color: green;">Loaded (as an exception)</span> in the following posts: ';

			$postsList = '';

			sort($handleData['load_exception_on_this_page']['post']);

			foreach ($handleData['load_exception_on_this_page']['post'] as $postId) {
				$postData   = get_post($postId);
				$postTitle  = $postData->post_title;
				$postType   = $postData->post_type;
				$postsList .= '<a title="Post Title: '.$postTitle.', Post Type: '.$postType.'" class="wpacu-tooltip" target="_blank" href="'.admin_url('post.php?post='.$postId.'&action=edit').'">'.$postId.'</a>, ';
			}

			$handleChangesOutput['load_exception_on_this_post'] .= rtrim($postsList, ', ');
			$anyLoadExceptionRule = true;
		}

		// [wpacu_pro]
        // Load exceptions? Per taxonomy page (e.g. /category/clothes/)
		if (isset($handleData['load_exception_on_this_page']['term'])) {
			$handleChangesOutput['load_exception_on_this_taxonomy'] = '<span style="color: green;">Loaded (as an exception)</span> in the following taxonomy pages: ';

			$postsList = '';

			sort($handleData['load_exception_on_this_page']['term']);

			foreach ($handleData['load_exception_on_this_page']['term'] as $termId) {
				$termData = get_term_by('term_taxonomy_id', $termId);
				$postsList .= '<a title="" target="_blank" href="'.admin_url('term.php?taxonomy='.$termData->taxonomy.'&tag_ID='.$termId).'">'.$termId.'</a> ('.$termData->name.' / taxonomy: '.$termData->taxonomy.'), ';
			}

			$handleChangesOutput['load_exception_on_this_taxonomy'] .= rtrim($postsList, ', ');
			$anyLoadExceptionRule = true;
		}

		// Load exceptions? Per user archive page (e.g. /author/john/)
		if (isset($handleData['load_exception_on_this_page']['user'])) {
			$handleChangesOutput['load_exception_on_this_user'] = '<span style="color: green;">Loaded (as an exception)</span> in the following user archive pages: ';

			$usersList = '';

			sort($handleData['load_exception_on_this_page']['user']);

			foreach ($handleData['load_exception_on_this_page']['user'] as $userId) {
				$userData = get_user_by('id', $userId);
				$usersList .= '<a title="" target="_blank" href="'.admin_url('user-edit.php?user_id='.$userData->ID).'">'.$userData->ID.'</a> ('.$userData->data->user_nicename.'), ';
			}

			$handleChangesOutput['load_exception_on_this_user'] .= rtrim($usersList, ', ');
			$anyLoadExceptionRule = true;
		}

		// Load exceptions? Search page
		if (isset($handleData['load_exception_on_this_page']['search'])) {
			$handleChangesOutput['load_exception_on_search_any_term'] = '<span style="color: green;">Loaded (as an exception)</span> in a `Search` page (any term)';
			$anyLoadExceptionRule = true;
		}

		// Load exceptions? 404 page
		if (isset($handleData['load_exception_on_this_page']['404'])) {
			$handleChangesOutput['load_exception_on_404_page'] = '<span style="color: green;">Loaded (as an exception)</span> in a `404 (Not Found)` page';
			$anyLoadExceptionRule = true;
		}

		// Load exceptions? Date archive page
		if (isset($handleData['load_exception_on_this_page']['date'])) {
			$handleChangesOutput['load_exception_on_date_archive_page'] = '<span style="color: green;">Loaded (as an exception)</span> in a `Date` archive page';
			$anyLoadExceptionRule = true;
		}

		// Load exceptions? Custom post type archive page
		$loadExceptionsPageStr = isset($handleData['load_exception_on_this_page']) && is_array($handleData['load_exception_on_this_page']) ? implode('', array_keys($handleData['load_exception_on_this_page'])) : '';
		if (strpos($loadExceptionsPageStr, 'custom_post_type_archive_') !== false) {
		    foreach (array_keys($handleData['load_exception_on_this_page']) as $loadExceptionForDataType) {
		        if (strpos($loadExceptionForDataType, 'custom_post_type_archive_') !== false) {
		            $customPostType = str_replace('custom_post_type_archive_', '', $loadExceptionForDataType);
			        $handleChangesOutput['load_exception_on_'.$loadExceptionForDataType] = '<span style="color: green;">Loaded (as an exception)</span> in an archive page (custom post type: <em>'.$customPostType.'</em>)';
		        }
		    }

			$anyLoadExceptionRule = true;
		}

		if (isset($handleData['load_regex']) && $handleData['load_regex']) {
		    if ($anyLoadExceptionRule) {
		        $textToShow = ' <strong>or</strong> if the request URI (from the URL) matches this RegEx';
            } else {
			    $textToShow = '<span style="color: green;">Loaded (as an exception)</span> if the request URI (from the URL) matches this RegEx(es)';
            }

			$handleChangesOutput['load_exception_regex'] = $textToShow.': <code>'.nl2br($handleData['load_regex']).'</code>';
			$anyLoadExceptionRule = true;
		}

		if (isset($handleData['load_it_logged_in']) && $handleData['load_it_logged_in']) {
			if ($anyLoadExceptionRule) {
				$textToShow = ' <strong>or</strong> if the user is logged-in';
			} else {
				$textToShow = '<span style="color: green;">Loaded (as an exception)</span> if the user is logged-in';
			}

			$handleChangesOutput['load_it_logged_in'] = $textToShow;
			$anyLoadExceptionRule = true;
		}

		// Since more than one load exception rule is set, merge them on the same row to save space and avoid duplicated words
		if (isset($handleChangesOutput['load_exception_on_this_post'], $handleChangesOutput['load_exception_regex'])) {
			$handleChangesOutput['load_exception_all'] = $handleChangesOutput['load_exception_on_this_post'] . $handleChangesOutput['load_exception_regex'];
			unset($handleChangesOutput['load_exception_on_this_post'], $handleChangesOutput['load_exception_regex']);
        }

		if (! $anyGroupPostUnloadRule && $anyLoadExceptionRule) {
		    $clearLoadExceptionsConfirmMsg = sprintf(esc_attr(__('This will clear all load exceptions for the `%s` CSS handle', 'wp-asset-clean-up')), $handleData['handle'])
                . esc_js("\n\n") . esc_js(__('Click \'OK\' to confirm the action', 'wp-asset-clean-up'));
		    $wpacuNonceField = wp_nonce_field('wpacu_clear_load_exceptions', 'wpacu_clear_load_exceptions_nonce');
		    $clearLoadExceptionsArea = <<<HTML
<form method="post" action="" style="display: inline-block;">
<input type="hidden" name="wpacu_action" value="clear_load_exceptions" />
<input type="hidden" name="wpacu_handle" value="{$handleData['handle']}" />
<input type="hidden" name="wpacu_asset_type" value="{$handleData['asset_type']}" />
{$wpacuNonceField}
<script type="text/javascript">
var wpacuClearLoadExceptionsConfirmMsg = '{$clearLoadExceptionsConfirmMsg}';
</script>
<button onclick="return confirm(wpacuClearLoadExceptionsConfirmMsg);" type="submit" class="button button-secondary clear-load-exceptions"><span class="dashicons dashicons-trash" style="vertical-align: text-bottom;"></span> Clear load exceptions for this handle</button>
</form>
HTML;
			$handleChangesOutput['load_exception_notice'] = '<div><em><small><strong>Note:</strong> Although a load exception rule is added, it is not relevant as there are no rules that would work together with it (e.g. unloaded site-wide, on all posts). This exception can be removed as the file is loaded anyway in all pages.</small></em>&nbsp;'.
                ' '.$clearLoadExceptionsArea.'</div><div style="clear:both;"></div>';
		}

		return $handleChangesOutput;
	}
}
