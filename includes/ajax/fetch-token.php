<?php
/**
 * Fetch user/page if and permanent token using AJAX
 *
 * @package Woo Stream
 */

function woo_stream_fetch_token() {

  check_ajax_referer( 'woost_ajax_nonce', 'nonce' );
  $error = $success = $page_id = $permanent_token = '';

  if ( isset( $_POST ) ) {

    $app_id = $_POST['app_id'];
    $app_secret = $_POST['app_secret'];
    $access_token = $_POST['access_token'];

    /**
     * # Get permanent access token
     */
    $params = array(
      'grant_type' => 'fb_exchange_token',
      'client_id' => $app_id,
      'client_secret' => $app_secret,
      'fb_exchange_token' => $access_token
    );

    $request_url = 'https://graph.facebook.com/oauth/access_token?' . http_build_query($params);
    $response = wp_remote_get( esc_url_raw( $request_url ) );

    if ( is_array( $response ) && ! is_wp_error( $response ) ) {

      $body = wp_remote_retrieve_body( $response );
      $data = json_decode( $body );

      // Check for facebook api side error
      if ( isset( $data->error ) ) {

        $error =  esc_html($data->error->message);

      } else {

        $permanent_token = $data->access_token; // Permanent access token

        /**
         * # Get user ID
         */
        $request_url = 'https://graph.facebook.com/me?access_token=' . $permanent_token;
        $response = wp_remote_get( esc_url_raw( $request_url ) );

        if ( is_array( $response ) && ! is_wp_error( $response ) ) {

          $body = wp_remote_retrieve_body( $response );
          $data = json_decode( $body );

          // Check for facebook api side error
          if ( isset( $data->error ) ) {

            $error =  esc_html($data->error->message);

          } else {

            $page_id = $data->id; // User/Page ID
            $success = esc_html__( 'User/Page ID & Token fetched and saved successfully.', 'woo-stream' );
            $user_id = get_current_user_id();

            update_user_meta( $user_id, 'woost_fb_page_id', $page_id );
            update_user_meta( $user_id, 'woost_fb_permanent_token', $permanent_token );

          }

        } elseif ( is_wp_error( $response ) ) {

          $error = esc_html( $response->get_error_message() );

        }

      }

    } elseif ( is_wp_error( $response ) ) {

      $error = esc_html( $response->get_error_message() );

    }

  }

  echo wp_json_encode( [
    'error' => $error,
    'success' => $success,
    'permanentToken' => $permanent_token,
    'pageId' => $page_id
  ] );

  wp_die();

}
add_action( 'wp_ajax_woo_stream_fetch_token', 'woo_stream_fetch_token' );
