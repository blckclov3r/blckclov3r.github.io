<?php
/*
 * No direct access to this file
 */
if (! isset($data)) {
	exit;
}
?>
<p><?php _e('Often, our WordPress websites are loaded with elements that are not needed to load on specific pages or even everywhere.', 'wp-asset-clean-up'); ?> <?php _e('These assets (CSS &amp; JavaScript files) as well as inline code are adding up to the total size of the page, thus taking more time for the page to load.', 'wp-asset-clean-up'); ?></p>
<p><?php _e('This could end up in a slow website that leads to page abandonment, poor ranking in Google search and sometimes conflict JavaScript errors where too many scripts are loading and one of them (or more) have poorly written code that is not autonomous and badly interacts with other code.', 'wp-asset-clean-up'); ?></p>
<hr />
<p class="area-title"><?php echo sprintf(__('What %s really does?', 'wp-asset-clean-up'), WPACU_PLUGIN_TITLE); ?> <span style="font-size: 24px;"><img draggable="false" class="wpacu-emoji" alt="🚀" src="https://s.w.org/images/core/emoji/12.0.0-1/svg/1f680.svg" /></span></p>
<p><?php echo WPACU_PLUGIN_TITLE; ?> is a <strong>front-end optimisation performance plugin</strong> and let's you select the assets that are not needed to load on your website and combine the remaining loaded ones into fewer files, which will in the end reduce considerably the number of HTTP requests and optimize the front-end side of your pages. <a target="_blank" href="https://developer.yahoo.com/performance/rules.html"><?php _e('Read more', 'wp-asset-clean-up'); ?></a></p>
<p>Once the setup is completed, the pages will have a better speed score since there will be less HTTP requests (.css &amp; .js files) loaded by the browser (this can be tested using tools such as GTMetrix) and combined with a backend page caching system it will improve the page speed even more.
<p><?php _e('Page caching solutions include', 'wp-asset-clean-up'); ?>:</p>
<ul style="font-size: 14px; list-style: disc; margin-left: 25px;">
	<li style="line-height: 21px; margin-bottom: 15px;">A plugin such as <a target="_blank" href="https://www.gabelivan.com/visit/wp-rocket">WP Rocket</a> that creates static HTML files (which are lighter thanks to <?php echo WPACU_PLUGIN_TITLE; ?>) and reads them avoiding PHP code processing within the active plugins &amp; theme, including database queries which can use lots of server resources if you have high traffic. <?php echo sprintf(__('The page caching improves the %sTTFB%s (time to first byte) which measures the duration from the user or client making an HTTP request to the first byte of the page being received by the client\'s browser.', 'wp-asset-clean-up'), '<a href="http://gabelivan.com/visit/wp-rocket-ttfb">', '</a>'); ?></li>
	<li style="line-height: 21px; margin-bottom: 20px;">
        <?php echo sprintf(
                __('A hosting service that has its in-built WordPress caching like %sWPEngine%s or Kinsta, a web application accelerator like Varnish that can be setup the server etc.', 'wp-asset-clean-up'),
                '<a href="https://www.gabelivan.com/visit/wp-engine">',
                '</a>'
        ); ?>
    </li>
</ul>

<p style="line-height: normal;"><small><strong><?php _e('Disclaimer', 'wp-asset-clean-up'); ?>:</strong> <?php _e('The recommendations above are based from my own experience as a developer &amp; user and I\'m happy to recommend them to whoever wants superior WordPress performance.', 'wp-asset-clean-up'); ?> <?php _e('The links are affiliate related and I might get a commission if you decide to make a purchase.', 'wp-asset-clean-up'); ?></small></p>
<hr />
<p class="area-title">Example (Stripping ~66% of "crap") <span style="font-size: 24px;"><img draggable="false" class="wpacu-emoji" alt="✨" src="https://s.w.org/images/core/emoji/12.0.0-1/svg/2728.svg" /></span></p>
<p>Let's suppose you have a page where 30 files (CSS &amp; JS) are loaded. All have a total size of 1.5 MB. Using <?php echo WPACU_PLUGIN_TITLE; ?>, you can reduce the number to 12 files by unloading the other 18 files which are useless on the page. You've reduced the total size to 0.7 MB, this resulting in less time in downloading the assets, thus the page will load faster. If you also combine and minify the remaining 12 files, the total assets size becomes smaller to 0.5 MB. In the end, <strong>the assets will load 3 times faster and improve your page speed score</strong>. Moreover, the HTML source code will be cleaner and easier to go through in case you're a developer and need to do any debugging or just check something in the code.</p>
<hr />
<p class="area-title"><?php _e('Not sure how to configure it?', 'wp-asset-clean-up'); ?> <span style="font-size: 24px;"><img draggable="false" class="wpacu-emoji" alt="🤔" src="https://s.w.org/images/core/emoji/12.0.0-1/svg/1f914.svg" /></span></p>
<p><?php _e('No problem!', 'wp-asset-clean-up'); ?> <?php _e('You can enable "Test Mode" and any changes you make, will only be visible for you (the logged-in administrator), while the regular visitors will see the pages as if the plugin is not active.', 'wp-asset-clean-up'); ?> <?php _e('Once all is good, you can disable "Test Mode" (thus applying the settings to everyone), clear the page caching (if using a plugin or a server-side solution such as Varnish) and check out the page speed score.', 'wp-asset-clean-up'); ?> <a target="_blank" href="https://assetcleanup.com/docs/?p=84"><?php _e('Read more', 'wp-asset-clean-up'); ?></a></p>