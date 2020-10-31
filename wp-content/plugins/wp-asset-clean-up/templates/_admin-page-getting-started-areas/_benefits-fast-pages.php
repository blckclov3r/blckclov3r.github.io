<?php
/*
 * No direct access to this file
 */
if (! isset($data)) {
	exit;
}
?>
<p class="area-title"><?php _e('Higher search ranking', 'wp-asset-clean-up'); ?> <span style="font-size: 22px;"><img draggable="false" class="wpacu-emoji" alt="📈" src="https://s.w.org/images/core/emoji/12.0.0-1/svg/1f4c8.svg" /></span></p>
<p><?php _e('Since 2010, there has been a signal in Google search ranking algorithms: site speed, which reflects how quickly a website responds to web requests.', 'wp-asset-clean-up'); ?></p>
<p><?php _e('Speeding up websites is important — not just to site owners, but to all Internet users.', 'wp-asset-clean-up'); ?> <?php _e('Faster sites create happy users and Google has seen in their internal studies that when a site responds slowly, visitors spend less time there.', 'wp-asset-clean-up'); ?> <?php _e('But faster sites don\'t just improve user experience', 'wp-asset-clean-up'); ?>; <?php _e('recent data shows that improving site speed also reduces operating costs.', 'wp-asset-clean-up'); ?> <?php _e('Like Google, their users place a lot of value in speed — that\'s why they\'ve decided to take site speed into account in their search rankings. They use a variety of sources to determine the speed of a site relative to other sites.', 'wp-asset-clean-up'); ?>
<p><span class="dashicons dashicons-video-alt3"></span> <a href="https://www.youtube.com/watch?v=SO4YuDAkplU" target="_blank"><?php _e('How does Google determine page speed?', 'wp-asset-clean-up'); ?></a></p>
<hr />

<p class="area-title"><?php _e('Visitor Experience', 'wp-asset-clean-up'); ?> <span style="font-size: 24px;"><img draggable="false" class="wpacu-emoji" alt="😊" src="https://s.w.org/images/core/emoji/12.0.0-1/svg/1f60a.svg" /></span></p>
<p><?php _e('For a customer (it\'s likely happened to you too) that wants to purchase something online, it\'s very frustrating to land on slow loading website.', 'wp-asset-clean-up'); ?> <?php _e('A blazing fast website, will keep your visitors happy, engaged, which will directly influence conversions.', 'wp-asset-clean-up'); ?> <?php _e('If a visitor doesn\'t get what he wants in a time he/she thinks it\'s reasonable, they will probably head to another website belonging to a competitor.', 'wp-asset-clean-up'); ?> <?php _e('As today\'s users expect a fast and streamlined web experience, you\'re losing business if you neglect this often overlooked aspect.', 'wp-asset-clean-up'); ?></p>
<hr />

<p class="area-title"><?php _e('Better Developer Experience', 'wp-asset-clean-up'); ?> <span style="font-size: 24px;"><img draggable="false" class="wpacu-emoji" alt="⚙" src="https://s.w.org/images/core/emoji/12.0.0-1/svg/2699.svg" /></span></p>
<p><?php _e('As developers, we often go through the HTML source code of the website, access the server (e.g. Apache, NGINX) logs that has the HTTP requests, and have to sometimes solve code conflict problems (e.g. between plugins) due to poorly written code.', 'wp-asset-clean-up'); ?> <?php _e('By preventing unnecessary files to load, having less HTTP requests, and cleaner HTML code, you will be able to easily go through the code (which is smaller).', 'wp-asset-clean-up'); ?> <?php _e('Your log files will take less space on the server, will be easier to backup and analyse, and by having less JavaScript files loading, you will be reduce the changes of getting less JS errors that could interfere with the functionality of your website.', 'wp-asset-clean-up'); ?></p>
<hr />

<p class="area-title"><?php _e('Higher Revenue', 'wp-asset-clean-up'); ?> <span style="font-size: 24px;"><img draggable="false" class="wpacu-emoji" alt="💯" src="https://s.w.org/images/core/emoji/12.0.0-1/svg/1f4af.svg" /></span></p>
<p><?php _e('Just about any major retailer is taking site speed as a very important factor for increasing conversions.', 'wp-asset-clean-up'); ?> <?php _e('According to Strangeloop, 57% of online customers will leave a website after waiting 3 seconds for the page to load. Moreover, 80% of those people will not return to that page. Some of them will tell others about their negative experience. This has a direct impact on the conversion rate, revenue and brand image.', 'wp-asset-clean-up'); ?></p>

<p style="margin-bottom: 0;"><em>"<?php echo sprintf(__('%s of users say they\'ve felt STRESS OR ANGER while using a slow website.', 'wp-asset-clean-up'), '78%'); ?>"</em></p>
<p style="margin-top: 5px; margin-bottom: 0;"><em>"<?php echo sprintf(__('%s of users say that slow online transaction make them ANXIOUS about the success of the transaction.', 'wp-asset-clean-up'), '44%'); ?>"</em></p>
<p style="margin-top: 5px;"><em>"<?php echo sprintf(__('%s of people have THROWN THEIR PHONE while using a slow mobile site.', 'wp-asset-clean-up'), '4%'); ?>"</em></p>

<?php add_thickbox(); ?>
<div id="wpacu-brain-slow-website-info" style="display:none;">
	<img alt="" style="width: 100%;"
	     src="<?php echo WPACU_PLUGIN_URL; ?>/assets/images/your-brain-on-a-slow-website-infographic.jpg" />
</div>

<span class="dashicons dashicons-format-image"></span> <a href="#TB_inline?&width=1024&height=550&inlineId=wpacu-brain-slow-website-info"
                                                          class="thickbox"><?php echo sprintf(__('View "%s" Infographic', 'wp-asset-clean-up'), 'This Is Your Brain On A Slow Website'); ?></a>