/*--------------------------------------------------------------
# Main Js Start
--------------------------------------------------------------*/
( function ( $ ) {

  /**
   * # Alerts
   *
   * @param {string}  type      Accepts 'success', 'info', 'warning' and 'danger'.
   * @param {string}  msg       Message to the user.
   * @param {bool}    loader    Display CSS loading animation. (true/false).
   * @param {bool}    dismiss   Dismissible alert. (true/false).
   *
   * @returns html element of alert
   */
  function wooStreamAlert( type, msg, loader, dismiss ) {

    var loaderElem = '';
    var dismissElem = '';

    if ( typeof loader == 'undefined' || loader == false ) {
      // do nothing...
    } else if ( loader == true ) {
      loaderElem = '<div class="woost-loader"></div> ';
    }

    if ( typeof dismiss == 'undefined' || dismiss == false ) {
      // do nothing...
    } else if ( dismiss == true ) {
      dismissElem = '<button type="button" class="close" aria-label="Close">'+
        '<span aria-hidden="true">&times;</span>'+
      '</button>';
    }

    return '<div class="woost-alert woost-alert-'+type+' woost-alert-dismissible" role="alert">'+
      dismissElem+
      loaderElem+msg+
    '</div>';

  }

  // Close alert
  $( '.woost-alerts' ).on( 'click', '.woost-alert-dismissible span', function () {
    $( this ).closest( '.woost-alert' ).fadeOut( 400, function () {
      $( this ).remove();
    } );
  } );

  /**
   * # Data Loading Animation
   *
   * @param {string} message  The message that needs to be displayed to the user.
   *
   * @returns html element of loading animation.
   */
  function wooStreamLoadingData( message ) {

    if ( typeof message == 'undefined' ) {
      message = woostObj.dataLoadingMsg;
    }

    return '<div class="woost-loader"></div> <div class="woost-loading-txt">'+message+'</div>';

  }

	/**
   * # Saving facebook stream settings
   */
  $( '.woost-fb .woost-save' ).on( 'click', function () {

    var appId = $( '#app_id' ).val();
    var appSecret = $( '#app_secret' ).val();
    var accessToken = $( '#access_token' ).val();
    var pageId = $( '#page_id' ).val();
    var permanentToken = $( '#permanent_token' ).val();

    $.ajax( {
      url: woostObj.ajaxUrl,
      method: 'POST',
      dataType: 'json',
      data: {
        action: 'woo_stream_save_fb_settings',
        app_id: appId,
        app_secret: appSecret,
        access_token: accessToken,
        page_id: pageId,
        permanent_token: permanentToken,
        nonce: woostObj.nonce
      },
      beforeSend: function () {
        $( '.woost-fb .woost-btn' ).prop( 'disabled', true );
        $( '.woost-fb .woost-loader' ).show();
      },
      success: function (data) {

        if ( data.error != '' ) {

          $( '.woost-alerts' ).html( wooStreamAlert( 'danger', data.error, false, true ) );

        } else {

          $( '.woost-alerts' ).html( wooStreamAlert( 'success', data.success, false, true ) );
          $( '.woost-short-token-expiry' ).html( data.expiry );

        }

        location.href = '#woost-alerts';

        setTimeout( function () {
          $( '.woost-alerts' ).html('');
        }, 30000);

        $( '.woost-fb .woost-btn' ).prop( 'disabled', false );
        $( '.woost-fb .woost-loader' ).hide();

      },
      error: function (error) {
        console.log(error);
        $( '.woost-fb .woost-btn' ).prop( 'disabled', false );
        $( '.woost-fb .woost-loader' ).hide();
      }
    } );

  } );

  /**
   * # Fetch facebook user/page ID & token
   */
  $( '.woost-fb .woost-fetch' ).on( 'click', function () {

    var appId = $( '#app_id' ).val();
    var appSecret = $( '#app_secret' ).val();
    var accessToken = $( '#access_token' ).val();

    $.ajax( {
      url: woostObj.ajaxUrl,
      method: 'POST',
      dataType: 'json',
      data: {
        action: 'woo_stream_fetch_token',
        app_id: appId,
        app_secret: appSecret,
        access_token: accessToken,
        nonce: woostObj.nonce
      },
      beforeSend: function () {
        $( '.woost-fb .woost-btn' ).prop( 'disabled', true );
        $( '.woost-fb .woost-loader' ).show();
      },
      success: function (data) {

        if ( data.error != '' ) {

          $( '.woost-alerts' ).html( wooStreamAlert( 'danger', data.error, false, true ) );

        } else {

          $( '.woost-alerts' ).html( wooStreamAlert( 'success', data.success, false, true ) );
          $( '#page_id' ).val( data.pageId );
          $( '#permanent_token' ).val( data.permanentToken );

        }

        location.href = '#woost-alerts';

        setTimeout( function () {
          $( '.woost-alerts' ).html('');
        }, 30000);

        $( '.woost-fb .woost-btn' ).prop( 'disabled', false );
        $( '.woost-fb .woost-loader' ).hide();

      },
      error: function (error) {
        console.log(error);
        $( '.woost-fb .woost-btn' ).prop( 'disabled', false );
        $( '.woost-fb .woost-loader' ).hide();
      }
    } );

  } );

  /**
   * # Test facebook api connection
   */
  $( '.woost-fb .woost-test' ).on( 'click', function () {

    var pageId = $( '#page_id' ).val();
    var permanentToken = $( '#permanent_token' ).val();

    $.ajax( {
      url: woostObj.ajaxUrl,
      method: 'POST',
      dataType: 'json',
      data: {
        action: 'woo_stream_test_fb_connection',
        page_id: pageId,
        permanent_token: permanentToken,
        nonce: woostObj.nonce
      },
      beforeSend: function () {
        $( '.woost-fb .woost-btn' ).prop( 'disabled', true );
        $( '.woost-fb .woost-loader' ).show();
      },
      success: function (data) {

        if ( data.error != '' ) {

          $( '.woost-alerts' ).html( wooStreamAlert( 'danger', data.error, false, true ) );

        } else {

          $( '.woost-alerts' ).html( wooStreamAlert( 'success', data.success, false, true ) );

        }

        location.href = '#woost-alerts';

        setTimeout( function () {
          $( '.woost-alerts' ).html('');
        }, 30000);

        $( '.woost-fb .woost-btn' ).prop( 'disabled', false );
        $( '.woost-fb .woost-loader' ).hide();

      },
      error: function (error) {
        console.log(error);
        $( '.woost-fb .woost-btn' ).prop( 'disabled', false );
        $( '.woost-fb .woost-loader' ).hide();
      }
    } );

  } );

  /**
   * # Paging nav to load videos.
   *
   * @param {string}  currElement   Class, ID or this can be passed.
   */
  function wooStreamLoadVideos(currElement) {

    var pageNo = 1;

    if (typeof currElement == 'undefined') {
      // do nothing...
    } else {
      pageNo = Number( $(currElement).text() );
    }

    // Check for previous button click
    if ( $(currElement).hasClass( 'woost-nav-prev' ) )  {

      pageNo = Number( $( '.woost-nav-curr' ).text() );

      if ( pageNo == 1 ) {
        return false;
      } else {
        pageNo--;
      }

    }

    // Check for next button click
    if ( $(currElement).hasClass( 'woost-nav-next' ) )  {

      pageNo = Number( $( '.woost-nav-curr' ).text() );
      lastPage = Number( $( '.woost-nav-next' ).prev().text() );

      if ( pageNo == lastPage ) {
        return false;
      } else {
        pageNo++;
      }

    }

    /**
     * # Get parameters from URL
     *
     * @link https://stackoverflow.com/a/42353290/4457943
     */
    var params = new URLSearchParams(window.location.search);
    var vendor = params.get('vendor');

    /**
     * # Change URL parameters
     *
     * @link https://stackoverflow.com/a/19279428/4457943
     */
    if ( history.pushState ) {

      var newUrl = window.location.protocol + '//' + window.location.host + window.location.pathname + '?vendor='+vendor+'&page_no='+pageNo;
      window.history.pushState( { path: newUrl }, '', newUrl );

    }

    $.ajax({
      url: woostObj.ajaxUrl,
      method: 'POST',
      dataType: 'json',
      data: {
        action: 'woo_stream_load_videos',
        page_no: pageNo,
        vendor: $( '#woost_vendor' ).val(),
        nonce: woostObj.nonce
      },
      beforeSend: function () {
        $( '.woost-row' ).html('');
        $( '.woost-loader-cont' ).show();
      },
      success: function (data) {

        if ( data.error != '' ) {

          $( '.woost-row' ).html( wooStreamAlert( 'danger', data.error, false, true ) );

        } else {

          $( '.woost-loader-cont' ).hide();
          $( '.woost-row' ).html( data.success );
          $( '.woost-paging-nav' ).html( data.paging );

        }

      },
      error: function (error) {
        console.log(error);
        $( '.woost-loader-cont' ).hide();
      }
    });

  }

  $( '.woost-paging-nav' ).on( 'click', 'a', function (e) {

    e.preventDefault();
    if ( $(this).hasClass('active') ) return false;
    wooStreamLoadVideos(this);

  } );

  /**
   * # Carousel of random stream videos
   *
   * @link https://github.com/ganlanyuan/tiny-slider
   */
  if ( $( '.woost-carousel' )[0] ) {

    var show = $( '.woost-carousel' ).data('show');

    var slider = tns( {
      container: '.woost-carousel',
      items: 1,
      responsive: {
        576: {
          items: 2
        },
        992: {
          items: 3
        },
        1200: {
          items: show
        }
      },
      lazyLoad: true,
      mouseDrag: true,
      swipeAngle: false,
      autoplay: true
    } );

  }

  /**
   * # Streaming service activation
   */
  $( '.woost-general .woost-activate' ).on( 'click', function (e) {

    e.preventDefault();

    $.ajax( {
      url: woostObj.ajaxUrl,
      method: 'POST',
      dataType: 'json',
      data: {
        action: 'woo_stream_activation_request',
        nonce: woostObj.nonce
      },
      beforeSend: function () {
        $( '.woost-general .woost-activate' ).text('Sending Request').prop( 'disabled', true );
        $( '.woost-general .woost-loader' ).show();
      },
      success: function (data) {

        if ( data.error != '' ) {

          $( '.woost-alerts' ).html( wooStreamAlert( 'danger', data.error, false, true ) );

        } else {

          $( '.woost-alerts' ).html( wooStreamAlert( 'info', data.success, false, true ) );

        }

        $( '.woost-general .woost-activate' ).text(data.buttonText);

        setTimeout( function () {
          $( '.woost-alerts' ).html('');
        }, 30000);

        $( '.woost-general .woost-loader' ).hide();

      },
      error: function (error) {
        console.log(error);
        $( '.woost-general .woost-activate' ).text('Activate Streaming').prop( 'disabled', false );
        $( '.woost-general .woost-loader' ).hide();
      }
    } );

  } );

} )( jQuery );
