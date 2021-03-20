<?php
$config = array();

$config['plugin_screen'] = 'tools_page_wp-reset';
$config['icon_border'] = '1px solid #00000099';
$config['icon_right'] = '35px';
$config['icon_bottom'] = '45px';
$config['icon_image'] = 'wp-reset.png';
$config['icon_padding'] = '4px';
$config['icon_size'] = '55px';
$config['menu_accent_color'] = '#dd3036';
$config['custom_css'] = '#wf-flyout .ucp-icon .wff-icon img { max-width: 70%; } #wf-flyout .ucp-icon .wff-icon { line-height: 57px; } #wf-flyout .wp301-icon .wff-icon img { max-width: 70%; } #wf-flyout .wp301-icon .wff-icon { line-height: 57px; } #wf-flyout .wff-custom-icon.wff-menu-item-6 span.wff-icon { line-height: 63px; }';

$config['menu_items'] = array(
  array('href' => 'https://wpreset.com/?ref=wff-wp-reset', 'target' => '_blank', 'label' => 'Get WP Reset PRO with 50% off', 'icon' => 'wp-reset.png'),
  array('href' => 'https://wp301redirects.com/?ref=wff-wp-reset&coupon=50off', 'label' => 'Get WP 301 Redirects PRO with 50% off', 'icon' => '301-logo.png', 'class' => 'wp301-icon'),
  array('href' => 'https://underconstructionpage.com/?ref=wff-wp-reset&coupon=welcome', 'target' => '_blank', 'label' => 'Create the perfect Under Construction Page', 'icon' => 'ucp.png', 'class' => 'ucp-icon'),
  array('href' => 'https://wpsticky.com/?ref=wff-wp-reset', 'target' => '_blank', 'label' => 'Make any element sticky with WP Sticky', 'icon' => 'dashicons-admin-post'),
  array('href' => 'https://wordpress.org/support/plugin/wp-reset/reviews/?filter=5#new-post', 'target' => '_blank', 'label' => 'Rate the Plugin', 'icon' => 'dashicons-thumbs-up'),
  array('href' => 'https://wordpress.org/support/plugin/wp-reset/#new-post', 'target' => '_blank', 'label' => 'Get Support', 'icon' => 'dashicons-sos'),
);
