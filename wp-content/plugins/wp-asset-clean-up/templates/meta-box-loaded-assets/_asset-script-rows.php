<?php
if (! isset($data)) {
	exit; // no direct access
}

$allAssets = $data['all']['scripts'];
$allAssetsFinal = $data['unloaded_js_handles'] = array();

foreach ($allAssets as $obj) {
	$row        = array();
	$row['obj'] = $obj;

	$active = ( isset( $data['current']['scripts'] ) && in_array( $row['obj']->handle, $data['current']['scripts'] ) );

	$row['class']   = $active ? 'wpacu_not_load' : '';
	$row['checked'] = $active ? 'checked="checked"' : '';

	/*
	 * $row['is_group_unloaded'] is only used to apply a red background in the script's area to point out that the script is unloaded
	*/
	$row['global_unloaded'] = $row['is_post_type_unloaded'] = $row['is_load_exception_per_page'] = $row['is_group_unloaded'] = false;

	// Mark it as unloaded - Everywhere
	if ( in_array( $row['obj']->handle, $data['global_unload']['scripts'] ) && ! $row['class'] ) {
		$row['global_unloaded'] = $row['is_group_unloaded'] = true;
	}

	// Mark it as unloaded - for the Current Post Type
	if ( $data['bulk_unloaded_type'] && in_array( $row['obj']->handle, $data['bulk_unloaded'][ $data['bulk_unloaded_type'] ]['scripts'] ) ) {
		$row['is_group_unloaded'] = true;

		if ( $data['bulk_unloaded_type'] === 'post_type' ) {
			$row['is_post_type_unloaded'] = true;
		}
	}

	$isLoadExceptionPerPage = isset( $data['load_exceptions']['scripts'] ) && in_array( $row['obj']->handle, $data['load_exceptions']['scripts'] );

	$row['is_load_exception_per_page']    = $isLoadExceptionPerPage;

	$isLoadException = $isLoadExceptionPerPage;

	// No load exception of any kind and a bulk unload rule is applied? Append the CSS class for unloading
	if ( ! $isLoadException && $row['is_group_unloaded']) {
		$row['class'] .= ' wpacu_not_load';
	}

	if (strpos($row['class'], 'wpacu_not_load') !== false) {
		// Actually unloaded JS, not just marked for unload
		$data['unloaded_js_handles'][] = $row['obj']->handle;
		}

	foreach ( array( 'data', 'before', 'after' ) as $extraKey ) {
		// "data": CDATA added via wp_localize_script()
		// "before" / "after" the tag inline content added via wp_add_inline_script()
		$row[ 'extra_' . $extraKey . '_js' ] = ( is_object( $row['obj']->extra ) && isset( $row['obj']->extra->{$extraKey} ) ) ? $row['obj']->extra->{$extraKey} : false;

		if ( ! $row[ 'extra_' . $extraKey . '_js' ] ) {
			$row[ 'extra_' . $extraKey . '_js' ] = ( is_array( $row['obj']->extra ) && isset( $row['obj']->extra[ $extraKey ] ) ) ? $row['obj']->extra[ $extraKey ] : false;
		}
	}

	$row['class'] .= ' script_' . $row['obj']->handle;

	$allAssetsFinal[$obj->handle] = $row;
}

foreach ($allAssetsFinal as $assetHandle => $row) {
	$data['row'] = $row;

	// Load Template
	$templateRowOutput = \WpAssetCleanUp\Main::instance()->parseTemplate(
		'/meta-box-loaded-assets/_asset-script-single-row',
		$data
	);

	if (isset($data['rows_build_array']) && $data['rows_build_array']) {
		$uniqueHandle = $row['obj']->handle;

		if (array_key_exists($uniqueHandle, $data['rows_assets'])) {
			$uniqueHandle .= 1; // make sure each key is unique
		}

		if (isset($data['rows_by_location']) && $data['rows_by_location']) {
			$data['rows_assets']
			  [$row['obj']->locationMain]
				[$row['obj']->locationChild]
				  [$uniqueHandle]
					['script'] = $templateRowOutput;
		} elseif (isset($data['rows_by_position']) && $data['rows_by_position']) {
			$handlePosition = $row['obj']->position;

			$data['rows_assets']
				[$handlePosition] // 'head', 'body'
					[$uniqueHandle]
						['script'] = $templateRowOutput;
		} elseif (isset($data['rows_by_preload']) && $data['rows_by_preload']) {
			$preloadStatus = $row['obj']->preload_status;

			$data['rows_assets']
				[$preloadStatus] // 'preloaded', 'not_preloaded'
					[$uniqueHandle]
						['script'] = $templateRowOutput;
		} elseif (isset($data['rows_by_parents']) && $data['rows_by_parents'])  {
			$childHandles = isset($data['all_deps']['parent_to_child']['scripts'][$row['obj']->handle]) ? $data['all_deps']['parent_to_child']['scripts'][$row['obj']->handle] : array();

			if (! empty($childHandles)) {
				$handleStatus = 'parent';
			} elseif (isset($row['obj']->deps) && ! empty($row['obj']->deps)) {
				$handleStatus = 'child';
			} else {
				$handleStatus = 'independent';
			}

			$data['rows_assets']
				[$handleStatus] // 'parent', 'child', 'independent'
					[$uniqueHandle]
						['scripts'] = $templateRowOutput;
		} elseif (isset($data['rows_by_loaded_unloaded']) && $data['rows_by_loaded_unloaded']) {
			$handleStatus = (strpos($row['class'], 'wpacu_not_load') !== false) ? 'unloaded' : 'loaded';

			$data['rows_assets']
				[$handleStatus] // 'loaded', 'unloaded'
					[$uniqueHandle]
						['script'] = $templateRowOutput;
		} elseif(isset($data['rows_by_size']) && $data['rows_by_size']) {
			$sizeStatus = (isset($row['obj']->sizeRaw) && is_int($row['obj']->sizeRaw)) ? 'with_size' : 'external_na';

			$data['rows_assets']
				[$sizeStatus] // 'with_size', 'external_na'
					[$uniqueHandle]
						['script'] = $templateRowOutput;

			if ($sizeStatus === 'with_size') {
				// Associated the handle with the raw size of the file
				$data['handles_sizes'][$uniqueHandle] = $row['obj']->sizeRaw;
			}
		} elseif (isset($data['rows_by_rules']) && $data['rows_by_rules']) {
			$ruleStatus = \WpAssetCleanUp\Misc::handleHasAtLeastOneRule($data, 'scripts') ? 'with_rules' : 'with_no_rules';
			$data['rows_assets']
				[$ruleStatus] // 'with_rules', 'with_no_rules'
					[$uniqueHandle]
						['script'] = $templateRowOutput;
		} else {
			$data['rows_assets'][$uniqueHandle] = $templateRowOutput;
		}
	} else {
		echo $templateRowOutput;
	}
}
