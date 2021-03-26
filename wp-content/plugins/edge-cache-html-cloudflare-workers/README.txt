=== Edge Cache HTML via Cloudflare Workers ===
Contributors: s1lviu, patrickmeenan, jwineman, furkan811, icyapril, manatarms
Donate link: https://www.paypal.me/silviustroe
Tags: cloudflare workers, cloudflare cache, caching, workers
Requires at least: 3.3.1
Tested up to: 5.3.2
Stable tag: 1.0.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Caching pages on Cloudflare CDN with the power of Workers, auto-purging when content changes.

== Description ==

Combine the [Cloudflare Page Cache Plugin](https://github.com/cloudflare/worker-examples/tree/master/examples/edge-cache-html/WordPress%20Plugin/cloudflare-page-cache) in order to offer edge-cached HTML content on the Cloudflare CDN for non-authenticated users with the power of Cloudflare Workers.
In this manner, the static content will be delivered from the Cloudflare large network and consists in big improvements, including TTFB and loading time.
This plugin has the advantage of automation of Worker creation via APIs.

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload `cloudflare-edge-cache` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= What I need to can use the plugin? =

You need a Cloudflare account and access to API Key.