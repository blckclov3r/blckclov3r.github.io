<?php
/**
 * JSON handler
 *
 * @copyright 2020 over-engineer
 */

namespace Animentor;

// Prevent direct access to files
if ( ! defined( 'ABSPATH' ) ) exit;

class Json_Handler {

  const MIME_TYPE = 'application/json';

  public function upload_mimes( $allowed_types ) {
    $allowed_types['json'] = self::MIME_TYPE;
    return $allowed_types;
  }

  public function wp_handle_upload_prefilter( $file ) {
    if ( self::MIME_TYPE !== $file['type'] ) {
      return $file;
    }

    $ext = pathinfo( $file['name'], PATHINFO_EXTENSION );

    if ( 'json' !== $ext ) {
      $file['error'] = sprintf(
        __( 'The uploaded %s file is not supported. Please upload a valid JSON file', 'animentor-lottie-bodymovin-elementor' )
      );
      return $file;
    }

    return $file;
  }

  public function wp_check_filetype_and_ext( $data, $file, $filename, $mimes ) {
    if ( ! empty( $data['ext'] ) && ! empty( $data['type'] ) ) {
      return $data;
    }

    $filetype = wp_check_filetype( $filename, $mimes );

    if ( 'json' === $filetype['ext'] ) {
      $data['ext'] = 'json';
      $data['type'] = self::MIME_TYPE;
    }

    return $data;
  }

  /**
   * Json_Handler constructor.
   */
  public function __construct() {
    add_filter( 'upload_mimes', array( $this, 'upload_mimes' ) );
    add_filter( 'wp_handle_upload_prefilter', array( $this, 'wp_handle_upload_prefilter' ) );
    add_filter( 'wp_check_filetype_and_ext', array( $this, 'wp_check_filetype_and_ext' ), 10, 4 );
  }

}
