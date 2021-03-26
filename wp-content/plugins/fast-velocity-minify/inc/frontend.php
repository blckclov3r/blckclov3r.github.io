<?php

# Exit if accessed directly				
if (!defined('ABSPATH')){ exit(); }	

# functions needed only for frontend ###########

# must have for large strings processing during minification
@ini_set('pcre.backtrack_limit', 5000000); 
@ini_set('pcre.recursion_limit', 5000000); 

# our own minification libraries
include_once($fvm_var_inc_lib . DIRECTORY_SEPARATOR . 'raisermin' . DIRECTORY_SEPARATOR . 'minify.php');

# php simple html
# https://sourceforge.net/projects/simplehtmldom/
include_once($fvm_var_inc_lib . DIRECTORY_SEPARATOR . 'simplehtmldom' . DIRECTORY_SEPARATOR . 'simple_html_dom.php');

# PHP Minify [1.3.60] for CSS minification only
# https://github.com/matthiasmullie/minify
$fvm_var_inc_lib_mm = $fvm_var_inc_lib . DIRECTORY_SEPARATOR . 'matthiasmullie' . DIRECTORY_SEPARATOR;
include_once($fvm_var_inc_lib_mm . 'minify' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Minify.php');
include_once($fvm_var_inc_lib_mm . 'minify' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'CSS.php');
include_once $fvm_var_inc_lib_mm . 'minify'. DIRECTORY_SEPARATOR .'src'. DIRECTORY_SEPARATOR .'JS.php';
include_once $fvm_var_inc_lib_mm . 'minify'. DIRECTORY_SEPARATOR .'src'. DIRECTORY_SEPARATOR .'Exception.php';
include_once $fvm_var_inc_lib_mm . 'minify'. DIRECTORY_SEPARATOR .'src'. DIRECTORY_SEPARATOR .'Exceptions'. DIRECTORY_SEPARATOR .'BasicException.php';
include_once $fvm_var_inc_lib_mm . 'minify'. DIRECTORY_SEPARATOR .'src'. DIRECTORY_SEPARATOR .'Exceptions'. DIRECTORY_SEPARATOR .'FileImportException.php';
include_once $fvm_var_inc_lib_mm . 'minify'. DIRECTORY_SEPARATOR .'src'. DIRECTORY_SEPARATOR .'Exceptions'. DIRECTORY_SEPARATOR .'IOException.php';
include_once($fvm_var_inc_lib_mm . 'path-converter' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'ConverterInterface.php');
include_once($fvm_var_inc_lib_mm . 'path-converter' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Converter.php');

################################################


# start buffering before template
function fvm_start_buffer() {
	if(fvm_can_minify()) {
		ob_start('fvm_process_page', 0, PHP_OUTPUT_HANDLER_REMOVABLE);
	}
}

# process html from fvm_end_buffer
function fvm_process_page($html) {
	
	# get globals
	global $fvm_settings, $fvm_cache_paths, $fvm_urls;
	
	# can process minification?
	if(fvm_can_minify()) {
		
		# return early if not html
		if(fvm_is_html($html) !== true) {
			return $html;
		}
						
		# defaults
		$tvers = get_option('fvm_last_cache_update', '0');
		$now = time();
		$htmlcssheader = array();
		$lp_css_last_ff_inline = '';
			
		# get html into an object
		# https://simplehtmldom.sourceforge.io/manual.htm
		$html_object = str_get_html($html, true, true, 'UTF-8', false, PHP_EOL, ' ');

		# return early if html is not an object, or overwrite html into an object for processing
		if (!is_object($html_object)) {
			return $html . '<!-- simplehtmldom failed to process the html -->';
		} else {
			$html = $html_object;
		}
		
		
		# collect all link preload headers
		$allpreloads = array();
		foreach($html->find('link[rel=preload]') as $tag) {
			
			# auto importance by default
			$importance = 'auto';
			if(isset($tag->importance)) { 
				$importance = $tag->importance; 
			} else {
				$tag->importance = 'auto';
			}
			
			# highest to high (but earlier in page)
			if(isset($tag->importance) && $tag->importance == 'highest') { 
				$tag->importance = 'high';
				$importance	= 'highest';
			}
			
			# collect, group by importance and remove
			$allpreloads[$importance][] = $tag->outertext;
			$tag->outertext = '';
		}
		
		
		# process css, if not disabled
		if(isset($fvm_settings['css']['enable']) && $fvm_settings['css']['enable'] == true) {
						
			# defaults
			$fvm_styles = array();
			$fvm_styles_log = array();
			$critical_path = array();
			$enable_css_minification = true;
			
			# exclude styles and link tags inside scripts, no scripts or html comments
			$excl = array();
			foreach($html->find('script link[rel=stylesheet], script style, noscript style, noscript link[rel=stylesheet], comment') as $element) {
				$excl[] = $element->outertext;
			}

			# collect all styles, but filter out if excluded
			$allcss = array();
			foreach($html->find('link[rel=stylesheet], style') as $element) {
				if(!in_array($element->outertext, $excl)) {
					$allcss[] = $element;
				}
			}
						
			# merge and process
			foreach($allcss as $k=>$tag) {
				
				# ignore list, leave these alone
				if(isset($tag->href) && isset($fvm_settings['css']['ignore']) && !empty($fvm_settings['css']['ignore'])) {
					$arr = fvm_string_toarray($fvm_settings['css']['ignore']);
					if(is_array($arr) && count($arr) > 0) {
						foreach ($arr as $e) { 
							if(stripos($tag->href, $e) !== false) {
								continue 2;
							} 
						}
					}
				}
				
				# remove css files
				if(isset($tag->href) && isset($fvm_settings['css']['remove']) && !empty($fvm_settings['css']['remove'])) {
					$arr = fvm_string_toarray($fvm_settings['css']['remove']);
					if(is_array($arr) && count($arr) > 0) {
						foreach ($arr as $e) { 
							if(stripos($tag->href, $e) !== false) {
								$tag->outertext = '';
								unset($allcss[$k]);
								continue 2;
							} 
						}
					}
				}
				
				# change the mediatype for files that are to be merged into the fonts css 
				if(isset($tag->href) && isset($fvm_settings['css']['fonts']) && $fvm_settings['css']['fonts'] == true) {
					$arr = array('/fonts.googleapis.com', '/animate.css', '/animate.min.css', '/icomoon.css', '/animations/', '/eicons/css/', 'font-awesome', 'fontawesome', '/flag-icon.min.css', '/fonts.css', '/pe-icon-7-stroke.css', '/fontello.css', '/dashicons.min.css');
					if(is_array($arr) && count($arr) > 0) {
						foreach ($arr as $e) { 
							if(stripos($tag->href, $e) !== false) {
								$tag->media = 'fonts';
								break;
							}
						} 
					}
				}
				
					
				# normalize mediatypes
				$media = 'all';
				if(isset($tag->media)) {
					$media = $tag->media;
					if ($media == 'screen' || $media == 'screen, print' || empty($media) || is_null($media) || $media == false) { 
						$media = 'all'; 
					}
				}
							
				# remove print mediatypes
				if(isset($fvm_settings['css']['noprint']) && $fvm_settings['css']['noprint'] == true && $media == 'print') {
					$tag->outertext = '';
					unset($allcss[$k]);
					continue;
				}	

				# process css files
				if($tag->tag == 'link' && isset($tag->href)) {
					
					# default
					$css = '';
					
					# make sure we have a complete url
					$href = fvm_normalize_url($tag->href, $fvm_urls['wp_domain'], $fvm_urls['wp_site_url']);
					
					# get minification settings for files
					if(isset($fvm_settings['css']['min_disable']) && $fvm_settings['css']['min_disable'] == '1') {
						$enable_css_minification = false;
					}					
					
					# force minification on google fonts
					if(stripos($href, 'fonts.googleapis.com') !== false) {
						$enable_css_minification = true;
					}
					
					# download, minify, cache (no ver query string)
					$tkey = hash('sha1', $href);
					$css = fvm_get_transient($tkey);
					if ($css === false) {
						
						# open or download file, get contents
						$ddl = array();
						$ddl = fvm_maybe_download($href);
										
						# if success
						if(isset($ddl['content'])) {
												
							# contents
							$css = $ddl['content'];
						
							# open or download file, get contents
							$css = fvm_maybe_minify_css_file($css, $href, $enable_css_minification);
							
							# developers filter
							$css = apply_filters( 'fvm_after_download_and_minify_code', $css, 'css');
											
							# quick integrity check
							if(!empty($css) && $css !== false) {

								# trim code
								$css = trim($css);
								
								# execution time in ms, size in bytes
								$fs = strlen($css);
								$ur = str_replace($fvm_urls['wp_site_url'], '', $href);
								$tkey_meta = array('fs'=>$fs, 'url'=>str_replace($fvm_cache_paths['cache_url_min'].'/', '', $ur), 'mt'=>$media);
													
								# save
								fvm_set_transient(array('uid'=>$tkey, 'date'=>$tvers, 'type'=>'css', 'content'=>$css, 'meta'=>$tkey_meta));

							}
						}
					}
					
					
					# success
					if($css !== false) {
						
						# inline all css
						if(isset($fvm_settings['css']['inline-all']) && $fvm_settings['css']['inline-all'] == true) {
							$mt = ''; if(isset($tag->media)) { $mt = ' media="'.$tag->media.'"'; }
							$tag->outertext = '<style type="text/css"'.$mt.'>'.$css.'</style>';
							unset($allcss[$k]);
							continue;
						} else {
							# collect for merging
							$fvm_styles[$media][] = $css;
							$fvm_styles_log[$media][] = $tkey;
							$tag->outertext = '';
							unset($allcss[$k]);
							continue;
						}
					
					} else {
										
						# there is an error, so leave them alone
						$err = ''; if(isset($ddl['error'])) { $err = '<!-- '.$ddl['error'].' -->'; }
						$tag->outertext = PHP_EOL . $tag->outertext.$err . PHP_EOL;
						unset($allcss[$k]);
						continue;
											
					}
				
				}
		
		
				# process styles
				if($tag->tag == 'style' && !isset($tag->href)) {
				
					# default
					$css = '';
					
					# get minification settings for files
					if(isset($fvm_settings['css']['min_disable']) && $fvm_settings['css']['min_disable'] == true) {
						$enable_css_minification = true;
					}
					
					# minify inline CSS
					$css = $tag->innertext;
					if($enable_css_minification) {
						$css = fvm_minify_css_string($css); 
					}
					
					# handle import rules
					$css = fvm_replace_css_imports($css);
					
					# remove fonts and icons and collect for later
					if(isset($fvm_settings['css']['fonts']) && $fvm_settings['css']['fonts'] == true) {
						$mff = array();
						preg_match_all('/(\@font-face)([^}]+)(\})/', $css, $mff);
						if(isset($mff[0]) && is_array($mff[0])) {
							foreach($mff[0] as $ff) {
								$css = str_replace($ff, '', $css);
								$lp_css_last_ff_inline.= $ff . PHP_EOL;
							}
						}
					}
					
					# add cdn support
					if(isset($fvm_settings['cdn']['enable']) && $fvm_settings['cdn']['enable'] == true && 
					isset($fvm_settings['cdn']['domain']) && !empty($fvm_settings['cdn']['domain'])) {
						if(isset($fvm_settings['cdn']['cssok']) && $fvm_settings['cdn']['cssok'] == true) {
								
							# scheme + site url
							$fcdn = str_replace($fvm_urls['wp_domain'], $fvm_settings['cdn']['domain'], $fvm_urls['wp_site_url']);
								
							# replacements
							$css = str_ireplace('url(/wp-content/', 'url('.$fcdn.'/wp-content/', $css);
							$css = str_ireplace('url("/wp-content/', 'url("'.$fcdn.'/wp-content/', $css);
							$css = str_ireplace('url(\'/wp-content/', 'url(\''.$fcdn.'/wp-content/', $css);

						}
					}
					
					# critical path needs to come before the CSS file
					if(isset($fvm_settings['css']['async']) && $fvm_settings['css']['async'] == true) {
						if(isset($tag->id) && $tag->id == 'critical-path') {
							$critical_path[] = $tag->outertext;
							$tag->outertext = '';
						}
					}
										
					# trim code
					$css = trim($css);
					
					# decide what to do with the inlined css
					if(empty($css)) {
						# delete empty style tags
						$tag->outertext = '';
						unset($allcss[$k]);
						continue;
					} else {
						# process inlined styles
						$tag->innertext = $css;
						unset($allcss[$k]);
						continue;
					}

				}
				
			}
			
			# generate merged css files, foreach mediatype
			if(is_array($fvm_styles) && count($fvm_styles) > 0) {
				
				# collect fonts for last
				$lp_css_last = '';
				$lp_css_last_ff = '';
				
				# merge files
				foreach ($fvm_styles as $mediatype=>$css_process) {
					
					# skip fonts file
					if($mediatype == 'fonts') {
						$lp_css_last = $fvm_styles['fonts'];
						continue;
					}		
				
					# merge code, generate cache file paths and urls
					$file_css_code = implode('', $css_process);
					$css_uid = $tvers.'-'.hash('sha1', $file_css_code);
					$file_css = $fvm_cache_paths['cache_dir_min'] . DIRECTORY_SEPARATOR .  $css_uid.'.min.css';
					$file_css_url = $fvm_cache_paths['cache_url_min'].'/'.$css_uid.'.min.css';
					
					# remove fonts and icons from final css
					if(isset($fvm_settings['css']['fonts']) && $fvm_settings['css']['fonts'] == true) {
						$mff = array();
						preg_match_all('/(\@font-face)([^}]+)(\})/', $file_css_code, $mff);
						if(isset($mff[0]) && is_array($mff[0])) {
							foreach($mff[0] as $ff) {
								$file_css_code = str_replace($ff, '', $file_css_code);
								$lp_css_last_ff.= $ff . PHP_EOL;
							}
						}
					}
					
					# add cdn support
					if(isset($fvm_settings['cdn']['enable']) && $fvm_settings['cdn']['enable'] == true && 
					isset($fvm_settings['cdn']['domain']) && !empty($fvm_settings['cdn']['domain'])) {
						if(isset($fvm_settings['cdn']['cssok']) && $fvm_settings['cdn']['cssok'] == true) {
							$file_css_url = str_replace('//'.$fvm_urls['wp_domain'], '//'.$fvm_settings['cdn']['domain'], $file_css_url);
						}
					}
					
					# generate cache file
					clearstatcache();
					if (!file_exists($file_css)) {
						
						# prepare log
						$log = (array) array_values($fvm_styles_log[$mediatype]);
						$log_meta = array('loc'=>home_url(add_query_arg(NULL, NULL)), 'fl'=>$file_css_url, 'mt'=>$mediatype);
						
						# generate cache, write log
						if(!empty($file_css_code)) {
							fvm_save_log(array('uid'=>$file_css_url, 'date'=>$now, 'type'=>'css', 'meta'=>$log_meta, 'content'=>$log));
							fvm_save_file($file_css, $file_css_code);
						}

					}
					
					# if file exists
					clearstatcache();
					if (file_exists($file_css)) {
						
						# preload and save for html implementation (with priority order prefix)
						if((!isset($fvm_settings['css']['nopreload']) || (isset($fvm_settings['css']['nopreload']) && $fvm_settings['css']['nopreload'] != true)) && (!isset($fvm_settings['css']['inline-all']) || (isset($fvm_settings['css']['inline-all']) && $fvm_settings['css']['inline-all'] != true))) {
							$allpreloads['high'][] = '<link rel="preload" href="'.$file_css_url.'" as="style" media="'.$mediatype.'" importance="high" />';	
						}
								
						# async or render block css
						if(isset($fvm_settings['css']['async']) && $fvm_settings['css']['async'] == true) {
							$htmlcssheader['b_'.$css_uid] = '<link rel="stylesheet" href="'.$file_css_url.'" media="print" onload="this.media=\''.$mediatype.'\'">';
						} else {
							$htmlcssheader['b_'.$css_uid] = '<link rel="stylesheet" href="'.$file_css_url.'" media="'.$mediatype.'" />';
						}
					}

				}
				
				
				# generate merged css files, foreach mediatype
				if(!empty($lp_css_last) || !empty($lp_css_last_ff) || !empty($lp_css_last_ff_inline)) {
					
					# reset to all
					$mediatype = 'all';
					
					# merge code, generate cache file paths and urls
					$file_css_code = implode('', $lp_css_last).$lp_css_last_ff.$lp_css_last_ff_inline;
					$css_uid = $tvers.'-'.hash('sha1', $file_css_code);
					$file_css = $fvm_cache_paths['cache_dir_min'] . DIRECTORY_SEPARATOR .  $css_uid.'.min.css';
					$file_css_url = $fvm_cache_paths['cache_url_min'].'/'.$css_uid.'.min.css';
					
					# add cdn support
					if(isset($fvm_settings['cdn']['enable']) && $fvm_settings['cdn']['enable'] == true && 
					isset($fvm_settings['cdn']['domain']) && !empty($fvm_settings['cdn']['domain'])) {
						if(isset($fvm_settings['cdn']['cssok']) && $fvm_settings['cdn']['cssok'] == true) {
							$file_css_url = str_replace('//'.$fvm_urls['wp_domain'], '//'.$fvm_settings['cdn']['domain'], $file_css_url);
						}
					}
						
					# generate cache file
					clearstatcache();
					if (!file_exists($file_css)) {
						
						# prepare log
						$log = (array) array_values($fvm_styles_log[$mediatype]);
						$log_meta = array('loc'=>home_url(add_query_arg(NULL, NULL)), 'fl'=>$file_css_url, 'mt'=>$mediatype);
						
						# generate cache, write log
						if(!empty($file_css_code)) {
							fvm_save_log(array('uid'=>$file_css_url, 'date'=>$now, 'type'=>'css', 'meta'=>$log_meta, 'content'=>$log));
							fvm_save_file($file_css, $file_css_code);
						}				
					}
					
					# if file exists
					clearstatcache();
					if (file_exists($file_css)) {
						
						# add file with js							
						$htmlcssheader['a_'.$css_uid] = '<script data-cfasync="false" id="fvmlpcss">var fvmft;fvmuag()&&((fvmft=document.getElementById("fvmlpcss")).outerHTML='.fvm_escape_url_js("<link rel='stylesheet' href='". $file_css_url . "' media='".$mediatype."' />").');</script>'; # prepend		
					
					}
						
				}	
				
			}
		}
			
		
		# always disable js minification in certain areas
		$nojsmin = false;
		if(function_exists('is_cart') && is_cart()){ $nojsmin = true; } # cart
		
		# process js, if not disabled
		if(isset($fvm_settings['js']['enable']) && $fvm_settings['js']['enable'] == true && $nojsmin === false) {
			
			# defaults
			$scripts_duplicate_check = array();
			$enable_js_minification = true;
			$htmljscodeheader = array();
			$htmljscodedefer = array();
			$scripts_header = array();
			$scripts_footer = array();
				
			# get all scripts
			$allscripts = array();
			foreach($html->find('script') as $element) {
				$allscripts[] = $element;
			}
							
			# process all scripts
			if (is_array($allscripts) && count($allscripts) > 0) {
				foreach($allscripts as $k=>$tag) {
											
					# handle application/ld+json or application/json before anything else
					if(isset($tag->type) && ($tag->type == 'application/ld+json' || $tag->type == 'application/json')) {
						$tag->innertext = fvm_minify_microdata($tag->innertext);
						unset($allscripts[$k]);
						continue;
					}
					
					# remove js code
					if(isset($tag->outertext) && isset($fvm_settings['js']['remove']) && !empty($fvm_settings['js']['remove'])) {
						$arr = fvm_string_toarray($fvm_settings['js']['remove']);
						if(is_array($arr) && count($arr) > 0) {
							foreach ($arr as $e) { 
								if(stripos($tag->outertext, $e) !== false) {
									$tag->outertext = '';
									unset($allscripts[$k]);
									continue 2;
								} 
							}
						}
					}

			
					# process inline scripts
					if(!isset($tag->src)) {
						
						# default
						$js = '';
						
						# get minification settings for files
						if(isset($fvm_settings['js']['min_disable']) && $fvm_settings['js']['min_disable'] == true) {
							$enable_js_minification = false;
						}	
						
						# minify inline scripts
						$js = $tag->innertext;
						$js = fvm_maybe_minify_js($js, null, $enable_js_minification);

						# Delay third party scripts and tracking codes (uses PHP stripos against the script innerHTML or the src attribute)
						if(isset($fvm_settings['js']['thirdparty']) && !empty($fvm_settings['js']['thirdparty'])) {
							if(isset($fvm_settings['js']['thirdparty']) && !empty($fvm_settings['js']['thirdparty'])) {
								$arr = fvm_string_toarray($fvm_settings['js']['thirdparty']);
								if(is_array($arr) && count($arr) > 0) {
									foreach ($arr as $b) {
										if(stripos($js, $b) !== false) {
											$js = 'if(fvmuag()){window.addEventListener("load",function(){var c=setTimeout(b,5E3),d=["mouseover","keydown","touchmove","touchstart"];d.forEach(function(a){window.addEventListener(a,e,{passive:!0})});function e(){b();clearTimeout(c);d.forEach(function(a){window.removeEventListener(a,e,{passive:!0})})}function b(){console.log("FVM: Loading Third Party Script (inline)!");'.$js.'};});}';
											break;
										}
									}
								}
							}
						}
						
						# delay inline scripts until after the 'window.load' event
						if(isset($fvm_settings['js']['defer_dependencies']) && !empty($fvm_settings['js']['defer_dependencies'])) {
							$arr = fvm_string_toarray($fvm_settings['js']['defer_dependencies']);
							if(is_array($arr) && count($arr) > 0) {
								foreach ($arr as $e) { 
									if(stripos($js, $e) !== false && stripos($js, 'FVM:') === false) {
										$js = 'if(fvmuag()){window.addEventListener("load",function(){console.log("FVM: Loading Inline Dependency!");'.$js.'});}';
									} 
								}
							}
						}
						
								
						# replace tag on the html
						$tag->innertext = $js;
							
						# mark as processed, unset and break inner loop
						unset($allscripts[$k]);
						continue;

					}
					
					
					# process js files
					if(isset($tag->src)) {
						
						# make sure we have a complete url
						$href = fvm_normalize_url($tag->src, $fvm_urls['wp_domain'], $fvm_urls['wp_site_url']);

						# upgrade jQuery library and jQuery migrate to version 3
						if(isset($fvm_settings['js']['jqupgrade']) && $fvm_settings['js']['jqupgrade'] == true) {
							# jquery 3
							if(stripos($tag->src, '/jquery.js') !== false || stripos($tag->src, '/jquery.min.js') !== false) {
								$href = 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js';
							}
							# jquery migrate 3
							if(stripos($tag->src, '/jquery-migrate.') !== false || stripos($tag->src, '/jquery-migrate-') !== false) { $href = 'https://cdnjs.cloudflare.com/ajax/libs/jquery-migrate/3.3.1/jquery-migrate.min.js'; }
						}
						
						# get minification settings for files
						if(isset($fvm_settings['js']['min_disable']) && $fvm_settings['js']['min_disable'] == true) {
							$enable_js_minification = false;
						}
						
						# ignore list, leave these alone
						if(isset($fvm_settings['js']['ignore']) && !empty($fvm_settings['js']['ignore'])) {
							$arr = fvm_string_toarray($fvm_settings['js']['ignore']);
							if(is_array($arr) && count($arr) > 0) {
								foreach ($arr as $e) { 
									if(stripos($tag->src, $e) !== false) {
										continue 2;
									} 
								}
							}
						}				
						
						# Delay third party scripts and tracking codes
						# uses PHP stripos against the script src, if it's async or deferred
						if(isset($tag->async) || isset($tag->defer)) {
							if(isset($fvm_settings['js']['thirdparty']) && !empty($fvm_settings['js']['thirdparty'])) {
								if(isset($fvm_settings['js']['thirdparty']) && !empty($fvm_settings['js']['thirdparty'])) {
									$arr = fvm_string_toarray($fvm_settings['js']['thirdparty']);
									if(is_array($arr) && count($arr) > 0) {
										foreach ($arr as $b) {
											if(stripos($tag->src, $b) !== false) {
												
												# get all extra attributes into $rem
												$rem = '';
												foreach($tag->getAllAttributes() as $k=>$v){
													$k = trim($k); $v = trim($v);
													if($k != 'async' && $k != 'defer' && $k != 'src' && $k != 'type') {
														$rem.= "b.setAttribute('$k','$v');";
													}
												}
												
												# defer attribute? (async is always by default)
												if(isset($tag->defer)) {
													$rem.= 'b.async=false;';
												}
																						
												# generate and set delayed script tag
												$tag->outertext = "<script data-cfasync='false'>".'if(fvmuag()){window.addEventListener("load",function(){var c=setTimeout(b,5E3),d=["mouseover","keydown","touchmove","touchstart"];d.forEach(function(a){window.addEventListener(a,e,{passive:!0})});function e(){b();clearTimeout(c);d.forEach(function(a){window.removeEventListener(a,e,{passive:!0})})}function b(){'."(function(a){var b=a.createElement('script'),c=a.scripts[0];b.src='".trim($tag->src)."';".$rem."c.parentNode.insertBefore(b,c);}(document));".'};});}'."</script>";
												unset($allscripts[$k]);
												continue 2;												
												
											}
										}
									}
								}
							}
						}						
						
						# render blocking scripts in the header
						if(isset($fvm_settings['js']['merge_header']) && !empty($fvm_settings['js']['merge_header'])) {
							$arr = fvm_string_toarray($fvm_settings['js']['merge_header']);
							if(is_array($arr) && count($arr) > 0) {
								foreach ($arr as $e) { 
									if(stripos($href, $e) !== false) {
										
										# download, minify, cache
										$tkey = hash('sha1', $href);
										$js = fvm_get_transient($tkey);
										if ($js === false) {

											# open or download file, get contents
											$ddl = array();
											$ddl = fvm_maybe_download($href);
										
											# if success
											if(isset($ddl['content'])) {
												
												# contents
												$js = $ddl['content'];
																								
												# minify, save and wrap
												$js = fvm_maybe_minify_js($js, $href, $enable_js_minification);
																								
												# quick integrity check
												if(!empty($js) && $js !== false) {
												
													# try catch
													$js = fvm_try_catch_wrap($js, $href);
													
													# developers filter
													$js = apply_filters( 'fvm_after_download_and_minify_code', $js, 'js');
																
													# execution time in ms, size in bytes
													$fs = strlen($js);
													$ur = str_replace($fvm_urls['wp_site_url'], '', $href);
													$tkey_meta = array('fs'=>$fs, 'url'=>str_replace($fvm_cache_paths['cache_url_min'].'/', '', $ur));
																	
													# save
													fvm_set_transient(array('uid'=>$tkey, 'date'=>$tvers, 'type'=>'js', 'content'=>$js, 'meta'=>$tkey_meta));	
													
												}
											}
										}

										# processed successfully?
										if ($js !== false) {
										
											# collect and mark as done for html removal
											$scripts_header[$tkey] = $js;
											$scripts_header_log[$tkey] = $tkey;
											
											# mark as processed, unset and break inner loop
											$tag->outertext = '';
											unset($allscripts[$k]);
											continue 2;
										
										} else {
										
											# there is an error, so leave them alone
											$err = ''; if(isset($ddl['error'])) { $err = '<!-- '.$ddl['error'].' -->'; }
											$tag->outertext = PHP_EOL . $tag->outertext.$err . PHP_EOL;
											unset($allscripts[$k]);
											continue 2;
											
										}
										
									} 
								}
							}
						}
					
							
						# merge and defer scripts
						if(isset($fvm_settings['js']['merge_defer']) && !empty($fvm_settings['js']['merge_defer'])) {
							$arr = fvm_string_toarray($fvm_settings['js']['merge_defer']);
							if(is_array($arr) && count($arr) > 0) {
								foreach ($arr as $e) { 
									if(stripos($href, $e) !== false) {

										# download, minify, cache
										$tkey = hash('sha1', $href);
										$js = fvm_get_transient($tkey);
										if ($js === false) {

											# open or download file, get contents
											$ddl = array();
											$ddl = fvm_maybe_download($href);
										
											# if success
											if(isset($ddl['content'])) {
												
												# contents
												$js = $ddl['content'];
															
												# minify, save and wrap
												$js = fvm_maybe_minify_js($js, $href, $enable_js_minification);
														
												# quick integrity check
												if(!empty($js) && $js !== false) {
													
													# try catch
													$js = fvm_try_catch_wrap($js, $href);
												
													# developers filter
													$js = apply_filters( 'fvm_after_download_and_minify_code', $js, 'js');
																													
													# execution time in ms, size in bytes
													$fs = strlen($js);
													$ur = str_replace($fvm_urls['wp_site_url'], '', $href);
													$tkey_meta = array('fs'=>$fs, 'url'=>str_replace($fvm_cache_paths['cache_url_min'].'/', '', $ur));
																
													# save
													fvm_set_transient(array('uid'=>$tkey, 'date'=>$tvers, 'type'=>'js', 'content'=>$js, 'meta'=>$tkey_meta));	
																
												}
											}
										}
										
										# processed successfully?
										if ($js !== false) {
													
											# collect and mark as done for html removal
											$scripts_footer[$tkey] = $js;
											$scripts_footer_log[$tkey] = $tkey;
											
											# mark as processed, unset and break inner loop
											$tag->outertext = '';
											unset($allscripts[$k]);
											continue 2;
											
										}
										
									} 
								}
							}
						}
				
					}
					
				}
			}
			


			# generate header merged scripts
			if(count($scripts_header) > 0) {

				# merge code, generate cache file paths and urls
				$fheader_code = implode('', $scripts_header);
				$js_header_uid = $tvers.'-'.hash('sha1', $fheader_code).'.header';
				$fheader = $fvm_cache_paths['cache_dir_min']  . DIRECTORY_SEPARATOR .  $js_header_uid.'.min.js';
				$fheader_url = $fvm_cache_paths['cache_url_min'].'/'.$js_header_uid.'.min.js';
				
				# add cdn support
				if(isset($fvm_settings['cdn']['enable']) && $fvm_settings['cdn']['enable'] == true && 
				isset($fvm_settings['cdn']['domain']) && !empty($fvm_settings['cdn']['domain'])) {
					if(isset($fvm_settings['cdn']['jsok']) && $fvm_settings['cdn']['jsok'] == true) {
						$fheader_url = str_replace('//'.$fvm_urls['wp_domain'], '//'.$fvm_settings['cdn']['domain'], $fheader_url);
					}
				}

				# generate cache file
				clearstatcache();
				if (!file_exists($fheader)) {
					
					# prepare log
					$log = (array) array_values($scripts_header_log);
					$log_meta = array('loc'=>home_url(add_query_arg(NULL, NULL)), 'fl'=>$fheader_url);
					
					# generate cache, write log
					if(!empty($fheader_code)) {
						fvm_save_log(array('uid'=>$fheader_url, 'date'=>$now, 'type'=>'js', 'meta'=>$log_meta, 'content'=>$log));
						fvm_save_file($fheader, $fheader_code);
					}
				}
				
				# preload and save for html implementation (with priority order prefix)
				if(!isset($fvm_settings['js']['nopreload']) || (isset($fvm_settings['js']['nopreload']) && $fvm_settings['js']['nopreload'] != true)) {
					$allpreloads['high'][] = '<link rel="preload" href="'.$fheader_url.'" as="script" importance="high" />';
				}
				
				# header
				$htmljscodeheader['c_'.$js_header_uid] = '<script data-cfasync="false" src="'.$fheader_url.'"></script>';
				
			}
			
			# generate footer merged scripts
			if(count($scripts_footer) > 0) {
				
				# merge code, generate cache file paths and urls
				$ffooter_code = implode('', $scripts_footer);
				$js_ffooter_uid = $tvers.'-'.hash('sha1', $ffooter_code).'.footer';
				$ffooter = $fvm_cache_paths['cache_dir_min']  . DIRECTORY_SEPARATOR .  $js_ffooter_uid.'.min.js';
				$ffooter_url = $fvm_cache_paths['cache_url_min'].'/'.$js_ffooter_uid.'.min.js';
				
				# add cdn support
				if(isset($fvm_settings['cdn']['enable']) && $fvm_settings['cdn']['enable'] == true && 
				isset($fvm_settings['cdn']['domain']) && !empty($fvm_settings['cdn']['domain'])) {
					if(isset($fvm_settings['cdn']['jsok']) && $fvm_settings['cdn']['jsok'] == true) {
						$ffooter_url = str_replace('//'.$fvm_urls['wp_domain'], '//'.$fvm_settings['cdn']['domain'], $ffooter_url);

					}
				}
				
				# generate cache file
				clearstatcache();
				if (!file_exists($ffooter)) {
					
					# prepare log
					$log = (array) array_values($scripts_footer_log);
					$log_meta = array('loc'=>home_url(add_query_arg(NULL, NULL)), 'fl'=>$ffooter_url);
												
					# generate cache, write log
					if(!empty($ffooter_code)) {
						fvm_save_log(array('uid'=>$ffooter_url, 'date'=>$now, 'type'=>'js', 'meta'=>$log_meta, 'content'=>$log));
						fvm_save_file($ffooter, $ffooter_code);
					}
				}
						
				# preload and save for html implementation (with priority order prefix)
				if(!isset($fvm_settings['js']['nopreload']) || (isset($fvm_settings['js']['nopreload']) && $fvm_settings['js']['nopreload'] != true)) {
					$allpreloads['low'][] = '<link rel="preload" href="'.$ffooter_url.'" as="script" importance="low" />';
				}
				
				# header
				$htmljscodedefer['d_'.$js_ffooter_uid] = '<script data-cfasync="false" defer src="'.$ffooter_url.'"></script>';
						
			}

		}
		
		
	
		# process html, if not disabled
		if(isset($fvm_settings['html']['enable']) && $fvm_settings['html']['enable'] == true) {
			
			# Remove HTML comments and IE conditionals
			if(isset($fvm_settings['html']['nocomments']) && $fvm_settings['html']['nocomments'] == true) {
				foreach($html->find('comment') as $element) {
					 $element->outertext = '';
				}
			}
			
			# cleanup header
			if(isset($fvm_settings['html']['cleanup_header']) && $fvm_settings['html']['cleanup_header'] == true) {
				foreach($html->find('head meta[name=generator], head link[rel=shortlink], head link[rel=dns-prefetch], head link[rel=preconnect], head link[rel=prefetch], head link[rel=prerender], head link[rel=EditURI], head link[rel=preconnect], head link[rel=wlwmanifest], head link[type=application/rss+xml], head link[rel=https://api.w.org/], head link[type=application/json+oembed], head link[type=text/xml+oembed], head meta[name*=msapplication], head link[rel=apple-touch-icon]') as $element) {
					 $element->outertext = '';
				}
			}
			
		}
		
		# cdn rewrites, when needed
		$html = fvm_rewrite_assets_cdn($html);

		# build extra head and footer ###############################	
		
		# header and footer markers
		$hm = '<!-- h_preheader --><!-- h_header_function --><!-- h_cssheader --><!-- h_jsheader -->';
		$fm = '<!-- h_footer_lozad -->';
		
		# add our function to head
		$hm = fvm_add_header_function($hm);
		
		# remove charset meta tag and collect it to first position
		if(!is_null($html->find('meta[charset]', 0))) {
			$hm = str_replace('<!-- h_preheader -->', $html->find('meta[charset]', 0)->outertext.'<!-- h_preheader -->', $hm);
			foreach($html->find('meta[charset]') as $element) { $element->outertext = ''; }
		}

		# preload headers, by importance
		if(is_array($allpreloads)) {
			
			# start
			$preload = '';
			
			# highest priority (rewritten as high, but earlier)
			if(isset($allpreloads['highest'])) {
				$preload.= implode(PHP_EOL, $allpreloads['highest']);
			}	
			
			# high priority
			if(isset($allpreloads['high'])) {
				$preload.= implode(PHP_EOL, $allpreloads['high']);
			}		
					
			# auto priority
			if(isset($allpreloads['auto'])) {
				$preload.= implode(PHP_EOL, $allpreloads['auto']);
			}						
			
			# low priority
			if(isset($allpreloads['low'])) {
				$preload.= implode(PHP_EOL, $allpreloads['low']);
			}	
				
			# add preload
			if(!empty($preload)) {
				$hm = str_replace('<!-- h_preheader -->', $preload.'<!-- h_preheader -->', $hm);
			}
		}
		
		# add critical path
		if(isset($critical_path)) {
			if(is_array($critical_path) && count($critical_path) > 0) {
				$hm = str_replace('<!-- h_preheader -->', implode(PHP_EOL, $critical_path).'<!-- h_preheader -->', $hm);
			}		
		}
		
		# add stylesheets
		if(isset($htmlcssheader)) {
			if(is_array($htmlcssheader) && count($htmlcssheader) > 0) {
				ksort($htmlcssheader); # priority
				$hm = str_replace('<!-- h_cssheader -->', implode(PHP_EOL, $htmlcssheader).'<!-- h_cssheader -->', $hm);
			}
		}
		
		# add header scripts
		if(isset($htmljscodeheader)) {
			if(is_array($htmljscodeheader) && count($htmljscodeheader) > 0) {
				ksort($htmljscodeheader); # priority
				$hm = str_replace('<!-- h_jsheader -->', implode(PHP_EOL, $htmljscodeheader).'<!-- h_jsheader -->', $hm);
			}
		}
		
		# add defer scripts
		if(isset($htmljscodedefer)) {
			if(is_array($htmljscodedefer) && count($htmljscodedefer) > 0) {
				ksort($htmljscodedefer); # priority
				$hm = str_replace('<!-- h_jsheader -->', implode(PHP_EOL, $htmljscodedefer), $hm);
			}
		}
		
		# cleanup leftover markers
		$hm = str_replace(
			  array('<!-- h_preheader -->', '<!-- h_header_function -->', '<!-- h_cssheader -->', '<!-- h_jsheader -->'), '', $hm); 
		$fm = str_replace('<!-- h_footer_lozad -->', '', $fm);
		
			
		# Save HTML and output page ###############################	
		
		# append header and footer, if available
		if(!is_null($html->find('head', 0)) && !is_null($html->find('body', -1))) {
			if(!is_null($html->find('head', 0)->first_child()) && !is_null($html->find('body', -1)->last_child())) {
				$html->find('head', 0)->first_child()->outertext = $hm . $html->find('head', 0)->first_child()->outertext;
				$html->find('body', -1)->last_child()->outertext = $html->find('body', -1)->last_child ()->outertext . $fm;
			}
		}
		
		# convert html object to string
		$html = trim($html->save());
		
		# minify HTML, if not disabled
		if(!isset($fvm_settings['html']['min_disable']) || (isset($fvm_settings['html']['min_disable']) && $fvm_settings['html']['min_disable'] != true)) {
			$html = fvm_raisermin_html($html, true);
		}
		
	}
		
	# return html
	return $html;
	
}

