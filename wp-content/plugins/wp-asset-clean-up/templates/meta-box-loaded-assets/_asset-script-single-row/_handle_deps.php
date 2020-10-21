<?php
/*
 * The file is included from /templates/meta-box-loaded-assets/_asset-script-single-row.php
*/
if (! isset($data)) {
	exit; // no direct access
}

// [Start] Any dependencies
if (isset($data['row']['obj']->deps) && ! empty($data['row']['obj']->deps)) {
	$depsOutput = '';

	if (is_array($data['row']['obj']->deps)) {
		$dependsOnText = (count($data['row']['obj']->deps) === 1)
			? __('"Child" of one "parent" JS file:', 'wp-asset-clean-up')
			: sprintf(__('"Child" of %s JS "parent" files:', 'wp-asset-clean-up'),
				count($data['row']['obj']->deps));
	} else {
		$dependsOnText = __('"Child" of "parent" JS file(s):', 'wp-asset-clean-up');
	}

	$depsOutput .= $dependsOnText.' ';

	foreach ($data['row']['obj']->deps as $depHandle) {
		$depHandleText = $depHandle;
		$color = in_array($depHandle, $data['unloaded_js_handles']) ? '#cc0000' : 'green';

		if ($depHandle === 'jquery' || strpos($depHandle, 'jquery-ui-') === 0) {
			$depHandleText .= '&nbsp;'.$jqueryIconHtmlDepends;
		}

		$depsOutput .= '<a style="color:'.$color.';font-weight:300;" href="#wpacu_script_row_'.$depHandle.'"><span>'.$depHandleText.'</span></a>, ';
	}

	$depsOutput = rtrim($depsOutput, ', ');

	$extraInfo[] = $depsOutput;
}
// [End] Any dependencies
