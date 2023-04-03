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

use Woo_Stream\Includes\Callbacks\{ Plugin_Options, Activation_Requests, License };

class Menu_Pages {

  function __construct() {

    add_action('admin_menu', [$this, 'add_menu_pages']);

  }

  /**
   * # Add menu and sub-menu pages
   */
  public function add_menu_pages() {

    $plugin_options = new Plugin_Options();
    $activation_requests = new Activation_Requests();
    $license = new License();

    /* Menu Pages */
    add_menu_page(
      esc_html__('Woo Stream', 'woo-stream'),
      esc_html__('Woo Stream', 'woo-stream'),
      'manage_options',
      'woo-stream-options',
      [$plugin_options, 'html'],
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
      esc_html__( 'Requests', 'woo-stream' ),
      'manage_options',
      'woo-stream-requests',
      [ $activation_requests, 'html' ]
    );

    if (is_multisite() && is_main_site()) {
      // do nothing...
    } else {
      add_submenu_page(
        'woo-stream-options',
        esc_html__('License', 'woo-stream'),
        esc_html__('License', 'woo-stream'),
        'manage_options',
        'woo-stream-license',
        [ $license, 'html' ]
      );
    }

  }

}
new Menu_Pages();
