<?php
/**
 * Get pending requests from users/vendors using AJAX
 *
 * @package Woo Stream
 */

use Woo_Stream\Helpers\Helpers as Helper;

function woo_stream_load_requests() {

  check_ajax_referer( 'woost_ajax_nonce', 'nonce' );
  $error = $success = $paging = '';

  if ( isset($_POST) ) {

    global $woost_opt;

    $page_no = $_POST['page_no'];

    $users = get_users( [
      'meta_key' => 'woost_switch',
      'meta_value' => 'pending'
    ] );

    if ( $users ) {

      $total = count($users);
      $per_page = $woost_opt->per_page;
      $page_count = ceil( $total / $per_page );

      foreach ( $users as $user ) {

        $id = $user->data->ID;
        $username = $user->data->user_login;
        $email = $user->data->user_email;
        $first_name = get_user_meta( $id, 'first_name', true );
        $last_name = get_user_meta( $id, 'last_name', true );
        $full_name = $first_name . ' ' . $last_name;
        $edit_url = get_edit_user_link( $id );

        ob_start();
        ?>
          <tr>
            <td data-label="<?php echo esc_html__( 'Username', 'woo-stream' ); ?>" class="woost-username">
              <a href="<?php echo esc_url( $edit_url ); ?>"><?php echo esc_html($username); ?></a>
            </td>

            <td data-label="<?php echo esc_html__( 'Email', 'woo-stream' ); ?>" class="woost-email">
              <?php echo esc_html($email); ?>
            </td>

            <td data-label="<?php echo esc_html__( 'Full Name', 'woo-stream' ); ?>" class="woost-full-name">
              <?php echo esc_html($full_name); ?>
            </td>

            <td data-label="<?php echo esc_html__( 'Request', 'woo-stream' ); ?>" data-id="<?php echo esc_attr($id); ?>">
              <button class="button button-primary button-large woost-accept"><?php echo esc_html__( 'Accept', 'woo-stream' ); ?></button>
              <button class="button button-primary button-large woost-reject"><?php echo esc_html__( 'Reject', 'woo-stream' ); ?></button>
              <span class="woost-loader" style="display: none"></span>
            </td>
          </tr>
        <?php
        $success .= ob_get_clean();

      }

      $paging = ( (int) $page_count === 1 ) ? '' : Helper::paging_nav( 1, $page_no, $page_count );

    }


  }

  echo wp_json_encode( [
    'error' => $error,
    'success' => $success,
    'paging' => $paging
  ] );

  wp_die();

}
add_action( 'wp_ajax_woo_stream_load_requests', 'woo_stream_load_requests' );
