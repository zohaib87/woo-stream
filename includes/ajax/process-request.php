<?php
/**
 * Process streaming service activation request using AJAX
 *
 * @package Woo Stream
 */

function woo_stream_process_request() {

  check_ajax_referer( 'woost_ajax_nonce', 'nonce' );
  $error = $success = '';

  if ( isset( $_POST ) ) {

    $username = $_POST['username'];
    $user_id = $_POST['user_id'];
    $action_type = $_POST['action_type'];

    if ( $action_type == 'accept' ) {

      update_user_meta($user_id, 'woost_switch', 'enable');

      $success = sprintf(
        esc_html__( 'Streaming service for %s has been enabled.', 'woo-stream' ),
        '<strong>' . esc_html($username) . '</strong>'
      );;

    } else {

      update_user_meta($user_id, 'woost_switch', 'block');

      $error = sprintf(
        esc_html__( 'Streaming service for %s has been rejected.', 'woo-stream' ),
        '<strong>' . esc_html($username) . '</strong>'
      );

    }

  }

  echo wp_json_encode( [
    'error' => $error,
    'success' => $success
  ] );

  wp_die();

}
add_action( 'wp_ajax_woo_stream_process_request', 'woo_stream_process_request' );
