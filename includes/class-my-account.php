<?php
/**
 * Customize mu account page for WooCommerce
 *
 * @link https://usersinsights.com/woocommerce-my-account-page/
 *
 * @package Woo Stream
 */

use Woo_Stream\Helpers\Helpers as Helper;

class My_Account {

  function __construct() {

    add_action( 'init', [ $this, 'register_endpoints' ] );
    add_filter( 'woocommerce_account_menu_items', [ $this, 'menu_items' ] );
    add_action( 'woocommerce_account_woost-settings_endpoint', [ $this, 'woo_stream_settings' ] );

  }

  /**
   * # Register end-points
   */
  public function register_endpoints() {

    add_rewrite_endpoint( 'woost-settings', EP_PAGES );
    flush_rewrite_rules();

  }

  /**
   * # My account menu
   */
  public function menu_items( $menu_items ) {

    global $woost_opt;

    $intersect = array_intersect( wp_get_current_user()->roles, $woost_opt->roles );

    if ( empty($intersect) ) return $menu_items;
    if ( woo_stream_fs()->is_not_paying() ) return $menu_items;

    unset( $menu_items['customer-logout'] );

    $menu_items['woost-settings'] = esc_html__( 'Stream Settings', 'woo-stream' );
    $menu_items['customer-logout'] = esc_html__( 'Logout', 'woo-stream' );

    return $menu_items;

  }

  /**
   * # Woo Stream settings page content.
   */
  public function woo_stream_settings() {

    global $woost_opt;

    $intersect = array_intersect( wp_get_current_user()->roles, $woost_opt->roles );

    if ( empty($intersect) ) return;
    if ( woo_stream_fs()->is_not_paying() ) return;

    $switch = get_the_author_meta( 'woost_switch' );
    $fb_app_id = get_the_author_meta( 'woost_fb_app_id' );
    $fb_app_secret = get_the_author_meta( 'woost_fb_app_secret' );
    $fb_access_token = get_the_author_meta( 'woost_fb_access_token' );
    $fb_page_id = get_the_author_meta( 'woost_fb_page_id' );
    $fb_permanent_token = get_the_author_meta( 'woost_fb_permanent_token' );

    $all_streams = ! empty( $woost_opt->all_streams ) ? get_post( $woost_opt->all_streams ) : null;

    if ( $all_streams ) {

      $vendor = get_user_by( 'id', get_current_user_id() )->user_nicename;
      $channel_url = get_permalink( $all_streams ) . '?vendor=' . $vendor;
      $channel_url = '<div class="woost-channel-url"><a href="' . $channel_url . '" target="_blank">' . $channel_url . '</a></div>';

    } else {

      $channel_url = '';

    }

    ?>
      <div class="woost-settings">
        <div id="woost-alerts" class="woost-alerts"></div>

        <div class="woost-general woost-form-cont">
          <h2><?php echo esc_html__('General', 'woo-stream'); ?></h2>

          <?php
            $disabled = ( $switch == 'enable' || $switch == 'pending' || $switch == 'block' ) ? 'disabled' : '';

            if ( $switch == 'enable' ) {

              $button_text = esc_html__( 'Streaming Service Enabled', 'woo-stream' );

            } else if ( $switch == 'pending' ) {

              $button_text = esc_html__( 'Pending Activation', 'woo-stream' );

            } else if ( $switch == 'block' ) {

              $button_text = esc_html__( 'Request Rejected', 'woo-stream' );

            } else {

              $button_text = esc_html__('Activate Streaming', 'woo-stream');

            }
          ?>

          <button class="woost-btn woost-btn-primary woost-activate" <?php echo esc_attr($disabled); ?>><?php echo esc_html($button_text); ?></button>
          <span class="woost-loader" style="display: none"></span>
          <?php echo wp_kses_post( $channel_url ); ?>
        </div>

        <br>

        <div class="woost-fb woost-form-cont">
          <h2><?php echo esc_html__('Facebook', 'woo-stream'); ?></h2>

          <!-- App ID -->
          <label for="app_id" class="woost-label"><?php echo esc_html__('App ID', 'woo-stream'); ?></label>
          <input type="text" name="app_id" id="app_id" class="woost-form-control" value="<?php echo esc_attr($fb_app_id); ?>">

          <!-- App Secret -->
          <label for="app_secret" class="woost-label"><?php echo esc_html__('App Secret', 'woo-stream'); ?></label>
          <input type="text" name="app_secret" id="app_secret" class="woost-form-control" value="<?php echo esc_attr($fb_app_secret); ?>">

          <!-- Access Token (Short Lived) -->
          <label for="access_token" class="woost-label"><?php echo esc_html__('Access Token (Short Lived)', 'woo-stream'); ?></label>
          <input type="text" name="access_token" id="access_token" class="woost-form-control" value="<?php echo esc_attr($fb_access_token); ?>">
          <small><?php echo esc_html__( 'Expires: ', 'woo-stream' ); ?><span class="woost-short-token-expiry"><?php echo Helper::fb_token_expiry( $fb_app_id, $fb_app_secret, $fb_access_token ); ?></span></small>

          <!-- User/Page ID -->
          <label for="page_id" class="woost-label"><?php echo esc_html__('User/Page ID', 'woo-stream'); ?></label>
          <input type="text" name="page_id" id="page_id" class="woost-form-control" value="<?php echo esc_attr($fb_page_id); ?>" readonly>

          <!-- Access Token (Permanent) -->
          <label for="permanent_token" class="woost-label"><?php echo esc_html__('Access Token (Permanent)', 'woo-stream'); ?></label>
          <input type="text" name="permanent_token" id="permanent_token" class="woost-form-control" value="<?php echo esc_attr($fb_permanent_token); ?>" readonly>

          <button class="woost-btn woost-btn-primary woost-save"><?php echo esc_html__('Save', 'woo-stream'); ?></button>
          <button class="woost-btn woost-btn-primary woost-fetch"><?php echo esc_html__('Fetch ID & Token', 'woo-stream'); ?></button>
          <button class="woost-btn woost-btn-primary woost-test"><?php echo esc_html__('Test Connection', 'woo-stream'); ?></button>
          <span class="woost-loader" style="display: none"></span>
        </div>
      </div>
    <?php

  }

}
new My_Account();
