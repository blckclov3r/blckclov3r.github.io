<?php

if (!defined('ABSPATH')) die('No direct access allowed');

if (!function_exists('wpo_format_filesize')) :

/**
 * Helper function to format bytes to a human readable value
 *
 * @param int $bytes - the filesize in bytes
 */
function wpo_format_filesize($bytes) {

	if (1073741824 <= $bytes) {
		$bytes = number_format($bytes / 1073741824, 2) . ' GB';
	} elseif (1048576 <= $bytes) {
		$bytes = number_format($bytes / 1048576, 2) . ' MB';
	} elseif (1024 <= $bytes) {
		$bytes = number_format($bytes / 1024, 2) . ' KB';
	} elseif (1 < $bytes) {
		$bytes = $bytes . ' bytes';
	} elseif (1 == $bytes) {
		$bytes = $bytes . ' byte';
	} else {
		$bytes = '0 bytes';
	}

	return $bytes;
}

endif;
