<?php
/*
Plugin Name: Custom Codes
Plugin URI: https://wordpress.org/plugins/custom-codes/
Description: Your custom SASS, CSS, JS and PHP customizations in same directory with the best advanced code editor CodeMirror.
Author: Bilal TAS
Author URI: https://www.bilaltas.net
License: MIT
License URI: https://opensource.org/licenses/MIT
Version: 1.0.2

Copyright (c) 2019 Custom Codes

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
define( 'CSTM_CDS_FILE', __FILE__ );
define( 'CSTM_CDS_DIR', WP_CONTENT_DIR ."/custom_codes/" );
define( 'CSTM_CDS_DEBUG', false );


if ( is_admin() ) { // Back-End

	require_once( dirname( CSTM_CDS_FILE ).'/settings/plugin_activation.php' );
	require_once( dirname( CSTM_CDS_FILE ).'/settings/helper_functions.php' );
	require_once( dirname( CSTM_CDS_FILE ).'/settings/permission_update.php' );
	require_once( dirname( CSTM_CDS_FILE ).'/settings/settings.php' );

	if ( defined( 'DOING_AJAX' ) && DOING_AJAX && isset($_POST['cstm_cds_editor_contents']) )
		require_once( dirname( CSTM_CDS_FILE ).'/lib/editor_ajax_saver.php' );

	if ( isset($_GET['page']) && $_GET['page'] == "custom-codes" )
		require_once( dirname( CSTM_CDS_FILE ).'/lib/editor_page.php' );

}
require_once( dirname( CSTM_CDS_FILE ).'/settings/admin_menu.php' );
require_once( dirname( CSTM_CDS_FILE ).'/lib/release_codes.php' );