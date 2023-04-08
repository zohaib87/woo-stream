<?php
/**
 * Default values for options.
 *
 * @package Woo Stream
 */

namespace Woo_Stream\Helpers;

class Defaults {

  // General
  public static $roles, $all_streams, $per_page;

  function __construct() {

    // General
    self::$roles = array();
    self::$all_streams = '';
    self::$per_page = 20;

  }

}
new Defaults();
