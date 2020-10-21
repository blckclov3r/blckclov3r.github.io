<?php
namespace WpAssetCleanUp\OptimiseAssets;

use MatthiasMullie\Minify\Minify;

use MatthiasMullie\PathConverter\ConverterInterface;
use MatthiasMullie\PathConverter\Converter;

/**
 * Combine CSS Imports extended from CSS minifier
 *
 * Please report bugs on https://github.com/matthiasmullie/minify/issues
 *
 * @package Minify
 * @author Matthias Mullie <minify@mullie.eu>
 * @author Tijs Verkoyen <minify@verkoyen.eu>
 * @copyright Copyright (c) 2012, Matthias Mullie. All rights reserved
 * @license MIT License
 */
class CombineCssImports extends Minify
{
	/**
	 * @var int maximum import size in kB
	 */
	protected $maxImportSize = 5;

	/**
	 * @var string[] valid import extensions
	 */
	protected $importExtensions = array(
		'gif' => 'data:image/gif',
		'png' => 'data:image/png',
		'jpe' => 'data:image/jpeg',
		'jpg' => 'data:image/jpeg',
		'jpeg' => 'data:image/jpeg',
		'svg' => 'data:image/svg+xml',
		'woff' => 'data:application/x-font-woff',
		'tif' => 'image/tiff',
		'tiff' => 'image/tiff',
		'xbm' => 'image/x-xbitmap',
	);

	/**
	 * Set the maximum size if files to be imported.
	 *
	 * Files larger than this size (in kB) will not be imported into the CSS.
	 * Importing files into the CSS as data-uri will save you some connections,
	 * but we should only import relatively small decorative images so that our
	 * CSS file doesn't get too bulky.
	 *
	 * @param int $size Size in kB
	 */
	public function setMaxImportSize($size)
	{
		$this->maxImportSize = $size;
	}

	/**
	 * Set the type of extensions to be imported into the CSS (to save network
	 * connections).
	 * Keys of the array should be the file extensions & respective values
	 * should be the data type.
	 *
	 * @param string[] $extensions Array of file extensions
	 */
	public function setImportExtensions(array $extensions)
	{
		$this->importExtensions = $extensions;
	}

	/**
	 * Move any import statements to the top.
	 *
	 * @param string $content Nearly finished CSS content
	 *
	 * @return string
	 */
	protected function moveImportsToTop($content)
	{
		if (preg_match_all('/(;?)(@import (?<url>url\()?(?P<quotes>["\']?).+?(?P=quotes)(?(url)\)));?/', $content, $matches)) {
			// remove from content
			foreach ($matches[0] as $import) {
				$content = str_replace($import, '', $content);
			}

			// add to top
			$content = implode(';', $matches[2]).';'.trim($content, ';');
		}

		return $content;
	}

