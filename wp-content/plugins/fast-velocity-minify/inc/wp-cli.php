<?php

# Exit if accessed directly				
if (!defined('ABSPATH')){ exit(); }	

###################################################
# extend wp-cli to purge cache, usage: wp fvm purge
###################################################

# only for wp-cli
if ( defined( 'WP_CLI' ) && WP_CLI ) {

	class fastvelocity_WPCLI {

		# purge files + cache
		public function purge() {
			WP_CLI::success( __( 'FVM and other caches were purged.', 'fast-velocity-minify' ) );
			fvm_purge_minification();
			fvm_purge_others();	
			
			# purge everything
			$cache = fvm_purge_minification();
			$others = fvm_purge_others();
			
			# notices
			WP_CLI::success( __( 'FVM: All Caches are now cleared.', 'fast-velocity-minify' ) .' ('.date("D, d M Y @ H:i:s e").')');
			if(is_string($cache)) { WP_CLI::warning($cache); }
			if(is_string($others)) { WP_CLI::success($others); }
					
		}
		
		# get cache size
		public function stats() {
			WP_CLI::error( __( 'This feature is currently under development.', 'fast-velocity-minify' ) );
		}	
		
	}

	# add commands
	WP_CLI::add_command( 'fvm', 'fastvelocity_WPCLI' );

}

