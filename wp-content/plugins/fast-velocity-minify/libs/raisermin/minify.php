<?php
/**
*
* Website: https://wpraiser.com/
* Author: Raul Peixoto (https://www.upwork.com/fl/raulpeixoto)
* Licensed under GPLv2 (or later)
* Version 1.0
*
* Usage: fvm_raisermin_js($js);
*
*/

# Exit if accessed directly				
if (!defined('ABSPATH')){ exit(); }	

# minify js, whitespace only
function fvm_raisermin_js($code){

	# remove // comments
	$code = preg_replace('/(^|\s)\/\/(.*)\n/m', '', $code);
	$code = preg_replace('/(\{|\}|\[|\]|\(|\)|\;)\/\/(.*)\n/m', '$1', $code);
	
	# remove /* ... */ comments
	$code = preg_replace('/(^|\s)\/\*(.*)\*\//Us', '', $code);
	$code = preg_replace('/(\;|\{)\/\*(.*)\*\//Us', '$1', $code);

	# remove sourceMappingURL
	$code = preg_replace('/(\/\/\s*[#]\s*sourceMappingURL\s*[=]\s*)([a-zA-Z0-9-_\.\/]+)(\.map)/ui', '', $code);
	
	# uniform line endings, make them all line feed
	$code = str_replace(array("\r\n", "\r"), "\n", $code);

	# collapse all non-line feed whitespace into a single space
	$code = preg_replace('/[^\S\n]+/', ' ', $code);

	# strip leading & trailing whitespace
	$code = str_replace(array(" \n", "\n "), "\n", $code);

	# collapse consecutive line feeds into just 1
	$code = preg_replace('/\n+/', "\n", $code);
		
	# process horizontal space
	$code = preg_replace('/([\[\]\(\)\{\}\;\<\>])(\h+)([\[\]\(\)\{\}\;\<\>])/ui', '$1 $3', $code);
	$code = preg_replace('/([\)])(\h?)(\.)/ui', '$1$3', $code);
	$code = preg_replace('/([\)\?])(\h?)(\.)/ui', '$1$3', $code);
	$code = preg_replace('/(\,)(\h+)/ui', '$1 ', $code);
	$code = preg_replace('/(\h+)(\,)/ui', ' $2', $code);
	$code = preg_replace('/([if])(\h+)(\()/ui', '$1$3', $code);
			
	# trim whitespace on beginning/end
	return trim($code);
}


# remove UTF8 BOM
function fvm_min_remove_utf8_bom($text) {
    $bom = pack('H*','EFBBBF');
	while (preg_match("/^$bom/", $text)) {
		$text = preg_replace("/^$bom/ui", '', $text);
	}
    return $text;
}




