=== WP Hide & Security Enhancer ===
Contributors: nsp-code, tdgu
Donate link: https://www.nsp-code.com/
Tags: wordpress hide, hide, security, improve security, hacking, wp hide, custom login, wp-loging.php, wp-admin, admin hide, login change, 
Requires at least: 2.8
Tested up to: 5.7
Stable tag: 1.6.3.1
License: GPLv2 or later

Hide WordPress default paths, wp-content, wp-includes, wp-admin, login URL, plugins, themes etc. Block the defaults for being still accessible. No files and data are changed on your server.

== Description ==

The **easy way to completely hide your WordPress** core files, login page, theme and plugins paths from being show on front side. This is a huge improvement over Site Security, no one will know you actually run a WordPress. Provide a simple way to clean up html by removing all WordPress fingerprints.

**No file and directory change!**
No file and directory are being changed anywhere, everything is processed virtually! The plugin code use URL rewrite techniques and WordPress filters to apply all internal functionality and features. Everything is done automatically, there's no user intervention require at all.

**Real hide of WordPress core files and plugins**
The plugin not only allow to change default urls of you WordPress, but it hide/block defaults! Other similar plugins, just change the slugs, but the default are still accessible, obviously revealing WordPress as CMS

Change the default WordPress login urls from wp-admin and wp-login.php to something totally arbitrary. No one will ever know where to try to guess a login and hack into your site. Totally invisible !!

