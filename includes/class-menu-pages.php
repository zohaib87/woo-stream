<?php
/**
 * Register custom menu and sub-menu pages.
 *
 * @link https://developer.wordpress.org/reference/functions/add_menu_page/
 * @link https://developer.wordpress.org/reference/functions/add_submenu_page/
 *
 * @package Woo Stream
 */

namespace Woo_Stream\Includes;

use Woo_Stream\Includes\Callbacks\Plugin_Options;
use Woo_Stream\Includes\Callbacks\Activation_Requests;

class Menu_Pages {

  function __construct() {

    add_action( 'admin_menu', [ $this, 'add_menu_pages' ] );

  }

  /**
   * # Add menu and sub-menu pages
   */
  public function add_menu_pages() {

    $plugin_options = new Plugin_Options();
    $activation_requests = new Activation_Requests();

    $requests = get_users( [
      'meta_key' => 'woost_switch',
      'meta_value' => 'pending'
    ] );

    $pending_count = '';

    if ( $requests ) {

      $requests_count = count($requests);

      if ( $requests_count > 0 ) {

        ob_start();
        ?>
        <span class="update-plugins count-<?php echo esc_attr($requests_count); ?>">
          <span class="plugin-count" aria-hidden="true"><?php echo esc_html($requests_count); ?></span>
          <span class="screen-reader-text"><?php echo sprintf( esc_html__( '%s notifications', 'woo-stream' ), $requests_count ); ?></span>
        </span>
        <?php
        $pending_count = ob_get_clean();

      }

    }

    /* Menu Pages */
    add_menu_page(
      esc_html__( 'Woo Stream', 'woo-stream' ),
      sprintf( esc_html__( 'Woo Stream %s', 'woo-stream' ), wp_kses_post($pending_count) ),
      'manage_options',
      'woo-stream-options',
      [ $plugin_options, 'html' ],
      'dashicons-welcome-widgets-menus',
      56
    );

    /* Submenu Pages */
    add_submenu_page(
      'woo-stream-options',
      esc_html__( 'Plugin Options', 'woo-stream' ),
      esc_html__( 'Plugin Options', 'woo-stream' ),
      'manage_options',
      'woo-stream-options',
      [ $plugin_options, 'html' ],
      1
    );

    add_submenu_page(
      'woo-stream-options',
      esc_html__( 'Requests', 'woo-stream' ),
      sprintf( esc_html__( 'Requests %s', 'woo-stream' ), wp_kses_post($pending_count) ),
      'manage_options',
      'woo-stream-requests',
      [ $activation_requests, 'html' ]
    );

  }

}
new Menu_Pages();
