=== Flying Images by WP Speed Matters ===

Contributors: gijo
Donate link: https://www.buymeacoffee.com/gijovarghese
Tags: compress images, adaptive images, cdn, image compression,
Requires at least: 4.5
Tested up to: 5.7
Requires PHP: 5.6
Stable tag: 2.4.13
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The complete solution for image optimization

== Description ==

The complete solution for image optimization

## Quick Links

- Demo: [https://wpspeedmatters.com](https://wpspeedmatters.com)
- Join our [Facebook Group](https://www.facebook.com/groups/wpspeedmatters/), a community of WordPress speed enthusiasts
- [Buy me a coffee](https://www.buymeacoffee.com/gijovarghese)

## Features

- **Free Unlimited CDN** - Flying Images uses [Statically](https://statically.io/) to provide free CDN. Statically is powered by Cloudflare (premium network), BunnyCDN, Fastly and CDN77. No hidden charges or upsell.
- **On the fly Image Compression** - Compress images on the fly via CDN. You can also set the desired quality needed.
- **On the fly WebP conversion** - Convert and deliver images as Webp via CDN if the browser supports it.
- **Responsive/Adaptive Images** - Creates srcset to deliver resized images based on device. Supports external images too.
- **Native/JavaScript lazy loading** – Load images using the browser's native lazy loading if available (currently supported in Chrome) or via JavaScript. JavaScript only lazy load is also available. Also supports lazy loading inlined background images.
- **Tiny JavaScript** – Only 0.7KB, gzipped, minified.
- **Rewrites entire HTML** – Never miss an image from lazy loading or adding CDN, even the ones injected by gallery plugins.

== Installation ==

#### From within WordPress

1. Visit 'Plugins > Add New'
1. Search for 'Flying Images'
1. Activate Flying Images for WordPress from your Plugins page.
1. Visit 'Settings -> Flying Images' to configure

#### Manually

1. Upload the `flying-images` folder to the `/wp-content/plugins/` directory
1. Activate the Flying Images plugin through the 'Plugins' menu in WordPress
1. Visit 'Settings -> Flying Images' to configure

== Screenshots ==

1. Flying Images Settings

== Changelog ==

= 2.4.13 =

- Fix: FacetWP compatibility

= 2.4.11 =

- Detect webp images

= 2.4.10 =

- Disable inbuilt lazy loading introduced in WP 5.5
- Security updates

= 2.4.9 =

- [NEW] Support for WP Fastest Cache

= 2.4.8 =

- [BUGFIX] Add `f=auto` to only images from Statically CDN

= 2.4.7 =

- [BUGFIX] Add `f=auto` query string for WebP delivery

= 2.4.6 =

- [NEW] Support for W3 Total Cache

= 2.4.5 =

- [NEW] New thresholds for lazy loading margin (upto 3000px)

= 2.4.4 =

- [BUGFIX] Inject JavaScript for lazy loading only when it's enabled
- [BUGFIX] Prevent adding Statically if image URL is already from Statically CDN
- [UPDATE] Updated "Optimize More" tab for new plugins

= 2.4.3 =

- [NEW] New URL for purging Statically CDN (https://statically.io/purge)

= 2.4.2 =

- [BUGFIX] Compatibility with Brizy page builder

= 2.4.1 =

- [NEW] More keywords to default lazy loading exclude list
- [BUGFIX] Compatibility with exisiting lazy loading plugins

= 2.4.0 =

- [NEW] Skip class "skip-lazy" and data attribute "data-skip-lazy" by default

= 2.3.10 =

- [BUGFIX] Revert bug in last update

= 2.3.9 =

- [BUGFIX] HTML detection failed when first line is empty

= 2.3.8 =

- [BUGFIX] Exclude base64 images from adding compression

= 2.3.7 =

- [BUGFIX] Warning while adding CDN to background style images

= 2.3.6 =

- [REMOVED] Elementor background lazy loading due to buggy behaviour

= 2.3.5 =

- [BUGFIX] Unable to save posts after the last update

= 2.3.4 =

- [BUGFIX] Improved HTML detection in parser

= 2.3.3 =

- [BUGFIX] Responsive images - Restrict srcset to max width of image
- [BUGFIX] Responsive images - Don't generate responsive images if width is unknown
- [ADDED] "no-lazy" to default lazy load exclude list

= 2.3.2 =

- [BUGFIX] Improved responsive images
- [REMOVED] Experimental container width for responsive images

= 2.3.1 =

- [BUGFIX] Background image URLs showing empty when compression is enabled

= 2.3.0 =

- [NEW] Restrict image width to container instead of device width (experimental)
- [BUGFIX] Repect "background:" or "background-image"
- [BUGFIX] Better compatibility with gravatar and external images
- [BUGFIX] Improved responsive images

= 2.2.6 =

- [BUGFIX] REVERT BACK! Changing 'background' to 'background-image' for compatibility
- [BUGFIX] CDN exclude now support background images inside style tags

= 2.2.5 =

- [BUGFIX] Changing 'background-image' to 'background' for compatibility

= 2.2.4 =

- [BUGFIX] Remove JS dependency for "Native only" lazy loading

= 2.2.3 =

- [BUGFIX] Lazy load Elementor background images
- [BUGFIX] Apply CDN to background images inside <style> tags

= 2.2.2 =

- [BUGFIX] Warning when lazy load or cdn exclude list is empty

= 2.2.1 =

- [BUGFIX] CDN not working on relative paths
- [BUGFIX] Removing quotes in background image URLs for compatibility with Oxygen

= 2.2.0 =

- [NEW] Add CDN to WooCommerce dynamically injected thumbnails
- [NEW] Purge CDN link
- [BUGFIX] Fixed issue with double backslash
- [UPDATE] Removed gravatar.com from default CDN exclude list

= 2.1.1 =

- [UPDATE] Cross origin to Preconnect

= 2.1.0 =

- [NEW] DNS-Prefetch and Preconnect to Statically CDN
- [BUGFIX] Removes picture tags created by other plugins (like ShoprtPixel, WebP Express etc) for compatibility
- [BUGFIX] Support relative URLs

= 2.0.0 =

- Major update!
- [New] Free CDN powered by [Statically](https://statically.io)
- [New] On the fly WebP
- [New] On the fly image compression
- [New] Lazy load backgroud images
- [Updated] Responsive/Adaptive images
- [Updated] Lazy loading method defaults to JavaScript only
- [Updated] Bottom margin of lazy loading defaults to 500px
- [Updated] Bottom margin now supports 0px to 1000px
- [Updated] Renamed from "Flying Images" to "Flying Images by WP Speed Matters"
- [New] Added 'lazyloaded' class to images when lazy loaded (only for JS based lazy loading)
- [New] FAQ tab
- [New] Support Us tab
- [New] Optimize More tab

= 1.3.3 =

- Bug fix - Change `window.onload` to `DOMContentLoaded`

= 1.3.2 =

- Bring back option to set bottom margin
- New lazy load method - JavaScript only

= 1.3.1 =

- Improve HTML and XML detection (also fixes issues with Oxygen builder)

= 1.3.0 =

- New feature: Add missing responsive images (srcset)
- Bug fix: Warning on countable function

= 1.2.9 =

- Bug fix - Prevent videos inside source tag from loading

= 1.2.8 =

- Bug fix in last update

= 1.2.7 =

- Bug fix for Jetpack integration and empty pages

= 1.2.6 =

- Removed styling for JS disabled browsers, for compatibility with Swift Performance

= 1.2.5 =

- Bug fix - Images loading multiple times when cache is disabled in Chrome

= 1.2.4 =

- Prevent conflicts with NextGen gallery plugin

= 1.2.3 =

- Added noscript tag for images (load images when JavaScript is disabled)

= 1.2.2 =

- Exclude keywords now looks from parent node (useful if your images doesn't have a class or anything unique)
- Bug fix for picture tag (webp)

= 1.2.1 =

- Improved lazy loading for dynamic content
- Prevent parser from removing white spaces
- Performance improvements

= 1.2.0 =

- Lazy load images in picture tag (also fixes issues for webp)
- Automatic bottom margin height (removed option for custom margin)
- Performance improvements

= 1.1.2 =

- Bug fix - Lazy load images in dynamically injected content

= 1.1.1 =

- Support for Internet Explorer

= 1.1.0 =

- Rewrote HTML parser (fix breaking sites)
- Exclude images from lazy loading

= 1.0.1 =

- Renamed plugin from **Nazy Load** to **Flying Images**
- Typo fixes

= 1.0.0 =

- Initial release
