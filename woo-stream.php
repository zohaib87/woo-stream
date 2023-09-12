<?php
/**
 * Plugin Name: Woo Stream
 * Description: Facebook live stream integration for WooCommerce.
 * Version:     0.0.1
 * Author:      Muhammad Zohaib
 * Author URI:  https://www.xecreators.pk
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: woo-stream
 */

if ( ! defined('ABSPATH') ) exit; // Exit if accessed directly

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
