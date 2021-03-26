<?php if( $active_tab == 'help' ) { ?>

<div class="fvm-wrapper">

<h2 class="title">FVM 3 Release Notes</h2>

<div class="accordion">
  <h3>Important JS and JavaScript changes</h3>
  <div>
    <p><strong>Notes:</strong></p>
    <p>JavaScript merging functionality went through a significant change on FVM 3 and it now requires manual configuration to work.</p>
	<p>If you are upgrading from FVM 2 please refer to the help section below, to understand how to reconfigure each plugin setting.</p>
	<p>If you just installed the plugin, please note that JS is not being optimized yet. You have to choose which files to be render blocking and which ones to be deferred, plus it's dependencies (if any). </p>
	<p>Previously, FVM merged everything and relied on having options to ignore scripts. This option frequently created issues with other plugin updates, when they changed something JavaScript related.</p>
	<p>Please understand that this plugin is and it has always been aimed at being a tool for advanced users and developers, so it's not meant just be plug and play, without manual settings in place.</p>
	<p>There is a new method to optimize third party scripts and load them on user interaction or automatically, after 5 seconds. This is a more recommended method to optimize scripts, as compared to FVM 2 which used document.write and other deprecated methods.</p>
	<p>Please refer to the JavaScript help section further down on this page to understand how you can optimize your scripts.</p>
  </div>
  <h3>Relevant CSS and Fonts changes</h3>
  <div>
    <p><strong>Notes:</strong></p>
    <p>You can now ignore or completely remove CSS files by URI Path or domain name (such as google fonts or other unwanted CSS files).</p>
	<p>Known fonts, icon, animation and some other CSS files now have an option to be merged separately and loaded Async.</p>
	<p>FVM now preloads the external CSS files on the header, before render blocking them later on the page.</p>
  </div>
  <h3>Relevant Cache changes</h3>
  <div>
    <p><strong>Notes:</strong></p>
    <p>Purging cache on FVM, renames the file name in order to bypass CDN and browser cache, however, expired CSS and JS cache files are only deleted 24 hours (by default) after your last cache purge request.</p>
	<p>This is needed because some hosting services can cache your HTML regardless of your cache purge request. If we were to delete the FVM cached files right away, it would break your layout for anonymous users, as the files would no longer exist but your page would still be referencing them.</p>
  </div>
 <h3>Other changes</h3>
  <div>
    <p><strong>Notes:</strong></p>
	<p>Preconnect and Preload Headers have been removed (please use your own PHP code and conditional tags for that).</p>
	<p>Critical Path CSS option has been removed (add your own code with code and <code>&lt;style id=&quot;critical-path&quot;&gt;</code> your code &lt;/style&gt; ).</p>
  </div>
</div>


<div style="height: 20px;"></div>
<h2 class="title">Global Settings</h2>

<div class="accordion">
  <h3>Purge Minified CSS/JS files instantly</h3>
  <div>
    <p><strong>Notes:</strong></p>
    <p>If your hosting has no page cache enabled, you can force the plugin to immediately purge it's cache files instead of waiting for 24 hours before deletion.</p>
  </div>
  <h3>Preserve settings on uninstall</h3>
  <div>
    <p><strong>Notes:</strong></p>
    <p>When you are testing things, sometimes you may want to preserve all FVM settings when you uninstall or delete the plugin.</p>
  </div>
  <h3>Force HTTPS urls on merged files</h3>
  <div>
    <p><strong>Notes:</strong></p>
    <p>This will make sure that when FVM generates a CSS and JS file, that it's linked on your page with https.</p>
  </div>
</div>

<div style="height: 20px;"></div>
<h2 class="title">HTML Settings</h2>

<div class="accordion">
  <h3>Enable HTML Processing</h3>
  <div>
    <p><strong>Notes:</strong></p>
    <p>You need to enable this option, for any other options in the HTML section to work.</p>
  </div>
  <h3>Disable HTML Minification</h3>
  <div>
    <p><strong>Notes:</strong></p>
    <p>Although rare, it's possible that HTML minification may strip too much code thus breaking something.</p>
	<p>You can use this option to test if that is the case.</p>
  </div>
  <h3>Strip HTML Comments</h3>
  <div>
    <p><strong>Notes:</strong></p>
    <p>Some plugins may need to use comments for certain functionality to work, however this is quite rare. This option is enabled by default.</p>
  </div>
  <h3>Cleanup Header</h3>
  <div>
    <p><strong>Notes:</strong></p>
    <p>This options removes resource hints, generator tag, shortlinks, emoji, manifest link, etc from the HTML header.</p> 
	<p>This is recommended because the head section, should be kept as lean as possible for the best TTFB response times and LCP metrics.</p>
  </div>
  <h3>Remove Emoji</h3>
  <div>
    <p><strong>Notes:</strong></p>
    <p>This will remove the default emoji scripts from wordpress, thus reducing the amount of code during page loading.</p>
  </div>
</div>


<div style="height: 20px;"></div>
<h2 class="title">CSS Settings</h2>

<div class="accordion">
  <h3>Enable CSS Processing</h3>
  <div>
    <p><strong>Notes:</strong></p>
    <p>You need to enable this option, for any other options in the CSS section to work.</p>
  </div>
  <h3>Disable CSS Minification</h3>
  <div>
    <p><strong>Notes:</strong></p>
    <p>Although rare, it's possible that CSS minification may strip too much code thus breaking your styles.</p>
  </div>
  <h3>Disable Merging and Inline all CSS</h3>
  <div>
    <p><strong>Notes:</strong></p>
    <p>If your total CSS code is very small (under 25 Kb uncompressed), it's recommended to inline all CSS.</p>
	<p>If your CSS files combined are large, inlining it will delay the time needed for the first byte, as you are forcing the server to compress the HTML and the CSS on the same process.</p>
  </div>
  <h3>Remove "Print" stylesheets</h3>
  <div>
    <p><strong>Notes:</strong></p>
    <p>As a generic rule, it's safe to remove it for the vast majority of sites, unless your users often need to print pages from your site, and you have customized styles for when they do so.</p>
  </div>
  <h3>Merge Fonts and Icons Separately</h3>
  <div>
    <p><strong>Notes:</strong></p>
    <p>This will try to collect all your icon and animation know files into a separate file.</p>
	<p>It may be useful for debugging purposes or to evaluate how many fonts are in use.</p>
  </div>
  <h3>Load generated CSS files Async</h3>
  <div>
    <p><strong>Notes:</strong></p>
    <p>This will load the generated CSS files Async, however, without a manually added critical path code, you will likely see a Flash of Unstyled Content before that CSS finishes loading.</p>
	<p>Use your own PHP code or another plugin to add the critical path CSS code using conditional tags, filters, hooks or other method.</p>
  </div>
  <h3>Ignore CSS files</h3>
  <div>
    <p><strong>Notes:</strong></p>
    <p>You can use this option to prevent a certain CSS file from being merged, for example, when it breaks something when merged.</p> 
	<p>This uses uses PHP stripos against the href attribute on the link tag, to decide if a CSS should be left alone or not.</p>
	<p>This should be empty by default, and only used strictly when a CSS being merged is breaking the page layout.</p>
  </div>
  <h3>Remove CSS files</h3>
  <div>
    <p><strong>Notes:</strong></p>
    <p>If you wish to remove a CSS file from the frontend without editing code or using other settings, you can use this option.</p>
	<p>For example, you can remove google fonts link tags, or the default Gutenberg CSS file enqueued by WordPress.</p>
	<p>You should still first try to remove it or dequeue it with PHP code, when possible.</p>
  </div>
</div>


<div style="height: 20px;"></div>
<h2 class="title">JS Settings</h2>

<div class="accordion">
  <h3>Enable JS Processing</h3>
  <div>
    <p><strong>Notes:</strong></p>
    <p>You need to enable this option, for any other options in the JS section to work.</p>
  </div>
  <h3>Disable JS Minification</h3>
  <div>
    <p><strong>Notes:</strong></p>
    <p>Although rare, it's possible that JS minification may strip too much white space, or fail to minify some more complex JavaScript  code, which can lead to breaking some functionality or triggering browser console log errors.</p>
	<p>You can use this option to test if that is the case.</p>
  </div>
  <h3>Upgrade to jQuery 3</h3>
  <div>
    <p><strong>Notes:</strong></p>
    <p>If your theme and plugins make use of modern standards and doesn't include outdated jQuery code, you can force the usage of the smaller jQuery 3 instead of the older, default WordPress jQuery.</p>
	<p>You must check your browser console log in incognito mode, for possible errors after enabling this feature. Sometimes there are no errors but some scripts may not work as well, so use this feature with care.</p>
  </div>
  <h3>Ignore Script Files</h3>
  <div>
    <p><strong>Notes:</strong></p>
    <p>When you are adding a lot of JS files to consider, it may be more convenient to add the default recommended paths and exclude individual files.</p>
	<p>This should be empty by default, and only used strictly when a CSS being merged is breaking the page layout.</p>
  </div>
  <h3>Merge render blocking JS files in the header</h3>
  <div>
    <p><strong>Notes:</strong></p>
    <p>In most WordPress themes and for a significant amount of plugins, you need to render block jQuery and possibly other scripts.</p> 
	<p>If you are a developer and are sure that there is no inlined code requiring jQuery to be defined earlier, then leave this section empty and add your scripts on the "Merge and Defer Scripts" section instead.</p>
	<p>It's important for speed, that you keep this section to a bare minimum, usually, jQuery and jQuery migrate only.</p>
	<p>Some plugins such as gravity forms may also require to be render blocking, so you should look out for browser console log errors in incognito mode.</p>
	<p><strong>Recommended Default Settings:</strong></p>
	<p class="fvm-code-full">
	/jquery-migrate-<br>
	/jquery-migrate.js<br>
	/jquery-migrate.min.js<br>
	/jquery.js<br>
	/jquery.min.js<br>
	</p>
  </div>
  <h3>Merge and Defer Scripts</h3>
  <div>
    <p><strong>Notes:</strong></p>
    <p>This option uses PHP stripos against the script <code>outerHTML</code> to decide what to merge and defer.</p>
	<p>Very frequently, jQuery, jQuery migrate and some plugins, need to be render blocking for everything to work, so you cannot just put all scripts in this section.</p>
	<p>You must check your browser console log in incognito mode, for possible errors after enabling this feature, and either move them to the header, remove them from this list (be more specific with paths so it doesn't match certain files), or use the Inline JavaScript Dependencies section, to force inline scripts to wait for this file to load.</p>
	<p>Note that this is an advanced feature that can stop your scripts from working, hence it requires manual configuration.</p>
	<p><strong>Recommended Default Settings:</strong></p>
	<p class="fvm-code-full">
	/ajax.aspnetcdn.com/ajax/<br>
	/ajax.googleapis.com/ajax/libs/<br>
	/cdnjs.cloudflare.com/ajax/libs/<br>
	/stackpath.bootstrapcdn.com/bootstrap/<br>
	/wp-admin/<br>
	/wp-content/<br>
	/wp-includes/
	</p>
  </div>
  <h3>Inline JavaScript Dependencies</h3>
  <div>
    <p><strong>Notes:</strong></p>
    <p>This option uses PHP stripos against the script <code>innerHTML</code> to decide what to merge and defer.</p>
	<p>When you merge JS files and defer them, you are effectively changing the order in which they load, however for certain scripts, the order of loading matters.</p>
	<p>If you defer a certain script and you see an "undefined" error triggered on some inline code, you can try this option to force it to wait for the deferred scripts to finish, but it may still not work if dependencies are more complex.</p>
	<p>You must check your browser console log in incognito mode, for possible errors after enabling this feature, and either move them to the header, remove them from this list (be more specific with paths so it doesn't match certain files), or use the Inline JavaScript Dependencies to force inline scripts to wait for this file to load.</p>
	<p>This is empty by default, unless you determine that it's needed (the plugin is for advanced users and developers, so you need to debug yourself).</p>
  </div>
  <h3>Execute matching third party scripts after user interaction</h3>
  <div>
    <p><strong>Notes:</strong></p>
	<p>Scripts like analytics, ads, tracking codes, etc, consume important CPU and Network resources needed for the initial pageview.</p>
	<p>This option uses PHP stripos against the script <code>innerHTML</code> or <code>src</code> attribute for async/defer scripts.</p>
	<p>It will delay the specified script execution until the user interacts with the page, on the first <code>'mouseover','keydown','touchmove','touchstart'</code> event, or <code>up to 5 seconds after page load</code>(whichever happens first).</p>
	<p>FVM will delay most Async and Defer scripts, a well as inline code matching these settings.</p>
	<p>You can rewrite most scripts to support this feature by adding the Async or Defer attribute, however, note that if you blindly use this method for render blocking scripts, it may trigger "undefined" errors on the browser console log or some elements may stop working (some scripts only work in render blocking mode).</p>
	<p>If you have render blocking third party scripts, ask your provider if they can provide you with an async implementation (else remove them, because render blocking scripts are not recommended for speed).</p>
	<p><strong>Example Settings:</strong></p>
	<p class="fvm-code-full">
	function(w,d,s,l,i)<br>
	function(f,b,e,v,n,t,s)<br>
	function(h,o,t,j,a,r)<br>
	www.googletagmanager.com/gtm.js<br>
	gtag(<br>
	fbq(<br>
	</p>
  </div>
  <h3>Remove JavaScript Scripts</h3>
  <div>
    <p><strong>Notes:</strong></p>
    <p>This option uses PHP stripos against the script <code>outerHTML</code> to decide what to remove.</p>
	<p>It can be used when you want to remove some JS file that you cannot remove directly from the source.</p>
	<p>If it's a third party script you added to the header or footer (or via some plugin), it's better if you delete it at the source.</p>
  </div>
</div>



<div style="height: 20px;"></div>
<h2 class="title">CDN Settings</h2>

<div class="accordion">
  <h3>Enable CDN Processing</h3>
  <div>
    <p><strong>Notes:</strong></p>
    <p>You need to enable this option, for any other options in the CDN section to work.</p>
  </div>
  <h3>Enable CDN for merged CSS files</h3>
  <div>
    <p><strong>Notes:</strong></p>
    <p>When selecting this option, FVM will replace your domain name with the CDN domain, for the generated CSS cache file.</p>
	<p>Under certain situations, you may not want to serve the CSS file from the CDN, such as when your server compression level is significantly higher than the CDN (smaller file than the one delivered by the CDN).</p>
	<p>Also bare in mind, that if the CSS file is served from the CDN, any static assets inside the CSS file that make use of relative paths, will also be cached and served from the CDN, which may also be undesirable in certain situations.</p>
  </div>
  <h3>Enable CDN for merged JS files</h3>
  <div>
    <p><strong>Notes:</strong></p>
    <p>When selecting this option, FVM will replace your domain name with the CDN domain, for the generated JS cache file.</p>
	<p>Under certain situations, you may not want to serve the JS file from the CDN, such as when your server compression level is significantly higher than the CDN (smaller file than the one delivered by the CDN).</p>
	<p>Also bare in mind, that if the JS file is served from the CDN, any static assets inside the JS file that make use of relative paths, will also be cached and served from the CDN, which may also be undesirable in certain situations.</p>
  </div>
  <h3>CDN URL</h3>
  <div>
    <p><strong>Notes:</strong></p>
    <p>This is not required for providers such as cloudflare.com or sucuri.com as well as any reverse proxy CDN service that doesn't change your domain name (the whole site is proxified through their service).</p>
	<p>For other types of CDN, you are usually provided with an alternative domain name from where your static files can be served, and in those cases, you would introduce your new domain name here, for your static assets.</p>
  </div>
  <h3>CDN Integration</h3>
  <div>
    <p><strong>Notes:</strong></p>
    <p>Uses syntax from <a target="_blank" href="https://simplehtmldom.sourceforge.io/manual.htm">https://simplehtmldom.sourceforge.io/manual.htm</a> for modifying the urls on the HTML.</p>
	<p>The plugin will only replace your site domain, with the CDN domain for the matching HTML tags.</p>
	<p><strong>Recommended Default Settings:</strong></p>
	<p class="fvm-code-full">
	a[data-interchange*=/wp-content/]<br>
	image[height]<br>
	img[src*=/wp-content/], img[data-src*=/wp-content/], img[data-srcset*=/wp-content/]<br>
	link[rel=icon]<br>
	picture source[srcset*=/wp-content/]<br>
	video source[type*=video]
	</p>
  </div>
</div>


<div style="height: 20px;"></div>
<h2 class="title">Cache Settings</h2>

<div class="accordion">
  <h3>Public Cache Path</h3>
  <div>
    <p><strong>Notes:</strong></p>
    <p>This can be any writeable directory or mounting point on your server, as long as it's mapped to be served on publicly available URL.</p>
	<p>Should be left empty by default, unless your hosting blocks writing files to the default cache directory.</p>
  </div>
  <h3>Public Cache URL</h3>
  <div>
    <p><strong>Notes:</strong></p>
    <p>This must be a publicly available URL on your site, where users can download the generated js/css files.</p>
	<p>Should be left empty by default, unless your hosting blocks writing files to the default cache directory.</p>
  </div>
</div>

<div style="height: 20px;"></div>
<h2 class="title">User Settings</h2>

<div class="accordion">
  <h3>User Options</h3>
  <div>
    <p><strong>Notes:</strong></p>
    <p>This will allow you to force CSS, HTML and JS processing for specific user roles.</p>
	<p>By default, only anonymous users should be optimized, to ensure that there is nothing broken for logged in users (unless you know what you are doing).</p>
  </div>
</div>



<div style="height: 20px;"></div>
<h2 class="title">Other FAQ's</h2>

<div class="accordion">
  <h3>Is the plugin GDPR compatible?</h3>
  <div>
    <p><strong>Notes:</strong></p>
    <p>FVM does not collect any information from you, your site or your users. It also doesn't require cookies to work, therefore it's fully GDPR compatible.</p>
  </div>
  <h3>How do I know if the plugin is working?</h3>
  <div>
    <p><strong>Notes:</strong></p>
    <p>For compatibility reasons, the plugin only optimizes anonymous users by default. That means, you need to open another browser window, or use incognito mode to test and see what it's doing. Logged in users will not see the optimizations, unless you manually enable certain user roles (not recommended for complex websites, unless you know what you are doing).</p> 
  </div>
  <h3>How do I purge the cache?</h3>
  <div>
    <p><strong>Notes:</strong></p>
    <p>Please note that FVM is not a page cache plugin and it doesn't cache your content. The only time you should need to purge it's cache, is when you edit a css or js file.</p>
	<p>If your HTML page is being cached somewhere else, you must purge your cache either, unless FVM supports it natively.</p> 
  </div>
  <h3>Why am I getting 404 error not found for the generated CSS or JS files?</h3>
  <div>
    <p><strong>Notes:</strong></p>
    <p>You deleted the FVM cache but forgot to purge the HTML page cache, or you are lacking writing permissions on the cache directory and files are not being created. </p>
	<p>You must purge your page cache either on some other cache plugin or at your server/hosting level for your page to update and find the latest merged file paths.</p>
	<p>Note that some hosts rate limit the amount of times you can purge caches to once every few minutes, so you may be purging and it doesn't work, because you are being rate limited by your hosting cache system.</p>
	<p>Avoid doing development on live sites and use a staging server without cache for testing. Production servers are for deploying once and leave it until the next deployment cycle.</p>
  </div>
  <h3>Why is my site layout broke after an update, a configuration change, or some other change?</h3>
  <div>
    <p><strong>Notes:</strong></p>
    <p>You must check your browser console log in incognito mode, for possible errors after enabling certain features, deferring scripts or using some other optimization plugins, which may be conflicting with FVM optimization.</p>
	<p>If there are no errors, disable each option one by one on FVM (html processing, css processing, js processing, etc) until you find the feature breaking it. After that, adjust and tweak those settings accordingly, or hire a developer to help you.</p>
  </div>
  <h3>How can I download an older version of FVM for testing purposes?</h3>
  <div>
    <p><strong>Notes:</strong></p>
	<p>It's not recommended you do that, but if you want to test something, you can do so from the <a target="_blank" href="https://plugins.svn.wordpress.org/fast-velocity-minify/tags/">SVN repository</a> on WordPress.</p>
  </div>
  <h3>How do I undo all optimizations done by FVM?</h3>
  <div>
    <p><strong>Notes:</strong></p>
    <p>Simply disable the plugin, and make sure to purge all page caches (Cache Plugins, Hosting, OPCache, etc).</p>
	<p>Note that some hosts rate limit the amount of times you can purge caches to once every few minutes, so you may be purging and it doesn't work, because you are being rate limited by your hosting cache system. If that happens, jsut ask your hosting to manually purge all caches on the server.</p>
	<p>FVM does not modify your site. It runs after your template loads and filters the final HTML to present it to your users, in a more optimized way.</p>
  </div>
  <h3>I have disabled FVM but somehow the cache files are still being generated?</h3>
  <div>
    <p><strong>Notes:</strong></p>
    <p>If you have disabled the plugin and purged all caches available, this is simply not possible.</p>
	<p>Please ensure you have delete the plugin on the correct site location and that all caches are emptied.</p>
	<p>A few hosting providers will cache your disk in memory to speed things up when they use remote disk locations, which may cause code to be cached even if you have deleted it completely from the disk. In that case, restart the server or ask your hosting to purge all disk caches.</p>
  </div>
  <h3>Where can I get support or ask questions about the plugin?</h3>
  <div>
    <p><strong>Notes:</strong></p>
    <p>You can ask for help on <a href="https://wordpress.org/support/plugin/fast-velocity-minify/">https://wordpress.org/support/plugin/fast-velocity-minify/</a> but please note we cannot guide you on which files to merge or how to solve JavaScript conflicts. You need to try different settings (trial and error) and open a separate window in incognito mode, to look for console log errors on your browser, and adjust settings as needed.</p>
  </div>
  <h3>How is it possible that some scan is showing malware on the plugin?</h3>
  <div>
    <p><strong>Notes:</strong></p>
    <p>I guarantee that the plugin is 100% clean of malware, provided you have downloaded the plugin from the official WordPress source.</p>
	<p>Understand that Malware can infect any plugin you have on your site (even security plugins), regardless of the point of entry. Sometimes it propagates to/from different areas (including other sites you may have on the same server). </p>
	<p>If there is any malware on any scripts being merged by FVM, they would be merged as they are until your next cache purge. </p>
	<p>If you are seeing malware on any file related to FVM, simply delete the plugin, purge all cache files and make sure to only download the plugin from the official source on wordpress.org or via wp-admin.</p>
  </div>
  <h3>How do I report a security issue or file a bug report?</h3>
  <div>
    <p><strong>Notes:</strong></p>
    <p>If you are sure it's a bug and not a misconfiguration specific to your site, thank you for taking the time to report it.</p>
	<p>You can contact me on <a href="https://fastvelocity.com/">https://fastvelocity.com/</a> using the contact form.</p>
	<p>You are also welcome to submit patches and fixes via <a href="https://github.com/peixotorms/fast-velocity-minify">https://github.com/peixotorms/fast-velocity-minify</a> if you are a developer.</p>
	
  </div>
  <h3>I'm not a developer, can I hire you for a more complete speed optimization?</h3>
  <div>
	<p>You can contact me on <a href="https://fastvelocity.com/">https://fastvelocity.com/</a> using the contact form, providing me your site URL and what issues you are trying to fix, for a more exact quote.</p>
	<p>My speed optimization starts from $500 for small sites and from $850 for woocommerce and membership sites.</p>
	<p>I do not use the free FVM for my professional work, but I guarantee as best performance as possible for your site content.</p>
  </div>
  <h3>How can I donate to the plugin author?</h3>
  <div>
    <p><strong>Notes:</strong></p>
    <p>While not required, if you are happy with my work and would like to buy me a <del>beer</del> green tea, you can do it via PayPal at <a target="_blank" href="https://goo.gl/vpLrSV">https://goo.gl/vpLrSV</a> and thank you in advance :)</p> 
  </div>
</div>


</div>
<?php 
}
