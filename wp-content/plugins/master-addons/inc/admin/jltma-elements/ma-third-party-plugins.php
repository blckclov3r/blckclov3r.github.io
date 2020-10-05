<?php
namespace MasterAddons\Admin\Dashboard\Addons\ThirdPartyPlugins;

$jltma_elements = [
    'jltma-plugins'  	   => [
    	'title' 	           => esc_html__( 'Extensions', MELA_TD),
    	'plugin'  	       => [
            [
                'key'           => 'custom-breakpoints',
                'title'         => esc_html__( 'Custom Breakpoints', MELA_TD),
                'wp_slug'       => 'custom-breakpoints-for-elementor',
                'download_url'  => '',
                'plugin_file'   => 'custom-breakpoints-for-elementor/custom-breakpoints-for-elementor.php'
            ]

    	]
    ]
];