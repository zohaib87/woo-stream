<?php
/**
 * Callback function for activation requests menu page.
 *
 * @package Woo Stream
 */

namespace Woo_Stream\Includes\Callbacks;

use Woo_Stream\Helpers\Helpers;
use Woo_Stream\Helpers\Views;

class Activation_Requests {

  public function html() {

    global $woost_opt;

    ?>
      <div class="wrap">
        <h1 class="wp-heading-inline"><?php echo esc_html__( 'Activation Requests', 'woo-stream' ); ?></h1>
        <hr class="wp-header-end">

        <div class="woost-alerts"></div>

        <table id="woost-table" class="woost-table">
          <thead>
            <tr>
              <th scope="col"><?php echo esc_html__( 'Username', 'woo-stream' ); ?></th>
              <th scope="col"><?php echo esc_html__( 'Email', 'woo-stream' ); ?></th>
              <th scope="col"><?php echo esc_html__( 'Full Name', 'woo-stream' ); ?></th>
              <th scope="col"><?php echo esc_html__( 'Request', 'woo-stream' ); ?></th>
            </tr>
          </thead>

          <tbody>
            <?php // Data will be added using ajax. ?>
          </tbody>
        </table>

        <div class="woost-paging-nav"></div>
      </div>
    <?php

  }

}
new Activation_Requests();
