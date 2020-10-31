<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}


// Custom CSS
$this->form->generateProField(
	array(
		'label'       => __( 'Custom CSS', 'maz-loader' ),
		'description' => __( 'Enter your Custom CSS here. You don\'t need to enter the &lt;style&gt;&lt;/style&gt; tags.', 'maz-loader' )
	)
);
// Custom JS
$this->form->generateProField(
	array(
		'label'       => __( 'Custom JS', 'maz-loader' ),
		'description' => __( 'Enter your Custom JavaScript here. You don\'t need to enter the &lt;script&gt;&lt;/script&gt; tags.', 'maz-loader' )
	)
);

?>