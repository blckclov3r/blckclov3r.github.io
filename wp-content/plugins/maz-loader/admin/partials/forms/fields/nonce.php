<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
// Generate a custom nonce value.
wp_nonce_field( 'maz_loader_meta_form_nonce', 'maz_loader_meta_nonce' );

