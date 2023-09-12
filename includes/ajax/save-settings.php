<?php
/**
 * Save stream settings using AJAX
 *
 * @package Woo Stream
 */

use Woo_Stream\Helpers\Helpers;

function woo_stream_save_fb_settings() {

  check_ajax_referer( 'woost_ajax_nonce', 'nonce' );
  $error = $success = $expiry = '';

  if ( isset( $_POST ) ) {

    $app_id = $_POST['app_id'];
    $app_secret = $_POST['app_secret'];
    $access_token = $_POST['access_token'];
    $page_id = $_POST['page_id'];
    $permanent_token = $_POST['permanent_token'];
    $user_id = get_current_user_id();

    update_user_meta( $user_id, 'woost_fb_app_id', $app_id );
    update_user_meta( $user_id, 'woost_fb_app_secret', $app_secret );
    update_user_meta( $user_id, 'woost_fb_access_token', $access_token );
    update_user_meta( $user_id, 'woost_fb_page_id', $page_id );
    update_user_meta( $user_id, 'woost_fb_permanent_token', $permanent_token );

    $success = esc_html__( 'Settings saved successfully.', 'woo-stream' );
    $expiry = Helpers::fb_token_expiry( $app_id, $app_secret, $access_token );

  } else {

    $error = sprintf(
      esc_html__( '%1$sError!%2$s Settings not saved.', 'woo-stream' ),
      '<strong>',
      '</strong>'
    );

  }

  echo wp_json_encode( [
    'error' => $error,
    'success' => $success,
    'expiry' => $expiry
  ] );

  wp_die();

}
add_action( 'wp_ajax_woo_stream_save_fb_settings', 'woo_stream_save_fb_settings' );
