<?php
/**
 * Functions that helps to ease plugin development.
 *
 * @package Woo Stream
 */

namespace Woo_Stream\Helpers;

class Helpers {

  /**
   * # Enqueue style or script with auto version control
   *
   * @link https://developer.wordpress.org/reference/functions/wp_enqueue_style/
   * @link https://developer.wordpress.org/reference/functions/wp_enqueue_script/
   *
   * @param string    $script     Accepts 'style' or 'script'
   * @param string    $handle     Name of the script. Should be unique.
   * @param string    $src        Path of the script relative to plugins folder.
   * @param array     $deps       An array of registered script handles this script depends on.
   * @param string    $media      The media for which this stylesheet has been defined.
   * @param bool      $in_footer  Whether to enqueue the script before </body> instead of in the <head>.
   * @param string    $ver        Version of the script.
   */
  public static function enqueue($script, $handle, $src = '', $deps = array(), $media = 'all', $in_footer = true, $ver = '') {

    $ver = empty($ver) ? filemtime(woo_stream_directory() . $src) : $ver;

    if ($script == 'style') {
      wp_enqueue_style( esc_attr($handle), woo_stream_directory_uri() . esc_attr($src), $deps, esc_attr($ver), esc_attr($media) );
    } elseif ($script == 'script') {
      wp_enqueue_script( esc_attr($handle), woo_stream_directory_uri() . esc_attr($src), $deps, esc_attr($ver), $in_footer);
    }

  }

  /**
   * # Minifying styles
   *
   * @param string  $css   Not compressed css.
   *
   * @return string of minified css.
   */
  public static function minify_css($css) {

    $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
    $css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);
    $css = str_replace(array('{ ', ' {'), '{', $css);
    $css = str_replace(array('} ', ' }'), '}', $css);
    $css = str_replace('; ', ';', $css);
    $css = str_replace(': ', ':', $css);
    $css = str_replace(', ', ',', $css);
    $css = str_replace(array('> ', ' >'), '>', $css);
    $css = str_replace(array('+ ', ' +'), '+', $css);
    $css = str_replace(array('~ ', ' ~'), '~', $css);
    $css = str_replace(';}', '}', $css);

