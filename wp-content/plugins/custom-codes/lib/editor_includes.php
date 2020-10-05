<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


// CUSTOM CODES ADMIN STYLES AND SCRIPTS
function cstm_cds_load() {
	global $cstm_cds_result_level;


		$codemirror_dir = plugin_dir_url( CSTM_CDS_FILE ) .'node_modules/codemirror';


		// CODE MIRROR
		wp_enqueue_style ( 'cc-codemirror-css', $codemirror_dir.'/lib/codemirror.css' );
		wp_enqueue_script( 'cc-codemirror-js', $codemirror_dir.'/lib/codemirror.js', array(), '20150913', true );


		// EMMET
		wp_enqueue_script( 'cc-emmet-js', plugin_dir_url( __FILE__ ) .'vendor/emmet/dist/emmet.js', array(), '20150913', true );


		// MODES
		wp_enqueue_script( 'cc-codemirror-htmlmixed-js', $codemirror_dir.'/mode/htmlmixed/htmlmixed.js', array(), '20150913', true );
		wp_enqueue_script( 'cc-codemirror-xml-js', $codemirror_dir.'/mode/xml/xml.js', array(), '20150913', true );
		wp_enqueue_script( 'cc-codemirror-css-js', $codemirror_dir.'/mode/css/css.js', array(), '20150913', true );
		wp_enqueue_script( 'cc-codemirror-sass-js', $codemirror_dir.'/mode/sass/sass.js', array(), '20150913', true );
		wp_enqueue_script( 'cc-codemirror-js-js', $codemirror_dir.'/mode/javascript/javascript.js', array(), '20150913', true );
		wp_enqueue_script( 'cc-codemirror-php-js', $codemirror_dir.'/mode/php/php.js', array(), '20150913', true );
		wp_enqueue_script( 'cc-codemirror-clike-js', $codemirror_dir.'/mode/clike/clike.js', array(), '20150913', true );


		// THEMES
		wp_enqueue_style ( 'cc-monokai-css', $codemirror_dir.'/theme/monokai.css' );


		// ADDONS
		wp_enqueue_script( 'cc-active-line-js', $codemirror_dir.'/addon/selection/active-line.js', array(), '20150913', true );

		wp_enqueue_script( 'cc-closebrackets-js', $codemirror_dir.'/addon/edit/closebrackets.js', array(), '20150913', true );
		wp_enqueue_script( 'cc-matchbrackets-js', $codemirror_dir.'/addon/edit/matchbrackets.js', array(), '20150913', true );
		wp_enqueue_script( 'cc-trailingspace-js', $codemirror_dir.'/addon/edit/trailingspace.js', array(), '20150913', true );

		wp_enqueue_script( 'cc-foldcode-js', $codemirror_dir.'/addon/fold/foldcode.js', array(), '20150913', true );
		wp_enqueue_style ( 'cc-foldgutter-css', $codemirror_dir.'/addon/fold/foldgutter.css' );
		wp_enqueue_script( 'cc-foldgutter-js', $codemirror_dir.'/addon/fold/foldgutter.js', array(), '20150913', true );
		wp_enqueue_script( 'cc-brace-fold-js', $codemirror_dir.'/addon/fold/brace-fold.js', array(), '20150913', true );

		wp_enqueue_script( 'cc-match-highlighter-js', $codemirror_dir.'/addon/search/match-highlighter.js', array(), '20150913', true );
		wp_enqueue_script( 'cc-search-js', $codemirror_dir.'/addon/search/search.js', array(), '20150913', true );
		wp_enqueue_script( 'cc-searchcursor-js', $codemirror_dir.'/addon/search/searchcursor.js', array(), '20150913', true );

		wp_enqueue_script( 'cc-dialog-js', $codemirror_dir.'/addon/dialog/dialog.js', array(), '20150913', true );
		wp_enqueue_style ( 'cc-dialog-css', $codemirror_dir.'/addon/dialog/dialog.css' );

		wp_enqueue_script( 'cc-fullscreen-js', $codemirror_dir.'/addon/display/fullscreen.js', array(), '20150913', true );
		wp_enqueue_style ( 'cc-fullscreen-css', $codemirror_dir.'/addon/display/fullscreen.css' );

		wp_enqueue_script( 'cc-comment-js', $codemirror_dir.'/addon/comment/comment.js', array(), '20150913', true );


		wp_enqueue_style ( 'cc-font-awesome-css', plugin_dir_url( __FILE__ ) .'vendor/font-awesome/css/font-awesome.min.css' );


		wp_enqueue_style ( 'cc-custom-codes-css', plugin_dir_url( __FILE__ ) .'css/custom_codes.css' );
		wp_enqueue_script( 'cc-custom-codes-js', plugin_dir_url( __FILE__ ) .'js/custom_codes.js', array('jquery'), '20150913', true );
		wp_localize_script( 'cc-custom-codes-js', 'cstm_cds_vars', array(
				'cstm_cds_nonce' => wp_create_nonce('cc-nonce'),
				'cstm_cds_plugin_dir_url' => plugin_dir_url( __FILE__ ),
				'cstm_cds_admin' => $cstm_cds_result_level
			)
		);

		wp_enqueue_script( 'cc-saver-js', plugin_dir_url( __FILE__ ) .'vendor/mousetrap/mousetrap.min.js', array(), '20150913', false );

}
add_action( 'admin_enqueue_scripts', 'cstm_cds_load' );

?>