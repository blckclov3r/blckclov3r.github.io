<?php
/**
 * Shift8 CDN Rewrite Class
 *
 * Class used to rewrite assets to go through CDN urls
 *
 */

if ( !defined( 'ABSPATH' ) ) {
    die();
}


class Shift8_CDN {

	/**
	 * Options instance
	 *
	 * @var Options_Data
	 */
	private $shift8_options;
	private $shift8_subst;

	/**
	 * Class constructor.
	 */
	public function __construct() {

		add_action( 'template_redirect', array( $this, 'template_redirect' ) );
		add_filter( 'rewrite_urls',      array( $this, 'filter' ) );
		add_filter( 'wp_calculate_image_srcset', array( $this, 'rewrite_srcset'), PHP_INT_MAX );
		add_filter( 'script_loader_src', array( $this, 'cdn_script_loader_src' ), 10, 2 );
		$this->shift8_options = shift8_cdn_check_options();

		// Determine which CDN suffix to use
		if (shift8_cdn_check_paid_transient() === S8CDN_SUFFIX_PAID) {
			$this->shift8_subst = 'https://' . $this->shift8_options['cdn_prefix'] . S8CDN_SUFFIX_PAID;
		} else if (shift8_cdn_check_paid_transient() === S8CDN_SUFFIX){
			$this->shift8_subst = 'https://' . $this->shift8_options['cdn_prefix'] . S8CDN_SUFFIX;
		} else {
			$this->shift8_subst = 'https://' . $this->shift8_options['cdn_prefix'] . S8CDN_SUFFIX_SECOND;
		}

	}

	/*
	 * Filtering URLs.
	 *
	 * @param   string   $content   The content to be filtered
	 * @return  string   $content   The modified content after URL rewriting
	 * 
	 */
	public function filter( $content ) {

		// This is a critical point at which you must add rules for rewriting URL's
		$rewrites = apply_filters( 'shift8_cdn_rewrites', array() );
		return $this->rewrite($content);
	}

	/**
	 * Search & Replace URLs with the CDN URLs in the provided content
	 * @param string $html HTML content.
	 * @return string
	 */
	public function rewrite( $html ) {
		$base_url = parse_url((empty(esc_attr(get_option('shift8_cdn_url'))) ? get_site_url() : esc_attr(get_option('shift8_cdn_url'))));
		$pattern_url = $base_url['scheme'] . '://' . $base_url['host'];
		$pattern = '#[("\']\s*(?<url>(?:(?:https?:|)' . preg_quote( $pattern_url, '#' ) . ')\/(?:(?:(?:' . $this->get_allowed_paths() . ')[^"\',)]+))|\/[^/](?:[^"\')\s>]+\.[[:alnum:]]+))\s*["\')]#i';

		return preg_replace_callback(
			$pattern,
			function( $matches ) {
				$uri = parse_url($matches['url']);
				$query_string = (parse_url($matches['url'], PHP_URL_QUERY) ? '?' . parse_url($matches['url'], PHP_URL_QUERY) : null);
				if (!$this->is_excluded( $matches['url'] )) {
					return str_replace($matches['url'], $this->shift8_subst . $uri['path'] . $query_string, $matches[0]);
				} else {
					return $matches[0];									
				}
			},
			$html
		);
	}

	/*
	 * Starting page buffer.
	 */
	public function template_redirect() {
		ob_start( array( $this, 'ob' ) );
	}

	/*
	 * Rewriting URLs once buffer ends.
	 *
	 * @return  string  The filtered page output including rewritten URLs.
	 */
	public function ob( $contents ) {
		return apply_filters( 'rewrite_urls', $contents, $this );
	}

	/**
	 * Rewrites URLs in srcset attributes to the CDN URLs 
	 * @param string $html HTML content.
	 * @return string
	 */
    function rewrite_srcset( $sources ) {
        if ( (bool) $sources ) {
            $uri = parse_url((empty(esc_attr(get_option('shift8_cdn_url'))) ? get_site_url() : esc_attr(get_option('shift8_cdn_url'))));
            $origin_host = $uri['scheme'] . '://' . $uri['host'];

            foreach ( $sources as $i => $source ) {
                $sources[ $i ]['url'] = str_replace( [ $origin_host ], $this->shift8_subst, $source['url'] );
            }
        }
        return $sources;
    }

