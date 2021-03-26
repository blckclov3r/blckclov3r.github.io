<?php

# Exit if accessed directly				
if (!defined('ABSPATH')){ exit(); }	

### Get General Information
function fvm_get_generalinfo() {
	
	# check if user has admin rights
	if(!current_user_can('manage_options')) {
		echo __( 'You are not allowed to execute this function!', 'fast-velocity-minify' );
	}

	echo'+++'. PHP_EOL;
	echo'SERVER INFO:'. PHP_EOL;
	echo'OS:           '. PHP_OS . PHP_EOL;
	echo'Server:       '. $_SERVER["SERVER_SOFTWARE"] . PHP_EOL;
	echo'PHP:          '. PHP_VERSION . PHP_EOL;
	echo'MySQL:        '. fvm_get_mysql_version() . PHP_EOL;
	echo'CPU Cores:    '. fvm_get_servercpu() . PHP_EOL;
	echo'Server Load:  '. fvm_get_serverload() . PHP_EOL;
	
	echo'---'. PHP_EOL;
	echo'SITE INFO:'. PHP_EOL;
	echo'Site Path:    '. ABSPATH . PHP_EOL;
	echo'Hostname:     '. $_SERVER['SERVER_NAME'] . PHP_EOL;
	echo'DB Data Size:          '. fvm_format_php_size(fvm_get_mysql_data_usage()) . PHP_EOL;
	echo'DB Index Size:         '. fvm_format_php_size(fvm_get_mysql_index_usage()) . PHP_EOL;

	echo'---'. PHP_EOL;
	echo'LIMITS:'. PHP_EOL;
	echo'PHP Max Exec Time:     '. fvm_get_php_max_execution(). PHP_EOL;
	echo'PHP Memory Limit:      '. fvm_format_php_size(fvm_get_php_memory_limit()) . PHP_EOL;
	echo'PHP Max Upload Size:   '. fvm_format_php_size(fvm_get_php_upload_max()) . PHP_EOL;
	echo'PHP Max Post Size:     '. fvm_format_php_size(fvm_get_php_post_max()) . PHP_EOL;
	echo'MySQL Max Packet Size: '. fvm_format_php_size(fvm_get_mysql_max_allowed_packet()) . PHP_EOL;
	echo'MySQL Max Connections: '. fvm_get_mysql_max_allowed_connections() . PHP_EOL;
	echo'+++';
}


### Convert PHP Size Format to an int, then readable format
function fvm_format_php_size($size) {
    if (!is_numeric($size)) {
        if (strpos($size, 'M') !== false) {
            $size = intval($size)*1024*1024;
        } elseif (strpos($size, 'K') !== false) {
            $size = intval($size)*1024;
        } elseif (strpos($size, 'G') !== false) {
            $size = intval($size)*1024*1024*1024;
        }
    }
		
    $size = is_numeric($size) ? fvm_format_filesize($size, 0) : $size;
	return str_pad($size, 8, " ", STR_PAD_LEFT);
}

### Function: Get PHP Max Upload Size
if(!function_exists('fvm_get_php_upload_max')) {
    function fvm_get_php_upload_max() {
		
		# check if user has admin rights
		if(!current_user_can('manage_options')) {
			return __( 'You are not allowed to execute this function!', 'fast-velocity-minify' );
		}
		
        if(ini_get('upload_max_filesize')) {
            $upload_max = ini_get('upload_max_filesize');
        } else {
            $upload_max = strval('N/A');
        }
        return $upload_max;
    }
}


### Function: Get PHP Max Post Size
if(!function_exists('fvm_get_php_post_max')) {
    function fvm_get_php_post_max() {
		
		# check if user has admin rights
		if(!current_user_can('manage_options')) {
			return __( 'You are not allowed to execute this function!', 'fast-velocity-minify' );
		}
		
        if(ini_get('post_max_size')) {
            $post_max = ini_get('post_max_size');
        } else {
            $post_max = strval('N/A');
        }
        return $post_max;
    }
}


### Function: PHP Maximum Execution Time
if(!function_exists('fvm_get_php_max_execution')) {
    function fvm_get_php_max_execution() {
		
		# check if user has admin rights
		if(!current_user_can('manage_options')) {
			return __( 'You are not allowed to execute this function!', 'fast-velocity-minify' );
		}
		
        if(ini_get('max_execution_time')) {
            $max_execute = intval(ini_get('max_execution_time'));
        } else {
            $max_execute = strval('N/A');
        }
		
        return str_pad($max_execute, 5, " ", STR_PAD_LEFT);
    }
}


### Function: PHP Memory Limit
if(!function_exists('fvm_get_php_memory_limit')) {
    function fvm_get_php_memory_limit() {
		
		# check if user has admin rights
		if(!current_user_can('manage_options')) {
			return __( 'You are not allowed to execute this function!', 'fast-velocity-minify' );
		}
		
        if(ini_get('memory_limit')) {
            $memory_limit = ini_get('memory_limit');
        } else {
            $memory_limit = strval('N/A');
        }
        return $memory_limit;
    }
}


### Function: Get MYSQL Version
if(!function_exists('fvm_get_mysql_version')) {
    function fvm_get_mysql_version() {
		
		# check if user has admin rights
		if(!current_user_can('manage_options')) {
			return __( 'You are not allowed to execute this function!', 'fast-velocity-minify' );
		}
		
        global $wpdb;
        return $wpdb->get_var("SELECT VERSION() AS version");
    }
}


