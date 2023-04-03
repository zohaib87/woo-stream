<?php
/**
 * Enqueue scripts and styles for admin panel and front end.
 *
 * @package Woo Stream
 */

namespace Woo_Stream\Includes;

use Woo_Stream\Helpers\Helpers as Helper;

class Scripts {

  function __construct() {

    add_action( 'wp_enqueue_scripts', [ $this, 'frontend' ] );
    add_action( 'admin_enqueue_scripts', [ $this, 'admin' ], 9999 );

  }

  /**
   * # Enqueue scripts and styles for front-end.
   */
  public function frontend() {

    global $woost_opt;

    /**
     * Styles
     */
    Helper::enqueue( 'style', 'tiny-slider', '/assets/css/tiny-slider.css' );
    Helper::enqueue( 'style', 'woo-stream-main', '/assets/css/main.css' );

    /**
     * Scripts
     */
    Helper::enqueue( 'script', 'tiny-slider', '/assets/js/tiny-slider.js' );
    Helper::enqueue( 'script', 'woo-stream-main', '/assets/js/main.js', ['jquery'] );

    wp_localize_script( 'woo-stream-main', 'woostObj', [
      'ajaxUrl' => admin_url( 'admin-ajax.php' ),
      'pluginUrl' => woo_stream_directory_uri(),
      'nonce' => wp_create_nonce( 'woost_ajax_nonce' ),
      'dataLoadingMsg' => esc_html__( 'Please wait, loading data...', 'woo-stream' ),
      'localhost' => $woost_opt->localhost
    ] );

  }

  /**
   * # Enqueue scripts and styles for admin panel.
   */
  public function admin() {

    global $current_screen, $woost_opt;

    /**
     * Styles
     */
    Helper::enqueue( 'style', 'select2', '/assets/css/select2.min.css' );
    Helper::enqueue( 'style', 'woo-stream-admin', '/assets/css/admin.css' );

    /**
     * Scripts
     */
    Helper::enqueue( 'script', 'select2', '/assets/js/select2.min.js', ['jquery'] );
    Helper::enqueue( 'script', 'woo-stream-admin', '/assets/js/admin.js', ['jquery'] );

    wp_localize_script( 'woo-stream-admin', 'woostObj', [
      'pluginUrl' => woo_stream_directory_uri(),
      'nonce' => wp_create_nonce( 'woost_ajax_nonce' ),
      'postType' => $current_screen->post_type,
      'base' => $current_screen->base,
      'dataLoadingMsg' => esc_html__( 'Please wait, loading data...', 'woo-stream' ),
      'noDataMsg' => esc_html__( 'No data to display.', 'woo-stream' ),
      'localhost' => $woost_opt->localhost
    ] );

  }

}
new Scripts();