[vimeo http://vimeo.com/185046480]

<br />Full plugin documentation available at <a target="_blank" href="https://www.wp-hide.com/documentation/">WordPress Hide and Security Enhancer Documentation</a>

When testing with WordPress theme and plugins detector services/sites, any setting change may not reflect right away on their reports, since they use cache. So you may want to check again later, or try a different inner url, homepage url usage is not mandatory.

Being the best content management system, widely used, WordPress is susceptible to a large range of hacking attacks including brute-force, SQL injections, XSS, XSRF etc. Despite the fact the WordPress core is a very secure code maintained by a team of professional enthusiast, the additional plugins and themes makes the vulnerable spot of every website. In many cases, those are created by pseudo-developers who do not follow the best coding practices or simply do not own the experience to create a secure plugin. 
Statistics reveal that every day new vulnerabilities are discovered, many affecting hundreds of thousands of WordPress websites. 
Over 99,9% of hacked WordPress websites are target of automated malware scripts, who search for certain WordPress fingerprints. This plugin hide or replace those traces, making the hacking boots attacks useless.

Works fine with custom WordPress directory structures e.g. custom plugins, themes, uplaods folder.

Once configured, you need to **clear server cache data and/or any cache plugins** (e.g. W3 Cache), for a new html data to be created. If use CDN this should be cache clear as well.

**Sample usage**
[vimeo https://vimeo.com/192011678]

**Main plugin functionality:**

* Custom Admin Url
* Block default admin Url
* Block any direct folder access to completely hide the structure
* Custom wp-login.php filename
* Block default wp-login.php
* Block default wp-signup.php
* Block XML-RPC API
* New XML-RPC path
* Adjustable theme url
* New child Theme url
* Change theme style file name
* Clean any headers for theme style file
* Custom wp-include 
* Block default wp-include paths
* Block defalt wp-content
* Custom plugins urls
* Individual plugin url change 
* Block default plugins paths
* New upload url
* Block default upload urls
* Remove wordpress version
* Meta Generator block
* Disble the emoji and required javascript code
* Remove pingback tag
* Remove wlwmanifest Meta
* Remove rsd_link Meta
* Remove wpemoji
* Minify Html, Css, JavaScript

and many more.

**No other plugins functionality is being blocked or interfered in any way, everything will function the same**

This plugin allow to change default Admin Url's from **wp-login.php** and **wp-admin** to something else. All original links return default theme 404 Not Found page, like nothing exists there. Beside the huge security advantage, this save lots of server processing time by reducing php code and MySQL usage since brute-force attacks trigger wrong urls.

**Important:** Compared to all other similar plugins which mainly use redirects, this plugin return a default theme 404 error page for all **block url** functionality, so is not revealing at all the link existence.

Since version 1.2 Change individual plugin urls which make them unrecognizable, for example change default WooCommerce plugin urls and dependencies from domain.com/wp-content/plugins/woocommerce/ to domain.com/ecommerce/cdn/ or anything customized.

= Plugin Sections =

**Rewrite > Theme**

* New Theme Path - Change default theme path
* New Style File Path - Change default style file name and path
* Remove description header from Style file - Replace any WordPress metadata informations (like theme name, version etc) from style file
* Child - New Theme Path - Change default child theme path
* Child - New Style File Path - Change child theme stylesheed file path and name
* Child - Remove description header from Style file - Replace any WordPress metadata informations (like theme name, version etc) from style file

**Rewrite > WP includes**

* New Includes Path - Change default wp-includes path / url
* Block wp-includes URL - Block default wp-includes url

**Rewrite > WP content**

* New Content Path - Change default wp-content path / url
* Block wp-content URL - Block default content url

**Rewrite > Plugins**

* New Plugins Path - Change default wp-content/plugins path / url
* Block plugins URL - Block default wp-content/plugins url
* New path / url for Every Active Plugin
* Custom path and name for any active plugins

**Rewrite > Uploads**

* New Uploads Path - Change default media files path / url
* Block uploads URL - Block default media files url

**Rewrite > Comments**

* New wp-comments-post.php Path
* Block wp-comments-post.php

**Rewrite > Author**

* New Author Path
* Block default path

**Rewrite > Search**

* New Search Path
* Block default path

**Rewrite > XML-RPC**

* New XML-RPC Path - Change default XML-RPC path / url
* Block default xmlrpc.php - Block default XML-RPC url
* Disable XML-RPC authentication - Filter whether XML-RPC methods requiring authentication
* Remove pingback - Remove pingback link tag from theme

**Rewrite > JSON REST**

* Disable JSON REST V1 service - Disable an API service for WordPress which is active by default.
* Disable JSON REST V2 service - Disable an API service for WordPress which is active by default.
* Block any JSON REST calls - Any call for JSON REST API service will be blocked.
* Disable output the REST API link tag into page header
* Disable JSON REST WP RSD endpoint from XML-RPC responses
* Disable Sends a Link header for the REST API

**Rewrite > Root Files**

* Block license.txt - Block access to license.txt root file
* Block readme.html - Block access to readme.html root file
* Block wp-activate.php - Block access to wp-activate.php file
* Block wp-cron.php -  Block access to wp-cron.php file
* Block wp-signup.php - Block default wp-signup.php file
* Block other wp-*.php files - Block other wp-*.php files within WordPress Root

**Rewrite > URL Slash**

* URL's add Slash - Add a slash to any links without. This disguise any existing for a file, folder or a wrong url, they all be all slashed.


**General / Html > Meta**

* Remove WordPress Generator Meta
* Remove Other Generator Meta
* Remove Shortlink Meta
* Remove DNS Prefetch
* Remove Resource Hints
* Remove wlwmanifest Meta
* Remove feed_links Meta
* Disable output the REST API link tag into page header
* Remove rsd_link Meta
* Remove adjacent_posts_rel Meta
* Remove profile link
* Remove canonical link

**General / Html > Admin Bar**

* Remove WordPress Admin Bar for specified urser roles

**General / Feed**

* Remove feed|rdf|rss|rss2|atom links

**General / Robots.txt**

* Disable admin url within Robots.txt

**General / Html > Emoji**

* Disable Emoji
* Disable TinyMC Emoji

**General / Html > Styles**

* Remove Version
* Remove ID from link tags

**General / Html > Scripts**

* Remove Version

**General / Html > Oembed**

* Remove Oembed

**General / Html > Headers**

* Remove Link Header
* Remove X-Powered-By Header
* Remove X-Pingback Header

**General / Html > HTML**

* Remove HTML Comments
* Minify Html, Css, JavaScript
* Disable right mouse click
* Remove general classes from body tag
* Remove ID from Menu items
* Remove class from Menu items
* Remove general classes from post
* Remove general classes from images

**Admin > wp-login.php**

* New wp-login.php - Map a new wp-login.php instead default
* Block default wp-login.php - Block default wp-login.php file from being accesible

**Admin > Admin URL**

* New Admin Url - Create a new admin url instead default /wp-admin. This also apply for admin-ajax.php calls
* Block default Admin Url - Block default admin url and files from being accesible

**CDN**

* CDN Url - Set-up CDN if apply, some providers replace site assets with custom urls.

<br />This free version works with Apache and IIS server types. 

<br />Something is wrong with this plugin on your site? Just use the forum or get in touch with us at <a target="_blank" href="https://www.wp-hide.com">Contact</a> and we'll check it out.

<br />A website example can be found at <a target="_blank" href="https://demo.wp-hide.com/">https://demo.wp-hide.com/</a> or our website <a target="_blank" href="https://www.wp-hide.com/">WP Hide and Security Enhancer</a>

<br />Plugin homepage at <a target="_blank" href="https://www.wp-hide.com/">WordPress Hide and Security Enhancer</a>

<br />
<br />This plugin is developed by <a target="_blank" href="https://www.nsp-code.com">Nsp-Code</a>

== Installation ==

1. Install the plugin through the WordPress plugins screen directly or upload the pacckage to `/wp-content/plugins/wp-hide-security-enhancer` directory.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Use the WP Hide menu screen to configure the plugin.

== Frequently Asked Questions ==

Feel free to contact us at contact@wp-hide.com for fast support.

= Does the plugin change anything on my server  =

Absolute None! 
No files and directories are being changed on your server, everything is processed virtually! The plugin code use URL rewrite techniques and WordPress filters to apply all internal functionality and features.

= I have no PHP knowledge at all, is this plugin for me? =

There's no requirements on php knowledge. All plugin features and functionality are applied automatically, controlled through a descriptive admin interface.

= Is there any demo I can check? =

A demo instance can be found at <a target="_blank" href="https://demo.wp-hide.com/">https://demo.wp-hide.com/</a> or our own website <a target="_blank" href="https://www.wp-hide.com/">WP Hide and Security Enhancer</a>

= Can I use the plugin on my Nginx server ?  =

If the server runs full-stack Nginx, the free plugin can't generate the required format Nginx rewrite rules. It works with Apache, LiteSpeed, IIS, Nginx as a reverse proxy and compatible.

= Can I still update WordPress, my plugins and themes?  =

Everything works as before, no functionality is being broken. You can run updates at any time.

= Does the plugin affect the SEO aspects of my website?  =

No, the plugin changes only assets links ( CSS, JavaScript, media files ) and not actual content URLs. There will be no negative impact from SEO perspective, whatsoever. 

= Does the plugin work with my site cache?  =

Yes, the plugin works with any cache plugin deployed on your site.

= What servers this plugin can work with =

This free code can with Apache, IIS server types and any other set-up which rely on .htaccess usage.
For all other checks the PRO version at <a target="_blank" href="https://www.wp-hide.com">WP Hide PRO</a>

= How to make it work with my OpenLiteSpeed server =

There are few things to consider when run on litespeed servers:

* Ensure the liteserver actually process the .htaccess file where the rewrite data is being saved. Check with the following topic regarding this issue <a target="_blank" href="https://www.litespeedtech.com/support/forum/threads/htaccess-is-ignored.15500/">Post</a>

* If you use Litespeed Cache plugin, in the Optimization Settings area, disable the CSS / JS Minify

* If your litespeed server requires to place the rewrite lines in a different file e.g. config file or interface, consider upgrading to PRO version which includes a Setup page where you can get the rewrite code <a href="https://www.wp-hide.com/wp-hide-pro-now-available/">WP Hide PRO</a>.


= How to use on my Bitnami setup =
As default, on Bitnami LAMP set-ups, the system will not process the .htaccess file, so none of the rewrites will work. You can change this behavior by updating the main config file located at /opt/bitnami/apps/APPNAME/conf/httpd-app.conf , update the line 
<pre><code>AllowOverride None</code></pre>
to
<pre><code>AllowOverride All</code></pre>
Restart the Apache service through ssh
<pre><code>sudo /opt/bitnami/ctlscript.sh restart</code></pre>
More details can be found at <a href="https://docs.bitnami.com/general/apps/redmine/administration/use-htaccess/">Bitnami Default .Htaccess
</a>

You can still keep the configuration as is using the <a target="_blank" href="https://www.wp-hide.com">WP Hide PRO</a>, more details at <a href="https://www.wp-hide.com/documentation/setup-the-plugin-on-bitnami-wordpress-lamp-stack/">Setup the plugin on Bitnami WordPress LAMP stack
</a>


= .htaccess file writing error - Unable to write custom rules to your .htaccess. Is this file writable? =

I'm seeing this error "Unable to write custom rules to your .htaccess. Is this file writable"  what does it mean?
The error appear when the plugin is not able to write to .htaccess file located in your WordPress root. You can try the followings to make a fix:

* Check if your .htaccess file is writable. This can be different from server to server, but usually require rw-rw-r– / 0664 Also ensure the file owner is the same group as php

* Sometimes the other codes wrongly use the flush_rules() which hijack the default filters for rewrite. Try to disable the other plugins and theme to figure out which ones produce the issue.

* De-activate and RE-activate the plugin, apparently worked for some users.

* Create a backup of .htaccess, then delete it from the server. Go to Settings > Permalinks > update once, this should create the file again on the WordPress root. If so, try to change any WP Hide options which will update the .htaccess content accordingly.

= Something is wrong, what can I do? How can I recover my site? =

* First, stay calm. There will be no harm, guaranteed :)
* Go to admin and change some of the plugin options to see which one causes the problem. Then report it to the forum or get in touch with us to fix it.
* If you can't log in to admin, use the Recovery Link which has been sent to your e-mail. This will reset the login to default.
* If you can't find the recovery link or none of the above worked, delete the plugin from your wp-content/plugins directory. Then remove any lines in your .htaccess file between 
 BEGIN WP Hide & Security Enhancer
..
 END WP Hide & Security Enhancer 

* At this point, the site should run as before. If for some reason still not working, you missed something, please get in touch with us at contact@wp-hide.com and we'll fix it for you in no time!

= I can't find a functionality that i'am looking for =

Please get in touch with us and we'll do our best to include it for a next version.

== Screenshots ==

1. Admin Interface.
2. Sample front html code.

== Changelog ==

= 1.6.3.1 =
* Improved description for Test Rewrite procedure, when the server fails to provide a valid response,  rewrite engine is not active or the custom urls are not allowd.
* Fixed Undefined Property Notice

= 1.6.3 =
* Server Environment Check to ensure there are no rewrite issues and the plugin can be safely deployed.
* Interactive feedback with hints and explanations for environment issues.
* Improved UI
* Clear fusion cache when plugin options changed if avada active
* Fix New Search Path replacement to include an end slash, to avoid catch wrong urls
* Check and tag for WordPress 5.7

= 1.6.2.5 =
* Fix: Add slash for "New Search Path" to avoid wrong replacements with urls containing the new search slug.

= 1.6.2.4 =
* Reverse URLs when saving a options, to avoid custom urls to be writted within the database.
* Check if string before making a replacement on metadata
* Compatibility file for Oxigen editor, when using Signatures
* Simple Firewall compatibility file update - check if FernleafSystems\Wordpress\Plugin\Shield\Controller\Controller class exists before apply
                                                         
= 1.6.2.0.4 =
* Update Compatibility file with Oxygen editor, for image with link wrapper
* WordPress 5.6 compatibility tag update

= 1.6.2.0.3 =
* Compatibility file with Oxygen editor

= 1.6.2.0.2 =
* Fix: Check the replacements for update_post_metadata method on text and array types.

= 1.6.2 =
* Reverse URLs when saving a meta data field, to avoid custom urls to be writted within the database.
* Trigger a system notice when deployed on MultiSite, as not being compatible.
* Don't run _init_remove_html_new_lines when AJAX call to avoid front side processing errors.
* WP Rocket compatibility file updates, to works with combined CSS assets
* Shield Security compatibility update, to works with version 10 and up.
* Prevent nottices and errors when attempt to rite on .htaccess file.
* New filter wph/components/wp_oembed_add_discovery_links to allow disabling the Remove Oembed - wp_oembed_add_discovery_links
* New filter wph/components/wp_oembed_add_host_js to allow disabling the Remove Oembed - wp_oembed_add_host_js
* New compatibility file for wePOS plugin
* New compatibility file for Asset CleanUp Pro Page Speed Booster plugin


= 1.6.1.3 =
* Compatibility with Hyper Cache
* Update JSON REST service disable, remove the json_enabled as being deprecated, rely on rest_authentication_errors filter
* Fix WooCommerce Update Database link when changing the default /wp-admin/ slug
* Fix password forget return URL
* Remove callback for Compatibility file for Shield Security within new-admin module

= 1.6.1.1 =
* Fix: Remove callback for Compatibility file for Shield Security within custom login module

= 1.6.1 =
* Compatibility file fix for Shield Security
* WordPress 5.5.1 compatibility tag

= 1.6.0.9.1 =
* Ignore CDN value check for domain name similitude

= 1.6.0.9 =
* LiteSpeed guide on Setup interface
* New functionality - Disable mouse right click
* Compatibility file - JobBoardWP
* Compatibility WP-Optimize - Clean, Compress, Cache
* WordPress 505 compatibility tag

= 1.6.0.8 =
* Avoid using domain name as replacement for any option, or might conclude to wrong replacements within the outputted HTML or wrong reversed urls.
* Add system reserved words as 'wp', 'admin', 'admin-ajax.php'
* Slight General code improvements
* Clean cookie for the new custom slug, if set.
* Integration with WP-Optimize - Clean, Compress, Cache

= 1.6.0.6 =
* WP Job Manager - compatibility update

= 1.6.0.5 =
* New Setup interface with helps and hints on how to use the plugin.
* New Sample Setup, which deploy a basic set-up of plugin options
* Remove internal wp_mail and rely on WordPress core
* Improved FAQ area
* Updated base language file

= 1.6.0.4 =
* Purge cache for Fast Velocity Minify plugin, when clearing internal cache
* Return new admin slug when calling admin_url() and if default admin is customized
* Use no-protocol when loading the files, to ensure they are being loaded over current domain protocol
* BuddyPress compatibility file update
* Elementor compatibility file update
* ShortPixel Adaptive Images compatibility file update
* WooCommerce compatibility file update
* WP Rocket compatibility file update
* New compatibility file for Fast Velocity Minify
* New compatibility file for LiteSpeed Cache
* New compatibility file for Swift Performance
* New compatibility file for WP Speed of Light

= 1.6 =
* New filter wp-hide/content_urls_replacement
* Compatibility with Ultimate Member, user picture upload fix
* Updated compatibility with W3 Cache, using postprocessorRequire option
* Fluentform compatibility updates
* Outputs the whole stack of errors for $wp_filesystem if exists
* Typo fix in Uploads module


= 1.5.9.9 =
* Updated procedure for server type identification
* Add new type text/plain for filterable content
* Add server_nginx_config to main class, to be used within other modules
* Updated rewrite quantifier for IIS from .+ to .*
* Ignore wp-content block if agent is LiteSpeed-Image

= 1.5.9.5 =
* Updated is_filterable_content_type method, return TRUE if no Content-Type header found

= 1.5.9.4 =
* Fix readme demo site protocol

= 1.5.9.4 =
* Fix "undefined method WPH_functions::get_site_module_saved_value()" when content type is text/xml

= 1.5.9.3 =
* Check for filterable buffer content type, before doing replacements, to prevent erroneously changes
* Update only URLs on XML content type
* Updated plugin demo site URL on readme file
* Compatibility update for ShortPixel Image Optimizer plugin 
* Notice possible issue for Cron block on certain servers

= 1.5.9 =
* New admin interfaces skin.
* Relocated plugin assets within a different folder for better organisator. 
* Updated mu-loader module
* Add help and hints for each options for easier understanding.
* Allow same base slug to be used for individual plugins
* Updated language file
* Check if environment file is not available and outputs admin messages
* Environment class with relocated environment json, to avoid security scanners false reports.
* Cache Enabler plugin compatibility module 
* WoodMart theme compatibility
* Compatibility module for WP Smush  and WP Smush PRO plugins
* Add the new filter available for WP Rocket to change css content
* WebARX compatibility module update
* W3 Cache module update

= 1.5.8.2 =
* Ensure base slug (e.g. base_slug/slug ) is not being used for another option to prevent rewrite conflicts
* Return correct home path when using own directory for WordPress and hosting account use the same slug in the path.
* Relocated get_default_variables() function on a higher priority execution level, to get default system details. 
* Switched Yes / No options selection, to outputs first No then Yes ( more logical )

= 1.5.8 =
* Add reserved option names to avoid conflicts e.g. wp
* Always clear any code plugin cache when plugin update
* Easy Digital Downloads compatibility
* Elementor plugin compatibility
* Fusion Builder plugin compatibility
* Divi theme compatibility updates
* WP Fastest Cache plugin compatibility updates
* Check if ob_gzhandler and zlib.output_compression before using 'ob_gzhandler' output buffering handler


= 1.5.7 =
* Autoptimize css/js cache and minify compatibility
* Wp Hummingbird and WP Hummingbird PRO assets cache compatibility

= 1.5.6.9 =
* New functionality: Remove Link Header 

= 1.5.6.8 =
* Fix: Call for invalid method WP_Error::has_errors()
* Fix: Attempt to clear Opcache if API not being restricted

= 1.5.6.7 =
* Allow internal cron jobs to run even if wp-cron.php is blocked.
* Check with wp_filesystem for any errors and output the messages, before attempt to write any content
* Trigger site cache clear on settings changed or code update
* Slight css updates
* Mark block option in red text for better visibility and user awareness

= 1.5.6.4 =
* Fix: Keep double quote at the start of the replacements when doing JSON matches to avoid replacing strings for other domains
* Fix: Run compatibility pachage for "ShortPixel Adaptive Images" only when plugin is active

= 1.5.6.3 =
* Fix: remove javascript comments produce worng replacements on specific format.

= 1.5.6.2 =
* Use curent site prefix when retreiving 'user_roles'

= 1.5.6 =
* Fix BBPress menus by calling directly the wp_user_roles option ratter get_roles()
* Replace comments within inline JavaScript code when Remove Comments active
* Possible login conflict notices when using WebArx, WPS Hide Login
* New action wp-hide/admin_notices when plugin admin notices
* Return updated url when calling admin_url instead replaced when buffer outputs to ensure compatibility with specific plugins

= 1.5.5.9 =
* Compatibility module for ShortPixel Adaptive Image plugin
* Add support for texarea fields within plugin options interface
* Fixed urls for minified files when using WP Rocket cache plugin

= 1.5.5.7 =
* Filter remove fix

= 1.5.5.6 =
* Fix log-in page when using Wp Rocket cache

= 1.5.5.5 =
* Fix admin dashboard replacements when using Wp Rocket cache

= 1.5.5.4 =
* Fix Wp Rocket cache when using Minify and Concatenation
* New functionality - Remove Admin Bar for specified roles
* Module block structure extend to support 'callback_arguments' to passThrough additional data to processing function
* Redirect the default non-pretty-url search url to customized one

= 1.5.5 =
* New component: Rewrite Author
* New component: Rewrite Search
* Show recovery link on top of page to ensure everyone can save the link to use if something goe wrong.
* Send recovery code to site admin e-mail
* Minor Code adjustments 
* Send new login url to site admin e-mail, to ensure user can recover access to dashboard if forget new slug
* Removed unused methods within WPH_module_rewrite_new_include_path component

= 1.5.4.2 =
* Fix: Undefined method for WooCommerce compatibility module

= 1.5.4.1 =
* Allow rewrite for images within admin, as being reversed to default when saving the post

= 1.5.4 =
* Compatibility re-structure, use a general module
* Compatibility fix for Shield Security wp-simple-firewall
* Removed the upload_dir filtering as produce some issues on specific environment, possible incompatibilities will be post-processed within General compatibility module
* Filter the post content on save_post action, to reverse any custom slugs like media urls, to preserve backward compatibility, in case plugin disable
* Ensure wp-simple-firewall run once when called from multiple components
* Update for Rewrite Slash component, use a rewrite conditional to ensure the code is not trigger for POST method

= 1.5.3.1 =
* Fix JSON encoded urls when using SSL

= 1.5.3 =
* Remove _relative_domain_url_replacements_ssl_sq and _relative_domain_url_replacements_ssl_dq replacements for buffer as being integrated to other variables
* Relocated upload_dir() to general functions.php to catch new content and uploads slugs.
* Use full domain url for new wp-admin slug, instead relative to avoid wrong replacements for 3rd urls
* Use full domain url for new wp-login.php,  instead relative to avoid wrong replacements for 3rd urls
* Typos fix for CDN texts
* Additional description for "Block any JSON REST calls" option to prevent Gutenberg block
* Updated rewrite for URL Slash to include a second conditional, to not trigger on POST calls

= 1.5.2.2 =
* Add trailingslashit to plugins slug to be used for replacements to avoid wrong (partial) slug changes

= 1.5.2.1 =
* Fixed upload rewrite by using default_variables['upload_url']
* WordPrss 5.0 compatibility check

= 1.5.2 =
* Updated po language file
* CDN support when using custom urls
* Moved the action replacement for wp_redirect_admin_locations at _init_admin_url()
* Trigger the action replacement for wp_redirect_admin_locations only if new admin slug exists
* Preserve absolute paths when doing relative replacements
* Populate upload_dir() data with new url if apply
* When doing reset, empty all options before fill in existing with default to ensure deprecated data is not being held anymore

= 1.5.1.2 =
* Do not redirect to new admin url unless rewrite_rules_applied()
* Generate no rewrite rules if there's no options / reset
* Removed any passed through variables when calling the do_action('wph/settings_changed') as the function can take no argument.
* Re-generate a new write_check_string on settings change to ensure if no .htacccess / web.config file is writable, it trigger correct error and flag the disable_filters variable.
* Use inline JS code confirmation for Reset Settings, in case the separate JavaScript file is not loaded caused by an rewrite issue.
* Reset confirmation message update to better inform the admin upon the procedure to follow.
* WPEngine environment check, as they do not support Apache rewrite out of the box
* Strip off protocol and any www prefix for site_url and home_url to ensure accurate comparing
* Fixed redirect url when saving the options and WordPress deployed in subfolder
* Fixed redirect url when reset all options and WordPress deployed in subfolder
* Improved compatibility for WordPress subfolder install
* Fixed some rewrite lines when WordPress installed in a path and subfolder
* Replaced the internal variable permalinks_not_applied to more intuitive custom_permalinks_applied
* Restart the buffering if flushed out, mainly used for footer when updating plugins and themes
* Add textdomain for multiple untranslated texts
* Updated PO language file
* Fixed textdomain for couple texts
* Add text to textdomain

= 1.4.9.1 =
* Updated MU Loader, if there's no plugin active avoid to receive any notice.
* Allow new wp-login.php
* PRO version available
* Check if there's a 'message' key for arguments set through wp_mail filter
* Updated po language file

= 1.4.8.2 =
* WPML compatibility when use different domains for each language
* Replaced google social as it produced some JavaScript errors.
* Do not apply the admin/login replacements if permalinks where not applied.
* Language Po file update
* Minify replaces 'Remove new line carriage'
* Minify Html, Css, JavaScript
* Options for Minify to compress different components
* Fixed conflict with Shield Security

= 1.4.7.6 =
* PHP 7.2 compatibility
* Replaced trilingslashit from the end of template url to improve compatibility with urls (e.g. JavaScript variables) which does not include an ending slash.

= 1.4.7.4 =
* WooCommerce downloadables fix when using custom slug for uploads
* Include support for admin_url() along with admin-ajax.php
* Fixed redirect link after user register.
* Use get_rewrite_base and get_rewrite_to_base for all modules to apply correct site path and any WordPress subdirectory install
* WordPress subdirectory install compatibility fix
* Improved router file processor for WordPress subdirectory installs

= 1.4.7 =
* Rewrite changes for many components
* Rewrite update for admin and login url
* Typos fix
* Compatibility for diferent environments, when WordPress deployed in a domain root, a subdirectory, or it's own folder https://codex.wordpress.org/Giving_WordPress_Its_Own_Directory

= 1.4.6.6 =
* Fixed rewrite ens slashes for wp-login.php and wp-admin components

= 1.4.6.5 =
* Fixed hardcoded wp-register.php within rewrite - root files component
* Updated components to rewrite_base / rewrite_to system
* Improved components: Rewrite - WP Includes, Rewrite - WP Content, Rewrite - Plugins, Rewrite - Uploads, Rewrite - Comments, Rewrite - Root Files, Admin - wp-login.php, Admin - Admin Url
* Typo fix environemnt to environment
* New Component - Remove Shortlink Meta
* New Component - Remove new line carriage
* Apply relative paths change on styles only if main theme / child theme rewrite slug is not empty
* Improved interface errors and warnings transient structure
* Use ABSPATH and Environemnt data to create file path for file processing, instead just ABSPATH, for better compatibility

= 1.4.5.6 =
* Prevent the wp-register.php redirect to new login page when using block
* Prepare plugin for Composer package
* URL Slash description update
* xml_rpc_path add php_extension_required validation
* File processor use ABSPATH instead DOCUMENT_ROOT environment variable to avoid different paths on certain systems
* Allow path structure to be used for New Theme Path and Child - New Theme Path

= 1.4.5.1 =
* Media Galery src images fix
* Use separate variables for holding replacements to avoid key overwrite

= 1.4.5 =
* Add replacements for urls which does not contain explicit protocol e.g. http: or https:
* Avada cache URLs replacements support
* Fix processing_order for specific root files
* Ignore wp-register.php when blocking other wp-* files
* Fixed wp-register.php block
* Check for replacements on url encoded links
* Show message notices on General/HTML -> Html for options which may interfere with themes.
* sanitize_file_path_name fix when slug include a file type extension
* Prevent redirect to new url when accessing links through www
* New component Feeds
* Windows - Global file process rewrite rules update

= 1.4.4.4 =
* If no server type identification possible, try to check for .htaccess file
* Improved .htaccess search mod, Use preg_grep for identify the begin and end of WordPress rules
* Output notice when no supported server was found
* Use separate block of rules for .htaccess file, outside of WordPress lines 
* Improved server htaccess support check
* Moved WPH_CACHE_PATH constant declaration from mu loader to wph class
* Use shutdown hock instead wp_loaded when plugin inline updated
* Use FS_CHMOD_FILE for $wp_filesystem->put_contents

= 1.4.4.2 =
* Fixed default wp-content block
* Updated compatibility with WP Fastest Cache
* Fixed wp-content replacement

= 1.4.4.1 =
* Replace the file-process file remove update

= 1.4.4 =
* New component : Robots.txt to control the outputed data
* Check if any environment variable has changed before Update static environment file
* Improved Default constants map
* File-processing check WordPress wp-load.php down the path, for custom install directory.
* Templates style clean
* Use cache for cleaned styles files
* Set HTTP_MOD_REWRITE environment variable through mod_rewrite
* Separate rewrite rules from Wordpress and use distinct block with specific marker
* Add relative .htaccess file manipulation to avoid accessing permissions when WordPress installed within a subfolder.
* Updated .po language file

= 1.4.3 =
* Tags update

= 1.4.2 =
* Replaced "Remove description header from Style file" and "Child - Remove description header from Style file" functionality

= 1.4.1 =
* Security improvments

= 1.4 =
* Fix: Allow only css files to be processed through the router to prevent other types from being displayed arbitrary.
* Mu-loader updated version
* Environment allowed path to limit css files processing
* Include _get_plugin_data_markup_translate ratter WordPress method
* Fix: replacement_exists returned wrong response since not using priority keys
* Fix: Add media replacement, use correct replacement_exists function call
* Router check for client HTTP_ACCEPT_ENCODING type to start ob_start using ob_gzhandler or not.
* Update urls dynamically within stylesheets files e.g. include '../theme-name'
* Use trailingslashit for theme / child new urls to make sure it match full url instead partial theme name (e.g. main-theme and main-theme-child)
* Block wp-register.php
* get_home_path rely on DIRECTORY_SEPARATOR for better compatibility
* Check if plugin slug actually exists within all plugins list on re_plugin_path component

= 1.3.9.2 =
* Fix: Use of undefined constant WPH_VERSION

= 1.3.9.1 =
* Fix: Child theme settings not showing up
* Use register_theme_directory if empty $wp_theme_directories
* Plugin Options validation improvements for unique slug

= 1.3.9 =
* General / Html > Meta -> new option Remove DNS Prefetch
* New component - Comments
* Fix: Updated admin urls on plugin / theme / core update page
* fix: WP Rocket url replacements for non cached pages
* Regex patterns updates for better performance and compatibility
* Fix: WP Rocket - support HTML Optimization, including Inline CSS and Inline JS

= 1.3.8.1 =
* Fix - Create mu-plugins folder if not exists

= 1.3.8 =
* WP Rocket plugin compatibility module
* Plugin loader component through mu-plugins for earlier processing and environment manage
* Fix: Plugins Update iframe styles src
* Fix: WordPress Core Update redirect url
* WP Fastest Cache plug in compatibility improvements

= 1.3.7 =
* Sanitize Admin Url for not using extension (e.g. .php) as it confuse the server upon the headers to sent
* Fix: replacements links when using custom directory for WordPress core files
* Fix: child theme path fix when changing style filename
* New Theme Path - help resource link fix
* Changed from DOMDocument to preg_replace for better compatibility with themes and plugins
* Improved execution speed

= 1.3.6.3 =
* Fixed PHP Notice: Undefined variable: dom

= 1.3.6.2 =
* W3 Total Cache - Page Cache compatibility fix
* Canonical tag replacement improvements
* Pingback tag replacement improvements
* Fix custom Background Images for body on themes which support that feature

= 1.3.6 =
* Post-process on options interface save for unique slugs on any text inputs to prevent conflicts.
* Processing Order change for new_theme_child_path to occur before new_theme_path
* New COmponent General -> Oembed
* Remove Oembed tags from header
* Remove Remove Resource Hints tags from header
* rewrite rules update to match only non base, from (.*) to (.+)
* wph-throw-404 improvements
* BuddyPress conflict handle for uploaded gravatars
* Admin Style changes
* BuddyPress Conflict Class handler
* Separate WordPress meta Generator and Other Meta Generator
* Process Location value within sent Headers list if exists
* Replacements for https and http urls relative to domain
* Add replacements for relative paths to cover WordPress installs within a folder.
* Use untralingslashit when creating theme and child theme url replacements
* Fix for Call to a member function is_404() on a non-object within wp_redirect

See full list of changelogs at https://www.wp-hide.com/plugin-changelogs/

== Upgrade Notice ==

Always keep plugin up to date.


== Localization ==
Please help and translate this plugin to your language at <a href="https://translate.wordpress.org/projects/wp-plugins/wp-hide-security-enhancer">https://translate.wordpress.org/projects/wp-plugins/wp-hide-security-enhancer</a>

Please help by promoting this plugin with an article on your site or any other place. If you liked this code or helped with your project, consider to leave a 5 star review on this board.