=== Asset CleanUp: Page Speed Booster ===
Contributors: gabelivan
Tags: minify css, minify javascript, defer css javascript, page speed, dequeue, performance
Donate link: https://www.gabelivan.com/items/wp-asset-cleanup-pro/?utm_source=wp_org_lite&utm_medium=donate
Requires at least: 4.5
Tested up to: 5.5.1
Stable tag: 1.3.7.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html

Make your website load FASTER by preventing specific scripts (.JS) & styles (.CSS) from loading on pages/posts and home page. Works best in addition to a cache plugin!

== Description ==
Don't just minify & combine CSS/JavaScript files ending up with large, bloated and slow loading pages: **Strip the "fat" first and get a faster website** :)

Faster page load = Happier Visitors = More Conversions = More Revenue

There are often times when you are using a theme and a number of plugins which are enabled and run on the same page. However, you don't need to use all of them and to improve the speed of your website and make the HTML source code cleaner (convenient for debugging purposes), it's better to prevent those styles and scripts from loading.

For instance, you might use a plugin that generates contact forms and it loads its assets (.CSS and .JS files) in every page of your website instead of doing it only in the /contact page (if that's the only place where you need it).

"Asset CleanUp" scans your page and detects all the assets that are loaded. All you have to do when editing a page/post is just to select the CSS/JS that are not necessary to load, this way reducing the bloat.

The plugin works best in combination with a cache plugin (e.g. WP Rocket, WP Fastest Cache, W3 Total Cache), a hosting company that offers packages with server-level caching available (e.g. WP Engine, Kinsta) or a service like Cloudflare that has page caching enabled.

= Main plugin's benefits include =
* Decreases the number of HTTP requests loaded and eliminate render-blocking resources (important for faster page load) by unloading useless CSS/JS
* Preload CSS/JS, Local Fonts & Google Fonts files to instruct the browser to download the chosen assets as soon as possible
* Minify CSS files (including inline code within STYLE tags)
* Minify JavaScript files (including inline code within SCRIPT tags)
* Combine remaining loaded CSS & JavaScript files
* Inline CSS Files (automatically & by specifying the path to the stylesheets)
* Defer combined JavaScript files by applying "defer" attribute to the SCRIPT tags
* Site-wide removal for Emojis, Dashicons for guest users and Comment Reply if they are not used
* Reduces the HTML code of the actual page (that's even better if GZIP compression is enabled)
* Makes source code easier to scan in case you're a developer and want to search for something
* Remove possible conflicts between plugins/theme (e.g. 2 JavaScript files that are loading from different plugins and they interfere one with another)
* Better performance score if you test your URL on websites such as GTmetrix, PageSpeed Insights, Pingdom Website Speed Test
* Google will love your website more as it would be faster and fast page load is nowadays a factor in search ranking
* Your server access log files (e.g the Apache ones) will be easier to scan and would take less space on your server

= Google Fonts Optimization / Removal =
* Combine all Google Font requests into fewer (usually one) requests, saving one round trip to the server for each additional font requested
* Choose between three methods of delivery: Render-blocking, Asynchronous via Web Font Loader (webfont.js) or Asynchronous by preloading the CSS stylesheet
* Option to preload Google Font Files from fonts.gstatic.com (e.g. ending in .woff2)
* Apply "font-display" CSS property to all loaded Google Font requests
* Enable preconnect resource hint for fonts.gstatic.com in case you use Google Fonts; don't let the browser wait until it fetches the CSS for loading the font files before it begins DNS/TCP/TLS
* Remove all Google Font requests including link/font preloads, @import/@font-face from CSS files & STYLE tags, resource hints

= Local Fonts Optimization =
* Preload local font files (ending in .woff, .woff2, .ttf, etc.)
* Apply "font-display" CSS property value to LINK / STYLE tags (Pro version)

= Remove useless links, meta tags and HTML comments within the HEAD and BODY (footer) tags of the website =
* Really Simple Discovery (RSD) link tag
* Windows Live Writer link tag
* REST API link tag
* Pages/Posts Shortlink tag
* Post's Relational Links tag
* WordPress version meta tag (also good for security reasons)
* All "generator" meta tags (also good for security reasons)
* RSS Feed Link Tags (usually they are not needed if your website is not used for blogging purposes)
* oEmbeds, if you do not need to embed videos (e.g. YouTube), tweets and audios
* Valid HTML Comments (exceptions from stripping can be added and Internet Explorer conditional comments are preserved)

Each option can be turned on/off depending on your needs. Instructions about each of them are given in the plugin's settings page.

= Disable partially or completely XML-RPC protocol =
This is an API service used by WordPress for 3rd party applications, such as mobile apps, communication between blogs and plugins such as Jetpack. If you use or are planning to use a remote system to post content to your website, you can keep this feature enabled (which it is by default). Many users do not use this function at all and if you’re one of them, you can disable it.

Plugin works with WordPress Multisite Network enabled!

> <strong>Asset CleanUp Pro</strong><br />
> This plugin is the lite version of Asset CleanUp Pro that comes with more benefits including managing assets (CSS & JS files) on all WordPress pages, unloading plugins site-wide or via Regex(es), apply "async" and "defer" attributes on loaded JavaScript files which would boost the speed score even higher, move the loading location of CSS/JS files (from HEAD to BODY to reduce render-blocking or vice-versa if you need specific files to trigger earlier) and premium support. <a href="https://www.gabelivan.com/items/wp-asset-cleanup-pro/?utm_source=wp_org_lite&utm_medium=inside_quote">Click here to purchase Asset CleanUp Pro!</a>

= NOTES =
People that have tested the plugin are so far happy with it and I want to keep a good reputation for it. In case something is not working for you or have any suggestions, please write to me on the forum and I will be happy to assist you. **BEFORE rating this plugin**, please check the following post http://chrislema.com/theres-wrong-way-give-plugin-feedback-wordpress-org/ and then use your common sense when writing the feedback :)

= GO PRO =
* Unload CSS/JS files on all WordPress pages including Categories, Tags, Custom Taxonomy (e.g. WooCommerce product category), 404 Not Found, Date & Author Archives, Search Results)
* Unload targeted plugins site-wide or via Regex(es), thus resulting in not only their CSS/JS stripped, but also their backend PHP code, HTML output printed via wp_head() or wp_footer() action hooks, cookies that are set, etc.)
* Defer CSS by appending it to the BODY to load it asynchronously (Render blocking CSS delays a web page from being visible in a timely manner)
* Move JavaScript files from HEAD to BODY and vice-versa (CSS files moved to the BODY are automatically deferred)
* Defer JavaScript loaded files (by applying "defer" attribute to any enqueued JS file)
* Async JavaScript loaded files (by applying "async" attribute to any enqueued JS file)
* Inline JavaScript files (automatically & by specifying the path to the stylesheets)
* Apply "font-display:" CSS property to @font-face from existing local files to improve the PageSpeed score for "Ensure text remains visible during webfont load"
* Premium support and updates within the Dashboard

Give Asset CleanUp a try! If you want to unlock all features, you can <a href="https://www.gabelivan.com/items/wp-asset-cleanup-pro/?utm_source=wp_org_lite&utm_medium=go_pro">Upgrade to the Pro version</a>.

== Installation ==
* If you're planning to use the Lite version of the plugin:

1. Go to "Plugins" -> "Add New" -> "Upload Plugin" and attach the downloaded ZIP archive from the plugin's page or use the "Search plugins..." form on the right side and look for "asset cleanup"
2. Install and activate the plugin (if server's PHP version is below 5.6, it will show you an error and activation will not be made).
3. Edit any Page / Post / Custom Post Type and you will see a meta box called "Asset CleanUp" which will load the list of all the loaded .CSS and .JS files. Alternatively, you will be able to manage the assets list in the front-end view as well (at the bottom of the pages) if you've enabled "Manage in the Front-end?" in plugin's settings page.
4. To unload the assets for the home page, go to "Asset CleanUp" menu on the left panel of the Dashboard and click on "CSS & JS MANAGER" ("Homepage" is the default tab).

* I have purchased the Pro version. How to do the upgrade?
1. Go to "Plugins" -> "Add New" -> "Upload Plugin"; You will notice an upload form and an "Install Now" submit button. Download the ZIP file you received in your purchase email receipt (example: wp-asset-clean-up-pro-v1.0.8.7.zip), attach it to the form and install the new upgraded plugin.
2. Finally, click "Activate Plugin"!
3. Once the plugin is activated, make sure to grab the license key from the purchase email receipt and activate it in the "License" (plugin's menu) page in order to be eligible for plugin updates from the Dashboard. That's it :)

== Frequently Asked Questions ==
= What PHP version is required for this plugin to work? =

5.6+ - I strongly recommend you to use PHP 7+, if you're website is fully compatible with it, as it's much faster than any PHP 5.* and it will make a big difference for your website's backend speed.

= If my website is hosted with WordPress.com (not self-hosted), can I still use Asset CleanUp to get a faster page load? =

Although Asset CleanUp is guaranteed to work with self-hosted (WordPress.org) websites, it can be used on WordPress.com ones too, but with limitations. Features such as minify/combine CSS/JS files or combine Google Fonts (if any loaded) aren't guaranteed to work (basically, anything requiring writing files to the caching directory). However, if you want to unload CSS/JS files, which is the key feature of the plugin, then this has always been tested & working for WordPress.com websites. Note that at the time of writing this (June 21, 2020), you can only install plugins on WordPress.com websites if you sign up for their Business plan: https://wordpress.com/pricing/

= How do I know if my website’s page loading speed is slow and needs improvement? =

There are various ways to check the speed of a website and this is in relation to the following: front-end (the part of the website visible to your visitors), back-end (PHP code, server-side optimization), hosting company, CDN (Content Delivery Network) setup, files loaded (making sure CSS, JS, Images, Fonts, and other elements are properly optimized when processed by the visitor’s browser).

Check out <a href="https://gtmetrix.com/" target="_blank">https://gtmetrix.com/</a> to do an analysis of your website and see the overall score your website gets in PageSpeed and YSlow.

= What is an asset and which are the assets this plugin is dealing with? =

Web assets are elements such as CSS, JavaScript, Fonts, and image files that make the front-end which is the look and functionality of your website that is processed by the browser you are using (e.g. Google Chrome. Mozilla Firefox, Safari, Internet Explorer, Opera etc.). Asset CleanUp deals with CSS and JavaScript assets which are enqueued in WordPress by your theme and other plugins.

= Is this plugin a caching one?

No, Asset CleanUp does not do any page caching. It just helps you unload .css and .js that you choose as not needed from specific pages (or all pages). This, combined with an existing caching plugin, will make your website pages load faster and get a higher score in speed checking tools such as GTMetrix (Google PageSpeed and YSlow).

= Has this plugin been tested with other caching plugins?

Yes, this plugin was tested with W3 Total Cache, WP Rocket and WP Fastest Caching and should work with any caching plugin as any page should be cached only after the page (HTML Source) was rendered and all the enqueueing / dequeueing was already completed (from either the plugins or the theme). Asset CleanUp comes with minify/combine files feature. Please do not also enable the same feature on a caching plugin. Example: If you already minified CSS/JS files with Asset CleanUp, do not enable Minify CSS/JS in WP Rocket or other caching plugins.

= I've noticed scripts and styles that are loaded on the page, but they do not show in the "Asset CleanUp" list when editing the page or no assets are showing at all. Why is that? =

There are a few known reasons why you might see different or no assets loading for management:

- Those assets weren't loaded properly into WordPress by the theme/plugin author as they were likely hardcoded and not enqueued the WordPress way. Here's a tutorial that will help you understand better the enqueuing process: http://www.wpbeginner.com/wp-tutorials/how-to-properly-add-javascripts-and-styles-in-wordpress/

- You're using a cache plugin that is caching pages even when you're logged in which is something I don't recommend as you might have conflicts with other plugins as well (e.g. in W3 Total Cache, you can enable/disable this) or that plugin is caching pages even when a POST request is made to them (which is not a good practice as there are many situations in which a page should not be cached). That could happen if you're using "WP Remote POST" method (from version 1.2.4.4) of retrieving the assets in the Dashboard.

- You might have other functions or plugins (e.g. Plugin Organizer) that are loading prior to Asset CleanUp. Note that Plugin Organizer has a file that is in “mu-plugins” which will load prior to any plugin you have in “plugins”, thus, if you have disabled specific plugins through “Plugin Organizer” in some pages, their assets will obviously not show in the assets list as they are not loaded at all in the first place.

If none of these apply to you and you just don't see assets that should definitely show there, please open a support ticket.

= Why are the unload settings that I've applied for CSS/JS files not taking any effect when I visit the page? =

Whenever you unload certain CSS/JS files, you expect to either see an immediate increase in the Google PageSpeed Insights / GTMetrix score or not loaded when you test the page in Incognito (visiting it as a guest, while you’re not logged-in). However, this doesn’t always happen. Pleae check this post to find out the possible reasons: https://assetcleanup.com/docs/changes-applied-not-taking-effect/

= How can I access all the features? =

You can get access to more features, priority support and automatic updates by <a href="https://www.gabelivan.com/items/wp-asset-cleanup-pro/?utm_source=wp_org_lite&utm_medium=inside_faq">Upgrading to the Pro version</a>.

= jQuery and jQuery Migrate are often loading on pages/post. Are they always needed? =

The known jQuery library is being used by many themes and plugins so it's recommended to keep it on. jQuery Migrate was created to simplify the transition from older versions of jQuery. It restores deprecated features and behaviors so that older code will still run properly on jQuery 1.9 and later.

However, there are cases when you might not need jQuery at all on a page. If that's the case, feel free to unload it. Make sure you properly test the page afterward, including testing it for mobile view.

= Is the plugin working with WordPress Multisite Network? =

Yes, the plugin has been tested for WordPress Multisite and all its settings are applied correctly to any of the sites that you will be updating.

= When editing a post/page, I can see the message "We're getting the loaded scripts and styles for this page. Please wait...", but nothing loads! Why? =

The plugin makes AJAX calls to retrieve the data from the front-end page with 100% accuracy. Possible reasons why nothing is shown despite the wait might be:

- Your internet connection cut off after you loaded the edit post/post (before the AJAX calls were triggered). Make sure to check that and refresh the page if it's back on - it happened to me a few times

- There could be a conflict between plugins or your theme and something is interfering with the script that is retrieving the assets

- You are loading the WordPress Dashboard through HTTPS, but you are forcing the front-end to load via HTTP. Although Asset CleanUp auto-corrects the retrieval URL (e.g. if you're logged in the Dashboard securely via HTTPS, it will attempt to fetch the assets through HTTPS too), there could be cases where another plugin or .htaccess forces an HTTP connection only for the public view. Due to Same Origin Policy (read more here: https://developer.mozilla.org/En/Same_origin_policy_for_JavaScript), you can't make plain HTTP AJAX calls from HTTPS connections. If that's the case, try to enable "WP Remote POST" as a retrieval method in the plugin's settings if you want to manage the assets in the Dashboard.

- You're using plugins such as Wordfence that block the AJAX request. At this time, if that's the case, it's best to enable managing assets in the front-end view (Settings).

In this case, it's advisable to enable "Manage in the Front-end?" in "Settings" of "Asset CleanUp", thus making the list to show at the bottom of the posts, pages, and front-page only for the logged in users with admin privileges.

Although I've written the code to ensure maximum compatibility, there are factors which are not up to the quality of the plugin that could interfere with it.
In case the assets are not loading for you, please write to me on the forum and I will be happy to assist you!

= I do not know or I'm not sure which assets to unload on my pages. What should I do? =

With the recently released "Test Mode" feature, you can safely unload assets on your web pages without affecting the pages' functionality for the regular visitors. It will unload CSS & JavaScript files that you selected ONLY for yourself (logged-in administrator). That's recommended in case you have any doubts about whether you should applying a specific setting or unload any asset. Once you've been through the trial and error and your website is lighter, you can deactivate "Test Mode", clear cache (if using a caching plugin) and the changes will apply for everyone. Then, test the page speed score of your website :)

== Screenshots ==
1. When editing a page, a meta box will load with the list of loaded CSS & JS files from the active theme & plugins
2. Plugin Usage Preferences (From "Settings")
3. Combine CSS & JS files option
4. Homepage CSS & JS Management (List sorted by location)

== Changelog ==
= 1.3.7.0 =
* Made sure a directory exists before attempting to delete it (for old directories) to avoid any error reports (harmless, but annoying) from plugins such as "Fatal Error Notify Pro"
* Updated notification related to HTTP/2 from Combine CSS/JS tabs within "Settings"
* If the total files from the caching directory generated by the combined CSS/JS files occupy over 1 GB in disk space, deactivate automatically the appending of the inline CSS/JS code associated with the tags to the generated combined CSS/JS files as that's usually the culprit for having so many redundant files in the caching directory, leading to unnecessary disk space
* Older caching files are by default set to be cleared after 4 days (the new default value) instead of 7
* Updated "Help" page
* Show more information about the caching directory in "Tools" -> "Storage Info" (each directory with CSS/JS files is shown along with the total size of the assets within it)
* WP Rocket 3.7+ compatibility fix: "Minify HTML" is removed (read more: https://github.com/wp-media/wp-rocket/issues/2682), thus, make sure this gets verified (for compatibility reasons) as well in Asset CleanUp Pro
* Shorten the file name of the combined CSS/JS to avoid possible duplicates
* Fix possible errors (400 Bad Request) when AJAX calls are made by Asset CleanUp Pro
* Check if Cloudflare is used and notify the user about whether it's needed to enable "CDN: Rewrite assets URLs" (read more: https://assetcleanup.com/docs/?p=957)

= 1.3.6.9 =
* Improvement: Save resources and do not check anything for optimization when the feed URL (e.g. /feed/) is loaded (the plugin should be inactive for these kinds of requests)
* Improvement: Do not trigger the plugin when WooCommerce makes AJAX calls (no point in using extra resources from Asset CleanUp)
* Improvement: When Google Fonts are marked for removal, nullify other related settings, leading to the usage of fewer resources
* The strings "/cart/" and "/checkout/" are added to the exclusion list where Asset CleanUp Pro is not triggered if the pattern is matched (read more: https://assetcleanup.com/docs/?p=488); These kinds of pages usually do not need optimization and if the admin decides to do some, he/she can remove the exclusion
* Fix (writing files to cache directory): If the handle name contained forward-slash (/), make sure that the final file name gets sanitized (including slash removal) to avoid errors related to file_put_contents() such as trying to write to directories that are non-existent
* Fix (unnecessary cached files): The plugin was caching CSS/JS files that did not need to be cached (e.g. already minified JS), leading to unnecessary extra disk space
* "WP-Optimize" minify is not triggering anymore when /?wpacu_clean_load is used for debugging purposes (viewing all files loading from their original location)
* Do not strip inline CSS/JS associated with the handle if the original file is empty as there's a high chance the inline code is needed

= 1.3.6.8 =
* Make the plugin's user aware about jQuery Migrate not loading starting from WordPress 5.5 (a notice is showing in "Settings" -> "Site-Wide Common Unloads" if the WP version >= 5.5)
* Add alerts for WooCommerce assets when the user is about to unload them to make sure he/she is aware of the consequences (e.g. "js-cookie", "wc-cart-fragments")
* Oxygen plugin compatibility: Make sure the page loads fine when "Manage in the front-end view" is enabled and the admin is logged-in (e.g. ob_flush() is used to print missing content)
* Added action hooks before ("wpacu_clear_cache_before") & after ("wpacu_clear_cache_after") the plugin's CSS/JS caching is cleared
* Do not deactivate the plugin automatically on Dashboard view if the PHP version is below 5.6 and when the plugin is activated, prevent its activation when the PHP version is below 5.6 printing an error message
* Yoast SEO Compatibility Fix: Prevent the plugin from minifying SCRIPT tags if the type is different than "text/javascript", avoiding errors with plugins such as Yoast SEO (type: application/ld+json)
* WordPress 5.5 & "Enable jQuery Migrate Helper" Fix - /assets/script.min.js: jQuery.fn.load() is deprecated
* When the CSS/JS list is fetched using the "Direct" way of fetching the assets ("Manage in the Dashboard"), there are two calls made; Now the progress is shown for each of the calls for easier debugging in case the assets' list is not retrieved successfully
* Improvement: Do not defer the plugin's own script file as sometimes its functions do not work (e.g. if there are JS errors from other plugins); It's better to have it loaded as render-blocking (small file anyway), as soon as possible
* Improvement: Do not leave extra space in the LINK & SCRIPT tags (it makes things easier when debugging the HTML source that might have been altered by the plugin)
* Fix: Avoid triggering the plugin if the request is an API one starting with "/wp-json/wc/" (excluding the site's base URL), WooCommerce related (REST requests)
* Fix: PHP Notice: Array to string conversion (CombineCss.php on line 503)
* Fix: PHP Notice: Undefined index: is_frontend_view (view-by-location.php on line 311)
* Fix: Fix: When assets are fetched, the list of CSS/JS wasn't showing up (AJAX call error) if the page URL that is called (from which the assets are fetched) is loaded with HTTP protocol while the Dashboard (the URL from which the AJAX call is made) is accessed via the HTTPS protocol - Error: "This request has been blocked; the content must be served over HTTPS."
* Fix: "PHP Warning: Use of undefined constant CURL_HTTP_VERSION_2_0" (triggered only for the admin to check if the server supports the HTTP/2 protocol); Show the verify link for HTTP/2 protocol if the automatic detection is not working
* Fix: "PHP Notice: Undefined variable: pluginListContracted in (...)/templates/meta-box-loaded-assets/view-by-location.php"
* Security Fix: Sanitize values from BulkChanges.php to prevent the execution of arbitrary code (e.g. JavaScript code)

= 1.3.6.7 =
* Fix: CombineJs.php - PHP Notice: Array to string conversion (it happened when there were more than one inline JS code associated with a handle)
* Fix: CombineJs.php - Prevent PHP notice errors from showing up
* Security Fix: Sanitize values from BulkChanges.php to prevent execution of arbitrary code (e.g. JavaScript code)
* Security Fix: Sanitize value from $_REQUEST['wpacu_selected_sub_tab_area'] to prevent execution of arbitrary code (e.g. JavaScript code)
* Security Fix: Sanitize $postId (make sure it's only an integer) from the "duplicate_post_meta_keys_filter" filter to avoid any SQL injection attack

= 1.3.6.6 =
* The caching of a file is re-built based on the filemtime() value as developers often forget to update the value of the "ver" (/?ver=) after updating a CSS/JS file's content
* When listing the loaded stylesheets (LINK tags), make sure to print the "media" attribute if it's different than "all" so the admin will be aware if that particular CSS is meant for mobile or other devices (e.g. to save time from going through the HTML source code and check it out there)
* Files loaded from "/wp-content/bs-booster-cache/" are not minified/combined (as they are already minified) to avoid getting a large caching directory (often having lots of GB)
* Prevent certain JavaScript code containing random strings such as nonces (e.g. CDATA one) from being added to the combined JS files to avoid the plugin generating lots of JS combined files that would increase the total disk space by writing to the caching directory
* New ways to sort the list of the CSS/JS files that are managed: "Grouped by their size (sorted in descending order)", "Grouped by having at least one rule & no rules", "All enqueues in one list"
* At least PHP 5.6 is now required (anything below that and the plugin won't activate). Official support for PHP 5.6 ended on December 31st, 2018. Unless you really have to use it (e.g. old code that won't work with never PHP version), it's strongly recommended that you update to PHP 7+ as it's much faster & uses less resources than older PHP versions, not to mention the improvement in terms of security vulnerabilities & bugs - Read more about unsupported PHP branches: https://www.php.net/eol.php
* Fix: "Combine CSS" was not working, unless "Combine JS" was also enabled

= 1.3.6.5 =
* Improved the function that verifies if a CSS/JS content is already minified (to avoid minification that would use extra resources); Set limitations to the number of entries in the database to avoid the number getting too large
* Caching dynamic assets (CSS/JS) is no longer enabled by default as it might cause some issues on certain themes/plugins, thus it has to be enabled by the admin only if necessary
* Added "Read more" links for some of the handles that have special documentation written about them (e.g. "How to unload Swiper in Elementor" or "How to check if Gutenberg Blocks CSS file is needed or not")

= 1.3.6.4 =
* New Option: "When list is grouped by location, keep the assets from each of the plugins in the following state": 'Contracted' mode is now available and if you have many plugins, the list can be contracted when the CSS/JS manager list is loaded
* New Option: "File Size:" available for each CSS/JS, either local or external
* Combine CSS/JS "Apply combination only for logged-in administrator (for debugging purposes)" is no longer available and has been replaced with two options: "Apply it only for guest visitors (default)" & "Apply it for all visitors (not recommended)" (if chosen, it enables the combine CSS/JS option for logged-in users which is usually not ideal)
* "Overview" new options: 1) Added option to remove all load exceptions for a handle in "Overview" page when the load exceptions are not tied to any bulk unload rule; 2) Clear redundant unload rules if the site-wide rule is already applied
* "Autoptimize" compatibility: Make sure the HTML source code is always altered before "Optimize HTML Code?" is triggered in Autoptimize

= 1.3.6.3 =
* New Feature in the CSS/JS Manager: The handle rows can be contracted/expanded (their status is saved when the form is submitted); This is useful to make the whole area smaller (less scrolling) as there will likely be CSS/JS files that you know you will never edit for a long time (if ever) and it's better to have them contracted
* New Option in "Settings" - "Plugin Usage Preferences": Hide Dashboard's "Asset CleanUp Pro" menu from the left sidebar in case you don't use the plugin too often or wish to have a cleaner sidebar (read more: https://assetcleanup.com/docs/?p=584)
* Compatibility with Ronneby Theme: Alter the style/script tag later (e.g. by appending plugin markers) after plugins such as "Ronneby Core" alter it (in this case it prevents the URLs from the LINK tags to be stripped)
* When listing dependencies in the CSS JS managing list (e.g. the "children" of a "parent"), show the unloaded ones in the red font; Dependency handles are linked as anchors for easier navigation between them
* "WP Rocket" compatibility: Make sure HTML changes made by Asset CleanUp Pro are always applied (via "rocket_buffer" filter hook) before WP Rocket saves the HTML content to the cached file
* Fix: After updating the the CSS/JS manager on any page, the page preloads for the guest visitors; Make sure the response from wp_remote_get() is always checked to avoid PHP errors logged to error_log (even though the errors are harmless for the front-end view, the error log files can get too big)
* Fix (unstyled CSS/JS management area in front-end view): Make sure the plugin's own style is properly loaded asynchronously in Firefox in any situation

= 1.3.6.2 =
* Once a page is updated, the plugin preloads that page for both the admin and the guest visitor, making sure any new changes would take effect, saving the admin's time and making sure any first visitor coming to that page will access it faster (not having to wait for the caching to re-built)
* If the attribute "data-wpacu-skip" is applied to any CSS/JS, then no alteration (e.g. no minify and no addition to any combine list) will be applied to that file (apart from the actual unload or attributes such as async/defer)
* 'Remove All "generator" meta tags?' improvement: Higher accuracy in stripping META tag generators if the option is enabled in case some of their attributes have no quotes around them (rare cases)
* If 'Remove "REST API" link tag?' is enabled, the /wp-json/ reference is also removed from the "Response headers" when accessing the page via remove_action()
* Compatibility with extra page builders: "X" & "PRO" themes (Theme.co), "WP Page Builder" & "Page Builder: Live Composer" plugins: whenever their editor is ON, no unloads or any other changes to the HTML source (including minification) are performed to make sure the editor is loading its files and works smoothly
* Compatibility with "Redis Object Cache" plugin: The global variable $wp_object_cache from the WordPress core is no longer used and it's replaced with a custom solution
* Compatibility with "404page – your smart custom 404 error page" plugin and similar plugins that are making pages as 404 customizable ones
* For debugging purposes, the admin can use /?wpacu_no_cache to view how the website would load without the CSS/JS cache applied (the files will still be referenced from the caching directory but they will be dynamically generated instead)
* Sometimes, large inline STYLE/SCRIPT tags' content has to be minified; if it's between 40KB and 500 KB it will be cached; any tags' content over 500KB will not be minified as it would use too many resources; it's advisable to put very large tag content into a .js external file as it would affect the TTFB (time to the first byte) when the page is loaded
* Any inline CSS/JS associated with a handle (generated via wp_add_inline_style() and wp_add_inline_script() respectively) are automatically added to the combined file
* Combined CSS/JS files are all stored in /wp-content/cache/asset-cleanup/("css" or "js")/ to avoid duplicated files that used to be stored in "logged-in" directory which is no longer created; This reduces the total disk space especially when the same CSS/JS is created (sometimes these files are quite large) for both guests & logged-in users
* If "Combine loaded CSS (Stylesheets) into fewer files" is enabled, the LINK tags that are preloaded (at least two of them) will also be combined, thus reducing the number of HTTP requests
* The method loadHTML() from DOMDocument is processing tags faster as the initial HTML source passed to it as a parameter goes through several filters, making it much smaller which makes a different in page speed when it comes to large HTML sources
* Prevent certain DOMDocument calls (which can be slow on large HTML documents) when they are not necessary (e.g. when preloading CSS stylesheets and the RegEx which is faster can do the same task with the same accuracy)
* Strip LINK tags that are pointing to empty content (including any inline code associated with the enqueued style added via wp_add_inline_style() function) if "CSS Files Minification" is enabled, making sure any empty tags are also stripped when "Inline CSS Files" is enabled, this saving HTTP requests and having less DOM elements
* In some cases, the PHP function strtr() has proven to be faster than str_replace() to make replacements, thus it has been applied to some methods that are dealing with the alteration of the HTML source
* Notify the admin that unloading 'jquery-migrate' won't unload it's "child" as well, 'jquery' (as it's a special case)
* Fix: In rare cases, URLs to the assets are starting with ../ (it's not the best practice as this would only work depending on the page's URL structure); Make sure the file's size is calculated correctly and the right URL for the file is checked in the background to determine if it returns a 200 OK response or not
* Fix: Store the assets info (which are shown only within the Dashboard for reference purposes) with the relative location (UR) to the asset, in case the data is later imported from a Staging to Live environment, it won't show any Staging URLs on the Live website on pages such as "Overview", thus avoiding any confusion the admin might have
* Fix: Make sure the time dequeueing CSS/JS is calculated correctly
* Fix: If "Asynchronous via Web Font Loader (webfont.js)" was chosen for "Combine Multiple Requests Into Fewer Ones", the font weights weren't added to the final generated SCRIPT tag
* Fix: Make sure when the handle information is saved, there are no PHP notice errors if the 'src' index is missing as some handles do not have an "src"

= 1.3.6.1 =
* Increased the speed of unloading (dequeueing) styles & scripts by ~40ms (caching is used to avoid calling the same PHP code during several action hooks)
* Higher accuracy in stripping 'before' and 'after' associated inline SCRIPT after adding the content to the JS combined file
* When combining JS content, make sure to extract the right relative path to make it combinable if a local file is loaded and it starts with // in the "src" attribute
* Show confirmation message when unloading specific files that are very likely needed such as jQuery, Backbone & Underscore libraries
* Alert the admin when there are unload rules from inactive plugins on pages such as "Bulk Changes" & "Overview"
* Keep Dashicons loaded if the toolbar (top admin bar) is shown
* Prevent front-end optimisation code from triggering while the CSS/JS is fetched, thus saving resources and improving the speed of retrieving the assets
* Optimised the plugin to use less calls to MinifyCss & MinifyJs for hardcoded assets reducing considerably the resources used (sometimes timeout errors are generated in hosts with a lower resources allocated)
* When a plugin page (e.g. "Settings") is visited within the Dashboard, trigger a maintenance script that will remove inactive handle data information (from handles without any rule attached to it, often from deleted plugins no longer used) from the "wpassetcleanup_global_data" option value (from `options` table), thus making it lighter
* In many hosting environments, the total number of fields submitted is maximum 1000 (set by default in php.ini); The total number of fields that were sent have been reduced (e.g. hardcoded assets information) as they are only enabled via JavaScript whenever they are relevant to make sure there are less fields sent (to void partial submit and missing data as a result in case the admin has difficulties increase the default 1000 in php.ini)
* Do not automatically store hardcoded assets info when the CSS/JS manager list is loaded; Instead, store it IF there's a rule attached to it in order to make the contents of the "wpassetcleanup_global_data" option smaller in size (for a lighter database & faster MySQL queries)
* Clear plugin's cache via AJAX after "Settings" is updated within the Dashboard (this is more effective then clearing it when the page reloads as it could take some time to clear the cache if there are lots of files stored there)
* Whenever Asset CleanUp's caching is cleared, the Autoptimize (if enabled) caching is also cleared via AJAX (in an asynchronous way) to avoid any broken CSS/JS (rare cases) even for short time
* Trigger certain actions (to save database & disk space) when the plugin is deactivated: Clear all its transients from the database & Remove the caching directory if it doesn't have any more CSS/JS; If all the plugin's changes were cleared via "Tools" -> "Reset", then deactivating the plugin will completely clear any of its traces
* The plugin's own files that are needed for the plugin's functionality (they are only loading for the logged-in admin), are loaded asynchronously (CSS) and deferred (JS) to ensure the admin doesn't load them as render-blocking especially when managing the pages in the front-end view
* The combined CSS tags can now be altered for any reason via add_filter() through the 'wpacu_combined_css_tag' tag name, just like the combined JS tags are via 'wpacu_combined_js_tag'
* While the CSS/JS assets are fetched prevent extra performance plugins from triggering their optimisation for CSS/JS/HTML as the action is irrelevant and uses resources during the fetching of the assets for the admin
* Fix: Remove request to non-existent CSS file within the Dashboard that generated a 404 Not Found error in the browser's console (harmless, but confusing)

= 1.3.6.0 =
* Improved the speed of the generated & printed combined CSS/JS assets by ~30ms (depending on the hosting package and the PHP version used) when the HTML source is altered by avoiding extra useless verifications of the HTML output
* When managing the assets, make sure to show "before" and "after" content (so the user is aware how is that inline tag generated) associated with a handle too (not just the "data" one added via wp_localize_script())
* When managing the assets, make sure the checkboxes from the load exception area are always disabled if there's no unload rule set, thus avoiding any user error to mistakenly add useless load exceptions to an already loading asset, also avoiding any confusing and get a cleaner list in the "Overview" area
* "Overview" page update: If there's any "Load it for the logged-in user" exception for a handle, show it
* Fix: Avoid creating redundant CSS files when minify inlined tags is enabled, leading to a large number of files in the caching

= 1.3.5.9 =
* Removed unused files from "vendor" (Composer's directory) and updated the Minify CSS/JS library to the latest version, fixing errors for PHP 7.4+
* Fix: In some cases, a 500 Internal Error was generated while fetching the assets list for management via the Dashboard view

= 1.3.5.8 =
* Caching: Expired CSS/JS files are cleared differently (in time after visiting various pages) to save resources and errors related to the PHP memory (e.g. shared hosting packages often have limitations in terms of the server's CPU & memory usage)
* Allow CSS/JS management for privately published pages
* Strip empty STYLE/SCRIPT tags if, after optimization, their content is empty (e.g. the CSS was minified as it had only comments in it)
* Show if there are any hardcoded (non-enqueued) LINK/STYLE/SCRIPT tags in the list of CSS/JS list (management of these tags is available in the Pro version)
* Make sure the combined CSS/JS file is valid before its tag is generated in the HTML output (in rare cases, the cached CSS/JS files get deleted either by mistake when developers are cleaning up the caching directory OR they weren't properly created in the first place)
* Compatibility fix to avoid PHP warning error when "Smart Slider 3" & "WP Rocket" are used and the CSS/JS assets are fetched

= 1.3.5.7 =
* Make the admin aware in case a certain CSS/JS asset is loaded within Internet Explorer conditional comments (Read more: https://www.sitepoint.com/internet-explorer-conditional-comments/)
* From now on, "disk" is the default method for storing the cached information of the assets
* Fix: Make sure the path to /wp-includes/ (or other internal directories) is the right one when the blog URL is like mysite.com/blog/
* Fix: Sometimes, warning errors were printed in the "Settings" page after an update (within the Dashboard): "Warning: array_search() expects parameter 2 to be array, null given"

= 1.3.5.6 =
* Added "Debugging" tab to "Tools" page, allowing the option to download the error log file & view other debugging options
* Improvement: Sometimes, 200 OK (success) is returned, but due to an issue with the page, the assets list is not retrieved; Notify the admin about this
* Fix: Sometimes, warnings (harmless, but obtrusive) were given when updating plugin's settings and previewing pages: "Warning: array_unique() expects parameter 1 to be array, null given"

= 1.3.5.5 =
* Updated Minify CSS library for PHP 7.4 support (avoid any PHP errors from "implode" function)
* Improvement: "display=" is now also applied to Google Fonts loading via WebFontConfig within inline SCRIPT tags
* FileSystem is always using the "direct" method for altering CSS/JS files, thus avoiding (e.g. by mistake via a different plugin using the same WordPress FileSystem class) any reading/writing error for the cached files
* When fetching assets, make sure some plugins such as Fast Velocity Minify (that could interfere with the HTML output) are deactivated
* When updating a post/page/homepage, the caching is now cleared after the page is updated via an AJAX call (asynchronously), thus reducing the memory usage and the time spent until the page reloads
* Debugging feature: If /?wpacu_clean_load is used, it will show the unoptimized version of the page (great for locating specific files that were perhaps combined and cached by various plugins)
* Removed Freemius SDK

= 1.3.5.4 =
* New Feature: Stop triggering Asset CleanUp Pro (early stop) on specific front-end pages
* New Feature: Easier way to deactivate options for debugging purposes on page load via /?wpacu_debug (printing an options area at the bottom of any front-end page for the logged-in admin)
* Improvement: Removed plugin's meta boxes when a block is edited in Oxygen (as the meta boxes are not relevant there)
* Improvement: Compressed images for a lighter plugin
* Improvement: Do not trigger Asset CleanUp Pro if TranslatePress Multilingual plugin is in edit mode (front-end view)
* Improvement: Storage info for cache directory shows the total size/number of all files (not just CSS/JS ones)
* Improvement: On page request (within the Dashboard), /wp-admin/?wpacu_get_cache_dir_size will retrieve information about the caching directory (e.g. all its files and their sizes get listed)
* Bug Fix: Avoid any PHP errors or very high CPU usage with some hosting packages in case a "circular reference" error is detected (e.g. an @import from a CSS file that is having the URL path to the same CSS file)
* Bug Fix: CSS/JS URLs starting with // were giving unreachable error when checked if they are valid or not (e.g. from //fonts.googleapis.com/)
* Bug Fix (plugin compatibility): Remove call_user_func_array() PHP error if SiteGround's "Remove Query String From Static Resources" is enabled
* Bug Fix: Prevent errors in some BuddyPress pages as $post->post_type is undefined

= 1.3.5.3 =
* New Feature: Make exception (from any unload rule) and load an asset if the user is logged in
* Adjust the text below "CSS & JS Manager" & "Bulk Changes" to keep it on the same line
* Improvement: Added the total number of handles for stylesheets and scripts in "Overview" page
* Improvement: Sometimes, specific plugins are used to alter the HTML source (e.g. features such as minify HTML); Make sure no META tags are left in the BODY tag as it would give validation errors in https://validator.w3.org/
* Bug Fix: In rare cases, the version of a CSS/JS could be an array, not just a float/integer number; Prevent notices from showing up when 'ver' is an array and make sure the proper query string is passed to the link/path of the source file

= 1.3.5.2 =
* Improvement: Added "Overview" page which has the list of all the changes made to a specific CSS/JS file (handle), offering a much easier way to understand the changes made and do any debugging
* UI Improvement: The height of the CSS/JS asset row (when managing the list) is smaller, depending on the settings, making it easier to do scrolling
* UI Improvement: Adjust the total height of the "Note" textarea based on the content added, thus reducing the spacing between assets for easier scrolling/management
* Code Improvement: Split a few large files into multiple ones for easier management
* Backend Performance Improvement: Prevent Asset CleanUp's (own) CSS/JS from loading in edit post/page when the files aren't needed (e.g. no meta boxes are showing up because they were hidden)
* Extra compatibility with AMP pages: Do not move from HEAD to BODY any SCRIPT tags containing //cdn.ampproject.org/
* Bug Fix: Do not alter any Google Fonts links if there is no "family=" within it ("Smart Slider 3" fix)

= 1.3.5.1 =
* Bug Fix: Prevent PHP notice errors from showing up in error log reports (not affecting the plugin's functionality)
* Improvement for debugging purposes: Change the "Settings" values on page-request (via query string)
* Debugging Improvement: When /?wpacu_show_handle_names is used, it will print the handle name as a "data" attribute tag within the LINK/SCRIPT; Great for debugging and to find out if any of the assets are hardcoded
* Make sure Asset CleanUp's combine CSS/JS works if "HTML Minify" is enabled in W3 Total Cache

= 1.3.5.0 =
* Bug Fix: Sometimes, when external URLs (from a different domain) were present in the assets management list, when checking them via JavaScript for their status, the browser returned some errors interfering with "Add Media" not allowing the user to upload new images from the edit post/page area

= 1.3.4.9 =
* New Feature: Check if the CSS/JS files are reachable; Once the Assets List is loaded for management, AJAX calls would be made to check if the files are not returning 404 not found errors and anyone that returns an "error" gets highlighted (e.g. it's good in situations when a file was enqueued, but later deleted and forgotten thus ending up with 404 Bad Requests in the browser)
* UI Improvement for "Bulk Changes": When a checkbox is ticked, highlight the area of the targeted handle (applies for any results in all the tabs)
* Improvement: Automatic compatibility added for "WP AMP — Accelerated Mobile Pages for WordPress and WooCommerce" (premium plugin)
* Improvement: For easier reference (determining where a handle is coming from: a plugin, the theme etc.), more information such as the source and the version number is added next to each handle in "Bulk Changes"
* Improvement: Once the management CSS/JS list gets loaded, AJAX calls would be made to determine if the source (URL) would return a successful response or not. If a 404 is retrieved or any other error code, then the source URL would be in red color along with a notice the page might load unreachable (bad requests) CSS/JS
* PHP 7.4 Compatibility Fix: Removed deprecated errors for "Array and string offset access syntax with curly braces is deprecated"
* WordPress 4.3 CSS Fix: In the new WP version, some buttons and drop-downs (selects) were unaligned and showing incomplete text

= 1.3.4.8 =
* Compatibility with "AMP (Official AMP Plugin for WordPress)" and "AMP for WP – Accelerated Mobile Pages" plugins: If the page is of AMP type, no Asset CleanUp settings/rules will be triggered to avoid validation errors; Moreover, NOSCRIPT tags added by Asset CleanUp are moved to the BODY tag (they are no longer stored in the HEAD tag) to avoid further validation errors in case other AMP plugins/scripts are used and Asset CleanUp Pro doesn't detect them
* New Feature: Skip "Test Mode" on page request for debugging purposes via /?wpacu_skip_test_mode - e.g. useful when you have to check a website and you don't have admin access and "Test Mode" is enabled (you can check if anything is broken there while the page loads fine for other visitors)
* Improvement: Changed the way the assets list is showing up for management by reducing the size of each row (with CSS/JS file details) so it's easier for scrolling (e.g. load exceptions are showing up only if you chosen a bulk unload option, reduced the height of some of the areas, such as "Handle:", "Source:" and notes area).
* Improvement: The number of total submitted fields is smaller now as the assets list area is not as cluttered since irrelevant fields are not submitted anymore with an empty value (this is not only useful for aesthetic reasons, but it's limiting the risk of submitting over 1000 fields where max_input_vars from php.ini is limited to only 1000 inputs)
* Improvement: No matter what type of layout to show the assets list is chosen from "Assets List Layout:", it will show the total number of CSS/JS for each group (e.g. total files from the theme, total files from all the active plugins, etc.)
* Improvement: Option to choose how the caching information (asset details including its location in the caching directory) is retrieved in "Plugin Usage Preferences" (useful to reduce database queries in case one has a large database that is slow in retrieving information)
* Improvement: If all CSS/JS from a plugin (when the assets list is grouped by location) are bulk unloaded (e.g. on all posts, site-wide), then "Unload on this page" (Check All | Uncheck All) (which becomes irrelevant) is replaced with 'Make an exception from bulk unload, "Load it on this page"' (Check All | Uncheck All)

= 1.3.4.7 =
* Improvement: When assets are fetched to show in the load manager, prevent WP Rocket from running as well as Query Monitor from outputting information
* "Duplicate Post" compatibility fix: Make sure Asset CleanUp's meta values are taken into account when a post is cloned
* Bug Fix: Hide any PHP notice errors (reported in the error log in some environments) for cached CSS/JS as the 3rd parameter ("src" as it is) wasn't added to the returned array

= 1.3.4.6 =
* New Feature in "Optimize JavaScript": Move jQuery Inline Code After jQuery library is called
* Combine Google Fonts Improvement: Strip any extra characters (like double comma ,,) from existing LINK tags pointing to fonts.googleapis.com (more info: https://wordpress.org/support/topic/preload-google-fonts/)
* Bug Fix: Prevent notice errors from showing up on certain hosting environments in Misc.php (in case fetching plugins' icons from WordPress.org fails) and CleanUp.php
* Added more tutorials to "Getting Started" -> "Video Tutorials"

= 1.3.4.5 =
* Improvement: Hardcoded LINK/SCRIPT tags that have relative URLs (no site url added to them) are also checked for optimization (e.g. <link rel="stylesheet" href="/wp-content/uploads/my-custom-hardcoded.css" />)
* Cache Dynamic Assets Improvement: It also checks for www.domain.com?query type of URLs without forward slash / after the domain's extension);
* Inline JS automatically is no longer enabled by default (new installations); A note was added about what it means to inline JS files so the user will be extra careful
* Bug Fix: 'Ignore dependency rule and keep the "children" loaded' wasn't working properly for SCRIPT tags in 1.3.4.4 due to the wrong parameter passed to strip the HTML tag

= 1.3.4.4 =
* Inline automatically CSS smaller then (specific size) KB (if option is enabled)
* Inline CSS Improvement: Inline dynamic loaded CSS (if option is enabled)
* Improvement for "Google Font Remove": Added more patterns to detect Web Font Loader CDN requests
* WP Rocket Compatibility Fix: If the CSS/JS files' path get changed by "WP Rocket" (path contains "/wp-content/cache/busting/"), make sure they are getting unloaded by Asset CleanUp Pro if 'Ignore dependency rule and keep the "children" loaded' option is checked along with the unload rule
* New Feature: Rewrite cached static assets URLs with the CDN ones if necessary (located in "Settings" -> "CDN: Rewrite assets URLs")
* Strip Google Fonts references from JavaScript (.js) files (if the removal option is active)
* Append "display" parameter to Google Font URLs within JavaScript files (if any option for "font-display:" is chosen)
* Bug Fix: Make sure all values from "Site-Wide Common Unloads" show the correct status (enabled/disabled) in "System Info" from "Tools"

= 1.3.4.3 =
* New Assets Management Feature: Until now, the list was loaded automatically on edit post, page, custom post type, and taxonomy. You can choose to fetch the list when clicking on a button. This is good when you rarely manage loaded CSS/JS and want to declutter the edit page on load and also save resources as AJAX calls to the front-end won't be made to retrieve the assets' list.
* New Feature: Cache Dynamic Loaded CSS & JavaScript to avoid loading the whole WP environment and save resources on each request (e.g. /?custom-css=value_here or /wp-content/plugins/plugin-name-here/js/generate-script-output.php?ver=1)
* Reduced the number of database queries to fetch cached information making the pages preload faster (when the caching is rebuilt) thus reducing the loading time especially if PHP 5.6 is still used (which is slower than PHP 7+ when it deals with database connections).
* Combine JS files improvement: If there are multiple files that have "defer" or "async" attribute set (or both) and they are not preloaded, then they will be grouped into fewer files; Before, only SCRIPT tags without these attributes were combined
* Improvement to reduce disk space: Make sure already minified (100%) static .js files aren't cached
* Google Fonts Optimization: Requests that are for icons (e.g. https://fonts.googleapis.com/icon?family=Material+Icons) are also combined to reduce HTTP requests
* "Optimize CSS Delivery" from WP Rocket works together with "Inline Chosen CSS Files" from Asset CleanUp Pro
* Prevent plugin from loading when Themify Builder (iFrame) is used
* Bug Fix: Sometimes, the position of an asset (HEAD or BODY) is reported incorrectly if it was enqueued in specific action hooks; Extra checks are made to fix that as sometimes developers do not use wp_enqueue_scripts() which is the proper hook to use when enqueuing items that are meant to appear on the front end
* Bug Fix: If CSS files get inlined, make sure @import without "url" is updated correctly in all situations
* Bug Fix: In rare cases, managing assets for the Homepage is not working properly. Reason: $post is overwritten by external plugins or the theme because the developers have forgotten to use wp_reset_postdata() and reset it to its initial value (which should be 0 in this case).

= 1.3.4.2 =
* New Feature: Remove Google Font Requests (including link/font preloads, @import/@font-face from CSS files & STYLE tags, resource hints)
* Minify/Combine CSS Improvement: Any @import found including a local CSS in another CSS file is fetched (and minified/optimized if necessary) and added to the parent file (this reduces HTTP requests, saving additional round-trip times to the overall page load) - Read more: https://gtmetrix.com/avoid-css-import.html
* Hardcoded CSS/JS (not enqueued the WordPress way) from the same domain (local) get minified/optimized
* Bug Fix: In rare cases, when viewing the homepage assets in the Dashboard, the wrong page is checked (e.g. a post instead of the actual homepage) because specific themes/plugins do not use wp_reset_postdata() to restore $post global to its original value (none in this instance)
* Bug Fix: If Google Fonts loading type is async (optional with preload) then make sure it's applied even if there's only one LINK request

= 1.3.4.1 =
* Combined CSS files are now grouped by the LINKs media attribute (before, only "all" (default) stylesheets were combined); e.g. if there are three "print" stylesheet and four "only screen and (max-width: 1024px)", then two separate combined CSS files will be created for each media type
* Make sure the "media" attribute is always added to the STYLE tag if a certain one (besides 'all') was set
* If Google Fonts loading type is async (optional with preload) then make sure it's applied even if there's only one LINK
* Bug Fix: If the relative path to a font/background image in a CSS file started with ./ the full path to the file wasn't updated when copied to the cache

= 1.3.4.0 =
* New feature: Inline Chosen CSS files (usually small ones) saving the overhead of fetching them resulting in fewer HTTP requests (more: https://varvy.com/pagespeed/inline-small-css.html / https://gtmetrix.com/inline-small-css.html)
* New Option to load Google Fonts: Asynchronous by preloading the CSS stylesheet
* Reduced redundant CSS/JS files cached for logged-in users, thus making clearing the caching faster and reducing the total disk space (sometimes, on certain hosting environments with lower memory limit clearing the whole caching resulted in "PHP Fatal error: Allowed memory size of (X) bytes exhausted")

= 1.3.3.9 =
* Option to disable "Freemius Analytics & Insights?" in "Settings" -> "Plugin Usage Preferences" (good if you do debugging & often deactivate the plugin or you just don't like plugin feedback popups)
* Changed the vertical "Settings" menu by renaming "Minify CSS & JS Files" & "Combine CSS & JS Files" to "Optimize CSS" & Optimize JavaScript; Added the status of the minify/combine below the menu titles to easily check what optimizations were done
* Improved the way JS files are combined; If "Defer loading JavaScript combined files" is enabled in "Optimize JavaScript", make sure that any external script between the first and last combined JS tags has "defer" attribute applied to it to avoid any JS errors in case a "child" JS file is loaded before a combined "parent" one.
* Option to minify inline content between from STYLE and SCRIPT (without any "src" attribute) tags
* Optimize minify CSS/JS feature to use less resource when dynamically generating the optimized (cached) files; Minification is performed via a new library (ref: https://www.minifier.org/)
* Option to choose between "Render-blocking" and "Asynchronous via Web Font Loader (webfont.js)" when loading the combined Google Font requests
* Bug Fix: Sometimes the dynamically created drop-down from "Hide all meta boxes for the following public post types" (in "Settings" -> "Plugin Usage Preferences") via jQuery Chosen plugin was returning an empty (0px in width) selector

= 1.3.3.8 =
* Option to hide all meta boxes for specific post types (e.g. not queryable or do not have a public URL, making the assets list irrelevant)
* Bug Fix: In some servers, when preload feature is used and the HTML is not fully valid for DOMDocument, PHP errors were printing
* Extra compatibility with "Breeze – WordPress Cache Plugin"
* Do not trigger Asset CleanUp on Avada's Fusion Builder Live: Edit Mode

= 1.3.3.7 =
* New Feature: Google Fonts Optimization: Option to preload Google Font Files
* New Feature: Enable preconnect resource hint for fonts.gstatic.com in case you use Google Fonts
* New Feature: Local Fonts Optimization: Option to preload Local Font Files
* New Feature: Strip LINKs that are made to Google Fonts (fonts.googleapis.com) without any "family" value (e.g. some themes/plugins allow to input the font family but don't validate empty submits)
* Extra Compatibility with the latest version of SG Optimiser
* Bug Fix: Excluding CSS/JS files from combination was not working effectively if Minify CSS/JS was also applied to the asset

= 1.3.3.6 =
* New Feature: Google Fonts Optimization: Combine multiple font requests into fewer requests; Option to add "font-display" CSS property (PageSpeed Insights Reference: "Ensure text remains visible during webfont load")

= 1.3.3.5 =
* New Option To Conveniently Site-Wide Unload Gutenberg CSS Library Block in "Settings" -> "Site-Wide Common Unloads"
* Better way to clear cached files as the system doesn't just check the version number of the enqueued file, but also the contents of the file in case an update is made for a CSS/JS file on the server, and the developer(s) forgot to update the version number
* When CSS/JS caching is cleared, the previously cached assets older than (X) days (set in "Settings" -> "Plugin Usage Preferences") are deleted from the server to free up space
* New Information was added to "Tools" -> "Storage Info" about the total number of cached assets and their total size
* Prevent specific already minified CSS files (based on their handle name) from various plugins from being minified again by Asset CleanUp (to save resources)
* Bug Fix: When the asset's note was saved, any quotes from the text were saved with backslashes that kept increasing on every save action

= 1.3.3.4 =
* Preload CSS/JS Compatibility Update: If "WP Fastest Cache" is enabled with "Minify CSS" or "Minify JS" option, Asset CleanUp preloading works fine with the new (cached) URLs
* New Option in "Assets List Layout": Sort assets by their preload status (preloaded or not)
* Bug Fix: Sometimes, the file writing permission constants were not loaded (e.g. FS_CHMOD_FILE)
* Bug Fix: Some transients where left in the database after a "Reset Everything" was performed causing confusing regarding the total number of unloaded assets
* Prevent Asset CleanUp from loading any of its rules when Gravity Forms are previewed

= 1.3.3.3 =
* New Feature: Option to preload CSS/JS files by ticking "Preload (if kept loaded)" checkbox for the corresponding file (More info: https://developers.google.com/web/tools/lighthouse/audits/preload)
* Hide irrelevant Asset CleanUp MetaBoxes when custom post types from "Popup Maker" & "Popup Builder" plugins are edited

= 1.3.3.2 =
* Bug Fix: When pages were updated, jQuery Migrate and Comment Reply were loaded back (when they were marked for unloading)
* Bug Fix: Sometimes, WP Rocket caching was not fully cleared because of an Asset CleanUp hook that interfered with it

= 1.3.3.1 =
* Option to unload on all pages (site-wide) the Dashicons for non-logged-in users
* Load it on this page (exception) is preserved if chosen before any bulk unload
* Better accuracy in getting the total unloaded assets
* Used transient to store total unloaded assets from the SQL query (it's slow on some servers)
* Improved "Plugin Review" notice to use fewer queries to determine if it will be shown or not
* On plugin activation, mark Checkout/Cart pages from WooCommerce & EDD to not apply plugin combine/minify options
* Minify/Combine CSS/JS files option from Asset CleanUp will be unavailable if Fast Velocity Minify is active
* Fixed undefined error related to ignoring "children" option
* Implemented WordPress File System for dealing with read/write cached CSS/JS files
* Improved "CSS/JS Load Manager" pages overview layout
* Disable oEmbeds Feature; Option to update "Assets List Layout" while managing the assets
* Added tip messages next to various handles
* Bug Fix: AJAX call for retrieving plugins' icons was not working
* Updated Freemius SDK to 2.3.0

= 1.3.3.0 =
* Minify/Combine CSS/JS files option from Asset CleanUp will be unavailable if the same feature is used in other plugins (the list includes: Autoptimize, WP Rocket, WP Fastest Cache, W3 Total Cache, SG Optimizer) to save resources and potential conflicts
* Remove Shortlink - Addition: Clean it up from the HTTP header as well (not just within the HEAD section of the website)
* Do not trigger Asset CleanUp on Elementor & Divi Page Builders AJAX calls from the Edit Area; Only trigger fetching plugin icons from WordPress.org in specific situations (this is especially to save resources on some hosting environments such as the shared ones)

= 1.3.2.9 =
* New Feature: Enable Minify CSS/JS on the fly when admin is logged in (for debugging purposes) - via /?wpacu_css_minify
* Updated "Tools" -> "System Info": Has database information related to the Asset CleanUp's entries
* Option to override "administrator" (default) role, in order to acesss plugin's pages
* Do not trigger Asset CleanUp Pro on REST Requests, WPBakery Page Builder Edit Mode, Brizy Page Builder Edit Mode
* Avoid notice errors if some "SG Optimizer" features are enabled
* Minify CSS: Compatibility with "Simple Custom CSS" plugin
* Match sidebar and top bar menus; Allow unloading of CSS/JS on the fly (via URI request) for debugging purposes; Added coloured left border for assets that had their position changed to easily distinguish them
* New Feature: Ignore dependency rule and keep the "children" loaded
* New Feature: CSS/JS "Notes" (useful to remember why you have unloaded or decided to keep a specific file)
* Bug Fix: Posts' Metas (e.g. load exceptions) were not imported
* Bug Fix: Make sure specific elements from "Site-Wide Common Unloads" are properly imported/exported

= 1.3.2.8 =
* "Import & Export" added under "Tools" For Settings & Load/Unload Rules
* "General & Files Management" -> "Assets List Layout" - 2 new options: Group by dependencies & Group by loaded/unloaded status
* Option to hide "Asset CleanUp" menu from the sidebar
* Minified CSS/JS Improvement: Do not replace the original source path with the minified one if the cache file was deleted via an external action (e.g. the "cache" directory was deleted via cPanel/FTPS)

= 1.3.2.7 =
* Bug Fix: array_key_first() didn't have a fallback for PHP 5 causing plugin admin pages to disappear
* Do not trigger Asset CleanUp if either of the following page builders are in edit mode: "Thrive Architect", "Page Builder by SiteOrigin" & "Beaver Builder"
* Code improvement; Hide meta boxes from Themify builder templates

= 1.3.2.6 =
* Prevent plugin from triggering any of its settings when page builders (e.g. Divi, Elementor, Beaver Builder, Oxygen, etc.) are in edit mode for maximum compatibility
* Compatibility with SG Optimizer plugin
* Option to prevent plugin to trigger any of its settings & unload rules on request via "wpacu_no_load" query string
* Do not minify CSS/JS from /wp-content/uploads/ (e.g. files belonging to Elementor or Oxygen page builder plugins)
* Added more things to "System Info" including settings and browser information
* Apply relative URLs for combined CSS/JS script/stylesheet tags, if URL opened is via SSL and the WordPress site URL starts with http://
* Bug Fix: Clear CSS/JS cache was returning a blank white page
* Bug Fix: Minify JS - Exceptions weren't applied

= 1.3.2.5 =
* Bug Fix: 403 Forbidden error was returned when fetching assets within the Dashboard because of the wrong nonce check
* Option to show on request all the settings (no tabs) within "Settings" plugin's area by appending '&wpacu_show_all' to the URL like: /wp-admin/admin.php?page=wpassetcleanup_settings&wpacu_show_all

= 1.3.2.4 =
* "Manage in the Front-end?": Add exceptions from printing the asset list when the URI contains specific strings (e.g. "et_fb=1" for Divi Visual Builder)
* Option to hide plugin's meta boxes on edit post/page area within the Dashboard
* Make sure no irrelevant errors are written excessively to the server's log printed via DOMDocument in case the HTML is not fully valid

= 1.3.2.3 =
* New Option in "Assets List Layout": Sort by CSS/JS position (HEAD and BODY tags)
* New Feature: Strip HTML Comments
* Assets Meta Box update: Changed 'advanced' to 'normal' for $context parameter (e.g. Oxygen Builder 2.2 compatibility); added option to update the $content and $priority of the Asset CleanUp meta boxes via "add_filter" via the following tags (for each meta box): wpacu_asset_list_meta_box_context, wpacu_asset_list_meta_box_priority, wpacu_page_options_meta_box_context, wpacu_page_options_meta_box_priority
* Bug Fix: Settings update now trigger on 'init' rather than 'plugins_loaded' for maximum compatibility with all WP installs;
* Bug Fix: Make sure Emojis are always disabled when specified in the Settings and there is no DNS prefetch to //s.w.org
* Potential WooCommerce bug fix (is_woocomerce() returns true while is_cart() isn't sometimes)
* Added video tutorials in the "Getting Started" area
* Removed HTML usage notice for Asset CleanUp

= 1.3.2.2 =
* Security Fix: Updated Freemius SDK to 2.2.4
* Bug Fix: Within the Dashboard, an error related to an undefined constant in Plugin.php was showing up

= 1.3.2.1 =
* Prevent AJAX calls from triggering to retrieve asset list when a new post/page is created as the CSS/JS files should only be fetched when after the post/page is published
* Improved the PHP code to use fewer resources on checking specific IF conditions
* Added introduction to the "Settings" area about how the plugin is working to give a clear understanding of what needs to be done to optimize the pages
* Bug Fix: Prevent CSS files containing "@import" from getting combined (they remain minified) to prevent breaking the layout
* Bug Fix: "Do not minify JS files on this page" checkbox from the side meta box (edit post/page area) wasn't kept as selected after "Update" button was used
* Bug Fix: Avoid PHP notice errors in case arrays that do not always have specific keys are checked
* Security Fix: Updated Freemius SDK to its latest version

= 1.3.2 =
* Minify/Combine JS files & HEAD CleanUp features are now available in the Lite version

= 1.3.1 =
* CSS Files within BODY tag are also combined just like the ones from HEAD tag
* Offer the option to clear the CSS/JS caching even if CSS/JS Minify/Combine options were deactivated
* Bug Fix: Old links to the manage homepage page from the admin bar were updated with the new ones
* Bug Fix: On some WordPress setups, the path to the CSS background image URL after combination was updated incorrectly

= 1.3 =
* New Feature: Minify CSS
* Bug Fix: Make sure no 500 errors are returned on save settings or save post when the wrong caching directory is read

= 1.2.9.9 =
* Bug Fix: Side "Asset CleanUp: Options" meta box was not showing in the edit page/post within the Dashboard view

= 1.2.9.8 =
* New Feature: "Asset CleanUp: Options" side meta box showing options to disable plugin functionality for posts, pages, and custom post types; Ideal to use with the "Preview" feature if you wish to see how a page loads/looks before publishing any changes

= 1.2.9.7 =
* Bug Fix: Prevent fatal error from showing in PHP 5.4 when the plugin was updated
* Re-organised the plugin's links within the Dashboard to make it easier to navigate through
* "Combine CSS files into one" feature update - CSS files having media="print" or media="only screen and (max-width: 768px)" (and so on) are not combined
* "Combine JS files into fewer ones" feature update - jQuery and jQuery Migrate are combined as a single group (not together with any other files); if just jQuery is loaded (without jQuery Migrate), it will not be added to any group and load independently

= 1.2.9.6 =
* Bug Fix: After post/page update, a fatal error was showing on calling a method that doesn't exist

= 1.2.9.5 =
* Changed the way the combined loaded CSS files caching is stored (/wp-content/cache/asset-cleanup/); Transients are not used anymore to avoid having too many records in the options table
* Clear cache now keeps the old merge CSS files in the "cache/asset-cleanup" folder in case a cached page still makes reference to any of the files

= 1.2.9.4 =
* Bug Fix: If PHP version is lower than 7, an error is shown when fetching the plugins' icons in the background to be shown on the management list (when sorted by the assets' location)

= 1.2.9.3 =
* New sorting by location (default) option in "Assets List Layout" setting
* Cache transients are also cleared when resetting everything
* Changed plugin's default settings ("Inline code associated with this handle" is contracted by default)

= 1.2.9.2 =
* WooCommerce & WP Rocket Compatibility - Bug Fix: When both WooCommerce and WP Rocket are active and an administrator user is logged in and tries to place an order, the "Sorry, your session is expired." message is returned

= 1.2.9.1 =
* PHP 5.4+ minimum required to use the plugin
* "Combined Loaded CSS" feature (concatenates all the remaining loaded stylesheets within the HEAD section of the page and saves them into one file) to reduce HTTP requests even further
* Improved "Getting Started" area
* Made "Settings" as the default page where you (the administrator user) is redirected when activating the plugin for the first time

= 1.2.9 =
* Added "System Info" to "Tools" page to fetch information about the WordPress environment in case something needs debugging
* Added "Getting Started" page to make things easier for anyone who doesn't understand how the plugin works

= 1.2.8.9 =
* Only trigger specific actions when necessary to avoid the use of extra server resources
* Make sure "ver" query string is stripped on request only for the front-end view; Avoid removing the license info from the database when resetting everything (unless the admin chooses to remove the license info too for a complete uninstall)
* Updated the way temporary data is stored (from $_SESSION to WordPress transient) for more effective use of server resources

= 1.2.8.8 =
* Option to clean any license data after everything is reset in case the Pro version was used before on the same website
* Removed "Opt In" option from the non-sensitive diagnostic tracking until it will get replaced with a smoother version

= 1.2.8.7 =
* Bug Fix: When settings are reset to their default values via "Tools", make sure 'jQuery Migrate' and 'Comment Reply' are loading again if added in the bulk (site-wide) unload list (as by default they were not unloaded)

= 1.2.8.6 =
* Better support for WordPress 5.0 when updating a post/page within the Dashboard
* On new plugin installations, "Hide WordPress Core Files From The Assets List?" is enabled by default
* Added option for users to opt in to security and feature updates notifications, and non-sensitive diagnostic tracking
* Added "Tools" page which allows you to reset all settings or reset everything
* Bug Fix: Notice error was printing when there was no source file for specific handles that are loading inline code (e.g. woocommerce-inline)

= 1.2.8.5 =
* Option to hide WordPress core files from the management list to avoid applying settings to any of them by mistake (showing the core files for unload, async or defer are mostly useful for advanced developers in particular situations)
* Improved security of the pages by adding nonces everywhere there is an update button within the Dashboard related to the plugin
* Added confirmation message on top of the list in front-end view after an update is made (to avoid confusion whether the settings were updated or not)
* The height of an asset row (CSS or JavaScript) is now smaller as "Unload on this page" and bulk unloads (site-wide, by post type etc.) are placed on the same line if the screen width is large enough, convenient when going through a big list of assets

= 1.2.8.4 =
* Added "Input Fields Style" option in plugin's "Settings" which would turn the fancy CSS3 iPhone-like checkboxes to standard HTML checkboxes (good for people with disabilities who use a screen reader software or personal preference)
* Added notification in the front-end view in case WP Rocket is enabled with "User Cache" enabled
* Option to have the "Inline code associated with the handle" contracted on request as it will reduce the length of the assets management page in case there are large blocks of text making it easier to scan through the assets list
* Tested the plugin for full compatibility with PHP 7.2 (5.3+ minimum required to use it)

= 1.2.8.3 =
* Added the logo on top of each admin page belonging to the plugin
* Changed plugin's icon from the Dashboard left menu

= 1.2.8.2 =
* Added option to expand / contract "Styles" and "Scripts" management list and ability to choose the initial state on page load via plugin's "Settings" page

= 1.2.8.1 =
* Added "Test Mode" option which will unload assets only if the user is logged in as administrator and has the capability of activating plugins.
* This is good for debugging in case one might worry that a CSS/JavaScript file could be unloaded by mistake and break the website for the regular (non-logged in) users.
* Once the page loads fine and all looks good, the "Test Mode" can be disabled so the visitors will load the lighter version of the page.

= 1.2.8 =
* Bug Fix: PHP code change to properly detect the singular pages had the wrong condition set

= 1.2.7.9 =
* Improved CSS styling for the assets list to avoid conflicts between other CSS rules from other themes and plugins

= 1.2.7.8 =
* PHP Code Improvement

= 1.2.7.7 =
* In case the assets can't be retrieved via AJAX calls within the Dashboard, the user will be notified about it and any response errors (e.g. 500 Internal Errors) would be printed for debugging purposes
* Make the user aware that there could be also CSS files loaded from the WordPress core that should be unloaded only if the user is comfortable with that
* Improved "Help" page by adding more explanations about how to upgrade to the Pro version and how to seek professional help in case you're stuck

= 1.2.7.6 =
* Bug Fix: "Everywhere" bulk unloads could not be removed from "Bulk Unloaded" page

= 1.2.7.5 =
* Bug Fix: When inline CSS code was attached to a handle, it would trigger an error and prevent the assets from printing in the back-end view

= 1.2.7.4 =
* Added "Feature Request" link
* Bug Fix: Sometimes scripts are loading on Dashboard view, but not showing on Front-end view
* Better detection for the home page especially if custom layouts are added like the one from "Extra" theme

= 1.2.7.3 =
* Made it more clear what bulk unloads are within the description of the options
* Added more extra options to the plugin's settings that become available if a premium upgrade is made
* Updated banner preview from the WordPress plugin's page

= 1.2.7.2 =
* Bug Fix: Sometimes, specific scripts were showing up on Dashboard view, but not showing on Front-end view
* Extra confirmation required when unloading site-wide "jQuery Migrate" and "Comment Reply" from the plugin's settings (to avoid accidental unload)

= 1.2.7.1 =
* Removed "@" from printing in the output when using AJAX call to fetch the assets, to avoid conflict with Cloudflare's email protection
* Replaced deprecated jQuery's live() with on() to avoid JavaScript error on the front-end in case jQuery Migrate is disabled

= 1.2.7 =
* Removed iCheck and replaced with pure CSS to make the plugin lighter
* Added top menu for easier navigation between plugin's pages;
* Added "Pages Info" with explanations regarding the type of pages that can be unloaded
* Removed "Lite" from the plugin's title

= 1.2.6.9 =
* Made sure that default.php (new file) is not missing within /templates/meta-box-loaded-assets/ directory

= 1.2.6.8 =
* Important: If you use the premium extension, please upgrade to 1.0.3
* Removed "WP" from the plugin's title
* Prevent the LITE plugin from loading if the PRO version is enabled as loading both plugins is not relevant anymore
* Avoided loading Asset CleanUp's own CSS and JS within the Dashboard view as they are irrelevant since they're only loaded for the admins that manage the plugin

= 1.2.6.7 =
* Bug Fix: "Unload on All Pages of [post type here] post type" was not showing within the Dashboard view

= 1.2.6.6 =
* Bug Fix: Assets were not retrieved within in the Dashboard for the home page
* Compatible with WP Asset CleanUp Pro

= 1.2.6.5 =
* Bug Fix: Fatal error "Can't use method return value in write context" (for PHP versions < 5.5)

= 1.2.6.4 =
* Bug Fix: When editing a post/page within the Dashboard and the "Update" button was pressed before the "WP Asset CleanUp" meta box was loading, it sent an empty unloaded list to the plugin and it deleted the current settings for that particular post/page

= 1.2.6.3 =
* Bug Fix: On some environments, a fatal error shows when activating the plugin (the issue was posted on the support and the ticket solved)

= 1.2.6.2 =
* Added "Disable jQuery Migrate Site-Wide?" and "Disable Comment Reply Site-Wide?" (which belong to WordPress core files and often are not used in a WordPress website) to "Settings" page for the convenience of the user
* Bug Fix: jQuery Migrate can be properly unloaded now without affecting the load of jQuery

= 1.2.6.1 =
* Bug Fix for PHP versions lower than 5.6 - Menu.php triggered a PHP warning as PHP constants were not allowed in class constants

= 1.2.6 =
* New Feature: Disable Emojis Site-Wide
* Hide "WP Asset Clean Up" menu if the logged in user doesn't have 'manage_options' capabilities (technically, it's just for administrators)

= 1.2.5.3 =
* Bug Fix: PHP Warning when array was passed to json_decode(), instead of string

= 1.2.5.2 =
* Bug Fix: Unload on All Pages of [post_type_here] post type wasn't keeping previous records when choosing new values to unload

= 1.2.5.1 =
* Bug Fix: Better accuracy for determining the current post ID and whether the page is the home page

= 1.2.5 =
* Bug Fix: Remove JavaScript error from window.btoa() in case the page contains non-latin characters
* Added "Get Help" page within the plugin's menu to anyone interested in hiring me or any of my colleagues for professional help related to the plugin or any other WordPress task

= 1.2.4.4 =
* Updated AJAX calls to work fine within the Dashboard even if mod_security Apache module is enabled as there were some problems on specific servers
* Added "Unload on this page" text next to the first checkbox to explain its purpose better
* Added "WP Remote Post" method to retrieve assets (in case the default "Direct" method doesn't work)
* Enable/disable asset list loading within the Dashboard (in case one prefers to only have it within the front-end)

= 1.2.4.3 =
* Bug Fix: PHP versions < 5.4 triggered errors

= 1.2.4.2 =
* Now styles that are loaded in the BODY section of the page are unloaded (if selected); Sometimes, in special cases, within "wp_footer" action (or other similar one such as "get_footer"), wp_enqueue_style is called

= 1.2.4.1 =
* Bug Fix: When the handle's key on update was equal with 0 (for remove global unload), the rule would not be remove *

= 1.2.4 =
* Bug Fix: Remove "Unload everywhere" rule had to be updated to work no matter what key is assigned to the handle in the array resulting from the JSON

= 1.2.3 =
* Assets can now be disabled for all the pages belonging to a specific post type
* The list of assets disabled globally (everywhere, for a specific post type etc.) can be managed in a single page too

= 1.2.2 =
* Bug Fix: Sometimes scripts in the footer were not detected for unloading

= 1.2.1 =
* Bug Fix: Sometimes the assets exceptions list (when disabled globally) for the homepage is not loaded from the right source

= 1.2 =
* Disable assets site-wide
* Add exceptions on pages where assets should load (if they are disabled everywhere)
* Bug Fix: Sometimes, due to website caching services/plugins, the HTML comments are removed needed from getting the assets

= 1.1.4.6 =
* Now the asset list can be updated on the front-end (below the loaded page, post, front page) if the feature is enabled in the "Settings"
* The assets URL is now clickable and loads the CSS/JS file in a new tab

= 1.1.4.5 =
* Some assets containing specific ASCII characters in the URL were not shown. This is solved now and they will show fine in the list.
* A warning icon is shown next to each script that is part of WordPress core. Also, a message on the top of the list warns the user about the risks of unloading core files

= 1.1.4.4 =
* If the Dashboard is accessed through HTTPS, then the AJAX call to the front-end must be through HTTPS too - otherwise the call gets blocked and the assets list will not show (loading message will appear and confuse user)

= 1.1.4.3 =
* Improved code to not show any PHP errors in case WP_DEBUG constant is set to 'true'

= 1.1.4.2 =
* Prevent JavaScript errors from showing in the background and interfere with the functionality of other plugins in case script.min.js is loaded in pages where the plugin is not needed

= 1.1.4.1 =
* Prevent any calls to be made for non-published posts/pages as the list of assets is relevant only after the post is published and all assets (from plugins and the themes) are properly loaded on that post/page

= 1.1.4 =
* Bug fix that prevented the AJAX calls from triggering on specific WordPress settings

= 1.1.3 =
* Improved the code and made sure that the actual URL being fetch is shown to avoid confusion

= 1.1.2 =
* Fixed a bug that wasn't loading the iCheck jQuery plugin all the time when it was needed
* Better check if PHP version is 5.3+ (notification is shown only in the Dashboard and the plugin does not load in the front-end)

= 1.1 =
* Remove assets from loading in public custom post types too besides the basic 'post' and 'page' ones
* Remove assets from loading in home page as well if "Front page displays" is set to "Your latest posts" in "Settings" -> "Reading"
* The plugin uses is_front_page() function to determine where the visitor is on your website

= 1.0 =
* Initial Release