# minify html, don't touch certain tags
function fvm_raisermin_html($html, $xtra) {

	# clone
	$content = $html;
		
	# get all scripts
	$allscripts = array();
	preg_match_all('/\<script(.*?)\<(\s*)\/script(\s*)\>/uis', $html, $allscripts);
	
	# replace all with a marker
	if(is_array($allscripts) && isset($allscripts[0]) && count($allscripts[0]) > 0) {
		foreach ($allscripts[0] as $k=>$v) {
			$content = str_replace($v, '<!-- SCRIPT '.$k.' -->', $content);
		}
	}
	
	# get all <code> sections
	$allcodes = array();
	preg_match_all('/\<code(.*?)\<(\s*)\/code(\s*)\>/uis', $html, $allcodes);
	
	# replace all with a marker
	if(is_array($allcodes) && isset($allcodes[0]) && count($allcodes[0]) > 0) {
		foreach ($allcodes[0] as $k=>$v) {
			$content = str_replace($v, '<!-- CODE '.$k.' -->', $content);
		}
	}
	
	# get all <pre> sections
	$allpres = array();
	preg_match_all('/\<pre(.*?)\<(\s*)\/pre(\s*)\>/uis', $html, $allpres);
	
	# replace all with a marker
	if(is_array($allpres) && isset($allpres[0]) && count($allpres[0]) > 0) {
		foreach ($allpres[0] as $k=>$v) {
			$content = str_replace($v, '<!-- PRE '.$k.' -->', $content);
		}
	}	
			
	# remove line breaks, and colapse two or more white spaces into one
	$content = preg_replace('/\s+/u', " ", $content);
	
	# remove space between tags
	$content = str_replace('> <', '><', $content);
		
	# add extra line breaks to code?
	if($xtra === true) {
	
		# add linebreaks after meta tags, for readability
		$allmeta = array();
		preg_match_all('/\<meta(.*?)\>/uis', $html, $allmeta);
		
		# replace all scripts and styles with a marker
		if(is_array($allmeta) && isset($allmeta[0]) && count($allmeta[0]) > 0) {
			foreach ($allmeta[0] as $k=>$v) {
				$content = str_replace($v,  PHP_EOL . $v . PHP_EOL, $content);
			}
		}
		
		# add linebreaks after link tags, for readability
		$alllink = array();
		preg_match_all('/\<link(.*?)\>/uis', $html, $alllink);
		
		# replace all scripts and styles with a marker
		if(is_array($alllink) && isset($alllink[0]) && count($alllink[0]) > 0) {
			foreach ($alllink[0] as $k=>$v) {
				$content = str_replace($v,  PHP_EOL . $v . PHP_EOL, $content);
			}
		}
	
		# add linebreaks after style tags, for readability
		$allstyles = array();
		preg_match_all('/\<s(.*?)\<(\s*)\/style(\s*)\>/uis', $html, $allstyles);
		
		# replace all scripts and styles with a marker
		if(is_array($allstyles) && isset($allstyles[0]) && count($allstyles[0]) > 0) {
			foreach ($allstyles[0] as $k=>$v) {
				$content = str_replace($v,  PHP_EOL . $v . PHP_EOL, $content);
			}
		}

		# add linebreaks after html and head tags, for readability
		$content = str_replace('<head>',  PHP_EOL . '<head>' . PHP_EOL, $content);
		$content = str_replace('</head>',  PHP_EOL . '</head>' . PHP_EOL, $content);
		$content = str_replace('<html',  PHP_EOL . '<html', $content);
		$content = str_replace('</html>',  PHP_EOL . '</html>', $content);
	
	}
	
	# replace markers for scripts		
	if(is_array($allscripts) && isset($allscripts[0]) && count($allscripts[0]) > 0) {
		foreach ($allscripts[0] as $k=>$v) {
			if($xtra === true) { 
				$content = str_replace('<!-- SCRIPT '.$k.' -->',  PHP_EOL . $v . PHP_EOL, $content);
			} else {
				$content = str_replace('<!-- SCRIPT '.$k.' -->', $v, $content);
			}
		}
	}
	
	# no more than 1 linebreak
	$content = preg_replace('/\v{2,}/u', PHP_EOL, $content);
	
	# replace markers for <code>	
	if(is_array($allcodes) && isset($allcodes[0]) && count($allcodes[0]) > 0) {
		foreach ($allcodes[0] as $k=>$v) {
			if($xtra === true) { 
				$content = str_replace('<!-- CODE '.$k.' -->',  PHP_EOL . $v . PHP_EOL, $content);
			} else {
				$content = str_replace('<!-- CODE '.$k.' -->', $v, $content);
			}
		}
	}
	
	# replace markers for <pre>	
	if(is_array($allpres) && isset($allpres[0]) && count($allpres[0]) > 0) {
		foreach ($allpres[0] as $k=>$v) {
			if($xtra === true) { 
				$content = str_replace('<!-- PRE '.$k.' -->',  PHP_EOL . $v . PHP_EOL, $content);
			} else {
				$content = str_replace('<!-- PRE '.$k.' -->', $v, $content);
			}
		}
	}	
		
	# save as html, if not empty
	if(!empty($content)) {
		$html = $content;
	}
			
	# return
	return $html;
}
