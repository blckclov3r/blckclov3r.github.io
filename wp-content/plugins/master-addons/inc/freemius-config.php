<?php

if( !function_exists('ma_el_fs_add_licensing_helper')){
	function ma_el_fs_add_licensing_helper() { ?>
		<script type="text/javascript">
            (function () {
                window.ma_el_fs = { can_use_premium_code: <?php
					echo  json_encode( ma_el_fs()->can_use_premium_code() ) ;
					?>};
            })();
		</script>
		<?php
	}
	add_action( 'wp_head', 'ma_el_fs_add_licensing_helper' );
}

// Customize Opt-in Message for Existing Users
if( !function_exists('ma_el_fs_custom_connect_message_on_update')){

	function ma_el_fs_custom_connect_message_on_update(
		$message,
		$user_first_name,
		$plugin_title,
		$user_login,
		$site_link,
		$freemius_link
	) {
		return sprintf(
			__( 'Hey %1$s' ) . ',<br>' .
			__( 'Please help us improve %2$s! If you opt-in, some data about your usage of %2$s will be sent to %5$s. If you skip this, that\'s okay! %2$s will still work just fine.', 'master-addons' ),
			$user_first_name,
			'<b>' . $plugin_title . '</b>',
			'<b>' . $user_login . '</b>',
			$site_link,
			$freemius_link
		);
	}

	ma_el_fs()->add_filter('connect_message_on_update', 'ma_el_fs_custom_connect_message_on_update', 10, 6);
}


// Not like register_uninstall_hook(), you do NOT have to use a static function.
ma_el_fs()->add_action('after_uninstall', 'ma_el_fs_uninstall_cleanup');



// Helpscout Permission
if( !function_exists('ma_el_fs_add_helpscount_permission')){

	function ma_el_fs_add_helpscount_permission( $permissions ) {
		$permissions['helpscout'] = array(
			'icon-class' => 'dashicons dashicons-email-alt',
			'label'      => ma_el_fs()->get_text_inline( 'Help Scout', 'helpscout' ),
			'desc'       => ma_el_fs()->get_text_inline( 'Rendering Help Scout\'s beacon for easy support access', 'permissions-helpscout' ),
			'priority'   => 16,
		);

		$permissions['newsletter'] = array(
			'icon-class' => 'dashicons dashicons-email-alt',
			'label'      => ma_el_fs()->get_text_inline( 'Newsletter', 'permissions-newsletter' ),
			'desc'       => ma_el_fs()->get_text_inline( 'Updates, announcements, marketing, no spam', 'permissions-newsletter_desc' ),
			'priority'   => 15,
		);

	}
	ma_el_fs()->add_filter( 'permissions_list', 'ma_el_fs_add_helpscount_permission' );
}



//Controlling the visibility of admin notices added by the Freemius SDK
if( !function_exists('ma_el_fs_custom_show_admin_notice')){

	function ma_el_fs_custom_show_admin_notice( $show, $msg ) {
		if ('trial_promotion' == $msg['id']) {
			// Don't show the trial promotional admin notice.
			return false;
		}

		return $show;
	}
	ma_el_fs()->add_filter( 'show_admin_notice', 'ma_el_fs_custom_show_admin_notice', 10, 2 );
}

// Freemius Purchase Completion JavaScript Callback Filter
if( !function_exists('ma_el_fs_after_purchase_js')){

	function ma_el_fs_after_purchase_js( $js_function ) {
		return 'function (data) {
            console.log("checkout", "purchaseCompleted");
        }';
	}

	ma_el_fs()->add_filter('checkout/purchaseCompleted', 'ma_el_fs_after_purchase_js' );
}

// Freemius submenu items visibility filter
if( !function_exists('ma_el_fs_is_submenu_visible')){

	function ma_el_fs_is_submenu_visible($is_visible, $submenu_id){
		return $is_visible;
	}
	ma_el_fs()->add_filter( 'is_submenu_visible', 'ma_el_fs_is_submenu_visible', 10, 2 );
}


// Trial
ma_el_fs()->override_i18n( array(
	'hey'                                        => 'Hey',
	'trial-x-promotion-message'                  => 'Thank you so much for using %s!',
	'already-opted-in-to-product-usage-tracking' => 'How do you like %s so far? Test all our %s premium features with a %d-day free trial.',
	'start-free-trial'                           => 'Start free trial',
	// Trial with a payment method required.
	'no-commitment-for-x-days'                   => 'No commitment for %s days - cancel anytime!',
	// Trial without a payment method.
	'no-cc-required'                             => 'No credit card required',
) );


#----------------------------------------------------------------------------------
#region Show the 1st trial promotion after 7 days instead of 24 hours.
#----------------------------------------------------------------------------------

if( !function_exists('ma_el_fs_show_first_trial_after_7_days')){

	function ma_el_fs_show_first_trial_after_7_days( $day_in_sec ) {
		// 7 days in sec.
		return 7 * 24 * 60 * 60;
	}

	ma_el_fs()->add_filter( 'show_first_trial_after_n_sec', 'ma_el_fs_show_first_trial_after_7_days' );

}



#----------------------------------------------------------------------------------
#region Re-show the trial promotional offer after every 60 days instead of 30 days.
#----------------------------------------------------------------------------------

if( !function_exists('ma_el_fs_reshow_trial_after_every_60_days')){

	function ma_el_fs_reshow_trial_after_every_60_days( $thirty_days_in_sec ) {
		// 60 days in sec.
		return 60 * 24 * 60 * 60;
	}

	ma_el_fs()->add_filter( 'reshow_trial_after_every_n_sec', 'ma_el_fs_reshow_trial_after_every_60_days' );
}