	/**
	 * Combine CSS from import statements.
	 *
	 * @import's will be loaded and their content merged into the original file,
	 * to save HTTP requests.
	 *
	 * @param string   $source  The file to combine imports for
	 * @param string   $content The CSS content to combine imports for
	 * @param string[] $parents Parent paths, for circular reference checks
	 *
	 * @return string
	 *
	 */
	protected function combineImports($source, $content, $parents)
	{
		$importRegexes = array(
			// @import url(xxx)
			'/
            # import statement
            @import

            # whitespace
            \s+

                # open url()
                url\(

                    # (optional) open path enclosure
                    (?P<quotes>["\']?)

                        # fetch path
                        (?P<path>.+?)

                    # (optional) close path enclosure
                    (?P=quotes)

                # close url()
                \)

                # (optional) trailing whitespace
                \s*

                # (optional) media statement(s)
                (?P<media>[^;]*)

                # (optional) trailing whitespace
                \s*

            # (optional) closing semi-colon
            ;?

            /ix',

			// @import 'xxx'
			'/

            # import statement
            @import

            # whitespace
            \s+

                # open path enclosure
                (?P<quotes>["\'])

                    # fetch path
                    (?P<path>.+?)

                # close path enclosure
                (?P=quotes)

                # (optional) trailing whitespace
                \s*

                # (optional) media statement(s)
                (?P<media>[^;]*)

                # (optional) trailing whitespace
                \s*

            # (optional) closing semi-colon
            ;?

            /ix',
		);

		// find all relative imports in css
		$matches = array();
		foreach ($importRegexes as $importRegex) {
			if (preg_match_all($importRegex, $content, $regexMatches, PREG_SET_ORDER)) {
				$matches = array_merge($matches, $regexMatches);
			}
		}

		$search = array();
		$replace = array();

		// loop the matches
		foreach ($matches as $match) {
			// get the path for the file that will be imported
			$importPath = dirname($source).'/'.$match['path'];

			// only replace the import with the content if we can grab the
			// content of the file
			if (!$this->canImportByPath($match['path']) || !$this->canImportFile($importPath)) {
				continue;
			}

			// check if current file was not imported previously in the same
			// import chain.
			if (in_array($importPath, $parents)) {
				// No need to have and endless loop (the same file imported again and again)
				$search[] = $match[0];
				$replace[] = '';
				continue;
				}

			// grab referenced file & optimize it (which may include importing
			// yet other @import statements recursively)
			$minifier = new static($importPath);
			$minifier->setMaxImportSize($this->maxImportSize);
			$minifier->setImportExtensions($this->importExtensions);
			$importContent = $minifier->execute($source, $parents);

			// check if this is only valid for certain media
			if (!empty($match['media'])) {
				$importContent = '@media '.$match['media'].'{'.$importContent.'}';
			}

			// add to replacement array
			$search[] = $match[0];
			$replace[] = $importContent;
		}

		// replace the import statements
		return str_replace($search, $replace, $content);
	}

	/**
	 * @param $css
	 *
	 * @return mixed
	 */
	protected function alterImportsBetweenComments($css)
	{
		preg_match_all('~/\*.*?@import(.*?)\*/~', $css, $commentsMatches);

		if (isset($commentsMatches[0]) && ! empty($commentsMatches[0])) {
			foreach ($commentsMatches[0] as $commentMatch) {
				$newComment = str_replace('@import', '(wpacu)(at)import', $commentMatch);
				$css = str_replace($commentMatch, $newComment, $css);
			}
		}

		return $css;
	}


	/**
	 * Import files into the CSS, base64-sized.
	 *
	 * @url(image.jpg) images will be loaded and their content merged into the
	 * original file, to save HTTP requests.
	 *
	 * @param string $source  The file to import files for
	 * @param string $content The CSS content to import files for
	 *
	 * @return string
	 */
	protected function importFiles($source, $content)
	{
		$regex = '/url\((["\']?)(.+?)\\1\)/i';
		if ($this->importExtensions && preg_match_all($regex, $content, $matches, PREG_SET_ORDER)) {
			$search = array();
			$replace = array();

			// loop the matches
			foreach ($matches as $match) {
				$extension = substr(strrchr($match[2], '.'), 1);
				if ($extension && !array_key_exists($extension, $this->importExtensions)) {
					continue;
				}

				// get the path for the file that will be imported
				$path = $match[2];
				$path = dirname($source).'/'.$path;

				// only replace the import with the content if we're able to get
				// the content of the file, and it's relatively small
				if ($this->canImportFile($path) && $this->canImportBySize($path)) {
					// grab content && base64-ize
					$importContent = $this->load($path);
					$importContent = base64_encode($importContent);

					// build replacement
					$search[] = $match[0];
					$replace[] = 'url('.$this->importExtensions[$extension].';base64,'.$importContent.')';
				}
			}

			// replace the import statements
			$content = str_replace($search, $replace, $content);
		}

		return $content;
	}

	/**
	 * Perform CSS optimizations.
	 *
	 * @param string[optional] $path    Path to write the data to
	 * @param string[]         $parents Parent paths, for circular reference checks
	 *
	 * @return string The minified data
	 */
	public function execute($path = null, $parents = array())
	{
		$content = '';

		// loop CSS data (raw data and files)
		foreach ($this->data as $source => $css) {
			// Some developers might have wrapped @import between comments
			// No import for those ones
			$css = $this->alterImportsBetweenComments($css);

			$source = is_int($source) ? '' : $source;
			$parents = $source ? array_merge($parents, array($source)) : $parents;
			$css = $this->combineImports($source, $css, $parents);
			$css = $this->importFiles($source, $css);

			/*
			 * If we'll save to a new path, we'll have to fix the relative paths
			 * to be relative no longer to the source file, but to the new path.
			 * If we don't write to a file, fall back to same path so no
			 * conversion happens (because we still want it to go through most
			 * of the move code, which also addresses url() & @import syntax...)
			 */
			$converter = $this->getPathConverter($source, $path ?: $source);
			$css = $this->move($converter, $css);

			// combine css
			$content .= $css;
		}

		$content = $this->moveImportsToTop($content);

		return $content;
	}

	/**
	 * Moving a css file should update all relative urls.
	 * Relative references (e.g. ../images/image.gif) in a certain css file,
	 * will have to be updated when a file is being saved at another location
	 * (e.g. ../../images/image.gif, if the new CSS file is 1 folder deeper).
	 *
	 * @param ConverterInterface $converter Relative path converter
	 * @param string             $content   The CSS content to update relative urls for
	 *
	 * @return string
	 */
	protected function move(ConverterInterface $converter, $content)
	{
		/*
		 * Relative path references will usually be enclosed by url(). @import
		 * is an exception, where url() is not necessary around the path (but is
		 * allowed).
		 * This *could* be 1 regular expression, where both regular expressions
		 * in this array are on different sides of a |. But we're using named
		 * patterns in both regexes, the same name on both regexes. This is only
		 * possible with a (?J) modifier, but that only works after a fairly
		 * recent PCRE version. That's why I'm doing 2 separate regular
		 * expressions & combining the matches after executing of both.
		 */
		$relativeRegexes = array(
			// url(xxx)
			'/
            # open url()
            url\(

                \s*

                # open path enclosure
                (?P<quotes>["\'])?

                    # fetch path
                    (?P<path>.+?)

                # close path enclosure
                (?(quotes)(?P=quotes))

                \s*

            # close url()
            \)

            /ix',

			// @import "xxx"
			'/
            # import statement
            @import

            # whitespace
            \s+

                # we don\'t have to check for @import url(), because the
                # condition above will already catch these

                # open path enclosure
                (?P<quotes>["\'])

                    # fetch path
                    (?P<path>.+?)

                # close path enclosure
                (?P=quotes)

            /ix',
		);

		// find all relative urls in css
		$matches = array();
		foreach ($relativeRegexes as $relativeRegex) {
			if (preg_match_all($relativeRegex, $content, $regexMatches, PREG_SET_ORDER)) {
				$matches = array_merge($matches, $regexMatches);
			}
		}

		$search = array();
		$replace = array();

		// loop all urls
		foreach ($matches as $match) {
			// determine if it's a url() or an @import match
			$type = (strpos($match[0], '@import') === 0 ? 'import' : 'url');

			$url = $match['path'];
			if ($this->canImportByPath($url)) {
				// attempting to interpret GET-params makes no sense, so let's discard them for awhile
				$params = strrchr($url, '?');
				$url = $params ? substr($url, 0, -strlen($params)) : $url;

				// fix relative url
				$url = $converter->convert($url);

				// now that the path has been converted, re-apply GET-params
				$url .= $params;
			}

			/*
			 * Urls with control characters above 0x7e should be quoted.
			 * According to Mozilla's parser, whitespace is only allowed at the
			 * end of unquoted urls.
			 * Urls with `)` (as could happen with data: uris) should also be
			 * quoted to avoid being confused for the url() closing parentheses.
			 * And urls with a # have also been reported to cause issues.
			 * Urls with quotes inside should also remain escaped.
			 *
			 * @see https://developer.mozilla.org/nl/docs/Web/CSS/url#The_url()_functional_notation
			 * @see https://hg.mozilla.org/mozilla-central/rev/14abca4e7378
			 * @see https://github.com/matthiasmullie/minify/issues/193
			 */
			$url = trim($url);
			if (preg_match('/[\s\)\'"#\x{7f}-\x{9f}]/u', $url)) {
				$url = $match['quotes'] . $url . $match['quotes'];
			}

			// build replacement
			$search[] = $match[0];
			if ($type === 'url') {
				$replace[] = 'url('.$url.')';
			} elseif ($type === 'import') {
				$replace[] = '@import "'.$url.'"';
			}
		}

		// replace urls
		return str_replace($search, $replace, $content);
	}

	/**
	 * Check if file is small enough to be imported.
	 *
	 * @param string $path The path to the file
	 *
	 * @return bool
	 */
	protected function canImportBySize($path)
	{
		return ($size = @filesize($path)) && $size <= $this->maxImportSize * 1024;
	}

	/**
	 * Check if file a file can be imported, going by the path.
	 *
	 * @param string $path
	 *
	 * @return bool
	 */
	protected function canImportByPath($path)
	{
		return preg_match('/^(data:|https?:|\\/)/', $path) === 0;
	}

	/**
	 * Return a converter to update relative paths to be relative to the new
	 * destination.
	 *
	 * @param string $source
	 * @param string $target
	 *
	 * @return ConverterInterface
	 */
	protected function getPathConverter($source, $target)
	{
		return new Converter($source, $target);
	}
}