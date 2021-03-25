<?php
function flying_pages_settings_faq() {
?>
    <h3>What's the difference between Native and JavaScript lazy loading?</h3>
    <p>Native loading is a newly introduced feature in Chrome. It doesn't require JavaScript and is very fast since it doesn't have wait for JS execution. However native lazy loading is not supported by all browsers.</p>

    <h3>Why images are taking a few seconds to load?</h3>
    <p>For the first request to an image, it has to be resized, compressed and converted to WebP (if configured) to deliver. Subsequent requests don't need this and will be much faster.</p>

    <h3>Which images should I exclude from Lazy Loading?</h3>
    <p>To get improve the FCP (first contentful paint), FMP (first meaningful paint) etc, it's highly recommended to exclude images that are in the above fold (content that is shown without scrolling). For example logo of the site, featured image of blog posts etc.</p>

    <h3>Which images should I exclude from CDN?</h3>
    <p>If the image is already delivered via a CDN, you can exclude them. A good example is images from gravatar.com. You can still deliver it via our CDN to get browser caches, reduce DNS lookups, control responsiveness, compression etc.</p>

    <h3>Do I need a cache plugin?</h3>
    <p>Every time you open a page, the HTML is parsed by Flying Images. This consumes a little memory and CPU. So to avoid it, it's highly recommended to use a cache plugin. <a href="https://wpspeedmatters.com/get/wp-rocket" target="_blank">WP Rocket</a> is what I usually recommend.</p>

    <h3>Why some background images are not lazy loaded?</h3>
    <p>Flying Images can only process background images that are inlined in HTML like <code>style="background-image:url('...')".</code></p>

    <h3>How to get support?</h3>
    <p>If there is an issue with the plugin, please create a support request in the official <a href="https://wordpress.org/support/plugin/nazy-load/">support forum</a>. If it's something related to Statically CDN, please use their <a href="https://github.com/marsble/statically/issues">GitHub issues</a></p>.
<?php
}