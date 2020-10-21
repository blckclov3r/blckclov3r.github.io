<?php
function flying_pages_faq() {
?>
<div style="max-width:800px">
    <h3>My GTmetrix fully load time increased after installing Flying Pages. What to do?</h3>
    <p>Flying Pages starts preloading when all other resources have completed downloading and browser has become idle. It will not affect the TTFB or First Contentful Paint or Time to Interactive. If you’re worried about the fully loaded time in GTmetrix, set the "Delay to start preloading" to a higher number in settings or set it to preload only on mouse hover.</p>

    <h3>How to check if Flying Pages is working or not?</h3>
    <p>See the <a href="https://www.youtube.com/watch?v=T658UlOKdx8" target="_blank">video</a> and you'll find 'prefetch cache' (test it from an incognito window if you've enabled 'Disable for logged in admins')</p>

    <h3>I’m not seeing any improvements in GTmetrix/Pingdom/Google PageSpeed Insights</h3>
    <p>Flying Pages preload links after the website is loaded and only improves the speed on clicking links. It doesn’t help you to speed up the initial load.</p>

    <h3>Do I need a cache plugin?</h3>
    <p>Every time a page/link is preloaded, it executes some PHP code and MySQL queries which is resource-intensive. So it's highly recommended to use a cache plugin like <a href="https://wpspeedmatters.com/get/wp-rocket" target="_blank">WP Rocket</a>.</p>

    <h3>Recommended hosting provider & cache plugin?</h3>
    <p>Flying Pages works with every hosting provider, without any cache plugins. However, to get the best results, consistent performance and no downtime, our recommended hosting provider is <a href="https://wpspeedmatters.com/get/cloudways" target="_blank">Cloudways</a> and cache plugin <a href="https://wpspeedmatters.com/get/wp-rocket" target="_blank">WP Rocket</a>.</p>

    <h3>Do Flying Pages affect Google Analytics or similar tracking scripts?</h3>
    <p>Flying Pages only downloads the HTML content. It doesn't execute any code inside it. So it will not affect Google Analytics or similar scripts.</p>

    <h3>Will Flying Pages increase my bandwidth usage?</h3>
    <p>It's mostly videos and images that consume 80% of the bandwidth. Flying Pages only preloads HTML pages (which is usually <30KB) and doesn't download any resources inside it (like images, css, js). Installing Flying Pages won't increase your bandwidth usage not even by 5%.</p>

    <h3>Do Flying Pages increase server load?</h3>
    <p>In short, yes. But you can configure Flying Pages to limit the number of preloads per second or preload only on mouse hover which reduces the server load. Also, make sure to use a good hosting provider like <a href="https://wpspeedmatters.com/get/cloudways" target="_blank">Cloudways</a> and a cache plugin like <a href="https://wpspeedmatters.com/get/wp-rocket" target="_blank">WP Rocket</a>. This will reduce server load dramatically.</p>

    <h3>How to get support?</h3>
    <p>Please create a support request in the official <a href="https://wordpress.org/support/plugin/flying-pages/" target="_blank">support forum</a>. You can also get help from WP Speed Matters' <a href="https://www.facebook.com/groups/wpspeedmatters" target="_blank">Facebook group</a>.</p>
</div>
<?php
}