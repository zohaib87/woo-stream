<?php
/**
 * Shortcode to display all streams of specific vendor
 *
 * @link https://developer.wordpress.org/reference/functions/add_shortcode/
 *
 * @package Woo Stream
 */

namespace Woo_Stream\Includes\Shortcodes;

use Woo_Stream\Helpers\Helpers as Helper;

class All_Streams {

  function __construct() {

    add_shortcode( 'woost_all', [ $this, 'html' ] );

  }

  public function html() {

    if ( woo_stream_fs()->is_not_paying() ) return;

    global $woost_opt;

    $channel = isset( $_GET['vendor'] ) ? $_GET['vendor'] : false;
    $page_no = isset( $_GET['page_no'] ) ? $_GET['page_no'] : 1;
    $output = '';

    $user = get_user_by( 'slug', $channel );
    $user_id = ( $user ) ? $user->ID : false;
    $streaming_service = get_user_meta( $user_id, 'woost_switch', true );

    if ( $streaming_service == 'enable' && $user_id ) {

      $permanent_token = get_the_author_meta( 'woost_fb_permanent_token', $user_id );
      $data = Helper::fb_live_videos($permanent_token);

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
      $start_index = ( $page_no - 1 ) * $per_page;

      // Get the items for the current page using array_slice
      $current_items = array_slice( $data, $start_index, $per_page );

      ob_start();
      ?>
        <div class="woost-loader-cont" style="display: none;">
          <div class="woost-loader"></div> <div class="woost-loading-txt"><?php echo esc_html__( 'Please wait, loading data...', 'woo-stream' ); ?></div>
        </div>
        <input type="hidden" id="woost_vendor" value="<?php echo esc_attr($channel); ?>">
      <?php
      $output .= ob_get_clean();
      $output .= '<div class="woost-row">';

      foreach ( $current_items as $current_item ) {

        $url = Helper::iframe_src( $current_item->embed_html );

        ob_start();
        ?>
          <div class="woost-column">
            <div class="woost-fb-video">
              <iframe src="<?php echo esc_url($url); ?>" style="border:none;"></iframe>
            </div>
          </div>
        <?php
        $output .= ob_get_clean();

      }

      $output .= '</div>';

      $output .= '<div class="woost-paging-nav">';
      $output .= ( (int) $page_count === 1 ) ? '' : Helper::paging_nav( 1, $page_no, $page_count );
      $output .= '</div>';

      return $output;

    } else {

      return '<div class="woost-alert woost-alert-danger woost-alert-dismissible" role="alert">' . wp_kses_post($data) . '</div>';

    }


  }

}
new All_Streams();
