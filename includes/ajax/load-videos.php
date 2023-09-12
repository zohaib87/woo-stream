<?php
/**
 * Load videos using AJAX
 *
 * @package Woo Stream
 */

use Woo_Stream\Helpers\Helpers;

function woo_stream_load_videos() {

  check_ajax_referer( 'woost_ajax_nonce', 'nonce' );
  $error = $success = $paging = '';

  if ( isset( $_POST ) ) {

    global $woost_opt;

    $page_no = $_POST['page_no'];
    $vendor = $_POST['vendor'];

    $user = get_user_by( 'slug', $channel );
    $user_id = ( $user ) ? $user->ID : false;

    if ( $user_id ) {

      $permanent_token = get_the_author_meta( 'woost_fb_permanent_token', $user_id );
      $data = Helpers::fb_live_videos($permanent_token);

    } else {

      $data = sprintf(
        esc_html__( '%sError!%s Channel not found.', 'woo-stream' ),
        '<strong>',
        '</strong>'
      );

    }

    if ( is_array($data) || is_object($data) ) {

      $total = count($data);
      $per_page = $woost_opt->per_page;
      $page_count = ceil( $total / $per_page );

      // Calculate the starting index of the items for the current page
      $start_index = ($page_no - 1) * $per_page;

      // Get the items for the current page using array_slice
      $current_items = array_slice( $data, $start_index, $per_page );

      foreach ( $current_items as $current_item ) {

        $url = Helpers::iframe_src( $current_item->embed_html );

        ob_start();
        ?>
          <div class="woost-column">
            <div class="woost-fb-video">
              <iframe src="<?php echo esc_url($url); ?>" style="border:none;"></iframe>
            </div>
          </div>
        <?php
        $success .= ob_get_clean();

      }

      $paging = ( (int) $page_count === 1 ) ? '' : Helpers::paging_nav( 1, $page_no, $page_count );

    } else {

      $error = wp_kses_post( $data );

    }

  }

  echo wp_json_encode( [
    'error' => $error,
    'success' => $success,
    'paging' => $paging
  ] );

  wp_die();

}
add_action( 'wp_ajax_woo_stream_load_videos', 'woo_stream_load_videos' );
