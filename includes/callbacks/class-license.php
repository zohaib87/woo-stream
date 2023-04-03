<?php
/**
 * Callback and redirect functions for plugin license.
 *
 * @package Woo Stream
 */

namespace Woo_Stream\Includes\Callbacks;

class License {

  function __construct() {
    add_action( 'admin_init', [ $this, 'redirect' ], 1 );
  }

  public function html() {}

  public function redirect() {

    if ( isset($_GET['page']) && $_GET['page'] == 'woo-stream-license' && woo_stream_fs()->is_paying() ) {

      wp_redirect( menu_page_url('woo-stream-license-account') );

      exit();

    }

  }

}
new License();