    return $css;

  }

  /**
   * # Hex color to rgb conversion
   *
   * @param string  $color   Hex color code.
   *
   * @return string of RGB color.
   */
  public static function hex2rgb($color) {

    if ( $color[0] == '#' ) {
      $color = substr( $color, 1 );
    }
    if ( strlen( $color ) == 6 ) {
      list( $r, $g, $b ) = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
    } elseif ( strlen( $color ) == 3 ) {
      list( $r, $g, $b ) = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
    } else {
      return false;
    }

    $r = hexdec( $r );
    $g = hexdec( $g );
    $b = hexdec( $b );

    return $r.', '.$g.', '.$b;

  }

  /**
   * # Darken or Lighten Color
   *
   * @param string  $color  Hex color code.
   * @param int     $dif    Number amount of lightning or darkening.
   *
   * @return string of lighter or darker color.
   */
  public static function darken($color, $dif=20) {

    $color = str_replace('#','', $color);
    $rgb = '';

    if (strlen($color) != 6) {

      // reduce the default amount a little
      $dif = ($dif==20)?$dif/10:$dif;

      for ($x = 0; $x < 3; $x++) {

        $c = hexdec(substr($color,(1*$x),1)) - $dif;
        $c = ($c < 0) ? 0 : dechex($c);
        $rgb .= $c;

      }

    } else {

      for ($x = 0; $x < 3; $x++) {

        $c = hexdec(substr($color, (2*$x),2)) - $dif;
        $c = ($c < 0) ? 0 : dechex($c);
        $rgb .= (strlen($c) < 2) ? '0'.$c : $c;

      }

    }

    return '#'.$rgb;

  }

  /**
   * # Adjusting spacing of classes
   *
   * @param array   $classes   An array of classes
   *
   * @return string of classes with single space in between.
   */
  public static function classes( $classes = array() ) {

    $classes = implode(' ', $classes);
    $classes = trim( preg_replace('/\s+/', ' ', $classes) );

    return $classes;

  }

  /**
   * # Update post meta fields
   *
   * @param int     $post_id      Current post id
   * @param string  $name         Input name attribute
   * @param bool    $is_array     If the input name attribute is array or not
   * @param string  $validation   Sanitization type, accepts: 'text', 'intval', 'floatval', 'textarea', 'email', 'url'
   * @param string  $meta_key     Post meta key
   * @param string  $delete       If true, post meta will be deleted when the specified name attribute is not set.
   */
  public static function update_field($post_id, $name, $is_array, $validation, $meta_key, $delete = false) {

    if (!array_key_exists($name, $_POST) && $delete == false) {
      return;
    } elseif (!array_key_exists($name, $_POST) && $delete == true) {
      delete_post_meta($post_id, $meta_key);
      return;
    }

    if ($is_array == true) {

      switch ($validation) {

        case 'text' :
          $updated_val = array_map('sanitize_text_field', $_POST[$name]);
          break;

        case 'intval' :
          $updated_val = array_map('intval', $_POST[$name]);
          break;

        case 'floatval' :
          $updated_val = array_map('floatval', $_POST[$name]);
          break;

        case 'textarea' :
          $updated_val = array_map('sanitize_textarea_field', $_POST[$name]);
          break;

        case 'email' :
          $updated_val = array_map('sanitize_email', $_POST[$name]);
          break;

        case 'url' :
          $updated_val = array_map('sanitize_url', $_POST[$name]);
          break;

      }

    } else {

      switch ($validation) {

        case 'text' :
          $updated_val = sanitize_text_field($_POST[$name]);
          break;

        case 'intval' :
          $updated_val = intval($_POST[$name]);
          break;

        case 'floatval' :
          $updated_val = floatval($_POST[$name]);
          break;

        case 'textarea' :
          $updated_val = sanitize_textarea_field($_POST[$name]);
          break;

        case 'email' :
          $updated_val = sanitize_email($_POST[$name]);
          break;

        case 'url' :
          $updated_val = sanitize_url($_POST[$name]);
          break;

      }

    }
    update_post_meta( $post_id, $meta_key, $updated_val );

    return $updated_val;

  }

  /**
   * # Check for a value in array for multi select
   *
   * @param string  $needle   One of the values to compare.
   * @param array   $array      Array of values to check in.
   *
   * @return string HTML attribute or empty string.
   */
  public static function selected($needle, $array) {

    $needle = esc_attr($needle);
    $array = array_map('esc_attr', $array);

    if (in_array($needle, $array)) {
      return 'selected';
    } else {
      return '';
    }

  }

  /**
   * # Get facebook token expiry time
   *
   * @param string  $app_id         App ID from facebook developers.
   * @param string  $app_secret     App secret key from facebook developers.
   * @param string  $access_token   The access token which needs to be checked.
   *
   * @return string expiry time or error message.
   */
  public static function fb_token_expiry($app_id, $app_secret, $access_token) {

    $params = array(
      'input_token' => $access_token,
      'access_token' => $app_id . '|' . $app_secret
    );

    $request_url = 'https://graph.facebook.com/debug_token?' . http_build_query($params);
    $response = wp_remote_get( esc_url_raw( $request_url ) );

    if ( is_array( $response ) && ! is_wp_error( $response ) ) {

      $body = wp_remote_retrieve_body( $response );
      $data = json_decode( $body );

      $expiry_time = $data->data->expires_at;
      return ( $expiry_time == 0 ) ? esc_html__( 'Never', 'woo-stream' ) : date( 'd-m-Y h:i:s A', $expiry_time );

    } elseif ( is_wp_error( $response ) ) {

      return esc_html( $response->get_error_message() );

    }

  }

  /**
   * # Get facebook live videos.
   *
   * @param string  $access_token   A valid access token.
   *
   * @return object|string an object of live videos 'status', 'embed_html', 'id' or error message.
   */
  public static function fb_live_videos($access_token) {

    $params = array(
      'fields' => 'live_videos',
      'access_token' => $access_token
    );

    $request_url = 'https://graph.facebook.com/me?' . http_build_query($params);
    $response = wp_remote_get( esc_url_raw( $request_url ) );

    if ( is_array( $response ) && ! is_wp_error( $response ) ) {

      $body = wp_remote_retrieve_body( $response );
      $data = json_decode( $body );

      if ( isset($data->error) ) {

        return esc_html( $data->error->message );

      } else {

        return $data->live_videos->data;

      }

    } elseif ( is_wp_error( $response ) ) {

      return esc_html( $response->get_error_message() );

    }

  }

  /**
   * # Custom Page Navigation
   *
   * @param int   $count_around   Numbers of pages to show before and after the current.
   * @param int   $current        Current page number.
   * @param int   $count_pages    Total number of pages.
   *
   * @return string of HTML content
   */
  public static function paging_nav($count_around, $current, $count_pages) {

    $output = '';
    $is_gap = false; // A "gap" is the pages to skip
    $current--; // Current page number

    for ( $i = 0; $i < $count_pages; $i++ ) { // Run through pages

      $is_gap = false;

      // Are we at a gap? If beyond "count_around" and not first or last.
      if ( $count_around >= 0 && $i > 0 && $i < $count_pages - 1 && abs($i - $current) > $count_around ) {

        $is_gap = true;

        // Skip to next linked item (or last if we've already run past the current page)
        $i = ( $i < $current ? $current - $count_around : $count_pages - 1 ) - 1;

      }

      // If gap, write ellipsis, else page number
      if ( $is_gap ) {

        $lnk = '<span class="woost-nav-gap">...</span>';

      } else {

        $lnk = $i + 1;

      }

      // Do not link gaps and current
      if ( $i != $current && ! $is_gap ) {

        $lnk = '<a href="#" class="woost-nav-link">' . $lnk . '</a>';

      } elseif ( $i == $current && ! $is_gap ) {

        $lnk = '<a href="#" class="woost-nav-curr active">' . $lnk . '</a>';

      }

      $output .= $lnk;

    }

    $prev = '<a href="#" class="woost-nav-prev">&laquo;</a>';
    $next = '<a href="#" class="woost-nav-next">&raquo;</a>';

    return $prev . $output . $next;

  }

  /**
   * # Get src from iframe
   *
   * @param string $iframe  iframe from which src needs to be fetched.
   *
   * @return string of URL
   */
  public static function iframe_src( $iframe ) {

    preg_match( '/src="([^"]+)"/', $iframe, $src );
    $url = preg_replace( "/\width[^)]+\&/", '', $src[1] );
    $url = str_replace( 'www.', 'm.', $url );

    return $url;

  }

}
