<?php
/**
 * Plugin Name: Woo Stream
 * Description: Facebook live stream integration for WooCommerce.
 * Version:     0.0.2
 * Author:      Muhammad Zohaib - XeCreators
 * Author URI:  https://www.xecreators.pk
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: woo-stream
 */

if (!defined('ABSPATH')) exit; // Exit if accessed directly

if ( ! function_exists( 'woo_stream_fs' ) ) {
  // Create a helper function for easy SDK access.
  function woo_stream_fs() {
      global $woo_stream_fs;

      if ( ! isset( $woo_stream_fs ) ) {
          // Activate multisite network integration.
          if ( ! defined( 'WP_FS__PRODUCT_12354_MULTISITE' ) ) {
              define( 'WP_FS__PRODUCT_12354_MULTISITE', true );
          }

          // Include Freemius SDK.
          require_once dirname(__FILE__) . '/freemius/start.php';

          $woo_stream_fs = fs_dynamic_init( array(
              'id'                  => '12354',
              'slug'                => 'woo-stream',
              'premium_slug'        => 'woo-stream',
              'type'                => 'plugin',
              'public_key'          => 'pk_afef6dba2b7b6d59de1439bcafbf2',
              'is_premium'          => true,
              'is_premium_only'     => true,
              'has_addons'          => false,
              'has_paid_plans'      => true,
              'trial'               => array(
                  'days'               => 30,
                  'is_require_payment' => true,
              ),
              'menu'                => array(
                  'slug'           => 'woo-stream-license',
                  'support'        => false,
                  'parent'         => array(
                      'slug' => 'woo-stream-options',
                  ),
              ),
              // Set the SDK to work in a sandbox mode (for development & testing).
              // IMPORTANT: MAKE SURE TO REMOVE SECRET KEY BEFORE DEPLOYMENT.
              'secret_key'          => 'sk_Dw<u8$;7nrTr=w*l?WReqWTJ!e-xo',
          ) );
      }

      return $woo_stream_fs;
  }

  // Init Freemius.
  woo_stream_fs();
  // Signal that SDK was initiated.
  do_action( 'woo_stream_fs_loaded' );
}

require 'helpers/functions.php';

/**
 * Plugin setup functions and definitions.
 */
require woo_stream_directory() . '/includes/class-setup.php';

/**
 * Object for containing default values.
 */
require woo_stream_directory() . '/helpers/class-defaults.php';

/**
 * Class that holds helper methods.
 */
require woo_stream_directory() . '/helpers/class-helpers.php';

/**
 * Class for reusable sections.
 */
require woo_stream_directory() . '/helpers/class-views.php';

/**
 * Class to get and use plugin options.
 */
require woo_stream_directory() . '/helpers/class-plugin-options.php';

/**
 * Enqueue scripts and styles for admin and front end.
 */
require woo_stream_directory() . '/includes/class-scripts.php';

/**
 * Menu or sub-menu Pages
 */
require woo_stream_directory() . '/includes/class-menu-pages.php';
require woo_stream_directory() . '/includes/callbacks/class-plugin-options.php';
require woo_stream_directory() . '/includes/callbacks/class-activation-requests.php';
require woo_stream_directory() . '/includes/callbacks/class-license.php';

/**
 * Class for adding custom WooCommerce fields, Tabs and Product types.
 */
require woo_stream_directory() . '/includes/class-my-account.php';

/**
 * Custom Fields for user profile.
 */
require woo_stream_directory() . '/includes/class-user-profile.php';

/**
 * AJAX functions
 */
require woo_stream_directory() . '/includes/ajax/save-settings.php';
require woo_stream_directory() . '/includes/ajax/test-connection.php';
require woo_stream_directory() . '/includes/ajax/fetch-token.php';
require woo_stream_directory() . '/includes/ajax/load-videos.php';
require woo_stream_directory() . '/includes/ajax/activation-request.php';
require woo_stream_directory() . '/includes/ajax/load-requests.php';
require woo_stream_directory() . '/includes/ajax/process-request.php';

/**
 * Shortcodes
 */
require woo_stream_directory() . '/includes/shortcodes/class-all-streams.php';
require woo_stream_directory() . '/includes/shortcodes/class-streams-carousel.php';
