<?php
/**
 * Custom Fields for user profile.
 *
 * @package Woo Stream
 */

namespace Woo_Stream\Includes;

use Woo_Stream\Helpers\Helpers;

class User_Profile {

  function __construct() {

    add_action( 'show_user_profile', [$this, 'html'] );
    add_action( 'edit_user_profile', [$this, 'html'] );

    add_action( 'personal_options_update', [$this, 'save'] );
    add_action( 'edit_user_profile_update', [$this, 'save'] );

  }

  /**
   * # Display the user profile HTML.
   */
  public function html( $user ) {

    $switch = get_the_author_meta( 'woost_switch', $user->ID );
    $fb_app_id = get_the_author_meta( 'woost_fb_app_id', $user->ID );
    $fb_app_secret = get_the_author_meta( 'woost_fb_app_secret', $user->ID );
    $fb_access_token = get_the_author_meta( 'woost_fb_access_token', $user->ID );
    $fb_page_id = get_the_author_meta( 'woost_fb_page_id', $user->ID );
    $fb_permanent_token = get_the_author_meta( 'woost_fb_permanent_token', $user->ID );

    ?>
      <div class="woost-settings">
        <h3><?php echo esc_html__( 'Woo Stream General Settings', 'woo-stream' ); ?></h3>

        <table class="form-table woost-general">
          <!-- Stream Service -->
          <tr>
            <th><label for="woost_switch"><?php echo esc_html__('Stream Service', 'woo-stream'); ?></label></th>
            <td>
              <select name="woost_switch" id="woost_switch">
                <option value="disable" <?php selected( $switch, 'disable' ); ?>><?php echo esc_html__( 'Disable', 'woo-stream' ); ?></option>
                <option value="pending" <?php selected( $switch, 'pending' ); ?>><?php echo esc_html__( 'Pending', 'woo-stream' ); ?></option>
                <option value="enable" <?php selected( $switch, 'enable' ); ?>><?php echo esc_html__( 'Enable', 'woo-stream' ); ?></option>
                <option value="block" <?php selected( $switch, 'block' ); ?>><?php echo esc_html__( 'Block', 'woo-stream' ); ?></option>
              </select>
            </td>
          </tr>
        </table>

        <h3><?php echo esc_html__( 'Facebook Stream Settings', 'woo-stream' ); ?></h3>
        <div id="woost-alerts" class="woost-alerts"></div>

        <table class="form-table woost-fb">
          <!-- FB App ID -->
          <tr>
            <th><label for="woost_fb_app_id"><?php echo esc_html__('App ID', 'woo-stream'); ?></label></th>
            <td>
              <input type="text" name="woost_fb_app_id" id="woost_fb_app_id" value="<?php echo esc_attr($fb_app_id); ?>" class="regular-text">
            </td>
          </tr>

          <!-- FB App Secret -->
          <tr>
            <th><label for="woost_fb_app_secret"><?php echo esc_html__('App Secret', 'woo-stream'); ?></label></th>
            <td>
              <input type="text" name="woost_fb_app_secret" id="woost_fb_app_secret" value="<?php echo esc_attr($fb_app_secret); ?>" class="regular-text">
            </td>
          </tr>

          <!-- FB Access Token -->
          <tr>
            <th><label for="woost_fb_access_token"><?php echo esc_html__('Access Token (Short Lived)', 'woo-stream'); ?></label></th>
            <td>
              <input type="text" name="woost_fb_access_token" id="woost_fb_access_token" value="<?php echo esc_attr($fb_access_token); ?>" class="regular-text">
              <p class="description"><?php echo esc_html__( 'Expires: ', 'woo-stream' ) .  Helpers::fb_token_expiry( $fb_app_id, $fb_app_secret, $fb_access_token ); ?></p>
            </td>
          </tr>

          <!-- FB Page ID -->
          <tr>
            <th><label for="woost_fb_page_id"><?php echo esc_html__('User/Page ID', 'woo-stream'); ?></label></th>
            <td>
              <input type="text" name="woost_fb_page_id" id="woost_fb_page_id" value="<?php echo esc_attr($fb_page_id); ?>" class="regular-text" readonly>
            </td>
          </tr>

          <!-- FB Access Token (Permanent) -->
          <tr>
            <th><label for="woost_fb_permanent_token"><?php echo esc_html__('Access Token (Permanent)', 'woo-stream'); ?></label></th>
            <td>
              <input type="text" name="woost_fb_permanent_token" id="woost_fb_permanent_token" value="<?php echo esc_attr($fb_permanent_token); ?>" class="regular-text" readonly>
            </td>
          </tr>

          <tr>
            <th></th>
            <td>
              <button type="button" class="button hide-if-no-js woost-fetch"><?php echo esc_html__( 'Fetch ID & Token', 'woo-stream' ); ?></button>
              <button type="button" class="button hide-if-no-js woost-test"><?php echo esc_html__( 'Test Connection', 'woo-stream' ); ?></button>
              <span class="woost-loader" style="display: none;"></span>
            </td>
          </tr>
        </table>
      </div>
    <?php

  }

  /**
   * # Save user profile fields
   */
  public function save( $user_id ) {

    if ( empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'update-user_' . $user_id ) ) {
      return;
    }

    if ( !current_user_can('edit_user', $user_id) ) {
      return false;
    }

    $switch = sanitize_text_field($_POST['woost_switch']);
    $fb_app_id = sanitize_text_field($_POST['woost_fb_app_id']);
    $fb_app_secret = sanitize_text_field($_POST['woost_fb_app_secret']);
    $fb_access_token = sanitize_text_field($_POST['woost_fb_access_token']);
    $fb_page_id = sanitize_text_field($_POST['woost_fb_page_id']);
    $fb_permanent_token = sanitize_text_field($_POST['woost_fb_permanent_token']);

    update_user_meta($user_id, 'woost_switch', $switch);
    update_user_meta($user_id, 'woost_fb_app_id', $fb_app_id);
    update_user_meta($user_id, 'woost_fb_app_secret', $fb_app_secret);
    update_user_meta($user_id, 'woost_fb_access_token', $fb_access_token);
    update_user_meta($user_id, 'woost_fb_page_id', $fb_page_id);
    update_user_meta($user_id, 'woost_fb_permanent_token', $fb_permanent_token);

  }

}
new User_Profile();
