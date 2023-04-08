<?php
/**
 * Callback function for Options menu page.
 *
 * @package Woo Stream
 */

namespace Woo_Stream\Includes\Callbacks;

use Woo_Stream\Helpers\Helpers as Helper;
use Woo_Stream\Helpers\Views as View;

class Plugin_Options {

  function __construct() {

    add_action('admin_init', [$this, 'register_options']);

  }

  public function html() {

    global $woost_opt;

    ?>
      <div class="wrap">
        <h1><?php echo esc_html__( 'Plugin Options', 'woo-stream' ); ?></h1>

        <?php
          settings_errors();
          $active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'general';
        ?>

        <h2 class="nav-tab-wrapper">
          <?php
            View::plugin_option_tabs( [
              array( esc_html__( 'General Options', 'woo-stream' ), 'general', $active_tab )
            ] );
          ?>
        </h2>

        <form method="post" action="options.php" enctype="multipart/form-data">

          <?php
            settings_fields('woost_options');
            do_settings_sections('woost_options');
          ?>

          <!-- # General Tab -->
          <table class="form-table" role="presentation" style="<?php echo ($active_tab !== 'general') ? 'display: none;' : ''; ?>">
            <tbody>
              <!-- Roles -->
              <tr>
                <th scope="row">
                  <label for="woost_roles"><?php echo esc_html__( 'Enable for Roles', 'woo-stream' ); ?></label>
                </th>
                <td>
                  <select name="woost_roles[]" id="woost_roles" multiple>
                    <?php
                      $roles = wp_roles()->roles;

                      foreach ($roles as $key => $value) {

                        if ( $key == 'administrator' ) continue;

                        echo '<option value="' . esc_attr($key) . '" ' . Helper::selected($key, $woost_opt->roles) . '>' . esc_html($value['name']) . '</option>';

                      }
                    ?>
                  </select>
                </td>
              </tr>

              <!-- Streams Page -->
              <tr>
                <th scope="row">
                  <label for="woost_all_streams"><?php echo esc_html__( 'Streams Page', 'woo-stream' ); ?></label>
                </th>
                <td>
                  <?php
                    $get_pages = get_posts( [
                      'post_type' => 'page',
                      'posts_per_page' => -1
                    ] );

                    if ( $get_pages ) {

                      ?>
                        <select name="woost_all_streams" id="woost_all_stream" class="woost-select2">
                          <option value="nonce" <?php selected( $woost_opt->all_streams, 'none' ); ?>><?php echo esc_html__( 'Select a Page', 'woo-stream' ); ?></option>
                      <?php

                      foreach ($get_pages as $the_page) {

                        ?>
                          <option value="<?php echo esc_attr($the_page->ID); ?>" <?php selected( $woost_opt->all_streams, $the_page->ID ); ?>><?php echo esc_html($the_page->post_title); ?></option>
                        <?php

                      }

                      ?>
                        </select>
                      <?php

                    } else {

                      ?>
                        <input type="text" name="woost_all_streams" id="woost_all_streams" value="No Pages Found." disabled>
                      <?php

                    }
                  ?>
                </td>
              </tr>

              <!-- Items Per Page -->
              <tr>
                <th scope="row">
                  <label for="woost_per_page"><?php echo esc_html__( 'Items Per Page', 'woo-stream' ); ?></label>
                </th>
                <td>
                  <input type="number" name="woost_per_page" id="woost_per_page" value="<?php echo esc_attr( $woost_opt->per_page ); ?>">
                </td>
              </tr>
            </tbody>
          </table>

          <?php submit_button(); ?>
        </form>
      </div>
    <?php

  }

  /**
   * # Register settings
   */
  public function register_options() {

    $options = [
      'woost_roles',
      'woost_all_streams',
      'woost_per_page'
    ];

    foreach ($options as $option) {
      register_setting('woost_options', $option);
    }

  }

}
new Plugin_Options();