### Function: Get MYSQL Data Usage
if(!function_exists('fvm_get_mysql_data_usage')) {
    function fvm_get_mysql_data_usage() {
		
		# check if user has admin rights
		if(!current_user_can('manage_options')) {
			return __( 'You are not allowed to execute this function!', 'fast-velocity-minify' );
		}
		
        global $wpdb;
        $data_usage = 0;
        $tablesstatus = $wpdb->get_results("SHOW TABLE STATUS");
        foreach($tablesstatus as  $tablestatus) {
			if(is_numeric($tablestatus->Data_length)) { $data_usage += $tablestatus->Data_length; } else { $data_usage += 0; }
        }
        if (!$data_usage) {
            $data_usage = strval('N/A');
        }
        return $data_usage;
    }
}


### Function: Get MYSQL Index Usage
if(!function_exists('fvm_get_mysql_index_usage')) {
    function fvm_get_mysql_index_usage() {
		
		# check if user has admin rights
		if(!current_user_can('manage_options')) {
			return __( 'You are not allowed to execute this function!', 'fast-velocity-minify' );
		}
		
        global $wpdb;
        $index_usage = 0;
        $tablesstatus = $wpdb->get_results("SHOW TABLE STATUS");
        foreach($tablesstatus as  $tablestatus) {
            if(is_numeric($tablestatus->Index_length)) { $index_usage +=  $tablestatus->Index_length; } else { $index_usage += 0; }
        }
        if (!$index_usage){
            $index_usage = strval('N/A');
        }
        return $index_usage;
    }
}


### Function: Get MYSQL Max Allowed Packet
if(!function_exists('fvm_get_mysql_max_allowed_packet')) {
    function fvm_get_mysql_max_allowed_packet() {
		
		# check if user has admin rights
		if(!current_user_can('manage_options')) {
			return __( 'You are not allowed to execute this function!', 'fast-velocity-minify' );
		}
		
        global $wpdb;
        $packet_max_query = $wpdb->get_row("SHOW VARIABLES LIKE 'max_allowed_packet'");
        $packet_max = $packet_max_query->Value;
        if(!$packet_max) {
            $packet_max = strval('N/A');
        }
        return $packet_max;
    }
}


### Function:Get MYSQL Max Allowed Connections
if(!function_exists('fvm_get_mysql_max_allowed_connections')) {
    function fvm_get_mysql_max_allowed_connections() {
		
		# check if user has admin rights
		if(!current_user_can('manage_options')) {
			return __( 'You are not allowed to execute this function!', 'fast-velocity-minify' );
		}
		
        global $wpdb;
        $connection_max_query = $wpdb->get_row("SHOW VARIABLES LIKE 'max_connections'");
        $connection_max = $connection_max_query->Value;
        if(!$connection_max) {
            $connection_max = strval('N/A');
        }
		
		return str_pad($connection_max, 5, " ", STR_PAD_LEFT);
    }
}


### Function: Get The Server Load
if(!function_exists('fvm_get_serverload')) {
    function fvm_get_serverload() {
		
		# check if user has admin rights
		if(!current_user_can('manage_options')) {
			return __( 'You are not allowed to execute this function!', 'fast-velocity-minify' );
		}
		
        $server_load = 0;
		$numCpus = 'N/A';
        if(PHP_OS != 'WINNT' && PHP_OS != 'WIN32') {
			clearstatcache();
            if(@file_exists('/proc/loadavg') ) {
                if ($fh = @fopen( '/proc/loadavg', 'r' )) {
                    $data = @fread( $fh, 6 );
                    @fclose( $fh );
                    $load_avg = explode( " ", $data );
                    $server_load = trim($load_avg[0]);
                }
			} else if ('WIN' == strtoupper(substr(PHP_OS, 0, 3)) && function_exists('popen') && fvm_function_available('popen')) {
				$process = @popen('wmic cpu get NumberOfCores', 'rb');
				if (false !== $process && null !== $process) {
					fgets($process);
					$numCpus = intval(fgets($process));
					pclose($process);
				}
			} else if (function_exists('system') && fvm_function_available('system')){
                $data = @system('uptime');
                preg_match('/(.*):{1}(.*)/', $data, $matches);
				if(isset($matches[2])) {
					$load_arr = explode(',', $matches[2]);
					$server_load = trim($load_arr[0]);
				} else {
					$server_load = strval('N/A');
				}
            } else {
				$server_load = strval('N/A');
			}
        }
        if(empty($server_load)) {
            $server_load = strval('N/A');
        }
        return $server_load;
    }
}


### Function: Get The Server CPU's
if(!function_exists('fvm_get_servercpu')) {
    function fvm_get_servercpu() {
		
		# check if user has admin rights
		if(!current_user_can('manage_options')) {
			return __( 'You are not allowed to execute this function!', 'fast-velocity-minify' );
		}
		
		$numCpus = 0;
        if(PHP_OS != 'WINNT' && PHP_OS != 'WIN32') {
			clearstatcache();
			if (@is_file('/proc/cpuinfo')) {
				$cpuinfo = file_get_contents('/proc/cpuinfo');
				preg_match_all('/^processor/m', $cpuinfo, $matches);
				$numCpus = count($matches[0]);
			} else if (function_exists('popen') && fvm_function_available('popen')) {
				$process = @popen('sysctl -a', 'rb');
				if (false !== $process && null !== $process) {
					$output = stream_get_contents($process);
					preg_match('/hw.ncpu: (\d+)/', $output, $matches);
					if ($matches) { $numCpus = intval($matches[1][0]); }
					pclose($process);
				}
			} else {
					$numCpus = strval('N/A');
			}
		}
        if(empty($numCpus)) {
            $numCpus = strval('N/A');
        }
        return $numCpus;
    }
}