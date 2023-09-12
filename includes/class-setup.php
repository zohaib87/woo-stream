<?php
/**
 * Plugin setup functions and definitions.
 *
 * @package Woo Stream
 */

namespace Woo_Stream\Includes;

class Setup {

  function __construct() {

    register_activation_hook( woo_stream_file(), [ $this, 'activation' ] );
    register_deactivation_hook( woo_stream_file(), [ $this, 'deactivation' ] );
    register_uninstall_hook( woo_stream_file(), [ self::class, 'uninstall' ] );
    add_action( 'plugins_loaded', [ $this, 'load_textdomain' ] );

  }

  /**
   * # Plugin Activation
   */
  public function activation() {
  }

  /**
   * # Plugin Deactivation
   */
  public function deactivation() {
  }

  /**
   * # Plugin Uninstall
   */
  public static function uninstall() {

    global $wpdb;

    // $wpdb->query("DELETE FROM {$wpdb->posts} WHERE post_type IN ('opos-sales', 'opos-frames', 'opos-glasses', 'opos-w-customers')");
    // $wpdb->query("DELETE FROM {$wpdb->postmeta} WHERE post_id NOT IN (SELECT id FROM {$wpdb->posts})");

    // $wpdb->query("DELETE FROM {$wpdb->term_taxonomy} WHERE taxonomy = 'opos-glasses-cat'");

    // $wpdb->query("DELETE FROM {$wpdb->usermeta} WHERE meta_key IN ('opos_company', 'opos_contactno', 'opos_address', 'opos_city', 'opos_postalcode')");

    // $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name = 'opos_options'");

  }

  /**
   * # Translate Plugin
   */
  public function load_textdomain() {
    load_plugin_textdomain( 'woo-stream', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
  }

}
new Setup();
