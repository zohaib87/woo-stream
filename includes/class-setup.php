<?php
/**
 * Plugin setup functions and definitions.
 *
 * @package Woo Stream
 */

namespace Woo_Stream\Includes;

class Setup {

  function __construct() {

    add_action( 'admin_notices', [ $this, 'update_notice' ] );
    register_activation_hook( woo_stream_file(), [ $this, 'activation' ] );
    register_deactivation_hook( woo_stream_file(), [ $this, 'deactivation' ] );
    woo_stream_fs()->add_action('after_uninstall', [ self::class, 'uninstall' ]);
    add_action( 'plugins_loaded', [ $this, 'load_textdomain' ] );

  }

  /**
   * # Plugin Update Notice on Multisite
   */
  public function update_notice() {

    if ( ! woo_stream_fs()->is_paying() || ! woo_stream_fs()->is_trial() ) {
      return;
    }

    if ( ! is_multisite() || (isset($_GET['page']) && $_GET['page'] == 'woo-stream-license-account') ) {
      return;
    }

    if ( ! is_super_admin() ) {
      return;
    }

    if ( isset($_GET['action']) && $_GET['action'] == 'upgrade-plugin' ) {
      return;
    }

    $update = woo_stream_fs()->get_update( false, false, WP_FS__TIME_24_HOURS_IN_SEC / 24 );

    if ( $update ) {
      ?>
        <div class="notice notice-warning">
          <p>
            <?php
              echo sprintf(
                esc_html__( 'Update available for %sWoo Stream%s, %sClick Here%s to update.', 'woo-stream' ),
                '<strong>',
                '</strong>',
                '<a href="' . admin_url( 'options-general.php?page=woo-stream-license-account#pframe' ) . '">',
                '</a>'
              );
            ?>
          </p>
        </div>
      <?php
    }

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
