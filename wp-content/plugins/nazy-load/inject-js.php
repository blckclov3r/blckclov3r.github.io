<?php
// Inject JavaScript code for lazy loading

function flying_images_inject_js() {

  $lazy_method = esc_attr(get_option('flying_images_lazymethod'));
  $lazy_margin = esc_attr(get_option('flying_images_margin'));

  if (get_option('flying_images_enable_lazyloading') != true) return;

    ?>
<script type="text/javascript" id="flying-images">"use strict";window.FIConfig={lazyMethod:"<?php echo $lazy_method; ?>",lazyMargin:<?php echo $lazy_margin; ?>};var flyingImages=function(){var a=document.querySelectorAll("[data-loading=\"lazy\"]");if(window.FIConfig.lazyMethod.includes("native")&&"loading"in HTMLImageElement.prototype)a.forEach(function(a){a.removeAttribute("data-loading"),a.setAttribute("loading","lazy"),a.dataset.srcset&&(a.srcset=a.dataset.srcset),a.src=a.dataset.src});else if(window.IntersectionObserver){var b=new IntersectionObserver(function(a){a.forEach(function(a){a.isIntersecting&&(b.unobserve(a.target),a.target.dataset.srcset&&(a.target.srcset=a.target.dataset.srcset),a.target.src=a.target.dataset.src,a.target.classList.add("lazyloaded"),a.target.removeAttribute("data-loading"))})},{rootMargin:window.FIConfig.lazyMargin+"px"});a.forEach(function(a){b.observe(a)})}else for(var c=0;c<a.length;c++)a[c].dataset.srcset&&(a[c].srcset=a[c].dataset.srcset),a[c].src=a[c].dataset.src};flyingImages();function throttle(a,b){var c=!1;return function(){c||(a.apply(null,arguments),c=!0,setTimeout(function(){c=!1},b))}}var dynamicContentObserver=new MutationObserver(throttle(flyingImages,125));dynamicContentObserver.observe(document.body,{attributes:!0,childList:!0,subtree:!0}),function(){var a=document.querySelectorAll("[data-loading=\"lazy-background\"]");if(window.IntersectionObserver){var d=new IntersectionObserver(function(a){a.forEach(function(a){if(a.isIntersecting){d.unobserve(a.target);var b=a.target.getAttribute("style"),c=b.replace("background:none;","");a.target.setAttribute("style",c),a.target.removeAttribute("data-loading")}})},{rootMargin:window.FIConfig.lazyMargin+"px"});a.forEach(function(a){d.observe(a)})}else for(var e=0;e<a.length;e++){var b=a[e].target.getAttribute("style"),c=b.replace("background:none;","");a[e].target.setAttribute("style",c),a[e].target.removeAttribute("data-loading")}}();</script>
    <?php
}

add_action( 'wp_print_footer_scripts', 'flying_images_inject_js');