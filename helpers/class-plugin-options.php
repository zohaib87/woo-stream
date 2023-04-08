<?php
/**
 * Plugin functions and definitions.
 *
 * @package Woo Stream
 */

namespace Woo_Stream\Helpers;

use Woo_Stream\Helpers\Helpers as Helper;
use Woo_Stream\Helpers\Defaults as De;

class Plugin_Options {

  // General
  public $roles, $all_streams, $per_page,

  // Others
  $localhost;

  function __construct() {

    // Assign Option values to variables
    add_action( 'init', array( $this, 'init_vars' ) );

  }

  /**
   * # Initialize variables for use.
   */
	public function init_vars() {

    // General
    $this->roles = get_option( 'woost_roles', De::$roles );
    $this->roles[] = 'administrator'; // Push administrator role manually.
    $this->all_streams = get_option( 'woost_all_streams', De::$all_streams );
    $this->per_page = get_option( 'woost_per_page', De::$per_page );

    // Others
    $this->localhost = $this->localhost();

  }

  /**
   * # Check if its localhost
   */
  protected function localhost() {

    $localhost = array(
      '127.0.0.1',
      '::1'
    );

    if ( in_array( $_SERVER['REMOTE_ADDR'], $localhost ) ) {
      return true;
    } else {
      return false;
    }

  }

}
global $woost_opt;
$woost_opt = new Plugin_Options();