    /**
     * Rewrites the emoji js file to go through the CDN
     * @param string 
     * @return string
     */
	public function cdn_script_loader_src( $source, $scriptname ) {		
		if ($scriptname == 'concatemoji') {
			$uri = parse_url((empty(esc_attr(get_option('shift8_cdn_url'))) ? get_site_url() : esc_attr(get_option('shift8_cdn_url'))));
	        $origin_host = $uri['scheme'] . '://' . $uri['host'];

			$source = str_replace($origin_host, $this->shift8_subst, $source);
		}
		return $source;
	}

	/**
	 * Checks if the provided URL should be allowed to be rewritten with the CDN URL
	 * @param string $url URL to check.
	 * @return boolean
	 */
	public function is_excluded( $url ) {
		$path = wp_parse_url( $url, PHP_URL_PATH );

		// Match specified excludes first
		if ( !empty($this->get_excluded_files( '#' )) && preg_match( '#^((.*)' . $this->get_excluded_files( '#' ) . ')$#', $path ) ) {
			return true;
		}

		// Build regex extensions
		$excluded_extensions = array();
		if($this->shift8_options['static_css'] !== 'on') $excluded_extensions[] = 'css';
		if($this->shift8_options['static_js'] !== 'on') $excluded_extensions[] = 'js';
		if($this->shift8_options['static_media'] !== 'on') {
			$excluded_extensions[] = explode('|','jpg|jpeg|png|gif|bmp|pdf|mp3|m4a|ogg|wav|mp4|m4v|mov|wmv|avi|mpg|ogv|3gp|3g2|webp|svg');
			$excluded_extensions = $this->flatten_array($excluded_extensions);
		}

		if ( !empty($excluded_extensions) && in_array( pathinfo( $path, PATHINFO_EXTENSION ), $excluded_extensions, true ) ) {
			return true;
		}

		if ( ! $path ) {
			return true;
		}

		if ( '/' === $path ) {
			return true;
		}

		return false;
	}

	/**
	 * Get all files we dont want to be passed through the CDN
	 * @param string $delimiter RegEx delimiter.
	 * @return string A pipe-separated list of excluded files.
	 */
	private function get_excluded_files( $delimiter ) {
		$files = esc_textarea(get_option('shift8_cdn_reject_files'));
		$files = (array) apply_filters( 'shift8_cdn_reject_files', $files );
		$files = array_filter( $files );

		if ( ! $files ) {
			return '';
		}

		$files = array_flip( array_flip( $files ) );
		$files = array_map(
			function ( $file ) use ( $delimiter ) {
				return str_replace( $delimiter, '\\' . $delimiter, $file );
			},
			$files
		);

		return implode( '|', $files );
	}

	/**
	 * Flatten multidimensional array
	 * @param multidimensional array
	 * @return flattened one dimensional array
	 */
	function flatten_array(array $array) {
	    $flattened_array = array();
	    array_walk_recursive($array, function($a) use (&$flattened_array) { $flattened_array[] = $a; });
	    return $flattened_array;
	}

	/**
	 * Gets the allowed paths as a regex pattern for the CDN rewrite
	 *
	 * @return string
	 */
	private function get_allowed_paths() {
		$wp_content_dirname  = ltrim( trailingslashit( wp_parse_url( content_url(), PHP_URL_PATH ) ), '/' );
		$wp_includes_dirname = ltrim( trailingslashit( wp_parse_url( includes_url(), PHP_URL_PATH ) ), '/' );

		$upload_dirname = '';
		$uploads_info   = wp_upload_dir();

		if ( ! empty( $uploads_info['baseurl'] ) ) {
			$upload_dirname = '|' . ltrim( trailingslashit( wp_parse_url( $uploads_info['baseurl'], PHP_URL_PATH ) ), '/' );
		}

		return $wp_content_dirname . $upload_dirname . '|' . $wp_includes_dirname;
	}
}

// Dont do anything unless we're enabled
if (shift8_cdn_check_enabled()) {
	new Shift8_CDN;
}