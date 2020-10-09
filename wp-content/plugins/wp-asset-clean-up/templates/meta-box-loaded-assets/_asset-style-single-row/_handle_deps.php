<?php
/*
 * The file is included from /templates/meta-box-loaded-assets/_asset-style-single-row.php
*/
if (! isset($data)) {
	exit; // no direct access
}

// [Start] Any dependencies
if (isset($data['row']['obj']->deps) && ! empty($data['row']['obj']->deps)) {
	$depsOutput = '';

	if (is_array($data['row']['obj']->deps)) {
		$dependsOnText = (count($data['row']['obj']->deps) === 1)
			? __('"Child" of one "parent" CSS file:')
			: sprintf(__('"Child" of %s CSS "parent" files:', 'wp-asset-clean-up'),
				count($data['row']['obj']->deps));
	} else {
		$dependsOnText = __('"Child" of "parent" CSS file(s):', 'wp-asset-clean-up');
	}

	$depsOutput .= $dependsOnText.' ';

	foreach ($data['row']['obj']->deps as $depHandle) {
		$depHandleText = $depHandle;
		$color = in_array($depHandle, $data['unloaded_css_handles']) ? '#cc0000' : 'green';
		$depsOutput .= '<a style="color:'.$color.';font-weight:300;" href="#wpacu_style_row_'.$depHandle.'"><span>'.$depHandleText.'</span></a>, ';
	}

	$depsOutput = rtrim($depsOutput, ', ');

	$extraInfo[] = $depsOutput;
}
// [End] Any dependencies
