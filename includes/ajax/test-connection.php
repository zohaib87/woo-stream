<?php
/**
 * Test connection using AJAX
 *
 * @package Woo Stream
 */

function woo_stream_test_fb_connection() {

  check_ajax_referer( 'woost_ajax_nonce', 'nonce' );
  $error = $success = '';

  if ( isset( $_POST ) ) {

    $page_id = $_POST['page_id'];
    $permanent_token = $_POST['permanent_token'];

    if ( empty($page_id) || empty($permanent_token) ) {

      $error = sprintf(
        esc_html__( '%1$sError!%2$s Fetch %1$sPage ID%2$s and %1$sToken%2$s first.', 'woo-stream' ),
        '<strong>',
        '</strong>'
      );

    } else {

      /**
       * # Test connection
       */
      $params = array(
        'fields' => 'id',
        'access_token' => $permanent_token
      );

      $request_url = 'https://graph.facebook.com/me?' . http_build_query($params);
      $response = wp_remote_get( esc_url_raw( $request_url ) );

      if ( is_array( $response ) && ! is_wp_error( $response ) ) {

        $body = wp_remote_retrieve_body( $response );
        $data = json_decode( $body );

        // Check for facebook api side error
        if ( isset( $data->error ) ) {

          $error =  esc_html($data->error->message);

        } else {

          $success = esc_html__( 'API connection successful.', 'woo-stream' );

        }

      } elseif ( is_wp_error( $response ) ) {

        $error = esc_html( $response->get_error_message() );

      }

    }

  } else {

    $error = sprintf(
      esc_html__( '%1$sError!%2$s Connection failed.', 'woo-stream' ),
      '<strong>',
      '</strong>'
    );

  }

  echo wp_json_encode( [
    'error' => $error,
    'success' => $success
  ] );

  wp_die();

}
add_action( 'wp_ajax_woo_stream_test_fb_connection', 'woo_stream_test_fb_connection' );
