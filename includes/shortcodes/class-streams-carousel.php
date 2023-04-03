<?php
/**
 * Shortcode to display random streams in carousel
 *
 * @link https://developer.wordpress.org/reference/functions/add_shortcode/
 *
 * @package Woo Stream
 */

namespace Woo_Stream\Includes\Shortcodes;

use Woo_Stream\Helpers\Helpers as Helper;

class Streams_Carousel {

  function __construct() {

    add_shortcode( 'woost_carousel', [ $this, 'html' ] );

  }

  public function html( $atts ) {

    if ( woo_stream_fs()->is_not_paying() ) return;

    $atts = shortcode_atts( array(
      'items' => 10,
      'show' => 5,
    ), $atts );

    $items = $atts['items'];
    $show = $atts['show'];
    $videos_count = 0;
    $error = false;
    $output = '';

    $get_users = get_users( [
      'meta_key' => 'woost_switch',
      'meta_value' => 'enable'
    ] );

    if ( $get_users ) {

      $users_count = count( $get_users );

      // Check if users with active streaming service are less than the videos to display.
      if ( $users_count < $items ) {

        $random_users = $get_users;

      } else {

        $random_users = array_rand( $get_users, $items );

      }

      $output .= '<div class="woost-carousel woost-row" data-show="' . esc_attr($show) . '">';

      foreach ( $random_users as $the_user ) {

        $user_id = $the_user->ID;

        $permanent_token = get_user_meta( $user_id, 'woost_fb_permanent_token', true );
        $live_videos = Helper::fb_live_videos($permanent_token);

        if ( is_array($live_videos) || is_object($live_videos) ) {

          // Don't proceed if there's only one user with 1 or 0 videos.
          if ( $users_count == 1 && count($live_videos) <= 1 ) {

            $error = true;

          // If there's one user with more than 1 videos display them.
          } else if ( $users_count == 1 && count($live_videos) > 1 ) {

            $videos_count = count( $live_videos );

            for ( $i = 0; $i < $videos_count; $i++ ) {

              if ( $i == $items ) break;

              $url = Helper::iframe_src( $live_videos[$i]->embed_html );

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

          // If there are more than 1 users.
          } else {

            $url = Helper::iframe_src( $live_videos[0]->embed_html );

            ob_start();
            ?>
              <div class="woost-column">
                <div class="woost-fb-video">
                  <iframe src="<?php echo esc_url($url); ?>" style="border:none;"></iframe>
                </div>
              </div>
            <?php
            $output .= ob_get_clean();

            $videos_count++;

          }

        }

        $output .= '</div>';

      }

    } else {

      $error = true;

    }

    return ( $error || $videos_count <= 1 ) ? '<div class="woost-alert woost-alert-info woost-alert-dismissible" role="alert">' . esc_html__( 'No videos to display.', 'woo-stream' ) . '</div>' : $output;

  }

}
new Streams_Carousel();
