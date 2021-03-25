=== Shift8 CDN ===
* Contributors: shift8
* Donate link: https://www.shift8web.ca
* Tags: cdn, free cdn, speed, performance, wordpress cache, content delivery network, free, free content delivery, free content delivery network
* Requires at least: 3.0.1
* Tested up to: 5.6
* Stable tag: 1.55
* License: GPLv3
* License URI: http://www.gnu.org/licenses/gpl-3.0.html

This is a plugin that integrates a 100% free CDN service operated by Shift8, for your Wordpress site. What this means is that you can simply install this plugin, activate and register with our CDN service and all of your static assets on your website will be served through our global content delivery network.

== Community Support ==

If you need support, please head over to our [community discourse forum](https://discourse.shift8cdn.com) for you to more easily access support resources.

== Check out our new Shift8 CDN website ==

You can now create your own dashboard account to add/remove/manage your sites : [Shift8 CDN](https://shift8cdn.com). Note if you have a site created prior to this dashboard, you can submit a request on the support forums and we can migrate the site for you.

== Instructions for setup ==

1. Register for an account by [CLICKING HERE](https://shift8cdn.com/register)
2. Once your account is activated, [go to the Shift8 CDN dashboard](https://shift8cdn.com/dashboard) and click "Create Site"
3. Enter your site URL exactly as it appears (i.e. https://www.yoursite.com) and click "Add"
4. Once added successfully, click "View" to view the site details and copy the API key and CDN prefix 
6. Install this Wordpress plugin and activate
7. Go to the plugin settings page (Shift8 > CDN Settings) and enter the site url, API key and CDN prefix and then click "Save Changes"
8. Once saved, you can click the "Check" button to ensure everything matches with our system
9. Click the "Test URL" to ensure it actually works and if so , click "Enable Shift8 CDN" at the top of the settings page to enable. You can use this to quickly disable if there are any problems. 

== Instructions for upgrading from a version prior to 1.30 ==

1. It is important before you upgrade the plugin to disable the CDN and unregister your site. Your site needs to be unregistered before upgrading, otherwise we will have to manually migrate the site for you
2. Once the "Enable Shift8 CDN" setting is disabled, and your site is unregistered, update the plugin via the Wordpress plugin update system
3. Once the plugin is updated, head to our site to [register your account](https://shift8cdn.com/register) on our systems
4. Enter your site URL exactly as it appears (i.e. https://www.yoursite.com) and click "Add"
5. Once added successfully, click "View" to view the site details and copy the API key and CDN prefix
6. Go back to the plugin settings page (Shift8 > CDN Settings) and enter the site url, API key and CDN prefix and then click "Save Changes"
7. Once saved, you can click the "Check" button to ensure everything matches with our system
8. Click the "Test URL" to ensure it actually works and if so , click "Enable Shift8 CDN" at the top of the settings page to enable. You can use this to quickly disable if there are any problems.

== Newly added entpoints ==

New endpoints added : 

- Frankfurt, Germany
- Ukraine

== Free Content Delivery Network for your Static Content ==

[Shift8](https://www.shift8web.ca) has rolled out a consistently-growing CDN with endpoints all over the world. This plugin will make your site load way faster by using latency-based and geographic-based DNS resolution for requests made to your site to be served by an endpoint closest to the user making the request. This means that a user making a request in Mumbai, India will hit the Mumbai server to download the static content from your website, improving performance remarkably.

Current endpoints in use (more added regularly) :

1. USA - Northern California
2. USA - Northern Virginia
3. USA - Dallas, TX
4. USA - Miami, FL
5. Canada - Toronto
6. Europe - London, England 
7. Europe - Stockholm, Sweden
8. Europe - Warsaw, Poland
9. Europe - Frankfurt, Germany
10. Europe - Ukraine
11. Asia Pacific - Hong Kong, China
12. Asia Pacific - Tokyo, Japan
13. Asia Pacific - Sydney, Australia
14. Asia Pacific - Singapore
15. Asia Pacific - Mumbai, India
16. Latin America - Sao Paulo, Brazil

You can learn more about how the CDN was setup and how it works by reading our [blog post](https://www.shift8web.ca/2019/05/how-we-created-our-own-free-content-delivery-network-for-wordpress-users/).

== Want to see the plugin in action? ==

You can view three example sites where this plugin is live :

- Example Site 1 : [Wordpress Hosting](https://www.stackstar.com "Wordpress Hosting")
- Example Site 2 : [Web Design in Toronto](https://www.shift8web.ca "Web Design in Toronto")

== Features ==

- 100% Free CDN for static assets on your site (CSS, JS, Images, Font files and more)
- Geographic and latency based DNS routing of requests to the nearest endpoint server across the globe
- Super easy set up : just install plugin, activate and register to start using within minutes.

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload the plugin files to the `/wp-content/plugins/shif8-cdn` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Navigate to the plugin settings page and define your settings
4. Hit the "Register" button to register your site with our CDN network.
5. Within a few minutes the change will propagate to all our systems across the network.

== Frequently Asked Questions ==

= I tested it on my site and its not working for me! =

Send us an email to cdnhelp@shift8web.com or post in the support forums here and we will help. We are constantly improving and updating the plugin also!

= I noticed on lazy load images, the CDN isnt being used =

This is a known issue with how lazy loading is implemented in some scenarios. Currently if you want the CDN to be used for lazy load images, you have to turn lazy load off.

== Screenshots ==

1. Geographic Endpoints for Content Delivery Network
2. Admin area settings 
3. Before / After CDN performance improvement, taken from Pingdom

== Changelog ==

= 1.00 =
* Stable version created

= 1.01 =
* Wordpress 5 compatibility

= 1.02 =
* Got rewrite working for CDN

= 1.03 =
* Cleanup

= 1.04 =
* Fixed str_replace function

= 1.05 =
* Now rewriting wp-includes static assets

= 1.06 =
* Updated readme

= 1.07 =
* Added DNS prefetch for CDN

= 1.08 =
* Added on/off switch to assist troubleshooting

= 1.09 =
* Fixed bug in ruleset where undefined constant was being used

= 1.10 =
* Added endpoints and instructional message on settings

= 1.11 =
* Updated success register message, hover on register button, incorporate test URL before enabling

= 1.12 =
* Fixed bug with register ajax query

= 1.13 =
* Expanded API queries and settings for plugin. Added tabbed settings, support tab as well as feature to check and synchronize settings and delete your CDN

= 1.14 =
* Fixed bug in static rules

= 1.15 =
* Added version query to admin static elements to force refresh on further updates

= 1.16 =
* Fixed bug with unregistered initial install

= 1.17 =
* Fixed bug to not accidentally stripping URI when replacing urls with CDN for those sites in sub-folders

= 1.18 = 
* Fixed bug on url validation for registration

= 1.19 =
* Force save of email in case user hits register without saving

= 1.20 =
* Fixed bug in ajax query processing of data returned. Added progress bar animation for interactions with CDN API. Added more clear instructions for first registering.

= 1.21 =
* Fixed bug with static constant of API URL

= 1.22 =
* Rewriting all wp-content URLs which weren't catching every static element prior

= 1.25 =
* Added option in admin settings for choosing which type of static files you want the CDN to serve

= 1.26 =
* Included webp extensions for media file CDN rewrite function

= 1.30 =
* Removed register API query
* Removed email field in settings
* Added instructions in settings area if unregistered
* Updated readme with new instructions

= 1.31 =
* Updated instruction note in settings page for registration

= 1.32 =
* Added a debug tab in the settings to allow gathering of debug information to assist in troubleshooting problems

= 1.33 =
* Removed apache and mysql info gathering for debug info tab

= 1.34 =
* Moved debug info into support tab

= 1.35 =
* Added ability to submit a purge request across the CDN network from the plugin settings page

= 1.36 =
* Implemented a function to submit CDN cache purge request from plugin settings page

= 1.37 =
* Wordpress 5.4 support

= 1.38 =
* Updated test image

= 1.39 =
* Updated test image

= 1.40 =
* Site url now supports folders / paths

= 1.41 =
* Added support for SVG files

= 1.42 =
* Updated help to utilize tooltips, Scheduled cron task to poll and check CDN account status, Utilization of premium CDN endpoints with logic to switch between the two.

= 1.43 =
* Improved url rewriting function to accommodate forcing lazy loading of images to go through the CDN
* Will not instantiate the rewrite class unless the plugin enable option is set

= 1.44 =
* Minor update for free CDN hostname

= 1.45 =
* Fix for error handling on manual check / poll with api

= 1.46 =
* Centralize get_transient for api checks
* Improved conditional logic to properly rewrite suffix host

= 1.47 =
* Added WP-CLI command to enable/disable CDN

= 1.48 =
* Resolved issue with missing file

= 1.49 =
* Improved rewriting mechanism for CDN host rewrite, exclude file list now functioning.

= 1.50 =
* Implementation of WP-CLI cache flush command. You can now submit a purge request via the command line.

= 1.51 =
* Fixed problem with custom WP-CLI command registering

= 1.52 =
* Fixed edge case exception with admin JS textarea for hidden path field in CDN settings

= 1.53 =
* Fixed bug with image src rewriting to CDN urls

= 1.54 =
* Fixed stripping of query string for CDN urls when rewritten. 

= 1.55 =
* New dash icon for WP Dashboard