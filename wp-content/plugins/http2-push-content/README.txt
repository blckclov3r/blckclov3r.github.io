=== HTTP/2 Push, Async JavaScript, Defer Render Blocking CSS, HTTP2 server push ===
Contributors: rajeshsingh520
Donate link: piwebsolution.com
Tags: HTTP2, Async CSS, Defer CSS, Defer JS, Async JS, Optimize
Requires at least: 4.0
Tested up to: 5.7
License: GPLv2 or later
Requires PHP: 5.4
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Stable tag: trunk

Push pre-load any resource, Async JavaScript, Defer Render Blocking CSS, with fine rule set to control js and css on different page types, HTTP2 Server push

== Description ==

* Push / Pre-load all **JS files** in site with one simple option
* Push / Pre-load all the **CSS files** in your website
* Push / Pre-load other resources throughout the site or based on the page types
* Load CSS Asynchronous or Remove any CSS file throughout the site, or there is a conditional selector that you can apply
* Async / Defer / Remove any JS file throughout the site or based on the WordPress page type

* You can create mobile device specific rule to push, pre-load, remove, async js or css, this works based on the device user agent detection

* You can create desktop device specific rule to push, pre-load, remove, async js or css, this works based on the device user agent detection

* Conditionally remove JS and CSS from various WooCommerce pages like checkout page, cart page, product page, product category page

* You can push pre-load resource specific on some pages by there ID

* Remove CSS, JS specific to page or post by there ID


https://www.youtube.com/watch?v=GHGclxgbSqI

<blockquote>
Mobile and Desktop detection works based on the wp_is_mobile() function of the WordPress that detect device based on the user agent date send in the request
</blockquote>

Apart from this it also offer ability to remove Css and JS file from specific pages based in the selected page tag conditions 

Eg: if css path is https://s.w.org/style/wp4.css

then you can match it with wp4.css or style/wp4.css or s.w.org/style/wp4.css

you use 2nd method (style/wp4.css) for more precise selection (this avoid error when there are 2 style with same file name)

== Frequently Asked Questions ==

= I want to push some resources when mobile device is used =
Yes you can do that

= I want to remove some css / js when mobile device is used =
Yes you can do that using our mobile detect rule, that works based on the browser user agent detection 

= I want to push some resource when the request is coming from desktop =
Yes you can do that using Desktop specific rule

= I want to use it for the WooCommerce related js and css file =
yes there is rules to control resources based on WooCommerce page types like product category, shop, single product, cart, checkout pages

= I want to push/pre-load resource on specific page =
you can do that there is that allows you to specify the Page id for which you want to push the resource

= I want to remove some JS and CSS specific on some page =
yes there is rule using that you can remove it for specific page by specifying there page id, e.g: 1,4,66 will select the page with id 1 ,4, and 66

= I want to remove it for whole site excluding certain pages =
ues you can do that we have rule to apply rule every where excluding the page you specified by id