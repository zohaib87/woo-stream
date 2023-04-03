<?php
/**
 * Send streaming service activation request using AJAX
 *
 * @package Woo Stream
 */

function woo_stream_activation_request() {

  check_ajax_referer( 'woost_ajax_nonce', 'nonce' );
  $error = $success = $button_text = '';

  if ( isset( $_POST ) ) {

    $user_id = get_current_user_id();
    $switch = get_the_author_meta( 'woost_switch', $user_id );

    if ( $switch == 'enable' ) {

      $button_text = esc_html__( 'Streaming Service Enabled', 'woo-stream' );
      $success = esc_html__( 'Your streaming service is enabled.', 'woo-stream' );

    } else if ( $switch == 'pending' ) {

      $button_text = esc_html__( 'Pending Activation', 'woo-stream' );
      $success = esc_html__( 'Your streaming service activation is pending.', 'woo-stream' );

    } else if ( $switch == 'block' ) {

      $button_text = esc_html__( 'Request Rejected', 'woo-stream' );
      $error = esc_html__( 'You are blocked from using the streaming service.', 'woo-stream' );

    } else {

      update_user_meta($user_id, 'woost_switch', 'pending');
      $button_text = esc_html__( 'Pending Activation', 'woo-stream' );
      $success = esc_html__('Your request for activation has been sent.', 'woo-stream');

    }

  }

  echo wp_json_encode( [
    'error' => $error,
    'success' => $success,
    'buttonText' => $button_text
  ] );

  wp_die();

}
add_action( 'wp_ajax_woo_stream_activation_request', 'woo_stream_activation_request' );
